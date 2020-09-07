<?php if (!defined('B_PROLOG_INCLUDED') && B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Loader;
use intec\core\helpers\ArrayHelper;
use intec\core\helpers\StringHelper;
use intec\core\helpers\Type;

/**
 * @var array $arParams
 * @var array $arResult
 */

if (!Loader::includeModule('intec.core'))
    return;

$arParams = ArrayHelper::merge([
    'PROPERTY_PRICE' => null,
    'CURRENCY' => null,
    'PROPERTY_CURRENCY' => null,
    'PRICE_FORMAT' => '#VALUE# #CURRENCY#',
    'PROPERTY_PRICE_FORMAT' => null,
    'BORDERS' => 'Y',
    'COLUMNS' => 3,
    'POSITION' => 'left',
    'SIZE' => 'big',
    'WIDE' => 'Y',
    'SLIDER_USE' => 'N',
    'SLIDER_DOTS' => 'N',
    'SLIDER_NAVIGATION' => 'N',
    'SLIDER_LOOP' => 'N',
    'SLIDER_AUTO_PLAY_USE' => 'N',
    'SLIDER_AUTO_PLAY_TIME' => 1000,
    'SLIDER_AUTO_PLAY_SPEED' => 500,
    'SLIDER_AUTO_PLAY_HOVER_PAUSE' => 'N'
], $arParams);

$arMacros = [
    'SITE_DIR' => SITE_DIR,
    'SITE_TEMPLATE_PATH' => SITE_TEMPLATE_PATH.'/'
];

$arVisual = [
    'BORDERS' => $arParams['BORDERS'] === 'Y',
    'COLUMNS' => Type::toInteger($arParams['COLUMNS']),
    'POSITION' => ArrayHelper::fromRange([
        'left',
        'center',
        'right'
    ], $arParams['POSITION']),
    'SIZE' => ArrayHelper::fromRange([
        'small',
        'big'
    ], $arParams['SIZE']),
    'WIDE' => $arParams['WIDE'] === 'Y',
    'SLIDER' => [
        'USE' => $arParams['SLIDER_USE'] === 'Y',
        'DOTS' => $arParams['SLIDER_DOTS'] === 'Y',
        'NAVIGATION' => $arParams['SLIDER_NAVIGATION'] === 'Y',
        'LOOP' => $arParams['SLIDER_LOOP'] === 'Y',
        'AUTO' => [
            'USE' => $arParams['SLIDER_AUTO_PLAY_USE'] === 'Y',
            'TIME' => Type::toInteger($arParams['SLIDER_AUTO_PLAY_TIME']),
            'SPEED' => Type::toInteger($arParams['SLIDER_AUTO_PLAY_SPEED']),
            'PAUSE' => $arParams['SLIDER_AUTO_PLAY_HOVER_PAUSE'] === 'Y'
        ]
    ]
];

if ($arVisual['COLUMNS'] < 2)
    $arVisual['COLUMNS'] = 3;

if ($arVisual['COLUMNS'] > 4)
    $arVisual['COLUMNS'] = 4;

if ($arVisual['SLIDER']['AUTO']['TIME'] < 100)
    $arVisual['SLIDER']['AUTO']['TIME'] = 100;

if ($arVisual['SLIDER']['AUTO']['SPEED'] < 100)
    $arVisual['SLIDER']['AUTO']['SPEED'] = 100;

$arResult['URL'] = [
    'BASKET' => ArrayHelper::getValue($arParams, 'BASKET_URL')
];

foreach ($arResult['URL'] as $sKey => $sUrl)
    $arResult['URL'][$sKey] = StringHelper::replaceMacros($sUrl, $arMacros);

$hGetPropertyValue = function (&$arItem, $mProperty) {
    $mProperty = ArrayHelper::getValue($arItem, [
        'PROPERTIES',
        $mProperty,
        'VALUE'
    ]);

    if (!empty($mProperty)) {
        if (Type::isArray($mProperty))
            $mProperty = ArrayHelper::getFirstValue($mProperty);

        return $mProperty;
    }

    return null;
};

foreach ($arResult['ITEMS'] as &$arItem) {
    $arItem['DATA'] = [
        'PRICE' => [
            'VALUE' => null,
            'CURRENCY' => null,
            'FORMAT' => null
        ]
    ];

    if (!empty($arParams['PROPERTY_PRICE'])) {
        $arProperty = $hGetPropertyValue($arItem, $arParams['PROPERTY_PRICE']);

        if (!empty($arProperty) && Type::isNumeric($arProperty))
            $arItem['DATA']['PRICE']['VALUE'] = $arProperty;
    }

    if (!empty($arParams['PROPERTY_CURRENCY'])) {
        $arProperty = $hGetPropertyValue($arItem, $arParams['PROPERTY_CURRENCY']);
        $arItem['DATA']['PRICE']['CURRENCY'] = $arProperty;
    }

    if (empty($arItem['DATA']['PRICE']['CURRENCY']))
        $arItem['DATA']['PRICE']['CURRENCY'] = $arParams['CURRENCY'];

    if (!empty($arParams['PROPERTY_PRICE_FORMAT'])) {
        $arProperty = $hGetPropertyValue($arItem, $arParams['PROPERTY_PRICE_FORMAT']);
        $arItem['DATA']['PRICE']['FORMAT'] = $arProperty;
    }

    if (empty($arItem['DATA']['PRICE']['FORMAT']))
        $arItem['DATA']['PRICE']['FORMAT'] = $arParams['PRICE_FORMAT'];
}

unset($arItem, $hGetPropertyValue);

include(__DIR__.'/modifiers/pictures.php');

$arResult['VISUAL'] = $arVisual;