<?php
namespace intec\core\db;

use intec\core\base\BaseObject;

/**
 * Class ColumnSchemaBuilder
 * @package intec\core\db
 * @since 1.0.0
 */
class ColumnSchemaBuilder extends BaseObject
{
    const CATEGORY_PK = 'pk';
    const CATEGORY_STRING = 'string';
    const CATEGORY_NUMERIC = 'numeric';
    const CATEGORY_TIME = 'time';
    const CATEGORY_OTHER = 'other';

    /**
     * @var string
     * @since 1.0.0
     */
    protected $type;
    /**
     * @var int|string|array
     * @since 1.0.0
     */
    protected $length;
    /**
     * @var bool|null
     * @since 1.0.0
     */
    protected $isNotNull;
    /**
     * @var bool
     * @since 1.0.0
     */
    protected $isUnique = false;
    /**
     * @var string
     * @since 1.0.0
     */
    protected $check;
    /**
     * @var mixed
     * @since 1.0.0
     */
    protected $default;
    /**
     * @var mixed
     * @since 1.0.0
     */
    protected $append;
    /**
     * @var bool
     * @since 1.0.0
     */
    protected $isUnsigned = false;
    /**
     * @var string
     * @since 1.0.0
     */
    protected $after;
    /**
     * @var bool
     * @since 1.0.0
     */
    protected $isFirst;


    /**
     * @var array
     * @since 1.0.0
     */
    public $categoryMap = [
        Schema::TYPE_PK => self::CATEGORY_PK,
        Schema::TYPE_UPK => self::CATEGORY_PK,
        Schema::TYPE_BIGPK => self::CATEGORY_PK,
        Schema::TYPE_UBIGPK => self::CATEGORY_PK,
        Schema::TYPE_CHAR => self::CATEGORY_STRING,
        Schema::TYPE_STRING => self::CATEGORY_STRING,
        Schema::TYPE_TEXT => self::CATEGORY_STRING,
        Schema::TYPE_SMALLINT => self::CATEGORY_NUMERIC,
        Schema::TYPE_INTEGER => self::CATEGORY_NUMERIC,
        Schema::TYPE_BIGINT => self::CATEGORY_NUMERIC,
        Schema::TYPE_FLOAT => self::CATEGORY_NUMERIC,
        Schema::TYPE_DOUBLE => self::CATEGORY_NUMERIC,
        Schema::TYPE_DECIMAL => self::CATEGORY_NUMERIC,
        Schema::TYPE_DATETIME => self::CATEGORY_TIME,
        Schema::TYPE_TIMESTAMP => self::CATEGORY_TIME,
        Schema::TYPE_TIME => self::CATEGORY_TIME,
        Schema::TYPE_DATE => self::CATEGORY_TIME,
        Schema::TYPE_BINARY => self::CATEGORY_OTHER,
        Schema::TYPE_BOOLEAN => self::CATEGORY_NUMERIC,
        Schema::TYPE_MONEY => self::CATEGORY_NUMERIC,
    ];
    /**
     * @var Connection
     * @since 1.0.0
     */
    public $db;
    /**
     * @var string
     * @since 1.0.0
     */
    public $comment;

    /**
     * @param string $type
     * @param int|string|array $length
     * @param Connection $db
     * @param array $config
     * @since 1.0.0
     */
    public function __construct($type, $length = null, $db = null, $config = [])
    {
        $this->type = $type;
        $this->length = $length;
        $this->db = $db;
        parent::__construct($config);
    }

    /**
     * @return $this
     * @since 1.0.0
     */
    public function notNull()
    {
        $this->isNotNull = true;
        return $this;
    }

    /**
     * @return $this
     * @since 1.0.0
     */
    public function null()
    {
        $this->isNotNull = false;
        return $this;
    }

    /**
     * @return $this
     * @since 1.0.0
     */
    public function unique()
    {
        $this->isUnique = true;
        return $this;
    }

    /**
     * @param string $check
     * @return $this
     * @since 1.0.0
     */
    public function check($check)
    {
        $this->check = $check;
        return $this;
    }

    /**
     * @param mixed $default
     * @return $this
     * @since 1.0.0
     */
    public function defaultValue($default)
    {
        if ($default === null) {
            $this->null();
        }

        $this->default = $default;
        return $this;
    }

    /**
     * @param string $comment
     * @return $this
     * @since 1.0.0
     */
    public function comment($comment)
    {
        $this->comment = $comment;
        return $this;
    }

    /**
     * @return $this
     * @since 1.0.0
     */
    public function unsigned()
    {
        switch ($this->type) {
            case Schema::TYPE_PK:
                $this->type = Schema::TYPE_UPK;
                break;
            case Schema::TYPE_BIGPK:
                $this->type = Schema::TYPE_UBIGPK;
                break;
        }
        $this->isUnsigned = true;
        return $this;
    }

    /**
     * @param string $after
     * @return $this
     * @since 1.0.0
     */
    public function after($after)
    {
        $this->after = $after;
        return $this;
    }

    /**
     * @return $this
     * @since 1.0.0
     */
    public function first()
    {
        $this->isFirst = true;
        return $this;
    }

    /**
     * @param string $default
     * @return $this
     * @since 1.0.0
     */
    public function defaultExpression($default)
    {
        $this->default = new Expression($default);
        return $this;
    }

    /**
     * @param string $sql
     * @return $this
     * @since 1.0.0
     */
    public function append($sql)
    {
        $this->append = $sql;
        return $this;
    }

    /**
     * @return string
     * @since 1.0.0
     */
    public function __toString()
    {
        switch ($this->getTypeCategory()) {
            case self::CATEGORY_PK:
                $format = '{type}{check}{comment}{append}';
                break;
            default:
                $format = '{type}{length}{notnull}{unique}{default}{check}{comment}{append}';
        }
        return $this->buildCompleteString($format);
    }

    /**
     * @return string
     * @since 1.0.0
     */
    protected function buildLengthString()
    {
        if ($this->length === null || $this->length === []) {
            return '';
        }
        if (is_array($this->length)) {
            $this->length = implode(',', $this->length);
        }
        return "({$this->length})";
    }

    /**
     * @return string
     * @since 1.0.0
     */
    protected function buildNotNullString()
    {
        if ($this->isNotNull === true) {
            return ' NOT NULL';
        } elseif ($this->isNotNull === false) {
            return ' NULL';
        } else {
            return '';
        }
    }

    /**
     * @return string
     * @since 1.0.0
     */
    protected function buildUniqueString()
    {
        return $this->isUnique ? ' UNIQUE' : '';
    }

    /**
     * @return string
     * @since 1.0.0
     */
    protected function buildDefaultString()
    {
        if ($this->default === null) {
            return $this->isNotNull === false ? ' DEFAULT NULL' : '';
        }

        $string = ' DEFAULT ';
        switch (gettype($this->default)) {
            case 'integer':
                $string .= (string) $this->default;
                break;
            case 'double':
                // ensure type cast always has . as decimal separator in all locales
                $string .= str_replace(',', '.', (string) $this->default);
                break;
            case 'boolean':
                $string .= $this->default ? 'TRUE' : 'FALSE';
                break;
            case 'object':
                $string .= (string) $this->default;
                break;
            default:
                $string .= "'{$this->default}'";
        }

        return $string;
    }

    /**
     * @return string
     * @since 1.0.0
     */
    protected function buildCheckString()
    {
        return $this->check !== null ? " CHECK ({$this->check})" : '';
    }

    /**
     * @return string
     * @since 1.0.0
     */
    protected function buildUnsignedString()
    {
        return '';
    }

    /**
     * @return string
     * @since 1.0.0
     */
    protected function buildAfterString()
    {
        return '';
    }

    /**
     * @return string
     * @since 1.0.0
     */
    protected function buildFirstString()
    {
        return '';
    }

    /**
     * @return string
     * @since 1.0.0
     */
    protected function buildAppendString()
    {
        return $this->append !== null ? ' ' . $this->append : '';
    }

    /**
     * @return string
     * @since 1.0.0
     */
    protected function getTypeCategory()
    {
        return isset($this->categoryMap[$this->type]) ? $this->categoryMap[$this->type] : null;
    }

    /**
     * @return string
     * @since 1.0.0
     */
    protected function buildCommentString()
    {
        return '';
    }

    /**
     * @param string $format
     * @return string
     * @since 1.0.0
     */
    protected function buildCompleteString($format)
    {
        $placeholderValues = [
            '{type}' => $this->type,
            '{length}' => $this->buildLengthString(),
            '{unsigned}' => $this->buildUnsignedString(),
            '{notnull}' => $this->buildNotNullString(),
            '{unique}' => $this->buildUniqueString(),
            '{default}' => $this->buildDefaultString(),
            '{check}' => $this->buildCheckString(),
            '{comment}' => $this->buildCommentString(),
            '{pos}' => $this->isFirst ? $this->buildFirstString() : $this->buildAfterString(),
            '{append}' => $this->buildAppendString(),
        ];
        return strtr($format, $placeholderValues);
    }
}
