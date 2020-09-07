<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die() ?>
<?php

/**
 * @var array $arParams
 * @var array $arResult
 * @var array $arItems
 * @var array $arItemsId
 */

$arAvailablePrices = [];
$arCurrency = $arResult['CURRENCY']['CONVERT'] ? $arResult['CURRENCY']['VALUE'] : null;

foreach ($arResult['PRICES'] as $arPrice)
    $arAvailablePrices[] = $arPrice['CODE'];

$arFilter = [
    'ID' => $arItemsId,
    'IBLOCK_LID' => SITE_ID,
    'IBLOCK_ACTIVE' => 'Y',
    'ACTIVE_DATE' => 'Y',
    'ACTIVE' => 'Y',
    'CHECK_PERMISSIONS' => 'Y',
    'MIN_PERMISSION' => 'R'
];

$rsItems = CStartShopCatalogProduct::GetList([], $arFilter, [], [],
    !empty($arCurrency) ? $arCurrency['CODE'] : false,
    $arAvailablePrices
);

while ($arItem = $rsItems->GetNext()) {
    $arPrices = [];

    if (!empty($arAvailablePrices))
        foreach ($arItem['STARTSHOP']['PRICES'] as $arPrice)
            $arPrices[] = $arPrice;

    $arItem['PRICES'] = $arPrices;

    unset($arItem['STARTSHOP']);
    unset($arItem['PROPERTIES']);

    $arItems[$arItem['ID']] = $arItem;
}