<?php

use Bitrix\Main\Loader;
use intec\Core;
use intec\core\helpers\ArrayHelper;
use intec\core\helpers\Json;

define('STOP_STATISTICS', true);

require_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_before.php');

if (!Loader::includeModule('intec.core'))
    return;

$oRequest = Core::$app->request;
$mResponse = null;

if ($oRequest->getIsAjax() && $oRequest->getIsPost()) {
    $arResult = [];
    $arResult['BASKET'] = [];
    $arResult['COMPARE'] = [];

    $arParams = $oRequest->post();
    $arParams = ArrayHelper::merge([
        'BASKET' => 'Y',
        'COMPARE' => 'Y',
        'COMPARE_NAME' => 'compare'
    ], $arParams);

    if ($arParams['BASKET'] === 'Y') {
        if (Loader::includeModule('sale')) {
            include(__DIR__.'/modifiers/base/products.php');
        } else if (Loader::includeModule('intec.startshop')) {
            include(__DIR__.'/modifiers/lite/products.php');
        }
    }

    if ($arParams['COMPARE'] === 'Y')
        include(__DIR__.'/modifiers/compare.php');

    $mResponse = [
        'basket' => $arResult['BASKET'],
        'compare' => $arResult['COMPARE']
    ];
}

echo Json::encode($mResponse);