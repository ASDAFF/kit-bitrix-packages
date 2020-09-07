<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die() ?>
<?php

use Bitrix\Main\Context;
use Bitrix\Main\Loader;
use Bitrix\Sale\Basket;
use Bitrix\Sale\BasketItem;
use Bitrix\Sale\Fuser;
use intec\core\helpers\ArrayHelper;
use intec\core\helpers\StringHelper;
use intec\core\net\Url;

/**
 * @var array $arParams
 * @var array $arResult
 * @var CBitrixComponent $this
 */

if (
    !Loader::includeModule('intec.core') ||
    !Loader::includeModule('iblock')
) return;

$arMacros = [
    'SITE_DIR' => SITE_DIR,
    'SITE_TEMPLATE_PATH' => SITE_TEMPLATE_PATH
];

$arBasket = [
    'SHOW' => ArrayHelper::getValue($arParams, 'BASKET_SHOW') == 'Y',
    'URL' => ArrayHelper::getValue($arParams, 'BASKET_URL'),
    'COUNT' => 0
];

$oDelayUrl = new Url($arBasket['URL']);
$oDelayUrl->getQuery()->setRange([
    'delay' => 'y'
]);

$arDelay = array(
    'SHOW' => ArrayHelper::getValue($arParams, 'DELAY_SHOW') == 'Y',
    'URL' => !empty($arBasket['URL']) ? $oDelayUrl->build() : null,
    'COUNT' => 0,
);

$arCompare = [
    'SHOW' => ArrayHelper::getValue($arParams, 'COMPARE_SHOW') == 'Y',
    'IBLOCK' => [
        'ID' => ArrayHelper::getValue($arParams, 'COMPARE_IBLOCK_ID'),
        'TYPE' => ArrayHelper::getValue($arParams, 'COMPARE_IBLOCK_TYPE')
    ],
    'CODE' => ArrayHelper::getValue($arParams, 'COMPARE_CODE'),
    'URL' => ArrayHelper::getValue($arParams, 'COMPARE_URL'),
    'COUNT' => 0
];

if (!empty($arCompare['CODE']) && !empty($arCompare['IBLOCK']['ID'])) {
    $arCompareItems = ArrayHelper::getValue($_SESSION, [
        $arCompare['CODE'],
        $arCompare['IBLOCK']['ID'],
        'ITEMS'
    ]);

    $arCompare['COUNT'] = !empty($arCompareItems) ? count($arCompareItems) : 0;
} else {
    $arCompare['SHOW'] = false;
}

if (Loader::includeModule('sale')) {
    $oBasket = Basket::loadItemsForFUser(
        Fuser::getId(),
        Context::getCurrent()->getSite()
    );

    if (!empty($oBasket)) {
        /** @var BasketItem $oItem */
        foreach ($oBasket as $oItem) {
            if ($oItem->getField('DELAY') != 'Y') {
                $arBasket['COUNT']++;
            } else {
                $arDelay['COUNT']++;
            }
        }
    }
} else if (Loader::includeModule('intec.startshop')) {
    $arBasket['COUNT'] = CStartShopBasket::GetItemsCount();
    $arDelay['SHOW'] = false;
}

$arResult['BASKET'] = $arBasket;
$arResult['DELAY'] = $arDelay;
$arResult['COMPARE'] = $arCompare;

foreach ($arResult as $sKey => $arItem)
    $arResult[$sKey]['URL'] = StringHelper::replaceMacros(
        $arItem['URL'],
        $arMacros
    );

$this->includeComponentTemplate();