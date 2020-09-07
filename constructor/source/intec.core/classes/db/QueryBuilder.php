<?php
namespace intec\core\db;

use intec\core\base\BaseObject;
use intec\core\base\InvalidParamException;
use intec\core\base\NotSupportedException;
use intec\core\helpers\ArrayHelper;

/**
 * Class QueryBuilder
 * @package intec\core\db
 * @since 1.0.0
 */
class QueryBuilder extends BaseObject
{
    /**
     * @since 1.0.0
     */
    const PARAM_PREFIX = ':qp';

    /**
     * @var Connection
     * @since 1.0.0
     */
    public $db;
    /**
     * @var string
     * @since 1.0.0
     */
    public $separator = ' ';
    /**
     * @var array
     * @since 1.0.0
     */
    public $typeMap = [];

    /**
     * @var array
     * @since 1.0.0
     */
    protected $conditionBuilders = [
        'NOT' => 'buildNotCondition',
        'AND' => 'buildAndCondition',
        'OR' => 'buildAndCondition',
        'BETWEEN' => 'buildBetweenCondition',
        'NOT BETWEEN' => 'buildBetweenCondition',
        'IN' => 'buildInCondition',
        'NOT IN' => 'buildInCondition',
        'LIKE' => 'buildLikeCondition',
        'NOT LIKE' => 'buildLikeCondition',
        'OR LIKE' => 'buildLikeCondition',
        'OR NOT LIKE' => 'buildLikeCondition',
        'EXISTS' => 'buildExistsCondition',
        'NOT EXISTS' => 'buildExistsCondition',
    ];


    /**
     * Constructor.
     * @param Connection $connection
     * @param array $config
     * @since 1.0.0
     */
    public function __construct($connection, $config = [])
    {
        $this->db = $connection;
        parent::__construct($config);
    }

    /**
     * @param Query $query
     * @param array $params
     * @return array
     * @since 1.0.0
     */
    public function build($query, $params = [])
    {
        $query = $query->prepare($this);

        $params = empty($params) ? $query->params : array_merge($params, $query->params);

        $clauses = [
            $this->buildSelect($query->select, $params, $query->distinct, $query->selectOption),
            $this->buildFrom($query->from, $params),
            $this->buildJoin($query->join, $params),
            $this->buildWhere($query->where, $params),
            $this->buildGroupBy($query->groupBy),
            $this->buildHaving($query->having, $params),
        ];

        $sql = implode($this->separator, array_filter($clauses));
        $sql = $this->buildOrderByAndLimit($sql, $query->orderBy, $query->limit, $query->offset);

        if (!empty($query->orderBy)) {
            foreach ($query->orderBy as $expression) {
                if ($expression instanceof Expression) {
                    $params = array_merge($params, $expression->params);
                }
            }
        }
        if (!empty($query->groupBy)) {
            foreach ($query->groupBy as $expression) {
                if ($expression instanceof Expression) {
                    $params = array_merge($params, $expression->params);
                }
            }
        }

        $union = $this->buildUnion($query->union, $params);
        if ($union !== '') {
            $sql = "($sql){$this->separator}$union";
        }

        return [$sql, $params];
    }

    /**
     * @param string $table
     * @param array|Query $columns
     * @param array $params
     * @return string
     * @since 1.0.0
     */
    public function insert($table, $columns, &$params)
    {
        $schema = $this->db->getSchema();
        if (($tableSchema = $schema->getTableSchema($table)) !== null) {
            $columnSchemas = $tableSchema->columns;
        } else {
            $columnSchemas = [];
        }
        $names = [];
        $placeholders = [];
        $values = ' DEFAULT VALUES';
        if ($columns instanceof Query) {
            list($names, $values) = $this->prepareInsertSelectSubQuery($columns, $schema);
        } else {
            foreach ($columns as $name => $value) {
                $names[] = $schema->quoteColumnName($name);
                if ($value instanceof Expression) {
                    $placeholders[] = $value->expression;
                    foreach ($value->params as $n => $v) {
                        $params[$n] = $v;
                    }
                } elseif ($value instanceof Query) {
                    list($sql, $params) = $this->build($value, $params);
                    $placeholders[] = "($sql)";
                } else {
                    $phName = self::PARAM_PREFIX . count($params);
                    $placeholders[] = $phName;
                    $params[$phName] = !is_array($value) && isset($columnSchemas[$name]) ? $columnSchemas[$name]->dbTypecast($value) : $value;
                }
            }
        }

        return 'INSERT INTO ' . $schema->quoteTableName($table)
            . (!empty($names) ? ' (' . implode(', ', $names) . ')' : '')
            . (!empty($placeholders) ? ' VALUES (' . implode(', ', $placeholders) . ')' : $values);
    }

    /**
     * @param Query $columns
     * @param Schema $schema
     * @return array
     * @since 1.0.0
     */
    protected function prepareInsertSelectSubQuery($columns, $schema)
    {
        if (!is_array($columns->select) || empty($columns->select) || in_array('*', $columns->select)) {
            throw new InvalidParamException('Expected select query object with enumerated (named) parameters');
        }

        list ($values, ) = $this->build($columns);
        $names = [];
        $values = ' ' . $values;
        foreach ($columns->select as $title => $field) {
            if (is_string($title)) {
                $names[] = $title;
            } else if (preg_match('/^(.*?)(?i:\s+as\s+|\s+)([\w\-_\.]+)$/', $field, $matches)) {
                $names[] = $schema->quoteColumnName($matches[2]);
            } else {
                $names[] = $schema->quoteColumnName($field);
            }
        }

        return [$names, $values];
    }

    /**
     * @param string $table
     * @param array $columns
     * @param array $rows
     * @return string
     * @since 1.0.0
     */
    public function batchInsert($table, $columns, $rows)
    {
        if (empty($rows)) {
            return '';
        }

        $schema = $this->db->getSchema();
        if (($tableSchema = $schema->getTableSchema($table)) !== null) {
            $columnSchemas = $tableSchema->columns;
        } else {
            $columnSchemas = [];
        }

        $values = [];
        foreach ($rows as $row) {
            $vs = [];
            foreach ($row as $i => $value) {
                if (isset($columns[$i], $columnSchemas[$columns[$i]]) && !is_array($value)) {
                    $value = $columnSchemas[$columns[$i]]->dbTypecast($value);
                }
                if (is_string($value)) {
                    $value = $schema->quoteValue($value);
                } elseif ($value === false) {
                    $value = 0;
                } elseif ($value === null) {
                    $value = 'NULL';
                }
                $vs[] = $value;
            }
            $values[] = '(' . implode(', ', $vs) . ')';
        }

        foreach ($columns as $i => $name) {
            $columns[$i] = $schema->quoteColumnName($name);
        }

        return 'INSERT INTO ' . $schema->quoteTableName($table)
        . ' (' . implode(', ', $columns) . ') VALUES ' . implode(', ', $values);
    }

    /**
     * @param string $table
     * @param array $columns
     * @param array|string $condition
     * @param array $params
     * @return string
     * @since 1.0.0
     */
    public function update($table, $columns, $condition, &$params)
    {
        if (($tableSchema = $this->db->getTableSchema($table)) !== null) {
            $columnSchemas = $tableSchema->columns;
        } else {
            $columnSchemas = [];
        }

        $lines = [];
        foreach ($columns as $name => $value) {
            if ($value instanceof Expression) {
                $lines[] = $this->db->quoteColumnName($name) . '=' . $value->expression;
                foreach ($value->params as $n => $v) {
                    $params[$n] = $v;
                }
            } else {
                $phName = self::PARAM_PREFIX . count($params);
                $lines[] = $this->db->quoteColumnName($name) . '=' . $phName;
                $params[$phName] = !is_array($value) && isset($columnSchemas[$name]) ? $columnSchemas[$name]->dbTypecast($value) : $value;
            }
        }

        $sql = 'UPDATE ' . $this->db->quoteTableName($table) . ' SET ' . implode(', ', $lines);
        $where = $this->buildWhere($condition, $params);

        return $where === '' ? $sql : $sql . ' ' . $where;
    }

    /**
     * @param string $table
     * @param array|string $condition
     * @param array $params
     * @return string
     * @since 1.0.0
     */
    public function delete($table, $condition, &$params)
    {
        $sql = 'DELETE FROM ' . $this->db->quoteTableName($table);
        $where = $this->buildWhere($condition, $params);

        return $where === '' ? $sql : $sql . ' ' . $where;
    }

    /**
     * @param string $table
     * @param array $columns
     * @param string $options
     * @return string
     * @since 1.0.0
     */
    public function createTable($table, $columns, $options = null)
    {
        $cols = [];
        foreach ($columns as $name => $type) {
            if (is_string($name)) {
                $cols[] = "\t" . $this->db->quoteColumnName($name) . ' ' . $this->getColumnType($type);
            } else {
                $cols[] = "\t" . $type;
            }
        }
        $sql = 'CREATE TABLE ' . $this->db->quoteTableName($table) . " (\n" . implode(",\n", $cols) . "\n)";

        return $options === null ? $sql : $sql . ' ' . $options;
    }

    /**
     * @param string $oldName
     * @param string $newName
     * @return string
     * @since 1.0.0
     */
    public function renameTable($oldName, $newName)
    {
        return 'RENAME TABLE ' . $this->db->quoteTableName($oldName) . ' TO ' . $this->db->quoteTableName($newName);
    }

    /**
     * @param string $table
     * @return string
     * @since 1.0.0
     */
    public function dropTable($table)
    {
        return 'DROP TABLE ' . $this->db->quoteTableName($table);
    }

    /**
     * @param string $name
     * @param string $table
     * @param string|array
     * @return string
     * @since 1.0.0
     */
    public function addPrimaryKey($name, $table, $columns)
    {
        if (is_string($columns)) {
            $columns = preg_split('/\s*,\s*/', $columns, -1, PREG_SPLIT_NO_EMPTY);
        }

        foreach ($columns as $i => $col) {
            $columns[$i] = $this->db->quoteColumnName($col);
        }

        return 'ALTER TABLE ' . $this->db->quoteTableName($table) . ' ADD CONSTRAINT '
            . $this->db->quoteColumnName($name) . '  PRIMARY KEY ('
            . implode(', ', $columns). ' )';
    }

    /**
     * @param string $name
     * @param string $table
     * @return string
     */
    public function dropPrimaryKey($name, $table)
    {
        return 'ALTER TABLE ' . $this->db->quoteTableName($table)
            . ' DROP CONSTRAINT ' . $this->db->quoteColumnName($name);
    }

    /**
     * @param string $table
     * @return string
     */
    public function truncateTable($table)
    {
        return 'TRUNCATE TABLE ' . $this->db->quoteTableName($table);
    }

    /**
     * @param string $table
     * @param string $column
     * @param string $type
     * @return string
     * @since 1.0.0
     */
    public function addColumn($table, $column, $type)
    {
        return 'ALTER TABLE ' . $this->db->quoteTableName($table)
            . ' ADD ' . $this->db->quoteColumnName($column) . ' '
            . $this->getColumnType($type);
    }

    /**
     * @param string $table
     * @param string $column
     * @return string
     * @since 1.0.0
     */
    public function dropColumn($table, $column)
    {
        return 'ALTER TABLE ' . $this->db->quoteTableName($table)
            . ' DROP COLUMN ' . $this->db->quoteColumnName($column);
    }

    /**
     * @param string $table
     * @param string $oldName
     * @param string $newName
     * @return string
     * @since 1.0.0
     */
    public function renameColumn($table, $oldName, $newName)
    {
        return 'ALTER TABLE ' . $this->db->quoteTableName($table)
            . ' RENAME COLUMN ' . $this->db->quoteColumnName($oldName)
            . ' TO ' . $this->db->quoteColumnName($newName);
    }

    /**
     * @param string $table
     * @param string $column
     * @param string $type
     * @return string
     * @since 1.0.0
     */
    public function alterColumn($table, $column, $type)
    {
        return 'ALTER TABLE ' . $this->db->quoteTableName($table) . ' CHANGE '
            . $this->db->quoteColumnName($column) . ' '
            . $this->db->quoteColumnName($column) . ' '
            . $this->getColumnType($type);
    }

    /**
     * @param string $name
     * @param string $table
     * @param string|array
     * @param string $refTable
     * @param string|array $refColumns
     * @param string $delete
     * @param string $update
     * @return string
     * @since 1.0.0
     */
    public function addForeignKey($name, $table, $columns, $refTable, $refColumns, $delete = null, $update = null)
    {
        $sql = 'ALTER TABLE ' . $this->db->quoteTableName($table)
            . ' ADD CONSTRAINT ' . $this->db->quoteColumnName($name)
            . ' FOREIGN KEY (' . $this->buildColumns($columns) . ')'
            . ' REFERENCES ' . $this->db->quoteTableName($refTable)
            . ' (' . $this->buildColumns($refColumns) . ')';
        if ($delete !== null) {
            $sql .= ' ON DELETE ' . $delete;
        }
        if ($update !== null) {
            $sql .= ' ON UPDATE ' . $update;
        }

        return $sql;
    }

    /**
     * @param string $name
     * @param string $table
     * @return string
     * @since 1.0.0
     */
    public function dropForeignKey($name, $table)
    {
        return 'ALTER TABLE ' . $this->db->quoteTableName($table)
            . ' DROP CONSTRAINT ' . $this->db->quoteColumnName($name);
    }

    /**
     * @param string $name
     * @param string $table
     * @param string|array $columns
     * @param bool $unique
     * @return string
     * @since 1.0.0
     */
    public function createIndex($name, $table, $columns, $unique = false)
    {
        return ($unique ? 'CREATE UNIQUE INDEX ' : 'CREATE INDEX ')
            . $this->db->quoteTableName($name) . ' ON '
            . $this->db->quoteTableName($table)
            . ' (' . $this->buildColumns($columns) . ')';
    }

    /**
     * @param string $name
     * @param string $table
     * @return string
     * @since 1.0.0
     */
    public function dropIndex($name, $table)
    {
        return 'DROP INDEX ' . $this->db->quoteTableName($name) . ' ON ' . $this->db->quoteTableName($table);
    }

    /**
     * @param string $table
     * @param array|string $value
     * @return string
     * @throws NotSupportedException
     * @since 1.0.0
     */
    public function resetSequence($table, $value = null)
    {
        throw new NotSupportedException($this->db->getDriverName() . ' does not support resetting sequence.');
    }

    /**
     * @param bool $check
     * @param string $schema
     * @param string $table
     * @return string
     * @throws NotSupportedException
     * @since 1.0.0
     */
    public function checkIntegrity($check = true, $schema = '', $table = '')
    {
        throw new NotSupportedException($this->db->getDriverName() . ' does not support enabling/disabling integrity check.');
    }

    /**
     * @param string $table
     * @param string $column
     * @param string $comment
     * @return string
     * @since 1.0.0
     */
    public function addCommentOnColumn($table, $column, $comment)
    {

        return 'COMMENT ON COLUMN ' . $this->db->quoteTableName($table) . '.' . $this->db->quoteColumnName($column) . ' IS ' . $this->db->quoteValue($comment);
    }

    /**
     * @param string $table
     * @param string $comment
     * @return string
     * @since 1.0.0
     */
    public function addCommentOnTable($table, $comment)
    {
        return 'COMMENT ON TABLE ' . $this->db->quoteTableName($table) . ' IS ' . $this->db->quoteValue($comment);
    }

    /**
     * @param string $table
     * @param string $column
     * @return string
     * @since 1.0.0
     */
    public function dropCommentFromColumn($table, $column)
    {
        return 'COMMENT ON COLUMN ' . $this->db->quoteTableName($table) . '.' . $this->db->quoteColumnName($column) . ' IS NULL';
    }

    /**
     * @param string $table
     * @return string
     * @since 1.0.0
     */
    public function dropCommentFromTable($table)
    {
        return 'COMMENT ON TABLE ' . $this->db->quoteTableName($table) . ' IS NULL';
    }

    /**
     * @param string|ColumnSchemaBuilder $type
     * @return string
     * @since 1.0.0
     */
    public function getColumnType($type)
    {
        if ($type instanceof ColumnSchemaBuilder) {
            $type = $type->__toString();
        }

        if (isset($this->typeMap[$type])) {
            return $this->typeMap[$type];
        } elseif (preg_match('/^(\w+)\((.+?)\)(.*)$/', $type, $matches)) {
            if (isset($this->typeMap[$matches[1]])) {
                return preg_replace('/\(.+\)/', '(' . $matches[2] . ')', $this->typeMap[$matches[1]]) . $matches[3];
            }
        } elseif (preg_match('/^(\w+)\s+/', $type, $matches)) {
            if (isset($this->typeMap[$matches[1]])) {
                return preg_replace('/^\w+/', $this->typeMap[$matches[1]], $type);
            }
        }

        return $type;
    }

    /**
     * @param array $columns
     * @param array $params
     * @param bool $distinct
     * @param string $selectOption
     * @return string
     * @since 1.0.0
     */
    public function buildSelect($columns, &$params, $distinct = false, $selectOption = null)
    {
        $select = $distinct ? 'SELECT DISTINCT' : 'SELECT';
        if ($selectOption !== null) {
            $select .= ' ' . $selectOption;
        }

        if (empty($columns)) {
            return $select . ' *';
        }

        foreach ($columns as $i => $column) {
            if ($column instanceof Expression) {
                if (is_int($i)) {
                    $columns[$i] = $column->expression;
                } else {
                    $columns[$i] = $column->expression . ' AS ' . $this->db->quoteColumnName($i);
                }
                $params = array_merge($params, $column->params);
            } elseif ($column instanceof Query) {
                list($sql, $params) = $this->build($column, $params);
                $columns[$i] = "($sql) AS " . $this->db->quoteColumnName($i);
            } elseif (is_string($i)) {
                if (strpos($column, '(') === false) {
                    $column = $this->db->quoteColumnName($column);
                }
                $columns[$i] = "$column AS " . $this->db->quoteColumnName($i);
            } elseif (strpos($column, '(') === false) {
                if (preg_match('/^(.*?)(?i:\s+as\s+|\s+)([\w\-_\.]+)$/', $column, $matches)) {
                    $columns[$i] = $this->db->quoteColumnName($matches[1]) . ' AS ' . $this->db->quoteColumnName($matches[2]);
                } else {
                    $columns[$i] = $this->db->quoteColumnName($column);
                }
            }
        }

        return $select . ' ' . implode(', ', $columns);
    }

    /**
     * @param array $tables
     * @param array $params
     * @return string
     * @since 1.0.0
     */
    public function buildFrom($tables, &$params)
    {
        if (empty($tables)) {
            return '';
        }

        $tables = $this->quoteTableNames($tables, $params);

        return 'FROM ' . implode(', ', $tables);
    }

    /**
     * @param array $joins
     * @param array $params
     * @return string
     * @throws Exception
     * @since 1.0.0
     */
    public function buildJoin($joins, &$params)
    {
        if (empty($joins)) {
            return '';
        }

        foreach ($joins as $i => $join) {
            if (!is_array($join) || !isset($join[0], $join[1])) {
                throw new Exception('A join clause must be specified as an array of join type, join table, and optionally join condition.');
            }
            // 0:join type, 1:join table, 2:on-condition (optional)
            list ($joinType, $table) = $join;
            $tables = $this->quoteTableNames((array) $table, $params);
            $table = reset($tables);
            $joins[$i] = "$joinType $table";
            if (isset($join[2])) {
                $condition = $this->buildCondition($join[2], $params);
                if ($condition !== '') {
                    $joins[$i] .= ' ON ' . $condition;
                }
            }
        }

        return implode($this->separator, $joins);
    }

    /**
     * @param array $tables
     * @param array $params
     * @return array
     * @since 1.0.0
     */
    private function quoteTableNames($tables, &$params)
    {
        foreach ($tables as $i => $table) {
            if ($table instanceof Query) {
                list($sql, $params) = $this->build($table, $params);
                $tables[$i] = "($sql) " . $this->db->quoteTableName($i);
            } elseif (is_string($i)) {
                if (strpos($table, '(') === false) {
                    $table = $this->db->quoteTableName($table);
                }
                $tables[$i] = "$table " . $this->db->quoteTableName($i);
            } elseif (strpos($table, '(') === false) {
                if (preg_match('/^(.*?)(?i:\s+as|)\s+([^ ]+)$/', $table, $matches)) { // with alias
                    $tables[$i] = $this->db->quoteTableName($matches[1]) . ' ' . $this->db->quoteTableName($matches[2]);
                } else {
                    $tables[$i] = $this->db->quoteTableName($table);
                }
            }
        }
        return $tables;
    }

    /**
     * @param string|array $condition
     * @param array $params
     * @return string
     * @since 1.0.0
     */
    public function buildWhere($condition, &$params)
    {
        $where = $this->buildCondition($condition, $params);

        return $where === '' ? '' : 'WHERE ' . $where;
    }

    /**
     * @param array $columns
     * @return string
     * @since 1.0.0
     */
    public function buildGroupBy($columns)
    {
        if (empty($columns)) {
            return '';
        }
        foreach ($columns as $i => $column) {
            if ($column instanceof Expression) {
                $columns[$i] = $column->expression;
            } elseif (strpos($column, '(') === false) {
                $columns[$i] = $this->db->quoteColumnName($column);
            }
        }
        return 'GROUP BY ' . implode(', ', $columns);
    }

    /**
     * @param string|array $condition
     * @param array $params
     * @return string
     * @since 1.0.0
     */
    public function buildHaving($condition, &$params)
    {
        $having = $this->buildCondition($condition, $params);

        return $having === '' ? '' : 'HAVING ' . $having;
    }

    /**
     * @param string $sql
     * @param array $orderBy
     * @param int $limit
     * @param int $offset
     * @return string
     * @since 1.0.0
     */
    public function buildOrderByAndLimit($sql, $orderBy, $limit, $offset)
    {
        $orderBy = $this->buildOrderBy($orderBy);
        if ($orderBy !== '') {
            $sql .= $this->separator . $orderBy;
        }
        $limit = $this->buildLimit($limit, $offset);
        if ($limit !== '') {
            $sql .= $this->separator . $limit;
        }
        return $sql;
    }

    /**
     * @param array $columns
     * @return string
     * @since 1.0.0
     */
    public function buildOrderBy($columns)
    {
        if (empty($columns)) {
            return '';
        }
        $orders = [];
        foreach ($columns as $name => $direction) {
            if ($direction instanceof Expression) {
                $orders[] = $direction->expression;
            } else {
                $orders[] = $this->db->quoteColumnName($name) . ($direction === SORT_DESC ? ' DESC' : '');
            }
        }

        return 'ORDER BY ' . implode(', ', $orders);
    }

    /**
     * @param int $limit
     * @param int $offset
     * @return string
     * @since 1.0.0
     */
    public function buildLimit($limit, $offset)
    {
        $sql = '';
        if ($this->hasLimit($limit)) {
            $sql = 'LIMIT ' . $limit;
        }
        if ($this->hasOffset($offset)) {
            $sql .= ' OFFSET ' . $offset;
        }

        return ltrim($sql);
    }

    /**
     * @param mixed $limit
     * @return bool
     * @since 1.0.0
     */
    protected function hasLimit($limit)
    {
        return ctype_digit((string) $limit);
    }

    /**
     * @param mixed $offset
     * @return bool
     * @since 1.0.0
     */
    protected function hasOffset($offset)
    {
        $offset = (string) $offset;
        return ctype_digit($offset) && $offset !== '0';
    }

    /**
     * @param array $unions
     * @param array $params
     * @return string
     * @since 1.0.0
     */
    public function buildUnion($unions, &$params)
    {
        if (empty($unions)) {
            return '';
        }

        $result = '';

        foreach ($unions as $i => $union) {
            $query = $union['query'];
            if ($query instanceof Query) {
                list($unions[$i]['query'], $params) = $this->build($query, $params);
            }

            $result .= 'UNION ' . ($union['all'] ? 'ALL ' : '') . '( ' . $unions[$i]['query'] . ' ) ';
        }

        return trim($result);
    }

    /**
     * @param string|array $columns
     * @return string
     * @since 1.0.0
     */
    public function buildColumns($columns)
    {
        if (!is_array($columns)) {
            if (strpos($columns, '(') !== false) {
                return $columns;
            } else {
                $columns = preg_split('/\s*,\s*/', $columns, -1, PREG_SPLIT_NO_EMPTY);
            }
        }
        foreach ($columns as $i => $column) {
            if ($column instanceof Expression) {
                $columns[$i] = $column->expression;
            } elseif (strpos($column, '(') === false) {
                $columns[$i] = $this->db->quoteColumnName($column);
            }
        }

        return is_array($columns) ? implode(', ', $columns) : $columns;
    }

    /**
     * @param string|array|Expression $condition
     * @param array $params
     * @return string
     * @since 1.0.0
     */
    public function buildCondition($condition, &$params)
    {
        if ($condition instanceof Expression) {
            foreach ($condition->params as $n => $v) {
                $params[$n] = $v;
            }
            return $condition->expression;
        } elseif (!is_array($condition)) {
            return (string) $condition;
        } elseif (empty($condition)) {
            return '';
        }

        if (isset($condition[0])) { // operator format: operator, operand 1, operand 2, ...
            $operator = strtoupper($condition[0]);
            if (isset($this->conditionBuilders[$operator])) {
                $method = $this->conditionBuilders[$operator];
            } else {
                $method = 'buildSimpleCondition';
            }
            array_shift($condition);
            return $this->$method($operator, $condition, $params);
        } else { // hash format: 'column1' => 'value1', 'column2' => 'value2', ...
            return $this->buildHashCondition($condition, $params);
        }
    }

    /**
     * @param array $condition
     * @param array $params
     * @return string
     * @since 1.0.0
     */
    public function buildHashCondition($condition, &$params)
    {
        $parts = [];
        foreach ($condition as $column => $value) {
            if (ArrayHelper::isTraversable($value) || $value instanceof Query) {
                // IN condition
                $parts[] = $this->buildInCondition('IN', [$column, $value], $params);
            } else {
                if (strpos($column, '(') === false) {
                    $column = $this->db->quoteColumnName($column);
                }
                if ($value === null) {
                    $parts[] = "$column IS NULL";
                } elseif ($value instanceof Expression) {
                    $parts[] = "$column=" . $value->expression;
                    foreach ($value->params as $n => $v) {
                        $params[$n] = $v;
                    }
                } else {
                    $phName = self::PARAM_PREFIX . count($params);
                    $parts[] = "$column=$phName";
                    $params[$phName] = $value;
                }
            }
        }
        return count($parts) === 1 ? $parts[0] : '(' . implode(') AND (', $parts) . ')';
    }

    /**
     * @param string $operator
     * @param array $operands
     * @param array $params
     * @return string
     * @since 1.0.0
     */
    public function buildAndCondition($operator, $operands, &$params)
    {
        $parts = [];
        foreach ($operands as $operand) {
            if (is_array($operand)) {
                $operand = $this->buildCondition($operand, $params);
            }
            if ($operand instanceof Expression) {
                foreach ($operand->params as $n => $v) {
                    $params[$n] = $v;
                }
                $operand = $operand->expression;
            }
            if ($operand !== '') {
                $parts[] = $operand;
            }
        }
        if (!empty($parts)) {
            return '(' . implode(") $operator (", $parts) . ')';
        } else {
            return '';
        }
    }

    /**
     * @param string $operator
     * @param array $operands
     * @param array $params
     * @return string
     * @throws InvalidParamException
     * @since 1.0.0
     */
    public function buildNotCondition($operator, $operands, &$params)
    {
        if (count($operands) !== 1) {
            throw new InvalidParamException("Operator '$operator' requires exactly one operand.");
        }

        $operand = reset($operands);
        if (is_array($operand)) {
            $operand = $this->buildCondition($operand, $params);
        }
        if ($operand === '') {
            return '';
        }

        return "$operator ($operand)";
    }

    /**
     * @param string $operator
     * @param array $operands
     * @param array $params
     * @return string
     * @throws InvalidParamException
     * @since 1.0.0
     */
    public function buildBetweenCondition($operator, $operands, &$params)
    {
        if (!isset($operands[0], $operands[1], $operands[2])) {
            throw new InvalidParamException("Operator '$operator' requires three operands.");
        }

        list($column, $value1, $value2) = $operands;

        if (strpos($column, '(') === false) {
            $column = $this->db->quoteColumnName($column);
        }
        if ($value1 instanceof Expression) {
            foreach ($value1->params as $n => $v) {
                $params[$n] = $v;
            }
            $phName1 = $value1->expression;
        } else {
            $phName1 = self::PARAM_PREFIX . count($params);
            $params[$phName1] = $value1;
        }
        if ($value2 instanceof Expression) {
            foreach ($value2->params as $n => $v) {
                $params[$n] = $v;
            }
            $phName2 = $value2->expression;
        } else {
            $phName2 = self::PARAM_PREFIX . count($params);
            $params[$phName2] = $value2;
        }

        return "$column $operator $phName1 AND $phName2";
    }

    /**
     * @param string $operator
     * @param array $operands
     * @param array $params
     * @return string
     * @throws Exception
     * @since 1.0.0
     */
    public function buildInCondition($operator, $operands, &$params)
    {
        if (!isset($operands[0], $operands[1])) {
            throw new Exception("Operator '$operator' requires two operands.");
        }

        list($column, $values) = $operands;

        if ($column === []) {
            // no columns to test against
            return $operator === 'IN' ? '0=1' : '';
        }

        if ($values instanceof Query) {
            return $this->buildSubqueryInCondition($operator, $column, $values, $params);
        }
        if (!is_array($values) && !$values instanceof \Traversable) {
            // ensure values is an array
            $values = (array) $values;
        }

        if ($column instanceof \Traversable || $column instanceof \Countable && count($column) > 1) {
            return $this->buildCompositeInCondition($operator, $column, $values, $params);
        } elseif (is_array($column)) {
            $column = reset($column);
        }

        $sqlValues = [];
        foreach ($values as $i => $value) {
            if (is_array($value) || $value instanceof \ArrayAccess) {
                $value = isset($value[$column]) ? $value[$column] : null;
            }
            if ($value === null) {
                $sqlValues[$i] = 'NULL';
            } elseif ($value instanceof Expression) {
                $sqlValues[$i] = $value->expression;
                foreach ($value->params as $n => $v) {
                    $params[$n] = $v;
                }
            } else {
                $phName = self::PARAM_PREFIX . count($params);
                $params[$phName] = $value;
                $sqlValues[$i] = $phName;
            }
        }

        if (empty($sqlValues)) {
            return $operator === 'IN' ? '0=1' : '';
        }

        if (strpos($column, '(') === false) {
            $column = $this->db->quoteColumnName($column);
        }

        if (count($sqlValues) > 1) {
            return "$column $operator (" . implode(', ', $sqlValues) . ')';
        } else {
            $operator = $operator === 'IN' ? '=' : '<>';
            return $column . $operator . reset($sqlValues);
        }
    }

    /**
     * @param string $operator
     * @param array $columns
     * @param Query $values
     * @param array $params
     * @return string
     * @since 1.0.0
     */
    protected function buildSubqueryInCondition($operator, $columns, $values, &$params)
    {
        list($sql, $params) = $this->build($values, $params);
        if (is_array($columns)) {
            foreach ($columns as $i => $col) {
                if (strpos($col, '(') === false) {
                    $columns[$i] = $this->db->quoteColumnName($col);
                }
            }
            return '(' . implode(', ', $columns) . ") $operator ($sql)";
        } else {
            if (strpos($columns, '(') === false) {
                $columns = $this->db->quoteColumnName($columns);
            }
            return "$columns $operator ($sql)";
        }
    }

    /**
     * @param string $operator
     * @param array|\Traversable $columns
     * @param array $values
     * @param array $params
     * @return string
     * @since 1.0.0
     */
    protected function buildCompositeInCondition($operator, $columns, $values, &$params)
    {
        $vss = [];
        foreach ($values as $value) {
            $vs = [];
            foreach ($columns as $column) {
                if (isset($value[$column])) {
                    $phName = self::PARAM_PREFIX . count($params);
                    $params[$phName] = $value[$column];
                    $vs[] = $phName;
                } else {
                    $vs[] = 'NULL';
                }
            }
            $vss[] = '(' . implode(', ', $vs) . ')';
        }

        if (empty($vss)) {
            return $operator === 'IN' ? '0=1' : '';
        }

        $sqlColumns = [];
        foreach ($columns as $i => $column) {
            $sqlColumns[] = strpos($column, '(') === false ? $this->db->quoteColumnName($column) : $column;
        }

        return '(' . implode(', ', $sqlColumns) . ") $operator (" . implode(', ', $vss) . ')';
    }

    /**
     * @param string $operator
     * @param array $operands
     * @param array $params
     * @return string
     * @throws InvalidParamException
     * @since 1.0.0
     */
    public function buildLikeCondition($operator, $operands, &$params)
    {
        if (!isset($operands[0], $operands[1])) {
            throw new InvalidParamException("Operator '$operator' requires two operands.");
        }

        $escape = isset($operands[2]) ? $operands[2] : ['%' => '\%', '_' => '\_', '\\' => '\\\\'];
        unset($operands[2]);

        if (!preg_match('/^(AND |OR |)(((NOT |))I?LIKE)/', $operator, $matches)) {
            throw new InvalidParamException("Invalid operator '$operator'.");
        }
        $andor = ' ' . (!empty($matches[1]) ? $matches[1] : 'AND ');
        $not = !empty($matches[3]);
        $operator = $matches[2];

        list($column, $values) = $operands;

        if (!is_array($values)) {
            $values = [$values];
        }

        if (empty($values)) {
            return $not ? '' : '0=1';
        }

        if (strpos($column, '(') === false) {
            $column = $this->db->quoteColumnName($column);
        }

        $parts = [];
        foreach ($values as $value) {
            if ($value instanceof Expression) {
                foreach ($value->params as $n => $v) {
                    $params[$n] = $v;
                }
                $phName = $value->expression;
            } else {
                $phName = self::PARAM_PREFIX . count($params);
                $params[$phName] = empty($escape) ? $value : ('%' . strtr($value, $escape) . '%');
            }
            $parts[] = "$column $operator $phName";
        }

        return implode($andor, $parts);
    }

    /**
     * @param string $operator
     * @param array $operands
     * @param array $params
     * @return string
     * @throws InvalidParamException
     * @since 1.0.0
     */
    public function buildExistsCondition($operator, $operands, &$params)
    {
        if ($operands[0] instanceof Query) {
            list($sql, $params) = $this->build($operands[0], $params);
            return "$operator ($sql)";
        } else {
            throw new InvalidParamException('Subquery for EXISTS operator must be a Query object.');
        }
    }

    /**
     * @param string $operator
     * @param array $operands
     * @param array $params
     * @return string
     * @throws InvalidParamException
     * @since 1.0.0
     */
    public function buildSimpleCondition($operator, $operands, &$params)
    {
        if (count($operands) !== 2) {
            throw new InvalidParamException("Operator '$operator' requires two operands.");
        }

        list($column, $value) = $operands;

        if (strpos($column, '(') === false) {
            $column = $this->db->quoteColumnName($column);
        }

        if ($value === null) {
            return "$column $operator NULL";
        } elseif ($value instanceof Expression) {
            foreach ($value->params as $n => $v) {
                $params[$n] = $v;
            }
            return "$column $operator {$value->expression}";
        } elseif ($value instanceof Query) {
            list($sql, $params) = $this->build($value, $params);
            return "$column $operator ($sql)";
        } else {
            $phName = self::PARAM_PREFIX . count($params);
            $params[$phName] = $value;
            return "$column $operator $phName";
        }
    }

    /**
     * @param string $rawSql
     * @return string
     * @since 1.0.0
     */
    public function selectExists($rawSql)
    {
        return 'SELECT EXISTS(' . $rawSql . ')';
    }
}
