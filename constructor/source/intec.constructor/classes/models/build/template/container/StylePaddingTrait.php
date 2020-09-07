<?php
namespace intec\constructor\models\build\template\container;

trait StylePaddingTrait
{
    /**
     * Возвращает свойство padding без объединения сторон для css.
     * @param bool $asArray
     * @return string|null
     */
    public function getStylePadding($asArray = false)
    {
        return $this->getPropertyMeasured('padding', $asArray);
    }

    /**
     * Устанавливает свойство padding для css.
     * @param int|float|bool|null $value
     * @param string|bool|null $measure
     * @return bool
     */
    public function setStylePadding($value = false, $measure = false)
    {
        return $this->setPropertyMeasured('padding', $value, $measure);
    }

    /**
     * Возвращает свойство padding-top для css.
     * @param bool $asArray
     * @return string|null
     */
    public function getStylePaddingTop($asArray = false)
    {
        return $this->getPropertyMeasured('padding.top', $asArray);
    }

    /**
     * Устанавливает свойство padding-top для css.
     * @param int|float|bool|null $value
     * @param string|bool|null $measure
     * @return bool
     */
    public function setStylePaddingTop($value = false, $measure = false)
    {
        return $this->setPropertyMeasured('padding.top', $value, $measure);
    }

    /**
     * Возвращает свойство padding-right для css.
     * @param bool $asArray
     * @return string|null
     */
    public function getStylePaddingRight($asArray = false)
    {
        return $this->getPropertyMeasured('padding.right', $asArray);
    }

    /**
     * Устанавливает свойство padding-right для css.
     * @param int|float|bool|null $value
     * @param string|bool|null $measure
     * @return bool
     */
    public function setStylePaddingRight($value = false, $measure = false)
    {
        return $this->setPropertyMeasured('padding.right', $value, $measure);
    }

    /**
     * Возвращает свойство padding-bottom для css.
     * @param bool $asArray
     * @return string|null
     */
    public function getStylePaddingBottom($asArray = false)
    {
        return $this->getPropertyMeasured('padding.bottom', $asArray);
    }

    /**
     * Устанавливает свойство padding-bottom для css.
     * @param int|float|bool|null $value
     * @param string|bool|null $measure
     * @return bool
     */
    public function setStylePaddingBottom($value = false, $measure = false)
    {
        return $this->setPropertyMeasured('padding.bottom', $value, $measure);
    }

    /**
     * Возвращает свойство padding-left для css.
     * @param bool $asArray
     * @return string|null
     */
    public function getStylePaddingLeft($asArray = false)
    {
        return $this->getPropertyMeasured('padding.left', $asArray);
    }

    /**
     * Устанавливает свойство padding-left для css.
     * @param int|float|bool|null $value
     * @param string|bool|null $measure
     * @return bool
     */
    public function setStylePaddingLeft($value = false, $measure = false)
    {
        return $this->setPropertyMeasured('padding.left', $value, $measure);
    }

    /**
     * Возвращает свойство padding для css.
     * @return string|null
     */
    public function getStylePaddingSummary()
    {
        $value = $this->getStylePadding();
        $sides = static::getStyleSidesValues();
        $values = [];
        $result = [];

        $values[static::STYLE_SIDE_TOP] = $this->getStylePaddingTop();
        $values[static::STYLE_SIDE_RIGHT] = $this->getStylePaddingRight();
        $values[static::STYLE_SIDE_BOTTOM] = $this->getStylePaddingBottom();
        $values[static::STYLE_SIDE_LEFT] = $this->getStylePaddingLeft();

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