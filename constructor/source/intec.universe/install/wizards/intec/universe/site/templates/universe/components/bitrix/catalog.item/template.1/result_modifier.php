<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use intec\core\helpers\ArrayHelper;

/**
 * @var array $arResult
 * @var array $arParams
 */

if (!empty($arResult['ITEM']['OFFERS'])) {
    $arPriceMinimal = [];

    foreach ($arResult['ITEM']['OFFERS'] as $arOffer) {
        $arPriceCurrent = ArrayHelper::getFirstValue($arOffer['ITEM_PRICES']);

        if (empty($arPriceMinimal) || $arPriceMinimal['RATIO_BASE_PRICE'] > $arPriceCurrent['RATIO_BASE_PRICE'])
            $arPriceMinimal = $arPriceCurrent;
    }

    $arResult['ITEM']['ITEM_PRICES'][] = $arPriceMinimal;
}