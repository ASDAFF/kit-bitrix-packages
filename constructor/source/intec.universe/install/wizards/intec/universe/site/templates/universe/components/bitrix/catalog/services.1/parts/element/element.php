<?php if (defined("B_PROLOG_INCLUDED") && B_PROLOG_INCLUDED === true) ?>
<?php

use Bitrix\Main\Data\Cache;
use intec\Core;
use intec\core\helpers\ArrayHelper;
use intec\core\helpers\StringHelper;

/**
 * @var array $arParams
 * @var array $arResult
 * @var array $arViews
 * @var array $arFilter
 * @var CBitrixComponent
 */

$arElement = [
    'SHOW' => true,
    'TEMPLATE' => ArrayHelper::getValue($arParams, 'DETAIL_TEMPLATE'),
    'PARAMETERS' => []
];

if (empty($arElement['TEMPLATE']))
    $arElement['SHOW'] = false;

if ($arElement['SHOW']) {
    $sPrefix = 'DETAIL_';
    $arElement['TEMPLATE'] = 'services.'.$arElement['TEMPLATE'];

    foreach ($arParams as $sKey => $mValue) {
        if (StringHelper::startsWith($sKey, $sPrefix)) {
            $sKey = StringHelper::cut(
                $sKey,
                StringHelper::length($sPrefix)
            );

            if ($sKey === 'TEMPLATE')
                continue;

            $arElement['PARAMETERS'][$sKey] = $mValue;
        }
    }

    $arElement['PARAMETERS'] = ArrayHelper::merge($arElement['PARAMETERS'], [
        'IBLOCK_TYPE' => $arParams['IBLOCK_TYPE'],
        'IBLOCK_ID' => $arParams['IBLOCK_ID'],
        'PROPERTY_CODE' => $arParams['DETAIL_PROPERTY_CODE'],
        'META_KEYWORDS' => $arParams['DETAIL_META_KEYWORDS'],
        'META_DESCRIPTION' => $arParams['DETAIL_META_DESCRIPTION'],
        'BROWSER_TITLE' => $arParams['DETAIL_BROWSER_TITLE'],
        'SET_CANONICAL_URL' => $arParams['DETAIL_SET_CANONICAL_URL'],
        'ACTION_VARIABLE' => $arParams['ACTION_VARIABLE'],
        'PRODUCT_ID_VARIABLE' => $arParams['PRODUCT_ID_VARIABLE'],
        'SECTION_ID_VARIABLE' => $arParams['SECTION_ID_VARIABLE'],
        'CHECK_SECTION_ID_VARIABLE' => $arParams['DETAIL_CHECK_SECTION_ID_VARIABLE'],
        'PRODUCT_QUANTITY_VARIABLE' => $arParams['PRODUCT_QUANTITY_VARIABLE'],
        'PRODUCT_PROPS_VARIABLE' => $arParams['PRODUCT_PROPS_VARIABLE'],
        'CACHE_TYPE' => $arParams['CACHE_TYPE'],
        'CACHE_TIME' => $arParams['CACHE_TIME'],
        'CACHE_GROUPS' => $arParams['CACHE_GROUPS'],
        'SET_TITLE' => $arParams['SET_TITLE'],
        'SET_LAST_MODIFIED' => $arParams['SET_LAST_MODIFIED'],
        'MESSAGE_404' => $arParams['MESSAGE_404'],
        'SET_STATUS_404' => $arParams['SET_STATUS_404'],
        'SHOW_404' => $arParams['SHOW_404'],
        'FILE_404' => $arParams['FILE_404'],
        'USE_MAIN_ELEMENT_SECTION' => $arParams['USE_MAIN_ELEMENT_SECTION'],
        'STRICT_SECTION_CHECK' => $arParams['DETAIL_STRICT_SECTION_CHECK'],


        'ELEMENT_ID' => !empty($arResult['VARIABLES']['ELEMENT_ID']) ? $arResult['VARIABLES']['ELEMENT_ID'] : null,
        'ELEMENT_CODE' => !empty($arResult['VARIABLES']['ELEMENT_CODE']) ? $arResult['VARIABLES']['ELEMENT_CODE'] : null,
        'SECTION_ID' => !empty($arResult['VARIABLES']['SECTION_ID']) ? $arResult['VARIABLES']['SECTION_ID'] : null,
        'SECTION_CODE' => !empty($arResult['VARIABLES']['SECTION_CODE']) ? $arResult['VARIABLES']['SECTION_CODE'] : null,
        'SECTION_URL' => $arResult['FOLDER'].$arResult['URL_TEMPLATES']['section'],
        'DETAIL_URL' => $arResult['FOLDER'].$arResult['URL_TEMPLATES']['element'],

        'PRODUCT_DISPLAY_MODE' => 'N',
        'USE_COMPARE' => 'N',
        'USE_STORE' => 'N',

        'ADD_SECTIONS_CHAIN' => $arParams['ADD_SECTIONS_CHAIN'],
        'ADD_ELEMENT_CHAIN' => $arParams['ADD_ELEMENT_CHAIN'],

        'DISABLE_INIT_JS_IN_COMPONENT' => 'Y',
        'SET_VIEWED_IN_COMPONENT' => 'N',

        'WIDE' => 'Y'
    ]);
}