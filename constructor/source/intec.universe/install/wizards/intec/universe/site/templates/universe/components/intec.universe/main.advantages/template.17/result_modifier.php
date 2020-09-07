<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use intec\core\helpers\ArrayHelper;

/**
 * @var array $arResult
 * @var array $arParams
 */

$arParams = ArrayHelper::merge([
    'PREVIEW_SHOW' => 'N',
    'NUMBER_SHOW' => 'N',
    'COLUMNS' => 2,
], $arParams);

$arVisual = [
    'PREVIEW' => [
        'SHOW' => $arParams['PREVIEW_SHOW'] === 'Y'
    ]
];

$arResult['VISUAL']['COLUMNS'] = ArrayHelper::fromRange([2, 3, 4, 5], $arParams['COLUMNS']);
$arResult['VISUAL'] = ArrayHelper::merge($arVisual, $arResult['VISUAL']);

unset($arVisual);