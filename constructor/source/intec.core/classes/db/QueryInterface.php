<?php
namespace intec\core\db;

/**
 * Interface QueryInterface
 * @package intec\core\db
 * @since 1.0.0
 */
interface QueryInterface
{
    /**
     * @param Connection $db
     * @return array
     * @since 1.0.0
     */
    public function all($db = null);

    /**
     * @param Connection $db
     * @return array|bool
     * @since 1.0.0
     */
    public function one($db = null);

    /**
     * @param string $q
     * @param Connection $db
     * @return int
     * @since 1.0.0
     */
    public function count($q = '*', $db = null);

    /**
     * @param Connection $db
     * @return bool
     * @since 1.0.0
     */
    public function exists($db = null);

    /**
     * @param string|callable $column
     * @return $this
     * @since 1.0.0
     */
    public function indexBy($column);

    /**
     * @param string|array $condition
     * @return $this
     * @see andWhere()
     * @see orWhere()
     * @since 1.0.0
     */
    public function where($condition);

    /**
     * @param string|array $condition
     * @return $this
     * @see where()
     * @see orWhere()
     * @since 1.0.0
     */
    public function andWhere($condition);

    /**
     * @param string|array $condition
     * @return $this
     * @see where()
     * @see andWhere()
     * @since 1.0.0
     */
    public function orWhere($condition);

    /**
     * @param array $condition
     * @return $this
     * @see andFilterWhere()
     * @see orFilterWhere()
     * @since 1.0.0
     */
    public function filterWhere(array $condition);

    /**
     * @param array $condition
     * @return $this
     * @see filterWhere()
     * @see orFilterWhere()
     * @since 1.0.0
     */
    public function andFilterWhere(array $condition);

    /**
     * @param array $condition
     * @return $this
     * @see filterWhere()
     * @see andFilterWhere()
     * @since 1.0.0
     */
    public function orFilterWhere(array $condition);

    /**
     * @param string|array $columns
     * @return $this
     * @see addOrderBy()
     * @since 1.0.0
     */
    public function orderBy($columns);

    /**
     * @param string|array $columns
     * @return $this
     * @see orderBy()
     * @since 1.0.0
     */
    public function addOrderBy($columns);

    /**
     * @param int $limit
     * @return $this
     * @since 1.0.0
     */
    public function limit($limit);

    /**
     * @param int $offset
     * @return $this
     * @since 1.0.0
     */
    public function offset($offset);

    /**
     * @param bool $value
     * @return $this
     * @since 1.0.0
     */
    public function emulateExecution($value = true);
}
