<?php if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
<?php
use intec\core\helpers\ArrayHelper;
use intec\template\Properties;

/**
 * @var array $arParams
 * @var array $arResult
 * @var CBitrixComponentTemplate $this
 */

if (!CModule::IncludeModule('intec.core'))
    return;

$arParams = ArrayHelper::merge([
    'SETTINGS_USE' => 'N',
    'LAZYLOAD_USE' => 'N'
], $arParams);

if ($arParams['SETTINGS_USE'] === 'Y')
    include(__DIR__.'/modifiers/settings.php');

$arVisual = ArrayHelper::merge($arResult['VISUAL'], [
    'LAZYLOAD' => [
        'USE' => $arParams['LAZYLOAD_USE'] === 'Y',
        'STUB' => null
    ]
]);

if (defined('EDITOR'))
    $arVisual['LAZYLOAD']['USE'] = false;

if ($arVisual['LAZYLOAD']['USE'])
    $arVisual['LAZYLOAD']['STUB'] = Properties::get('template-images-lazyload-stub');

$arResult['VISUAL'] = $arVisual;

unset($arVisual);

$sIBlockType = ArrayHelper::getValue($arParams, 'IBLOCK_TYPE');
$iIBlockId = ArrayHelper::getValue($arParams, 'IBLOCK_ID');
$arSections = array();

$arProperties = array(
    'PROPERTY_POSITION' => 'POSITION',
    'PROPERTY_SKYPE' => 'SKYPE',
    'PROPERTY_EMAIL' => 'EMAIL',
    'PROPERTY_PHONE' => 'PHONE'
);
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
    $arItem['SYSTEM_PROPERTIES'] = [];

    foreach ($arProperties as $sPropertyKey => $sPropertyName) {
        $arItem['SYSTEM_PROPERTIES'][$sPropertyName] = null;

        $sPropertyParameter = ArrayHelper::getValue($arParams, $sPropertyKey);

        if (!empty($sPropertyParameter))
            if (ArrayHelper::keyExists($sPropertyParameter, $arItem['PROPERTIES']))
                $arItem['SYSTEM_PROPERTIES'][$sPropertyName] = ArrayHelper::getValue($arItem, ['PROPERTIES', $sPropertyParameter]);
    }

    if (ArrayHelper::keyExists($arItem['IBLOCK_SECTION_ID'], $arSections)) {
        $arSections[$arItem['IBLOCK_SECTION_ID']]['ITEMS'][] = $arItem;
    }
}
if($arParams["SHOW_ALL_STUFFS"] == "Y") {
    $arSectionAll = array("ID" => "ALL", "NAME" => GetMessage("TAB_ITEM_ALL"), "ITEMS" => $arResult['ITEMS']);
    array_unshift($arSections,$arSectionAll);
}

$arResult['SECTIONS'] = $arSections;