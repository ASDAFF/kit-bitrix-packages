<?php
namespace intec\core\db;

use intec\core\base\Event;

/**
 * Class AfterSaveEvent
 * @package intec\core\db
 * @since 1.0.0
 */
class AfterSaveEvent extends Event
{
    /**
     * @var array
     * @since 1.0.0
     */
    public $changedAttributes;
}
