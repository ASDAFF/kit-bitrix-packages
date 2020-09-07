<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

/** @var array $arCurrentValues */

use Bitrix\Main\Loader;
use intec\core\helpers\Type;

if (!Loader::includeModule('intec.startshop'))
    return;

/** Prices List */
$arPricesTypes = array();
$arPriceIDs = array();

$dbPricesTypes = CStartShopPrice::GetList(array('SORT' => 'ASC'));

while ($arPriceType = $dbPricesTypes->Fetch()) {
    $arPricesTypes[$arPriceType['CODE']] = '['.$arPriceType['CODE'].'] '.$arPriceType['LANG'][LANGUAGE_ID]['NAME'];
    $arPriceIDs[$arPriceType['ID']] = '['.$arPriceType['ID'].'] '.$arPriceType['LANG'][LANGUAGE_ID]['NAME'];
}
unset($dbPricesTypes, $arPriceType);

/** Currencies */
$arCurrencies = array();
$dbCurrencies = CStartShopCurrency::GetList();

while ($arCurrency = $dbCurrencies->Fetch())
    $arCurrencies[$arCurrency['CODE']] = '['.$arCurrency['CODE'].'] '.$arCurrency['LANG'][LANGUAGE_ID]['NAME'];
unset($dbCurrencies, $arCurrency);

$arTemplateParameters += array(
    'PRICE_CODE' => array(
        'PARENT' => 'PRICES',
        'NAME' => GetMessage('C_CATALOG_PRICE_CODE'),
        'TYPE' => 'LIST',
        'MULTIPLE' => 'Y',
        'VALUES' => $arPricesTypes
    ),
    'CONVERT_CURRENCY' => array(
        'PARENT' => 'PRICES',
        'NAME' => GetMessage('SH_C_USE_COMMON_CURRENCY'),
        'TYPE' => 'CHECKBOX',
        'DEFAULT' => 'N',
        'REFRESH' => 'Y'
    )
);
if ($arCurrentValues['CONVERT_CURRENCY'] == 'Y') {
    $arTemplateParameters['CURRENCY_ID'] = array(
        'PARENT' => 'PRICES',
        'NAME' => GetMessage('C_CATALOG_CURRENCY'),
        'TYPE' => 'LIST',
        'VALUES' => $arCurrencies
    );
}

