<?php if (defined("B_PROLOG_INCLUDED") && B_PROLOG_INCLUDED === true) ?>
<?php

use intec\core\collections\Arrays;
use intec\core\helpers\ArrayHelper;
use intec\core\helpers\Type;

/**
 * @var array $arParams
 * @var array $arResult
 * @var CBitrixComponent $component
 * @var CBitrixComponentTemplate $this
 */

$arProducts = [];

foreach ($arResult['ITEMS'] as &$arItem) {
    $arItem['MIN_PRICE'] = null;
    $arProduct = CCatalogSku::GetProductInfo($arItem['ID']);

    if ($arProduct) {
        $arItem['PRODUCT'] = $arProduct['ID'];
        $arProducts[] = $arProduct['ID'];
    }

    if (!empty($arItem['PRICE_MATRIX']) && !empty($arItem['PRICE_MATRIX']['MATRIX'])) {
        foreach ($arItem['PRICE_MATRIX']['MATRIX'] as $iPriceTypeId => $arPrices) {
            $arPrice = current($arPrices);

            if (empty($arPrice))
                continue;

            if (empty($arItem['MIN_PRICE']) || $arItem['MIN_PRICE']['DISCOUNT_PRICE'] > $arPrice['DISCOUNT_PRICE']) {
                $arItem['MIN_PRICE'] = [
                    'TYPE' => $iPriceTypeId,
                    'CURRENCY' => $arPrice['CURRENCY'],
                    'VAT_RATE' => Type::toFloat($arPrice['VAT_RATE']),
                    'VALUE' => Type::toFloat($arPrice['PRICE']),
                    'PRINT_VALUE' => null,
                    'DISCOUNT_VALUE' => Type::toFloat($arPrice['DISCOUNT_PRICE']),
                    'PRINT_DISCOUNT_VALUE' => null
                ];
            }
        }

        if (!empty($arItem['MIN_PRICE'])) {
            $arItem['MIN_PRICE']['TYPE'] = ArrayHelper::getValue($arItem['PRICE_MATRIX'], [
                'COLS',
                $arItem['MIN_PRICE']['TYPE']
            ]);

            if (!empty($arItem['MIN_PRICE']['TYPE']))
                $arItem['MIN_PRICE']['TYPE'] = $arItem['MIN_PRICE']['TYPE']['NAME'];

            $arItem['MIN_PRICE']['PRINT_VALUE'] = CCurrencyLang::CurrencyFormat(
                $arItem['MIN_PRICE']['VALUE'],
                $arItem['MIN_PRICE']['CURRENCY']
            );

            $arItem['MIN_PRICE']['PRINT_DISCOUNT_VALUE'] = CCurrencyLang::CurrencyFormat(
                $arItem['MIN_PRICE']['DISCOUNT_VALUE'],
                $arItem['MIN_PRICE']['CURRENCY']
            );
        }
    }

    $arItem['CAN_BUY'] = CIBlockPriceTools::CanBuy($arItem['IBLOCK_ID'], $arResult['PRICES'], $arItem);
}

$arProducts = array_unique($arProducts);

if (!empty($arProducts)) {
    $arProducts = Arrays::fromDBResult(CIBlockElement::GetList([], [
        'ID' => $arProducts
    ]))->indexBy('ID');
} else {
    $arProducts = Arrays::from([]);
}

if (!$arProducts->isEmpty())
    foreach ($arResult['ITEMS'] as &$arItem) {
        $arProduct = $arProducts->get($arItem['PRODUCT']);

        if (!empty($arProduct)) {
            if (empty($arItem['PREVIEW_PICTURE']))
                $arItem['PREVIEW_PICTURE'] = $arProduct['PREVIEW_PICTURE'];

            if (empty($arItem['DETAIL_PICTURE']))
                $arItem['DETAIL_PICTURE'] = $arProduct['DETAIL_PICTURE'];
        }

        unset($arItem['PRODUCT']);
    }

unset($arItem, $arProducts, $arProduct);