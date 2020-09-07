<?php
namespace intec\constructor\structure\block\element;

use intec\core\base\Event;
use intec\constructor\structure\block\Resolution;

/**
 * Class AttributeSetEvent
 * @package intec\constructor\structure\block\element
 */
class AttributeCorrectEvent extends AttributeEvent
{
    /**
     * Операция: Установка значения
     */
    const OPERATION_SET = 'set';
    /**
     * Операция: Получение значения
     */
    const OPERATION_GET = 'get';

    /**
     * Операция, производимая над атрибутом.
     * @var string
     */
    public $operation;
}