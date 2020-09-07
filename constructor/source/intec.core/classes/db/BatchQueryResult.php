<?php
namespace intec\core\db;

use intec\core\base\BaseObject;

/**
 * Class BatchQueryResult
 * @package intec\core\db
 * @since 1.0.0
 */
class BatchQueryResult extends BaseObject implements \Iterator
{
    /**
     * @var Connection
     * @since 1.0.0
     */
    public $db;
    /**
     * @var Query
     * @since 1.0.0
     */
    public $query;
    /**
     * @var int
     * @since 1.0.0
     */
    public $batchSize = 100;
    /**
     * @var bool
     * @since 1.0.0
     */
    public $each = false;

    /**
     * @var DataReader
     * @since 1.0.0
     */
    private $_dataReader;
    /**
     * @var array
     * @since 1.0.0
     */
    private $_batch;
    /**
     * @var mixed
     * @since 1.0.0
     */
    private $_value;
    /**
     * @var string|int
     * @since 1.0.0
     */
    private $_key;


    /**
     * Destructor.
     */
    public function __destruct()
    {
        // make sure cursor is closed
        $this->reset();
    }

    /**
     * @since 1.0.0
     */
    public function reset()
    {
        if ($this->_dataReader !== null) {
            $this->_dataReader->close();
        }
        $this->_dataReader = null;
        $this->_batch = null;
        $this->_value = null;
        $this->_key = null;
    }

    /**
     * @since 1.0.0
     */
    public function rewind()
    {
        $this->reset();
        $this->next();
    }

    /**
     * @since 1.0.0
     */
    public function next()
    {
        if ($this->_batch === null || !$this->each || $this->each && next($this->_batch) === false) {
            $this->_batch = $this->fetchData();
            reset($this->_batch);
        }

        if ($this->each) {
            $this->_value = current($this->_batch);
            if ($this->query->indexBy !== null) {
                $this->_key = key($this->_batch);
            } elseif (key($this->_batch) !== null) {
                $this->_key++;
            } else {
                $this->_key = null;
            }
        } else {
            $this->_value = $this->_batch;
            $this->_key = $this->_key === null ? 0 : $this->_key + 1;
        }
    }

    /**
     * @return array
     * @since 1.0.0
     */
    protected function fetchData()
    {
        if ($this->_dataReader === null) {
            $this->_dataReader = $this->query->createCommand($this->db)->query();
        }

        $rows = [];
        $count = 0;
        while ($count++ < $this->batchSize && ($row = $this->_dataReader->read())) {
            $rows[] = $row;
        }

        return $this->query->populate($rows);
    }

    /**
     * @return int
     * @since 1.0.0
     */
    public function key()
    {
        return $this->_key;
    }

    /**
     * @return mixed
     * @since 1.0.0
     */
    public function current()
    {
        return $this->_value;
    }

    /**
     * @return bool
     * @since 1.0.0
     */
    public function valid()
    {
        return !empty($this->_batch);
    }
}
