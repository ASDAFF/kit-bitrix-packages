<?php

use elements\intec\constructor\shape\Element;
use intec\core\helpers\Html;
use intec\core\helpers\StringHelper;
use intec\constructor\structure\Block;

/**
 * @var Block $block
 * @var Element $this
 */

$image = $this->image;
$border = null;

if ($image !== null) {
    $image = StringHelper::replaceMacros($image, [
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
    'class' => 'ns-intec-constructor block-element block-element-shape block-element-shape-'.$this->type,
    'style' => [
        'background-color' => $this->color,
        'background-image' => $image !== null ? 'url(\''.$image.'\')' : null,
        'background-size' => $this->imageContain ? 'contain' : 'cover',
        'border-radius' => $this->type === 'rectangle' ? $this->borderRadius.'px' : null
    ]
]) ?>
    <?= Html::tag('div', null, [
        'class' => 'block-element-shape-border',
        'style' => [
            'border' => $border,
            'border-radius' => $this->type === 'rectangle' ? $this->borderRadius.'px' : null
        ]
    ]) ?>
<?= Html::endTag('div') ?>