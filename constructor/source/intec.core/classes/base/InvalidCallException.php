<?php
namespace intec\core\base;

/**
 * Исключение при неверном вызове метода или функции.
 * Class InvalidCallException
 * @package intec\core\base
 * @since 1.0.0
 */
class InvalidCallException extends \BadMethodCallException
{
    /**
     * Возвращает наименование исключения.
     * @return string
     * @since 1.0.0
     */
    public function getName()
    {
        return 'Invalid Call';
    }
}
