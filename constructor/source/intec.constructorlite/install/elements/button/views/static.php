<?php

use elements\intec\constructor\button\Element;
use intec\core\helpers\Html;
use intec\core\helpers\StringHelper;
use intec\constructor\structure\Block;

/**
 * @var Block $block
 * @var Element $this
 */

$link = $this->link;
$textSize = null;
$textLetterSpacing = null;
$textLineHeight = null;
$textStyle = $this->textStyle;

$link = StringHelper::replaceMacros($link, [
    'SITE_DIR' => SITE_DIR
]);

if (
    !empty($this->textSize) &&
    !empty($this->textSizeMeasure)
) {
    $textSize = $this->textSize.$this->textSizeMeasure;
}

if (
    !empty($this->textLetterSpacing) &&
    !empty($this->textLetterSpacingMeasure)
) {
    $textLetterSpacing = $this->textLetterSpacing.$this->textLetterSpacingMeasure;
}

if (
    !empty($this->textLineHeight) &&
    !empty($this->textLineHeightMeasure)
) {
    $textLineHeight = $this->textLineHeight.$this->textLineHeightMeasure;
}
?>
<?= Html::beginTag('a', [
    'class' => 'ns-intec-constructor block-element block-element-button',
    'href' => $link,
    'target' => $this->linkTarget,
    'style' => [
        'border-radius' => $this->borderRadius.'px'
    ]
]) ?>
    <?= Html::tag('div', null, [
        'class' => 'block-element-button-background',
        'style' => [
            'opacity' => (100 - $this->backgroundOpacity) / 100
        ]
    ]) ?>
    <?= Html::tag('div', null, [
        'class' => 'block-element-button-border',
        'style' => [
            'border-radius' => $this->borderRadius.'px'
        ]
    ]) ?>
    <div class="block-element-button-wrapper">
        <span class="block-element-button-aligner"></span>
        <?= Html::tag('span', Html::encode($this->text), [
            'class' => 'block-element-button-text',
            'style' => [
                'font-size' => $textSize,
                'letter-spacing' => $textLetterSpacing,
                'line-height' => $textLineHeight,
                'font-weight' => $this->textStyle === 'bold' ? 'bold' : null,
                'font-style' => $this->textStyle === 'italic' ? 'italic' : null
            ]
        ]) ?>
    </div>
<?= Html::endTag('a') ?>