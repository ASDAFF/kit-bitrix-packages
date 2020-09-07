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
    $dbPrices = CStartShopPrice::GetList([], [
        'CODE' => $arPrices
    ]);

    $arPrices = [];

    while ($arPrice = $dbPrices->GetNext())
        $arPrices[] = $arPrice;
} else {
    $arPrices = [];
}

$arCurrency['VALUE'] = CStartShopCurrency::GetByCode($arCurrency['VALUE'])->GetNext();

if (empty($arCurrency['VALUE'])) {
    $arCurrency['CONVERT'] = false;
    $arCurrency['VALUE'] = null;
}

$arResult['CURRENCY'] = $arCurrency;
$arResult['PRICES'] = $arPrices;