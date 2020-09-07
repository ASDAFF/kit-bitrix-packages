<?php
namespace intec\constructor\models\build\template\container;

trait StyleSizeTrait
{
    /**
     * Возвращает свойство width для css.
     * @param bool $asArray
     * @return string|null
     */
    public function getStyleWidth($asArray = false)
    {
        return $this->getPropertyMeasured('width', $asArray);
    }

    /**
     * Устанавливает свойство width для css.
     * @param int|float|bool|null $value
     * @param string|bool|null $measure
     * @return bool
     */
    public function setStyleWidth($value = false, $measure = false)
    {
        return $this->setPropertyMeasured('width', $value, $measure);
    }

    /**
     * Возвращает свойство height для css.
     * @param bool $asArray
     * @return string|null
     */
    public function getStyleHeight($asArray = false)
    {
        return $this->getPropertyMeasured('height', $asArray);
    }

    /**
     * Устанавливает свойство height для css.
     * @param int|float|bool|null $value
     * @param string|bool|null $measure
     * @return bool
     */
    public function setStyleHeight($value = false, $measure = false)
    {
        return $this->setPropertyMeasured('height', $value, $measure);
    }

    /**
     * Возвращает свойство min-width для css.
     * @param bool $asArray
     * @return string|null
     */
    public function getStyleMinWidth($asArray = false)
    {
        return $this->getPropertyMeasured('width.min', $asArray);
    }

    /**
     * Устанавливает свойство min-width для css.
     * @param int|float|bool|null $value
     * @param string|bool|null $measure
     * @return bool
     */
    public function setStyleMinWidth($value = false, $measure = false)
    {
        return $this->setPropertyMeasured('width.min', $value, $measure);
    }

    /**
     * Возвращает свойство min-height для css.
     * @param bool $asArray
     * @return string|null
     */
    public function getStyleMinHeight($asArray = false)
    {
        return $this->getPropertyMeasured('height.min', $asArray);
    }

    /**
     * Устанавливает свойство min-height для css.
     * @param int|float|bool|null $value
     * @param string|bool|null $measure
     * @return bool
     */
    public function setStyleMinHeight($value = false, $measure = false)
    {
        return $this->setPropertyMeasured('height.min', $value, $measure);
    }

    /**
     * Возвращает свойство max-width для css.
     * @param bool $asArray
     * @return string|null
     */
    public function getStyleMaxWidth($asArray = false)
    {
        return $this->getPropertyMeasured('width.max', $asArray);
    }

    /**
     * Устанавливает свойство max-width для css.
     * @param int|float|bool|null $value
     * @param string|bool|null $measure
     * @return bool
     */
    public function setStyleMaxWidth($value = false, $measure = false)
    {
        return $this->setPropertyMeasured('width.max', $value, $measure);
    }

    /**
     * Возвращает свойство max-height для css.
     * @param bool $asArray
     * @return string|null
     */
    public function getStyleMaxHeight($asArray = false)
    {
        return $this->getPropertyMeasured('height.max', $asArray);
    }

    /**
     * Устанавливает свойство max-height для css.
     * @param int|float|bool|null $value
     * @param string|bool|null $measure
     * @return bool
     */
    public function setStyleMaxHeight($value = false, $measure = false)
    {
        return $this->setPropertyMeasured('height.max', $value, $measure);
    }
}