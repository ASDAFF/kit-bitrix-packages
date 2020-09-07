<?php
namespace intec\core\base;

/**
 * Класс, представляющий коллекцию условий.
 * Class Conditions
 * @package intec\core\base
 * @author apocalypsisdimon@gmail.com
 */
class Conditions extends Collection
{
    /**
     * @inheritdoc
     */
    protected function verify($item)
    {
        return ($item instanceof Condition);
    }
}