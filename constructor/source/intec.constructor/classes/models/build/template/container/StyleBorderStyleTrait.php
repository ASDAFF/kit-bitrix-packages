<?php
namespace intec\constructor\models\build\template\container;

use intec\core\helpers\ArrayHelper;

trait StyleBorderStyleTrait
{
    /**
     * Общий обработчик получения свойства border-style css.
     * @param string|null $side
     * @return string|null
     */
    protected function getStyleBorderStyleHandler($side = null)
    {
        $property = !empty($side) ? 'border.' . $side . '.style.value' : 'border.style';
        $values = $this->getStyleBorderStylesValues();
        $value = $this->getProperty($property);

        if (!empty($value) && ArrayHelper::isIn($value, $values))
            return $value;

        return null;
    }

    /**
     * Общий обработчик установки свойства border-style css.
     * @param string|null $value
     * @param string|null $side
     * @return string|null
     */
    protected function setStyleBorderStyleHandler($value = null, $side = null)
    {
        $property = !empty($side) ? 'border.' . $side . '.style.value' : 'border.style';
        $values = $this->getStyleBorderStylesValues();

        if ($value !== null)
            $value = ArrayHelper::fromRange($values, $value);

        return $this->setProperty($property, $value);
    }

    /**
     * Возвращает свойство border-style для css.
     * @return string|null
     */
    public function getStyleBorderStyle()
    {
        return $this->getStyleBorderStyleHandler();
    }

    /**
     * Устанавливает свойство border-style для css.
     * @param string|null $value
     * @return bool
     */
    public function setStyleBorderStyle($value)
    {
        return $this->setStyleBorderStyleHandler($value);
    }

    /**
     * Возвращает свойство border-top-style для css.
     * @return string|null
     */
    public function getStyleBorderTopStyle()
    {
        return $this->getStyleBorderStyleHandler('top');
    }

    /**
     * Устанавливает свойство border-top-style для css.
     * @param string|null $value
     * @return bool
     */
    public function setStyleBorderTopStyle($value)
    {
        return $this->setStyleBorderStyleHandler($value, 'top');
    }

    /**
     * Возвращает свойство border-right-style для css.
     * @return string|null
     */
    public function getStyleBorderRightStyle()
    {
        return $this->getStyleBorderStyleHandler('right');
    }

    /**
     * Устанавливает свойство border-right-style для css.
     * @param string|null $value
     * @return bool
     */
    public function setStyleBorderRightStyle($value)
    {
        return $this->setStyleBorderStyleHandler($value, 'right');
    }

    /**
     * Возвращает свойство border-bottom-style для css.
     * @return string|null
     */
    public function getStyleBorderBottomStyle()
    {
        return $this->getStyleBorderStyleHandler('bottom');
    }

    /**
     * Устанавливает свойство border-bottom-style для css.
     * @param string|null $value
     * @return bool
     */
    public function setStyleBorderBottomStyle($value)
    {
        return $this->setStyleBorderStyleHandler($value, 'bottom');
    }

    /**
     * Возвращает свойство border-left-style для css.
     * @return string|null
     */
    public function getStyleBorderLeftStyle()
    {
        return $this->getStyleBorderStyleHandler('left');
    }

    /**
     * Устанавливает свойство border-left-style для css.
     * @param string|null $value
     * @return bool
     */
    public function setStyleBorderLeftStyle($value)
    {
        return $this->setStyleBorderStyleHandler($value, 'left');
    }
}