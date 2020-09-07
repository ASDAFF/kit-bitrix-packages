<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use intec\core\helpers\Html;

?>
<?= Html::beginTag('div', [
    'class' => 'basket-item-quantity',
    'data-entity' => 'basket-item-quantity-block'
]) ?>
    <?= Html::beginTag('div', [
        'class' => [
            'basket-item-quantity-counter',
            'intec-ui' => [
                '',
                'control-numeric',
                'scheme-current',
                'view-2',
                'size-5'
            ]
        ]
    ]) ?>
        {{#NOT_AVAILABLE}}
            <?= Html::button('-', [
                'class' => 'intec-ui-part-decrement',
                'disabled' => 'disabled',
                'data' => [
                    'entity' => 'basket-item-quantity-minus',
                    'print' => 'false'
                ]
            ]) ?>
            <?= Html::input('text', null, '{{QUANTITY}}', [
                'id' => 'basket-item-quantity-{{ID}}',
                'class' => 'intec-ui-part-input',
                'disabled' => 'disabled',
                'data' => [
                    'entity' => 'basket-item-quantity-field',
                    'value' => '{{QUANTITY}}'
                ]
            ]) ?>
            <?= Html::button('+', [
                'class' => 'intec-ui-part-increment',
                'disabled' => 'disabled',
                'data' => [
                    'entity' => 'basket-item-quantity-plus',
                    'print' => 'false'
                ]
            ]) ?>
        {{/NOT_AVAILABLE}}
        {{^NOT_AVAILABLE}}
            <?= Html::button('-', [
                'class' => 'intec-ui-part-decrement',
                'data' => [
                    'entity' => 'basket-item-quantity-minus',
                    'print' => 'false'
                ]
            ]) ?>
            <?= Html::input('text', null, '{{QUANTITY}}', [
                'id' => 'basket-item-quantity-{{ID}}',
                'class' => 'intec-ui-part-input',
                'data' => [
                    'entity' => 'basket-item-quantity-field',
                    'value' => '{{QUANTITY}}'
                ]
            ]) ?>
            <?= Html::button('+', [
                'class' => 'intec-ui-part-increment',
                'data' => [
                    'entity' => 'basket-item-quantity-plus',
                    'print' => 'false'
                ]
            ]) ?>
        {{/NOT_AVAILABLE}}
    <?= Html::endTag('div') ?>
<?= Html::endTag('div') ?>