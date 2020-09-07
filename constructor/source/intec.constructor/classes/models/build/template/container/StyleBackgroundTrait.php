<?php
namespace intec\constructor\models\build\template\container;

use intec\core\helpers\ArrayHelper;
use intec\core\helpers\StringHelper;
use intec\constructor\models\Build;
use intec\constructor\models\build\Template;

trait StyleBackgroundTrait
{
    /**
     * Возвращает свойство background-color для css.
     * @return string|null
     */
    public function getStyleBackgroundColor()
    {
        $value = $this->getProperty('background.color');
        return empty($value) ? null : $value;
    }

    /**
     * Устанавливает свойство background-color для css.
     * @param string|bool|null $value
     * @return bool
     */
    public function setStyleBackgroundColor($value = false)
    {
        if ($value === false)
            return false;

        return $this->setProperty('background.color', $value);
    }

    /**
     * Возвращает свойство background-size для css.
     * @param bool $asArray
     * @return string|null
     */
    public function getStyleBackgroundSize($asArray = false)
    {
        $measures = ['px', '%', 'em', 'cm'];
        $width = $this->getPropertyMeasured('background.size.left', true, $measures);
        $height = $this->getPropertyMeasured('background.size.top', true, $measures);
        $type = $this->getProperty('background.size.type');

        if ($asArray) {
            return [
                'width' => $width,
                'height' => $height,
                'type' => $type
            ];
        } else if ($type == 'cover' || $type == 'contain' || $type == 'auto') {
            return $type;
        } else if ($width['value'] !== null || $height['value'] !== null) {
            if ($width['value'] === null) {
                $width = 'auto';
            } else {
                $width = $width['value'].$width['measure'];
            }

            if ($height['value'] === null) {
                $height = 'auto';
            } else {
                $height = $height['value'].$height['measure'];
            }

            return $width.' '.$height;
        }

        return null;
    }

    /**
     * Устанавливает свойство background-size для css.
     * @param int|float|bool|null $width
     * @param string|bool|null $widthMeasure
     * @param int|float|bool|null $height
     * @param string|bool|null $heightMeasure
     * @param string|bool|null $type
     * @return bool
     */
    public function setStyleBackgroundSize(
        $width = false, $widthMeasure = false,
        $height = false, $heightMeasure = false,
        $type = false
    ) {
        $measures = ['px', '%', 'em', 'cm'];
        $types = ['auto', 'cover', 'contain', 'custom'];
        $changed = false;

        $changed = $this->setPropertyMeasured('background.size.left', $width, $widthMeasure, $measures) || $changed;
        $changed = $this->setPropertyMeasured('background.size.top', $height, $heightMeasure, $measures) || $changed;

        if ($type !== false) {
            if (ArrayHelper::isIn($type, $types)) {
                $changed = $this->setProperty('background.size.type', $type) || $changed;
            } else {
                $changed = $this->setProperty('background.size.type', null) || $changed;
            }
        }

        return $changed;
    }

    /**
     * Возвращает свойство background-position для css.
     * @param bool $asArray
     * @return string|null
     */
    public function getStyleBackgroundPosition($asArray = false)
    {
        $top = $this->getPropertyMeasured('background.position.top', true);
        $left = $this->getPropertyMeasured('background.position.left', true);

        if (!$asArray) {
            if ($top['value'] !== null || $left['value'] !== null) {
                $top = $top['value'] !== null ? $top['value'].$top['measure'] : 0;
                $left = $left['value'] !== null ? $left['value'].$left['measure'] : 0;

                return $top.' '.$left;
            }

            return null;
        }

        return [
            'top' => $top,
            'left' => $left
        ];
    }

    /**
     * Устанавливает свойство background-position для css.
     * @param int|float|bool|null $topValue
     * @param string|bool|null $topMeasure
     * @param int|float|bool|null $leftValue
     * @param string|bool|null $leftMeasure
     * @return bool
     */
    public function setStyleBackgroundPosition(
        $topValue = false, $topMeasure = false,
        $leftValue = false, $leftMeasure = false
    ) {
        $changed = false;
        $changed = $this->setPropertyMeasured('background.position.top', $topValue, $topMeasure) || $changed;
        $changed = $this->setPropertyMeasured('background.position.left', $leftValue, $leftMeasure) || $changed;
        return $changed;
    }

    /**
     * Возвращает свойство background-image для css.
     * @param bool $raw Возвращать в исходном виде.
     * @return string|null
     */
    public function getStyleBackgroundImage($raw = false)
    {

        $value = $this->getProperty('background.image.url');

        if (empty($value))
            return null;

        if (!$raw) {
            /** @var Template $template */
            $template = $this->getStorageTemplate(true);

            if (!empty($template)) {
                $build = $template->getBuild(true);

                if (!empty($build))
                    $value = StringHelper::replaceMacros($value, [
                        'TEMPLATE' => $build->getDirectory(false, true, '/')
                    ]);
            }
        }

        return $value;
    }

    /**
     * Устанавливает свойство background-image для css.
     * @param string|null $value
     * @return bool
     */
    public function setStyleBackgroundImage($value = null)
    {
        return $this->setProperty('background.image.url', $value);
    }

    /**
     * Возвращает свойство background-repeat для css.
     * @return string|null
     */
    public function getStyleBackgroundRepeat()
    {
        $values = $this->getStyleBackgroundRepeatsValues();
        $value = $this->getProperty('background.repeat');

        return ArrayHelper::fromRange($values, $value, false, false);
    }

    /**
     * Устанавливает свойство background-repeat для css.
     * @return bool
     */
    public function setStyleBackgroundRepeat($value = false)
    {
        if ($value === false)
            return false;

        $values = $this->getStyleBackgroundRepeatsValues();
        $value = ArrayHelper::fromRange($values, $value, false, false);

        return $this->setProperty('background.repeat', $value);
    }
}