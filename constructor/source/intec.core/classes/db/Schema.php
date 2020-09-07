<?php
namespace intec\core\db;

use intec\Core;
use intec\core\base\BaseObject;
use intec\core\base\NotSupportedException;
use intec\core\base\InvalidCallException;
use intec\core\base\InvalidConfigException;

/**
 * Schema базовый класс для схем.
 * Class Schema
 * @property string $lastInsertID Идентификатор последней добавленной строки. Только для чтения.
 * @property QueryBuilder $queryBuilder Построитель запросов для данной схемы. Только для чтения.
 * @property string[] $schemaNames Все имена схем базы данных, без системных схем. Только для чтения.
 * @property string[] $tableNames Все имена таблиц в базе данных. Только для чтения.
 * @property TableSchema[] $tableSchemas Метаданные всех таблиц в базе данных.
 * Каждый элемент является экземпляром [[TableSchema]]. Только для чтения.
 * @property string $transactionIsolationLevel Уровень изоляции транзакции.
 * @package intec\core\db
 * @since 1.0.0
 */
abstract class Schema extends BaseObject
{
    const TYPE_PK = 'pk';
    const TYPE_UPK = 'upk';
    const TYPE_BIGPK = 'bigpk';
    const TYPE_UBIGPK = 'ubigpk';
    const TYPE_CHAR = 'char';
    const TYPE_STRING = 'string';
    const TYPE_TEXT = 'text';
    const TYPE_SMALLINT = 'smallint';
    const TYPE_INTEGER = 'integer';
    const TYPE_BIGINT = 'bigint';
    const TYPE_FLOAT = 'float';
    const TYPE_DOUBLE = 'double';
    const TYPE_DECIMAL = 'decimal';
    const TYPE_DATETIME = 'datetime';
    const TYPE_TIMESTAMP = 'timestamp';
    const TYPE_TIME = 'time';
    const TYPE_DATE = 'date';
    const TYPE_BINARY = 'binary';
    const TYPE_BOOLEAN = 'boolean';
    const TYPE_MONEY = 'money';

    /**
     * Подключение к базе данных.
     * @var Connection
     * @since 1.0.0
     */
    public $db;
    /**
     * Наименование схемы по умолчанию для данной сессии.
     * @var string
     * @since 1.0.0
     */
    public $defaultSchema;
    /**
     * Карта ошибок базы данных.
     * @var array
     * @since 1.0.0
     */
    public $exceptionMap = [
        'SQLSTATE[23' => 'intec\core\db\IntegrityException',
    ];
    /**
     * Класс схемы столбца.
     * @var string
     * @since 1.0.0
     */
    public $columnSchemaClass = 'intec\core\db\ColumnSchema';

    /**
     * Все имена схем базы данных, без системных схем.
     * @var array
     * @since 1.0.0
     */
    private $_schemaNames;
    /**
     * Все имена таблиц в базе данных.
     * @var array
     * @since 1.0.0
     */
    private $_tableNames = [];
    /**
     * Метаданные всех таблиц в базе данных.
     * @var array
     * @since 1.0.0
     */
    private $_tables = [];
    /**
     * Построитель запросов для данной схемы.
     * @var QueryBuilder
     * @since 1.0.0
     */
    private $_builder;


    /**
     * Создает схему столбца.
     * @return ColumnSchema Схема столбца.
     * @throws InvalidConfigException Неверный класс схемы.
     * @since 1.0.0
     */
    protected function createColumnSchema()
    {
        return Core::createObject($this->columnSchemaClass);
    }

    /**
     * Загружает метаданные для таблицы.
     * @param string $name Наименование таблицы.
     * @return null|TableSchema Схема таблицы или `null`, если не существует.
     * @since 1.0.0
     */
    abstract protected function loadTableSchema($name);

    /**
     * Получает метаданные для таблицы.
     * @param string $name Наименование таблицы. Может также содержать наименование схемы.
     * @param bool $refresh Обновить метаданные если они присутствуют в кеше.
     * @return null|TableSchema Схема таблицы или `null`, если таблицы не существует.
     * @since 1.0.0
     */
    public function getTableSchema($name, $refresh = false)
    {
        if (array_key_exists($name, $this->_tables) && !$refresh) {
            return $this->_tables[$name];
        }

        $db = $this->db;
        $realName = $this->getRawTableName($name);

        return $this->_tables[$name] = $this->loadTableSchema($realName);
    }

    /**
     * Возвращает метаданные для всех таблиц в базе данных.
     * @param string $schema Схема таблиц.
     * @param bool $refresh Обновить схемы таблиц, если таковые имеются в кеше.
     * @return TableSchema[] Метаданные всех таблиц в базе данных.
     * Каждый элемент массива является экземпляром [[TableSchema]].
     * @since 1.0.0
     */
    public function getTableSchemas($schema = '', $refresh = false)
    {
        $tables = [];
        foreach ($this->getTableNames($schema, $refresh) as $name) {
            if ($schema !== '') {
                $name = $schema . '.' . $name;
            }
            if (($table = $this->getTableSchema($name, $refresh)) !== null) {
                $tables[] = $table;
            }
        }

        return $tables;
    }

    /**
     * Возвращает все названия схем в базе данных.
     * @param bool $refresh Обновить наименования схем, если таковые имеются в кеше.
     * @return string[] Все названия схем в базе данных, исключая системные.
     * @since 1.0.0
     */
    public function getSchemaNames($refresh = false)
    {
        if ($this->_schemaNames === null || $refresh) {
            $this->_schemaNames = $this->findSchemaNames();
        }

        return $this->_schemaNames;
    }

    /**
     * Возвращает все названия таблиц в базе данных.
     * @param string $schema Схема таблиц.
     * @param bool $refresh Обновить наименования таблиц, если таковые имеются в кеше.
     * @return string[] Все названия таблиц.
     * @since 1.0.0
     */
    public function getTableNames($schema = '', $refresh = false)
    {
        if (!isset($this->_tableNames[$schema]) || $refresh) {
            $this->_tableNames[$schema] = $this->findTableNames($schema);
        }

        return $this->_tableNames[$schema];
    }

    /**
     * Построитель запросов для данного подключения.
     * @return QueryBuilder
     * @since 1.0.0
     */
    public function getQueryBuilder()
    {
        if ($this->_builder === null) {
            $this->_builder = $this->createQueryBuilder();
        }

        return $this->_builder;
    }

    /**
     * Определяет тип PDO для полученного значения из PHP.
     * @param mixed $data Значение.
     * @return int Тип PDO.
     * @since 1.0.0
     */
    public function getPdoType($data)
    {
        static $typeMap = [
            // php тип => PDO тип
            'boolean' => \PDO::PARAM_BOOL,
            'integer' => \PDO::PARAM_INT,
            'string' => \PDO::PARAM_STR,
            'resource' => \PDO::PARAM_LOB,
            'NULL' => \PDO::PARAM_NULL,
        ];
        $type = gettype($data);

        return isset($typeMap[$type]) ? $typeMap[$type] : \PDO::PARAM_STR;
    }

    /**
     * Очищает схемы таблиц.
     * Данный метод очищает все кешированные таблицы.
     * @since 1.0.0
     */
    public function refresh()
    {
        $this->_tableNames = [];
        $this->_tables = [];
    }

    /**
     * Очищает конкретную схему таблицы.
     * @param string $name Наименование таблицы.
     * @since 1.0.0
     */
    public function refreshTableSchema($name)
    {
        unset($this->_tables[$name]);
        $this->_tableNames = [];
    }

    /**
     * Создает построитель запросов для подключения.
     * @return QueryBuilder Построитель запросов.
     * @since 1.0.0
     */
    public function createQueryBuilder()
    {
        return new QueryBuilder($this->db);
    }

    /**
     * Создает построитель схем столбцов.
     * @param string $type Тип столбца. Смотрите [[ColumnSchemaBuilder::$type]].
     * @param int|string|array $length Длина или точность столбца. Смотрите [[ColumnSchemaBuilder::$length]].
     * @return ColumnSchemaBuilder column Построитель схем столбцов.
     * @since 1.0.0
     */
    public function createColumnSchemaBuilder($type, $length = null)
    {
        return new ColumnSchemaBuilder($type, $length);
    }

    /**
     * Возвращает все названия схем в базе данных, исключая системные.
     * @return array Все названия схем в базе данных, исключая системные.
     * @throws NotSupportedException Если данный метод не поддерживается схемой.
     * @since 1.0.0
     */
    protected function findSchemaNames()
    {
        throw new NotSupportedException(get_class($this) . ' does not support fetching all schema names.');
    }

    /**
     * Возвращает все названия таблиц в базе данных.
     * @param string $schema Схема таблиц.
     * @return array Все названия таблиц базы данных.
     * @throws NotSupportedException Если данный метод не поддерживается схемой.
     * @since 1.0.0
     */
    protected function findTableNames($schema = '')
    {
        throw new NotSupportedException(get_class($this) . ' does not support fetching all table names.');
    }

    /**
     * Возвращает все уникальные индексы таблицы.
     * @param TableSchema $table Метаданные таблицы.
     * @return array Все уникальные индексы таблицы.
     * Вид: [Наименование индекса => [Столбец]]
     * @throws NotSupportedException Если данный метод не поддерживается схемой.
     * @since 1.0.0
     */
    public function findUniqueIndexes($table)
    {
        throw new NotSupportedException(get_class($this) . ' does not support getting unique indexes information.');
    }

    /**
     * Возвращает идентификатор последней внесенной записи.
     * @param string $sequenceName
     * @return string Идентификатор последней внесенной записи.
     * @throws InvalidCallException Если подключение к базе данных неактивно.
     * @since 1.0.0
     */
    public function getLastInsertID($sequenceName = '')
    {
        if ($this->db->isActive) {
            return $this->db->pdo->lastInsertId($sequenceName === '' ? null : $this->quoteTableName($sequenceName));
        } else {
            throw new InvalidCallException('DB Connection is not active.');
        }
    }

    /**
     * Поддержка безопасных точек.
     * @return bool.
     * @since 1.0.0
     */
    public function supportsSavepoint()
    {
        return $this->db->enableSavepoint;
    }

    /**
     * Создает новую безопасную точку.
     * @param string $name Наименование безопасной точки.
     * @since 1.0.0
     */
    public function createSavepoint($name)
    {
        $this->db->createCommand("SAVEPOINT $name")->execute();
    }

    /**
     * Освобождает безопасную точку.
     * @param string $name Наименование безопасной точки.
     * @since 1.0.0
     */
    public function releaseSavepoint($name)
    {
        $this->db->createCommand("RELEASE SAVEPOINT $name")->execute();
    }

    /**
     * Откатывает к безопасной точке.
     * @param string $name Наименование безопасной точки.
     * @since 1.0.0
     */
    public function rollBackSavepoint($name)
    {
        $this->db->createCommand("ROLLBACK TO SAVEPOINT $name")->execute();
    }

    /**
     * Устанавливает уровень изоляции транзакций.
     * @param string $level Уровень изоляции транзакций.
     * @since 1.0.0
     */
    public function setTransactionIsolationLevel($level)
    {
        $this->db->createCommand("SET TRANSACTION ISOLATION LEVEL $level;")->execute();
    }

    /**
     * Выполняет команду INSERT, возвращает значения первичного ключа.
     * @param string $table Таблицы, в которую будет добавлена строка.
     * @param array $columns Данные столбцов.
     * Вид: [Строка => Значение]
     * @return array|false Значения первичного ключа или `false` при неудаче.
     * @since 1.0.0
     */
    public function insert($table, $columns)
    {
        $command = $this->db->createCommand()->insert($table, $columns);
        if (!$command->execute()) {
            return false;
        }
        $tableSchema = $this->getTableSchema($table);
        $result = [];
        foreach ($tableSchema->primaryKey as $name) {
            if ($tableSchema->columns[$name]->autoIncrement) {
                $result[$name] = $this->getLastInsertID($tableSchema->sequenceName);
                break;
            } else {
                $result[$name] = isset($columns[$name]) ? $columns[$name] : $tableSchema->columns[$name]->defaultValue;
            }
        }
        return $result;
    }

    /**
     * Экранирует строковое значение для использования в запросе.
     * @param string $str Строка для экранирования.
     * @return string Экранированная строка.
     * @since 1.0.0
     */
    public function quoteValue($str)
    {
        if (!is_string($str)) {
            return $str;
        }

        if (($value = $this->db->getSlavePdo()->quote($str)) !== false) {
            return $value;
        } else {
            // the driver doesn't support quote (e.g. oci)
            return "'" . addcslashes(str_replace("'", "''", $str), "\000\n\r\\\032") . "'";
        }
    }

    /**
     * Экранирует название таблицы для использования в запросе.
     * @param string $name Название таблицы.
     * @return string Экранированное название таблицы.
     * @see quoteSimpleTableName()
     * @since 1.0.0
     */
    public function quoteTableName($name)
    {
        if (strpos($name, '(') !== false || strpos($name, '{{') !== false) {
            return $name;
        }
        if (strpos($name, '.') === false) {
            return $this->quoteSimpleTableName($name);
        }
        $parts = explode('.', $name);
        foreach ($parts as $i => $part) {
            $parts[$i] = $this->quoteSimpleTableName($part);
        }

        return implode('.', $parts);

    }

    /**
     * Экранирует название столбца для использования в запросе.
     * @param string $name Название столбца.
     * @return string Экранированное название столбца.
     * @see quoteSimpleColumnName()
     * @since 1.0.0
     */
    public function quoteColumnName($name)
    {
        if (strpos($name, '(') !== false || strpos($name, '[[') !== false) {
            return $name;
        }
        if (($pos = strrpos($name, '.')) !== false) {
            $prefix = $this->quoteTableName(substr($name, 0, $pos)) . '.';
            $name = substr($name, $pos + 1);
        } else {
            $prefix = '';
        }
        if (strpos($name, '{{') !== false) {
            return $name;
        }
        return $prefix . $this->quoteSimpleColumnName($name);
    }

    /**
     * Экранирует обычное название таблицы (без схемы).
     * @param string $name Название таблицы.
     * @return string Экранированное название таблицы.
     * @since 1.0.0
     */
    public function quoteSimpleTableName($name)
    {
        return strpos($name, "'") !== false ? $name : "'" . $name . "'";
    }

    /**
     * Экранирует обычное название столбца (без схемы).
     * @param string $name Название столбца.
     * @return string Экранированное название столбца.
     * @since 1.0.0
     */
    public function quoteSimpleColumnName($name)
    {
        return strpos($name, '"') !== false || $name === '*' ? $name : '"' . $name . '"';
    }

    /**
     * Возвращает актуальное имя для таблицы.
     * Данный метод удаляет скобки из названия и
     * заменяет символ '%' на [[Connection::tablePrefix]].
     * @param string $name Имя таблицы для преобразования.
     * @return string Преобразованное имя таблицы.
     * @since 1.0.0
     */
    public function getRawTableName($name)
    {
        if (strpos($name, '{{') !== false) {
            $name = preg_replace('/\\{\\{(.*?)\\}\\}/', '\1', $name);

            return str_replace('%', $this->db->tablePrefix, $name);
        } else {
            return $name;
        }
    }

    /**
     * Извлекает PHP тип из типа базы данных.
     * @param ColumnSchema $column Схема столбца.
     * @return string Тип PHP.
     * @since 1.0.0
     */
    protected function getColumnPhpType($column)
    {
        static $typeMap = [
            // Тип базы данных => PHP тип
            'smallint' => 'integer',
            'integer' => 'integer',
            'bigint' => 'integer',
            'boolean' => 'boolean',
            'float' => 'double',
            'double' => 'double',
            'binary' => 'resource',
        ];
        if (isset($typeMap[$column->type])) {
            if ($column->type === 'bigint') {
                return PHP_INT_SIZE === 8 && !$column->unsigned ? 'integer' : 'string';
            } elseif ($column->type === 'integer') {
                return PHP_INT_SIZE === 4 && $column->unsigned ? 'string' : 'integer';
            } else {
                return $typeMap[$column->type];
            }
        } else {
            return 'string';
        }
    }

    /**
     * Конвертирует исключение базы данных в одно из доступных исключений если можно.
     * @param \Exception $e
     * @param string $rawSql SQL код, который вызвал исключение.
     * @return Exception Исключение.
     * @since 1.0.0
     */
    public function convertException(\Exception $e, $rawSql)
    {
        if ($e instanceof Exception) {
            return $e;
        }

        $exceptionClass = '\intec\core\db\Exception';
        foreach ($this->exceptionMap as $error => $class) {
            if (strpos($e->getMessage(), $error) !== false) {
                $exceptionClass = $class;
            }
        }
        $message = $e->getMessage()  . "\nThe SQL being executed was: $rawSql";
        $errorInfo = $e instanceof \PDOException ? $e->errorInfo : null;
        return new $exceptionClass($message, $errorInfo, (int) $e->getCode(), $e);
    }

    /**
     * Проверяет, если запрос предназначен для чтения.
     * @param string $sql Код SQL.
     * @return bool Запрос предназначен для чтения.
     * @since 1.0.0
     */
    public function isReadQuery($sql)
    {
        $pattern = '/^\s*(SELECT|SHOW|DESCRIBE)\b/i';
        return preg_match($pattern, $sql) > 0;
    }
}
