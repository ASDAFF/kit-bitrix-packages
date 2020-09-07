<? if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
<?php

use intec\Core;
use intec\core\helpers\ArrayHelper;

/**
 * @var array $arParams
 * @var array $arResult
 */

$oRequest = Core::$app->request;
$arParams = ArrayHelper::merge([
    'VARIABLES_ACTION' => 'system-settings-action'
], $arParams);

$arResult['ACTION'] = null;

if ($oRequest->getIsPost())
    $arResult['ACTION'] = $oRequest->post($arParams['VARIABLES_ACTION']);