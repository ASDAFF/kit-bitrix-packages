<?
namespace elements\intec\constructor\shape;

use intec\core\helpers\ArrayHelper;
use intec\core\helpers\Type;
use intec\constructor\structure\block\Element as Model;

/**
 * Class Element
 * @property string $type
 * @property string|null $color
 * @property boolean $border
 * @property float $borderWidth
 * @property string $borderWidthMeasure
 * @property string|null $borderColor
 * @property string|null $borderStyle
 * @property integer $borderRadius
 * @property string|null $image
 * @property boolean $imageContain
 * @package elements\intec\constructor\shape
 */
class Element extends Model
{
    /**
     * @inheritdoc
     */
    public function getName()
    {
        return $this->getLanguage()->getMessage('name');
    }

    /**
     * @inheritdoc
     */
    public function attributes()
    {
        $attributes = parent::attributes();
        $attributes = ArrayHelper::merge($attributes, [
            'type',
            'color',
            'border',
            'borderWidth',
            'borderWidthMeasure',
            'borderColor',
            'borderStyle',
            'borderRadius',
            'image',
            'imageContain'
        ]);

        return $attributes;
    }

    /**
     * @inheritdoc
     */
    public function attributeCorrect($attribute, $value, $resolution, $operation)
    {
        if (
            $attribute == 'color' ||
            $attribute == 'borderColor' ||
            $attribute == 'borderStyle' ||
            $attribute == 'image'
        ) {
            if ($value !== null)
                $value = Type::toString($value);
        } else if ($attribute == 'type') {
            $value = ArrayHelper::fromRange(['rectangle', 'circle'], $value);
        } else if (
            $attribute == 'border' ||
            $attribute == 'imageContain'
        ) {
            $value = Type::toBoolean($value);
        } else if ($attribute == 'borderWidth') {
            $value = Type::toFloat($value);
        } else if ($attribute == 'borderWidthMeasure') {
            $value = ArrayHelper::fromRange(['px', 'em', 'cm'], $value);
        } else if ($attribute == 'borderRadius') {
            $value = Type::toInteger($value);
        }

        return parent::attributeCorrect(
            $attribute,
            $value,
            $resolution,
            $operation
        );
    }
}