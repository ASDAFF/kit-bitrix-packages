<?php
namespace intec\core\db;

use intec\Core;
use intec\core\base\InvalidConfigException;
use intec\core\helpers\ArrayHelper;
use intec\core\helpers\Inflector;
use intec\core\helpers\StringHelper;
use intec\core\helpers\Type;

/**
 * Class ActiveRecord
 * @method ActiveQuery hasMany($class, array $link)
 * @method ActiveQuery hasOne($class, array $link)
 * @package intec\core\db
 * @since 1.0.0
 */
class ActiveRecord extends ActiveRecordBase
{
    const OP_INSERT = 0x01;
    const OP_UPDATE = 0x02;
    const OP_DELETE = 0x04;
    const OP_ALL = 0x07;

    /**
     * @param bool $skipIfSet
     * @return $this
     * @since 1.0.0
     */
    public function loadDefaultValues($skipIfSet = true)
    {
        foreach (static::getTableSchema()->columns as $column) {
            if ($column->defaultValue !== null && (!$skipIfSet || $this->{$column->name} === null)) {
                $this->{$column->name} = $column->defaultValue;
            }
        }
        return $this;
    }

    /**
     * @return Connection
     * @since 1.0.0
     */
    public static function getDb()
    {
        return Core::$app->getDb();
    }

    /**
     * @param string $sql
     * @param array $params
     * @return ActiveQuery
     * @since 1.0.0
     */
    public static function findBySql($sql, $params = [])
    {
        $query = static::find();
        $query->sql = $sql;

        return $query->params($params);
    }

    /**
     * @param mixed $condition
     * @return ActiveQueryInterface
     * @throws InvalidConfigException
     * @internal
     * @since 1.0.0
     */
    protected static function findByCondition($condition)
    {
        $query = static::find();

        if (!ArrayHelper::isAssociative($condition)) {
            // query by primary key
            $primaryKey = static::primaryKey();
            if (isset($primaryKey[0])) {
                $pk = $primaryKey[0];
                if (!empty($query->join) || !empty($query->joinWith)) {
                    $pk = static::tableName() . '.' . $pk;
                }
                $condition = [$pk => $condition];
            } else {
                throw new InvalidConfigException('"' . get_called_class() . '" must have a primary key.');
            }
        }

        return $query->andWhere($condition);
    }

    /**
     * @param array $attributes
     * @param string|array $condition
     * @param array $params
     * @return int
     * @since 1.0.0
     */
    public static function updateAll($attributes, $condition = '', $params = [])
    {
        $command = static::getDb()->createCommand();
        $command->update(static::tableName(), $attributes, $condition, $params);

        return $command->execute();
    }

    /**
     * @param array $counters
     * @param string|array $condition
     * @param array $params
     * @return int
     * @since 1.0.0
     */
    public static function updateAllCounters($counters, $condition = '', $params = [])
    {
        $n = 0;
        foreach ($counters as $name => $value) {
            $counters[$name] = new Expression("[[$name]]+:bp{$n}", [":bp{$n}" => $value]);
            $n++;
        }
        $command = static::getDb()->createCommand();
        $command->update(static::tableName(), $counters, $condition, $params);

        return $command->execute();
    }

    /**
     * @param string|array $condition
     * @param array $params
     * @return int
     * @since 1.0.0
     */
    public static function deleteAll($condition = '', $params = [])
    {
        $command = static::getDb()->createCommand();
        $command->delete(static::tableName(), $condition, $params);

        return $command->execute();
    }

    /**
     * @inheritdoc
     * @return ActiveQuery
     * @since 1.0.0
     */
    public static function find()
    {
        return Core::createObject(ActiveQuery::className(), [get_called_class()]);
    }

    /**
     * @return string
     * @since 1.0.0
     */
    public static function tableName()
    {
        return '{{%' . Inflector::camel2id(StringHelper::basename(get_called_class()), '_') . '}}';
    }

    /**
     * @return TableSchema
     * @throws InvalidConfigException
     * @since 1.0.0
     */
    public static function getTableSchema()
    {
        $tableSchema = static::getDb()
            ->getSchema()
            ->getTableSchema(static::tableName());

        if ($tableSchema === null) {
            throw new InvalidConfigException('The table does not exist: ' . static::tableName());
        }

        return $tableSchema;
    }

    /**
     * @return string[]
     * @since 1.0.0
     */
    public static function primaryKey()
    {
        return static::getTableSchema()->primaryKey;
    }

    /**
     * @return array
     * @since 1.0.0
     */
    public function attributes()
    {
        return array_keys(static::getTableSchema()->columns);
    }

    /**
     * @return array
     * @since 1.0.0
     */
    public function transactions()
    {
        return [];
    }

    /**
     * @inheritdoc
     * @since 1.0.0
     */
    public static function populateRecord($record, $row)
    {
        $columns = static::getTableSchema()->columns;
        foreach ($row as $name => $value) {
            if (isset($columns[$name])) {
                $row[$name] = $columns[$name]->phpTypecast($value);
            }
        }
        parent::populateRecord($record, $row);
    }

    /**
     * @param bool $runValidation
     * @param array $attributes
     * @return bool
     * @throws \Exception
     * @throws \Throwable.
     * @since 1.0.0
     */
    public function insert($runValidation = true, $attributes = null)
    {
        if ($runValidation && !$this->validate($attributes)) {
            return false;
        }

        if (!$this->isTransactional(self::OP_INSERT)) {
            return $this->insertInternal($attributes);
        }

        $transaction = static::getDb()->beginTransaction();
        try {
            $result = $this->insertInternal($attributes);
            if ($result === false) {
                $transaction->rollBack();
            } else {
                $transaction->commit();
            }
            return $result;
        } catch (\Exception $e) {
            $transaction->rollBack();
            throw $e;
        } catch (\Throwable $e) {
            $transaction->rollBack();
            throw $e;
        }
    }

    /**
     * @param array $attributes
     * @return bool
     * @since 1.0.0
     */
    protected function insertInternal($attributes = null)
    {
        if (!$this->beforeSave(true)) {
            return false;
        }
        $values = $this->getDirtyAttributes($attributes);
        if (($primaryKeys = static::getDb()->schema->insert(static::tableName(), $values)) === false) {
            return false;
        }
        foreach ($primaryKeys as $name => $value) {
            $id = static::getTableSchema()->columns[$name]->phpTypecast($value);
            $this->setAttribute($name, $id);
            $values[$name] = $id;
        }

        $changedAttributes = array_fill_keys(array_keys($values), null);
        $this->setOldAttributes($values);
        $this->afterSave(true, $changedAttributes);

        return true;
    }

    /**
     * @param bool $runValidation
     * @param array $attributeNames
     * @return int|false
     * @throws StaleObjectException
     * @throws \Exception
     * @throws \Throwable.
     * @since 1.0.0
     */
    public function update($runValidation = true, $attributeNames = null)
    {
        if ($runValidation && !$this->validate($attributeNames)) {
            return false;
        }

        if (!$this->isTransactional(self::OP_UPDATE)) {
            return $this->updateInternal($attributeNames);
        }

        $transaction = static::getDb()->beginTransaction();
        try {
            $result = $this->updateInternal($attributeNames);
            if ($result === false) {
                $transaction->rollBack();
            } else {
                $transaction->commit();
            }
            return $result;
        } catch (\Exception $e) {
            $transaction->rollBack();
            throw $e;
        } catch (\Throwable $e) {
            $transaction->rollBack();
            throw $e;
        }
    }

    /**
     * @throws \Exception
     * @since 1.0.0
     */
    public function delete()
    {
        if (!$this->isTransactional(self::OP_DELETE)) {
            return $this->deleteInternal();
        }

        $transaction = static::getDb()->beginTransaction();
        try {
            $result = $this->deleteInternal();
            if ($result === false) {
                $transaction->rollBack();
            } else {
                $transaction->commit();
            }
            return $result;
        } catch (\Exception $e) {
            $transaction->rollBack();
            throw $e;
        } catch (\Throwable $e) {
            $transaction->rollBack();
            throw $e;
        }
    }

    /**
     * @return int|false
     * @throws StaleObjectException
     * @since 1.0.0
     */
    protected function deleteInternal()
    {
        if (!$this->beforeDelete()) {
            return false;
        }

        $condition = $this->getOldPrimaryKey(true);
        $lock = $this->optimisticLock();
        if ($lock !== null) {
            $condition[$lock] = $this->$lock;
        }
        $result = static::deleteAll($condition);
        if ($lock !== null && !$result) {
            throw new StaleObjectException('The object being deleted is outdated.');
        }
        $this->setOldAttributes(null);
        $this->afterDelete();

        return $result;
    }

    /**
     * @param ActiveRecord $record
     * @return bool
     * @since 1.0.0
     */
    public function equals($record)
    {
        if ($this->isNewRecord || $record->isNewRecord) {
            return false;
        }

        return static::tableName() === $record->tableName() && $this->getPrimaryKey() === $record->getPrimaryKey();
    }

    /**
     * @param int $operation
     * @return bool
     * @since 1.0.0
     */
    public function isTransactional($operation)
    {
        $scenario = $this->getScenario();
        $transactions = $this->transactions();

        return isset($transactions[$scenario]) && ($transactions[$scenario] & $operation);
    }

    /**
     * Переменная, хранящая кеш.
     * Хранит в том случае, если установлена как массив в дочернем классе.
     * @var null
     */
    protected static $cache = null;

    /**
     * Кеширует запись.
     * @param ActiveRecord $record Запись.
     * @return bool Кеш добавлен.
     */
    protected static function addCache($record)
    {
        if (!Type::isArray(static::$cache))
            return false;

        $key = static::getCacheKey($record);

        if (empty($key))
            return false;

        static::$cache[$key] = $record;
        return true;
    }

    /**
     * Кеширует запись.
     * @param string $key
     * @param ActiveRecord|null $record
     * @return bool Кеш установлен.
     */
    protected static function setCache($key, $record)
    {
        if (!Type::isArray(static::$cache))
            return false;

        if (empty($key))
            return false;

        if ($record instanceof static) {
            static::$cache[$key] = $record;
        } else {
            static::$cache[$key] = null;
        }
        return true;
    }

    /**
     * Возвращает кешированную запись по ключу.
     * Возвращает false, если кеш не установлен или запись в кеше не найдена.
     * Если была попытка поиска записи, но ничего найдено небыло, то вернет null.
     * @param string $key Ключ для поиска записи.
     * @return bool
     */
    protected static function getCache($key)
    {
        if (!Type::isArray(static::$cache))
            return false;

        if (!ArrayHelper::keyExists($key, static::$cache))
            return false;

        return static::$cache[$key];
    }

    /**
     * Очищает ключ одной или всех записей.
     * @param null $key Ключ или `null`, если необходимо очистить весь кеш.
     */
    public static function clearCache($key = null)
    {
        if (!Type::isArray(static::$cache))
            return;

        if ($key !== null)
            $key = Type::toString($key);

        if ($key === null) {
            static::$cache = [];
        } else {
            unset(static::$cache[$key]);
        }
    }

    /**
     * Получает ключ кеширования для записи.
     * @param ActiveRecord $record
     * @return string|null
     */
    protected static function getCacheKey($record)
    {
        $key = $record->getPrimaryKey(false);

        if (!Type::isString($key))
            return null;

        return $key;
    }

    /**
     * @inheritdoc
     */
    public static function findOne($condition)
    {
        if (Type::isArray($condition)) {
            return parent::findOne($condition);
        } else {
            $result = static::getCache($condition);

            if ($result !== false)
                return $result;

            $result = parent::findOne($condition);

            if ($result instanceof static) {
                static::addCache($result);
            } else {
                static::setCache($condition, null);
            }

            return $result;
        }
    }

    /**
     * @inheritdoc
     */
    public function afterDelete()
    {
        parent::afterDelete();
        static::clearCache(static::getCacheKey($this));
    }

    /**
     * @inheritdoc
     */
    public function afterFind()
    {
        parent::afterFind();
        static::addCache($this);
    }

    /**
     * Создает реляцию.
     * @param string $name Название реляции.
     * @param ActiveQuery $query Запрос.
     * @param bool $result Возвращать результат в любом случае.
     * @param bool $collection Возвращать коллекцию.
     * @return ActiveRecord|ActiveRecord[]|ActiveQuery|null
     */
    protected function relation($name, $query, $result = false, $collection = true) {
        if(!$result)
            return $query;

        if ($this->isRelationPopulated($name)) {
            $result = $this->getRelatedRecord($name);

            if ($collection && $query->multiple)
                $result = $query->createCollection($result);

            return $result;
        }

        if ($query->multiple) {
            $result = $query->all(null, $collection);
        } else {
            $result = $query->one(null);
        }

        $this->populateRelation($name, ($result instanceof ActiveRecords) ? $result->asArray() : $result);

        return $result;
    }
}
