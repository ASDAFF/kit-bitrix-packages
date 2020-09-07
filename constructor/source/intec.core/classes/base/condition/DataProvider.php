<?php
namespace intec\core\base\condition;

use intec\core\base\BaseObject;
use intec\core\base\Condition;

/**
 * Класс, представляющий провайдер данных для условий.
 * Class ConditionDataProvider
 * @package intec\core\base\condition
 * @author apocalypsisdimon@gmail.com
 */
abstract class DataProvider extends BaseObject
{
    /**
     * Возвращает данные для условия.
     * @param Condition $condition
     * @return DataProviderResult|null
     */
    public abstract function receive($condition);
}