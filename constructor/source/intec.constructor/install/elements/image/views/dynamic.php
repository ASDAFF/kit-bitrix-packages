<?php

use intec\core\helpers\Html;
use elements\intec\constructor\image\Element;

/**
 * @var Element $this
 */

$directory = $this->getDirectory(true)->asAbsolute();

?>
<!-- ko if: properties.source.summary -->
    <div class="ns-intec-constructor block-element block-element-image" data-bind="{
        'style': {
            'background-image': properties.source.summary,
            'background-size': properties.size.summary,
            'background-position': properties.position.summary,
            'background-repeat': properties.repeat,
            'border-radius': properties.border.radius() + 'px'
        }
    }">
        <div class="block-element-image-border" data-bind="{
            'style': {
                'border': properties.border.summary,
                'border-radius': properties.border.radius() + 'px'
            }
        }"></div>
    </div>
<!-- /ko -->
<!-- ko if: !properties.source.summary() -->
    <?= Html::beginTag('div', [
        'class' => 'ns-intec-constructor block-element block-element-image block-element-image-empty',
        'style' => [
            'background-image' => 'url(\''.$directory->add('resources/images/stub.png')->getValue('/').'\')'
        ]
    ]) ?>
        <div class="block-element-image-border"></div>
    <?= Html::endTag('div') ?>
<!-- /ko -->