<?php
namespace intec\constructor\models\build\template\container;

trait StyleBorderTrait
{
    /**
     * Возвращает свойство border для css.
     * @param bool $asArray
     * @return string|null
     */
    public function getStyleBorder($asArray = false)
    {
        $width = $this->getStyleBorderWidth($asArray);
        $style = $this->getStyleBorderStyle();
        $color = $this->getStyleBorderColor();

        if ($asArray) {
            return [
                'width' => $width,
                'style' => $style,
                'color' => $color
            ];
        }

        if ($width === null || $style === null || $color === null)
            return null;

        return $width.' '.$style.' '.$color;
    }

    /**
     * Возвращает свойство border-top для css.
     * @param bool $asArray
     * @return string|null
     */
    public function getStyleBorderTop($asArray = false)
    {
        $width = $this->getStyleBorderTopWidth($asArray);
        $style = $this->getStyleBorderTopStyle();
        $color = $this->getStyleBorderTopColor();

        if ($asArray) {
            return [
                'width' => $width,
                'style' => $style,
                'color' => $color
            ];
        }

        if ($width === null || $style === null || $color === null)
            return null;

        return $width.' '.$style.' '.$color;
    }

    /**
     * Возвращает свойство border-right для css.
     * @param bool $asArray
     * @return string|null
     */
    public function getStyleBorderRight($asArray = false)
    {
        $width = $this->getStyleBorderRightWidth($asArray);
        $style = $this->getStyleBorderRightStyle();
        $color = $this->getStyleBorderRightColor();

        if ($asArray) {
            return [
                'width' => $width,
                'style' => $style,
                'color' => $color
            ];
        }

        if ($width === null || $style === null || $color === null)
            return null;

        return $width.' '.$style.' '.$color;
    }

    /**
     * Возвращает свойство border-bottom для css.
     * @param bool $asArray
     * @return string|null
     */
    public function getStyleBorderBottom($asArray = false)
    {
        $width = $this->getStyleBorderBottomWidth($asArray);
        $style = $this->getStyleBorderBottomStyle();
        $color = $this->getStyleBorderBottomColor();

        if ($asArray) {
            return [
                'width' => $width,
                'style' => $style,
                'color' => $color
            ];
        }

        if ($width === null || $style === null || $color === null)
            return null;

        return $width.' '.$style.' '.$color;
    }

    /**
     * Возвращает свойство border-left для css.
     * @param bool $asArray
     * @return string|null
     */
    public function getStyleBorderLeft($asArray = false)
    {
        $width = $this->getStyleBorderLeftWidth($asArray);
        $style = $this->getStyleBorderLeftStyle();
        $color = $this->getStyleBorderLeftColor();

        if ($asArray) {
            return [
                'width' => $width,
                'style' => $style,
                'color' => $color
            ];
        }

        if ($width === null || $style === null || $color === null)
            return null;

        return $width.' '.$style.' '.$color;
    }
}