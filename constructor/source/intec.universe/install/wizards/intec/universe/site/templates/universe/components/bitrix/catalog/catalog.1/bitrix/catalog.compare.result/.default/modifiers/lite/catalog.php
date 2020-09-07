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
    $arProducts[] = $arItem['ID'];
}

$arProducts = array_unique($arProducts);

if (!empty($arProducts)) {
    $arProducts = Arrays::fromDBResult(CStartShopCatalogProduct::GetList([], [
        'ID' => $arProducts
    ]))->indexBy('ID');
} else {
    $arProducts = Arrays::from([]);
}

if (!$arProducts->isEmpty()) {
    foreach ($arResult['ITEMS'] as &$arItem) {
        $arProduct = $arProducts->get($arItem['ID']);

        if (empty($arProduct))
            continue;

        $arItem['CAN_BUY'] = $arProduct['STARTSHOP']['AVAILABLE'];

        if (!empty($arProduct['STARTSHOP']['PRICES']['MINIMAL'])) {
            $arItem['MIN_PRICE'] = $arProduct['STARTSHOP']['PRICES']['MINIMAL'];
            $arItem['MIN_PRICE']['DISCOUNT_VALUE'] = $arItem['MIN_PRICE']['VALUE'];
            $arItem['MIN_PRICE']['PRINT_DISCOUNT_VALUE'] = $arItem['MIN_PRICE']['PRINT_VALUE'];
        }
    }
}

unset($arItem, $arProducts, $arProduct);