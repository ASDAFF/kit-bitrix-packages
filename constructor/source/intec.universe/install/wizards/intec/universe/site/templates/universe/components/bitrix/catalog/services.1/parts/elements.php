<?php if (defined("B_PROLOG_INCLUDED") && B_PROLOG_INCLUDED === true) ?>
<?php

use Bitrix\Main\Data\Cache;
use intec\core\helpers\ArrayHelper;
use intec\core\helpers\StringHelper;
use intec\core\helpers\Type;

/**
 * @var array $arParams
 * @var array $arResult
 * @var string $sLevel
 */

$sPrefix = 'LIST_'.$sLevel.'_';
$arElements = [
    'SHOW' => false,
    'PARAMETERS' => []
];

$arElements['POSITION'] = ArrayHelper::fromRange([
    'top',
    'bottom'
], $arElements['POSITION']);

$oCache = Cache::createInstance();
$arElementsFilter = null;

if (isset($GLOBALS[$arParams['FILTER_NAME']]))
    $arElementsFilter = $GLOBALS[$arParams['FILTER_NAME']];

if (!Type::isArray($arElementsFilter))
    $arElementsFilter = [];

$arElementsFilter['ACTIVE'] = 'Y';
$arElementsFilter['ACTIVE_DATE'] = 'Y';

if (!empty($arResult['IBLOCK']))
    $arElementsFilter['IBLOCK_ID'] = $arResult['IBLOCK']['ID'];

if ($sLevel === 'ROOT') {
    $arElementsFilter['SECTION_ID'] = false;
} else if (!empty($arResult['SECTION'])) {
    $arElementsFilter['SECTION_ID'] = $arResult['SECTION']['ID'];
} else {
    $arElementsFilter = null;
}

if ($sLevel !== 'ROOT' && $arElementsFilter !== null) {
    if ($arParams['INCLUDE_SUBSECTIONS'] === 'Y' || $arParams['INCLUDE_SUBSECTIONS'] === 'A')
        $arElementsFilter['INCLUDE_SUBSECTIONS'] = 'Y';

    if ($arParams['INCLUDE_SUBSECTIONS'] === 'A')
        $arElementsFilter['SECTION_GLOBAL_ACTIVE'] = 'Y';
}

if ($oCache->initCache(36000, 'ELEMENTS'.serialize($arElementsFilter), '/iblock/services')) {
    $arElements = $oCache->getVars();
} else if ($oCache->startDataCache()) {
    if ($arElementsFilter !== null) {
        $rsElements = CIBlockElement::GetList(['SORT' => 'ASC'], $arElementsFilter, false, false);
        $arElements['SHOW'] = $rsElements->Fetch();
        $arElements['SHOW'] = !empty($arElements['SHOW']);

        unset($rsElements);
    } else {
        $arElements['SHOW'] = false;
    }

    $oCache->endDataCache($arElements);
}

unset($arElementsFilter);

$arElements['TEMPLATE'] = ArrayHelper::getValue($arParams, $sPrefix.'TEMPLATE');
$arElements['POSITION'] = ArrayHelper::getValue($arParams, $sPrefix.'POSITION');

if (empty($arElements['TEMPLATE']))
    $arElements['SHOW'] = false;

if ($arElements['SHOW'] || !empty($arElements['TEMPLATE'])) {
    $arElements['TEMPLATE'] = 'services.'.$arElements['TEMPLATE'];

    foreach ($arParams as $sKey => $mValue) {
        if (!StringHelper::startsWith($sKey, $sPrefix))
            continue;

        $sKey = StringHelper::cut(
            $sKey,
            StringHelper::length($sPrefix)
        );

        if ($sKey === 'TEMPLATE')
            continue;

        $arElements['PARAMETERS'][$sKey] = $mValue;
    }

    $arElements['PARAMETERS'] = ArrayHelper::merge($arElements['PARAMETERS'], [
        'IBLOCK_TYPE' => $arParams['IBLOCK_TYPE'],
        'IBLOCK_ID' => $arParams['IBLOCK_ID'],
        'ELEMENT_SORT_FIELD' => $arParams['ELEMENT_SORT_FIELD'],
        'ELEMENT_SORT_ORDER' => $arParams['ELEMENT_SORT_ORDER'],
        'ELEMENT_SORT_FIELD2' => $arParams['ELEMENT_SORT_FIELD2'],
        'ELEMENT_SORT_ORDER2' => $arParams['ELEMENT_SORT_ORDER2'],
        'PROPERTY_CODE' => $arParams['LIST_PROPERTY_CODE'],
        'META_KEYWORDS' => $arParams['LIST_META_KEYWORDS'],
        'META_DESCRIPTION' => $arParams['LIST_META_DESCRIPTION'],
        'BROWSER_TITLE' => $arParams['LIST_BROWSER_TITLE'],
        'INCLUDE_SUBSECTIONS' => $arParams['INCLUDE_SUBSECTIONS'],
        'ACTION_VARIABLE' => $arParams['ACTION_VARIABLE'],
        'PRODUCT_ID_VARIABLE' => $arParams['PRODUCT_ID_VARIABLE'],
        'SECTION_ID_VARIABLE' => $arParams['SECTION_ID_VARIABLE'],
        'PRODUCT_QUANTITY_VARIABLE' => $arParams['PRODUCT_QUANTITY_VARIABLE'],
        'PRODUCT_PROPS_VARIABLE' => $arParams['PRODUCT_PROPS_VARIABLE'],
        'FILTER_NAME' => $arParams['FILTER_NAME'],
        'CACHE_TYPE' => $arParams['CACHE_TYPE'],
        'CACHE_TIME' => $arParams['CACHE_TIME'],
        'CACHE_FILTER' => $arParams['CACHE_FILTER'],
        'CACHE_GROUPS' => $arParams['CACHE_GROUPS'],
        'SET_TITLE' => $arParams['SET_TITLE'],
        'MESSAGE_404' => $arParams['MESSAGE_404'],
        'SET_STATUS_404' => $arParams['SET_STATUS_404'],
        'SHOW_404' => $arParams['SHOW_404'],
        'FILE_404' => $arParams['FILE_404'],
        'PAGE_ELEMENT_COUNT' => $arParams['PAGE_ELEMENT_COUNT'],
        'LINE_ELEMENT_COUNT' => 3,
        'USE_MAIN_ELEMENT_SECTION' => $arParams['USE_MAIN_ELEMENT_SECTION'],

        'DISPLAY_TOP_PAGER' => $arParams['DISPLAY_TOP_PAGER'],
        'DISPLAY_BOTTOM_PAGER' => $arParams['DISPLAY_BOTTOM_PAGER'],
        'PAGER_TITLE' => $arParams['PAGER_TITLE'],
        'PAGER_SHOW_ALWAYS' => $arParams['PAGER_SHOW_ALWAYS'],
        'PAGER_TEMPLATE' => $arParams['PAGER_TEMPLATE'],
        'PAGER_DESC_NUMBERING' => $arParams['PAGER_DESC_NUMBERING'],
        'PAGER_DESC_NUMBERING_CACHE_TIME' => $arParams['PAGER_DESC_NUMBERING_CACHE_TIME'],
        'PAGER_SHOW_ALL' => $arParams['PAGER_SHOW_ALL'],

        'PRODUCT_DISPLAY_MODE' => 'N',

        'SECTION_ID' => !empty($arResult['VARIABLES']['SECTION_ID']) ? $arResult['VARIABLES']['SECTION_ID'] : null,
        'SECTION_CODE' => !empty($arResult['VARIABLES']['SECTION_CODE']) ? $arResult['VARIABLES']['SECTION_CODE'] : null,
        'SECTION_URL' => $arResult['FOLDER'].$arResult['URL_TEMPLATES']['section'],
        'DETAIL_URL' => $arResult['FOLDER'].$arResult['URL_TEMPLATES']['element'],

        'HIDE_NOT_AVAILABLE' => 'N',

        'USE_COMPARE' => 'N',

        'WIDE' => 'Y'
    ]);
}