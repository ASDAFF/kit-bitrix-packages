<?php
namespace intec\constructor\models\build\template\container;

use intec\core\helpers\ArrayHelper;
use intec\core\helpers\Type;

trait StyleOverflowTrait
{
    /**
     * Обработчик для получения свойства overflow для css.
     * @param string|null $coordinate
     * @return string|null
     */
    protected function getStyleOverflowHandler($coordinate = null)
    {
        $value = $this->getProperty(
            $coordinate !== null ?
            'overflow.'.$coordinate.'.value' :
            'overflow.value'
        );

        if (!ArrayHelper::isIn($value, static::getStyleOverflowsValues()))
            $value = null;

        return $value;
    }

    /**
     * Обработчик для установки свойства overflow для css.
     * @param string|null $coordinate
     * @param int|float|bool|null $value
     * @return bool
     */
    protected function setStyleOverflowHandler($value = false, $coordinate = null)
    {
        if ($value === false)
            return false;

        if ($value !== null)
            $value = ArrayHelper::fromRange(static::getStyleOverflowsValues(), $value);

        return $this->setProperty(
            ($coordinate !== null ?
            'overflow.'.$coordinate.'.value' :
            'overflow.value'),
            $value
        );
    }

    /**
     * Возвращает свойство overflow для css.
     * @return string|null
     */
    public function getStyleOverflow()
    {
        return $this->getStyleOverflowHandler();
    }

    /**
     * Устанавливает свойство overflow для css.
     * @param int|float|bool|null $value
     * @return bool
     */
    public function setStyleOverflow($value = false)
    {
        return $this->setStyleOverflowHandler($value);
    }

    /**
     * Возвращает свойство overflow-x для css.
     * @return string|null
     */
    public function getStyleOverflowX()
    {
        return $this->getStyleOverflowHandler('x');
    }

    /**
     * Устанавливает свойство overflow-x для css.
     * @param int|float|bool|null $value
     * @return bool
     */
    public function setStyleOverflowX($value = false)
    {
        return $this->setStyleOverflowHandler($value, 'x');
    }

    /**
     * Возвращает свойство overflow-y для css.
     * @return string|null
     */
    public function getStyleOverflowY()
    {
        return $this->getStyleOverflowHandler('y');
    }

    /**
     * Устанавливает свойство overflow-y для css.
     * @param int|float|bool|null $value
     * @return bool
     */
    public function setStyleOverflowY($value = false)
    {
        return $this->setStyleOverflowHandler($value, 'y');
    }
}