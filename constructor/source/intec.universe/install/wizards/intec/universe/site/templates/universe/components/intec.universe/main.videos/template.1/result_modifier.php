<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;
use intec\core\helpers\ArrayHelper;
use intec\core\helpers\Html;
use intec\core\helpers\StringHelper;
use intec\core\helpers\Type;
use intec\template\Properties;

/**
 * @var array $arParams
 * @var array $arResult
 */

$arParams = ArrayHelper::merge([
    'SETTINGS_USE' => 'N',
    'LAZYLOAD_USE' => 'N',
    'CONTENT_POSITION' => null,
    'FOOTER_BUTTON_TEXT' => null,
    'FOOTER_BUTTON_LINK' => null,
    'FOOTER_SHOW' => 'N',
    'FOOTER_POSITION' => null,
    'COLUMNS' => 3,
    'SLIDER_USE' => 'N',
    'SLIDER_LOOP_USE' => 'N',
    'SLIDER_AUTO_PLAY_USE' => 'N',
    'SLIDER_AUTO_PLAY_TIME' => null,
    'SLIDER_AUTO_PLAY_SPEED' => 500,
    'SLIDER_AUTO_PLAY_HOVER_PAUSE' => 'N'
], $arParams);

$arMacros = [
    'SITE_DIR' => SITE_DIR,
    'SITE_TEMPLATE_PATH' => SITE_TEMPLATE_PATH.'/'
];

if ($arParams['SETTINGS_USE'] === 'Y')
    include(__DIR__.'/modifiers/settings.php');

$arResult['BLOCKS']['CONTENT'] = [
    'SHOW' => true,
    'POSITION' => ArrayHelper::fromRange([
        'left',
        'center',
        'right'
    ], $arParams['CONTENT_POSITION'])
];

$arResult['BLOCKS']['FOOTER'] = [
    'SHOW' => $arParams['FOOTER_SHOW'] === 'Y',
    'POSITION' => ArrayHelper::fromRange([
        'left',
        'center',
        'right'
    ], $arParams['FOOTER_POSITION']),
    'BUTTON' => [
        'SHOW' => true,
        'TEXT' => $arParams['FOOTER_BUTTON_TEXT'],
        'LINK' => $arParams['FOOTER_BUTTON_LINK']
    ]
];

$arFooter = &$arResult['BLOCKS']['FOOTER'];

if (empty($arFooter['BUTTON']['TEXT']))
    $arFooter['BUTTON']['TEXT'] = Loc::getMessage('C_VIDEOS_TEMP1_FOOTER_BUTTON_TEXT');

if (empty($arFooter['BUTTON']['LINK'])) {
    $arFooter['BUTTON']['SHOW'] = false;
} else {
    $arFooter['BUTTON']['LINK'] = StringHelper::replaceMacros(
        $arFooter['BUTTON']['LINK'],
        $arMacros
    );
}

if (!$arFooter['BUTTON']['SHOW'])
    $arFooter['SHOW'] = false;

unset($arFooter);

$arVisual = [
    'LAZYLOAD' => [
        'USE' => $arParams['LAZYLOAD_USE'] === 'Y',
        'STUB' => null
    ],
    'COLUMNS' => Type::toInteger($arParams['COLUMNS']),
    'SLIDER' => [
        'USE' => $arParams['SLIDER_USE'] === 'Y',
        'LOOP' => $arParams['SLIDER_LOOP_USE'] === 'Y',
        'AUTO' => [
            'USE' => $arParams['SLIDER_AUTO_PLAY_USE'] === 'Y',
            'TIME' => $arParams['SLIDER_AUTO_PLAY_TIME'],
            'SPEED' => $arParams['SLIDER_AUTO_PLAY_SPEED'],
            'PAUSE' => $arParams['SLIDER_AUTO_PLAY_HOVER_PAUSE'] === 'Y'
        ]
    ]
];

if (defined('EDITOR'))
    $arVisual['LAZYLOAD']['USE'] = false;

if ($arVisual['LAZYLOAD']['USE'])
    $arVisual['LAZYLOAD']['STUB'] = Properties::get('template-images-lazyload-stub');

if ($arVisual['COLUMNS'] < 1)
    $arVisual['COLUMNS'] = 1;

if ($arVisual['COLUMNS'] > 5)
    $arVisual['COLUMNS'] = 5;

$arResult['VISUAL'] = $arVisual;