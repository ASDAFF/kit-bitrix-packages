<?
namespace elements\intec\constructor\button;

use intec\constructor\models\Font;
use intec\core\helpers\ArrayHelper;
use intec\core\helpers\Html;
use intec\core\helpers\Type;
use intec\constructor\structure\block\Element as Model;

/**
 * Class Element
 * @property string|null $link
 * @property string|null $linkTarget
 * @property string|null $text
 * @property string|null $textColor
 * @property string|null $textColorHover
 * @property float $textSize
 * @property string $textSizeMeasure
 * @property boolean $textCapital
 * @property float $textLetterSpacing
 * @property string $textLetterSpacingMeasure
 * @property float $textLineHeight
 * @property string $textLineHeightMeasure
 * @property string $textStyle
 * @property boolean $border
 * @property float $borderWidth
 * @property string $borderWidthMeasure
 * @property string|null $borderColor
 * @property string|null $borderColorHover
 * @property string $borderStyle
 * @property integer $borderRadius
 * @property string|null $backgroundColor
 * @property string|null $backgroundColorHover
 * @property integer $backgroundOpacity
 * @package elements\button
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
            'link',
            'linkTarget',
            'text',
            'textColor',
            'textColorHover',
            'textFont',
            'textSize',
            'textSizeMeasure',
            'textCapital',
            'textLetterSpacing',
            'textLetterSpacingMeasure',
            'textLineHeight',
            'textLineHeightMeasure',
            'textStyle',
            'border',
            'borderWidth',
            'borderWidthMeasure',
            'borderColor',
            'borderColorHover',
            'borderStyle',
            'borderRadius',
            'backgroundColor',
            'backgroundColorHover',
            'backgroundOpacity'
        ]);

        return $attributes;
    }

    /**
     * @inheritdoc
     */
    public function attributeCorrect($attribute, $value, $resolution, $operation)
    {
        if (
            $attribute == 'link' ||
            $attribute == 'text' ||
            $attribute == 'textColor' ||
            $attribute == 'textColorHover' ||
            $attribute == 'borderColor' ||
            $attribute == 'borderColorHover' ||
            $attribute == 'borderStyle' ||
            $attribute == 'backgroundColor' ||
            $attribute == 'backgroundColorHover'
        ) {
            if ($value !== null)
                $value = Type::toString($value);
        } else if ($attribute == 'linkTarget') {
            $value = ArrayHelper::fromRange(['_self', '_blank', '_parent', '_top'], $value);
        } else if ($attribute == 'borderRadius') {
            $value = Type::toInteger($value);
        } else if (
            $attribute == 'textSize' ||
            $attribute == 'textLetterSpacing' ||
            $attribute == 'textLineHeight' ||
            $attribute == 'borderWidth'
        ) {
            $value = Type::toFloat($value);
        } else if (
            $attribute == 'textSizeMeasure' ||
            $attribute == 'textLetterSpacingMeasure' ||
            $attribute == 'textLineHeightMeasure'
        ) {
            $value = ArrayHelper::fromRange(['px', 'pt', '%', 'em'], $value);
        } else if (
            $attribute == 'textCapital' ||
            $attribute == 'border'
        ) {
            $value = Type::toBoolean($value);
        } else if ($attribute == 'textStyle') {
            $value = ArrayHelper::fromRange(['none', 'bold', 'italic'], $value);
        } else if ($attribute == 'borderWidthMeasure') {
            $value = ArrayHelper::fromRange(['px', 'em', 'cm'], $value);
        } else if ($attribute == 'backgroundOpacity') {
            $value = Type::toInteger($value);
            $value = $value >= 0 ? $value : 0;
            $value = $value <= 100 ? $value : 100;
        }

        return parent::attributeCorrect(
            $attribute,
            $value,
            $resolution,
            $operation
        );
    }

    /**
     * @inheritdoc
     */
    public function getStyle($block, $id, $resolution = null)
    {
        $style = [];

        if ($resolution === null) {
            $textFont = $this->getAttribute('textFont', null);

            if (!empty($textFont)) {
                $font = Font::findOne($textFont);

                if (!empty($font)) {
                    $font->register();

                    $style[] = '.'.$this->makeStyleClass($block, $id).' '.
                        '.ns-intec-constructor.block-element.block-element-button '.
                        '.block-element-button-text {'.
                            Html::cssStyleFromArray([
                                'font-family' => '\''.$font->code.'\', sans-serif',
                            ]).
                        '}';
                }
            }
        } else {

            $border = null;

            if (
                $this->border &&
                !empty($this->borderColor) &&
                !empty($this->borderStyle) &&
                !empty($this->borderWidth) &&
                !empty($this->borderWidthMeasure)
            ) {
                $border = $this->borderWidth.
                    $this->borderWidthMeasure.' '.
                    $this->borderStyle.' '.
                    $this->borderColor;
            }

            $style[] = '.'.$this->makeStyleClass($block, $id).' '.
                '.ns-intec-constructor.block-element.block-element-button '.
                '.block-element-button-background {'.
                    Html::cssStyleFromArray([
                        'background-color' => $this->backgroundColor
                    ]).
                '}';

            $style[] = '.'.$this->makeStyleClass($block, $id).' '.
                '.ns-intec-constructor.block-element.block-element-button '.
                '.block-element-button-border {'.
                    Html::cssStyleFromArray([
                        'border' => $border
                    ]).
                '}';

            $style[] = '.'.$this->makeStyleClass($block, $id).' '.
                '.ns-intec-constructor.block-element.block-element-button '.
                '.block-element-button-text {'.
                    Html::cssStyleFromArray([
                        'color' => $this->textColor
                    ]).
                '}';

            $style[] = '.'.$this->makeStyleClass($block, $id).' '.
                '.ns-intec-constructor.block-element.block-element-button:hover '.
                '.block-element-button-background {'.
                    Html::cssStyleFromArray([
                        'background-color' => $this->backgroundColorHover
                    ]).
                '}';

            $style[] = '.'.$this->makeStyleClass($block, $id).' '.
                '.ns-intec-constructor.block-element.block-element-button:hover '.
                '.block-element-button-border {'.
                    Html::cssStyleFromArray([
                        'border-color' => $border !== null ? $this->borderColorHover : null
                    ]).
                '}';

            $style[] = '.'.$this->makeStyleClass($block, $id).' '.
                '.ns-intec-constructor.block-element.block-element-button:hover '.
                '.block-element-button-text {'.
                    Html::cssStyleFromArray([
                        'color' => $this->textColorHover
                    ]).
                '}';
        }

        if (empty($style))
            return null;

        return implode('', $style);
    }
}