<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die() ?>
<?php

use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;

$arPrices = array();
$rsPrices = CCatalogGroup::GetList($order = 'sort', $filter = 'asc');

while($arPrice = $rsPrices->Fetch())
    $arPrices[$arPrice['NAME']] = '['.$arPrice['NAME'].'] '.$arPrice['NAME_LANG'];

$arTemplateParameters['PRICE_CODE'] = [
    'PARENT' => 'PRICES',
    'NAME' => Loc::getMessage('C_SEARCH_TITLE_POPUP_1_PRICE_CODE'),
    'TYPE' => 'LIST',
    'MULTIPLE' => 'Y',
    'VALUES' => $arPrices
];

$arTemplateParameters['PRICE_VAT_INCLUDE'] = [
    'PARENT' => 'PRICES',
    'NAME' => Loc::getMessage('C_SEARCH_TITLE_POPUP_1_PRICE_VAT_INCLUDE'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'Y'
];

if (Loader::includeModule('currency')) {
    $arTemplateParameters['CURRENCY_CONVERT'] = [
        'PARENT' => 'PRICES',
        'NAME' => Loc::getMessage('C_SEARCH_TITLE_POPUP_1_CURRENCY_CONVERT'),
        'TYPE' => 'CHECKBOX',
        'DEFAULT' => 'N',
        'REFRESH' => 'Y',
    ];

    if ($arCurrentValues['CURRENCY_CONVERT'] == 'Y') {
        $arCurrencies = [];
        $rsCurrencies = CCurrency::GetList($by = 'SORT', $order = 'ASC');

        while ($arCurrency = $rsCurrencies->Fetch())
            $arCurrencies[$arCurrency['CURRENCY']] = $arCurrency['CURRENCY'];

        $arTemplateParameters['CURRENCY_ID'] = [
            'PARENT' => 'PRICES',
            'NAME' => Loc::getMessage('C_SEARCH_TITLE_POPUP_1_CURRENCY_ID'),
            'TYPE' => 'LIST',
            'VALUES' => $arCurrencies,
            'DEFAULT' => CCurrency::GetBaseCurrency(),
            'ADDITIONAL_VALUES' => 'Y'
        ];
    }
}