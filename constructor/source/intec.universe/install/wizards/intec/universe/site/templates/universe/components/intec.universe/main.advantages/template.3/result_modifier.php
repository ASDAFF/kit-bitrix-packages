<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die() ?>
<?php

use intec\core\helpers\ArrayHelper;
use intec\template\Properties;

/**
 * @var array $arParams
 * @var array $arResult
 */

$arParams = ArrayHelper::merge([
    'SETTINGS_USE' => 'N',
    'LAZYLOAD_USE' => 'N',
    'ARROW_SHOW' => 'N',
    'INDENT_USE' => 'N',
    'BACKGROUND_SIZE' => 'cover'
], $arParams);

if ($arParams['SETTINGS_USE'] === 'Y')
    include(__DIR__.'/modifiers/settings.php');

$arResult['VISUAL'] = [
    'LAZYLOAD' => [
        'USE' => $arParams['LAZYLOAD_USE'] === 'Y',
        'STUB' => null
    ],
    'BACKGROUND' => [
        'SIZE' => ArrayHelper::fromRange([
            'cover',
            'contain'
        ], $arParams['BACKGROUND_SIZE'])
    ],
    'ARROW' => [
        'SHOW' => $arParams['ARROW_SHOW'] === 'Y'
    ],
    'INDENT' => [
        'USE' => $arParams['INDENT_USE'] === 'Y' ? 'true' : 'false'
    ]
];

if (defined('EDITOR'))
    $arResult['VISUAL']['LAZYLOAD']['USE'] = false;

if ($arResult['VISUAL']['LAZYLOAD']['USE'])
    $arResult['VISUAL']['LAZYLOAD']['STUB'] = Properties::get('template-images-lazyload-stub');