<?php
namespace intec\constructor\models\build\template\container;

use intec\constructor\models\Font;
use intec\core\helpers\ArrayHelper;
use intec\core\helpers\StringHelper;
use intec\constructor\models\Build;
use intec\constructor\models\build\Template;

trait StyleTextTrait
{
    /**
     * Возвращает свойство color для css.
     * @return string|null
     */
    public function getStyleColor()
    {
        $value = $this->getProperty('text.color');
        return empty($value) ? null : $value;
    }

    /**
     * Устанавливает свойство color для css.
     * @param string|bool|null $value
     * @return bool
     */
    public function setStyleColor($value = false)
    {
        if ($value === false)
            return false;

        return $this->setProperty('text.color', $value);
    }

    /**
     * Возвращает свойство font-family для css.
     * @return string|null
     */
    public function getStyleFontFamily()
    {
        $value = $this->getProperty('text.font');

        if (empty($value))
            return null;

        $font = Font::findOne($value);

        if (!empty($font))
            $font->register();

        return $value;
    }

    /**
     * Устанавливает свойство font-family для css.
     * @param int|float|bool|null $value
     * @return bool
     */
    public function setStyleFontFamily($value = false)
    {
        return $this->setProperty('text.font', $value);
    }

    /**
     * Возвращает свойство font-size для css.
     * @param bool $asArray
     * @return string|null
     */
    public function getStyleFontSize($asArray = false)
    {
        return $this->getPropertyMeasured('text.size', $asArray, ['px', 'pt', '%', 'em']);
    }

    /**
     * Устанавливает свойство font-size для css.
     * @param int|float|bool|null $value
     * @param string|bool|null $measure
     * @return bool
     */
    public function setStyleFontSize($value = false, $measure = false)
    {
        return $this->setPropertyMeasured('text.size', $value, $measure, ['px', 'pt', '%', 'em']);
    }

    /**
     * Возвращает свойство line-height для css.
     * @param bool $asArray
     * @return string|null
     */
    public function getStyleLineHeight($asArray = false)
    {
        return $this->getPropertyMeasured('text.lineHeight', $asArray, ['px', 'pt', '%', 'em']);
    }

    /**
     * Устанавливает свойство line-height для css.
     * @param int|float|bool|null $value
     * @param string|bool|null $measure
     * @return bool
     */
    public function setStyleLineHeight($value = false, $measure = false)
    {
        return $this->setPropertyMeasured('text.lineHeight', $value, $measure, ['px', 'pt', '%', 'em']);
    }

    /**
     * Возвращает свойство letter-spacing для css.
     * @param bool $asArray
     * @return string|null
     */
    public function getStyleLetterSpacing($asArray = false)
    {
        return $this->getPropertyMeasured('text.letterSpacing', $asArray, ['px', 'pt', '%', 'em']);
    }

    /**
     * Устанавливает свойство letter-spacing для css.
     * @param int|float|bool|null $value
     * @param string|bool|null $measure
     * @return bool
     */
    public function setStyleLetterSpacing($value = false, $measure = false)
    {
        return $this->setPropertyMeasured('text.letterSpacing', $value, $measure, ['px', 'pt', '%', 'em']);
    }

    /**
     * Возвращает свойство text-transform для css.
     * @return string|null
     */
    public function getStyleTextTransform()
    {
        $uppercase = $this->getProperty('text.uppercase');
        return $uppercase ? 'uppercase' : null;
    }

    /**
     * Устанавливает свойство text-transform для css.
     * @param string|bool|null $value
     * @return bool
     */
    public function setStyleTextTransform($value = false)
    {
        if ($value === false)
            return false;

        $value = $value == 'uppercase' ? true : null;
        return $this->setProperty('text.uppercase', $value);
    }
}