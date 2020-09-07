<?php if (!defined("B_PROLOG_INCLUDED") && B_PROLOG_INCLUDED !== true) die();

use intec\core\helpers\Html;

$vCounter = function () { ?>
    <div class="widget-item-counter intec-grid-item">
        <div class="intec-ui intec-ui-control-numeric intec-ui-view-1" data-role="item.counter">
            <?= Html::beginTag('a', [
                'class' => [
                    'intec-ui-part-decrement',
                    'intec-cl-background-hover',
                    'intec-cl-border-hover'
                ],
                'href' => 'javascript:void(0)',
                'data-type' => 'button',
                'data-action' => 'decrement'
            ]) ?>
                <i class="widget-item-counter-text far fa-minus"></i>
                <div class="intec-aligner"></div>
            <?= Html::endTag('a') ?>
            <?= Html::input('text', null, 0, [
                'data-type' => 'input',
                'class' => 'intec-ui-part-input'
            ]) ?>
            <?= Html::beginTag('a', [
                'class' => [
                    'intec-ui-part-increment',
                    'intec-cl-background-hover',
                    'intec-cl-border-hover'
                ],
                'href' => 'javascript:void(0)',
                'data-type' => 'button',
                'data-action' => 'increment'
            ]) ?>
                <i class="widget-item-counter-text far fa-plus"></i>
                <div class="intec-aligner"></div>
            <?= Html::endTag('a') ?>
        </div>
    </div>
<?php };