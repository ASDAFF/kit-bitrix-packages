<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die() ?>
<?php

use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;

/**
 * @var array $arCurrentValues
 */

if (!Loader::includeModule('iblock'))
    return;

if (!Loader::includeModule('intec.core') || !Loader::includeModule('sale'))
    return;

$arTemplateParameters = [];
$arTemplateParameters['LAZYLOAD_USE'] = [
    'PARENT' => 'BASE',
    'NAME' => Loc::getMessage('C_CATALOG_PRODUCTS_VIEWED_TILE_1_LAZYLOAD_USE'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'N'
];

$arTemplateParameters['TITLE_SHOW'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_CATALOG_PRODUCTS_VIEWED_TILE_1_TITLE_SHOW'),
    'TYPE' => 'CHECKBOX',
    'REFRESH' => 'Y',
    'DEFAULT' => 'N'
];

if ($arCurrentValues['TITLE_SHOW'] === 'Y') {
    $arTemplateParameters['TITLE'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_CATALOG_PRODUCTS_VIEWED_TILE_1_TITLE'),
        'TYPE' => 'STRING'
    ];
}

$arTemplateParameters['PAGE_ELEMENT_COUNT'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_CATALOG_PRODUCTS_VIEWED_TILE_1_ELEMENT_COUNT'),
    'TYPE' => 'STRING',
    'DEFAULT' => '10',
    'HIDDEN' => 'Y'
];

$arTemplateParameters['COLUMNS'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_CATALOG_PRODUCTS_VIEWED_TILE_1_COLUMNS'),
    'TYPE' => 'LIST',
    'VALUES' => [
        4 => '4',
        5 => '5',
        8 => '8',
        9 => '9'
    ],
    'DEFAULT' => 5
];

$arTemplateParameters['SHOW_NAVIGATION'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_CATALOG_PRODUCTS_VIEWED_TILE_1_SHOW_NAVIGATION'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'N'
];