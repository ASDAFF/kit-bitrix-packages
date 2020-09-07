<?php
namespace intec\constructor\models\build\template\container;

use intec\core\di\Container;

trait StylePositionTrait
{
    /**
     * Возвращает свойство position для css.
     * @return string
     */
    public function getStylePosition()
    {
        $parent = $this->getParent();

        if ($this->type == static::TYPE_ABSOLUTE) {
            $position = 'relative';
        } else {
            $position = null;
        }

        if ($parent instanceof Container)
            if ($parent->type == static::TYPE_ABSOLUTE)
                $position = 'absolute';

        return $position;
    }
}