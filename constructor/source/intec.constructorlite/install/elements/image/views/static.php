<?php

use elements\intec\constructor\image\Element;
use intec\core\helpers\Html;
use intec\core\helpers\StringHelper;
use intec\constructor\structure\Block;

/**
 * @var Block $block
 * @var Element $this
 */

$border = null;
$source = $this->source;
$size = $this->size;
$position = null;

if ($size == 'values')
    $size = $this->sizeX.$this->sizeXMeasure.' '.$this->sizeY.$this->sizeYMeasure;

if (!empty($this->positionX) || !empty($this->positionY))
    $position = $this->positionX.$this->positionXMeasure.' '.$this->positionY.$this->positionYMeasure;

if ($source !== null) {
    $source = StringHelper::replaceMacros($source, [
        'RESOURCES' => $block->getResources()->getDirectory(true)->getValue('/')
    ]);
}

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
?>
<?= Html::beginTag('div', [
    'class' => 'ns-intec-constructor block-element block-element-image',
    'style' => [
        'background-image' => $source !== null ? 'url(\''.$source.'\')' : null,
        'background-size' => $size,
        'background-position' => $position,
        'background-repeat' => $this->repeat,
        'border-radius' => $this->borderRadius.'px'
    ]
]) ?>
    <?= Html::tag('div',null,[
        'class' => 'block-element-image-border',
        'style' => [
            'border' => $border,
            'border-radius' => $this->borderRadius.'px'
        ]
    ]) ?>
<?= Html::endTag('div') ?>