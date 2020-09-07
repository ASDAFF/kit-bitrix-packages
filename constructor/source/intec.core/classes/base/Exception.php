<?php
namespace intec\core\base;

/**
 * Базовый класс исключения.
 * Class Exception
 * @package intec\core\base
 * @since 1.0.0
 */
class Exception extends \Exception
{
    /**
     * Возвращает наименование исключения.
     * @return string
     * @since 1.0.0
     */
    public function getName()
    {
        return 'Exception';
    }
}
