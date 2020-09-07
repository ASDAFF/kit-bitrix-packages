<?php if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
<?php
use intec\core\helpers\ArrayHelper;
use intec\core\helpers\StringHelper;
use intec\core\helpers\RegExp;
use intec\core\helpers\Type;

/**
 * @var array $arParams
 * @var array $arResult
 * @var CBitrixComponentTemplate $this
 */

if (!CModule::IncludeModule('intec.core'))
    return;

$sIBlockType = ArrayHelper::getValue($arParams, 'IBLOCK_TYPE');
$iIBlockId = ArrayHelper::getValue($arParams, 'IBLOCK_ID');
$arSections = array();

if (!empty($sIBlockType) && !empty($iIBlockId)) {
    $rsSections = CIBlockSection::GetList(array(
        'SORT' => 'ASC'
    ), array(
        'ACTIVE' => 'Y',
        'SECTION_ID' => false,
        'IBLOCK_TYPE' => $sIBlockType,
        'IBLOCK_ID' => $iIBlockId
    ));

    while ($arSection = $rsSections->Fetch()) {
        $arSection['ITEMS'] = array();
        $arSections[$arSection['ID']] = $arSection;
    }
}

foreach ($arResult['ITEMS'] as &$arItem) {
    if (ArrayHelper::keyExists($arItem['IBLOCK_SECTION_ID'], $arSections)) {
        $arSections[$arItem['IBLOCK_SECTION_ID']]['ITEMS'][] = $arItem;
    }
}

$arResult['SECTIONS'] = $arSections;