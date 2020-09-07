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
    'LINE_COUNT' => 4,
    'SUB_SECTIONS_SHOW' => 'N',
    'SUB_SECTIONS_MAX' => 3
], $arParams);

if ($arParams['SETTINGS_USE'] === 'Y')
    include(__DIR__.'/modifiers/settings.php');

$arVisual = ArrayHelper::merge($arResult['VISUAL'], [
    'LAZYLOAD' => [
        'USE' => $arParams['LAZYLOAD_USE'] === 'Y',
        'STUB' => null
    ],
    'COLUMNS' => ArrayHelper::fromRange([3, 2], $arParams['LINE_COUNT']),
    'CHILDREN' => [
        'SHOW' => $arParams['SUB_SECTIONS_SHOW'] === 'Y',
        'COUNT' => Type::toInteger($arParams['SUB_SECTIONS_MAX'])
    ]
]);

if (defined('EDITOR'))
    $arVisual['LAZYLOAD']['USE'] = false;

if ($arVisual['LAZYLOAD']['USE'])
    $arVisual['LAZYLOAD']['STUB'] = Properties::get('template-images-lazyload-stub');

if (empty($arVisual['CHILDREN']['COUNT']) && !Type::isNumeric($arVisual['CHILDREN']['COUNT']))
    $arVisual['CHILDREN']['COUNT'] = null;

$arResult['VISUAL'] = $arVisual;

unset($arVisual);