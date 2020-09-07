<?php
namespace intec\constructor\models\build\template\container;

use intec\core\helpers\ArrayHelper;
use intec\core\helpers\Type;

trait StyleMarginTrait
{
    /**
     * Общий обработчик получения для свойства margin
     * @param bool $asArray
     * @param string|null $side
     * @return string|null
     */
    protected function getStyleMarginHandler($asArray = false, $side = null)
    {
        $property = !empty($side) ? 'margin.'.$side : 'margin';
        $value = $this->getPropertyMeasured($property, true);
        $isAuto = $this->getProperty($property.'.isAuto');

        if ($asArray) {
            $value['isAuto'] = $isAuto;
            return $value;
        } else if ($isAuto) {
            return 'auto';
        } else if ($value['value'] !== null) {
            return $value['value'].$value['measure'];
        }

        return null;
    }

    /**
     * Общий обработчик установки для свойства margin
     * @param int|float|bool|null $value
     * @param string|bool|null $measure
     * @param bool|null $isAuto
     * @param string|null $side
     * @return bool
     */
    protected function setStyleMarginHandler($value, $measure, $isAuto = false, $side = null)
    {
        $property = !empty($side) ? 'margin.'.$side : 'margin';
        $changed = false;
        $isAuto = $isAuto === null ? null : Type::toBoolean($isAuto);
        $changed = $this->setPropertyMeasured($property, $value, $measure) || $changed;
        $changed = $this->setProperty($property.'.isAuto', $isAuto) || $changed;

        return $changed;
    }

    /**
     * Возвращает свойство margin без объединения сторон для css.
     * @param bool $asArray
     * @return string|null
     */
    public function getStyleMargin($asArray = false)
    {
        return $this->getStyleMarginHandler($asArray);
    }

    /**
     * Устанавливает свойство margin для css.
     * @param int|float|bool|null $value
     * @param string|bool|null $measure
     * @param bool|null $isAuto
     * @return bool
     */
    public function setStyleMargin($value = false, $measure = false, $isAuto = false)
    {
        return $this->setStyleMarginHandler($value, $measure, $isAuto);
    }

    /**
     * Возвращает свойство margin-top для css.
     * @param bool $asArray
     * @return string|null
     */
    public function getStyleMarginTop($asArray = false)
    {
        return $this->getStyleMarginHandler($asArray, 'top');
    }

    /**
     * Устанавливает свойство margin-top для css.
     * @param int|float|bool|null $value
     * @param string|bool|null $measure
     * @param bool|null $isAuto
     * @return bool
     */
    public function setStyleMarginTop($value = false, $measure = false, $isAuto = false)
    {
        return $this->setStyleMarginHandler($value, $measure, $isAuto, 'top');
    }

    /**
     * Возвращает свойство margin-right для css.
     * @param bool $asArray
     * @return string|null
     */
    public function getStyleMarginRight($asArray = false)
    {
        return $this->getStyleMarginHandler($asArray, 'right');
    }

    /**
     * Устанавливает свойство margin-right для css.
     * @param int|float|bool|null $value
     * @param string|bool|null $measure
     * @param bool|null $isAuto
     * @return bool
     */
    public function setStyleMarginRight($value = false, $measure = false, $isAuto = false)
    {
        return $this->setStyleMarginHandler($value, $measure, $isAuto, 'right');
    }

    /**
     * Возвращает свойство margin-bottom для css.
     * @param bool $asArray
     * @return string|null
     */
    public function getStyleMarginBottom($asArray = false)
    {
        return $this->getStyleMarginHandler($asArray, 'bottom');
    }

    /**
     * Устанавливает свойство margin-bottom для css.
     * @param int|float|bool|null $value
     * @param string|bool|null $measure
     * @param bool|null $isAuto
     * @return bool
     */
    public function setStyleMarginBottom($value = false, $measure = false, $isAuto = false)
    {
        return $this->setStyleMarginHandler($value, $measure, $isAuto, 'bottom');
    }

    /**
     * Возвращает свойство margin-left для css.
     * @param bool $asArray
     * @return string|null
     */
    public function getStyleMarginLeft($asArray = false)
    {
        return $this->getStyleMarginHandler($asArray, 'left');
    }

    /**
     * Устанавливает свойство margin-left для css.
     * @param int|float|bool|null $value
     * @param string|bool|null $measure
     * @param bool|null $isAuto
     * @return bool
     */
    public function setStyleMarginLeft($value = false, $measure = false, $isAuto = false)
    {
        return $this->setStyleMarginHandler($value, $measure, $isAuto, 'left');
    }

    /**
     * Возвращает свойство margin для css.
     * @return string|null
     */
    public function getStyleMarginSummary()
    {
        $value = $this->getStyleMargin();
        $sides = static::getStyleSidesValues();
        $values = [];
        $result = [];

        $values[static::STYLE_SIDE_TOP] = $this->getStyleMarginTop();
        $values[static::STYLE_SIDE_RIGHT] = $this->getStyleMarginRight();
        $values[static::STYLE_SIDE_BOTTOM] = $this->getStyleMarginBottom();
        $values[static::STYLE_SIDE_LEFT] = $this->getStyleMarginLeft();

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