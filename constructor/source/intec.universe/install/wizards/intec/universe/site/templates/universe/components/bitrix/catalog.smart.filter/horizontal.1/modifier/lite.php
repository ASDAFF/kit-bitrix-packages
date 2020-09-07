<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use intec\core\helpers\ArrayHelper;

/**
 * @var array $arResult
 * @var array $arParams
 */

CStartShopTheme::ApplyTheme(SITE_ID);

$arPrices = [];
$rsPrices = CStartShopPrice::GetList(['SORT' => 'ASC'], $arParams['PRICE_CODE']);

while ($arPrice = $rsPrices->Fetch()) {
    $arPrices[$arPrice['ID']] = $arPrice;
}

$sPrefix = CStartShopToolsIBlock::GetPricePrefix();
$arPriceNames = [];

foreach ($arPrices as $arItem) {
    $arPriceNames[$arItem['ID']] = $sPrefix.'_'.$arItem['ID'];
}

if (!empty($arPriceNames)) {
    foreach($arResult['ITEMS'] as $key=>$arItem) {
        if (ArrayHelper::isIn($arItem['CODE'], $arPriceNames)) {
            $arResult['ITEMS'][$key]['PRICE'] = 1;
        }
    }
}