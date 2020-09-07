<?php
namespace intec\core\db;

use intec\core\base\NotSupportedException;

/**
 * Trait QueryTrait
 * @package intec\core\db
 * @since 1.0.0
 */
trait QueryTrait
{
    /**
     * @var string|array
     * @see where()
     * @since 1.0.0
     */
    public $where;
    /**
     * @var int
     * @since 1.0.0
     */
    public $limit;
    /**
     * @var int
     * @since 1.0.0
     */
    public $offset;
    /**
     * @var array
     * @since 1.0.0
     */
    public $orderBy;
    /**
     * @var string|callable
     * @since 1.0.0
     */
    public $indexBy;
    /**
     * @var boolean
     * @see emulateExecution()
     * @since 1.0.0
     */
    public $emulateExecution = false;


    /**
     * @param string|callable $column
     * @return $this
     * @since 1.0.0
     */
    public function indexBy($column)
    {
        $this->indexBy = $column;
        return $this;
    }

    /**
     * @param string|array $condition
     * @return $this
     * @see andWhere()
     * @see orWhere()
     * @since 1.0.0
     */
    public function where($condition)
    {
        $this->where = $condition;
        return $this;
    }

    /**
     * @param string|array $condition
     * @return $this
     * @see where()
     * @see orWhere()
     * @since 1.0.0
     */
    public function andWhere($condition)
    {
        if ($this->where === null) {
            $this->where = $condition;
        } else {
            $this->where = ['and', $this->where, $condition];
        }
        return $this;
    }

    /**
     * @param string|array $condition
     * @return $this
     * @see where()
     * @see andWhere()
     * @since 1.0.0
     */
    public function orWhere($condition)
    {
        if ($this->where === null) {
            $this->where = $condition;
        } else {
            $this->where = ['or', $this->where, $condition];
        }
        return $this;
    }

    /**
     * @param array $condition
     * @return $this
     * @see where()
     * @see andFilterWhere()
     * @see orFilterWhere()
     * @since 1.0.0
     */
    public function filterWhere(array $condition)
    {
        $condition = $this->filterCondition($condition);
        if ($condition !== []) {
            $this->where($condition);
        }
        return $this;
    }

    /**
     * @param array $condition
     * @return $this
     * @see filterWhere()
     * @see orFilterWhere()
     * @since 1.0.0
     */
    public function andFilterWhere(array $condition)
    {
        $condition = $this->filterCondition($condition);
        if ($condition !== []) {
            $this->andWhere($condition);
        }
        return $this;
    }

    /**
     * @param array $condition
     * @return $this
     * @see filterWhere()
     * @see andFilterWhere()
     * @since 1.0.0
     */
    public function orFilterWhere(array $condition)
    {
        $condition = $this->filterCondition($condition);
        if ($condition !== []) {
            $this->orWhere($condition);
        }
        return $this;
    }

    /**
     * @param array $condition
     * @return array
     * @throws NotSupportedException
     * @since 1.0.0
     */
    protected function filterCondition($condition)
    {
        if (!is_array($condition)) {
            return $condition;
        }

        if (!isset($condition[0])) {
            // hash format: 'column1' => 'value1', 'column2' => 'value2', ...
            foreach ($condition as $name => $value) {
                if ($this->isEmpty($value)) {
                    unset($condition[$name]);
                }
            }
            return $condition;
        }

        // operator format: operator, operand 1, operand 2, ...

        $operator = array_shift($condition);

        switch (strtoupper($operator)) {
            case 'NOT':
            case 'AND':
            case 'OR':
                foreach ($condition as $i => $operand) {
                    $subCondition = $this->filterCondition($operand);
                    if ($this->isEmpty($subCondition)) {
                        unset($condition[$i]);
                    } else {
                        $condition[$i] = $subCondition;
                    }
                }

                if (empty($condition)) {
                    return [];
                }
                break;
            case 'BETWEEN':
            case 'NOT BETWEEN':
                if (array_key_exists(1, $condition) && array_key_exists(2, $condition)) {
                    if ($this->isEmpty($condition[1]) || $this->isEmpty($condition[2])) {
                        return [];
                    }
                }
                break;
            default:
                if (array_key_exists(1, $condition) && $this->isEmpty($condition[1])) {
                    return [];
                }
        }

        array_unshift($condition, $operator);

        return $condition;
    }

    /**
     * @param mixed $value
     * @return bool
     * @since 1.0.0
     */
    protected function isEmpty($value)
    {
        return $value === '' || $value === [] || $value === null || is_string($value) && trim($value) === '';
    }

    /**
     * @param string|array|Expression $columns
     * @return $this
     * @see addOrderBy()
     * @since 1.0.0
     */
    public function orderBy($columns)
    {
        $this->orderBy = $this->normalizeOrderBy($columns);
        return $this;
    }

    /**
     * @param string|array|Expression $columns
     * @return $this
     * @see orderBy()
     * @since 1.0.0
     */
    public function addOrderBy($columns)
    {
        $columns = $this->normalizeOrderBy($columns);
        if ($this->orderBy === null) {
            $this->orderBy = $columns;
        } else {
            $this->orderBy = array_merge($this->orderBy, $columns);
        }
        return $this;
    }

    /**
     * @param array|string|Expression $columns
     * @return array
     * @since 1.0.0
     */
    protected function normalizeOrderBy($columns)
    {
        if ($columns instanceof Expression) {
            return [$columns];
        } elseif (is_array($columns)) {
            return $columns;
        } else {
            $columns = preg_split('/\s*,\s*/', trim($columns), -1, PREG_SPLIT_NO_EMPTY);
            $result = [];
            foreach ($columns as $column) {
                if (preg_match('/^(.*?)\s+(asc|desc)$/i', $column, $matches)) {
                    $result[$matches[1]] = strcasecmp($matches[2], 'desc') ? SORT_ASC : SORT_DESC;
                } else {
                    $result[$column] = SORT_ASC;
                }
            }
            return $result;
        }
    }

    /**
     * @param int $limit
     * @return $this
     * @since 1.0.0
     */
    public function limit($limit)
    {
        $this->limit = $limit;
        return $this;
    }

    /**
     * @param int $offset
     * @return $this
     * @since 1.0.0
     */
    public function offset($offset)
    {
        $this->offset = $offset;
        return $this;
    }

    /**
     * @param bool $value
     * @return $this
     * @since 1.0.0
     */
    public function emulateExecution($value = true)
    {
        $this->emulateExecution = $value;
        return $this;
    }
}
