<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die() ?>
<?php

use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;

$arPrices = array();
$rsPrices = CStartShopPrice::GetList(['SORT' => 'ASC'], ['ACTIVE'=>'Y']);

while($arPrice = $rsPrices->Fetch())
    $arPrices[$arPrice['CODE']] = '['.$arPrice['CODE'].'] '.$arPrice['LANG'][LANGUAGE_ID]['NAME'];

$arTemplateParameters['PRICE_CODE'] = [
    'PARENT' => 'PRICES',
    'NAME' => Loc::getMessage('C_SEARCH_TITLE_POPUP_1_PRICE_CODE'),
    'TYPE' => 'LIST',
    'MULTIPLE' => 'Y',
    'VALUES' => $arPrices
];

$arTemplateParameters['CURRENCY_CONVERT'] = [
    'PARENT' => 'PRICES',
    'NAME' => Loc::getMessage('C_SEARCH_TITLE_POPUP_1_CURRENCY_CONVERT'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'N',
    'REFRESH' => 'Y',
];

if ($arCurrentValues['CURRENCY_CONVERT'] == 'Y') {
    $arCurrencies = [];
    $rsCurrencies = CStartShopCurrency::GetList([], [
        'ACTIVE' => 'Y'
    ]);

    while ($arCurrency = $rsCurrencies->Fetch())
        $arCurrencies[$arCurrency['CODE']] = '['.$arCurrency['CODE'].'] '.$arCurrency['LANG'][LANGUAGE_ID]['NAME'];

    $arCurrency = CStartShopCurrency::GetBase()->Fetch();

    $arTemplateParameters['CURRENCY_ID'] = [
        'PARENT' => 'PRICES',
        'NAME' => Loc::getMessage('C_SEARCH_TITLE_POPUP_1_CURRENCY_ID'),
        'TYPE' => 'LIST',
        'VALUES' => $arCurrencies,
        'DEFAULT' => $arCurrency['CODE'],
        'ADDITIONAL_VALUES' => 'Y'
    ];
}