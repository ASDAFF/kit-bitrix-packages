<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die() ?>
<?php

use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use intec\core\collections\Arrays;

/**
 * @var array $arCurrentValues
 * @var string $componentName
 * @var string $componentTemplate
 * @var string $siteTemplate
 */

$bBase = false;
$bLite = false;

if (!Loader::includeModule('iblock'))
    return;

if (!Loader::includeModule('intec.core'))
    return;

if (Loader::includeModule('catalog') && Loader::includeModule('sale')) {
    $bBase = true;
} else if (Loader::includeModule('intec.startshop')) {
    $bLite = true;
}

$arTemplateParameters = [];

if ($bLite) {
    $arPriceCodes = Arrays::fromDBResult(CStartShopPrice::GetList())->indexBy('CODE');

    $hPriceCodes = function ($sKey, $arProperty) {
        if (!empty($arProperty['CODE']))
            return [
                'key' => $arProperty['CODE'],
                'value' => '['.$arProperty['CODE'].'] '.$arProperty['LANG'][LANGUAGE_ID]['NAME']
            ];

        return ['skip' => true];
    };

    $arTemplateParameters['PRICE_CODE'] = [
        'PARENT' => 'PRICES',
        'NAME' => Loc::getMessage('C_CATALOG_SECTION_PRODUCTS_SMALL_1_PRICE_CODE'),
        'TYPE' => 'LIST',
        'VALUES' => $arPriceCodes->asArray($hPriceCodes),
        'MULTIPLE' => 'Y',
        'ADDITIONAL_VALUES' => 'Y'
    ];
    $arTemplateParameters['CONVERT_CURRENCY'] = [
        'PARENT' => 'PRICES',
        'NAME' => Loc::getMessage('C_CATALOG_SECTION_PRODUCTS_SMALL_1_CONVERT_CURRENCY'),
        'TYPE' => 'CHECKBOX',
        'DEFAULT' => 'N',
        'REFRESH' => 'Y'
    ];

    if ($arCurrentValues['CONVERT_CURRENCY'] === 'Y') {
        $arCurrencies = Arrays::fromDBResult(CStartShopCurrency::GetList())->indexBy('CODE');

        $hCurrencies = function ($sKey, $arProperty) {
            if (!empty($arProperty['CODE']))
                return [
                    'key' => $arProperty['CODE'],
                    'value' => '['.$arProperty['CODE'].'] '.$arProperty['LANG'][LANGUAGE_ID]['NAME']
                ];

            return ['skip' => true];
        };

        $arTemplateParameters['CURRENCY_ID'] = [
            'PARENT' => 'PRICES',
            'NAME' => Loc::getMessage('C_CATALOG_SECTION_PRODUCTS_SMALL_1_CURRENCY_ID'),
            'TYPE' => 'LIST',
            'VALUES' => $arCurrencies->asArray($hCurrencies),
            'ADDITIONAL_VALUES' => 'Y'
        ];
    }
}

$arTemplateParameters['LAZYLOAD_USE'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_CATALOG_SECTION_PRODUCTS_SMALL_1_LAZYLOAD_USE'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'N'
];

$arTemplateParameters['BORDERS'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_CATALOG_SECTION_PRODUCTS_SMALL_1_BORDERS'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'Y'
];

$arTemplateParameters['COLUMNS'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_CATALOG_SECTION_PRODUCTS_SMALL_1_COLUMNS'),
    'TYPE' => 'LIST',
    'VALUES' => [
        2 => 2,
        3 => 3,
        4 => 4
    ],
    'DEFAULT' => 3
];

$arTemplateParameters['POSITION'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_CATALOG_SECTION_PRODUCTS_SMALL_1_POSITION'),
    'TYPE' => 'LIST',
    'VALUES' => [
        'left' => Loc::getMessage('C_CATALOG_SECTION_PRODUCTS_SMALL_1_POSITION_LEFT'),
        'center' => Loc::getMessage('C_CATALOG_SECTION_PRODUCTS_SMALL_1_POSITION_CENTER'),
        'right' => Loc::getMessage('C_CATALOG_SECTION_PRODUCTS_SMALL_1_POSITION_RIGHT')
    ],
    'DEFAULT' => 'left'
];

$arTemplateParameters['SIZE'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_CATALOG_SECTION_PRODUCTS_SMALL_1_SIZE'),
    'TYPE' => 'LIST',
    'VALUES' => [
        'small' => Loc::getMessage('C_CATALOG_SECTION_PRODUCTS_SMALL_1_SIZE_SMALL'),
        'big' => Loc::getMessage('C_CATALOG_SECTION_PRODUCTS_SMALL_1_SIZE_BIG')
    ],
    'DEFAULT' => 'big'
];

$arTemplateParameters['SLIDER_USE'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_CATALOG_SECTION_PRODUCTS_SMALL_1_SLIDER_USE'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'N',
    'REFRESH' => 'Y'
];

if ($arCurrentValues['SLIDER_USE'] === 'Y') {
    $arTemplateParameters['SLIDER_DOTS'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_CATALOG_SECTION_PRODUCTS_SMALL_1_SLIDER_DOTS'),
        'TYPE' => 'CHECKBOX',
        'DEFAULT' => 'N'
    ];

    $arTemplateParameters['SLIDER_NAVIGATION'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_CATALOG_SECTION_PRODUCTS_SMALL_1_SLIDER_NAVIGATION'),
        'TYPE' => 'CHECKBOX',
        'DEFAULT' => 'N'
    ];

    $arTemplateParameters['SLIDER_LOOP'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_CATALOG_SECTION_PRODUCTS_SMALL_1_SLIDER_LOOP'),
        'TYPE' => 'CHECKBOX',
        'DEFAULT' => 'N'
    ];

    $arTemplateParameters['SLIDER_AUTO_PLAY_USE'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_CATALOG_SECTION_PRODUCTS_SMALL_1_SLIDER_AUTO_PLAY_USE'),
        'TYPE' => 'CHECKBOX',
        'DEFAULT' => 'N',
        'REFRESH' => 'Y'
    ];

    if ($arCurrentValues['SLIDER_AUTO_PLAY_USE'] === 'Y') {
        $arTemplateParameters['SLIDER_AUTO_PLAY_TIME'] = [
            'PARENT' => 'VISUAL',
            'NAME' => Loc::getMessage('C_CATALOG_SECTION_PRODUCTS_SMALL_1_SLIDER_AUTO_PLAY_TIME'),
            'TYPE' => 'STRING',
            'DEFAULT' => 1000
        ];

        $arTemplateParameters['SLIDER_AUTO_PLAY_SPEED'] = [
            'PARENT' => 'VISUAL',
            'NAME' => Loc::getMessage('C_CATALOG_SECTION_PRODUCTS_SMALL_1_SLIDER_AUTO_PLAY_SPEED'),
            'TYPE' => 'STRING',
            'DEFAULT' => 500
        ];

        $arTemplateParameters['SLIDER_AUTO_PLAY_HOVER_PAUSE'] = [
            'PARENT' => 'VISUAL',
            'NAME' => Loc::getMessage('C_CATALOG_SECTION_PRODUCTS_SMALL_1_SLIDER_AUTO_PLAY_HOVER_PAUSE'),
            'TYPE' => 'CHECKBOX',
            'DEFAULT' => 'N'
        ];
    }
}