<?php
namespace intec\core\db;

use intec\core\base\InvalidConfigException;
use intec\core\base\InvalidParamException;

/**
 * Trait ActiveRelationTrait
 * @method ActiveRecordInterface one()
 * @method ActiveRecordInterface[] all()
 * @property ActiveRecord $modelClass
 * @package intec\core\db
 * @since 1.0.0
 */
trait ActiveRelationTrait
{
    /**
     * @var bool
     * @since 1.0.0
     */
    public $multiple;
    /**
     * @var ActiveRecord
     * @since 1.0.0
     */
    public $primaryModel;
    /**
     * @var array
     * @since 1.0.0
     */
    public $link;
    /**
     * @var array|object
     * @see via()
     * @since 1.0.0
     */
    public $via;
    /**
     * @var string
     * @see inverseOf()
     * @since 1.0.0
     */
    public $inverseOf;


    /**
     * Clones internal objects.
     * @since 1.0.0
     */
    public function __clone()
    {
        parent::__clone();
        if (is_object($this->via)) {
            $this->via = clone $this->via;
        } elseif (is_array($this->via)) {
            $this->via = [$this->via[0], clone $this->via[1]];
        }
    }

    /**
     * @param string $relationName
     * @param callable $callable
     * @return $this
     * @since 1.0.0
     */
    public function via($relationName, callable $callable = null)
    {
        $relation = $this->primaryModel->getRelation($relationName);
        $this->via = [$relationName, $relation];
        if ($callable !== null) {
            call_user_func($callable, $relation);
        }
        return $this;
    }

    /**
     * @param string $relationName
     * @return $this
     * @since 1.0.0
     */
    public function inverseOf($relationName)
    {
        $this->inverseOf = $relationName;
        return $this;
    }

    /**
     * @param string $name
     * @param ActiveRecordInterface|ActiveRecordBase $model
     * @return mixed
     * @throws InvalidParamException
     * @since 1.0.0
     */
    public function findFor($name, $model)
    {
        if (method_exists($model, 'get' . $name)) {
            $method = new \ReflectionMethod($model, 'get' . $name);
            $realName = lcfirst(substr($method->getName(), 3));
            if ($realName !== $name) {
                throw new InvalidParamException('Relation names are case sensitive. ' . get_class($model) . " has a relation named \"$realName\" instead of \"$name\".");
            }
        }

        return $this->multiple ? $this->all() : $this->one();
    }

    /**
     * @param array $result
     * @since 1.0.0
     */
    private function addInverseRelations(&$result)
    {
        if ($this->inverseOf === null) {
            return;
        }

        foreach ($result as $i => $relatedModel) {
            if ($relatedModel instanceof ActiveRecordInterface) {
                if (!isset($inverseRelation)) {
                    $inverseRelation = $relatedModel->getRelation($this->inverseOf);
                }
                $relatedModel->populateRelation($this->inverseOf, $inverseRelation->multiple ? [$this->primaryModel] : $this->primaryModel);
            } else {
                if (!isset($inverseRelation)) {
                    $inverseRelation = (new $this->modelClass)->getRelation($this->inverseOf);
                }
                $result[$i][$this->inverseOf] = $inverseRelation->multiple ? [$this->primaryModel] : $this->primaryModel;
            }
        }
    }

    /**
     * @param string $name
     * @param array $primaryModels
     * @return array
     * @throws InvalidConfigException
     * @since 1.0.0
     */
    public function populateRelation($name, &$primaryModels)
    {
        if (!is_array($this->link)) {
            throw new InvalidConfigException('Invalid link: it must be an array of key-value pairs.');
        }

        if ($this->via instanceof self) {
            /* @var $viaQuery ActiveRelationTrait */
            $viaQuery = $this->via;
            $viaModels = $viaQuery->findJunctionRows($primaryModels);
            $this->filterByModels($viaModels);
        } elseif (is_array($this->via)) {
            /* @var $viaQuery ActiveRelationTrait|ActiveQueryTrait */
            list($viaName, $viaQuery) = $this->via;
            if ($viaQuery->asArray === null) {
                $viaQuery->asArray($this->asArray);
            }
            $viaQuery->primaryModel = null;
            $viaModels = $viaQuery->populateRelation($viaName, $primaryModels);
            $this->filterByModels($viaModels);
        } else {
            $this->filterByModels($primaryModels);
        }

        if (!$this->multiple && count($primaryModels) === 1) {
            $model = $this->one();
            foreach ($primaryModels as $i => $primaryModel) {
                if ($primaryModel instanceof ActiveRecordInterface) {
                    $primaryModel->populateRelation($name, $model);
                } else {
                    $primaryModels[$i][$name] = $model;
                }
                if ($this->inverseOf !== null) {
                    $this->populateInverseRelation($primaryModels, [$model], $name, $this->inverseOf);
                }
            }

            return [$model];
        } else {
            $indexBy = $this->indexBy;
            $this->indexBy = null;
            $models = $this->all();

            if (isset($viaModels, $viaQuery)) {
                $buckets = $this->buildBuckets($models, $this->link, $viaModels, $viaQuery->link);
            } else {
                $buckets = $this->buildBuckets($models, $this->link);
            }

            $this->indexBy = $indexBy;
            if ($this->indexBy !== null && $this->multiple) {
                $buckets = $this->indexBuckets($buckets, $this->indexBy);
            }

            $link = array_values(isset($viaQuery) ? $viaQuery->link : $this->link);
            foreach ($primaryModels as $i => $primaryModel) {
                if ($this->multiple && count($link) === 1 && is_array($keys = $primaryModel[reset($link)])) {
                    $value = [];
                    foreach ($keys as $key) {
                        $key = $this->normalizeModelKey($key);
                        if (isset($buckets[$key])) {
                            if ($this->indexBy !== null) {
                                // if indexBy is set, array_merge will cause renumbering of numeric array
                                foreach ($buckets[$key] as $bucketKey => $bucketValue) {
                                    $value[$bucketKey] = $bucketValue;
                                }
                            } else {
                                $value = array_merge($value, $buckets[$key]);
                            }
                        }
                    }
                } else {
                    $key = $this->getModelKey($primaryModel, $link);
                    $value = isset($buckets[$key]) ? $buckets[$key] : ($this->multiple ? [] : null);
                }
                if ($primaryModel instanceof ActiveRecordInterface) {
                    $primaryModel->populateRelation($name, $value);
                } else {
                    $primaryModels[$i][$name] = $value;
                }
            }
            if ($this->inverseOf !== null) {
                $this->populateInverseRelation($primaryModels, $models, $name, $this->inverseOf);
            }

            return $models;
        }
    }

    /**
     * @param ActiveRecordInterface[] $primaryModels
     * @param ActiveRecordInterface[] $models
     * @param string $primaryName
     * @param string $name
     * @since 1.0.0
     */
    private function populateInverseRelation(&$primaryModels, $models, $primaryName, $name)
    {
        if (empty($models) || empty($primaryModels)) {
            return;
        }
        $model = reset($models);
        /* @var $relation ActiveQueryInterface|ActiveQuery */
        $relation = $model instanceof ActiveRecordInterface ? $model->getRelation($name) : (new $this->modelClass)->getRelation($name);

        if ($relation->multiple) {
            $buckets = $this->buildBuckets($primaryModels, $relation->link, null, null, false);
            if ($model instanceof ActiveRecordInterface) {
                foreach ($models as $model) {
                    $key = $this->getModelKey($model, $relation->link);
                    $model->populateRelation($name, isset($buckets[$key]) ? $buckets[$key] : []);
                }
            } else {
                foreach ($primaryModels as $i => $primaryModel) {
                    if ($this->multiple) {
                        foreach ($primaryModel as $j => $m) {
                            $key = $this->getModelKey($m, $relation->link);
                            $primaryModels[$i][$j][$name] = isset($buckets[$key]) ? $buckets[$key] : [];
                        }
                    } elseif (!empty($primaryModel[$primaryName])) {
                        $key = $this->getModelKey($primaryModel[$primaryName], $relation->link);
                        $primaryModels[$i][$primaryName][$name] = isset($buckets[$key]) ? $buckets[$key] : [];
                    }
                }
            }
        } else {
            if ($this->multiple) {
                foreach ($primaryModels as $i => $primaryModel) {
                    foreach ($primaryModel[$primaryName] as $j => $m) {
                        if ($m instanceof ActiveRecordInterface) {
                            $m->populateRelation($name, $primaryModel);
                        } else {
                            $primaryModels[$i][$primaryName][$j][$name] = $primaryModel;
                        }
                    }
                }
            } else {
                foreach ($primaryModels as $i => $primaryModel) {
                    if ($primaryModels[$i][$primaryName] instanceof ActiveRecordInterface) {
                        $primaryModels[$i][$primaryName]->populateRelation($name, $primaryModel);
                    } elseif (!empty($primaryModels[$i][$primaryName])) {
                        $primaryModels[$i][$primaryName][$name] = $primaryModel;
                    }
                }
            }
        }
    }

    /**
     * @param array $models
     * @param array $link
     * @param array $viaModels
     * @param array $viaLink
     * @param bool $checkMultiple
     * @return array
     * @since 1.0.0
     */
    private function buildBuckets($models, $link, $viaModels = null, $viaLink = null, $checkMultiple = true)
    {
        if ($viaModels !== null) {
            $map = [];
            $viaLinkKeys = array_keys($viaLink);
            $linkValues = array_values($link);
            foreach ($viaModels as $viaModel) {
                $key1 = $this->getModelKey($viaModel, $viaLinkKeys);
                $key2 = $this->getModelKey($viaModel, $linkValues);
                $map[$key2][$key1] = true;
            }
        }

        $buckets = [];
        $linkKeys = array_keys($link);

        if (isset($map)) {
            foreach ($models as $model) {
                $key = $this->getModelKey($model, $linkKeys);
                if (isset($map[$key])) {
                    foreach (array_keys($map[$key]) as $key2) {
                        $buckets[$key2][] = $model;
                    }
                }
            }
        } else {
            foreach ($models as $model) {
                $key = $this->getModelKey($model, $linkKeys);
                $buckets[$key][] = $model;
            }
        }

        if ($checkMultiple && !$this->multiple) {
            foreach ($buckets as $i => $bucket) {
                $buckets[$i] = reset($bucket);
            }
        }

        return $buckets;
    }


    /**
     * @param array $buckets
     * @param string|callable $indexBy
     * @return array
     * @since 1.0.0
     */
    private function indexBuckets($buckets, $indexBy)
    {
        $result = [];
        foreach ($buckets as $key => $models) {
            $result[$key] = [];
            foreach ($models as $model) {
                $index = is_string($indexBy) ? $model[$indexBy] : call_user_func($indexBy, $model);
                $result[$key][$index] = $model;
            }
        }
        return $result;
    }

    /**
     * @param array $attributes
     * @return array
     * @since 1.0.0
     */
    private function prefixKeyColumns($attributes)
    {
        if ($this instanceof ActiveQuery && (!empty($this->join) || !empty($this->joinWith))) {
            if (empty($this->from)) {
                /* @var $modelClass ActiveRecord */
                $modelClass = $this->modelClass;
                $alias = $modelClass::tableName();
            } else {
                foreach ($this->from as $alias => $table) {
                    if (!is_string($alias)) {
                        $alias = $table;
                    }
                    break;
                }
            }
            if (isset($alias)) {
                foreach ($attributes as $i => $attribute) {
                    $attributes[$i] = "$alias.$attribute";
                }
            }
        }
        return $attributes;
    }

    /**
     * @param array $models
     * @since 1.0.0
     */
    private function filterByModels($models)
    {
        $attributes = array_keys($this->link);

        $attributes = $this->prefixKeyColumns($attributes);

        $values = [];
        if (count($attributes) === 1) {
            $attribute = reset($this->link);
            foreach ($models as $model) {
                if (($value = $model[$attribute]) !== null) {
                    if (is_array($value)) {
                        $values = array_merge($values, $value);
                    } else {
                        $values[] = $value;
                    }
                }
            }
            if (empty($values)) {
                $this->emulateExecution();
            }
        } else {
            $prefixedLink = array_combine(
                $attributes,
                array_values($this->link)
            );
            foreach ($models as $model) {
                $v = [];
                foreach ($prefixedLink as $attribute => $link) {
                    $v[$attribute] = $model[$link];
                }
                $values[] = $v;
                if (empty($v)) {
                    $this->emulateExecution();
                }
            }
        }
        $this->andWhere(['in', $attributes, array_unique($values, SORT_REGULAR)]);
    }

    /**
     * @param ActiveRecordInterface|array $model
     * @param array $attributes
     * @return string
     * @since 1.0.0
     */
    private function getModelKey($model, $attributes)
    {
        $key = [];
        foreach ($attributes as $attribute) {
            $key[] = $this->normalizeModelKey($model[$attribute]);
        }
        if (count($key) > 1) {
            return serialize($key);
        }
        $key = reset($key);
        return is_scalar($key) ? $key : serialize($key);
    }

    /**
     * @param mixed $value
     * @return string
     * @since 1.0.0
     */
    private function normalizeModelKey($value)
    {
        if (is_object($value) && method_exists($value, '__toString')) {
            $value = $value->__toString();
        }
        return $value;
    }

    /**
     * @param array $primaryModels
     * @return array
     * @since 1.0.0
     */
    private function findJunctionRows($primaryModels)
    {
        if (empty($primaryModels)) {
            return [];
        }
        $this->filterByModels($primaryModels);
        /* @var $primaryModel ActiveRecord */
        $primaryModel = reset($primaryModels);
        if (!$primaryModel instanceof ActiveRecordInterface) {
            $primaryModel = $this->modelClass;
        }

        return $this->asArray()->all($primaryModel::getDb());
    }
}
