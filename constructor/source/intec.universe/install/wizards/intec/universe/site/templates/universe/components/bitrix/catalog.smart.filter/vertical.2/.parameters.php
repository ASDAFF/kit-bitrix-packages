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
    'NAME' => Loc::getMessage('C_CATALOG_SMART_FILTER_VERTICAL_2_COLLAPSED'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'N'
];

if (!empty($arPrices))
    $arTemplateParameters['PRICES_EXPANDED'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_CATALOG_SMART_FILTER_VERTICAL_2_PRICES_EXPANDED'),
        "TYPE" => "LIST",
        "MULTIPLE" => "Y",
        "VALUES" => $arPrices
    ];

$arTemplateParameters['TYPE_A_PRECISION'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_CATALOG_SMART_FILTER_VERTICAL_2_TYPE_A_PRECISION'),
    'TYPE' => 'STRING',
    'DEFAULT' => 2
];

$arTemplateParameters['TYPE_B_PRECISION'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_CATALOG_SMART_FILTER_VERTICAL_2_TYPE_B_PRECISION'),
    'TYPE' => 'STRING',
    'DEFAULT' => 2
];