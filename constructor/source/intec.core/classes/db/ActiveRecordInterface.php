<?php
namespace intec\core\db;

/**
 * Interface ActiveRecordInterface
 * @package intec\core\db
 * @since 1.0.0
 */
interface ActiveRecordInterface
{
    /**
     * @return string[]
     * @since 1.0.0
     */
    public static function primaryKey();

    /**
     * @return array
     * @since 1.0.0
     */
    public function attributes();

    /**
     * @param string $name
     * @return mixed
     * @see hasAttribute()
     * @since 1.0.0
     */
    public function getAttribute($name);

    /**
     * @param string $name
     * @param mixed $value
     * @see hasAttribute()
     * @since 1.0.0
     */
    public function setAttribute($name, $value);

    /**
     * @param string $name
     * @return bool
     * @since 1.0.0
     */
    public function hasAttribute($name);

    /**
     * @param bool $asArray
     * @return mixed
     * @since 1.0.0
     */
    public function getPrimaryKey($asArray = false);

    /**
     * @param bool $asArray
     * @return mixed
     * @since 1.0.0
     */
    public function getOldPrimaryKey($asArray = false);

    /**
     * @param array $keys
     * @return bool
     * @since 1.0.0
     */
    public static function isPrimaryKey($keys);

    /**
     * @return ActiveQueryInterface
     * @since 1.0.0
     */
    public static function find();

    /**
     * @param mixed $condition
     * @return static ActiveRecord
     * @since 1.0.0
     */
    public static function findOne($condition);

    /**
     * @param mixed $condition
     * @return array
     * @since 1.0.0
     */
    public static function findAll($condition);

    /**
     * @param array $attributes
     * @param array $condition
     * @return int
     * @since 1.0.0
     */
    public static function updateAll($attributes, $condition = null);

    /**
     * @param array $condition
     * @return int
     * @since 1.0.0
     */
    public static function deleteAll($condition = null);

    /**
     * @param bool $runValidation
     * @param array $attributeNames
     * @return bool
     * @since 1.0.0
     */
    public function save($runValidation = true, $attributeNames = null);

    /**
     * @param bool $runValidation
     * @param array $attributes
     * @return bool
     * @since 1.0.0
     */
    public function insert($runValidation = true, $attributes = null);

    /**
     * @param bool $runValidation
     * @return int|bool
     * @since 1.0.0
     */
    public function update($runValidation = true, $attributeNames = null);

    /**
     * @return int|bool
     * @since 1.0.0
     */
    public function delete();

    /**
     * @return bool
     * @since 1.0.0
     */
    public function getIsNewRecord();

    /**
     * @param static $record
     * @return bool
     * @since 1.0.0
     */
    public function equals($record);

    /**
     * @param string $name
     * @param bool $throwException
     * @return ActiveQueryInterface
     * @since 1.0.0
     */
    public function getRelation($name, $throwException = true);

    /**
     * @param string $name
     * @param ActiveRecordInterface|array|null $records
     * @since 1.0.0
     */
    public function populateRelation($name, $records);

    /**
     * @param string $name
     * @param static $model
     * @param array $extraColumns
     * @since 1.0.0
     */
    public function link($name, $model, $extraColumns = []);

    /**
     * @param string $name
     * @param static $model
     * @param bool $delete
     * @since 1.0.0
     */
    public function unlink($name, $model, $delete = false);

    /**
     * @return mixed
     * @since 1.0.0
     */
    public static function getDb();
}
