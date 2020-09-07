<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use intec\core\helpers\ArrayHelper;
use intec\template\Properties;

/**
 * @var array $arResult
 * @var array $arParams
 */

$arParams = ArrayHelper::merge([
    'SETTINGS_USE' => 'N',
    'LAZYLOAD_USE' => 'N',
    'PICTURE_SHOW' => 'N',
    'PICTURE_SIZE' => '60',
    'PREVIEW_SHOW' => 'N',
    'NAME_SHOW' => 'Y',
    'NAME_SIZE' => 'middle',
    'NAME_MARGIN' => 'middle',
    'COLUMNS' => 5
], $arParams);

if ($arParams['SETTINGS_USE'] === 'Y')
    include(__DIR__.'/modifiers/settings.php');

$arVisual = [
    'LAZYLOAD' => [
        'USE' => $arParams['LAZYLOAD_USE'] === 'Y',
        'STUB' => null
    ],
    'PICTURE' => [
        'SHOW' => $arParams['PICTURE_SHOW'] === 'Y',
        'SIZE' => ArrayHelper::fromRange([60, 80, 100], $arParams['PICTURE_SIZE'])
    ],
    'PREVIEW' => [
        'SHOW' => $arParams['PREVIEW_SHOW'] === 'Y'
    ],
    'NAME' => [
        'SHOW' => $arParams['NAME_SHOW'] === 'Y',
        'SIZE' => ArrayHelper::fromRange(['small', 'middle', 'big'], $arParams['NAME_SIZE']),
        'MARGIN' => ArrayHelper::fromRange(['small', 'middle', 'big'], $arParams['NAME_MARGIN'])
    ],
    'ELEMENT' => [
        'MARGIN' => ArrayHelper::fromRange(['small', 'middle', 'big'], $arParams['ELEMENT_MARGIN'])
    ]
];

if (defined('EDITOR'))
    $arVisual['LAZYLOAD']['USE'] = false;

if ($arVisual['LAZYLOAD']['USE'])
    $arVisual['LAZYLOAD']['STUB'] = Properties::get('template-images-lazyload-stub');

$arResult['VISUAL']['COLUMNS'] = ArrayHelper::fromRange([2, 3, 4], $arParams['COLUMNS']);
$arResult['VISUAL'] = ArrayHelper::merge($arVisual, $arResult['VISUAL']);

unset($arVisual);