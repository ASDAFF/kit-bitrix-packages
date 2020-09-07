<?php if (!defined('B_PROLOG_INCLUDED') && B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;

/**
 * @var array $arCurrentValues
 */

if (!Loader::includeModule('intec.core'))
    return;

$arTemplateParameters = [];

$arTemplateParameters['LAZYLOAD_USE'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_BITRIX_CATALOG_SECTION_LIST_SERVICES_TILE_2_LAZYLOAD_USE'),
    'TYPE' => 'CHECKBOX',
    'REFRESH' => 'Y'
];
$arTemplateParameters['COLUMNS'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_BITRIX_CATALOG_SECTION_LIST_SERVICES_TILE_2_COLUMNS'),
    'TYPE' => 'LIST',
    'VALUES' => [
        2 => 2,
        3 => 3,
        4 => 4
    ],
    'DEFAULT' => 3
];
$arTemplateParameters['PICTURE_TYPE'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_BITRIX_CATALOG_SECTION_LIST_SERVICES_TILE_2_PICTURE_TYPE'),
    'TYPE' => 'LIST',
    'VALUES' => [
        'square' => Loc::getMessage('C_BITRIX_CATALOG_SECTION_LIST_SERVICES_TILE_2_PICTURE_TYPE_SQUARE'),
        'round' => Loc::getMessage('C_BITRIX_CATALOG_SECTION_LIST_SERVICES_TILE_2_PICTURE_TYPE_ROUND')
    ],
    'DEFAULT' => 'square',
    'REFRESH' => 'Y'
];

if ($arCurrentValues['PICTURE_TYPE'] === 'square') {
    $arTemplateParameters['PICTURE_INDENTS'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_BITRIX_CATALOG_SECTION_LIST_SERVICES_TILE_2_PICTURE_INDENTS'),
        'TYPE' => 'CHECKBOX'
    ];
}

$arTemplateParameters['NAME_POSITION'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_BITRIX_CATALOG_SECTION_LIST_SERVICES_TILE_2_NAME_POSITION'),
    'TYPE' => 'LIST',
    'VALUES' => [
        'left' => Loc::getMessage('C_BITRIX_CATALOG_SECTION_LIST_SERVICES_TILE_2_POSITION_LEFT'),
        'center' => Loc::getMessage('C_BITRIX_CATALOG_SECTION_LIST_SERVICES_TILE_2_POSITION_CENTER'),
        'right' => Loc::getMessage('C_BITRIX_CATALOG_SECTION_LIST_SERVICES_TILE_2_POSITION_RIGHT')
    ],
    'DEFAULT' => 'center'
];
$arTemplateParameters['DESCRIPTION_SHOW'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_BITRIX_CATALOG_SECTION_LIST_SERVICES_TILE_2_DESCRIPTION_SHOW'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'Y',
    'REFRESH' => 'Y'
];

if ($arCurrentValues['DESCRIPTION_SHOW'] === 'Y') {
    $arTemplateParameters['DESCRIPTION_POSITION'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_BITRIX_CATALOG_SECTION_LIST_SERVICES_TILE_2_DESCRIPTION_POSITION'),
        'TYPE' => 'LIST',
        'VALUES' => [
            'left' => Loc::getMessage('C_BITRIX_CATALOG_SECTION_LIST_SERVICES_TILE_2_POSITION_LEFT'),
            'center' => Loc::getMessage('C_BITRIX_CATALOG_SECTION_LIST_SERVICES_TILE_2_POSITION_CENTER'),
            'right' => Loc::getMessage('C_BITRIX_CATALOG_SECTION_LIST_SERVICES_TILE_2_POSITION_RIGHT')
        ],
        'DEFAULT' => 'center'
    ];
}