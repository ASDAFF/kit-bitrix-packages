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
$arTemplateParameters['COLUMNS'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('FILTER_TEMP_HORIZONTAL_COLUMNS'),
    'TYPE' => 'LIST',
    'VALUES' => [
        3 => '3',
        4 => '4'
    ],
    'DEFAULT' => 4
];
$arTemplateParameters['WIDE'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('FILTER_TEMP_HORIZONTAL_WIDE'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'N',
    'REFRESH' => 'Y'
];

if ($arCurrentValues['WIDE'] == 'Y') {
    $arTemplateParameters['WIDE_BACKGROUND'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('FILTER_TEMP_HORIZONTAL_WIDE_BACKGROUND'),
        'TYPE' => 'LIST',
        'VALUES' => [
            'dark' => Loc::getMessage('FILTER_TEMP_HORIZONTAL_WIDE_BACKGROUND_DARK'),
            'light' => Loc::getMessage('FILTER_TEMP_HORIZONTAL_WIDE_BACKGROUND_LIGHT')
        ],
        'DEFAULT' => 'dark'
    ];
}

$arTemplateParameters['COLLAPSED'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('FILTER_TEMP_HORIZONTAL_COLLAPSED'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'N'
];
if(!empty($arPrices))
    $arTemplateParameters['PRICES_EXPANDED'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('FILTER_TEMP_HORIZONTAL_PRICES_EXPANDED'),
        "TYPE" => "LIST",
        "MULTIPLE" => "Y",
        "VALUES" => $arPrices
    ];
$arTemplateParameters['BUTTONS_TOGGLE_TYPE'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('FILTER_TEMP_HORIZONTAL_BUTTONS_TOGGLE_TYPE'),
    'TYPE' => 'LIST',
    'VALUES' => [
        'text-arrow' => Loc::getMessage('FILTER_TEMP_HORIZONTAL_BUTTONS_TOGGLE_TYPE_TEXT_ARROW'),
        'arrow' => Loc::getMessage('FILTER_TEMP_HORIZONTAL_BUTTONS_TOGGLE_TYPE_ARROW')
    ],
    'DEFAULT' => 'text-arrow'
];
$arTemplateParameters['TYPE_F_BACKGROUND'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('FILTER_TEMP_HORIZONTAL_TYPE_F_BG'),
    'TYPE' => 'LIST',
    'VALUES' => [
        'light' => Loc::getMessage('FILTER_TEMP_HORIZONTAL_TYPE_F_BG_LIGHT'),
        'dark' => Loc::getMessage('FILTER_TEMP_HORIZONTAL_TYPE_F_BG_DARK'),
        'none' => Loc::getMessage('FILTER_TEMP_HORIZONTAL_TYPE_F_BG_NONE')
    ],
    'DEFAULT' => 'light'
];
$arTemplateParameters['TYPE_G_SIZE'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('FILTER_TEMP_HORIZONTAL_TYPE_G_SIZE'),
    'TYPE' => 'LIST',
    'VALUES' => [
        'default' => Loc::getMessage('FILTER_TEMP_HORIZONTAL_TYPE_G_SIZE_DEFAULT'),
        'big' => Loc::getMessage('FILTER_TEMP_HORIZONTAL_TYPE_G_SIZE_BIG')
    ],
    'DEFAULT' => 'default'
];
$arTemplateParameters['TYPE_A_PRECISION'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('FILTER_TEMP_HORIZONTAL_TYPE_A_PRECISION'),
    'TYPE' => 'STRING',
    'DEFAULT' => '2'
];