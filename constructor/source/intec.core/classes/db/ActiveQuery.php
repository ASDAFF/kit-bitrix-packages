<?php
namespace intec\core\db;

use intec\core\base\InvalidConfigException;

/**
 * ActiveQuery представляет запрос к базе данных, ассоциирующийся с [[ActiveRecord]].
 * ActiveQuery содержит следующие методы для возврата результатов запроса:
 *
 * - [[one()]]: Возвращает одну запись.
 * - [[all()]]: Вовзращает все записи.
 * - [[count()]]: Возвращает количество записей.
 * - [[sum()]]: Возвращает сумму определенного столбца.
 * - [[average()]]: Возвращает среднее значение определенного столбца.
 * - [[min()]]: Возвращает минимальное значение определенного столбца.
 * - [[max()]]: Возвращает максимальное значение определенного столбца.
 * - [[scalar()]]: Возвращает первый столбец первой записи.
 * - [[column()]]: Возвращает первый столбец записей.
 * - [[exists()]]: Возвращает значение, которое отражает наличие данных в результате запроса.
 *
 * Class ActiveQuery
 * @package intec\core\db
 * @since 1.0.0
 */
class ActiveQuery extends Query implements ActiveQueryInterface
{
    use ActiveQueryTrait;
    use ActiveRelationTrait;

    /**
     * Событие, возникающее при инициализации события с помощью [[init()]].
     * @event Event
     * @since 1.0.0
     */
    const EVENT_INIT = 'init';

    /**
     * SQL код, который будет выполнен для получения [[ActiveRecord]].
     * Устанавливается в [[ActiveRecord::findBySql()]].
     * @var string
     * @since 1.0.0
     */
    public $sql;
    /**
     * @var string|array Условия объединения.
     * @see onCondition()
     * @since 1.0.0
     */
    public $on;
    /**
     * Список реляций, к которым должен быть присоединен данный запрос.
     * @var array
     * @since 1.0.0
     */
    public $joinWith;


    /**
     * Constructor.
     * @param string $modelClass Класс модели, ассоциирующийся с запросом.
     * @param array $config Конфигурация, применимая к запросу.
     * @since 1.0.0
     */
    public function __construct($modelClass, $config = [])
    {
        $this->modelClass = $modelClass;
        parent::__construct($config);
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
     * Выполняет запрос и возвращает результаты как массив.
     * @param Connection $db Подключение к базе данных.
     * @param bool $collection Возвращать коллекцию.
     * @return array|ActiveRecord[]|ActiveRecords Результат запроса.
     * @since 1.0.0
     */
    public function all($db = null, $collection = true)
    {
        $result = parent::all($db);

        if (!$this->asArray)
            if ($collection)
                return $this->createCollection($result);

        return $result;
    }

    /**
     * @inheritdoc
     * @since 1.0.0
     */
    public function prepare($builder)
    {
        // NOTE: because the same ActiveQuery may be used to build different SQL statements
        // (e.g. by ActiveDataProvider, one for count query, the other for row data query,
        // it is important to make sure the same ActiveQuery can be used to build SQL statements
        // multiple times.
        if (!empty($this->joinWith)) {
            $this->buildJoinWith();
            $this->joinWith = null;    // clean it up to avoid issue https://github.com/yiisoft/yii2/issues/2687
        }

        if (empty($this->from)) {
            /* @var $modelClass ActiveRecord */
            $modelClass = $this->modelClass;
            $tableName = $modelClass::tableName();
            $this->from = [$tableName];
        }

        if (empty($this->select) && !empty($this->join)) {
            list(, $alias) = $this->getTableNameAndAlias();
            $this->select = ["$alias.*"];
        }

        if ($this->primaryModel === null) {
            // eager loading
            $query = Query::create($this);
        } else {
            // lazy loading of a relation
            $where = $this->where;

            if ($this->via instanceof self) {
                // via junction table
                $viaModels = $this->via->findJunctionRows([$this->primaryModel]);
                $this->filterByModels($viaModels);
            } elseif (is_array($this->via)) {
                // via relation
                /* @var $viaQuery ActiveQuery */
                list($viaName, $viaQuery) = $this->via;
                if ($viaQuery->multiple) {
                    $viaModels = $viaQuery->all();
                    $this->primaryModel->populateRelation($viaName, $viaModels);
                } else {
                    $model = $viaQuery->one();
                    $this->primaryModel->populateRelation($viaName, $model);
                    $viaModels = $model === null ? [] : [$model];
                }
                $this->filterByModels($viaModels);
            } else {
                $this->filterByModels([$this->primaryModel]);
            }

            $query = Query::create($this);
            $this->where = $where;
        }

        if (!empty($this->on)) {
            $query->andWhere($this->on);
        }

        return $query;
    }

    /**
     * @inheritdoc
     * @since 1.0.0
     */
    public function populate($rows)
    {
        if (empty($rows)) {
            return [];
        }

        $models = $this->createModels($rows);
        if (!empty($this->join) && $this->indexBy === null) {
            $models = $this->removeDuplicatedModels($models);
        }
        if (!empty($this->with)) {
            $this->findWith($this->with, $models);
        }

        if ($this->inverseOf !== null) {
            $this->addInverseRelations($models);
        }

        if (!$this->asArray) {
            foreach ($models as $model) {
                $model->afterFind();
            }
        }

        return $models;
    }

    /**
     * Удаляет дублирующие записи с помощью проверки их первичного ключа.
     * @param array $models Модели для проверки.
     * @throws InvalidConfigException Если первичный ключ модели пуст.
     * @return array Уникальные модели.
     * @since 1.0.0
     */
    private function removeDuplicatedModels($models)
    {
        $hash = [];
        /* @var $class ActiveRecord */
        $class = $this->modelClass;
        $pks = $class::primaryKey();

        if (count($pks) > 1) {
            // composite primary key
            foreach ($models as $i => $model) {
                $key = [];
                foreach ($pks as $pk) {
                    if (!isset($model[$pk])) {
                        // do not continue if the primary key is not part of the result set
                        break 2;
                    }
                    $key[] = $model[$pk];
                }
                $key = serialize($key);
                if (isset($hash[$key])) {
                    unset($models[$i]);
                } else {
                    $hash[$key] = true;
                }
            }
        } elseif (empty($pks)) {
            throw new InvalidConfigException("Primary key of '{$class}' can not be empty.");
        } else {
            // single column primary key
            $pk = reset($pks);
            foreach ($models as $i => $model) {
                if (!isset($model[$pk])) {
                    // do not continue if the primary key is not part of the result set
                    break;
                }
                $key = $model[$pk];
                if (isset($hash[$key])) {
                    unset($models[$i]);
                } elseif ($key !== null) {
                    $hash[$key] = true;
                }
            }
        }

        return array_values($models);
    }

    /**
     * Выполняет запрос и возвращает одну запись.
     * @param Connection|null $db Подключение к базе данных.
     * @return ActiveRecord|array|null Одна запись или `null`,
     * если ничего не было найдено.
     * @since 1.0.0
     */
    public function one($db = null)
    {
        $row = parent::one($db);
        if ($row !== false) {
            $models = $this->populate([$row]);
            return reset($models) ?: null;
        } else {
            return null;
        }
    }

    /**
     * Создает команду базы данных для выполнения данного запроса.
     * @param Connection|null $db Подключение к базе данных.
     * @return Command Команда базы данных.
     * @since 1.0.0
     */
    public function createCommand($db = null)
    {
        /* @var $modelClass ActiveRecord */
        $modelClass = $this->modelClass;
        if ($db === null) {
            $db = $modelClass::getDb();
        }

        if ($this->sql === null) {
            list ($sql, $params) = $db->getQueryBuilder()->build($this);
        } else {
            $sql = $this->sql;
            $params = $this->params;
        }

        return $db->createCommand($sql, $params);
    }

    /**
     * @inheritdoc
     * @since 1.0.0
     */
    protected function queryScalar($selectExpression, $db)
    {
        if ($this->sql === null) {
            return parent::queryScalar($selectExpression, $db);
        }
        /* @var $modelClass ActiveRecord */
        $modelClass = $this->modelClass;
        if ($db === null) {
            $db = $modelClass::getDb();
        }
        return (new Query)->select([$selectExpression])
            ->from(['c' => "({$this->sql})"])
            ->params($this->params)
            ->createCommand($db)
            ->queryScalar();
    }

    /**
     * Присоединяет реляции к запросу с помощью join.
     * @param bool|array $eagerLoading Ленивая загрузка.
     * @param string|array $joinType Тип запроса join.
     * @return $this Текущий запрос.
     * @since 1.0.0
     */
    public function joinWith($with, $eagerLoading = true, $joinType = 'LEFT JOIN')
    {
        $relations = [];
        foreach ((array) $with as $name => $callback) {
            if (is_int($name)) {
                $name = $callback;
                $callback = null;
            }

            if (preg_match('/^(.*?)(?:\s+AS\s+|\s+)(\w+)$/i', $name, $matches)) {
                // relation is defined with an alias, adjust callback to apply alias
                list(, $relation, $alias) = $matches;
                $name = $relation;
                $callback = function ($query) use ($callback, $alias) {
                    /** @var $query ActiveQuery */
                    $query->alias($alias);
                    if ($callback !== null) {
                        call_user_func($callback, $query);
                    }
                };
            }

            if ($callback === null) {
                $relations[] = $name;
            } else {
                $relations[$name] = $callback;
            }
        }
        $this->joinWith[] = [$relations, $eagerLoading, $joinType];
        return $this;
    }

    private function buildJoinWith()
    {
        $join = $this->join;
        $this->join = [];

        $model = new $this->modelClass;
        foreach ($this->joinWith as $config) {
            list ($with, $eagerLoading, $joinType) = $config;
            $this->joinWithRelations($model, $with, $joinType);

            if (is_array($eagerLoading)) {
                foreach ($with as $name => $callback) {
                    if (is_int($name)) {
                        if (!in_array($callback, $eagerLoading, true)) {
                            unset($with[$name]);
                        }
                    } elseif (!in_array($name, $eagerLoading, true)) {
                        unset($with[$name]);
                    }
                }
            } elseif (!$eagerLoading) {
                $with = [];
            }

            $this->with($with);
        }

        // remove duplicated joins added by joinWithRelations that may be added
        // e.g. when joining a relation and a via relation at the same time
        $uniqueJoins = [];
        foreach ($this->join as $j) {
            $uniqueJoins[serialize($j)] = $j;
        }
        $this->join = array_values($uniqueJoins);

        if (!empty($join)) {
            // append explicit join to joinWith()
            // https://github.com/yiisoft/yii2/issues/2880
            $this->join = empty($this->join) ? $join : array_merge($this->join, $join);
        }
    }

    /**
     * Присоеденяет определенную реляцию с помощью inner join.
     * @param string|array $with Реляция для присоединения.
     * @param bool|array $eagerLoading Ленивая загрузка.
     * @return $this Текущий запрос.
     * @see joinWith()
     * @since 1.0.0
     */
    public function innerJoinWith($with, $eagerLoading = true)
    {
        return $this->joinWith($with, $eagerLoading, 'INNER JOIN');
    }

    /**
     * Модифицирует текущий запрос добавляя элементы join, базирующиеся на реляциях.
     * @param ActiveRecord $model Первичная модель.
     * @param array $with Реляции.
     * @param string|array $joinType Тип join.
     * @since 1.0.0
     */
    private function joinWithRelations($model, $with, $joinType)
    {
        $relations = [];

        foreach ($with as $name => $callback) {
            if (is_int($name)) {
                $name = $callback;
                $callback = null;
            }

            $primaryModel = $model;
            $parent = $this;
            $prefix = '';
            while (($pos = strpos($name, '.')) !== false) {
                $childName = substr($name, $pos + 1);
                $name = substr($name, 0, $pos);
                $fullName = $prefix === '' ? $name : "$prefix.$name";
                if (!isset($relations[$fullName])) {
                    $relations[$fullName] = $relation = $primaryModel->getRelation($name);
                    $this->joinWithRelation($parent, $relation, $this->getJoinType($joinType, $fullName));
                } else {
                    $relation = $relations[$fullName];
                }
                $primaryModel = new $relation->modelClass;
                $parent = $relation;
                $prefix = $fullName;
                $name = $childName;
            }

            $fullName = $prefix === '' ? $name : "$prefix.$name";
            if (!isset($relations[$fullName])) {
                $relations[$fullName] = $relation = $primaryModel->getRelation($name);
                if ($callback !== null) {
                    call_user_func($callback, $relation);
                }
                if (!empty($relation->joinWith)) {
                    $relation->buildJoinWith();
                }
                $this->joinWithRelation($parent, $relation, $this->getJoinType($joinType, $fullName));
            }
        }
    }

    /**
     * Возвращает тип join
     * @param string|array $joinType Полученные типы join.
     * @param string $name Реляция.
     * @return string Реальный тип join.
     * @since 1.0.0
     */
    private function getJoinType($joinType, $name)
    {
        if (is_array($joinType) && isset($joinType[$name])) {
            return $joinType[$name];
        } else {
            return is_string($joinType) ? $joinType : 'INNER JOIN';
        }
    }

    /**
     * Возвращает имя таблицы и алиас для [[modelClass]].
     * @return array Имя таблицы и алиаса.
     * @internal
     * @since 1.0.0
     */
    private function getTableNameAndAlias()
    {
        if (empty($this->from)) {
            /* @var $modelClass ActiveRecord */
            $modelClass = $this->modelClass;
            $tableName = $modelClass::tableName();
        } else {
            $tableName = '';
            foreach ($this->from as $alias => $tableName) {
                if (is_string($alias)) {
                    return [$tableName, $alias];
                } else {
                    break;
                }
            }
        }

        if (preg_match('/^(.*?)\s+({{\w+}}|\w+)$/', $tableName, $matches)) {
            $alias = $matches[2];
        } else {
            $alias = $tableName;
        }

        return [$tableName, $alias];
    }

    /**
     * Присоединяет родительский запрос к дочернему.
     * @param ActiveQuery $parent
     * @param ActiveQuery $child
     * @param string $joinType
     * @since 1.0.0
     */
    private function joinWithRelation($parent, $child, $joinType)
    {
        $via = $child->via;
        $child->via = null;
        if ($via instanceof ActiveQuery) {
            // via table
            $this->joinWithRelation($parent, $via, $joinType);
            $this->joinWithRelation($via, $child, $joinType);
            return;
        } elseif (is_array($via)) {
            // via relation
            $this->joinWithRelation($parent, $via[1], $joinType);
            $this->joinWithRelation($via[1], $child, $joinType);
            return;
        }

        list ($parentTable, $parentAlias) = $parent->getTableNameAndAlias();
        list ($childTable, $childAlias) = $child->getTableNameAndAlias();

        if (!empty($child->link)) {

            if (strpos($parentAlias, '{{') === false) {
                $parentAlias = '{{' . $parentAlias . '}}';
            }
            if (strpos($childAlias, '{{') === false) {
                $childAlias = '{{' . $childAlias . '}}';
            }

            $on = [];
            foreach ($child->link as $childColumn => $parentColumn) {
                $on[] = "$parentAlias.[[$parentColumn]] = $childAlias.[[$childColumn]]";
            }
            $on = implode(' AND ', $on);
            if (!empty($child->on)) {
                $on = ['and', $on, $child->on];
            }
        } else {
            $on = $child->on;
        }
        $this->join($joinType, empty($child->from) ? $childTable : $child->from, $on);

        if (!empty($child->where)) {
            $this->andWhere($child->where);
        }
        if (!empty($child->having)) {
            $this->andHaving($child->having);
        }
        if (!empty($child->orderBy)) {
            $this->addOrderBy($child->orderBy);
        }
        if (!empty($child->groupBy)) {
            $this->addGroupBy($child->groupBy);
        }
        if (!empty($child->params)) {
            $this->addParams($child->params);
        }
        if (!empty($child->join)) {
            foreach ($child->join as $join) {
                $this->join[] = $join;
            }
        }
        if (!empty($child->union)) {
            foreach ($child->union as $union) {
                $this->union[] = $union;
            }
        }
    }

    /**
     * Добавляет условия к запросу.
     * @param string|array $condition Условия.
     * @param array $params Параметры для присоединения к запросу.
     * @return $this Текущий запрос.
     * @since 1.0.0
     */
    public function onCondition($condition, $params = [])
    {
        $this->on = $condition;
        $this->addParams($params);
        return $this;
    }

    /**
     * Добавляет условия к существующим условиям запроса с помощью AND.
     * @param string|array $condition Условия.
     * @param array $params Параметры для присоединения к запросу.
     * @return $this Текущий запрос.
     * @see onCondition()
     * @see orOnCondition()
     * @since 1.0.0
     */
    public function andOnCondition($condition, $params = [])
    {
        if ($this->on === null) {
            $this->on = $condition;
        } else {
            $this->on = ['and', $this->on, $condition];
        }
        $this->addParams($params);
        return $this;
    }

    /**
     * Добавляет условия к существующим условиям запроса с помощью OR.
     * @param string|array $condition Условия.
     * @param array $params Параметры для присоединения к запросу.
     * @return $this Текущий запрос.
     * @see onCondition()
     * @see andOnCondition()
     * @since 1.0.0
     */
    public function orOnCondition($condition, $params = [])
    {
        if ($this->on === null) {
            $this->on = $condition;
        } else {
            $this->on = ['or', $this->on, $condition];
        }
        $this->addParams($params);
        return $this;
    }

    /**
     * Определяет таблицу связи для реляции.
     * @param string $tableName Наименование таблицы.
     * @param array $link Объединение столбцов таблиц.
     * @param callable $callable Функция обратного вызова для кастомизации.
     * @return $this Текущий запрос.
     * @see via()
     * @since 1.0.0
     */
    public function viaTable($tableName, $link, callable $callable = null)
    {
        $relation = new ActiveQuery(get_class($this->primaryModel), [
            'from' => [$tableName],
            'link' => $link,
            'multiple' => true,
            'asArray' => true,
        ]);
        $this->via = $relation;
        if ($callable !== null) {
            call_user_func($callable, $relation);
        }

        return $this;
    }

    /**
     * Определяет алиас для таблицы, определенной в [[modelClass]].
     * @param string $alias Алиас.
     * @return $this Текущий запрос.
     * @since 1.0.0
     */
    public function alias($alias)
    {
        if (empty($this->from) || count($this->from) < 2) {
            list($tableName, ) = $this->getTableNameAndAlias();
            $this->from = [$alias => $tableName];
        } else {
            /* @var $modelClass ActiveRecord */
            $modelClass = $this->modelClass;
            $tableName = $modelClass::tableName();

            foreach ($this->from as $key => $table) {
                if ($table === $tableName) {
                    unset($this->from[$key]);
                    $this->from[$alias] = $tableName;
                }
            }
        }
        return $this;
    }

    /**
     * Возвращает коллекцию для запроса.
     * @params ActiveRecord[] $items Элементы коллекции.
     * @return ActiveRecords Коллекция элементов.
     */
    public function createCollection($items)
    {
        return new ActiveRecords($items);
    }
}
