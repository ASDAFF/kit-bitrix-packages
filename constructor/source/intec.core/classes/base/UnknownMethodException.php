<?php
namespace intec\core\base;

/**
 * Возникает, когда какой-либо метод не найден.
 * Class UnknownMethodException
 * @package intec\core\base
 */
class UnknownMethodException extends \BadMethodCallException
{
    /**
     * Возвращает наименование исключения.
     * @return string
     */
    public function getName()
    {
        return 'Unknown Method';
    }
}
