<?php
namespace intec\core\base\condition;

use intec\core\base\BaseObject;
use intec\core\base\Condition;

/**
 * Класс, представляющий модификатор результата условий.
 * Class ConditionResultModifier
 * @package intec\core\base\condition
 * @author apocalypsisdimon@gmail.com
 */
abstract class ResultModifier extends BaseObject
{
    /**
     * Возвращает данные для условия.
     * @param Condition $condition Условие.
     * @param DataProviderResult $data Значение с провайдера данных.
     * @param boolean $result Результат выполнения.
     * @return boolean
     */
    public abstract function modify($condition, $data, $result);
}