<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die() ?>
<?php

use intec\core\collections\Arrays;

/**
 * @var array $arParams
 * @var array $arResult
 */

$arBasket = Arrays::fromDBResult(CStartShopBasket::GetList([], [], [], [], [], SITE_ID));

foreach ($arBasket as $arItem) {
    $arResult['BASKET'][] = [
        'id' => $arItem['ID'],
        'delay' => false
    ];
}

unset($arItem);
unset($arBasket);