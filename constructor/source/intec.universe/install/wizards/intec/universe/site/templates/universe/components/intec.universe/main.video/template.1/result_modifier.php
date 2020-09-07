<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use intec\core\helpers\ArrayHelper;
use intec\core\helpers\Type;
use intec\template\Properties;

/**
 * @var array $arParams
 * @var array $arResult
 */

$arParams = ArrayHelper::merge([
    'SETTINGS_USE' => 'N',
    'LAZYLOAD_USE' => 'N',
    'WIDE' => 'N',
    'HEIGHT' => 'auto',
    'ROUNDED' => 'N',
    'FADE' => 'N',
    'SHADOW_USE' => 'N',
    'SHADOW_MODE' => 'hover',
    'THEME' => 'light',
    'PARALLAX_USE' => 'N',
    'PARALLAX_RATIO' => 25
], $arParams);

if ($arParams['SETTINGS_USE'] === 'Y')
    include(__DIR__.'/modifiers/settings.php');

$arVisual = [
    'LAZYLOAD' => [
        'USE' => $arParams['LAZYLOAD_USE'] === 'Y',
        'STUB' => null
    ],
    'WIDE' => $arParams['WIDE'] === 'Y',
    'HEIGHT' => $arParams['HEIGHT'] !== 'auto' ? Type::toInteger($arParams['HEIGHT']) : 'auto',
    'ROUNDED' => $arParams['ROUNDED'] === 'Y' && $arParams['WIDE'] !== 'Y',
    'FADE' => $arParams['FADE'] === 'Y',
    'SHADOW' => [
        'USE' => $arParams['SHADOW_USE'] === 'Y',
        'MODE' => ArrayHelper::fromRange(['hover', 'permanent'], $arParams['SHADOW_MODE'])
    ],
    'THEME' => ArrayHelper::fromRange(['light', 'dark'], $arParams['THEME']),
    'PARALLAX' => [
        'USE' => $arParams['PARALLAX_USE'] === 'Y',
        'RATIO' => Type::toInteger($arParams['PARALLAX_RATIO'])
    ]
];

if (defined('EDITOR'))
    $arVisual['LAZYLOAD']['USE'] = false;

if ($arVisual['LAZYLOAD']['USE'])
    $arVisual['LAZYLOAD']['STUB'] = Properties::get('template-images-lazyload-stub');

if (!$arVisual['HEIGHT'] || $arVisual['HEIGHT'] < 300)
    $arVisual['HEIGHT'] = 'auto';

if ($arVisual['PARALLAX']['RATIO'] < 0) {
    $arVisual['PARALLAX']['USE'] = false;
    $arVisual['PARALLAX']['RATIO'] = 0;
} else if ($arVisual['PARALLAX']['RATIO'] > 100) {
    $arVisual['PARALLAX']['RATIO'] = 100;
}

$arVisual['PARALLAX']['RATIO'] = (100 - $arVisual['PARALLAX']['RATIO']) / 100;

$arResult['VISUAL'] = $arVisual;

unset($arVisual);