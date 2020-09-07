<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die() ?>
<?php

use Bitrix\Main\Context;
use Bitrix\Sale\Basket;
use Bitrix\Sale\BasketItem;
use Bitrix\Sale\Fuser;

/**
 * @var array $arParams
 * @var array $arResult
 */

$oBasket = Basket::loadItemsForFUser(
    Fuser::getId(),
    Context::getCurrent()->getSite()
);

/** @var BasketItem $oItem */
foreach ($oBasket as $oItem) {
    $arResult['BASKET'][] = [
        'id' => $oItem->getField('PRODUCT_ID'),
        'delay' => $oItem->isDelay()
    ];
}

unset($oItem);
unset($oBasket);