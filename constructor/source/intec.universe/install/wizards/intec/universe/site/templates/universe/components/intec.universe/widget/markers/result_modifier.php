<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use intec\core\helpers\ArrayHelper;

/**
 * @var array $arResult
 * @var array $arParams
 */

$bRecommendation = ArrayHelper::getValue($arParams, 'MARKER_RECOMMENDATION', 'N') == 'Y';
$bNew = ArrayHelper::getValue($arParams, 'MARKER_NEW', 'N') == 'Y';
$bHit = ArrayHelper::getValue($arParams, 'MARKER_HIT', 'N') == 'Y';
$bDiscount = ArrayHelper::getValue($arParams, 'MARKER_DISCOUNT', 'N') == 'Y';
$sDiscountValue = ArrayHelper::getValue($arParams, 'MARKER_DISCOUNT_VALUE');

$arResult['MARKERS'] = [
    'RECOMMENDATION' => $bRecommendation,
    'NEW' => $bNew,
    'HIT' => $bHit,
    'DISCOUNT' => [
        'ACTIVE' => $bDiscount,
        'VALUE' => $sDiscountValue
    ]
];