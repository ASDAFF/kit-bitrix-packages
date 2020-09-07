<?php if (defined("B_PROLOG_INCLUDED") && B_PROLOG_INCLUDED === true) ?>
<?php

use intec\core\helpers\ArrayHelper;
use intec\core\helpers\StringHelper;

/**
 * @var array $arParams
 * @var array $arResult
 * @var string $sLevel
 */

$sPrefix = 'SECTIONS_'.$sLevel.'_';
$arSections = [
    'SHOW' => true,
    'TEMPLATE' => ArrayHelper::getValue($arParams, $sPrefix.'TEMPLATE'),
    'PARAMETERS' => []
];

if (empty($arSections['TEMPLATE']))
    $arSections['SHOW'] = false;

if ($arSections['SHOW']) {
    $arSections['TEMPLATE'] = 'services.'.$arSections['TEMPLATE'];

    foreach ($arParams as $sKey => $mValue) {
        if (!StringHelper::startsWith($sKey, $sPrefix))
            continue;

        $sKey = StringHelper::cut(
            $sKey,
            StringHelper::length($sPrefix)
        );

        if ($sKey === 'TEMPLATE')
            continue;

        $arSections['PARAMETERS'][$sKey] = $mValue;
    }

    $arSections['PARAMETERS'] = ArrayHelper::merge($arSections['PARAMETERS'], [
        'IBLOCK_TYPE' => $arParams['IBLOCK_TYPE'],
        'IBLOCK_ID' => $arParams['IBLOCK_ID'],
        'SECTION_ID' => !empty($arResult['VARIABLES']['SECTION_ID']) ? $arResult['VARIABLES']['SECTION_ID'] : null,
        'SECTION_CODE' => !empty($arResult['VARIABLES']['SECTION_CODE']) ? $arResult['VARIABLES']['SECTION_CODE'] : null,
        'CACHE_TYPE' => $arParams['CACHE_TYPE'],
        'CACHE_TIME' => $arParams['CACHE_TIME'],
        'CACHE_GROUPS' => $arParams['CACHE_GROUPS'],
        'COUNT_ELEMENTS' => $arParams['SECTION_COUNT_ELEMENTS'],
        'TOP_DEPTH' => $arParams['SECTION_TOP_DEPTH'],
        'SECTION_URL' => $arResult['FOLDER'].$arResult['URL_TEMPLATES']['section'],
        'ADD_SECTIONS_CHAIN' => (isset($arParams['ADD_SECTIONS_CHAIN']) ? $arParams['ADD_SECTIONS_CHAIN'] : ''),
        'WIDE' => 'Y'
    ]);
}