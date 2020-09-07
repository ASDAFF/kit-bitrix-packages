<?php
namespace intec\constructor\models\build\template\container;

use intec\core\helpers\ArrayHelper;

trait StyleBorderColorTrait
{
    /**
     * Общий обработчик получения свойства border-color css.
     * @param string|null $side
     * @return string|null
     */
    protected function getStyleBorderColorHandler($side = null)
    {
        $value = $this->getProperty(!empty($side) ? 'border.' . $side . '.color.value' : 'border.color');
        return empty($value) ? null : $value;
    }

    /**
     * Общий обработчик установки свойства border-color css.
     * @param string|bool|null $value
     * @param string|null $side
     * @return bool
     */
    protected function setStyleBorderColorHandler($value = false, $side = null)
    {
        if ($value === false)
            return false;

        return $this->setProperty(
            (!empty($side) ? 'border.' . $side . '.color.value' : 'border.color'),
            $value
        );
    }

    /**
     * Возвращает свойство border-color для css.
     * @return string|null
     */
    public function getStyleBorderColor()
    {
        return $this->getStyleBorderColorHandler();
    }

    /**
     * Устанавливает свойство border-color для css.
     * @param string|bool|null $value
     * @return bool
     */
    public function setStyleBorderColor($value = false)
    {
        return $this->setStyleBorderColorHandler($value);
    }

    /**
     * Возвращает свойство border-top-color для css.
     * @return string|null
     */
    public function getStyleBorderTopColor()
    {
        return $this->getStyleBorderColorHandler('top');
    }

    /**
     * Устанавливает свойство border-top-color для css.
     * @param string|bool|null $value
     * @return bool
     */
    public function setStyleBorderTopColor($value = false)
    {
        return $this->setStyleBorderColorHandler($value, 'top');
    }

    /**
     * Возвращает свойство border-right-color для css.
     * @return string|null
     */
    public function getStyleBorderRightColor()
    {
        return $this->getStyleBorderColorHandler('right');
    }

    /**
     * Устанавливает свойство border-right-color для css.
     * @param string|bool|null $value
     * @return bool
     */
    public function setStyleBorderRightColor($value = false)
    {
        return $this->setStyleBorderColorHandler($value, 'right');
    }

    /**
     * Возвращает свойство border-bottom-color для css.
     * @return string|null
     */
    public function getStyleBorderBottomColor()
    {
        return $this->getStyleBorderColorHandler('bottom');
    }

    /**
     * Устанавливает свойство border-bottom-color для css.
     * @param string|bool|null $value
     * @return bool
     */
    public function setStyleBorderBottomColor($value = false)
    {
        return $this->setStyleBorderColorHandler($value, 'bottom');
    }

    /**
     * Возвращает свойство border-left-color для css.
     * @return string|null
     */
    public function getStyleBorderLeftColor()
    {
        return $this->getStyleBorderColorHandler('left');
    }

    /**
     * Устанавливает свойство border-left-color для css.
     * @param string|bool|null $value
     * @return bool
     */
    public function setStyleBorderLeftColor($value = false)
    {
        return $this->setStyleBorderColorHandler($value, 'left');
    }
}