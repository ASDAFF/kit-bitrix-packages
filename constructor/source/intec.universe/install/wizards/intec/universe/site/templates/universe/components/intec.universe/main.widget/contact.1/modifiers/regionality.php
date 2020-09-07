<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die() ?>
<?php

use Bitrix\Main\Loader;
use intec\core\helpers\ArrayHelper;

/**
 * @var array $arParams
 * @var array $arResult
 * @var CBitrixComponentTemplate $this
 * @global $APPLICATION
 */

$arParams = ArrayHelper::merge([
    'REGIONALITY_USE' => 'N'
], $arParams);

$arResult['REGIONALITY'] = [
    'USE' => $arParams['REGIONALITY_USE'] === 'Y'
];

if (!Loader::includeModule('intec.regionality'))
    $arResult['REGIONALITY']['USE'] = false;