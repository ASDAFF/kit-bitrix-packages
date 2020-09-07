<?php
namespace intec\core\db;

use intec\core\base\BaseObject;
use intec\core\base\InvalidCallException;

/**
 * Class DataReader
 * @property int $columnCount
 * @property int $fetchMode
 * @property bool $isClosed
 * @property int $rowCount
 * @package intec\core\db
 * @since 1.0.0
 */
class DataReader extends BaseObject implements \Iterator, \Countable
{
    /**
     * @var \PDOStatement
     * @since 1.0.0
     */
    private $_statement;
    private $_closed = false;
    private $_row;
    private $_index = -1;


    /**
     * Constructor.
     * @param Command $command
     * @param array $config
     * @since 1.0.0
     */
    public function __construct(Command $command, $config = [])
    {
        $this->_statement = $command->pdoStatement;
        $this->_statement->setFetchMode(\PDO::FETCH_ASSOC);
        parent::__construct($config);
    }

    /**
     * @param int|string $column
     * @param mixed $value
     * @param int $dataType
     * @since 1.0.0
     */
    public function bindColumn($column, &$value, $dataType = null)
    {
        if ($dataType === null) {
            $this->_statement->bindColumn($column, $value);
        } else {
            $this->_statement->bindColumn($column, $value, $dataType);
        }
    }

    /**
     * @param int $mode
     * @since 1.0.0
     */
    public function setFetchMode($mode)
    {
        $params = func_get_args();
        call_user_func_array([$this->_statement, 'setFetchMode'], $params);
    }

    /**
     * @return array
     * @since 1.0.0
     */
    public function read()
    {
        return $this->_statement->fetch();
    }

    /**
     * @param int $columnIndex
     * @return mixed
     * @since 1.0.0
     */
    public function readColumn($columnIndex)
    {
        return $this->_statement->fetchColumn($columnIndex);
    }

    /**
     * @param string $className
     * @param array $fields
     * @return mixed
     * @since 1.0.0
     */
    public function readObject($className, $fields)
    {
        return $this->_statement->fetchObject($className, $fields);
    }

    /**
     * @return array
     * @since 1.0.0
     */
    public function readAll()
    {
        return $this->_statement->fetchAll();
    }

    /**
     * @return bool
     * @since 1.0.0
     */
    public function nextResult()
    {
        if (($result = $this->_statement->nextRowset()) !== false) {
            $this->_index = -1;
        }

        return $result;
    }

    /**
     * @since 1.0.0
     */
    public function close()
    {
        $this->_statement->closeCursor();
        $this->_closed = true;
    }

    /**
     * @return bool
     * @since 1.0.0
     */
    public function getIsClosed()
    {
        return $this->_closed;
    }

    /**
     * @return int
     * @since 1.0.0
     */
    public function getRowCount()
    {
        return $this->_statement->rowCount();
    }

    /**
     * @return int
     * @since 1.0.0
     */
    public function count()
    {
        return $this->getRowCount();
    }

    /**
     * @return int
     * @since 1.0.0
     */
    public function getColumnCount()
    {
        return $this->_statement->columnCount();
    }

    /**
     * @throws InvalidCallException
     * @since 1.0.0
     */
    public function rewind()
    {
        if ($this->_index < 0) {
            $this->_row = $this->_statement->fetch();
            $this->_index = 0;
        } else {
            throw new InvalidCallException('DataReader cannot rewind. It is a forward-only reader.');
        }
    }

    /**
     * @return int
     * @since 1.0.0
     */
    public function key()
    {
        return $this->_index;
    }

    /**
     * @return mixed
     * @since 1.0.0
     */
    public function current()
    {
        return $this->_row;
    }

    /**
     * @since 1.0.0
     */
    public function next()
    {
        $this->_row = $this->_statement->fetch();
        $this->_index++;
    }

    /**
     * @return bool
     * @since 1.0.0
     */
    public function valid()
    {
        return $this->_row !== false;
    }
}
