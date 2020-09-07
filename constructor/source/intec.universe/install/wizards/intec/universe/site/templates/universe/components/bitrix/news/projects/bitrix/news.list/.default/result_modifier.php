<?php if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die() ?>
<?php

use intec\core\helpers\ArrayHelper;
use intec\template\Properties;

/**
 * @var array $arParams
 * @var array $arResult
 */

if (!CModule::IncludeModule('iblock'))
    return;

if (!CModule::IncludeModule('intec.core'))
    return;

$arParams = ArrayHelper::merge([
    'LAZYLOAD_USE' => 'N',
], $arParams);

$arResult['LAZYLOAD'] = [
    'USE' => $arParams['LAZYLOAD_USE'] === 'Y',
    'STUB' => null
];

if (defined('EDITOR'))
    $arResult['LAZYLOAD']['USE'] = false;

if ($arResult['LAZYLOAD']['USE'])
    $arResult['LAZYLOAD']['STUB'] = Properties::get('template-images-lazyload-stub');

$arSections = array();

foreach ($arResult['ITEMS'] as $arItem) {
    $iSectionId = ArrayHelper::getValue($arItem, 'IBLOCK_SECTION_ID');

    if (!empty($iSectionId))
        if (!ArrayHelper::isIn($iSectionId, $arSections))
            $arSections[] = $iSectionId;
}

if (!empty($arSections)) {
    $rsSections = CIBlockSection::GetList(array('SORT' => 'ASC'), array(
        'ID' => $arSections
    ));

    $arSections = array();

    if ($arParams['DISPLAY_TAB_ALL'] == 'Y')
        $arSections[0] = array(
            'ID' => 0,
            'NAME' => GetMessage('N_PROJECTS_N_L_DEFAULT_TAB_ALL'),
            'ITEMS' => array()
        );

    while ($arSection = $rsSections->GetNext()) {
        $arSection['ITEMS'] = array();
        $arSections[$arSection['ID']] = $arSection;
    }
}

foreach ($arResult['ITEMS'] as &$arItem) {
    $arItem['IBLOCK_SECTION'] = null;
    $iSectionId = ArrayHelper::getValue($arItem, 'IBLOCK_SECTION_ID');

    if (!empty($iSectionId)) {
        $arSection = ArrayHelper::getValue($arSections, $iSectionId);

        if (!empty($arSection)) {
            $arItem['IBLOCK_SECTION'] = &$arSection;
            $arSections[$iSectionId]['ITEMS'][] = &$arItem;
        }
    }

    if ($arParams['DISPLAY_TAB_ALL'] == 'Y')
        $arSections[0]['ITEMS'][] = &$arItem;
}

$arResult['SECTIONS'] = $arSections;