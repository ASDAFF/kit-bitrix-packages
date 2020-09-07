<?php
namespace intec\core\db;

use PDO;
use intec\Core;
use intec\core\base\Component;
use intec\core\base\InvalidConfigException;
use intec\core\base\NotSupportedException;

/**
 * Представляет собой подключение к базе данных.
 * Class Connection
 * @property string $driverName Имя драйвера баз данных.
 * @property bool $isActive Соединение с базой данных установлено. Только для чтения.
 * @property string $lastInsertID Идентификатор последней добавленной строки. Только для чтения.
 * @property Connection $master Текущее активное главное подключение. `null` будет возвращен,
 * если нет активного главного подключения. Только для чтения.
 * @property PDO $masterPdo PDO экземпляр текущего главного подключения. `null` будет возвращен,
 * если нет активного главного подключения. Только для чтения.
 * @property QueryBuilder $queryBuilder Построитель запросов для текущего подключения.
 * Только для чтения.
 * @property Schema $schema Схема базы данных, открытая данным подключением. Только для чтения.
 * @property Connection $slave Текущее открытое подчиненное подключение. `null` будет возвращен,
 * если нет активного подчиненного подключения. Только для чтения.
 * @property PDO $slavePdo PDO экземпляр текущего подчиненного подключения. `null` будет возвращен,
 * если нет активного подчиненного подключения. Только для чтения.
 * @property Transaction $transaction Текущая активная транзакция или `null`, если нет транзакции.
 * Только для чтения.
 * @package intec\core\db
 * @since 1.0.0
 */
class Connection extends Component
{
    /**
     * Событие, возникающее после установки подключения.
     * @event Event
     * @since 1.0.0
     */
    const EVENT_AFTER_OPEN = 'afterOpen';
    /**
     * Событие, возникающее после того, как транзакция стартовала.
     * @event Event
     * @since 1.0.0
     */
    const EVENT_BEGIN_TRANSACTION = 'beginTransaction';
    /**
     * Событие, возникающее после того, как транзакция была выполнена.
     * @event Event
     * @since 1.0.0
     */
    const EVENT_COMMIT_TRANSACTION = 'commitTransaction';
    /**
     * Событие, возникающее после того, как транзакцию откатили.
     * @event Event
     * @since 1.0.0
     */
    const EVENT_ROLLBACK_TRANSACTION = 'rollbackTransaction';

    /**
     * Источник данных.
     * @var string
     * @since 1.0.0
     */
    public $dsn;
    /**
     * Имя пользователя для подключения.
     * @var string
     * @since 1.0.0
     */
    public $username;
    /**
     * Пароль для подключения.
     * @var string
     * @since 1.0.0
     */
    public $password;
    /**
     * PDO аттрибуты, которые будут отправлены при вызове [[open()]]
     * Вид: [Имя => Значение]
     * @var array
     * @since 1.0.0
     */
    public $attributes;
    /**
     * PDO экземпляр, ассоциирующийся с данным подключением.
     * @var PDO
     * @since 1.0.0
     */
    public $pdo;
    /**
     * Кодировка, используемая при подключении к базе данных.
     * @var string
     * @since 1.0.0
     */
    public $charset;
    /**
     * @var bool
     * @since 1.0.0
     */
    public $emulatePrepare;
    /**
     * Префикс таблиц.
     * @var string
     * @since 1.0.0
     */
    public $tablePrefix = '';
    /**
     * Карта между драйвером PDO и классами схем [[Schema]].
     * @var array
     * @since 1.0.0
     */
    public $schemaMap = [
        'mysqli' => 'intec\core\db\mysql\Schema', // MySQL
        'mysql' => 'intec\core\db\mysql\Schema', // MySQL
    ];
    /**
     * @var string
     * @see pdo
     * @since 1.0.0
     */
    public $pdoClass;
    /**
     * Класс, используемый для создания нового объекта [[Command]].
     * @var string
     * @see createCommand
     * @since 1.0.0
     */
    public $commandClass = 'intec\core\db\Command';
    /**
     * Активация механизма точек сохранения.
     * @var bool
     * @since 1.0.0
     */
    public $enableSavepoint = true;
    /**
     * @var int
     * @since 1.0.0
     */
    public $serverRetryInterval = 600;
    /**
     * @var bool
     * @since 1.0.0
     */
    public $enableSlaves = true;
    /**
     * @var array
     * @see enableSlaves
     * @see slaveConfig
     * @since 1.0.0
     */
    public $slaves = [];
    /**
     * @var array
     * @since 1.0.0
     */
    public $slaveConfig = [];
    /**
     * @var array
     * @see masterConfig
     * @see shuffleMasters
     * @since 1.0.0
     */
    public $masters = [];
    /**
     * @var array
     * @since 1.0.0
     */
    public $masterConfig = [];
    /**
     * @var bool
     * @see masters
     * @since 1.0.0
     */
    public $shuffleMasters = true;

    /**
     * Текущая активная транзакция.
     * @var Transaction
     * @since 1.0.0
     */
    private $_transaction;
    /**
     * Схема базы данных.
     * @var Schema
     * @since 1.0.0
     */
    private $_schema;
    /**
     * Наименование драйвера.
     * @var string
     * @since 1.0.0
     */
    private $_driverName;
    /**
     * Текущее активное главное подключение.
     * @var Connection
     * @since 1.0.0
     */
    private $_master = false;
    /**
     * Текущее активное подчиненное подключение.
     * @var Connection
     * @since 1.0.0
     */
    private $_slave = false;

    /**
     * Активность подключения.
     * @return bool Подключение выполнено.
     * @since 1.0.0
     */
    public function getIsActive()
    {
        return $this->pdo !== null;
    }

    /**
     * Производит подключение к базе данных.
     * @throws Exception Если подключение не удалось.
     * @since 1.0.0
     */
    public function open()
    {
        if ($this->pdo !== null) {
            return;
        }

        if (!empty($this->masters)) {
            $db = $this->getMaster();
            if ($db !== null) {
                $this->pdo = $db->pdo;
                return;
            } else {
                throw new InvalidConfigException('None of the master DB servers is available.');
            }
        }

        if (empty($this->dsn)) {
            throw new InvalidConfigException('Connection::dsn cannot be empty.');
        }
        try {
            $this->pdo = $this->createPdoInstance();
            $this->initConnection();
        } catch (\PDOException $e) {
            throw new Exception($e->getMessage(), $e->errorInfo, (int) $e->getCode(), $e);
        }
    }

    /**
     * Закрывает текущее активное подключение.
     * @since 1.0.0
     */
    public function close()
    {
        if ($this->_master) {
            if ($this->pdo === $this->_master->pdo) {
                $this->pdo = null;
            }

            $this->_master->close();
            $this->_master = null;
        }

        if ($this->pdo !== null) {
            $this->pdo = null;
            $this->_schema = null;
            $this->_transaction = null;
        }

        if ($this->_slave) {
            $this->_slave->close();
            $this->_slave = null;
        }
    }

    /**
     * Создает экземпляр PDO.
     * @return PDO Экземпляр PDO.
     * @since 1.0.0
     */
    protected function createPdoInstance()
    {
        $pdoClass = $this->pdoClass;
        if ($pdoClass === null) {
            $pdoClass = 'PDO';
            if ($this->_driverName !== null) {
                $driver = $this->_driverName;
            } elseif (($pos = strpos($this->dsn, ':')) !== false) {
                $driver = strtolower(substr($this->dsn, 0, $pos));
            }
            if (isset($driver)) {
                if ($driver === 'mssql' || $driver === 'dblib') {
                    $pdoClass = 'intec\core\db\mssql\PDO';
                } elseif ($driver === 'sqlsrv') {
                    $pdoClass = 'intec\core\db\mssql\SqlsrvPDO';
                }
            }
        }

        $dsn = $this->dsn;
        if (strncmp('sqlite:@', $dsn, 8) === 0) {
            $dsn = 'sqlite:' . Core::getAlias(substr($dsn, 7));
        }
        return new $pdoClass($dsn, $this->username, $this->password, $this->attributes);
    }

    /**
     * Инициализирует подключение к базе данных.
     * @since 1.0.0
     */
    protected function initConnection()
    {
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        if ($this->emulatePrepare !== null && constant('PDO::ATTR_EMULATE_PREPARES')) {
            $this->pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, $this->emulatePrepare);
        }
        if ($this->charset !== null && in_array($this->getDriverName(), ['pgsql', 'mysql', 'mysqli', 'cubrid'], true)) {
            $this->pdo->exec('SET NAMES ' . $this->pdo->quote($this->charset));
        }
        $this->trigger(self::EVENT_AFTER_OPEN);
    }

    /**
     * Создает команду для выполнения.
     * @param string $sql SQL код для выполнения.
     * @param array $params Параметры для вставки в SQL код.
     * @return Command Команда к базе данных.
     * @since 1.0.0
     */
    public function createCommand($sql = null, $params = [])
    {
        /** @var Command $command */
        $command = new $this->commandClass([
            'db' => $this,
            'sql' => $sql,
        ]);

        return $command->bindValues($params);
    }

    /**
     * Возвращает текущую активную транзакцию.
     * @return Transaction Текущая активная транзакция.
     * @since 1.0.0
     */
    public function getTransaction()
    {
        return $this->_transaction && $this->_transaction->getIsActive() ? $this->_transaction : null;
    }

    /**
     * Запускает транзакцию.
     * @param string|null $isolationLevel Уровень изоляции транзакции.
     * @return Transaction Инициированная транзакция.
     * @since 1.0.0
     */
    public function beginTransaction($isolationLevel = null)
    {
        $this->open();

        if (($transaction = $this->getTransaction()) === null) {
            $transaction = $this->_transaction = new Transaction(['db' => $this]);
        }
        $transaction->begin($isolationLevel);

        return $transaction;
    }

    /**
     * Вызывает функцию обратного вызова для проведения транзакции.
     *
     * @param callable $callback Функция обратного вызова.
     * @param string|null $isolationLevel Уровень изоляции транзакции.
     * @throws \Exception|\Throwable Если возникли ошибки, то будет откат транзакции.
     * @return mixed Результат функции обратного вызова.
     * @since 1.0.0
     */
    public function transaction(callable $callback, $isolationLevel = null)
    {
        $transaction = $this->beginTransaction($isolationLevel);
        $level = $transaction->level;

        try {
            $result = call_user_func($callback, $this);
            if ($transaction->isActive && $transaction->level === $level) {
                $transaction->commit();
            }
        } catch (\Exception $e) {
            if ($transaction->isActive && $transaction->level === $level) {
                $transaction->rollBack();
            }
            throw $e;
        } catch (\Throwable $e) {
            if ($transaction->isActive && $transaction->level === $level) {
                $transaction->rollBack();
            }
            throw $e;
        }

        return $result;
    }

    /**
     * Возвращает схему базы данных текущего подключения.
     * @return Schema Схема базы данных текущего подключения.
     * @throws NotSupportedException Если не поддерживается текущий драйвер.
     * @since 1.0.0
     */
    public function getSchema()
    {
        if ($this->_schema !== null) {
            return $this->_schema;
        } else {
            $driver = $this->getDriverName();
            if (isset($this->schemaMap[$driver])) {
                $config = !is_array($this->schemaMap[$driver]) ? ['class' => $this->schemaMap[$driver]] : $this->schemaMap[$driver];
                $config['db'] = $this;

                return $this->_schema = Core::createObject($config);
            } else {
                throw new NotSupportedException("Connection does not support reading schema information for '$driver' DBMS.");
            }
        }
    }

    /**
     * Возвращает построитель запросов для текущего подключения.
     * @return QueryBuilder Построитель запросов для текущего подключения.
     * @since 1.0.0
     */
    public function getQueryBuilder()
    {
        return $this->getSchema()->getQueryBuilder();
    }

    /**
     * Получает метаданные о таблице.
     * @param string $name Название таблицы.
     * @param bool $refresh Обновить кеш.
     * @return TableSchema Метаданные таблицы. Будет возвращен `null`,
     * если таблицы не существует.
     * @since 1.0.0
     */
    public function getTableSchema($name, $refresh = false)
    {
        return $this->getSchema()->getTableSchema($name, $refresh);
    }

    /**
     * Возвращает идентификатор последней внесенной строки.
     * @param string $sequenceName
     * @return string Идентификатор строки.
     * @since 1.0.0
     */
    public function getLastInsertID($sequenceName = '')
    {
        return $this->getSchema()->getLastInsertID($sequenceName);
    }

    /**
     * Экранирует строковое значение для использования в запросе.
     * @param string $value Строка для экранирования.
     * @return string Экранированная строка.
     * @since 1.0.0
     */
    public function quoteValue($value)
    {
        return $this->getSchema()->quoteValue($value);
    }

    /**
     * Экранирует название таблицы для использования в запросе.
     * @param string $name Название таблицы.
     * @return string Экранированное название таблицы.
     * @since 1.0.0
     */
    public function quoteTableName($name)
    {
        return $this->getSchema()->quoteTableName($name);
    }

    /**
     * Экранирует название столбца для использования в запросе.
     * @param string $name Название столбца.
     * @return string Экранированное название столбца.
     * @since 1.0.0
     */
    public function quoteColumnName($name)
    {
        return $this->getSchema()->quoteColumnName($name);
    }

    /**
     * Обрабатывает код SQL, цитируя имена таблиц и столбцов,
     * заключенные в двойные скобки.
     * @param string $sql SQL код для экранирования.
     * @return string Экранированный SQL код.
     */
    public function quoteSql($sql)
    {
        return preg_replace_callback(
            '/(\\{\\{(%?[\w\-\. ]+%?)\\}\\}|\\[\\[([\w\-\. ]+)\\]\\])/',
            function ($matches) {
                if (isset($matches[3])) {
                    return $this->quoteColumnName($matches[3]);
                } else {
                    return str_replace('%', $this->tablePrefix, $this->quoteTableName($matches[2]));
                }
            },
            $sql
        );
    }

    /**
     * Возвращает наименование драйвера.
     * @return string Наименование драйвера.
     * @since 1.0.0
     */
    public function getDriverName()
    {
        if ($this->_driverName === null) {
            if (($pos = strpos($this->dsn, ':')) !== false) {
                $this->_driverName = strtolower(substr($this->dsn, 0, $pos));
            } else {
                $this->_driverName = strtolower($this->getSlavePdo()->getAttribute(PDO::ATTR_DRIVER_NAME));
            }
        }
        return $this->_driverName;
    }

    /**
     * Изменяет имя драйвера.
     * @param string $driverName Наименование драйвера.
     * @since 1.0.0
     */
    public function setDriverName($driverName)
    {
        $this->_driverName = strtolower($driverName);
    }

    /**
     * @param bool $fallbackToMaster
     * @return PDO
     * @since 1.0.0
     */
    public function getSlavePdo($fallbackToMaster = true)
    {
        $db = $this->getSlave(false);
        if ($db === null) {
            return $fallbackToMaster ? $this->getMasterPdo() : null;
        } else {
            return $db->pdo;
        }
    }

    /**
     * @return PDO
     * @since 1.0.0
     */
    public function getMasterPdo()
    {
        $this->open();
        return $this->pdo;
    }

    /**
     * @param bool $fallbackToMaster
     * @return Connection
     * @since 1.0.0
     */
    public function getSlave($fallbackToMaster = true)
    {
        if (!$this->enableSlaves) {
            return $fallbackToMaster ? $this : null;
        }

        if ($this->_slave === false) {
            $this->_slave = $this->openFromPool($this->slaves, $this->slaveConfig);
        }

        return $this->_slave === null && $fallbackToMaster ? $this : $this->_slave;
    }

    /**
     * @return Connection
     * @since 1.0.0
     */
    public function getMaster()
    {
        if ($this->_master === false) {
            $this->_master = ($this->shuffleMasters)
                ? $this->openFromPool($this->masters, $this->masterConfig)
                : $this->openFromPoolSequentially($this->masters, $this->masterConfig);
        }

        return $this->_master;
    }

    /**
     * @param callable $callback
     * @return mixed
     * @since 1.0.0
     */
    public function useMaster(callable $callback)
    {
        $enableSlave = $this->enableSlaves;
        $this->enableSlaves = false;
        $result = call_user_func($callback, $this);
        $this->enableSlaves = $enableSlave;
        return $result;
    }

    /**
     * Открывает подключение из набора подключений.
     * @param array $pool Список конфигураций подключений.
     * @param array $sharedConfig Общая конфигурация для набора подключений.
     * @return Connection Открытое подключение к базе.
     * @throws InvalidConfigException Если конфигурация не содержит dsn.
     * @since 1.0.0
     */
    protected function openFromPool(array $pool, array $sharedConfig)
    {
        shuffle($pool);
        return $this->openFromPoolSequentially($pool, $sharedConfig);
    }

    /**
     * Открывает подключение из набора подключений последовательно.
     * @param array $pool Список конфигураций подключений.
     * @param array $sharedConfig Общая конфигурация для набора подключений.
     * @return Connection Открытое подключение к базе.
     * @throws InvalidConfigException Если конфигурация не содержит dsn.
     * @since 1.0.0
     */
    protected function openFromPoolSequentially(array $pool, array $sharedConfig)
    {
        if (empty($pool)) {
            return null;
        }

        if (!isset($sharedConfig['class'])) {
            $sharedConfig['class'] = get_class($this);
        }

        foreach ($pool as $config) {
            $config = array_merge($sharedConfig, $config);
            if (empty($config['dsn'])) {
                throw new InvalidConfigException('The "dsn" option must be specified.');
            }

            /* @var $db Connection */
            $db = Core::createObject($config);

            try {
                $db->open();
                return $db;
            } catch (\Exception $e) {}
        }

        return null;
    }

    /**
     * Закрытие подключения перед сериализацией.
     * @return array
     * @since 1.0.0
     */
    public function __sleep()
    {
        $this->close();
        return array_keys((array) $this);
    }
}
