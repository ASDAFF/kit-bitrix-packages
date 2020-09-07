<?
namespace elements\intec\constructor\image;

use intec\constructor\structure\block\Element as Model;
use intec\core\helpers\ArrayHelper;
use intec\core\helpers\Type;

/**
 * Class Element
 * @property string $source
 * @property float $positionX
 * @property float $positionXMeasure
 * @property float $positionY
 * @property float $positionYMeasure
 * @property string $size
 * @property float $sizeX
 * @property float $sizeXMeasure
 * @property float $sizeY
 * @property float $sizeYMeasure
 * @property string $repeat
 * @property boolean $border
 * @property float $borderWidth
 * @property string $borderWidthMeasure
 * @property string|null $borderColor
 * @property string $borderStyle
 * @property integer $borderRadius
 * @package elements\intec\constructor\image
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
            'source',
            'positionX',
            'positionXMeasure',
            'positionY',
            'positionYMeasure',
            'size',
            'sizeX',
            'sizeXMeasure',
            'sizeY',
            'sizeYMeasure',
            'repeat',
            'border',
            'borderWidth',
            'borderWidthMeasure',
            'borderColor',
            'borderColorHover',
            'borderStyle',
            'borderRadius'
        ]);

        return $attributes;
    }

    /**
     * @inheritdoc
     */
    public function attributeCorrect($attribute, $value, $resolution, $operation)
    {
        if (
            $attribute == 'borderColor' ||
            $attribute == 'borderStyle'
        ) {
            if ($value !== null)
                $value = Type::toString($value);
        } else if ($attribute == 'borderRadius') {
            $value = Type::toInteger($value);
        } else if (
            $attribute == 'positionX' ||
            $attribute == 'positionY' ||
            $attribute == 'sizeX' ||
            $attribute == 'sizeY' ||
            $attribute == 'borderWidth'
        ) {
            $value = Type::toFloat($value);
        } else if (
            $attribute == 'positionXMeasure' ||
            $attribute == 'positionYMeasure' ||
            $attribute == 'sizeXMeasure' ||
            $attribute == 'sizeYMeasure'
        ) {
            $value = ArrayHelper::fromRange(['px', 'cm', '%', 'em'], $value);
        } else if ($attribute == 'border') {
            $value = Type::toBoolean($value);
        } else if ($attribute == 'repeat') {
            $value = ArrayHelper::fromRange(['no-repeat', 'repeat-x', 'repeat-y', 'repeat'], $value);
        } else if ($attribute == 'borderWidthMeasure') {
            $value = ArrayHelper::fromRange(['px', 'em', 'cm'], $value);
        }

        return parent::attributeCorrect(
            $attribute,
            $value,
            $resolution,
            $operation
        );
    }
}