<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die() ?>
<?php

use intec\core\helpers\ArrayHelper;
use intec\template\Properties;

/**
 * @var array $arResult
 * @var array $arParams
 */

$arParams = ArrayHelper::merge([
    'SETTINGS_USE' => 'N',
    'LAZYLOAD_USE' => 'N',
    'LINE_COUNT' => 4,
    'VIEW' => 'number'
], $arParams);

if ($arParams['SETTINGS_USE'] === 'Y')
    include(__DIR__.'/modifiers/settings.php');

$arResult['VISUAL']['COLUMNS'] = ArrayHelper::fromRange([2, 3, 4, 5], $arParams['LINE_COUNT']);
$arResult['VISUAL']['VIEW'] = ArrayHelper::fromRange([
    'number',
    'icon'
], $arParams['VIEW']);

$arResult['VISUAL']['LAZYLOAD'] = [
    'USE' => $arParams['LAZYLOAD_USE'] === 'Y',
    'STUB' => null
];

if (defined('EDITOR'))
    $arResult['VISUAL']['LAZYLOAD']['USE'] = false;

if ($arResult['VISUAL']['LAZYLOAD']['USE'])
    $arResult['VISUAL']['LAZYLOAD']['STUB'] = Properties::get('template-images-lazyload-stub');
