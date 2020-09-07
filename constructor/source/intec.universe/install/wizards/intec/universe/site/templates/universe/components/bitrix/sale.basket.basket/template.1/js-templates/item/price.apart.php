<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;
use intec\core\helpers\ArrayHelper;
use intec\core\helpers\Html;

?>
<?= Html::beginTag('div', [
    'class' => [
        'basket-item-price-apart-wrap',
        'intec-grid-item' => [
            '3',
            '1200-1'
        ]
    ],
    'data-mobile-hidden' => ArrayHelper::keyExists('PRICE', $mobileColumns) ? 'false' : 'true'
]) ?>
    <div class="basket-item-price-apart">
        <div class="basket-item-price-apart-measure">
            <?= Html::tag('span', Loc::getMessage('C_BASKET_DEFAULT_1_TEMPLATE_ITEM_PRICE_APART_BEFORE')) ?>
            <?= Html::tag('span', '{{MEASURE_RATIO}}') ?>
            <?= Html::tag('span', '{{MEASURE_TEXT}}') ?>
        </div>
        <?= Html::tag('div', '{{{PRICE_FORMATED}}}', [
            'id' => 'basket-item-price-{{ID}}',
            'class' => 'basket-item-price-apart-value'
        ]) ?>
        {{#SHOW_DISCOUNT_PRICE}}
            <div class="basket-item-price-apart-discount">
                {{{FULL_PRICE_FORMATED}}}
            </div>
        {{/SHOW_DISCOUNT_PRICE}}
    </div>
<?= Html::endTag('div') ?>