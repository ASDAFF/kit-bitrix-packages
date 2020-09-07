<?php if (!defined('B_PROLOG_INCLUDED') && B_PROLOG_INCLUDED === true) die();

use intec\core\helpers\ArrayHelper;
use intec\core\helpers\StringHelper;

/**
 * @var array $arResult
 * @var array $arParams
 */

$arOrderFast = [];
$arOrderFast['USE'] = $arParams['ORDER_FAST_USE'] === 'Y';
$arOrderFast['PREFIX'] = 'ORDER_FAST_';
$arOrderFast['TEMPLATE'] = $arParams['ORDER_FAST_TEMPLATE'];
$arOrderFast['PARAMETERS'] = [];

if (empty($arOrderFast['TEMPLATE']))
    $arOrderFast['USE'] = false;

if ($arOrderFast['USE']) {
    $sLength = StringHelper::length($arOrderFast['PREFIX']);

    $arExcluded = [
        'USE',
        'TEMPLATE'
    ];

    foreach ($arParams as $key => $sValue) {
        if (!StringHelper::startsWith($key, $arOrderFast['PREFIX']))
            continue;

        $key = StringHelper::cut($key, $sLength);

        if (!ArrayHelper::isIn($key, $arExcluded))
            $arOrderFast['PARAMETERS'][$key] = $sValue;
    }

    unset($sLength, $arExcluded, $key, $sValue);
}

$arResult['ORDER_FAST'] = $arOrderFast;

unset($arOrderFast);