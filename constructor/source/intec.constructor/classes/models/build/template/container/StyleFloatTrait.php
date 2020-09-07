<?php
namespace intec\constructor\models\build\template\container;

use intec\core\helpers\ArrayHelper;

trait StyleFloatTrait
{
    /**
     * Возвращает свойство float для css.
     * @return string|null
     */
    public function getStyleFloat()
    {
        $value = $this->getProperty('float');

        if (!ArrayHelper::isIn($value, static::getStyleFloatsValues()))
            $value = null;

        return $value;
    }

    /**
     * Устанавливает свойство float для css.
     * @param int|float|bool|null $value
     * @return bool
     */
    public function setStyleFloat($value = false)
    {
        return $this->setProperty('float', $value);
    }
}