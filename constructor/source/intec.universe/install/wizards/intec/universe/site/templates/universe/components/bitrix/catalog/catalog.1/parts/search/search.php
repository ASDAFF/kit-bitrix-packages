<?php if (defined("B_PROLOG_INCLUDED") && B_PROLOG_INCLUDED === true) ?>
<?php

use intec\core\helpers\ArrayHelper;
use intec\core\helpers\StringHelper;

/**
 * @var array $arParams
 * @var array $arResult
 * @var array $arViews
 * @var array $arFilter
 * @var CBitrixComponent
 */

$arSearch = [
    'SHOW' => true,
    'TEMPLATE' => '.default',
    'PARAMETERS' => []
];

if ($arSearch['SHOW']) {
    $sListView = ArrayHelper::getValue($arParams, 'LIST_VIEW');
    $sListView = ArrayHelper::fromRange([
        'text',
        'list',
        'tile'
    ], $sListView);

    $sListPrefix = 'LIST_'.$sListView.'_';
    $sListPrefix = StringHelper::toUpperCase($sListPrefix);

    foreach ($arParams as $sKey => $mValue) {
        if (!StringHelper::startsWith($sKey, $sListPrefix)) {
            if (StringHelper::startsWith($sKey, 'LIST_')) {
                $sKey = StringHelper::cut($sKey, 5);
            } else if (!StringHelper::startsWith($sKey, 'QUICK_VIEW_')) {
                continue;
            }
        } else {
            $sKey = StringHelper::cut(
                $sKey,
                StringHelper::length($sListPrefix)
            );
        }

        if ($sKey === 'TEMPLATE')
            $mValue = 'catalog.'.$mValue;

        $arSearch['PARAMETERS']['LIST_'.$sKey] = $mValue;
    }

    foreach ($arResult['PARAMETERS']['COMMON'] as $sProperty) {
        $arSearch['PARAMETERS']['LIST_' . $sProperty] = ArrayHelper::getValue($arParams, $sProperty);
        $arSearch['PARAMETERS']['LIST_QUICK_VIEW_'.$sProperty] = ArrayHelper::getValue($arParams, $sProperty);
    }

    $arSearch['PARAMETERS'] = ArrayHelper::merge($arSearch['PARAMETERS'], [
        'IBLOCK_TYPE' => $arParams['IBLOCK_TYPE'],
        'IBLOCK_ID' => $arParams['IBLOCK_ID'],
        'ELEMENT_SORT_FIELD' => $arParams['ELEMENT_SORT_FIELD'],
        'ELEMENT_SORT_ORDER' => $arParams['ELEMENT_SORT_ORDER'],
        'ELEMENT_SORT_FIELD2' => $arParams['ELEMENT_SORT_FIELD2'],
        'ELEMENT_SORT_ORDER2' => $arParams['ELEMENT_SORT_ORDER2'],
        'PAGE_ELEMENT_COUNT' => $arParams['PAGE_ELEMENT_COUNT'],
        'LINE_ELEMENT_COUNT' => $arParams['LINE_ELEMENT_COUNT'],
        'SECTION_URL' => $arParams['SECTION_URL'],
        'DETAIL_URL' => $arParams['DETAIL_URL'],
        'BASKET_URL' => $arParams['BASKET_URL'],
        'ACTION_VARIABLE' => (!empty($arParams['ACTION_VARIABLE']) ? $arParams['ACTION_VARIABLE'] : 'action').'_cscs',
        'PRODUCT_ID_VARIABLE' => $arParams['PRODUCT_ID_VARIABLE'],
        'PRODUCT_QUANTITY_VARIABLE' => $arParams['PRODUCT_QUANTITY_VARIABLE'],
        'PRODUCT_PROPS_VARIABLE' => $arParams['PRODUCT_PROPS_VARIABLE'],
        'SECTION_ID_VARIABLE' => $arParams['SECTION_ID_VARIABLE'],
        'CACHE_TYPE' => $arParams['CACHE_TYPE'],
        'CACHE_TIME' => $arParams['CACHE_TIME'],
        'DISPLAY_COMPARE' => (isset($arParams['USE_COMPARE']) ? $arParams['USE_COMPARE'] : ''),
        'PRICE_CODE' => $arParams['PRICE_CODE'],
        'USE_PRICE_COUNT' => $arParams['USE_PRICE_COUNT'],
        'SHOW_PRICE_COUNT' => $arParams['SHOW_PRICE_COUNT'],
        'PRICE_VAT_INCLUDE' => $arParams['PRICE_VAT_INCLUDE'],
        'ADD_PROPERTIES_TO_BASKET' => (isset($arParams['ADD_PROPERTIES_TO_BASKET']) ? $arParams['ADD_PROPERTIES_TO_BASKET'] : ''),
        'PARTIAL_PRODUCT_PROPERTIES' => (isset($arParams['PARTIAL_PRODUCT_PROPERTIES']) ? $arParams['PARTIAL_PRODUCT_PROPERTIES'] : ''),
        'CONVERT_CURRENCY' => $arParams['CONVERT_CURRENCY'],
        'CURRENCY_ID' => $arParams['CURRENCY_ID'],
        'HIDE_NOT_AVAILABLE' => $arParams['HIDE_NOT_AVAILABLE'],
        'DISPLAY_TOP_PAGER' => $arParams['DISPLAY_TOP_PAGER'],
        'DISPLAY_BOTTOM_PAGER' => $arParams['DISPLAY_BOTTOM_PAGER'],
        'PAGER_TITLE' => $arParams['PAGER_TITLE'],
        'PAGER_SHOW_ALWAYS' => $arParams['PAGER_SHOW_ALWAYS'],
        'PAGER_TEMPLATE' => $arParams['PAGER_TEMPLATE'],
        'PAGER_DESC_NUMBERING' => $arParams['PAGER_DESC_NUMBERING'],
        'PAGER_DESC_NUMBERING_CACHE_TIME' => $arParams['PAGER_DESC_NUMBERING_CACHE_TIME'],
        'PAGER_SHOW_ALL' => $arParams['PAGER_SHOW_ALL'],
        'FILTER_NAME' => 'searchFilter',
        'SECTION_ID' => '',
        'SECTION_CODE' => '',
        'SECTION_USER_FIELDS' => array(),
        'INCLUDE_SUBSECTIONS' => 'Y',
        'SHOW_ALL_WO_SECTION' => 'Y',
        'META_KEYWORDS' => '',
        'META_DESCRIPTION' => '',
        'BROWSER_TITLE' => '',
        'ADD_SECTIONS_CHAIN' => 'N',
        'SET_TITLE' => 'N',
        'SET_STATUS_404' => 'N',
        'CACHE_FILTER' => 'N',
        'CACHE_GROUPS' => 'N',

        'HIDE_NOT_AVAILABLE_OFFERS' => $arParams['HIDE_NOT_AVAILABLE_OFFERS'],
        'PROPERTY_CODE' => $arParams['LIST_PROPERTY_CODE'],
        'PRODUCT_DISPLAY_MODE' => 'Y',
        'PRODUCT_PROPERTIES' => $arParams['PRODUCT_PROPERTIES'],
        'OFFER_TREE_PROPS' => $arParams['LIST_OFFERS_PROPERTY_CODE'],
        'OFFERS_CART_PROPERTIES' => $arParams['OFFERS_CART_PROPERTIES'],
        'OFFERS_FIELD_CODE' => $arParams['LIST_OFFERS_FIELD_CODE'],
        'OFFERS_PROPERTY_CODE' => $arParams['LIST_OFFERS_PROPERTY_CODE'],
        'OFFERS_SORT_FIELD' => $arParams['OFFERS_SORT_FIELD'],
        'OFFERS_SORT_ORDER' => $arParams['OFFERS_SORT_ORDER'],
        'OFFERS_SORT_FIELD2' => $arParams['OFFERS_SORT_FIELD2'],
        'OFFERS_SORT_ORDER2' => $arParams['OFFERS_SORT_ORDER2'],
        'OFFERS_LIMIT' => $arParams['OFFERS_LIMIT'],

        'RESTART' => 'N',
        'NO_WORD_LOGIC' => 'Y',
        'USE_LANGUAGE_GUESS' => 'N',
        'CHECK_DATES' => 'Y',

        'USE_COMPARE' => $arParams['USE_COMPARE'],
        'COMPARE_NAME' => $arParams['COMPARE_NAME'],
        'COMPARE_PATH' => $arResult['FOLDER'].$arResult['URL_TEMPLATES']['compare'],

        'WIDE' => 'Y'
    ]);
}