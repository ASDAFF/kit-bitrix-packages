<?php
namespace intec\core\base;

/**
 * Событие вызываемое [[Model]].
 * Class ModelEvent
 * @package intec\core\base
 * @since 1.0.0
 */
class ModelEvent extends Event
{
    /**
     * Значение, показывающее корректность проверки конкретным событием.
     * @var bool
     */
    public $isValid = true;
}
