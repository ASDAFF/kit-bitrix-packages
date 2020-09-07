<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die() ?>
<?php

use Bitrix\Main\Loader;
use intec\core\helpers\ArrayHelper;

/**
 * @var array $arParams
 * @var array $arResult
 */

if (!Loader::includeModule('intec.core') || !Loader::includeModule('sale'))
    return;

$arParams = ArrayHelper::merge([
    'LAZYLOAD_USE' => 'N',
    'TITLE_SHOW' => 'N',
    'TITLE' => null,
    'COLUMNS' => 5,
    'SHOW_NAVIGATION' => 'N',
    'PAGE_ELEMENT_COUNT' => 10
], $arParams);

$arVisual = [
    'LAZYLOAD' => [
        'USE' => $arParams['LAZYLOAD_USE'] === 'Y'
    ],
    'COLUMNS' => ArrayHelper::fromRange([4, 5, 8, 9], $arParams['COLUMNS']),
    'SLIDER' => [
        'NAVIGATION' => $arParams['SHOW_NAVIGATION'] === 'Y'
    ],
    'TITLE' => [
        'SHOW' => $arParams['TITLE_SHOW'] === 'Y',
        'VALUE' => ArrayHelper::getValue($arParams, 'TITLE')
    ]
];

if (defined('EDITOR'))
    $arVisual['LAZYLOAD']['USE'] = false;

include(__DIR__.'/modifiers/prices.php');

$arResult['VISUAL'] = $arVisual;