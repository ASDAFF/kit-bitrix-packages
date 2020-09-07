<?php
namespace intec\constructor\structure\block\element;

use intec\core\base\Event;
use intec\constructor\structure\block\Resolution;

/**
 * Class AttributeSetEvent
 * @package intec\constructor\structure\block\element
 */
class AttributeEvent extends Event
{
    /**
     * Устанавливаемый атрибут.
     * @var string
     */
    public $attribute;

    /**
     * Разрешение.
     * @var Resolution
     */
    public $resolution;

    /**
     * Значение.
     * @var mixed
     */
    public $value;
}