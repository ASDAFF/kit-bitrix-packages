<?php
namespace intec\core\db;

use intec\Core;
use intec\core\base\Component;
use intec\core\base\NotSupportedException;

/**
 * Класс представляет собой код SQL, который должен быть выполнен в базе данных.
 * @property string $rawSql Необработанный SQL код, с замененными значениями.
 * Только для чтения.
 * @property string $sql SQL код, который будет выполнен.
 * @since 1.0.0
 */
class Command extends Component
{
    /**
     * Подключение, с которым ассоциирована данная команда.
     * @var Connection
     * @since 1.0.0
     */
    public $db;
    /**
     * Объект PDOStatement, с которым ассоциирована команда.
     * @var \PDOStatement
     * @since 1.0.0
     */
    public $pdoStatement;
    /**
     * Режим выборки по умолчанию для данной команды.
     * @var int
     * @since 1.0.0
     */
    public $fetchMode = \PDO::FETCH_ASSOC;
    /**
     * Параметры, которые привязаны к текущему оператору PDO.
     * Вид: [Наименование => Значение]
     * @var array
     * @since 1.0.0
     */
    public $params = [];

    /**
     * Параметры, которые будут привязаны к текущему оператору PDO.
     * @var array
     * @since 1.0.0
     */
    private $_pendingParams = [];
    /**
     * SQL код, который представляет данную команду.
     * @var string
     * @since 1.0.0
     */
    private $_sql;
    /**
     * Название схемы таблицы, которая будет обновлена после выполнения команды.
     * @var string
     * @since 1.0.0
     */
    private $_refreshTableName;

    /**
     * Возвращает SQL код данной команды.
     * @return string Возвращает SQL код данной команды.
     * @since 1.0.0
     */
    public function getSql()
    {
        return $this->_sql;
    }

    /**
     * Устанавливает SQL код команды.
     * @param string $sql SQL код.
     * @return $this Текущая команда.
     * @since 1.0.0
     */
    public function setSql($sql)
    {
        if ($sql !== $this->_sql) {
            $this->cancel();
            $this->_sql = $this->db->quoteSql($sql);
            $this->_pendingParams = [];
            $this->params = [];
            $this->_refreshTableName = null;
        }

        return $this;
    }

    /**
     * Возвращает SQL код с внесенными в него параметрами.
     * @return string SQL код с внесенными параметрами.
     * @since 1.0.0
     */
    public function getRawSql()
    {
        if (empty($this->params)) {
            return $this->_sql;
        }
        $params = [];
        foreach ($this->params as $name => $value) {
            if (is_string($name) && strncmp(':', $name, 1)) {
                $name = ':' . $name;
            }
            if (is_string($value)) {
                $params[$name] = $this->db->quoteValue($value);
            } elseif (is_bool($value)) {
                $params[$name] = ($value ? 'TRUE' : 'FALSE');
            } elseif ($value === null) {
                $params[$name] = 'NULL';
            } elseif (!is_object($value) && !is_resource($value)) {
                $params[$name] = $value;
            }
        }
        if (!isset($params[1])) {
            return strtr($this->_sql, $params);
        }
        $sql = '';
        foreach (explode('?', $this->_sql) as $i => $part) {
            $sql .= (isset($params[$i]) ? $params[$i] : '') . $part;
        }

        return $sql;
    }

    /**
     * Подготавливает запрос перед выполнением.
     * @param bool $forRead Запрос для чтения. Если `null`, то определится автоматически.
     * @throws Exception При возникновении ошибки.
     * @since 1.0.0
     */
    public function prepare($forRead = null)
    {
        if ($this->pdoStatement) {
            $this->bindPendingParams();
            return;
        }

        $sql = $this->getSql();

        if ($this->db->getTransaction()) {
            // master is in a transaction. use the same connection.
            $forRead = false;
        }
        if ($forRead || $forRead === null && $this->db->getSchema()->isReadQuery($sql)) {
            $pdo = $this->db->getSlavePdo();
        } else {
            $pdo = $this->db->getMasterPdo();
        }

        try {
            $this->pdoStatement = $pdo->prepare($sql);
            $this->bindPendingParams();
        } catch (\Exception $e) {
            $message = $e->getMessage() . "\nFailed to prepare SQL: $sql";
            $errorInfo = $e instanceof \PDOException ? $e->errorInfo : null;
            throw new Exception($message, $errorInfo, (int) $e->getCode(), $e);
        }
    }

    /**
     * Отменяет выполнение команды.
     * @since 1.0.0
     */
    public function cancel()
    {
        $this->pdoStatement = null;
    }

    /**
     * Привязывает параметры к SQL коду, который будет выполнен.
     * @param string|int $name Идентификатор параметра.
     * @param mixed $value Значение.
     * @param int $dataType Тип данных SQL. Если `null`, то определяется автоматически.
     * @param int $length Длина данных.
     * @param mixed $driverOptions Специфические настройки драйвера.
     * @return $this Текущая команда.
     * @since 1.0.0
     */
    public function bindParam($name, &$value, $dataType = null, $length = null, $driverOptions = null)
    {
        $this->prepare();

        if ($dataType === null) {
            $dataType = $this->db->getSchema()->getPdoType($value);
        }
        if ($length === null) {
            $this->pdoStatement->bindParam($name, $value, $dataType);
        } elseif ($driverOptions === null) {
            $this->pdoStatement->bindParam($name, $value, $dataType, $length);
        } else {
            $this->pdoStatement->bindParam($name, $value, $dataType, $length, $driverOptions);
        }
        $this->params[$name] =& $value;

        return $this;
    }

    /**
     * Привязывает параметры в очереди.
     * @since 1.0.0
     */
    protected function bindPendingParams()
    {
        foreach ($this->_pendingParams as $name => $value) {
            $this->pdoStatement->bindValue($name, $value[0], $value[1]);
        }
        $this->_pendingParams = [];
    }

    /**
     * Привязывает значение к параметру.
     * @param string|int $name Идентификатор параметра.
     * @param mixed $value Значение параметра.
     * @param int $dataType Тип данных SQL. Если `null`, то определяется автоматически.
     * @return $this Текущая команда.
     * @since 1.0.0
     */
    public function bindValue($name, $value, $dataType = null)
    {
        if ($dataType === null) {
            $dataType = $this->db->getSchema()->getPdoType($value);
        }
        $this->_pendingParams[$name] = [$value, $dataType];
        $this->params[$name] = $value;

        return $this;
    }

    /**
     * Привязывает список параметров.
     * @param array $values Значения для привязки.
     * Вид: [:Параметр => Значение]
     * @return $this Текущая команда.
     * @since 1.0.0
     */
    public function bindValues($values)
    {
        if (empty($values)) {
            return $this;
        }

        $schema = $this->db->getSchema();
        foreach ($values as $name => $value) {
            if (is_array($value)) {
                $this->_pendingParams[$name] = $value;
                $this->params[$name] = $value[0];
            } else {
                $type = $schema->getPdoType($value);
                $this->_pendingParams[$name] = [$value, $type];
                $this->params[$name] = $value;
            }
        }

        return $this;
    }

    /**
     * Выполняет SQL код и возвращает результат запроса.
     * Данный метод выполняет SQL код и возвращает результат запроса для `SELECT`.
     * @return DataReader Объект с результатами.
     * @throws Exception Выполнение прервано.
     * @since 1.0.0
     */
    public function query()
    {
        return $this->queryInternal('');
    }

    /**
     * Выполняет SQL код и возвращает все строки за раз.
     * @param int $fetchMode Режим выборки результатов.
     * @return array Все строки результата.
     * @throws Exception Выполнение прервано.
     * @since 1.0.0
     */
    public function queryAll($fetchMode = null)
    {
        return $this->queryInternal('fetchAll', $fetchMode);
    }

    /**
     * Выполняет SQL код и возвращает первую строку результата.
     * @param int $fetchMode Режим выборки результатов.
     * @return array|false Первая строка результат или `false` если результат пуст.
     * @throws Exception Выполнение прервано.
     * @since 1.0.0
     */
    public function queryOne($fetchMode = null)
    {
        return $this->queryInternal('fetch', $fetchMode);
    }

    /**
     * Выполняет SQL код и возвращает первый столбец первой строки результата.
     * @return string|null|false Первый столбец первой строки результа или `false` если результат пуст.
     * @throws Exception Выполнение прервано.
     * @since 1.0.0
     */
    public function queryScalar()
    {
        $result = $this->queryInternal('fetchColumn', 0);
        if (is_resource($result) && get_resource_type($result) === 'stream') {
            return stream_get_contents($result);
        } else {
            return $result;
        }
    }

    /**
     * Выполняет SQL код и возвращает первый столбец результата.
     * @return array Первый столбец результата. Пустой массив если результат пуст.
     * @throws Exception Выполнение прервано.
     * @since 1.0.0
     */
    public function queryColumn()
    {
        return $this->queryInternal('fetchAll', \PDO::FETCH_COLUMN);
    }

    /**
     * Генерирует команду INSERT.
     * @param string $table Таблица, в которую будут добавлены данные.
     * @param array|Query $columns Данные столбцов.
     * Вид: [Строка => Значение]
     * @return $this Текущая команда.
     * @since 1.0.0
     */
    public function insert($table, $columns)
    {
        $params = [];
        $sql = $this->db->getQueryBuilder()->insert($table, $columns, $params);

        return $this->setSql($sql)->bindValues($params);
    }

    /**
     * Создает множественную команду INSERT.
     * @param string $table Таблица, в которую будут добавлены данные.
     * @param array $columns Имена столбцов.
     * @param array $rows Данные столбцов.
     * @return $this Текущая команда.
     * @since 1.0.0
     */
    public function batchInsert($table, $columns, $rows)
    {
        $sql = $this->db->getQueryBuilder()->batchInsert($table, $columns, $rows);

        return $this->setSql($sql);
    }

    /**
     * Создает команду UPDATE.
     * @param string $table Таблица, в которой будут обновлены данные.
     * @param array $columns Значения столбцов для обновления.
     * Вид: [Столбец => Значение]
     * @param string|array $condition Условия.
     * @param array $params Параметры для привязки к команде.
     * @return $this Текущая команда.
     * @since 1.0.0
     */
    public function update($table, $columns, $condition = '', $params = [])
    {
        $sql = $this->db->getQueryBuilder()->update($table, $columns, $condition, $params);

        return $this->setSql($sql)->bindValues($params);
    }

    /**
     * Создает команду DELETE.
     * @param string $table Таблица, в которой будут обновлены данные.
     * @param string|array $condition Условия.
     * @param array $params Параметры для привязки к команде.
     * @return $this Текущая команда.
     * @since 1.0.0
     */
    public function delete($table, $condition = '', $params = [])
    {
        $sql = $this->db->getQueryBuilder()->delete($table, $condition, $params);

        return $this->setSql($sql)->bindValues($params);
    }

    /**
     * Создает SQL код для создания новой таблицы.
     * @param string $table Имя таблицы.
     * @param array $columns Стобцы.
     * Вид: [Название столбца => Определение столбца]
     * @param string $options Дополнительный фрагмент SQL кода, который будет добавлен в конец.
     * @return $this Текущая команда.
     * @since 1.0.0
     */
    public function createTable($table, $columns, $options = null)
    {
        $sql = $this->db->getQueryBuilder()->createTable($table, $columns, $options);

        return $this->setSql($sql);
    }

    /**
     * Создает SQL код для переименования таблицы.
     * @param string $table Таблица, которую необходимо переименовать.
     * @param string $newName Новое название таблицы.
     * @return $this Текущая команда.
     * @since 1.0.0
     */
    public function renameTable($table, $newName)
    {
        $sql = $this->db->getQueryBuilder()->renameTable($table, $newName);

        return $this->setSql($sql)->requireTableSchemaRefresh($table);
    }

    /**
     * Создает SQL код для удаления таблицы.
     * @param string $table Таблица для удаления.
     * @return $this Текущая команда.
     * @since 1.0.0
     */
    public function dropTable($table)
    {
        $sql = $this->db->getQueryBuilder()->dropTable($table);

        return $this->setSql($sql)->requireTableSchemaRefresh($table);
    }

    /**
     * Создает SQL код для очистки таблицы.
     * @param string $table Таблица для очистки.
     * @return $this Текущая команда.
     * @since 1.0.0
     */
    public function truncateTable($table)
    {
        $sql = $this->db->getQueryBuilder()->truncateTable($table);

        return $this->setSql($sql);
    }

    /**
     * Создает SQL код для добавления столбца таблицы.
     * @param string $table Таблица, в которую будет добавлен столбец.
     * @param string $column Наименование столбца.
     * @param string $type Тип столбца.
     * @return $this Текущая команда.
     * @since 1.0.0
     */
    public function addColumn($table, $column, $type)
    {
        $sql = $this->db->getQueryBuilder()->addColumn($table, $column, $type);

        return $this->setSql($sql)->requireTableSchemaRefresh($table);
    }

    /**
     * Создает SQL код для удаления столбца таблицы.
     * @param string $table Таблица, откуда будет удален столбец.
     * @param string $column Столбец, который будет удален.
     * @return $this Текущая команда.
     * @since 1.0.0
     */
    public function dropColumn($table, $column)
    {
        $sql = $this->db->getQueryBuilder()->dropColumn($table, $column);

        return $this->setSql($sql)->requireTableSchemaRefresh($table);
    }

    /**
     * Создает SQL код для переименования столбца таблицы.
     * @param string $table Таблица, где будет переименован столбец.
     * @param string $oldName Столбец, который будет переименован.
     * @param string $newName Новое название столбца.
     * @return $this Текущая команда.
     * @since 1.0.0
     */
    public function renameColumn($table, $oldName, $newName)
    {
        $sql = $this->db->getQueryBuilder()->renameColumn($table, $oldName, $newName);

        return $this->setSql($sql)->requireTableSchemaRefresh($table);
    }

    /**
     * Создает SQL код для изменения типа столбца.
     * @param string $table Таблица, где будет изменен столбец.
     * @param string $column Столбец, который будет изменен.
     * @param string $type Тип столбца.
     * @return $this Текущая команда.
     * @since 1.0.0
     */
    public function alterColumn($table, $column, $type)
    {
        $sql = $this->db->getQueryBuilder()->alterColumn($table, $column, $type);

        return $this->setSql($sql)->requireTableSchemaRefresh($table);
    }

    /**
     * Создает SQL код для добавления первичного ключа.
     * @param string $name Название первичного ключа.
     * @param string $table Таблица, куда будет добавлен первичный ключ.
     * @param string|array $columns Столбцы, если несколько, то разделяются запятыми.
     * @return $this Текущая команда.
     * @since 1.0.0
     */
    public function addPrimaryKey($name, $table, $columns)
    {
        $sql = $this->db->getQueryBuilder()->addPrimaryKey($name, $table, $columns);

        return $this->setSql($sql)->requireTableSchemaRefresh($table);
    }

    /**
     * Создает SQL код для удаления первичного ключа.
     * @param string $name Название первичного ключа.
     * @param string $table Таблица, откуда будет удален первичный ключ.
     * @return $this Текущая команда.
     * @since 1.0.0
     */
    public function dropPrimaryKey($name, $table)
    {
        $sql = $this->db->getQueryBuilder()->dropPrimaryKey($name, $table);

        return $this->setSql($sql)->requireTableSchemaRefresh($table);
    }

    /**
     * Создает SQL код для добавления внешнего ключа.
     * @param string $name Название внешнего ключа.
     * @param string $table Таблица, куда будет добавлен внешний ключ.
     * @param string|array $columns Столбцы, если несколько, то разделяются запятыми.
     * @param string $refTable Таблица, к которой привязывается внешний ключ.
     * @param string|array $refColumns Столбцы таблицы, к которой привязывается внешний ключ. Если несколько, то разделяются запятыми.
     * @param string $delete ON DELETE действие. Доступные значения: RESTRICT, CASCADE, NO ACTION, SET DEFAULT, SET NULL.
     * @param string $update ON UPDATE действие. Доступные значения: RESTRICT, CASCADE, NO ACTION, SET DEFAULT, SET NULL.
     * @return $this Текущая команда.
     * @since 1.0.0
     */
    public function addForeignKey($name, $table, $columns, $refTable, $refColumns, $delete = null, $update = null)
    {
        $sql = $this->db->getQueryBuilder()->addForeignKey($name, $table, $columns, $refTable, $refColumns, $delete, $update);

        return $this->setSql($sql);
    }

    /**
     * Создает SQL код для удаления внешнего ключа.
     * @param string $name Название внешнего ключа.
     * @param string $table Таблица, откуда будет удален первичный ключ.
     * @return $this Текущая команда.
     * @since 1.0.0
     */
    public function dropForeignKey($name, $table)
    {
        $sql = $this->db->getQueryBuilder()->dropForeignKey($name, $table);

        return $this->setSql($sql);
    }

    /**
     * Создает SQL код для создания индекса.
     * @param string $name Наименование индекса.
     * @param string $table Таблица, куда будет добавлен индекс.
     * @param string|array $columns Столбцы индекса. Если несколько, то разделяются запятыми.
     * @param bool $unique Добавлять UNIQUE к созданному индексу.
     * @return $this Текущая команда.
     * @since 1.0.0
     */
    public function createIndex($name, $table, $columns, $unique = false)
    {
        $sql = $this->db->getQueryBuilder()->createIndex($name, $table, $columns, $unique);

        return $this->setSql($sql);
    }

    /**
     * Создает SQL код для удаления индекса.
     * @param string $name Наименование индекса.
     * @param string $table Таблица, откуда будет удален индекс.
     * @return $this Текущая команда.
     * @since 1.0.0
     */
    public function dropIndex($name, $table)
    {
        $sql = $this->db->getQueryBuilder()->dropIndex($name, $table);

        return $this->setSql($sql);
    }

    /**
     * Создает SQL код для сброса значения последовательности первичного ключа таблицы.
     * @param string $table Наименование таблицы.
     * @param mixed $value Значение.
     * @return $this Текущая команда.
     * @throws NotSupportedException Не поддерживается базой данных.
     * @since 1.0.0
     */
    public function resetSequence($table, $value = null)
    {
        $sql = $this->db->getQueryBuilder()->resetSequence($table, $value);

        return $this->setSql($sql);
    }

    /**
     * Создает SQL код для включения или отключения проверки целостности.
     * @param bool $check Проверка целостности включена.
     * @param string $schema Схема базы данных.
     * @param string $table Наименование таблицы.
     * @return $this Текущая команда.
     * @throws NotSupportedException Не поддерживается базой данных.
     * @since 1.0.0
     */
    public function checkIntegrity($check = true, $schema = '', $table = '')
    {
        $sql = $this->db->getQueryBuilder()->checkIntegrity($check, $schema, $table);

        return $this->setSql($sql);
    }

    /**
     * Создает SQL код для добавления комментария к столбцу.
     * @param string $table Таблица, к столбцу которой будет добавлен комментарий.
     * @param string $column Столбец, к которому будет добавлен комментарий.
     * @param string $comment Текст комментария.
     * @return $this Текущая команда.
     * @since 1.0.0
     */
    public function addCommentOnColumn($table, $column, $comment)
    {
        $sql = $this->db->getQueryBuilder()->addCommentOnColumn($table, $column, $comment);

        return $this->setSql($sql);
    }

    /**
     * Создает SQL код для добавления комментария к таблице.
     * @param string $table Таблица, к которой будет добавлен комментарий.
     * @param string $comment Текст комментария.
     * @return $this Текущая команда.
     * @since 1.0.0
     */
    public function addCommentOnTable($table, $comment)
    {
        $sql = $this->db->getQueryBuilder()->addCommentOnTable($table, $comment);

        return $this->setSql($sql);
    }

    /**
     * Создает SQL код для удаления комментария к столбцу.
     * @param string $table Таблица, из столбца которой будет удален комментарий.
     * @param string $column Столбец, у которого будет удален комментарий.
     * @return $this Текущая команда.
     * @since 1.0.0
     */
    public function dropCommentFromColumn($table, $column)
    {
        $sql = $this->db->getQueryBuilder()->dropCommentFromColumn($table, $column);

        return $this->setSql($sql);
    }

    /**
     * Создает SQL код для удаления комментария к таблице.
     * @param string $table Таблица, у которой будет удален комментарий.
     * @return $this Текущая команда.
     * @since 1.0.0
     */
    public function dropCommentFromTable($table)
    {
        $sql = $this->db->getQueryBuilder()->dropCommentFromTable($table);

        return $this->setSql($sql);
    }

    /**
     * Выполняет SQL код без результата.
     * @return int number Количество строк, которых затронуло изменение.
     * @throws Exception Выполнение прервано.
     * @since 1.0.0
     */
    public function execute()
    {
        $sql = $this->getSql();

        $rawSql = $this->getRawSql();

        if ($sql == '') {
            return 0;
        }

        $this->prepare(false);

        $token = $rawSql;
        try {
            $this->pdoStatement->execute();
            $n = $this->pdoStatement->rowCount();
            $this->refreshTableSchema();

            return $n;
        } catch (\Exception $e) {
            throw $this->db->getSchema()->convertException($e, $rawSql);
        }
    }

    /**
     * Выполняет фактический запрос к базе данных.
     * @param string $method Метод PDO инструкции.
     * @param int $fetchMode Режим выборки.
     * @return mixed Результат выполнения метода.
     * @throws Exception Если запрос имеет проблемы.
     * @since 1.0.0
     */
    protected function queryInternal($method, $fetchMode = null)
    {
        $rawSql = $this->getRawSql();
        $this->prepare(true);

        try {
            $this->pdoStatement->execute();

            if ($method === '') {
                $result = new DataReader($this);
            } else {
                if ($fetchMode === null) {
                    $fetchMode = $this->fetchMode;
                }
                $result = call_user_func_array([$this->pdoStatement, $method], (array) $fetchMode);
                $this->pdoStatement->closeCursor();
            }
        } catch (\Exception $e) {
            throw $this->db->getSchema()->convertException($e, $rawSql);
        }

        return $result;
    }

    /**
     * Помечает схему таблицы, которую необходимо обновить после запроса.
     * @param string $name Наименование таблицы.
     * @return $this Текущая команда.
     * @since 1.0.0
     */
    protected function requireTableSchemaRefresh($name)
    {
        $this->_refreshTableName = $name;
        return $this;
    }

    /**
     * Обновляет схему таблицы.
     * @since 1.0.0
     */
    protected function refreshTableSchema()
    {
        if ($this->_refreshTableName !== null) {
            $this->db->getSchema()->refreshTableSchema($this->_refreshTableName);
        }
    }
}
