<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

/**
 * @global CMain $APPLICATION
 * @var array $arParams
 * @var array $arResult
 * @var CBitrixComponent $component
 */

global $APPLICATION;

$APPLICATION->IncludeComponent('intec:startshop.order', '.default', [
        'CURRENCY' => $arParams['CURRENCY'],
        'REQUEST_VARIABLE_ACTION' => $arParams['REQUEST_VARIABLE_ACTION'],
        'URL_BASKET' => $arResult['URL_BASKET'],
        'URL_ORDER_CREATED' => $arParams['URL_ORDER_CREATED'],
        'URL_ORDER_CREATED_TO_USER' => $arParams['URL_ORDER_CREATED_TO_USER'],
        'URL_BASKET_EMPTY' => $arResult['URL_BASKET_EMPTY'],
        'URL_RULES_OF_PERSONAL_DATA_PROCESSING' => $arParams['URL_RULES_OF_PERSONAL_DATA_PROCESSING']
    ],
    $component
);