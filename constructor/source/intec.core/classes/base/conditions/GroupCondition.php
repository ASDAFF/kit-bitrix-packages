<?php
namespace intec\core\base\conditions;

use intec\core\base\Condition;
use intec\core\base\condition\ResultModifier;
use intec\core\base\Conditions;
use intec\core\helpers\ArrayHelper;
use intec\core\helpers\Type;

/**
 * Класс, представляющий группу условий.
 * Class GroupCondition
 * @property string $operator Оператор.
 * @property boolean $result Результат.
 * @property Conditions|Condition[] $conditions Условия.
 * @package intec\core\base\condition
 * @author apocalypsisdimon@gmail.com
 */
class GroupCondition extends Condition
{
    /**
     * Логический оператор: и.
     */
    const OPERATOR_AND = 'and';
    /**
     * Логический оператор: или.
     */
    const OPERATOR_OR = 'or';

    /**
     * Возвращает список логических операторов.
     * @return array
     */
    public static function getOperators()
    {
        return [
            self::OPERATOR_AND,
            self::OPERATOR_OR
        ];
    }

    /**
     * Логический оператор.
     * @var string
     */
    protected $_operator = self::OPERATOR_AND;
    /**
     * Результат.
     * @var boolean
     */
    protected $_result = true;
    /**
     * Коллекция условий.
     * @var Conditions
     */
    protected $_conditions;

    /**
     * Устанавливает логический оператор.
     * @param string $value Значение.
     * @return static
     */
    public function setOperator($value)
    {
        $this->_operator = ArrayHelper::fromRange(self::getOperators(), $value);

        return $this;
    }

    /**
     * Комбинирует группы условий, превращая их в одноменрный массив с группами условий.
     * @param static $group
     * @return array
     */
    public static function getCombinationsFor($group)
    {
        $result = [];

        if (!($group instanceof static))
            return $result;

        $conditions = $group->getConditions();

        /** Если оператор: И */
        if ($group->operator === self::OPERATOR_AND) {
            /** Идем по условиям */
            foreach ($conditions as $condition) {
                /** Если условие - группа */
                if ($condition instanceof static) {
                    /** Формируем новый результат */
                    $collection = [];
                    /** Комбинации той группы */
                    $combinations = self::getCombinationsFor($condition);

                    /** Если комбинации есть */
                    if (!empty($combinations)) {
                        /** Добавляем заготовку для пока что единственного условия (если ее нет) */
                        if (empty($result))
                            $result[] = [];

                        /** Идем по текущим собранным результатам */
                        foreach ($result as $combination1)
                            /** Идем по новым результатам */
                            foreach ($combinations as $combination2)
                                /** Генерируем результат из предыдущего и нового результата, тем самым умножая их на количество новых результатов */
                                $collection[] = ArrayHelper::merge($combination1, $combination2);

                        /** Присваеваем старому результату новый */
                        $result = $collection;
                    }
                } else {
                    /** Добавляем заготовку для пока что единственного условия (если ее нет) */
                    if (empty($result))
                        $result[] = [];

                    /** Добавляем к каждому собранному результату по данному условию */
                    foreach ($result as $key => $part)
                        $result[$key][] = $condition;
                }
            }
        } else { /** Если оператор: ИЛИ */
            /** Идем по условиям */
            foreach ($conditions as $condition) {
                /** Если условие - группа */
                if ($condition instanceof static) {
                    $groupResult = self::getCombinationsFor($condition);

                    /** Добавляем каждый результат группы в текущий */
                    foreach ($groupResult as $groupPart)
                        $result[] = $groupPart;
                } else {
                    /** Создаем единственный результат с условием и добавляем его в текущий */
                    $result[] = [
                        $condition
                    ];
                }
            }
        }

        return $result;
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
     * Устанавливает результат.
     * @param boolean $value
     * @return static
     */
    public function setResult($value)
    {
        $this->_result = Type::toBoolean($value);

        return $this;
    }

    /**
     * Возвращает результат.
     * @return boolean
     */
    public function getResult()
    {
        return $this->_result;
    }

    /**
     * Устанавливает новый список условий.
     * @param Condition[] $conditions
     * @return static
     */
    public function setConditions($conditions)
    {
        if ($this->_conditions === null)
            $this->_conditions = new Conditions();

        $this->_conditions->removeAll();
        $this->_conditions->setRange($conditions);

        return $this;
    }

    /**
     * Возвращает коллекцию условий.
     * @return Conditions
     */
    public function getConditions()
    {
        return $this->_conditions;
    }

    /**
     * @inheritdoc
     */
    public function __construct(array $config = [])
    {
        if (isset($config['conditions']) && Type::isArray($config['conditions'])) {
            $conditions = [];

            foreach ($config['conditions'] as $key => $condition) {
                if (!($condition instanceof Condition))
                    $condition = Condition::create($condition);

                if (!empty($condition))
                    $conditions[$key] = $condition;
            }

            $config['conditions'] = $conditions;

            unset($conditions);
        }

        parent::__construct($config);
    }

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        if ($this->_conditions === null)
            $this->_conditions = new Conditions();
    }

    /**
     * @inheritdoc
     */
    public function getIsFulfilled($provider, $modifier = null)
    {
        parent::getIsFulfilled($provider, $modifier);

        $result = $this->_result;

        if (!$this->_conditions->isEmpty()) {
            $result = true;

            if ($this->_operator === self::OPERATOR_AND) {
                /** Все условия (выполнены/не выполнены) */
                /** @var Condition $condition */
                foreach ($this->_conditions as $condition)
                    $result = $result && ($this->_result ? $condition->getIsFulfilled($provider, $modifier) : !$condition->getIsFulfilled($provider, $modifier));
            } else if ($this->_operator === self::OPERATOR_OR) {
                /** @var Condition $condition */
                foreach ($this->_conditions as $condition) {
                    $result = $condition->getIsFulfilled($provider, $modifier);

                    if ($this->_result) {
                        /** Одно из условий выполнено */
                        if ($result)
                            break;
                    } else {
                        /** Одно из условий не выполнено */
                        if (!$result) {
                            $result = true;
                            break;
                        }

                        $result = false;
                    }
                }
            }
        }

        /** @var ResultModifier $modifier */
        if (!empty($modifier))
            $result = $modifier->modify($this, null, $result);

        return $result;
    }

    /**
     * Возвращает все возможные комбинации условий.
     * @return array
     */
    public function getCombinations()
    {
        return self::getCombinationsFor($this);
    }

    /**
     * @inheritdoc
     */
    public function export()
    {
        $result = parent::export();
        $result['operator'] = $this->_operator;
        $result['result'] = $this->_result;
        $result['conditions'] = [];

        /** @var Condition $condition */
        foreach ($this->_conditions as $key => $condition)
            $result['conditions'][$key] = $condition->export();

        return $result;
    }
}