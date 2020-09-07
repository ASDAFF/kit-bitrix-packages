<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;
use intec\core\helpers\ArrayHelper;
use intec\core\helpers\Html;

/**
 * @var array $arResult
 * @var array $arParams
 * @var CAllMain $APPLICATION
 * @var CBitrixComponent $component
 */

if (empty($arParams['GIFTS_BLOCK_TITLE']))
    $arParams['GIFTS_BLOCK_TITLE'] = Loc::getMessage('C_BASKET_DEFAULT_1_TEMPLATE_GIFTS_TITLE');

if (empty($arParams['GIFTS_PRODUCT_BLOCKS_ORDER']))
    $arParams['GIFTS_PRODUCT_BLOCKS_ORDER'] = '';

if (empty($arParams['GIFTS_DETAIL_URL']))
    $arParams['GIFTS_DETAIL_URL'] = null;

$arParams['GIFTS_COLUMNS'] = ArrayHelper::fromRange([4, 5], $arParams['GIFTS_COLUMNS']);

CBitrixComponent::includeComponentClass('bitrix:sale.products.gift.basket');

?>
<div class="basket-gifts" data-entity="parent-container" data-showed="false" data-print="false">
    <?= Html::tag('div', $arParams['GIFTS_BLOCK_TITLE'], [
        'class' => 'basket-gifts-title',
        'data' => [
            'entity' => 'header',
            'showed' => 'false'
        ],
        'style' => [
            'display' => 'none',
            'opacity' => '0'
        ]
    ]) ?>
    <div class="basket-gifts-list">
        <?php $APPLICATION->IncludeComponent(
            'bitrix:sale.products.gift.basket',
            'template.1', [
                'SHOW_PRICE_COUNT' => 1,
                'PRODUCT_SUBSCRIPTION' => 'N',
                'PRODUCT_ID_VARIABLE' => 'id',
                'USE_PRODUCT_QUANTITY' => 'N',
                'ACTION_VARIABLE' => 'actionGift',
                'ADD_PROPERTIES_TO_BASKET' => 'Y',
                'PARTIAL_PRODUCT_PROPERTIES' => 'Y',
                'BASKET_URL' => $APPLICATION->GetCurPage(),
                'APPLIED_DISCOUNT_LIST' => $arResult['APPLIED_DISCOUNT_LIST'],
                'FULL_DISCOUNT_LIST' => $arResult['FULL_DISCOUNT_LIST'],
                'PRICE_VAT_INCLUDE' => $arParams['PRICE_VAT_SHOW_VALUE'],
                'CACHE_GROUPS' => $arParams['CACHE_GROUPS'],
                'BLOCK_TITLE' => $arParams['GIFTS_BLOCK_TITLE'],
                'HIDE_BLOCK_TITLE' => $arParams['GIFTS_HIDE_BLOCK_TITLE'],
                'TEXT_LABEL_GIFT' => $arParams['GIFTS_TEXT_LABEL_GIFT'],
                'DETAIL_URL' => $arParams['GIFTS_DETAIL_URL'],
                'PRODUCT_QUANTITY_VARIABLE' => $arParams['GIFTS_PRODUCT_QUANTITY_VARIABLE'],
                'PRODUCT_PROPS_VARIABLE' => $arParams['GIFTS_PRODUCT_PROPS_VARIABLE'],
                'SHOW_OLD_PRICE' => $arParams['GIFTS_SHOW_OLD_PRICE'],
                'SHOW_DISCOUNT_PERCENT' => $arParams['GIFTS_SHOW_DISCOUNT_PERCENT'],
                'CONVERT_CURRENCY' => $arParams['GIFTS_CONVERT_CURRENCY'],
                'HIDE_NOT_AVAILABLE' => $arParams['GIFTS_HIDE_NOT_AVAILABLE'],
                'PRODUCT_ROW_VARIANTS' => '',
                'PAGE_ELEMENT_COUNT' => 0,
                'COLUMNS' => $arParams['GIFTS_COLUMNS'],
                'DEFERRED_PAGE_ELEMENT_COUNT' => $arParams['GIFTS_PAGE_ELEMENT_COUNT'],
                'ADD_TO_BASKET_ACTION' => 'BUY',
                'PRODUCT_BLOCKS_ORDER' => $arParams['GIFTS_PRODUCT_BLOCKS_ORDER']
            ],
            $component
        ) ?>
    </div>
</div>