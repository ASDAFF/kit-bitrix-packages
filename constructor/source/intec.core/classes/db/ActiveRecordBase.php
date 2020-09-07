<?php
namespace intec\core\db;

use intec\core\base\InvalidConfigException;
use intec\core\base\Event;
use intec\core\base\Model;
use intec\core\base\InvalidParamException;
use intec\core\base\ModelEvent;
use intec\core\base\NotSupportedException;
use intec\core\base\UnknownMethodException;
use intec\core\base\InvalidCallException;
use intec\core\helpers\ArrayHelper;

/**
 * ActiveRecord это базовый класс записи.
 * Class ActiveRecord
 * @property array $dirtyAttributes Измененные аттрибуты. Только для чтения.
 * @property bool $isNewRecord Новая запись.
 * @property array $oldAttributes Старые значения аттрибутов. Только для чтения.
 * @property mixed $primaryKey Значение первичного ключа. Только для чтения.
 * @property array $relatedRecords Массив реляций. Только для чтения.
 * @package intec\core\db
 * @since 1.0.0
 */
abstract class ActiveRecordBase extends Model implements ActiveRecordInterface
{
    /**
     * Событие инициализации записи с помощью [[init()]].
     * @event Event
     * @since 1.0.0
     */
    const EVENT_INIT = 'init';
    /**
     * Событие после того, как запись была найдена.
     * @event Event
     * @since 1.0.0
     */
    const EVENT_AFTER_FIND = 'afterFind';
    /**
     * Событие перед внесением записи в таблицу.
     * @event ModelEvent
     * Вы можете установить [[ModelEvent::isValid]] в `false` для остановки внесения.
     * @since 1.0.0
     */
    const EVENT_BEFORE_INSERT = 'beforeInsert';
    /**
     * Событие после внесения записи в таблицу.
     * @event AfterSaveEvent
     * @since 1.0.0
     */
    const EVENT_AFTER_INSERT = 'afterInsert';
    /**
     * Событие перед обновлением записи в таблице.
     * @event ModelEvent
     * Вы можете установить [[ModelEvent::isValid]] в `false` для остановки обновления.
     * @since 1.0.0
     */
    const EVENT_BEFORE_UPDATE = 'beforeUpdate';
    /**
     * Событие после обновления записи в таблице.
     * @event AfterSaveEvent
     * @since 1.0.0
     */
    const EVENT_AFTER_UPDATE = 'afterUpdate';
    /**
     * Событие перед удалением записи из таблицы.
     * @event ModelEvent an event that is triggered before deleting a record.
     * Вы можете установить [[ModelEvent::isValid]] в `false` для остановки удаления.
     * @since 1.0.0
     */
    const EVENT_BEFORE_DELETE = 'beforeDelete';
    /**
     * Событие после удаления записи из таблицы.
     * @event Event
     * @since 1.0.0
     */
    const EVENT_AFTER_DELETE = 'afterDelete';
    /**
     * Событие после того как запись была сброшена.
     * @event Event an event that is triggered after a record is refreshed.
     * @since 1.0.0
     */
    const EVENT_AFTER_REFRESH = 'afterRefresh';

    /**
     * Значения аттрибутов, проиндексированные по их имени.
     * @var array
     * @since 1.0.0
     */
    private $_attributes = [];
    /**
     * Старые значения аттрибутов, проиндексированные по их имени.
     * @var array|null
     * Будет `null` если запись [[isNewRecord|is new]].
     * @since 1.0.0
     */
    private $_oldAttributes;
    /**
     * Списки моделей, запрошенные реляциями,
     * проиндексированные по названиям реляций.
     * @var array
     * @since 1.0.0
     */
    private $_related = [];


    /**
     * @inheritdoc
     * @return static ActiveRecord Экземпляр [[ActiveRecord]], найденный по условию,
     * или `null` если ничего найдено небыло.
     * @since 1.0.0
     */
    public static function findOne($condition)
    {
        return static::findByCondition($condition)->one();
    }

    /**
     * @inheritdoc
     * @return static[] Массив экземпляров [[ActiveRecord]] или пустой массив,
     * если ничего не найдено.
     * @since 1.0.0
     */
    public static function findAll($condition)
    {
        return static::findByCondition($condition)->all();
    }

    /**
     * Ищет экземпляры [[ActiveRecord]] по заданному условию.
     * Данный методы вызывается внутри [[findOne()]] и [[findAll()]].
     * @param mixed $condition Условия.
     * @return ActiveQueryInterface Новый экземпляр [[ActiveQueryInterface|ActiveQuery]].
     * @throws InvalidConfigException Если первичных ключей не обнаружено.
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
                $condition = [$primaryKey[0] => $condition];
            } else {
                throw new InvalidConfigException('"' . get_called_class() . '" must have a primary key.');
            }
        }

        return $query->andWhere($condition);
    }

    /**
     * Обновляет аттрибуты записей по условию.
     * @param array $attributes Значения аттрибутов.
     * @param string|array $condition Условия.
     * @return int Количество обновленных строк.
     * @throws NotSupportedException Если не поддерживается.
     * @since 1.0.0
     */
    public static function updateAll($attributes, $condition = '')
    {
        throw new NotSupportedException(__METHOD__ . ' is not supported.');
    }

    /**
     * Обновляет всю таблицу, используя изменения счетчика и условия.
     * @param array $counters Счетчики для обновления.
     * @param string|array $condition Условие.
     * @return int Количество обновленных строк.
     * @throws NotSupportedException Если не поддерживается.
     * @since 1.0.0
     */
    public static function updateAllCounters($counters, $condition = '')
    {
        throw new NotSupportedException(__METHOD__ . ' is not supported.');
    }

    /**
     * Удаляет все записи по условию.
     * @param string|array $condition Условия.
     * @param array $params Параметры, добавляемые к запросу.
     * @return int Количество удаленных строк.
     * @throws NotSupportedException Если не поддерживается.
     * @since 1.0.0
     */
    public static function deleteAll($condition = '', $params = [])
    {
        throw new NotSupportedException(__METHOD__ . ' is not supported.');
    }

    /**
     * Возвращает имя столбца, в котором хранится версия блокировки для реализации оптимистической блокировки.
     * @return string Имя столбца.
     * @since 1.0.0
     */
    public function optimisticLock()
    {
        return null;
    }

    /**
     * @inheritdoc
     * @since 1.0.0
     */
    public function canGetProperty($name, $checkVars = true, $checkBehaviors = true)
    {
        if (parent::canGetProperty($name, $checkVars, $checkBehaviors)) {
            return true;
        }

        try {
            return $this->hasAttribute($name);
        } catch (\Exception $e) {
            // `hasAttribute()` may fail on base/abstract classes in case automatic attribute list fetching used
            return false;
        }
    }

    /**
     * @inheritdoc
     * @since 1.0.0
     */
    public function canSetProperty($name, $checkVars = true, $checkBehaviors = true)
    {
        if (parent::canSetProperty($name, $checkVars, $checkBehaviors)) {
            return true;
        }

        try {
            return $this->hasAttribute($name);
        } catch (\Exception $e) {
            // `hasAttribute()` may fail on base/abstract classes in case automatic attribute list fetching used
            return false;
        }
    }

    /**
     * @param string $name Наименование свойства.
     * @throws \intec\core\base\InvalidParamException Если имя реляции неверно.
     * @return mixed property value
     * @see getAttribute()
     * @since 1.0.0
     */
    public function __get($name)
    {
        if (isset($this->_attributes[$name]) || array_key_exists($name, $this->_attributes)) {
            return $this->_attributes[$name];
        } elseif ($this->hasAttribute($name)) {
            return null;
        } else {
            if (isset($this->_related[$name]) || array_key_exists($name, $this->_related)) {
                return $this->_related[$name];
            }
            $value = parent::__get($name);
            if ($value instanceof ActiveQueryInterface) {
                return $this->_related[$name] = $value->findFor($name, $this);
            } else {
                return $value;
            }
        }
    }

    /**
     * @param string $name Наименование свойства.
     * @param mixed $value Значение свойства.
     * @since 1.0.0
     */
    public function __set($name, $value)
    {
        if ($this->hasAttribute($name)) {
            $this->_attributes[$name] = $value;
        } else {
            parent::__set($name, $value);
        }
    }

    /**
     * @param string $name Наименование свойства или события.
     * @return bool Значение свойство пустое.
     * @since 1.0.0
     */
    public function __isset($name)
    {
        try {
            return $this->__get($name) !== null;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * @param string $name Наименование свойства.
     * @since 1.0.0
     */
    public function __unset($name)
    {
        if ($this->hasAttribute($name)) {
            unset($this->_attributes[$name]);
        } elseif (array_key_exists($name, $this->_related)) {
            unset($this->_related[$name]);
        } elseif ($this->getRelation($name, false) === null) {
            parent::__unset($name);
        }
    }

    /**
     * Объявляет реляцию `has-one`.
     * @param string $class Класс модели реляции.
     * @param array $link Связь между записью реляции и этой записи.
     * @return ActiveQueryInterface Запрос реляции.
     * @since 1.0.0
     */
    public function hasOne($class, $link)
    {
        /* @var $class ActiveRecordInterface */
        /* @var $query ActiveQuery */
        $query = $class::find();
        $query->primaryModel = $this;
        $query->link = $link;
        $query->multiple = false;
        return $query;
    }

    /**
     * Объявляет реляцию `has-many`.
     * @param string $class Класс модели реляции.
     * @param array $link Связь между записью реляции и этой записи.
     * @return ActiveQueryInterface Запрос реляции.
     * @since 1.0.0
     */
    public function hasMany($class, $link)
    {
        /* @var $class ActiveRecordInterface */
        /* @var $query ActiveQuery */
        $query = $class::find();
        $query->primaryModel = $this;
        $query->link = $link;
        $query->multiple = true;
        return $query;
    }

    /**
     * Привязывает модели реляции.
     * @param string $name Имя реляции.
     * @param ActiveRecordInterface|array|null $records Записи.
     * @see getRelation()
     * @since 1.0.0
     */
    public function populateRelation($name, $records)
    {
        $this->_related[$name] = $records;
    }

    /**
     * Проверяет привязку реляции с указанным именем.
     * @param string $name Имя реляции.
     * @return bool Реляция с указанным именем привязана.
     * @see getRelation()
     * @since 1.0.0
     */
    public function isRelationPopulated($name)
    {
        return array_key_exists($name, $this->_related);
    }

    /**
     * Возвращает все привязанные реляции.
     * @return array Массив реляций, проиндексированный по их именам.
     * @see getRelation()
     * @since 1.0.0
     */
    public function getRelatedRecords()
    {
        return $this->_related;
    }

    /**
     * Возвращает конкретную реляцию, если она была привязана.
     * @param string $name Название реляции.
     * @return ActiveRecord|ActiveRecord[]|null Запись, записи или null,
     * если реляция не привязана.
     */
    public function getRelatedRecord($name)
    {
        if ($this->isRelationPopulated($name))
            return $this->_related[$name];

        return null;
    }

    /**
     * Имеет ли модель аттрибут с указанным именем.
     * @param string $name Наименование аттрибута.
     * @return bool Запись имеет аттрибут с указанным именем.
     * @since 1.0.0
     */
    public function hasAttribute($name)
    {
        return isset($this->_attributes[$name]) || in_array($name, $this->attributes(), true);
    }

    /**
     * Возвращает значение аттрибута.
     * Если аттрибут не существует, то `null`.
     * @param string $name Имя аттрибута.
     * @return mixed Значение аттрибута.
     * @see hasAttribute()
     * @since 1.0.0
     */
    public function getAttribute($name)
    {
        return isset($this->_attributes[$name]) ? $this->_attributes[$name] : null;
    }

    /**
     * Устанавливает аттрибут.
     * @param string $name Имя аттрибута.
     * @param mixed $value Значение аттрибута..
     * @throws InvalidParamException Если аттрибут не существует.
     * @see hasAttribute()
     * @since 1.0.0
     */
    public function setAttribute($name, $value)
    {
        if ($this->hasAttribute($name)) {
            $this->_attributes[$name] = $value;
        } else {
            throw new InvalidParamException(get_class($this) . ' has no attribute named "' . $name . '".');
        }
    }

    /**
     * Возвращает старые значения аттрибутов.
     * @return array Старые значения аттрибутов.
     * @since 1.0.0
     */
    public function getOldAttributes()
    {
        return $this->_oldAttributes === null ? [] : $this->_oldAttributes;
    }

    /**
     * Устанавливает старые значения аттрибутов.
     * Все старые значения будут заменены.
     * @param array|null $values Старые значения аттрибутов.
     * Если установлено в `null`, то данная запись будет [[isNewRecord|new]].
     * @since 1.0.0
     */
    public function setOldAttributes($values)
    {
        $this->_oldAttributes = $values;
    }

    /**
     * Возвращает старое значение аттрибута.
     * @param string $name Имя аттрибута.
     * @return mixed Старое значение или `null`, если аттрибут не найден.
     * @see hasAttribute()
     * @since 1.0.0
     */
    public function getOldAttribute($name)
    {
        return isset($this->_oldAttributes[$name]) ? $this->_oldAttributes[$name] : null;
    }

    /**
     * Устанавливает старое значение аттрибута.
     * @param string $name Имя аттрибута.
     * @param mixed $value Значение аттрибута.
     * @throws InvalidParamException Если аттрибута не существует.
     * @see hasAttribute()
     * @since 1.0.0
     */
    public function setOldAttribute($name, $value)
    {
        if (isset($this->_oldAttributes[$name]) || $this->hasAttribute($name)) {
            $this->_oldAttributes[$name] = $value;
        } else {
            throw new InvalidParamException(get_class($this) . ' has no attribute named "' . $name . '".');
        }
    }

    /**
     * Помечает аттрибут пустым.
     * @param string $name Имя аттрибута.
     * @since 1.0.0
     */
    public function markAttributeDirty($name)
    {
        unset($this->_oldAttributes[$name]);
    }

    /**
     * Проверяет, был ли изменен аттрибут.
     * @param string $name Имя аттрибута.
     * @param bool $identical Использовать строгое сравнение.
     * @return bool Аттрибут был изменен.
     * @since 1.0.0
     */
    public function isAttributeChanged($name, $identical = true)
    {
        if (isset($this->_attributes[$name], $this->_oldAttributes[$name])) {
            if ($identical) {
                return $this->_attributes[$name] !== $this->_oldAttributes[$name];
            } else {
                return $this->_attributes[$name] != $this->_oldAttributes[$name];
            }
        } else {
            return isset($this->_attributes[$name]) || isset($this->_oldAttributes[$name]);
        }
    }

    /**
     * Возвращает список пустых аттрибутов.
     * @param string[]|null $names Список аттрибутов. Если `null`, то берутся все.
     * @return array Измененные значения.
     * @since 1.0.0
     */
    public function getDirtyAttributes($names = null)
    {
        if ($names === null) {
            $names = $this->attributes();
        }
        $names = array_flip($names);
        $attributes = [];
        if ($this->_oldAttributes === null) {
            foreach ($this->_attributes as $name => $value) {
                if (isset($names[$name])) {
                    $attributes[$name] = $value;
                }
            }
        } else {
            foreach ($this->_attributes as $name => $value) {
                if (isset($names[$name]) && (!array_key_exists($name, $this->_oldAttributes) || $value !== $this->_oldAttributes[$name])) {
                    $attributes[$name] = $value;
                }
            }
        }
        return $attributes;
    }

    /**
     * Сохраняет запись.
     * @param bool $runValidation Запускать валидацию перед сохранением.
     * @param array $attributeNames Список аттрибутов, которые должны быть сохранены.
     * @return bool Запись успешно сохранена.
     * @since 1.0.0
     */
    public function save($runValidation = true, $attributeNames = null)
    {
        if ($this->getIsNewRecord()) {
            return $this->insert($runValidation, $attributeNames);
        } else {
            return $this->update($runValidation, $attributeNames) !== false;
        }
    }

    /**
     * Сохраняет изменения уже существующей записи.
     * @param bool $runValidation Запуск валидации.
     * @param array $attributeNames Список аттрибутов, которые должны быть сохранены.
     * @return int|false Количество обновленных строк или `false`, если валидация провалена
     * или была остановка с помощью метода [[beforeSave()]].
     * @throws StaleObjectException Если [[optimisticLock|optimistic locking]] включена и некорректна.
     * @throws Exception Обновление прервано.
     * @since 1.0.0
     */
    public function update($runValidation = true, $attributeNames = null)
    {
        if ($runValidation && !$this->validate($attributeNames)) {
            return false;
        }
        return $this->updateInternal($attributeNames);
    }

    /**
     * Обновляет аттрибуты.
     * @param array $attributes Аттрибуты для сохранения.
     * @return int Количество обновленных строк.
     * @since 1.0.0
     */
    public function updateAttributes($attributes)
    {
        $attrs = [];
        foreach ($attributes as $name => $value) {
            if (is_int($name)) {
                $attrs[] = $value;
            } else {
                $this->$name = $value;
                $attrs[] = $name;
            }
        }

        $values = $this->getDirtyAttributes($attrs);
        if (empty($values) || $this->getIsNewRecord()) {
            return 0;
        }

        $rows = static::updateAll($values, $this->getOldPrimaryKey(true));

        foreach ($values as $name => $value) {
            $this->_oldAttributes[$name] = $this->_attributes[$name];
        }

        return $rows;
    }

    /**
     * @see update()
     * @param array $attributes Список аттрибутов, которые должны быть сохранены.
     * @return int|false Количество обновленных строк или `false`, если валидация провалена
     * или была остановка с помощью метода [[beforeSave()]].
     * @throws StaleObjectException
     * @since 1.0.0
     */
    protected function updateInternal($attributes = null)
    {
        if (!$this->beforeSave(false)) {
            return false;
        }
        $values = $this->getDirtyAttributes($attributes);
        if (empty($values)) {
            $this->afterSave(false, $values);
            return 0;
        }
        $condition = $this->getOldPrimaryKey(true);
        $lock = $this->optimisticLock();
        if ($lock !== null) {
            $values[$lock] = $this->$lock + 1;
            $condition[$lock] = $this->$lock;
        }
        // We do not check the return value of updateAll() because it's possible
        // that the UPDATE statement doesn't change anything and thus returns 0.
        $rows = static::updateAll($values, $condition);

        if ($lock !== null && !$rows) {
            throw new StaleObjectException('The object being updated is outdated.');
        }

        if (isset($values[$lock])) {
            $this->$lock = $values[$lock];
        }

        $changedAttributes = [];
        foreach ($values as $name => $value) {
            $changedAttributes[$name] = isset($this->_oldAttributes[$name]) ? $this->_oldAttributes[$name] : null;
            $this->_oldAttributes[$name] = $value;
        }
        $this->afterSave(false, $changedAttributes);

        return $rows;
    }

    /**
     * Обновляет счетчики.
     * @param array $counters Счетчики.
     * @return bool Сохранение успешно.
     * @see updateAllCounters()
     * @since 1.0.0
     */
    public function updateCounters($counters)
    {
        if (static::updateAllCounters($counters, $this->getOldPrimaryKey(true)) > 0) {
            foreach ($counters as $name => $value) {
                if (!isset($this->_attributes[$name])) {
                    $this->_attributes[$name] = $value;
                } else {
                    $this->_attributes[$name] += $value;
                }
                $this->_oldAttributes[$name] = $this->_attributes[$name];
            }
            return true;
        } else {
            return false;
        }
    }

    /**
     * Удаляет запись из таблицы.
     * @return int|false Количество удаленных строк или `false`, если удаление прервано
     * с помощью метода [[beforeDelete()]].
     * @throws StaleObjectException Если [[optimisticLock|optimistic locking]] включена и некорректна.
     * @throws Exception Ошибка удаления.
     * @since 1.0.0
     */
    public function delete()
    {
        $result = false;
        if ($this->beforeDelete()) {
            // we do not check the return value of deleteAll() because it's possible
            // the record is already deleted in the database and thus the method will return 0
            $condition = $this->getOldPrimaryKey(true);
            $lock = $this->optimisticLock();
            if ($lock !== null) {
                $condition[$lock] = $this->$lock;
            }
            $result = static::deleteAll($condition);
            if ($lock !== null && !$result) {
                throw new StaleObjectException('The object being deleted is outdated.');
            }
            $this->_oldAttributes = null;
            $this->afterDelete();
        }

        return $result;
    }

    /**
     * Текущая запись является новой.
     * @return bool Запись новая.
     * @since 1.0.0
     */
    public function getIsNewRecord()
    {
        return $this->_oldAttributes === null;
    }

    /**
     * Устанавливает, что текущая запись - новая.
     * @param bool $value Запись новая.
     * @see getIsNewRecord()
     * @since 1.0.0
     */
    public function setIsNewRecord($value)
    {
        $this->_oldAttributes = $value ? null : $this->_attributes;
    }

    /**
     * Инициализирует объект.
     * @since 1.0.0
     */
    public function init()
    {
        parent::init();
        $this->trigger(self::EVENT_INIT);
    }

    /**
     * Данный метод вызывается после того, как запись была найдена.
     * @since 1.0.0
     */
    public function afterFind()
    {
        $this->trigger(self::EVENT_AFTER_FIND);
    }

    /**
     * Метод вызывается перед сохранением.
     * @param bool $insert Производится добавление новой записи в таблицу.
     * @return bool Продолжить внесение или обновление.
     * @since 1.0.0
     */
    public function beforeSave($insert)
    {
        $event = new ModelEvent;
        $this->trigger($insert ? self::EVENT_BEFORE_INSERT : self::EVENT_BEFORE_UPDATE, $event);

        return $event->isValid;
    }

    /**
     * Данный метод вызывается после сохранения.
     * @param bool $insert Производилось добавление новой записи в таблицу.
     * @param array $changedAttributes Старые значения измененных аттрибутов.
     * @since 1.0.0
     */
    public function afterSave($insert, $changedAttributes)
    {
        $this->trigger($insert ? self::EVENT_AFTER_INSERT : self::EVENT_AFTER_UPDATE, new AfterSaveEvent([
            'changedAttributes' => $changedAttributes,
        ]));
    }

    /**
     * Данный метод вызывается перед сохранением.
     * @return bool Запись будет удалена.
     * @since 1.0.0
     */
    public function beforeDelete()
    {
        $event = new ModelEvent;
        $this->trigger(self::EVENT_BEFORE_DELETE, $event);

        return $event->isValid;
    }

    /**
     * Данный метод вызывается после удаления.
     * @since 1.0.0
     */
    public function afterDelete()
    {
        $this->trigger(self::EVENT_AFTER_DELETE);
    }

    /**
     * Обновляет данную запись в соответствии с таблицей.
     * @return bool Запись существует в базе.
     * @since 1.0.0
     */
    public function refresh()
    {
        /* @var $record ActiveRecordBase */
        $record = static::findOne($this->getPrimaryKey(true));
        if ($record === null) {
            return false;
        }
        foreach ($this->attributes() as $name) {
            $this->_attributes[$name] = isset($record->_attributes[$name]) ? $record->_attributes[$name] : null;
        }
        $this->_oldAttributes = $record->_oldAttributes;
        $this->_related = [];
        $this->afterRefresh();

        return true;
    }

    /**
     * Данный метод вызывается после сброса записи.
     * @since 1.0.0
     */
    public function afterRefresh()
    {
        $this->trigger(self::EVENT_AFTER_REFRESH);
    }

    /**
     * Проверяет на идентичность 2 записи.
     * @param ActiveRecordInterface $record Запись для сравнения.
     * @return bool Записи идентичны.
     * @since 1.0.0
     */
    public function equals($record)
    {
        if ($this->getIsNewRecord() || $record->getIsNewRecord()) {
            return false;
        }

        return get_class($this) === get_class($record) && $this->getPrimaryKey() === $record->getPrimaryKey();
    }

    /**
     * Возвращает значения первичного ключа.
     * @param bool $asArray Возвращать первичный ключ как массив.
     * @return mixed Значения первичного ключа.
     * @since 1.0.0
     */
    public function getPrimaryKey($asArray = false)
    {
        $keys = $this->primaryKey();
        if (!$asArray && count($keys) === 1) {
            return isset($this->_attributes[$keys[0]]) ? $this->_attributes[$keys[0]] : null;
        } else {
            $values = [];
            foreach ($keys as $name) {
                $values[$name] = isset($this->_attributes[$name]) ? $this->_attributes[$name] : null;
            }

            return $values;
        }
    }

    /**
     * RВозвращает значения старого первичного ключа.
     * @param bool $asArray Возвращать первичный ключ как массив.
     * @return mixed Значения первичного ключа.
     * @throws Exception Если первичного ключа не существует.
     * @since 1.0.0
     */
    public function getOldPrimaryKey($asArray = false)
    {
        $keys = $this->primaryKey();
        if (empty($keys)) {
            throw new Exception(get_class($this) . ' does not have a primary key. You should either define a primary key for the corresponding table or override the primaryKey() method.');
        }
        if (!$asArray && count($keys) === 1) {
            return isset($this->_oldAttributes[$keys[0]]) ? $this->_oldAttributes[$keys[0]] : null;
        } else {
            $values = [];
            foreach ($keys as $name) {
                $values[$name] = isset($this->_oldAttributes[$name]) ? $this->_oldAttributes[$name] : null;
            }

            return $values;
        }
    }

    /**
     * Привязывает строку к записи.
     * @param ActiveRecordBase $record Запись, к которой необходимо привязать строку.
     * @param array $row Строка со значениями.
     * @since 1.0.0
     */
    public static function populateRecord($record, $row)
    {
        $columns = array_flip($record->attributes());
        foreach ($row as $name => $value) {
            if (isset($columns[$name])) {
                $record->_attributes[$name] = $value;
            } elseif ($record->canSetProperty($name)) {
                $record->$name = $value;
            }
        }
        $record->_oldAttributes = $record->_attributes;
    }

    /**
     * Создает экземпляр записи.
     * @param array $row Данные для внесения в запись.
     * @return static Новая запись.
     * @since 1.0.0
     */
    public static function instantiate($row)
    {
        return new static;
    }

    public function offsetExists($offset)
    {
        return $this->__isset($offset);
    }

    /**
     * Возвращает запрос реляции.
     * @param string $name Имя реляции.
     * @param bool $throwException Вызывать исключение если реляции не существует.
     * @return ActiveQueryInterface|ActiveQuery Запрос реляции.
     * @throws InvalidParamException Если реляция не существует.
     * @since 1.0.0
     */
    public function getRelation($name, $throwException = true)
    {
        $getter = 'get' . $name;
        try {
            // the relation could be defined in a behavior
            $relation = $this->$getter();
        } catch (UnknownMethodException $e) {
            if ($throwException) {
                throw new InvalidParamException(get_class($this) . ' has no relation named "' . $name . '".', 0, $e);
            } else {
                return null;
            }
        }
        if (!$relation instanceof ActiveQueryInterface) {
            if ($throwException) {
                throw new InvalidParamException(get_class($this) . ' has no relation named "' . $name . '".');
            } else {
                return null;
            }
        }

        if (method_exists($this, $getter)) {
            // relation name is case sensitive, trying to validate it when the relation is defined within this class
            $method = new \ReflectionMethod($this, $getter);
            $realName = lcfirst(substr($method->getName(), 3));
            if ($realName !== $name) {
                if ($throwException) {
                    throw new InvalidParamException('Relation names are case sensitive. ' . get_class($this) . " has a relation named \"$realName\" instead of \"$name\".");
                } else {
                    return null;
                }
            }
        }

        return $relation;
    }

    /**
     * Устанавливает связь между моделями.
     * @param string $name Имя реляции..
     * @param ActiveRecordInterface $model Модель, которая будет привязана.
     * @param array $extraColumns Дополнительные значения для сохранения в промежуточную таблицу.
     * @throws InvalidCallException Если метод не может привязать модели.
     * @since 1.0.0
     */
    public function link($name, $model, $extraColumns = [])
    {
        $relation = $this->getRelation($name);

        if ($relation->via !== null) {
            if ($this->getIsNewRecord() || $model->getIsNewRecord()) {
                throw new InvalidCallException('Unable to link models: the models being linked cannot be newly created.');
            }
            if (is_array($relation->via)) {
                /* @var $viaRelation ActiveQuery */
                list($viaName, $viaRelation) = $relation->via;
                $viaClass = $viaRelation->modelClass;
                // unset $viaName so that it can be reloaded to reflect the change
                unset($this->_related[$viaName]);
            } else {
                $viaRelation = $relation->via;
                $viaTable = reset($relation->via->from);
            }
            $columns = [];
            foreach ($viaRelation->link as $a => $b) {
                $columns[$a] = $this->$b;
            }
            foreach ($relation->link as $a => $b) {
                $columns[$b] = $model->$a;
            }
            foreach ($extraColumns as $k => $v) {
                $columns[$k] = $v;
            }
            if (is_array($relation->via)) {
                /* @var $viaClass ActiveRecordInterface */
                /* @var $record ActiveRecordInterface */
                $record = new $viaClass();
                foreach ($columns as $column => $value) {
                    $record->$column = $value;
                }
                $record->insert(false);
            } else {
                /* @var $viaTable string */
                static::getDb()->createCommand()
                    ->insert($viaTable, $columns)->execute();
            }
        } else {
            $p1 = $model->isPrimaryKey(array_keys($relation->link));
            $p2 = static::isPrimaryKey(array_values($relation->link));
            if ($p1 && $p2) {
                if ($this->getIsNewRecord() && $model->getIsNewRecord()) {
                    throw new InvalidCallException('Unable to link models: at most one model can be newly created.');
                } elseif ($this->getIsNewRecord()) {
                    $this->bindModels(array_flip($relation->link), $this, $model);
                } else {
                    $this->bindModels($relation->link, $model, $this);
                }
            } elseif ($p1) {
                $this->bindModels(array_flip($relation->link), $this, $model);
            } elseif ($p2) {
                $this->bindModels($relation->link, $model, $this);
            } else {
                throw new InvalidCallException('Unable to link models: the link defining the relation does not involve any primary key.');
            }
        }

        // update lazily loaded related objects
        if (!$relation->multiple) {
            $this->_related[$name] = $model;
        } elseif (isset($this->_related[$name])) {
            if ($relation->indexBy !== null) {
                if ($relation->indexBy instanceof \Closure) {
                    $index = call_user_func($relation->indexBy, $model);
                } else {
                    $index = $model->{$relation->indexBy};
                }
                $this->_related[$name][$index] = $model;
            } else {
                $this->_related[$name][] = $model;
            }
        }
    }

    /**
     * Удаляет связь между двумя моделями.
     * @param string $name Имя реляции.
     * @param ActiveRecordInterface $model Модель, которую необходимо отвязать.
     * @param bool $delete Удалять модель, содержащую внешний ключ.
     * Если `false`, то внешний ключ модели будет установлен как `null` и сохранен.
     * Если `true`, то модель будет удалена по внешнему ключу.
     * @throws InvalidCallException Если модель не может быть отвязана.
     * @since 1.0.0
     */
    public function unlink($name, $model, $delete = false)
    {
        $relation = $this->getRelation($name);

        if ($relation->via !== null) {
            if (is_array($relation->via)) {
                /* @var $viaRelation ActiveQuery */
                list($viaName, $viaRelation) = $relation->via;
                $viaClass = $viaRelation->modelClass;
                unset($this->_related[$viaName]);
            } else {
                $viaRelation = $relation->via;
                $viaTable = reset($relation->via->from);
            }
            $columns = [];
            foreach ($viaRelation->link as $a => $b) {
                $columns[$a] = $this->$b;
            }
            foreach ($relation->link as $a => $b) {
                $columns[$b] = $model->$a;
            }
            $nulls = [];
            foreach (array_keys($columns) as $a) {
                $nulls[$a] = null;
            }
            if (is_array($relation->via)) {
                /* @var $viaClass ActiveRecordInterface */
                if ($delete) {
                    $viaClass::deleteAll($columns);
                } else {
                    $viaClass::updateAll($nulls, $columns);
                }
            } else {
                /* @var $viaTable string */
                /* @var $command Command */
                $command = static::getDb()->createCommand();
                if ($delete) {
                    $command->delete($viaTable, $columns)->execute();
                } else {
                    $command->update($viaTable, $nulls, $columns)->execute();
                }
            }
        } else {
            $p1 = $model->isPrimaryKey(array_keys($relation->link));
            $p2 = static::isPrimaryKey(array_values($relation->link));
            if ($p2) {
                if ($delete) {
                    $model->delete();
                } else {
                    foreach ($relation->link as $a => $b) {
                        $model->$a = null;
                    }
                    $model->save(false);
                }
            } elseif ($p1) {
                foreach ($relation->link as $a => $b) {
                    if (is_array($this->$b)) { // relation via array valued attribute
                        if (($key = array_search($model->$a, $this->$b, false)) !== false) {
                            $values = $this->$b;
                            unset($values[$key]);
                            $this->$b = array_values($values);
                        }
                    } else {
                        $this->$b = null;
                    }
                }
                $delete ? $this->delete() : $this->save(false);
            } else {
                throw new InvalidCallException('Unable to unlink models: the link does not involve any primary key.');
            }
        }

        if (!$relation->multiple) {
            unset($this->_related[$name]);
        } elseif (isset($this->_related[$name])) {
            /* @var $b ActiveRecordInterface */
            foreach ($this->_related[$name] as $a => $b) {
                if ($model->getPrimaryKey() === $b->getPrimaryKey()) {
                    unset($this->_related[$name][$a]);
                }
            }
        }
    }

    /**
     * Удаляет все модели реляции для данной модели.
     * @param string $name Имя реляции.
     * @param bool $delete Удалять модель, содержащую внешний ключ.
     * @since 1.0.0
     */
    public function unlinkAll($name, $delete = false)
    {
        $relation = $this->getRelation($name);

        if ($relation->via !== null) {
            if (is_array($relation->via)) {
                /* @var $viaRelation ActiveQuery */
                list($viaName, $viaRelation) = $relation->via;
                $viaClass = $viaRelation->modelClass;
                unset($this->_related[$viaName]);
            } else {
                $viaRelation = $relation->via;
                $viaTable = reset($relation->via->from);
            }
            $condition = [];
            $nulls = [];
            foreach ($viaRelation->link as $a => $b) {
                $nulls[$a] = null;
                $condition[$a] = $this->$b;
            }
            if (!empty($viaRelation->where)) {
                $condition = ['and', $condition, $viaRelation->where];
            }
            if (!empty($viaRelation->on)) {
                $condition = ['and', $condition, $viaRelation->on];
            }
            if (is_array($relation->via)) {
                /* @var $viaClass ActiveRecordInterface */
                if ($delete) {
                    $viaClass::deleteAll($condition);
                } else {
                    $viaClass::updateAll($nulls, $condition);
                }
            } else {
                /* @var $viaTable string */
                /* @var $command Command */
                $command = static::getDb()->createCommand();
                if ($delete) {
                    $command->delete($viaTable, $condition)->execute();
                } else {
                    $command->update($viaTable, $nulls, $condition)->execute();
                }
            }
        } else {
            /* @var $relatedModel ActiveRecordInterface */
            $relatedModel = $relation->modelClass;
            if (!$delete && count($relation->link) === 1 && is_array($this->{$b = reset($relation->link)})) {
                // relation via array valued attribute
                $this->$b = [];
                $this->save(false);
            } else {
                $nulls = [];
                $condition = [];
                foreach ($relation->link as $a => $b) {
                    $nulls[$a] = null;
                    $condition[$a] = $this->$b;
                }
                if (!empty($relation->where)) {
                    $condition = ['and', $condition, $relation->where];
                }
                if (!empty($relation->on)) {
                    $condition = ['and', $condition, $relation->on];
                }
                if ($delete) {
                    $relatedModel::deleteAll($condition);
                } else {
                    $relatedModel::updateAll($nulls, $condition);
                }
            }
        }

        unset($this->_related[$name]);
    }

    /**
     * @param array $link
     * @param ActiveRecordInterface $foreignModel
     * @param ActiveRecordInterface $primaryModel
     * @throws InvalidCallException
     * @since 1.0.0
     */
    private function bindModels($link, $foreignModel, $primaryModel)
    {
        foreach ($link as $fk => $pk) {
            $value = $primaryModel->$pk;
            if ($value === null) {
                throw new InvalidCallException('Unable to link models: the primary key of ' . get_class($primaryModel) . ' is null.');
            }
            if (is_array($foreignModel->$fk)) { // relation via array valued attribute
                $foreignModel->$fk = array_merge($foreignModel->$fk, [$value]);
            } else {
                $foreignModel->$fk = $value;
            }
        }
        $foreignModel->save(false);
    }

    /**
     * Является ли набор аттрибутов первичным ключом.
     * @param array $keys Набор аттрибутов.
     * @return bool Является ли набор аттрибутов первичным ключом.
     * @since 1.0.0
     */
    public static function isPrimaryKey($keys)
    {
        $pks = static::primaryKey();
        if (count($keys) === count($pks)) {
            return count(array_intersect($keys, $pks)) === count($pks);
        } else {
            return false;
        }
    }

    /**
     * Возвращает метки для аттрибута.
     * @param string $attribute Имя аттрибута.
     * @return string Метка аттрибута.
     * @see generateAttributeLabel()
     * @see attributeLabels()
     * @since 1.0.0
     */
    public function getAttributeLabel($attribute)
    {
        $labels = $this->attributeLabels();
        if (isset($labels[$attribute])) {
            return $labels[$attribute];
        } elseif (strpos($attribute, '.')) {
            $attributeParts = explode('.', $attribute);
            $neededAttribute = array_pop($attributeParts);

            $relatedModel = $this;
            foreach ($attributeParts as $relationName) {
                if ($relatedModel->isRelationPopulated($relationName) && $relatedModel->$relationName instanceof self) {
                    $relatedModel = $relatedModel->$relationName;
                } else {
                    try {
                        $relation = $relatedModel->getRelation($relationName);
                    } catch (InvalidParamException $e) {
                        return $this->generateAttributeLabel($attribute);
                    }
                    $relatedModel = new $relation->modelClass;
                }
            }

            $labels = $relatedModel->attributeLabels();
            if (isset($labels[$neededAttribute])) {
                return $labels[$neededAttribute];
            }
        }

        return $this->generateAttributeLabel($attribute);
    }

    /**
     * Возвращает подсказку для аттрибута.
     * @param string $attribute Имя аттрибута.
     * @return string Подсказка для аттрибута.
     * @see attributeHints()
     * @since 1.0.0
     */
    public function getAttributeHint($attribute)
    {
        $hints = $this->attributeHints();
        if (isset($hints[$attribute])) {
            return $hints[$attribute];
        } elseif (strpos($attribute, '.')) {
            $attributeParts = explode('.', $attribute);
            $neededAttribute = array_pop($attributeParts);

            $relatedModel = $this;
            foreach ($attributeParts as $relationName) {
                if ($relatedModel->isRelationPopulated($relationName) && $relatedModel->$relationName instanceof self) {
                    $relatedModel = $relatedModel->$relationName;
                } else {
                    try {
                        $relation = $relatedModel->getRelation($relationName);
                    } catch (InvalidParamException $e) {
                        return '';
                    }
                    $relatedModel = new $relation->modelClass;
                }
            }

            $hints = $relatedModel->attributeHints();
            if (isset($hints[$neededAttribute])) {
                return $hints[$neededAttribute];
            }
        }
        return '';
    }

    /**
     * @inheritdoc
     * @since 1.0.0
     */
    public function fields()
    {
        $fields = array_keys($this->_attributes);

        return array_combine($fields, $fields);
    }

    /**
     * @inheritdoc
     * @since 1.0.0
     */
    public function extraFields()
    {
        $fields = array_keys($this->getRelatedRecords());

        return array_combine($fields, $fields);
    }

    /**
     * @param mixed $offset Смещение.
     * @since 1.0.0
     */
    public function offsetUnset($offset)
    {
        if (property_exists($this, $offset)) {
            $this->$offset = null;
        } else {
            unset($this->$offset);
        }
    }
}
