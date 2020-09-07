<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use intec\core\helpers\ArrayHelper;
use intec\core\helpers\Html;
use intec\core\helpers\StringHelper;
use intec\core\helpers\Type;
use intec\template\Properties;

/**
 * @var array $arResult
 * @var array $arParams
 */

$arParams = ArrayHelper::merge([
    'SETTINGS_USE' => 'N',
    'LAZYLOAD_USE' => 'N',
    'TITLE' => null,
    'DESCRIPTION_SHOW' => 'N',
    'DESCRIPTION_TEXT' => null,
    'BUTTON_TEXT' => null,
    'THEME' => 'dark',
    'VIEW' => 'left',
    'ALIGN_HORIZONTAL' => 'center',
    'BACKGROUND_COLOR' => null,
    'BACKGROUND_IMAGE_USE' => null,
    'BACKGROUND_IMAGE_PATH' => null,
    'BACKGROUND_IMAGE_HORIZONTAL' => 'center',
    'BACKGROUND_IMAGE_VERTICAL' => 'center',
    'BACKGROUND_IMAGE_SIZE' => 'cover',
    'BACKGROUND_IMAGE_REPEAT' => 'no-repeat',
    'BACKGROUND_IMAGE_PARALLAX_USE' => 'N',
    'BACKGROUND_IMAGE_PARALLAX_RATIO' => '20',
], $arParams);

if ($arParams['SETTINGS_USE'] === 'Y')
    include(__DIR__.'/modifiers/settings.php');

$arData = [
    'LAZYLOAD' => [
        'USE' => $arParams['LAZYLOAD_USE'] === 'Y',
        'STUB' => null
    ],
    'TITLE' => Html::stripTags($arParams['~TITLE'], ['br']),
    'DESCRIPTION' => [
        'SHOW' => $arParams['DESCRIPTION_SHOW'] === 'Y',
        'TEXT' => Html::stripTags($arParams['~DESCRIPTION_TEXT'], ['br'])
    ],
    'THEME' => ArrayHelper::fromRange(['dark', 'light'], $arParams['THEME']),
    'VIEW' => ArrayHelper::fromRange(['left', 'vertical', 'right'], $arParams['VIEW']),
    'ALIGN' => [
        'HORIZONTAL' => ArrayHelper::fromRange(['center', 'left', 'right'], $arParams['ALIGN_HORIZONTAL'])
    ],
    'BUTTON' => [
        'SHOW' => !empty($arResult['ID']),
        'TEXT' => Html::stripTags($arParams['~BUTTON_TEXT'])
    ],
    'BACKGROUND' => [
        'COLOR' => $arParams['BACKGROUND_COLOR'],
        'IMAGE' => [
            'USE' => $arParams['BACKGROUND_IMAGE_USE'] === 'Y',
            'PATH' => $arParams['BACKGROUND_IMAGE_PATH'],
            'HORIZONTAL' => ArrayHelper::fromRange(['center', 'left', 'right'], $arParams['BACKGROUND_IMAGE_HORIZONTAL']),
            'VERTICAL' => ArrayHelper::fromRange(['center', 'top', 'bottom'], $arParams['BACKGROUND_IMAGE_VERTICAL']),
            'SIZE' => ArrayHelper::fromRange(['cover', 'contain'], $arParams['BACKGROUND_IMAGE_SIZE']),
            'REPEAT' => ArrayHelper::fromRange(['no-repeat', 'repeat', 'repeat-x', 'repeat-y'], $arParams['BACKGROUND_IMAGE_REPEAT']),
            'PARALLAX' => [
                'USE' => $arParams['BACKGROUND_IMAGE_PARALLAX_USE'] === 'Y',
                'RATIO' => Type::toInteger($arParams['BACKGROUND_IMAGE_PARALLAX_RATIO'])
            ]
        ]
    ]
];

if (defined('EDITOR'))
    $arData['LAZYLOAD']['USE'] = false;

if ($arData['LAZYLOAD']['USE'])
    $arData['LAZYLOAD']['STUB'] = Properties::get('template-images-lazyload-stub');

if (!empty($arData['BACKGROUND']['IMAGE']['PATH']))
    $arData['BACKGROUND']['IMAGE']['PATH'] = StringHelper::replaceMacros($arData['BACKGROUND']['IMAGE']['PATH'], [
        'SITE_DIR' => SITE_DIR,
        'TEMPLATE_PATH' => $this->GetFolder().'/',
        'SITE_TEMPLATE_PATH' => SITE_TEMPLATE_PATH.'/',
    ]);

if ($arData['BACKGROUND']['IMAGE']['USE'] && empty($arData['BACKGROUND']['IMAGE']['PATH']))
    $arData['BACKGROUND']['IMAGE']['USE'] = false;

if ($arData['BACKGROUND']['IMAGE']['PARALLAX']['USE'] && !empty($arData['BACKGROUND']['IMAGE']['PARALLAX']['RATIO'])) {
    if ($arData['BACKGROUND']['IMAGE']['PARALLAX']['RATIO'] < 0)
        $arData['BACKGROUND']['IMAGE']['PARALLAX']['RATIO'] = 0;

    if ($arData['BACKGROUND']['IMAGE']['PARALLAX']['RATIO'] > 100)
        $arData['BACKGROUND']['IMAGE']['PARALLAX']['RATIO'] = 100;

    $arData['BACKGROUND']['IMAGE']['PARALLAX']['RATIO'] = (100 - $arData['BACKGROUND']['IMAGE']['PARALLAX']['RATIO']) / 100;
} else {
    $arData['BACKGROUND']['IMAGE']['PARALLAX']['USE'] = false;
}

$arResult = ArrayHelper::merge($arResult, $arData);

unset($arData);