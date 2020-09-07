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
    'NAME' => Loc::getMessage('C_BITRIX_CATALOG_SECTION_LIST_CATALOG_TILE_1_LAZYLOAD_USE'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'Y'
];
$arTemplateParameters['BORDERS'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_BITRIX_CATALOG_SECTION_LIST_CATALOG_TILE_1_BORDERS'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'Y'
];
$arTemplateParameters['COLUMNS'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_BITRIX_CATALOG_SECTION_LIST_CATALOG_TILE_1_COLUMNS'),
    'TYPE' => 'LIST',
    'VALUES' => [
        1 => 1,
        2 => 2,
        3 => 3
    ],
    'DEFAULT' => 3
];
$arTemplateParameters['CHILDREN_SHOW'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_BITRIX_CATALOG_SECTION_LIST_CATALOG_TILE_1_CHILDREN_SHOW'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'Y',
    'REFRESH' => 'Y'
];

if ($arCurrentValues['CHILDREN_SHOW'] === 'Y') {
    $arTemplateParameters['CHILDREN_COUNT'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_BITRIX_CATALOG_SECTION_LIST_CATALOG_TILE_1_CHILDREN_COUNT'),
        'TYPE' => 'LIST',
        'VALUES' => [
            0 => Loc::getMessage('C_BITRIX_CATALOG_SECTION_LIST_CATALOG_TILE_1_CHILDREN_COUNT_UNLIMITED'),
            1 => 1,
            2 => 2,
            3 => 3,
            4 => 4
        ],
        'DEFAULT' => 3,
        'ADDITIONAL_VALUES' => 'Y'
    ];
}

$arTemplateParameters['PICTURE_SHOW'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_BITRIX_CATALOG_SECTION_LIST_CATALOG_TILE_1_PICTURE_SHOW'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'Y',
    'REFRESH' => 'Y'
];

if ($arCurrentValues['PICTURE_SHOW'] === 'Y') {
    $arTemplateParameters['PICTURE_SIZE'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_BITRIX_CATALOG_SECTION_LIST_CATALOG_TILE_1_PICTURE_SIZE'),
        'TYPE' => 'LIST',
        'VALUES' => [
            'small' => Loc::getMessage('C_BITRIX_CATALOG_SECTION_LIST_CATALOG_TILE_1_PICTURE_SIZE_SMALL'),
            'medium' => Loc::getMessage('C_BITRIX_CATALOG_SECTION_LIST_CATALOG_TILE_1_PICTURE_SIZE_MEDIUM'),
            'large' => Loc::getMessage('C_BITRIX_CATALOG_SECTION_LIST_CATALOG_TILE_1_PICTURE_SIZE_LARGE')
        ],
        'DEFAULT' => 'large'
    ];
}

$arTemplateParameters['DESCRIPTION_SHOW'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_BITRIX_CATALOG_SECTION_LIST_CATALOG_TILE_1_DESCRIPTION_SHOW'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'Y'
];