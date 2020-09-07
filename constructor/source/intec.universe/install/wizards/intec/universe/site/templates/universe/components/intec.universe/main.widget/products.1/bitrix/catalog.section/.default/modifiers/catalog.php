<?php if (!defined("B_PROLOG_INCLUDED") && B_PROLOG_INCLUDED !== true) die();

/**
 * @var array $arResult
 * @var array $arCodes
 * @var array $arVisual
 */

foreach ($arResult['ITEMS'] as &$arItem) {
    if (!empty($arItem['OFFERS'])) {
        $arItem['CAN_BUY'] = false;

        if ($arItem['ACTION'] === 'buy' && !$arVisual['OFFERS']['USE'])
            $arItem['ACTION'] = 'detail';

        $arPrices = null;
        $arPrice = null;

        foreach ($arItem['OFFERS'] as &$arOffer) {
            if (!empty($arOffer['ITEM_PRICES'])) {
                if ($arPrices === null || $arPrices[0]['PRICE'] > $arOffer['ITEM_PRICES'][0]['PRICE']) {
                    $arPrices = $arOffer['ITEM_PRICES'];
                }
            }

            if (!empty($arOffer['MIN_PRICE'])) {
                if ($arPrice === null || $arPrice['DISCOUNT_VALUE'] > $arOffer['MIN_PRICE']['DISCOUNT_VALUE']) {
                    $arPrice = $arOffer['MIN_PRICE'];
                }
            }

            unset($arOffer);
        }

        $arItem['MIN_PRICE'] = $arPrice;
        $arItem['ITEM_PRICES'] = $arPrices;

        unset($arPrice);
        unset($arPrices);
    }

    unset($arItem);
}