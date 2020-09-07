<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;
use intec\core\helpers\Html;

?>
<div class="intec-grid-item-auto intec-grid-item-768-1">
    <div class="basket-price">
        <?= Html::beginTag('div', [
            'class' => [
                'basket-price-wrapper',
                'intec-grid' => [
                    '',
                    'wrap',
                    'a-h-end',
                    'i-h-16',
                    'i-v-8'
                ]
            ]
        ]) ?>
            <div class="intec-grid-item-auto">
                <div class="basket-price-info">
                    <div class="basket-price-info-name">
                        <?= Loc::getMessage('C_BASKET_DEFAULT_1_TEMPLATE_TOTAL_TOTAL') ?>
                    </div>
                    <div class="basket-price-info-values">
                        {{#WEIGHT_FORMATED}}
                            <div class="basket-price-info-value">
                                <?= Loc::getMessage('C_BASKET_DEFAULT_1_TEMPLATE_TOTAL_WEIGHT').' {{{WEIGHT_FORMATED}}}' ?>
                            </div>
                        {{/WEIGHT_FORMATED}}
                        {{#SHOW_VAT}}
                            <div class="basket-price-info-value">
                                <?= Loc::getMessage('C_BASKET_DEFAULT_1_TEMPLATE_TOTAL_VAT').' {{{VAT_SUM_FORMATED}}}' ?>
                            </div>
                        {{/SHOW_VAT}}
                    </div>
                </div>
            </div>
            <div class="intec-grid-item-auto">
                <div class="basket-price-values">
                    <?= Html::tag('div', '{{{PRICE_FORMATED}}}', [
                        'class' => 'basket-price-current',
                        'data-entity' => 'basket-total-price'
                    ]) ?>
                    {{#DISCOUNT_PRICE_FORMATED}}
                        <div class="basket-price-discount">
                            {{{PRICE_WITHOUT_DISCOUNT_FORMATED}}}
                        </div>
                    {{/DISCOUNT_PRICE_FORMATED}}
                    {{#DISCOUNT_PRICE_FORMATED}}
                        <div class="basket-price-economy">
                            <div class="basket-price-economy-wrapper">
                                <span class="basket-price-economy-name">
                                    <?= Loc::getMessage('C_BASKET_DEFAULT_1_TEMPLATE_TOTAL_ECONOMY') ?>
                                </span>
                                <span class="basket-price-economy-value">
                                    {{{DISCOUNT_PRICE_FORMATED}}}
                                </span>
                            </div>
                        </div>
                    {{/DISCOUNT_PRICE_FORMATED}}
                </div>
            </div>
        <?= Html::endTag('div') ?>
    </div>
</div>