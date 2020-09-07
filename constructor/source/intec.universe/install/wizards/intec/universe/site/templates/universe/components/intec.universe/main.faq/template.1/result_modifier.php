<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die() ?>
<?php

use intec\core\helpers\ArrayHelper;
use intec\core\helpers\StringHelper;

/**
 * @var array $arResult
 * @var array $arParams
 */

$arParams = ArrayHelper::merge([
    'BY_SECTION' => 'N',
    'TABS_POSITION' => 'center',
    'FOOTER_SHOW' => 'N',
    'FOOTER_POSITION' => 'center',
    'FOOTER_BUTTON_SHOW' => 'N',
    'FOOTER_BUTTON_TEXT' => null,
    'LIST_PAGE_URL' => null
], $arParams);

$arMacros = [
    'SITE_DIR' => SITE_DIR
];

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

$arResult['VISUAL']['TABS'] = [
    'USE' => $arParams['BY_SECTION'] === 'Y',
    'POSITION' => ArrayHelper::fromRange([
        'left',
        'center',
        'right'
    ], $arParams['TABS_POSITION'])
];

$arResult['VISUAL']['TEXT'] = [
    'ALIGNMENT' => ArrayHelper::fromRange([
        'left',
        'center',
        'right'
    ], $arParams['ELEMENT_TEXT_ALIGN'])
];