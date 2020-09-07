<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die() ?>
<?php

use intec\core\helpers\ArrayHelper;
use intec\core\helpers\Type;

/**
 * @var array $arParams
 * @var array $arResult
 */

$arCurrency = [
    'CONVERT' => ArrayHelper::getValue($arParams, 'CURRENCY_CONVERT') == 'Y',
    'VALUE' => ArrayHelper::getValue($arParams, 'CURRENCY_ID')
];

$arPrices = ArrayHelper::getValue($arParams, 'PRICE_CODE');

if (Type::isArrayable($arPrices)) {
    $arPrices = CIBlockPriceTools::GetCatalogPrices(0, $arPrices);
} else {
    $arPrices = [];
}

if (!CModule::IncludeModule('currency')) {
    $arCurrency['CONVERT'] = false;
    $arCurrency['VALUE'] = null;
} else {
    $arCurrency['VALUE'] = CCurrency::GetByID($arCurrency['VALUE']);

    if (empty($arCurrency['VALUE'])) {
        $arCurrency['CONVERT'] = false;
        $arCurrency['VALUE'] = null;
    }
}

$arResult['CURRENCY'] = $arCurrency;
$arResult['PRICES'] = $arPrices;