<?php
namespace intec\core\base;

/**
 * Исключение при неверной конфигурации.
 * Class InvalidConfigException
 * @package intec\core\base
 * @since 1.0.0
 */
class InvalidConfigException extends Exception
{
    /**
     * Возвращает наименование исключения.
     * @return string
     * @since 1.0.0
     */
    public function getName()
    {
        return 'Invalid Configuration';
    }
}
