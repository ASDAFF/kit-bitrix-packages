<?php  if (!defined('B_PROLOG_INCLUDED') && B_PROLOG_INCLUDED !== true) die();

use intec\core\helpers\Html;

?>
<?= Html::beginTag('div', [
    'class' => [
        'catalog-element-counter-control',
        'intec-ui' => [
            '',
            'control-numeric',
            'view-2',
            'scheme-current',
            'size-4'
        ]
    ],
    'data' => [
        'role' => 'panel.counter'
    ]
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
