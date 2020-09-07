<?php
namespace intec\core\base;

/**
 * Возникает когда что-либо не поддерживается.
 * Class NotSupportedException
 * @package intec\core\base
 */
class NotSupportedException extends Exception
{
    /**
     * Возвращает наименование исключения.
     * @return string
     */
    public function getName()
    {
        return 'Not Supported';
    }
}
