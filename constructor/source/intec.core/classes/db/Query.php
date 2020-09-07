<?php
namespace intec\core\db;

use intec\Core;
use intec\core\base\Component;

/**
 * Class Query
 * @package intec\core\db
 * @since 1.0.0
 */
class Query extends Component implements QueryInterface
{
    use QueryTrait;

    /**
     * @var array
     * @see select()
     * @since 1.0.0
     */
    public $select;
    /**
     * @var string
     * @since 1.0.0
     */
    public $selectOption;
    /**
     * @var bool
     * @since 1.0.0
     */
    public $distinct;
    /**
     * @var array
     * @see from()
     * @since 1.0.0
     */
    public $from;
    /**
     * @var array
     * @since 1.0.0
     */
    public $groupBy;
    /**
     * @var array
     * @since 1.0.0
     */
    public $join;
    /**
     * @var string|array|Expression
     * @since 1.0.0
     */
    public $having;
    /**
     * @var array
     * @since 1.0.0
     */
    public $union;
    /**
     * @var array
     * @since 1.0.0
     */
    public $params = [];


    /**
     * @param Connection $db
     * @return Command
     * @since 1.0.0
     */
    public function createCommand($db = null)
    {
        if ($db === null) {
            $db = Core::$app->getDb();
        }
        list ($sql, $params) = $db->getQueryBuilder()->build($this);

        return $db->createCommand($sql, $params);
    }

    /**
     * @param QueryBuilder $builder
     * @return $this
     * @since 1.0.0
     */
    public function prepare($builder)
    {
        return $this;
    }

    /**
     * @param int $batchSize
     * @param Connection $db
     * @return BatchQueryResult
     * @since 1.0.0
     */
    public function batch($batchSize = 100, $db = null)
    {
        return Core::createObject([
            'class' => BatchQueryResult::className(),
            'query' => $this,
            'batchSize' => $batchSize,
            'db' => $db,
            'each' => false,
        ]);
    }

    /**
     * @param int $batchSize
     * @param Connection $db
     * @return BatchQueryResult
     * @since 1.0.0
     */
    public function each($batchSize = 100, $db = null)
    {
        return Core::createObject([
            'class' => BatchQueryResult::className(),
            'query' => $this,
            'batchSize' => $batchSize,
            'db' => $db,
            'each' => true,
        ]);
    }

    /**
     * @param Connection $db
     * @return array
     * @since 1.0.0
     */
    public function all($db = null)
    {
        if ($this->emulateExecution) {
            return [];
        }
        $rows = $this->createCommand($db)->queryAll();
        return $this->populate($rows);
    }

    /**
     * @param array $rows
     * @return array
     * @since 1.0.0
     */
    public function populate($rows)
    {
        if ($this->indexBy === null) {
            return $rows;
        }
        $result = [];
        foreach ($rows as $row) {
            if (is_string($this->indexBy)) {
                $key = $row[$this->indexBy];
            } else {
                $key = call_user_func($this->indexBy, $row);
            }
            $result[$key] = $row;
        }
        return $result;
    }

    /**
     * @param Connection $db
     * @return array|bool
     * @since 1.0.0
     */
    public function one($db = null)
    {
        if ($this->emulateExecution) {
            return false;
        }
        return $this->createCommand($db)->queryOne();
    }

    /**
     * @param Connection $db
     * @return string|null|false
     * @since 1.0.0
     */
    public function scalar($db = null)
    {
        if ($this->emulateExecution) {
            return null;
        }
        return $this->createCommand($db)->queryScalar();
    }

    /**
     * @param Connection $db
     * @return array
     * @since 1.0.0
     */
    public function column($db = null)
    {
        if ($this->emulateExecution) {
            return [];
        }

        if ($this->indexBy === null) {
            return $this->createCommand($db)->queryColumn();
        }

        if (is_string($this->indexBy) && is_array($this->select) && count($this->select) === 1) {
            $this->select[] = $this->indexBy;
        }
        $rows = $this->createCommand($db)->queryAll();
        $results = [];
        foreach ($rows as $row) {
            $value = reset($row);

            if ($this->indexBy instanceof \Closure) {
                $results[call_user_func($this->indexBy, $row)] = $value;
            } else {
                $results[$row[$this->indexBy]] = $value;
            }
        }
        return $results;
    }

    /**
     * @param string $q
     * @param Connection $db
     * @return int|string
     * @since 1.0.0
     */
    public function count($q = '*', $db = null)
    {
        if ($this->emulateExecution) {
            return 0;
        }
        return $this->queryScalar("COUNT($q)", $db);
    }

    /**
     * @param string $q
     * @param Connection $db
     * @return mixed
     * @since 1.0.0
     */
    public function sum($q, $db = null)
    {
        if ($this->emulateExecution) {
            return 0;
        }
        return $this->queryScalar("SUM($q)", $db);
    }

    /**
     * @param string $q
     * @param Connection $db
     * @return mixed
     * @since 1.0.0
     */
    public function average($q, $db = null)
    {
        if ($this->emulateExecution) {
            return 0;
        }
        return $this->queryScalar("AVG($q)", $db);
    }

    /**
     * @param string $q
     * @param Connection $db
     * @return mixed
     * @since 1.0.0
     */
    public function min($q, $db = null)
    {
        return $this->queryScalar("MIN($q)", $db);
    }

    /**
     * @param string $q
     * @param Connection $db
     * @return mixed
     * @since 1.0.0
     */
    public function max($q, $db = null)
    {
        return $this->queryScalar("MAX($q)", $db);
    }

    /**
     * @param Connection $db
     * @return bool
     * @since 1.0.0
     */
    public function exists($db = null)
    {
        if ($this->emulateExecution) {
            return false;
        }
        $command = $this->createCommand($db);
        $params = $command->params;
        $command->setSql($command->db->getQueryBuilder()->selectExists($command->getSql()));
        $command->bindValues($params);
        return (bool) $command->queryScalar();
    }

    /**
     * @param string|Expression $selectExpression
     * @param Connection|null $db
     * @return bool|string
     * @since 1.0.0
     */
    protected function queryScalar($selectExpression, $db)
    {
        if ($this->emulateExecution) {
            return null;
        }

        $select = $this->select;
        $limit = $this->limit;
        $offset = $this->offset;

        $this->select = [$selectExpression];
        $this->limit = null;
        $this->offset = null;
        $command = $this->createCommand($db);

        $this->select = $select;
        $this->limit = $limit;
        $this->offset = $offset;

        if (
            !$this->distinct
            && empty($this->groupBy)
            && empty($this->having)
            && empty($this->union)
            && empty($this->orderBy)
        ) {
            return $command->queryScalar();
        } else {
            return (new Query)->select([$selectExpression])
                ->from(['c' => $this])
                ->createCommand($command->db)
                ->queryScalar();
        }
    }

    /**
     * @param string|array|Expression $columns
     * @param string $option
     * @return $this
     * @since 1.0.0
     */
    public function select($columns, $option = null)
    {
        if ($columns instanceof Expression) {
            $columns = [$columns];
        } elseif (!is_array($columns)) {
            $columns = preg_split('/\s*,\s*/', trim($columns), -1, PREG_SPLIT_NO_EMPTY);
        }
        $this->select = $columns;
        $this->selectOption = $option;
        return $this;
    }

    /**
     * @param string|array|Expression $columns
     * @return $this
     * @see select()
     * @since 1.0.0
     */
    public function addSelect($columns)
    {
        if ($columns instanceof Expression) {
            $columns = [$columns];
        } elseif (!is_array($columns)) {
            $columns = preg_split('/\s*,\s*/', trim($columns), -1, PREG_SPLIT_NO_EMPTY);
        }
        if ($this->select === null) {
            $this->select = $columns;
        } else {
            $this->select = array_merge($this->select, $columns);
        }
        return $this;
    }

    /**
     * @param bool $value
     * @return $this
     * @since 1.0.0
     */
    public function distinct($value = true)
    {
        $this->distinct = $value;
        return $this;
    }

    /**
     * @param string|array $tables
     * @return $this
     * @since 1.0.0
     */
    public function from($tables)
    {
        if (!is_array($tables)) {
            $tables = preg_split('/\s*,\s*/', trim($tables), -1, PREG_SPLIT_NO_EMPTY);
        }
        $this->from = $tables;
        return $this;
    }

    /**
     * @param string|array|Expression $condition
     * @param array $params
     * @return $this
     * @see andWhere()
     * @see orWhere()
     * @see QueryInterface::where()
     * @since 1.0.0
     */
    public function where($condition, $params = [])
    {
        $this->where = $condition;
        $this->addParams($params);
        return $this;
    }

    /**
     * @param string|array|Expression $condition
     * @param array $params
     * @return $this
     * @see where()
     * @see orWhere()
     * @since 1.0.0
     */
    public function andWhere($condition, $params = [])
    {
        if ($this->where === null) {
            $this->where = $condition;
        } elseif (is_array($this->where) && isset($this->where[0]) && strcasecmp($this->where[0], 'and') === 0) {
            $this->where[] = $condition;
        } else {
            $this->where = ['and', $this->where, $condition];
        }
        $this->addParams($params);
        return $this;
    }

    /**
     * @param string|array|Expression $condition
     * @param array $params
     * @return $this
     * @see where()
     * @see andWhere()
     * @since 1.0.0
     */
    public function orWhere($condition, $params = [])
    {
        if ($this->where === null) {
            $this->where = $condition;
        } else {
            $this->where = ['or', $this->where, $condition];
        }
        $this->addParams($params);
        return $this;
    }

    /**
     * @param string $name
     * @param string $value
     * @param string $defaultOperator
     * @return $this
     * @since 1.0.0
     */
    public function andFilterCompare($name, $value, $defaultOperator = '=')
    {
        if (preg_match('/^(<>|>=|>|<=|<|=)/', $value, $matches)) {
            $operator = $matches[1];
            $value = substr($value, strlen($operator));
        } else {
            $operator = $defaultOperator;
        }
        return $this->andFilterWhere([$operator, $name, $value]);
    }

    /**
     * @param string $type
     * @param string|array $table
     * @param string|array $on
     * @param array $params
     * @return $this
     * @since 1.0.0
     */
    public function join($type, $table, $on = '', $params = [])
    {
        $this->join[] = [$type, $table, $on];
        return $this->addParams($params);
    }

    /**
     * @param string|array $table
     * @param string|array $on
     * @param array $params
     * @return $this
     * @since 1.0.0
     */
    public function innerJoin($table, $on = '', $params = [])
    {
        $this->join[] = ['INNER JOIN', $table, $on];
        return $this->addParams($params);
    }

    /**
     * @param string|array $table
     * @param string|array $on
     * @param array $params
     * @return $this
     * @since 1.0.0
     */
    public function leftJoin($table, $on = '', $params = [])
    {
        $this->join[] = ['LEFT JOIN', $table, $on];
        return $this->addParams($params);
    }

    /**
     * @param string|array $table
     * @param string|array $on
     * @param array $params
     * @return $this
     * @since 1.0.0
     */
    public function rightJoin($table, $on = '', $params = [])
    {
        $this->join[] = ['RIGHT JOIN', $table, $on];
        return $this->addParams($params);
    }

    /**
     * @param string|array|Expression $columns
     * @return $this
     * @see addGroupBy()
     * @since 1.0.0
     */
    public function groupBy($columns)
    {
        if ($columns instanceof Expression) {
            $columns = [$columns];
        } elseif (!is_array($columns)) {
            $columns = preg_split('/\s*,\s*/', trim($columns), -1, PREG_SPLIT_NO_EMPTY);
        }
        $this->groupBy = $columns;
        return $this;
    }

    /**
     * @param string|array $columns
     * @return $this
     * @see groupBy()
     * @since 1.0.0
     */
    public function addGroupBy($columns)
    {
        if ($columns instanceof Expression) {
            $columns = [$columns];
        } elseif (!is_array($columns)) {
            $columns = preg_split('/\s*,\s*/', trim($columns), -1, PREG_SPLIT_NO_EMPTY);
        }
        if ($this->groupBy === null) {
            $this->groupBy = $columns;
        } else {
            $this->groupBy = array_merge($this->groupBy, $columns);
        }
        return $this;
    }

    /**
     * @param string|array|Expression $condition
     * @param array $params
     * @return $this
     * @see andHaving()
     * @see orHaving()
     * @since 1.0.0
     */
    public function having($condition, $params = [])
    {
        $this->having = $condition;
        $this->addParams($params);
        return $this;
    }

    /**
     * @param string|array|Expression $condition
     * @param array $params
     * @return $this
     * @see having()
     * @see orHaving()
     * @since 1.0.0
     */
    public function andHaving($condition, $params = [])
    {
        if ($this->having === null) {
            $this->having = $condition;
        } else {
            $this->having = ['and', $this->having, $condition];
        }
        $this->addParams($params);
        return $this;
    }

    /**
     * @param string|array|Expression $condition
     * @param array $params
     * @return $this
     * @see having()
     * @see andHaving()
     * @since 1.0.0
     */
    public function orHaving($condition, $params = [])
    {
        if ($this->having === null) {
            $this->having = $condition;
        } else {
            $this->having = ['or', $this->having, $condition];
        }
        $this->addParams($params);
        return $this;
    }

    /**
     * @param array $condition
     * @return $this
     * @see having()
     * @see andFilterHaving()
     * @see orFilterHaving()
     * @since 1.0.0
     */
    public function filterHaving(array $condition)
    {
        $condition = $this->filterCondition($condition);
        if ($condition !== []) {
            $this->having($condition);
        }
        return $this;
    }

    /**
     * @param array $condition
     * @return $this
     * @see filterHaving()
     * @see orFilterHaving()
     * @since 1.0.0
     */
    public function andFilterHaving(array $condition)
    {
        $condition = $this->filterCondition($condition);
        if ($condition !== []) {
            $this->andHaving($condition);
        }
        return $this;
    }

    /**
     * @param array $condition
     * @return $this
     * @see filterHaving()
     * @see andFilterHaving()
     * @since 1.0.0
     */
    public function orFilterHaving(array $condition)
    {
        $condition = $this->filterCondition($condition);
        if ($condition !== []) {
            $this->orHaving($condition);
        }
        return $this;
    }

    /**
     * @param string|Query $sql
     * @param bool $all
     * @return $this
     * @since 1.0.0
     */
    public function union($sql, $all = false)
    {
        $this->union[] = ['query' => $sql, 'all' => $all];
        return $this;
    }

    /**
     * @param array $params
     * @return $this
     * @see addParams()
     * @since 1.0.0
     */
    public function params($params)
    {
        $this->params = $params;
        return $this;
    }

    /**
     * @param array $params
     * @return $this
     * @see params()
     * @since 1.0.0
     */
    public function addParams($params)
    {
        if (!empty($params)) {
            if (empty($this->params)) {
                $this->params = $params;
            } else {
                foreach ($params as $name => $value) {
                    if (is_int($name)) {
                        $this->params[] = $value;
                    } else {
                        $this->params[$name] = $value;
                    }
                }
            }
        }
        return $this;
    }

    /**
     * @param Query $from
     * @return Query
     * @since 1.0.0
     */
    public static function create($from)
    {
        return new self([
            'where' => $from->where,
            'limit' => $from->limit,
            'offset' => $from->offset,
            'orderBy' => $from->orderBy,
            'indexBy' => $from->indexBy,
            'select' => $from->select,
            'selectOption' => $from->selectOption,
            'distinct' => $from->distinct,
            'from' => $from->from,
            'groupBy' => $from->groupBy,
            'join' => $from->join,
            'having' => $from->having,
            'union' => $from->union,
            'params' => $from->params,
        ]);
    }
}
