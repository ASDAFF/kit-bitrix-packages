<?php
namespace intec\core\base;

/**
 * Исключение при неаерном параметре.
 * Class InvalidParamException
 * @package intec\core\base
 * @since 1.0.0
 */
class InvalidParamException extends \BadMethodCallException
{
    /**
     * Возвращает наименование исключения.
     * @return string
     */
    public function getName()
    {
        return 'Invalid Parameter';
    }
}
