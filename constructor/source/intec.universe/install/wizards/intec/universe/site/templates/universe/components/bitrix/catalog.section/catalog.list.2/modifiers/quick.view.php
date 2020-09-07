<?php if (defined("B_PROLOG_INCLUDED") && B_PROLOG_INCLUDED === true) ?>
    <?php

use intec\core\helpers\StringHelper;

$arQuickView = [];
$arQuickView['USE'] = $arParams['QUICK_VIEW_USE'] === 'Y';
$arQuickView['DETAIL'] = $arQuickView['USE'] && $arParams['QUICK_VIEW_DETAIL'] === 'Y';
$arQuickView['PREFIX'] = 'QUICK_VIEW_';
$arQuickView['TEMPLATE'] = $arParams['QUICK_VIEW_TEMPLATE'];
$arQuickView['PARAMETERS'] = [
    'IBLOCK_TYPE' => $arParams['IBLOCK_TYPE'],
    'IBLOCK_ID' => $arParams['IBLOCK_ID'],
    'SECTION_URL' => $arParams['SECTION_URL'],
    'DETAIL_URL' => $arParams['DETAIL_URL'],
    'BASKET_URL' => $arParams['BASKET_URL'],
    'ACTION_VARIABLE' => null,
    'PRODUCT_ID_VARIABLE' => null,
    'SECTION_ID_VARIABLE' => null,
    'PRODUCT_QUANTITY_VARIABLE' => null,
    'PRODUCT_PROPS_VARIABLE' => null,
    'CACHE_TYPE' => $arParams['CACHE_TYPE'],
    'CACHE_TIME' => $arParams['CACHE_TIME'],
    'CACHE_GROUPS' => $arParams['CACHE_GROUPS'],
    'SET_TITLE' => 'N',
    'SET_LAST_MODIFIED' => 'N',
    'MESSAGE_404' => null,
    'SET_STATUS_404' => 'N',
    'PRICE_CODE' => $arParams['PRICE_CODE'],
    'USE_PRICE_COUNT' => $arParams['USE_PRICE_COUNT'] ? 'Y' : 'N',
    'SHOW_PRICE_COUNT' => $arParams['~SHOW_PRICE_COUNT'],
    'PRICE_VAT_INCLUDE' => $arParams['PRICE_VAT_INCLUDE'] ? 'Y' : 'N',
    'PRICE_VAT_SHOW_VALUE' => $arParams['PRICE_VAT_SHOW_VALUE'],
    'ADD_PROPERTIES_TO_BASKET' => $arParams['ADD_PROPERTIES_TO_BASKET'],
    'PARTIAL_PRODUCT_PROPERTIES' => $arParams['PARTIAL_PRODUCT_PROPERTIES'],
    'LINK_IBLOCK_TYPE' => $arParams['LINK_IBLOCK_TYPE'],
    'LINK_IBLOCK_ID' => $arParams['LINK_IBLOCK_ID'],
    'LINK_PROPERTY_SID' => $arParams['LINK_PROPERTY_SID'],
    'LINK_ELEMENTS_URL' => $arParams['LINK_ELEMENTS_URL'],
    'USE_MAIN_ELEMENT_SECTION' => $arParams['USE_MAIN_ELEMENT_SECTION'] ? 'Y' : 'N',
    'DISABLE_INIT_JS_IN_COMPONENT' => $arParams['DISABLE_INIT_JS_IN_COMPONENT'],
    'SET_VIEWED_IN_COMPONENT' => 'Y',

    'HIDE_NOT_AVAILABLE_OFFERS' => $arParams['HIDE_NOT_AVAILABLE_OFFERS'],
    'PRODUCT_DISPLAY_MODE' => 'Y',
    'PRODUCT_PROPERTIES' => $arParams['PRODUCT_PROPERTIES'],
    'OFFER_TREE_PROPS' => $arParams['OFFERS_PROPERTY_CODE'],
    'OFFERS_CART_PROPERTIES' => $arParams['OFFERS_CART_PROPERTIES'],
    'OFFERS_FIELD_CODE' => $arParams['OFFERS_FIELD_CODE'],
    'OFFERS_PROPERTY_CODE' => $arParams['OFFERS_PROPERTY_CODE'],
    'OFFERS_SORT_FIELD' => $arParams['OFFERS_SORT_FIELD'],
    'OFFERS_SORT_ORDER' => $arParams['OFFERS_SORT_ORDER'],
    'OFFERS_SORT_FIELD2' => $arParams['OFFERS_SORT_FIELD2'],
    'OFFERS_SORT_ORDER2' => $arParams['OFFERS_SORT_ORDER2'],

    'CURRENCY_ID' => $arParams['CURRENCY_ID'],
    'CONVERT_CURRENCY' => $arParams['CONVERT_CURRENCY'],

    'USE_COMPARE' => $arParams['USE_COMPARE'],
    'COMPARE_PATH' => $arParams['COMPARE_PATH'],
    'COMPARE_NAME' => $arParams['COMPARE_NAME']
];

foreach ($arParams as $sKey => $sValue) {
    if (!StringHelper::startsWith($sKey, $arQuickView['PREFIX']))
        continue;

    $sKey = StringHelper::cut($sKey, StringHelper::length($arQuickView['PREFIX']));
    $arQuickView['PARAMETERS'][$sKey] = $sValue;
}

if (!empty($arQuickView['TEMPLATE'])) {
    $arQuickView['TEMPLATE'] = 'quick.view.'.$arQuickView['TEMPLATE'];
} else {
    $arQuickView['USE'] = false;
}

$arResult['QUICK_VIEW'] = $arQuickView;

unset($arQuickView);