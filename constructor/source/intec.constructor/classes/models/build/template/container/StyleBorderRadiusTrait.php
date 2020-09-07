<?php
namespace intec\constructor\models\build\template\container;

trait StyleBorderRadiusTrait
{
    /**
     * Возвращает свойство border-radius без объединения сторон для css.
     * @param bool $asArray
     * @return string|null
     */
    public function getStyleBorderRadius($asArray = false)
    {
        return $this->getPropertyMeasured('border.radius', $asArray);
    }

    /**
     * Устанавливает свойство border-radius для css.
     * @param int|float|bool|null $value
     * @param string|bool|null $measure
     * @return bool
     */
    public function setStyleBorderRadius($value = false, $measure = false)
    {
        return $this->setPropertyMeasured('border.radius', $value, $measure);
    }

    /**
     * Возвращает свойство border-top-left-radius css.
     * @param bool $asArray
     * @return string|null
     */
    public function getStyleBorderTopLeftRadius($asArray = false)
    {
        return $this->getPropertyMeasured('border.top.radius', $asArray);
    }

    /**
     * Устанавливает свойство border-top-left-radius для css.
     * @param int|float|bool|null $value
     * @param string|bool|null $measure
     * @return bool
     */
    public function setStyleBorderTopLeftRadius($value = false, $measure = false)
    {
        return $this->setPropertyMeasured('border.top.radius', $value, $measure);
    }

    /**
     * Возвращает свойство border-top-right-radius css.
     * @param bool $asArray
     * @return string|null
     */
    public function getStyleBorderTopRightRadius($asArray = false)
    {
        return $this->getPropertyMeasured('border.right.radius', $asArray);
    }

    /**
     * Устанавливает свойство border-top-right-radius для css.
     * @param int|float|bool|null $value
     * @param string|bool|null $measure
     * @return bool
     */
    public function setStyleBorderTopRightRadius($value = false, $measure = false)
    {
        return $this->setPropertyMeasured('border.right.radius', $value, $measure);
    }

    /**
     * Возвращает свойство border-bottom-right-radius css.
     * @param bool $asArray
     * @return string|null
     */
    public function getStyleBorderBottomRightRadius($asArray = false)
    {
        return $this->getPropertyMeasured('border.bottom.radius', $asArray);
    }

    /**
     * Устанавливает свойство border-bottom-right-radius для css.
     * @param int|float|bool|null $value
     * @param string|bool|null $measure
     * @return bool
     */
    public function setStyleBorderBottomRightRadius($value = false, $measure = false)
    {
        return $this->setPropertyMeasured('border.bottom.radius', $value, $measure);
    }

    /**
     * Возвращает свойство border-bottom-left-radius css.
     * @param bool $asArray
     * @return string|null
     */
    public function getStyleBorderBottomLeftRadius($asArray = false)
    {
        return $this->getPropertyMeasured('border.left.radius', $asArray);
    }

    /**
     * Устанавливает свойство border-bottom-left-radius для css.
     * @param int|float|bool|null $value
     * @param string|bool|null $measure
     * @return bool
     */
    public function setStyleBorderBottomLeftRadius($value = false, $measure = false)
    {
        return $this->setPropertyMeasured('border.left.radius', $value, $measure);
    }

    /**
     * Возвращает свойство border-radius для css.
     * @return string|null
     */
    public function getStyleBorderRadiusSummary()
    {
        $value = $this->getStyleBorderRadius();
        $sides = static::getStyleSidesValues();
        $values = [];
        $result = [];

        $values[static::STYLE_SIDE_TOP] = $this->getStyleBorderTopLeftRadius();
        $values[static::STYLE_SIDE_RIGHT] = $this->getStyleBorderTopRightRadius();
        $values[static::STYLE_SIDE_BOTTOM] = $this->getStyleBorderBottomRightRadius();
        $values[static::STYLE_SIDE_LEFT] = $this->getStyleBorderBottomLeftRadius();

        foreach ($sides as $side) {
            if ($values[$side] !== null) {
                $result[] = $values[$side];
            } else if ($value !== null) {
                $result[] = $value;
            } else {
                $result[] = '0';
            }
        }

        return implode(' ', $result);
    }
}