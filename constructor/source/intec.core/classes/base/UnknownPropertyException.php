<?php
namespace intec\core\base;

/**
 * Возникает, когда какое-либо свойство не найдено.
 * Class UnknownPropertyException
 * @package intec\core\base
 */
class UnknownPropertyException extends Exception
{
    /**
     * Возвращает наименование исключения.
     * @return string
     */
    public function getName()
    {
        return 'Unknown Property';
    }
}
