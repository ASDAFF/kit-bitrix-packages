<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use intec\core\collections\Arrays;

/**
 * @var array $arCurrentValues
 */

$bBase = false;
$bLite = false;
$arPrices = [];

if (Loader::includeModule('catalog') && Loader::includeModule('sale')) {
    $bBase = true;
} else if (Loader::includeModule('intec.startshop')) {
    $bLite = true;
}

if ($bBase) {
    $arPrices = CCatalogIBlockParameters::getPriceTypesList();
} else if ($bLite) {
    $arPrices = Arrays::fromDBResult(CStartShopPrice::GetList([], ['ACTIVE' => 'Y']))
        ->indexBy('ID')
        ->asArray(function ($sKey, $arPrice) {
            if (!empty($arPrice['CODE']))
                return [
                    'key' => $arPrice['CODE'],
                    'value' => '['.$arPrice['CODE'].'] '.$arPrice['LANG'][LANGUAGE_ID]['NAME']
                ];

            return ['skip' => true];
        });
}

$arTemplateParameters = [];

/** VISUAL */
$arTemplateParameters['COLLAPSED'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_CATALOG_SMART_FILTER_VERTICAL_1_COLLAPSED'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'N'
];
if(!empty($arPrices))
    $arTemplateParameters['PRICES_EXPANDED'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_CATALOG_SMART_FILTER_VERTICAL_1_PRICES_EXPANDED'),
        "TYPE" => "LIST",
        "MULTIPLE" => "Y",
        "VALUES" => $arPrices
    ];
$arTemplateParameters['TYPE_F_VIEW'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_CATALOG_SMART_FILTER_VERTICAL_1_TYPE_F_VIEW'),
    'TYPE' => 'LIST',
    'VALUES' => [
        'default' => Loc::getMessage('C_CATALOG_SMART_FILTER_VERTICAL_1_TYPE_F_VIEW_DEFAULT'),
        'block' => Loc::getMessage('C_CATALOG_SMART_FILTER_VERTICAL_1_TYPE_F_VIEW_BLOCK'),
        'tile' => Loc::getMessage('C_CATALOG_SMART_FILTER_VERTICAL_1_TYPE_F_VIEW_TILE')
    ],
    'DEFAULT' => 'default'
];
$arTemplateParameters['TYPE_G_VIEW'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_CATALOG_SMART_FILTER_VERTICAL_1_TYPE_G_VIEW'),
    'TYPE' => 'LIST',
    'VALUES' => [
        'default' => Loc::getMessage('C_CATALOG_SMART_FILTER_VERTICAL_1_TYPE_G_VIEW_DEFAULT'),
        'tile' => Loc::getMessage('C_CATALOG_SMART_FILTER_VERTICAL_1_TYPE_G_VIEW_TILE')
    ],
    'DEFAULT' => 'default',
    'REFRESH' => 'Y'
];

if ($arCurrentValues['TYPE_G_VIEW'] == 'default') {
    $arTemplateParameters['TYPE_G_SIZE'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_CATALOG_SMART_FILTER_VERTICAL_1_TYPE_G_SIZE'),
        'TYPE' => 'LIST',
        'VALUES' => [
            'default' => Loc::getMessage('C_CATALOG_SMART_FILTER_VERTICAL_1_TYPE_G_SIZE_DEFAULT'),
            'big' => Loc::getMessage('C_CATALOG_SMART_FILTER_VERTICAL_1_TYPE_G_SIZE_BIG')
        ],
        'DEFAULT' => 'default'
    ];
}

$arTemplateParameters['TYPE_A_PRECISION'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_CATALOG_SMART_FILTER_VERTICAL_1_TYPE_A_PRECISION'),
    'TYPE' => 'STRING',
    'DEFAULT' => 2
];