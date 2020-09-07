<?php
namespace intec\constructor\models\build\template\container;

use intec\core\helpers\Type;

trait StyleOpacityTrait
{
    /**
     * Возвращает свойство opacity для css.
     * @return string|null
     */
    public function getStyleOpacity()
    {
        $value = $this->getProperty('opacity');
        return 1 - $value;
    }

    /**
     * Устанавливает свойство opacity для css.
     * @param int|float|bool|null $value
     * @return bool
     */
    public function setStyleOpacity($value = false)
    {
        if ($value === false)
            return false;

        if (Type::isNumeric($value)) {
            if ($value < 0) $value = 0;
            if ($value > 1) $value = 1;
        } else {
            $value = null;
        }

        return $this->setProperty('opacity', $value);
    }
}