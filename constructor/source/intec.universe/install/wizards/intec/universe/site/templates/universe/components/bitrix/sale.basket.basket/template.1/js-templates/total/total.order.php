<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;
use intec\core\helpers\Html;

?>
<div class="intec-grid-item-auto intec-grid-item-768-1" data-print="false">
    <div class="basket-order">
        <div class="basket-order-wrapper intec-grid intec-grid-wrap intec-grid-a-h-end">
            <div class="intec-grid-item-auto">
                {{#DISABLE_CHECKOUT}}
                    <?= Html::tag('button', Loc::getMessage('C_BASKET_DEFAULT_1_TEMPLATE_TOTAL_ORDER'), [
                        'class' => [
                            'basket-order-button',
                            'intec-ui' => [
                                '',
                                'control-button',
                                'size-3',
                                'mod-round-4',
                                'mod-block',
                                'scheme-current'
                            ]
                        ],
                        'disabled' => 'disabled',
                        'data-entity' => 'basket-checkout-button'
                    ]) ?>
                {{/DISABLE_CHECKOUT}}
                {{^DISABLE_CHECKOUT}}
                    <?= Html::tag('button', Loc::getMessage('C_BASKET_DEFAULT_1_TEMPLATE_TOTAL_ORDER'), [
                        'class' => [
                            'basket-order-button',
                            'intec-ui' => [
                                '',
                                'control-button',
                                'size-3',
                                'mod-round-4',
                                'mod-block',
                                'scheme-current'
                            ]
                        ],
                        'data-entity' => 'basket-checkout-button'
                    ]) ?>
                {{/DISABLE_CHECKOUT}}
                <?php include(__DIR__.'/total.order.fast.php') ?>
            </div>
        </div>
    </div>
</div>