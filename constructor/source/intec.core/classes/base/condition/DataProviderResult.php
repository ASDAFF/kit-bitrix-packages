<?php
namespace intec\core\base\condition;

use intec\core\base\BaseObject;
use intec\core\helpers\Type;

/**
 * Класс, представляющий результат провайдера данных условия.
 * Class DataProviderResult
 * @property mixed $value Значение. Только для чтения.
 * @property boolean $isValid Значение верное.
 * @package intec\core\base\condition
 * @author apocalypsisdimon@gmail.com
 */
class DataProviderResult extends BaseObject
{
    /**
     * Значение.
     * @var mixed $value
     */
    protected $_value;
    /**
     * Значение анулировано.
     * @var boolean
     */
    protected $_isValid;

    /**
     * Значение.
     * @return mixed
     */
    public function getValue()
    {
        return $this->_value;
    }

    /**
     * Устанавливает значение верности.
     * @param boolean $value Значение верное.
     * @return $this
     */
    public function setIsValid($value)
    {
        $this->_isValid = Type::toBoolean($value);

        return $this;
    }

    /**
     * Значение верное.
     * @return boolean
     */
    public function getIsValid()
    {
        return $this->_isValid;
    }

    /**
     * @inheritdoc
     * @param mixed $value Значение.
     * @param boolean $isValid Значение верное.
     */
    public function __construct($value, $isValid = true, array $config = [])
    {
        $this->_value = $value;
        $this->_isValid = Type::toBoolean($isValid);

        parent::__construct($config);
    }
}