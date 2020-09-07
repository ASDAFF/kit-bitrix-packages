<?php
namespace intec\constructor\models\build\template\container;

use intec\core\helpers\ArrayHelper;

trait StyleBorderWidthTrait
{
    /**
     * Общий обработчик получения свойства border-width css.
     * @param bool $asArray
     * @param string|null $side
     * @return string|null
     */
    protected function getStyleBorderWidthHandler($asArray = false, $side = null)
    {
        return $this->getPropertyMeasured(
            (!empty($side) ? 'border.' . $side . '.width' : 'border.width'),
            $asArray
        );
    }

    /**
     * Общий обработчик установки свойства border-width css.
     * @param int|float|bool|null $value
     * @param string|bool|null $measure
     * @param string|null $side
     * @return string|null
     */
    protected function setStyleBorderWidthHandler($value = false, $measure = false, $side = null)
    {
        return $this->setPropertyMeasured(
            (!empty($side) ? 'border.' . $side . '.width' : 'border.width'),
            $value,
            $measure
        );
    }

    /**
     * Возвращает свойство border-width для css.
     * @param bool $asArray
     * @return string|null
     */
    public function getStyleBorderWidth($asArray = false)
    {
        return $this->getStyleBorderWidthHandler($asArray);
    }

    /**
     * Устанавливает свойство border-width для css.
     * @param int|float|bool|null $value
     * @param string|bool|null $measure
     * @return bool
     */
    public function setStyleBorderWidth($value = false, $measure = false)
    {
        return $this->setStyleBorderWidthHandler($value, $measure);
    }

    /**
     * Возвращает свойство border-top-width для css.
     * @param bool $asArray
     * @return string|null
     */
    public function getStyleBorderTopWidth($asArray = false)
    {
        return $this->getStyleBorderWidthHandler($asArray, 'top');
    }

    /**
     * Устанавливает свойство border-top-width для css.
     * @param int|float|bool|null $value
     * @param string|bool|null $measure
     * @return bool
     */
    public function setStyleBorderTopWidth($value = false, $measure = false)
    {
        return $this->setStyleBorderWidthHandler($value, $measure, 'top');
    }

    /**
     * Возвращает свойство border-right-width для css.
     * @param bool $asArray
     * @return string|null
     */
    public function getStyleBorderRightWidth($asArray = false)
    {
        return $this->getStyleBorderWidthHandler($asArray, 'right');
    }

    /**
     * Устанавливает свойство border-right-width для css.
     * @param int|float|bool|null $value
     * @param string|bool|null $measure
     * @return bool
     */
    public function setStyleBorderRightWidth($value = false, $measure = false)
    {
        return $this->setStyleBorderWidthHandler($value, $measure, 'right');
    }

    /**
     * Возвращает свойство border-bottom-width для css.
     * @param bool $asArray
     * @return string|null
     */
    public function getStyleBorderBottomWidth($asArray = false)
    {
        return $this->getStyleBorderWidthHandler($asArray, 'bottom');
    }

    /**
     * Устанавливает свойство border-bottom-width для css.
     * @param int|float|bool|null $value
     * @param string|bool|null $measure
     * @return bool
     */
    public function setStyleBorderBottomWidth($value = false, $measure = false)
    {
        return $this->setStyleBorderWidthHandler($value, $measure, 'bottom');
    }

    /**
     * Возвращает свойство border-left-width для css.
     * @param bool $asArray
     * @return string|null
     */
    public function getStyleBorderLeftWidth($asArray = false)
    {
        return $this->getStyleBorderWidthHandler($asArray, 'left');
    }

    /**
     * Устанавливает свойство border-left-width для css.
     * @param int|float|bool|null $value
     * @param string|bool|null $measure
     * @return bool
     */
    public function setStyleBorderLeftWidth($value = false, $measure = false)
    {
        return $this->setStyleBorderWidthHandler($value, $measure, 'left');
    }
}
