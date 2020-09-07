<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

/**
 * @global CMain $APPLICATION
 * @var array $arParams
 * @var array $arResult
 * @var CBitrixComponent $component
 */

global $APPLICATION;

$APPLICATION->IncludeComponent('intec:startshop.payment', '', array(
        'REQUEST_VARIABLE_ACTION' => $arParams['REQUEST_VARIABLE_ACTION'],
        'REQUEST_VARIABLE_PAYMENT' => $arParams['REQUEST_VARIABLE_PAYMENT'],
        'REQUEST_VARIABLE_VALUE_RESULT' => $arParams['REQUEST_VARIABLE_VALUE_RESULT'],
        'REQUEST_VARIABLE_VALUE_SUCCESS' => $arParams['REQUEST_VARIABLE_VALUE_SUCCESS'],
        'REQUEST_VARIABLE_VALUE_FAIL' => $arParams['REQUEST_VARIABLE_VALUE_FAIL'],
        'URL_CATALOG' => $arParams['URL_CATALOG'],
    ),
    $component
);