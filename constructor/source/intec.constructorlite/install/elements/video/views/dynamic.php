<?php

use intec\core\helpers\Html;
use elements\intec\constructor\image\Element;

/**
 * @var Element $this
 */

$directory = $this->getDirectory(true)->asAbsolute();

?>
<!-- ko if: properties.source -->
    <div class="ns-intec-constructor block-element block-element-video">
        <iframe class="block-element-video-frame" data-bind="{
            'attr': {
                'src': properties.source,
                'allowfullscreen': properties.allowFullScreen() ? 'allowfullscreen' : null
            }
        }"></iframe>
        <div class="block-element-video-locker"></div>
    </div>
<!-- /ko -->
<!-- ko if: !properties.source() -->
    <?= Html::beginTag('div', [
        'class' => 'ns-intec-constructor block-element block-element-video block-element-video-empty',
        'style' => [
            'background-image' => 'url(\''.$directory->add('resources/images/stub.png')->getValue('/').'\')'
        ]
    ]) ?>
        <div class="block-element-video-border"></div>
    <?= Html::endTag('div') ?>
<!-- /ko -->