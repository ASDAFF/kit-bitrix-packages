<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use intec\core\helpers\ArrayHelper;
use intec\core\helpers\Type;
use intec\core\helpers\StringHelper;
use intec\template\Properties;

/**
 * @var array $arParams
 * @var array $arResult
 */

$arParams = ArrayHelper::merge([
    'SETTINGS_USE' => 'N',
    'LAZYLOAD_USE' => 'N',
    'COLUMNS' => 4,
    'WIDE' => 'N',
    'INDENT_USE' => 'N',
    'INDENT_VALUE' => 'default',
    'TABS_USE' => 'N',
    'TABS_POSITION' => 'center',
    'SLIDER_USE' => 'N',
    'SLIDER_NAV' => 'N',
    'SLIDER_DOTS' => 'N',
    'SLIDER_LOOP' => 'N',
    'SLIDER_CENTER' => 'N',
    'SLIDER_AUTO_USE' => 'N',
    'SLIDER_AUTO_TIME' => '5000',
    'SLIDER_AUTO_HOVER' => 'N',
    'FOOTER_SHOW' => 'N',
    'FOOTER_POSITION' => 'center',
    'FOOTER_BUTTON_SHOW' => 'N',
    'FOOTER_BUTTON_TEXT' => null
], $arParams);

if ($arParams['SETTINGS_USE'] === 'Y')
    include(__DIR__.'/modifiers/settings.php');

$arMacros = [
    'SITE_DIR' => SITE_DIR,
    'SITE_TEMPLATE_PATH' => SITE_TEMPLATE_PATH.'/',
    'TEMPLATE_PATH' => $this->GetFolder().'/'
];

$arVisual = [
    'LAZYLOAD' => [
        'USE' => $arParams['LAZYLOAD_USE'] === 'Y',
        'STUB' => null
    ],
    'COLUMNS' => ArrayHelper::fromRange([4, 3, 5, 6], $arParams['COLUMNS']),
    'WIDE' => $arParams['WIDE'] === 'Y',
    'INDENT' => [
        'USE' => $arParams['INDENT_USE'] === 'Y',
        'VALUE' => ArrayHelper::fromRange(['default', 'small', 'big'], $arParams['INDENT_VALUE'])
    ],
    'TABS' => [
        'USE' => $arParams['TABS_USE'] === 'Y',
        'POSITION' => ArrayHelper::fromRange(['center', 'left', 'right'], $arParams['TABS_POSITION'])
    ],
    'SLIDER' => [
        'USE' => $arParams['SLIDER_USE'] === 'Y',
        'NAV' => $arParams['SLIDER_NAV'] === 'Y',
        'DOTS' => $arParams['SLIDER_DOTS'] === 'Y',
        'LOOP' => $arParams['SLIDER_LOOP'] === 'Y',
        'CENTER' => $arParams['SLIDER_CENTER'] === 'Y',
        'AUTO' => [
            'USE' => $arParams['SLIDER_AUTO_USE'] === 'Y',
            'TIME' => $arParams['SLIDER_AUTO_TIME'],
            'HOVER' => $arParams['SLIDER_AUTO_HOVER'] === 'Y'
        ]
    ]
];

if (defined('EDITOR'))
    $arVisual['LAZYLOAD']['USE'] = false;

if ($arVisual['LAZYLOAD']['USE'])
    $arVisual['LAZYLOAD']['STUB'] = Properties::get('template-images-lazyload-stub');

if (!$arVisual['WIDE'] && $arVisual['COLUMNS'] > 4)
    $arVisual['COLUMNS'] = 4;

if (!$arVisual['SLIDER']['LOOP'] && $arVisual['SLIDER']['CENTER'])
    $arVisual['SLIDER']['CENTER'] = false;

if (!empty($arVisual['SLIDER']['AUTO']['TIME']))
    if (Type::isNumeric($arVisual['SLIDER']['AUTO']['TIME']))
        $arVisual['SLIDER']['AUTO']['TIME'] = Type::toInteger($arVisual['SLIDER']['AUTO']['TIME']);
    else
        $arVisual['SLIDER']['AUTO']['TIME'] = 5000;
else
    $arVisual['SLIDER']['AUTO']['USE'] = false;

$arResult['VISUAL'] = ArrayHelper::merge($arVisual, $arResult['VISUAL']);

unset($arVisual);

$arFooter = [
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
    $arFooter['BUTTON']['LINK'] = StringHelper::replaceMacros(
        $arParams['LIST_PAGE_URL'],
        $arMacros
    );

if (empty($arFooter['BUTTON']['TEXT']) || empty($arFooter['BUTTON']['LINK']))
    $arFooter['BUTTON']['SHOW'] = false;

if (!$arFooter['BUTTON']['SHOW'])
    $arFooter['SHOW'] = false;

$arResult['BLOCKS']['FOOTER'] = $arFooter;

unset($arFooter, $arMacros);