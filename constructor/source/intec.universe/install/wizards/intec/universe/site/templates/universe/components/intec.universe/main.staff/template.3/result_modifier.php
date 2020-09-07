<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die() ?>
<?php

use intec\core\helpers\ArrayHelper;
use intec\core\helpers\Type;
use intec\template\Properties;

/**
 * @var array $arResult
 * @var array $arParams
 */

$arParams = ArrayHelper::merge([
    'SETTINGS_USE' => 'N',
    'LAZYLOAD_USE' => 'N',
    'COLUMNS' => 2,
    'HIDING_USE' => 'N',
    'HIDING_VISIBLE' => 2
], $arParams);

$arMacros = [
    'SITE_DIR' => SITE_DIR
];

if ($arParams['SETTINGS_USE'] === 'Y')
    include(__DIR__.'/modifiers/settings.php');

$arResult['VISUAL']['LAZYLOAD'] = [
    'USE' => $arParams['LAZYLOAD_USE'] === 'Y',
    'STUB' => null
];
$arResult['VISUAL']['COLUMNS'] = ArrayHelper::fromRange([1, 2], $arParams['COLUMNS']);
$arResult['VISUAL']['HIDING'] = [
    'USE' => $arParams['HIDING_USE'] === 'Y',
    'VISIBLE' => Type::toInteger($arParams['HIDING_VISIBLE'])
];

if (defined('EDITOR'))
    $arVisual['LAZYLOAD']['USE'] = false;

if ($arVisual['LAZYLOAD']['USE'])
    $arVisual['LAZYLOAD']['STUB'] = Properties::get('template-images-lazyload-stub');

if ($arResult['VISUAL']['HIDING']['VISIBLE'] < 1)
    $arResult['VISUAL']['HIDING']['VISIBLE'] = 1;

if (count($arResult['ITEMS']) <= $arResult['VISUAL']['HIDING']['VISIBLE'] * $arResult['VISUAL']['COLUMNS'])
    $arResult['VISUAL']['HIDING']['USE'] = false;