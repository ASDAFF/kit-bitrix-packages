<?php
namespace intec\core\base;

/**
 * Возникает, когда какой-либо класс не найден.
 * Class UnknownClassException
 * @package intec\core\base
 */
class UnknownClassException extends Exception
{
    /**
     * Возвращает наименование исключения.
     * @return string
     */
    public function getName()
    {
        return 'Unknown Class';
    }
}
