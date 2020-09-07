<?
namespace elements\intec\constructor\text;

use intec\constructor\models\Font;
use intec\core\helpers\ArrayHelper;
use intec\constructor\structure\block\Element as Model;
use intec\core\helpers\Html;
use intec\core\helpers\Type;

/**
 * Class Element
 * @property string $text
 * @property string $textAlign
 * @property string|null $textColor
 * @property float|null $textSize
 * @property string $textSizeMeasure
 * @property float|null $textLetterSpacing
 * @property string $textLetterSpacingMeasure
 * @property float|null $textLineHeight
 * @property string $textLineHeightMeasure
 * @package elements\intec\constructor\text
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
            'text',
            'textAlign',
            'textColor',
            'textFont',
            'textSize',
            'textSizeMeasure',
            'textLetterSpacing',
            'textLetterSpacingMeasure',
            'textLineHeight',
            'textLineHeightMeasure'
        ]);

        return $attributes;
    }

    /**
     * @inheritdoc
     */
    public function attributeCorrect($attribute, $value, $resolution, $operation)
    {
        if ($attribute == 'text') {
            if ($value !== null)
                $value = Type::toString($value);
        } else if ($attribute == 'textAlign') {
            $value = ArrayHelper::fromRange(['left', 'center', 'right', 'justify'], $value);
        } else if (
            $attribute == 'textSize' ||
            $attribute == 'textLetterSpacing' ||
            $attribute == 'textLineHeight'
        ) {
            if ($value !== null)
                $value = Type::toFloat($value);
        } else if (
            $attribute == 'textSizeMeasure' ||
            $attribute == 'textLetterSpacingMeasure' ||
            $attribute == 'textLineHeightMeasure'
        ) {
            $value = ArrayHelper::fromRange(['px', 'pt', '%', 'em'], $value);
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
                        '.ns-intec-constructor.block-element.block-element-text {'.
                        Html::cssStyleFromArray([
                            'font-family' => '\''.$font->code.'\', sans-serif',
                        ]).
                    '}';
                }
            }
        } else {
            $textSize = $this->getAttribute('textSize', $resolution);
            $textLetterSpacing = $this->getAttribute('textLetterSpacing', $resolution);
            $textLineHeight = $this->getAttribute('textLineHeight', $resolution);
            $textAlign = $this->getAttribute('textAlign', $resolution);
            $textAlign = ArrayHelper::fromRange(['left', 'center', 'right', 'justify'], $textAlign);

            if ($textSize !== null) {
                $textSize .= $this->getAttribute('textSizeMeasure', $resolution);
            } else {
                $textSize = 'medium';
            }

            if ($textLetterSpacing !== null) {
                $textLetterSpacing .= $this->getAttribute('textLetterSpacingMeasure', $resolution);
            } else {
                $textLetterSpacing = 'normal';
            }

            if ($textLineHeight !== null) {
                $textLineHeight .= $this->getAttribute('textLineHeightMeasure', $resolution);
            } else {
                $textLineHeight = 'normal';
            }

            $style[] = '.'.$this->makeStyleClass($block, $id).' '.
                '.ns-intec-constructor.block-element.block-element-text {'.
                    Html::cssStyleFromArray([
                        'color' => $this->getAttribute('textColor', $resolution),
                        'font-size' => $textSize,
                        'letter-spacing' => $textLetterSpacing,
                        'line-height' => $textLineHeight,
                        'text-align' => $textAlign
                    ]).
                '}';
        }

        if (empty($style))
            return null;

        return implode('', $style);
    }
}