<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die() ?>
<?php

use intec\core\collections\Arrays;
use intec\core\helpers\ArrayHelper;
use intec\core\helpers\Type;

/**
 * @var array $arParams
 * @var array $arResult
 * @var CBitrixComponent $component
 * @var CBitrixComponentTemplate $this
 * @global CMain $APPLICATION
 */

$this->setFrameMode(true);

if (!CModule::IncludeModule('iblock'))
    return;

if (!CModule::IncludeModule('intec.core'))
    return;

$arParams = ArrayHelper::merge([
    'SETTINGS_USE' => 'N',
    'LAZYLOAD_USE' => 'N',
    'IBLOCK_TYPE' => null,
    'IBLOCK_ID' => null,
    'PROPERTY_CATEGORY' => null,
    'ELEMENTS_COUNT' => null,
    'CACHE_TIME' => null,
    'CACHE_TYPE' => null,
    'CACHE_GROUPS' => null,
    'PRICE_CODE' => null,
    'BASKET_URL' => null,
    'MODE' => 'all'
], $arParams);

$arProperty = $arParams['PROPERTY_CATEGORY'];

if (empty($arParams['IBLOCK_ID']))
    return;

if (empty($arProperty))
    return;

$arProperty = CIBlockProperty::GetList([], [
    'IBLOCK_ID' => $arParams['IBLOCK_ID'],
    'CODE' => $arProperty
])->GetNext();

if (empty($arProperty))
    return;

$arParameters = ArrayHelper::merge([
    'FILTER_NAME' => 'arFilter'
], $arParams, [
    'SECTION_ID' => null,
    'SECTION_CODE' => null,
    'INCLUDE_SUBSECTIONS' => 'Y',
    'SHOW_ALL_WO_SECTION' => 'Y',
    'HIDE_NOT_AVAILABLE' => 'N',
    'HIDE_NOT_AVAILABLE_OFFERS' => 'N',
    'ELEMENT_SORT_FIELD' => 'SORT',
    'ELEMENT_SORT_ORDER' => 'ASC',
    'ELEMENT_SORT_FIELD2' => 'ID',
    'ELEMENT_SORT_ORDER2' => 'DESC',
    'OFFERS_SORT_FIELD' => 'SORT',
    'OFFERS_SORT_ORDER' => 'ASC',
    'OFFERS_SORT_FIELD2' => 'ID',
    'OFFERS_SORT_ORDER2' => 'DESC',
    'PAGE_ELEMENT_COUNT' => $arParams['ELEMENTS_COUNT'],
    'PROPERTY_SECTION' => $arProperty['CODE'],
    'PROPERTY_CODE' => [
        $arProperty['CODE']
    ],
    'OFFERS_FIELD_CODE' => [],
    'OFFER_TREE_PROPS' => $arParams['OFFERS_PROPERTY_CODE'],
    'SECTION_ID_VARIABLE' => null,
    'SEF_MODE' => 'N',
    'AJAX_MODE' => 'N',
    'SET_TITLE' => 'N',
    'SET_BROWSER_TITLE' => 'N',
    'SET_META_KEYWORDS' => 'N',
    'SET_META_DESCRIPTION' => 'N',
    'SET_LAST_MODIFIED' => 'N',
    'USE_MAIN_ELEMENT_SECTION' => 'N',
    'ADD_SECTIONS_CHAIN' => 'N',
    'CACHE_FILTER' => 'N',
    'ACTION_VARIABLE' => null,
    'PRODUCT_ID_VARIABLE' => null,
    'PRODUCT_PROPERTIES' => [],
    'DISPLAY_TOP_PAGER' => 'N',
    'DISPLAY_BOTTOM_PAGER' => 'N',
    'PAGER_SHOW_ALWAYS' => 'N',
    'PAGER_SHOW_ALL' => 'N',
    'PAGER_BASE_LINK_ENABLE' => 'N',
    'SET_STATUS_404' => 'N',
    'SHOW_404' => 'N',
    'COMPATIBLE_MODE' => 'Y',
    'DISABLE_INIT_JS_IN_COMPONENT' => 'Y',
    'PRODUCT_DISPLAY_MODE' => 'Y'
]);

if (empty($arParameters['FILTER_NAME']))
    $arParameters['FILTER_NAME'] = 'arFilter';

$arParameters['MODE'] = ArrayHelper::fromRange([
    'all',
    'categories',
    'category'
], $arParameters['MODE']);

$arFilter = ArrayHelper::getValue($GLOBALS, $arParameters['FILTER_NAME']);

if (!Type::isArray($arFilter))
    $arFilter = [];

$arPropertiesValues = Arrays::fromDBResult(CIBlockPropertyEnum::GetList([], [
    'PROPERTY_ID' => $arProperty['ID']
]), true, function ($arCategory) {
    if (empty($arCategory['XML_ID']))
        return null;

    return [
        'key' => $arCategory['XML_ID'],
        'value' => $arCategory
    ];
});

if ($arParameters['MODE'] === 'all') {
    $arFilter['!PROPERTY_'.$arProperty['CODE']] = false;
} else if ($arParameters['MODE'] === 'categories') {
    $arCategories = ArrayHelper::getValue($arParameters, 'CATEGORIES');

    if (!Type::isArrayable($arCategories))
        return;

    $arCategories = $arPropertiesValues->where(function ($sKey, $arPropertyValue) use ($arCategories) {
        return ArrayHelper::isIn($arPropertyValue['XML_ID'], $arCategories);
    })->asArray(function ($sKey, $arPropertyValue) {
        return [
            'value' => $arPropertyValue['ID']
        ];
    });

    if (empty($arCategories))
        return;

    $arFilter['PROPERTY_'.$arProperty['CODE']] = $arCategories;
} else if ($arParameters['MODE'] === 'category') {
    $arCategory = ArrayHelper::getValue($arParameters, 'CATEGORY');
    $arCategory = $arPropertiesValues->get($arCategory);

    if (empty($arCategory))
        return;

    $arFilter['PROPERTY_'.$arProperty['CODE']] = $arCategory['ID'];
}

$GLOBALS[$arParameters['FILTER_NAME']] = $arFilter;

$APPLICATION->IncludeComponent(
    'bitrix:catalog.section',
    '.default',
    $arParameters,
    $component
);
