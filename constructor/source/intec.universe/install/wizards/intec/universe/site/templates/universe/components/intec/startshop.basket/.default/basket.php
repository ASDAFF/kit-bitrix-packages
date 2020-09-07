<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Loader;
use intec\core\helpers\ArrayHelper;
use intec\core\helpers\StringHelper;

/**
 * @global CMain $APPLICATION
 * @var array $arParams
 * @var array $arResult
 * @var CBitrixComponent $component
 */

global $APPLICATION;

if (!Loader::includeModule('intec.core'))
    return;

$arParameters = [];

foreach ($arParams as $sKey => $sValue) {
    if (StringHelper::startsWith($sKey, 'FAST_ORDER_'))
        $arParameters[$sKey] = $sValue;
}

$arParameters = ArrayHelper::merge($arParameters, [
    'USE_ADAPTABILITY' => $arParams['USE_ADAPTABILITY'],
    'CURRENCY' => $arParams['CURRENCY'],
    'REQUEST_VARIABLE_ACTION' => $arParams['REQUEST_VARIABLE_ACTION'],
    'REQUEST_VARIABLE_QUANTITY' => $arParams['REQUEST_VARIABLE_QUANTITY'],
    'REQUEST_VARIABLE_ITEM' => $arParams['REQUEST_VARIABLE_ITEM'],
    'USE_ITEMS_PICTURES' => $arParams['USE_ITEMS_PICTURES'],
    'USE_BUTTON_CLEAR' => $arParams['USE_BUTTON_CLEAR'],
    'USE_SUM_FIELD' => $arParams['USE_SUM_FIELD'],
    'USE_BUTTON_ORDER' => 'Y',
    'USE_BUTTON_FAST_ORDER' => $arParams['USE_BUTTON_FAST_ORDER'],
    'USE_BUTTON_CONTINUE_SHOPPING' => $arParams['USE_BUTTON_CONTINUE_SHOPPING'],
    'URL_ORDER' => $arResult['URL_ORDER'],
    'URL_CATALOG' => $arParams['URL_CATALOG'],
    'URL_BASKET_EMPTY' => $arParams['URL_BASKET_EMPTY']
]);

$APPLICATION->IncludeComponent(
    'intec:startshop.basket.basket',
    '.default',
    $arParameters,
    $component
);
