<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die() ?>
<?php

use Bitrix\Main\Loader;
use intec\core\helpers\ArrayHelper;
use intec\regionality\Module as Regionality;
use intec\regionality\models\Region;

/**
 * @var array $arParams
 * @var array $arResult
 * @var CBitrixComponentTemplate $this
 * @global $APPLICATION
 */

$arParams = ArrayHelper::merge([
    'REGIONALITY_USE' => 'N',
    'REGIONALITY_PRICES_TYPES_USE' => 'N'
], $arParams);

$arResult['REGIONALITY'] = [
    'USE' => $arParams['REGIONALITY_USE'] === 'Y',
    'PRICES' => [
        'USE' => $arParams['REGIONALITY_PRICES_TYPES_USE'] === 'Y'
    ]
];

if (!Loader::includeModule('intec.regionality'))
    $arResult['REGIONALITY']['USE'] = false;

if ($arResult['REGIONALITY']['USE']) {
    $oRegion = Region::getCurrent();

    if (!empty($oRegion))
        if ($arResult['REGIONALITY']['PRICES']['USE'])
            $arParams['PRODUCTS_VIEWED_PRICE_CODE'] = $_SESSION[Regionality::VARIABLE][Region::VARIABLE]['PRICES']['CODE'];
}