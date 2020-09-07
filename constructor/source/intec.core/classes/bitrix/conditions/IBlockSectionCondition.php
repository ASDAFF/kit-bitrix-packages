<?php
namespace intec\core\bitrix\conditions;

use intec\core\base\Condition;
use intec\core\base\condition\ResultModifier;
use intec\core\helpers\ArrayHelper;
use intec\core\helpers\Type;

/**
 * Класс, представляющий условие инфоблока по разделу.
 * Class IBlockSectionCondition
 * @property integer $value Идентификатор раздела.
 * @package intec\core\bitrix\conditions
 * @author apocalypsisdimon@gmail.com
 */
class IBlockSectionCondition extends Condition
{
    /**
     * Логический оператор: равно.
     */
    const OPERATOR_EQUAL = '=';
    /**
     * Логический оператор: не равно.
     */
    const OPERATOR_NOT_EQUAL = '!';

    /**
     * Возвращает список логических операторов.
     * @return array
     */
    public static function getOperators()
    {
        return [
            self::OPERATOR_EQUAL,
            self::OPERATOR_NOT_EQUAL
        ];
    }

    /**
     * Логический оператор.
     * @var string
     */
    protected $_operator = self::OPERATOR_EQUAL;

    /**
     * Раздел.
     * @var mixed
     */
    public $value;

    /**
     * Устанавливает логический оператор.
     * @param string $value
     * @return static
     */
    public function setOperator($value)
    {
        $this->_operator = ArrayHelper::fromRange(self::getOperators(), $value);

        return $this;
    }

    /**
     * Возвращает логический оператор.
     * @return string
     */
    public function getOperator()
    {
        return $this->_operator;
    }

    /**
     * @inheritdoc
     */
    public function getIsFulfilled($provider, $modifier = null)
    {
        parent::getIsFulfilled($provider, $modifier);

        $result = false;
        $data = $provider->receive($this);

        if (!empty($data) && $data->getIsValid()) {
            $values = $data->getValue();

            if (!Type::isArray($values)) {
                switch ($this->_operator) {
                    case self::OPERATOR_EQUAL: {
                        $result = $this->value == $values;
                        break;
                    }
                    case self::OPERATOR_NOT_EQUAL: {
                        $result = $this->value != $values;
                        break;
                    }
                    default: {
                        $data->setIsValid(false);
                    }
                }
            } else {
                switch ($this->_operator) {
                    case self::OPERATOR_EQUAL: {
                        foreach ($values as $value) {
                            $result = $this->value == $value;

                            if ($result)
                                break;
                        }

                        break;
                    }
                    case self::OPERATOR_NOT_EQUAL: {
                        $result = true;

                        foreach ($values as $value) {
                            if ($this->value == $value) {
                                $result = false;
                                break;
                            }
                        }

                        break;
                    }
                    default: {
                        $data->setIsValid(false);
                    }
                }
            }
        }

        /** @var ResultModifier $modifier */
        if (!empty($modifier))
            $result = $modifier->modify($this, $data, $result);

        return $result;
    }

    /**
     * @inheritdoc
     */
    public function export()
    {
        $result = parent::export();
        $result['operator'] = $this->_operator;
        $result['value'] = $this->value;

        return $result;
    }
}