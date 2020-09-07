<?php
namespace intec\constructor\models\build\template\container;

trait StyleSideTrait
{
    /**
     * Возвращает свойство top для css.
     * @param bool $asArray
     * @return string|null
     */
    public function getStyleTop($asArray = false)
    {
        return $this->getPropertyMeasured('top', $asArray);
    }

    /**
     * Устанавливает свойство top для css.
     * @param int|float|bool|null $value
     * @param string|bool|null $measure
     * @return bool
     */
    public function setStyleTop($value = false, $measure = false)
    {
        return $this->setPropertyMeasured('top', $value, $measure);
    }

    /**
     * Возвращает свойство right для css.
     * @param bool $asArray
     * @return string|null
     */
    public function getStyleRight($asArray = false)
    {
        return $this->getPropertyMeasured('right', $asArray);
    }

    /**
     * Устанавливает свойство right для css.
     * @param int|float|bool|null $value
     * @param string|bool|null $measure
     * @return bool
     */
    public function setStyleRight($value = false, $measure = false)
    {
        return $this->setPropertyMeasured('right', $value, $measure);
    }

    /**
     * Возвращает свойство bottom для css.
     * @param bool $asArray
     * @return string|null
     */
    public function getStyleBottom($asArray = false)
    {
        return $this->getPropertyMeasured('bottom', $asArray);
    }

    /**
     * Устанавливает свойство bottom для css.
     * @param int|float|bool|null $value
     * @param string|bool|null $measure
     * @return bool
     */
    public function setStyleBottom($value = false, $measure = false)
    {
        return $this->setPropertyMeasured('bottom', $value, $measure);
    }

    /**
     * Возвращает свойство left для css.
     * @param bool $asArray
     * @return string|null
     */
    public function getStyleLeft($asArray = false)
    {
        return $this->getPropertyMeasured('left', $asArray);
    }

    /**
     * Устанавливает свойство left для css.
     * @param int|float|bool|null $value
     * @param string|bool|null $measure
     * @return bool
     */
    public function setStyleLeft($value = false, $measure = false)
    {
        return $this->setPropertyMeasured('left', $value, $measure);
    }
}