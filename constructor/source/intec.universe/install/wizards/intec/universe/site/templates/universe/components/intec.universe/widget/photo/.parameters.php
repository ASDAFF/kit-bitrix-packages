<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ModuleManager;

if (!CModule::IncludeModule('iblock'))
    return;

$arTemplateParameters = array();

$arIBlocksTypes = CIBlockParameters::GetIBlockTypes();
$sIBlockType = $arCurrentValues['IBLOCK_TYPE'];

$arIBlocks = array();
$arIBlocksFilter = array();
$arIBlocksFilter['ACTIVE'] = 'Y';

if (!empty($sIBlockType))
    $arIBlocksFilter['TYPE'] = $sIBlockType;

$rsIBlocks = CIBlock::GetList(array('SORT' => 'ASC'), $arIBlocksFilter);

while ($arIBlock = $rsIBlocks->Fetch())
    $arIBlocks[$arIBlock['ID']] = '['.$arIBlock['ID'].'] '.$arIBlock['NAME'];

$iIBlockId = (int)$arCurrentValues['IBLOCK_ID'];

$arTemplateParameters['IBLOCK_TYPE'] = array(
    'PARENT' => 'BASE',
    'TYPE' => 'LIST',
    'NAME' => GetMessage('N_L_GALLERY_IBLOCK_TYPE'),
    'VALUES' => $arIBlocksTypes,
    'ADDITIONAL_VALUES' => 'Y',
    'REFRESH' => 'Y'
);
$arTemplateParameters['IBLOCK_ID'] = array(
    'PARENT' => 'BASE',
    'TYPE' => 'LIST',
    'NAME' => GetMessage('N_L_GALLERY_IBLOCK_ID'),
    'VALUES' => $arIBlocks,
    'ADDITIONAL_VALUES' => 'Y'
);

if (!empty($iIBlockId)) {
    $arProperties = array();
    $arPropertiesBoolean = array();
    $rsProperties = CIBlockProperty::GetList(array('SORT' => 'ASC'), array(
        'IBLOCK_ID' => $iIBlockId
    ));

    while ($arProperty = $rsProperties->Fetch()) {
        if (!empty($arProperty['CODE'])) {
            $sName = '[' . $arProperty['CODE'] . '] ' . $arProperty['NAME'];

            if ($arProperty['PROPERTY_TYPE'] == 'L' && $arProperty['LIST_TYPE'] == 'C')
                $arPropertiesBoolean[$arProperty['CODE']] = $sName;
        }

        $arProperties[$arProperty['CODE']] = $arProperty;
    }

}

if (!Loader::includeModule('iblock'))
    return;

/**
 * @var array $arCurrentValues
 */

$arTemplateParameters['SETTINGS_USE'] = [
    'PARENT' => 'BASE',
    'NAME' => Loc::getMessage('N_L_GALLERY_SETTINGS_USE'),
    'TYPE' => 'CHECKBOX'
];

$arTemplateParameters['LAZYLOAD_USE'] = [
    'PARENT' => 'BASE',
    'NAME' => Loc::getMessage('N_L_GALLERY_LAZYLOAD_USE'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'N'
];

$arTemplateParameters['SHOW_TITLE'] =  array(
        'PARENT' => 'VISUAL',
        'NAME' => GetMessage('N_L_GALLERY_SHOW_TITLE'),
        'TYPE' => 'CHECKBOX',
        'REFRESH' => 'Y'
    );
if($arCurrentValues["SHOW_TITLE"] == "Y"){
    $arTemplateParameters['ALIGHT_HEADER'] = array(
        'PARENT' => 'VISUAL',
        'NAME' => GetMessage('N_L_GALLERY_ALIGHT_HEADER'),
        'TYPE' => 'CHECKBOX'
    );
    $arTemplateParameters['TITLE'] = array(
        'PARENT' => 'VISUAL',
        'NAME' => GetMessage('N_L_GALLERY_TITLE'),
        'TYPE' => 'STRING',
    );
}

$arTemplateParameters['SHOW_DETAIL_LINK'] = array(
        'PARENT' => 'VISUAL',
        'NAME' => GetMessage('N_L_GALLERY_SHOW_DETAIL_LINK'),
        'TYPE' => 'CHECKBOX',
        'REFRESH' => 'Y'
);
if($arCurrentValues["SHOW_DETAIL_LINK"] == "Y"){
    $arTemplateParameters['LIST_URL'] = array(
        'PARENT' => 'VISUAL',
        'NAME' => GetMessage('N_L_GALLERY_LIST_URL'),
        'TYPE' => 'STRING',
    );
    $arTemplateParameters['DETAIL_LINK_TEXT'] = array(
        'PARENT' => 'VISUAL',
        'NAME' => GetMessage('N_L_GALLERY_DETAIL_LINK_TEXT'),
        'TYPE' => 'STRING',
        'DEFAULT' => GetMessage('N_L_GALLERY_DETAIL_LINK_TEXT_DEFAULT')
    );
}

$arTemplateParameters['USE_CAROUSEL'] = array(
        'PARENT' => 'VISUAL',
        'NAME' => GetMessage('N_L_GALLERY_USE_CAROUSEL'),
        'TYPE' => 'CHECKBOX',
        'REFRESH' => 'Y'
);
$arTemplateParameters['COLUMNS_COUNT'] = array(
        'PARENT' => 'VISUAL',
        'NAME' => GetMessage('N_L_GALLERY_COLUMNS_COUNT'),
        'TYPE' => 'STRING',
        'DEFAULT' => 4
);
$arTemplateParameters["ITEMS_LIMIT"] = array(
    'PARENT' => 'VISUAL',
    'NAME' => GetMessage('N_L_GALLERY_LIMIT'),
    'TYPE' => 'STRING',
    'DEFAULT' => 8
);

if ($arCurrentValues['USE_CAROUSEL'] == 'Y') {
    $arTemplateParameters['ROWS_COUNT'] = array(
        'PARENT' => 'VISUAL',
        'NAME' => GetMessage('N_L_GALLERY_ROWS_COUNT'),
        'TYPE' => 'STRING',
        'DEFAULT' => 1
    );
}