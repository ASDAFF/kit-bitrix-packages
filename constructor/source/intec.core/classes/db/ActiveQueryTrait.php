<?php
namespace intec\core\db;

/**
 * ActiveQueryTrait implements the common methods and properties for active record query classes.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @author Carsten Brandt <mail@cebe.cc>
 * @since 2.0
 */
trait ActiveQueryTrait
{
    /**
     * Класс ActiveRecord.
     * @var string
     */
    public $modelClass;
    /**
     * Список реляций.
     * @var array
     */
    public $with;
    /**
     * Возвращать результаты в виде массива.
     * @var bool
     */
    public $asArray;


    /**
     * Устанавливает свойство [[asArray]].
     * @param bool $value Возвращать результаты в виде массива.
     * @return $this Текущий запрос.
     * @since 1.0.0
     */
    public function asArray($value = true)
    {
        $this->asArray = $value;
        return $this;
    }

    /**
     * Устанавливает реляции, которые необходимо получить с данным запросом.
     * @return $this Текущий запрос.
     * @since 1.0.0
     */
    public function with()
    {
        $with = func_get_args();
        if (isset($with[0]) && is_array($with[0])) {
            // the parameter is given as an array
            $with = $with[0];
        }

        if (empty($this->with)) {
            $this->with = $with;
        } elseif (!empty($with)) {
            foreach ($with as $name => $value) {
                if (is_int($name)) {
                    // repeating relation is fine as normalizeRelations() handle it well
                    $this->with[] = $value;
                } else {
                    $this->with[$name] = $value;
                }
            }
        }

        return $this;
    }

    /**
     * Конвертирует найденные строки в модели.
     * @param array $rows
     * @return array|ActiveRecord[]
     * @since 2.0.11
     */
    protected function createModels($rows)
    {
        $models = [];
        if ($this->asArray) {
            if ($this->indexBy === null) {
                return $rows;
            }
            foreach ($rows as $row) {
                if (is_string($this->indexBy)) {
                    $key = $row[$this->indexBy];
                } else {
                    $key = call_user_func($this->indexBy, $row);
                }
                $models[$key] = $row;
            }
        } else {
            /* @var $class ActiveRecord */
            $class = $this->modelClass;
            if ($this->indexBy === null) {
                foreach ($rows as $row) {
                    $model = $class::instantiate($row);
                    $modelClass = get_class($model);
                    $modelClass::populateRecord($model, $row);
                    $models[] = $model;
                }
            } else {
                foreach ($rows as $row) {
                    $model = $class::instantiate($row);
                    $modelClass = get_class($model);
                    $modelClass::populateRecord($model, $row);
                    if (is_string($this->indexBy)) {
                        $key = $model->{$this->indexBy};
                    } else {
                        $key = call_user_func($this->indexBy, $model);
                    }
                    $models[$key] = $model;
                }
            }
        }

        return $models;
    }

    /**
     * Ищет реляции и привязывает их к моделям.
     * @param array $with Список реляций.
     * @param array|ActiveRecord[] $models Модели.
     */
    public function findWith($with, &$models)
    {
        $primaryModel = reset($models);
        if (!$primaryModel instanceof ActiveRecordInterface) {
            $primaryModel = new $this->modelClass;
        }
        $relations = $this->normalizeRelations($primaryModel, $with);
        /* @var $relation ActiveQuery */
        foreach ($relations as $name => $relation) {
            if ($relation->asArray === null) {
                // inherit asArray from primary query
                $relation->asArray($this->asArray);
            }
            $relation->populateRelation($name, $models);
        }
    }

    /**
     * @param ActiveRecord $model
     * @param array $with
     * @return ActiveQueryInterface[]
     */
    private function normalizeRelations($model, $with)
    {
        $relations = [];
        foreach ($with as $name => $callback) {
            if (is_int($name)) {
                $name = $callback;
                $callback = null;
            }
            if (($pos = strpos($name, '.')) !== false) {
                // with sub-relations
                $childName = substr($name, $pos + 1);
                $name = substr($name, 0, $pos);
            } else {
                $childName = null;
            }

            if (!isset($relations[$name])) {
                $relation = $model->getRelation($name);
                $relation->primaryModel = null;
                $relations[$name] = $relation;
            } else {
                $relation = $relations[$name];
            }

            if (isset($childName)) {
                $relation->with[$childName] = $callback;
            } elseif ($callback !== null) {
                call_user_func($callback, $relation);
            }
        }

        return $relations;
    }
}
