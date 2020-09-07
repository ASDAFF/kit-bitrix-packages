<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;
use intec\core\helpers\ArrayHelper;
use intec\core\helpers\Html;

?>
<?= Html::beginTag('div', [
    'class' => [
        'intec-grid-item' => [
            '3',
            '1200-1'
        ],
        'basket-item-total-wrap'
    ],
    'data-mobile-hidden' => ArrayHelper::keyExists('SUM', $mobileColumns) ? 'false' : 'true'
]) ?>
    <div class="basket-item-total">
        {{#DISCOUNT_PRICE_PERCENT}}
            <div class="basket-item-total-percent">
                <?= Html::tag('span', Loc::getMessage('C_BASKET_DEFAULT_1_TEMPLATE_ITEM_TOTAL_DISCOUNT')) ?>
                <?= Html::tag('span', '{{DISCOUNT_PRICE_PERCENT_FORMATED}}') ?>
            </div>
        {{/DISCOUNT_PRICE_PERCENT}}
        <?= Html::tag('div', '{{{SUM_PRICE_FORMATED}}}', [
            'id' => 'basket-item-sum-price-{{ID}}',
            'class' => 'basket-item-total-value'
        ]) ?>
        {{#SHOW_DISCOUNT_PRICE}}
            <?= Html::tag('div', '{{{SUM_FULL_PRICE_FORMATED}}}', [
                'id' => 'basket-item-sum-price-old-{{ID}}',
                'class' => 'basket-item-total-discount',
            ]) ?>
            <div class="basket-item-total-economy">
                <div class="basket-item-total-economy-wrapper">
                    <div class="basket-item-total-economy-name">
                        <?= Loc::getMessage('C_BASKET_DEFAULT_1_TEMPLATE_ITEM_TOTAL_ECONOMY') ?>
                    </div>
                    <?= Html::tag('div', '{{{SUM_DISCOUNT_PRICE_FORMATED}}}', [
                        'id' => 'basket-item-sum-price-difference-{{ID}}',
                        'class' => 'basket-item-total-economy-value'
                    ]) ?>
                </div>
            </div>
        {{/SHOW_DISCOUNT_PRICE}}
    </div>
<?= Html::endTag('div') ?>