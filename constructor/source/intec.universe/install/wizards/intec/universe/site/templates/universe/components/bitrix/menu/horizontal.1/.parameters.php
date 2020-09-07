<?php if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;

/**
 * @var array $arCurrentValues;
 */

if (!CModule::IncludeModule('iblock'))
    return;

$arIBlocksTypes = CIBlockParameters::GetIBlockTypes();
$sIBlockType = $arCurrentValues['IBLOCK_TYPE'];

$arIBlocks = array();
$iIBlockId = $arCurrentValues['IBLOCK_ID'];
$arIBlocksFilter = array();
$arIBlocksFilter['ACTIVE'] = 'Y';

if (!empty($sIBlockType))
    $arIBlocksFilter['TYPE'] = $sIBlockType;

$rsIBlocks = CIBlock::GetList(['SORT' => 'ASC'], $arIBlocksFilter);

while ($arIBlock = $rsIBlocks->Fetch())
    $arIBlocks[$arIBlock['ID']] = '['.$arIBlock['ID'].'] '.$arIBlock['NAME'];

$arTemplateParameters = [];
$arTemplateParameters['IBLOCK_TYPE'] = [
    'PARENT' => 'BASE',
    'NAME' => Loc::getMessage('C_MENU_HORIZONTAL_1_IBLOCK_TYPE'),
    'TYPE' => 'LIST',
    'VALUES' => $arIBlocksTypes,
    'REFRESH' => 'Y',
    'ADDITIONAL_VALUES' => 'Y'
];

$arTemplateParameters['IBLOCK_ID'] = [
    'PARENT' => 'BASE',
    'NAME' => Loc::getMessage('C_MENU_HORIZONTAL_1_IBLOCK_ID'),
    'TYPE' => 'LIST',
    'VALUES' => $arIBlocks,
    'REFRESH' => 'Y',
    'ADDITIONAL_VALUES' => 'Y'
];

$arTemplateParameters['LAZYLOAD_USE'] = [
    'PARENT' => 'BASE',
    'NAME' => Loc::getMessage('C_MENU_HORIZONTAL_1_LAZYLOAD_USE'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'N'
];

$arTemplateParameters['UPPERCASE'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_MENU_HORIZONTAL_1_UPPERCASE'),
    'TYPE' => 'CHECKBOX',
    'REFRESH' => 'Y'
];

$arTemplateParameters['TRANSPARENT'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_MENU_HORIZONTAL_1_TRANSPARENT'),
    'TYPE' => 'CHECKBOX',
    'REFRESH' => 'Y'
];

if ($arCurrentValues['TRANSPARENT'] != 'Y') {
    $arTemplateParameters['DELIMITERS'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_MENU_HORIZONTAL_1_DELIMITERS'),
        'TYPE' => 'CHECKBOX'
    ];
}

if (!empty($iIBlockId)) {
    $arFields = array();
    $rsFields = CUserTypeEntity::GetList(['SORT' => 'ASC'], array(
        'ENTITY_ID' => 'IBLOCK_'.$iIBlockId.'_SECTION',
        'USER_TYPE_ID' => 'file'
    ));

    while ($arField = $rsFields->Fetch())
        $arFields[$arField['FIELD_NAME']] = $arField['FIELD_NAME'];

    $arTemplateParameters['PROPERTY_IMAGE'] = [
        'PARENT' => 'DATA_SOURCE',
        'NAME' => Loc::getMessage('C_MENU_HORIZONTAL_1_PROPERTY_IMAGE'),
        'TYPE' => 'LIST',
        'VALUES' => $arFields,
        'REFRESH' => 'Y',
        'ADDITIONAL_VALUES' => 'Y'
    ];
}

$arTemplateParameters['SECTION_VIEW'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_MENU_HORIZONTAL_1_SECTION_VIEW'),
    'TYPE' => 'LIST',
    'VALUES' => [
        'default' => Loc::getMessage('C_MENU_HORIZONTAL_1_SECTION_VIEW_DEFAULT'),
        'images' => Loc::getMessage('C_MENU_HORIZONTAL_1_SECTION_VIEW_IMAGES'),
        'information' => Loc::getMessage('C_MENU_HORIZONTAL_1_SECTION_VIEW_INFORMATION')
    ],
    'REFRESH' => 'Y'
];

$arTemplateParameters['SUBMENU_VIEW'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_MENU_HORIZONTAL_1_SUBMENU_VIEW'),
    'TYPE' => 'LIST',
    'VALUES' => [
        'simple.1' => Loc::getMessage('C_MENU_HORIZONTAL_1_SUBMENU_VIEW_SIMPLE_1'),
        'simple.2' => Loc::getMessage('C_MENU_HORIZONTAL_1_SUBMENU_VIEW_SIMPLE_2')
    ],
    'REFRESH' => 'Y'
];

$arTemplateParameters['SECTION_COLUMNS_COUNT'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_MENU_HORIZONTAL_1_SECTION_COLUMNS_COUNT'),
    'TYPE' => 'LIST',
    'VALUES' => [
        2 => 2,
        3 => 3,
        4 => 4
    ],
    'DEFAULT' => 3
];

if ($arCurrentValues['SECTION_VIEW'] == 'images') {
    $arTemplateParameters['SECTION_ITEMS_COUNT'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_MENU_HORIZONTAL_1_SECTION_ITEMS_COUNT'),
        'TYPE' => 'STRING',
        'DEFAULT' => 3
    ];
}

$arTemplateParameters['OVERLAY_USE'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_MENU_HORIZONTAL_1_OVERLAY_USE'),
    'TYPE' => 'CHECKBOX',
    'REFRESH' => 'Y'
];

$arTemplateParameters['CATALOG_LINKS'] = [
    'NAME' => Loc::getMessage('C_MENU_HORIZONTAL_1_CATALOG_LINKS'),
    'PARENT' => 'VISUAL',
    'TYPE' => 'STRING',
    'MULTIPLE' => 'Y'
];