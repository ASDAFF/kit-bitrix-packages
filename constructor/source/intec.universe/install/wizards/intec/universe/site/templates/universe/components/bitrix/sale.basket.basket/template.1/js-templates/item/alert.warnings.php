<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use intec\core\helpers\Html;

?>
{{#WARNINGS.length}}
    <?= Html::beginTag('div', [
        'class' => [
            'basket-alert',
            'intec-ui' => [
                '',
                'control-alert',
                'scheme-orange'
            ]
        ],
        'data-entity' => 'basket-item-warning-node'
    ]) ?>
        <?= Html::tag('div', null, [
            'class' => [
                'basket-alert-close',
                'far fa-times'
            ],
            'data-entity' => 'basket-item-warning-close'
        ]) ?>
        {{#WARNINGS}}
            <?= Html::tag('div', '{{{.}}}', [
                'class' => 'basket-alert-line',
                'data-entity' => 'basket-item-alert-text'
            ]) ?>
        {{/WARNINGS}}
    <?= Html::endTag('div') ?>
{{/WARNINGS.length}}