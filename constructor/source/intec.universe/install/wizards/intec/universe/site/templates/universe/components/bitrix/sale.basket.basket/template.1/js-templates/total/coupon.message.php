<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;
use intec\core\helpers\Html;

?>
<div class="basket-coupon-messages">
    {{#COUPON_LIST}}
        <div class="basket-coupon-message" data-state="{{CLASS}}">
                <span class="basket-coupon-message-value">
                    {{COUPON}}
                </span>
                <?= Html::tag('span', Loc::getMessage('C_BASKET_DEFAULT_1_TEMPLATE_COUPON_MESSAGE_DELETE'), [
                    'class' => 'basket-coupon-message-delete',
                    'data' => [
                        'entity' => 'basket-coupon-delete',
                        'coupon' => '{{COUPON}}'
                    ]
                ]) ?>
                <div class="basket-coupon-message-description">
                    <?= Html::tag('span', Loc::getMessage('C_BASKET_DEFAULT_1_TEMPLATE_COUPON_MESSAGE_DESCRIPTION')) ?>
                    <?= Html::tag('span', '{{JS_CHECK_CODE}}') ?>
                    <?= Html::tag('span', '{{#DISCOUNT_NAME}}({{DISCOUNT_NAME}}){{/DISCOUNT_NAME}}') ?>
                </div>
        </div>
    {{/COUPON_LIST}}
</div>
