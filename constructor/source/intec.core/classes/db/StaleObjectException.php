<?php
namespace intec\core\db;

/**
 * Class StaleObjectException
 * @package intec\core\db
 * @since 1.0.0
 */
class StaleObjectException extends Exception
{
    /**
     * Возвращает наименование исключения.
     * @return string
     * @since 1.0.0
     */
    public function getName()
    {
        return 'Stale Object Exception';
    }
}
