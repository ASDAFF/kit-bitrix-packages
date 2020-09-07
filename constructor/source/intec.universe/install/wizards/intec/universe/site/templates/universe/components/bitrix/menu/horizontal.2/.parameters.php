<?php if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;

/**
 * @var array $arCurrentValues;
 */

$arTemplateParameters['SECTION_VIEW'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_MENU_HORIZONTAL_2_SECTION_VIEW'),
    'TYPE' => 'LIST',
    'VALUES' => [
        'default' => Loc::getMessage('C_MENU_HORIZONTAL_2_SECTION_VIEW_DEFAULT'),
        'images' => Loc::getMessage('C_MENU_HORIZONTAL_2_SECTION_VIEW_IMAGES')
    ],
    'REFRESH' => 'Y'
];

$arTemplateParameters['SECTION_COLUMNS_COUNT'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_MENU_HORIZONTAL_2_SECTION_COLUMNS_COUNT'),
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
        'NAME' => Loc::getMessage('C_MENU_HORIZONTAL_2_SECTION_ITEMS_COUNT'),
        'TYPE' => 'STRING',
        'DEFAULT' => 3
    ];
}

$arTemplateParameters['CATALOG_LINKS'] = [
    'NAME' => Loc::getMessage('C_MENU_HORIZONTAL_2_CATALOG_LINKS'),
    'PARENT' => 'VISUAL',
    'TYPE' => 'STRING',
    'MULTIPLE' => 'Y'
];