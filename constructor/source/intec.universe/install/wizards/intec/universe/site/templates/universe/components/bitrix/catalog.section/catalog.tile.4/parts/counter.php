<?php if (!defined('B_PROLOG_INCLUDED') && B_PROLOG_INCLUDED !== true) die();

use intec\core\helpers\Html;

?>
<?php $vCounter = function () { ?>
    <div class="catalog-section-item-counter intec-grid-item">
        <?= Html::beginTag('div', [
            'class' => [
                'intec-ui' => [
                    '',
                    'control-numeric',
                    'view-6',
                    'size-5',
                    'scheme-current'
                ]
            ],
            'data-role' => 'item.counter'
        ]) ?>
            <?= Html::tag('a', '-', [
                'class' => 'intec-ui-part-decrement',
                'href' => 'javascript:void(0)',
                'data-type' => 'button',
                'data-action' => 'decrement'
            ]) ?>
            <?= Html::input('text', null, 0, [
                'data-type' => 'input',
                'class' => 'intec-ui-part-input'
            ]) ?>
            <?= Html::tag('a', '+', [
                'class' => 'intec-ui-part-increment',
                'href' => 'javascript:void(0)',
                'data-type' => 'button',
                'data-action' => 'increment'
            ]) ?>
        <?= Html::endTag('div') ?>
    </div>
<?php };