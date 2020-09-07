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
    'NAME' => Loc::getMessage('C_CATALOG_SECTION_LIST_CATALOG_TILE_4_LAZYLOAD_USE'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'N'
];
$arTemplateParameters['COLUMNS'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_CATALOG_SECTION_LIST_CATALOG_TILE_4_COLUMNS'),
    'TYPE' => 'LIST',
    'VALUES' => [
        2 => '2',
        3 => '3',
        4 => '4',
    ],
    'DEFAULT' => 3
];
$arTemplateParameters['LINK_BLANK'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_CATALOG_SECTION_LIST_CATALOG_TILE_4_LINK_BLANK'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'N'
];
$arTemplateParameters['PICTURE_SHOW'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_CATALOG_SECTION_LIST_CATALOG_TILE_4_PICTURE_SHOW'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'N',
    'REFRESH' => 'Y'
];

if ($arCurrentValues['PICTURE_SHOW'] === 'Y') {
    $arTemplateParameters['PICTURE_SIZE'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_CATALOG_SECTION_LIST_CATALOG_TILE_4_PICTURE_SIZE'),
        'TYPE' => 'LIST',
        'VALUES' => [
            'cover' => Loc::getMessage('C_CATALOG_SECTION_LIST_CATALOG_TILE_4_PICTURE_SIZE_COVER'),
            'contain' => Loc::getMessage('C_CATALOG_SECTION_LIST_CATALOG_TILE_4_PICTURE_SIZE_CONTAIN'),
        ],
        'DEFAULT' => 'cover'
    ];
}

$arTemplateParameters['CHILDREN_SHOW'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_CATALOG_SECTION_LIST_CATALOG_TILE_4_CHILDREN_SHOW'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'N',
    'REFRESH' => 'Y'
];

if ($arCurrentValues['CHILDREN_SHOW'] === 'Y') {
    $arTemplateParameters['CHILDREN_VIEW'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_CATALOG_SECTION_LIST_CATALOG_TILE_4_CHILDREN_VIEW'),
        'TYPE' => 'LIST',
        'VALUES' => [
            1 => Loc::getMessage('C_CATALOG_SECTION_LIST_CATALOG_TILE_4_CHILDREN_VIEW_1'),
            2 => Loc::getMessage('C_CATALOG_SECTION_LIST_CATALOG_TILE_4_CHILDREN_VIEW_2')
        ],
        'DEFAULT' => 1
    ];
    $arTemplateParameters['CHILDREN_ELEMENTS'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_CATALOG_SECTION_LIST_CATALOG_TILE_4_CHILDREN_ELEMENTS'),
        'TYPE' => 'CHECKBOX',
        'DEFAULT' => 'N'
    ];
    $arTemplateParameters['CHILDREN_COUNT'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_CATALOG_SECTION_LIST_CATALOG_TILE_4_CHILDREN_COUNT'),
        'TYPE' => 'STRING',
        'DEFAULT' => '5'
    ];
}