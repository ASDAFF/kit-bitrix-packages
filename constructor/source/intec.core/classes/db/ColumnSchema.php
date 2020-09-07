<?php
namespace intec\core\db;

use intec\core\base\BaseObject;

/**
 * Class ColumnSchema
 * @package intec\core\db
 * @since 1.0.0
 */
class ColumnSchema extends BaseObject
{
    /**
     * @var string
     * @since 1.0.0
     */
    public $name;
    /**
     * @var bool
     * @since 1.0.0
     */
    public $allowNull;
    /**
     * @var string
     * @since 1.0.0
     */
    public $type;
    /**
     * @var string
     * @since 1.0.0
     */
    public $phpType;
    /**
     * @var string
     * @since 1.0.0
     */
    public $dbType;
    /**
     * @var mixed
     * @since 1.0.0
     */
    public $defaultValue;
    /**
     * @var array
     * @since 1.0.0
     */
    public $enumValues;
    /**
     * @var int
     * @since 1.0.0
     */
    public $size;
    /**
     * @var int
     * @since 1.0.0
     */
    public $precision;
    /**
     * @var int
     * @since 1.0.0
     */
    public $scale;
    /**
     * @var bool
     * @since 1.0.0
     */
    public $isPrimaryKey;
    /**
     * @var bool
     * @since 1.0.0
     */
    public $autoIncrement = false;
    /**
     * @var bool
     * @since 1.0.0
     */
    public $unsigned;
    /**
     * @var string
     * @since 1.0.0
     */
    public $comment;


    /**
     * @param mixed $value
     * @return mixed
     * @since 1.0.0
     */
    public function phpTypecast($value)
    {
        return $this->typecast($value);
    }

    /**
     * @param mixed $value
     * @return mixed
     * @since 1.0.0
     */
    public function dbTypecast($value)
    {
        // the default implementation does the same as casting for PHP, but it should be possible
        // to override this with annotation of explicit PDO type.
        return $this->typecast($value);
    }

    /**
     * @param mixed $value
     * @return mixed
     * @since 1.0.0
     */
    protected function typecast($value)
    {
        if ($value === '' && $this->type !== Schema::TYPE_TEXT && $this->type !== Schema::TYPE_STRING && $this->type !== Schema::TYPE_BINARY && $this->type !== Schema::TYPE_CHAR) {
            return null;
        }
        if ($value === null || gettype($value) === $this->phpType || $value instanceof Expression || $value instanceof Query) {
            return $value;
        }
        switch ($this->phpType) {
            case 'resource':
            case 'string':
                if (is_resource($value)) {
                    return $value;
                }
                if (is_float($value)) {
                    // ensure type cast always has . as decimal separator in all locales
                    return str_replace(',', '.', (string) $value);
                }
                return (string) $value;
            case 'integer':
                return (int) $value;
            case 'boolean':
                // treating a 0 bit value as false too
                // https://github.com/yiisoft/yii2/issues/9006
                return (bool) $value && $value !== "\0";
            case 'double':
                return (double) $value;
        }

        return $value;
    }
}
