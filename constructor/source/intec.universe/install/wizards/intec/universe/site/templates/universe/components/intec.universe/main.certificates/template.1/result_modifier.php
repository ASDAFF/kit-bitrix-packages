<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die() ?>
<?php

use intec\core\helpers\ArrayHelper;
use intec\core\helpers\StringHelper;
use intec\template\Properties;

/**
 * @var array $arResult
 * @var array $arParams
 */

$arParams = ArrayHelper::merge([
    'SETTINGS_USE' => 'N',
    'LAZYLOAD_USE' => 'N',
    'LINE_COUNT' => 4,
    'ALIGNMENT' => 'center',
    'NAME_SHOW' => 'Y',
    'FOOTER_SHOW' => 'N',
    'FOOTER_POSITION' => 'center',
    'FOOTER_BUTTON_SHOW' => 'N',
    'FOOTER_BUTTON_TEXT' => null,
    'LIST_PAGE_URL' => null
], $arParams);

$arMacros = [
    'SITE_DIR' => SITE_DIR
];

if ($arParams['SETTINGS_USE'] === 'Y')
    include(__DIR__.'/modifiers/settings.php');

$arResult['BLOCKS']['FOOTER'] = [
    'SHOW' => $arParams['FOOTER_SHOW'] === 'Y',
    'POSITION' => ArrayHelper::fromRange([
        'left',
        'center',
        'right'
    ], $arParams['FOOTER_POSITION']),
    'BUTTON' => [
        'SHOW' => $arParams['FOOTER_BUTTON_SHOW'] === 'Y',
        'TEXT' => $arParams['FOOTER_BUTTON_TEXT'],
        'LINK' => null
    ]
];

if (!empty($arParams['LIST_PAGE_URL']))
    $arResult['BLOCKS']['FOOTER']['BUTTON']['LINK'] = StringHelper::replaceMacros(
        $arParams['LIST_PAGE_URL'],
        $arMacros
    );

if (
    empty($arResult['BLOCKS']['FOOTER']['BUTTON']['TEXT']) ||
    empty($arResult['BLOCKS']['FOOTER']['BUTTON']['LINK'])
) $arResult['BLOCKS']['FOOTER']['BUTTON']['SHOW'] = false;

if (!$arResult['BLOCKS']['FOOTER']['BUTTON']['SHOW'])
    $arResult['BLOCKS']['FOOTER']['SHOW'] = false;

$arResult['VISUAL']['LAZYLOAD'] = [
    'USE' => $arParams['LAZYLOAD_USE'] === 'Y',
    'STUB' => null
];
if (defined('EDITOR'))
    $arResult['VISUAL']['LAZYLOAD']['USE'] = false;

if ($arResult['VISUAL']['LAZYLOAD']['USE'])
    $arResult['VISUAL']['LAZYLOAD']['STUB'] = Properties::get('template-images-lazyload-stub');

$arResult['VISUAL']['COLUMNS'] = ArrayHelper::fromRange([3, 4, 5], $arParams['LINE_COUNT']);
$arResult['VISUAL']['ALIGNMENT'] = ArrayHelper::fromRange([
    'center',
    'left',
    'right'
], $arParams['ALIGNMENT']);

$arResult['VISUAL']['NAME'] = [
    'SHOW' => $arParams['NAME_SHOW'] === 'Y'
];

if ($arResult['VISUAL']['ALIGNMENT'] === 'left')
    $arResult['VISUAL']['ALIGNMENT'] = 'start';

if ($arResult['VISUAL']['ALIGNMENT'] === 'right')
    $arResult['VISUAL']['ALIGNMENT'] = 'end';