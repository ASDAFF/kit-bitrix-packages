<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use intec\core\collections\Arrays;
use intec\core\helpers\ArrayHelper;
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
    'PROPERTY_POSITION' => null,
    'PROPERTY_LOGOTYPE' => null,
    'PROPERTY_LINK' => null,
    'LINK_USE' => 'N',
    'LINK_BLANK' => 'N',
    'COUNTER_SHOW' => 'N',
    'POSITION_SHOW' => 'N',
    'LOGOTYPE_SHOW' => 'N',
    'LOGOTYPE_LINK_USE' => 'N',
    'LOGOTYPE_LINK_BLANK' => 'N',
    'SLIDER_LOOP' => 'N',
    'SLIDER_AUTO_USE' => 'N',
    'SLIDER_AUTO_TIME' => 10000,
    'SLIDER_AUTO_HOVER' => 'N',
    'FOOTER_SHOW' => 'N',
    'FOOTER_POSITION' => 'center',
    'FOOTER_BUTTON_SHOW' => 'N',
    'FOOTER_BUTTON_TEXT' => null
], $arParams);

if ($arParams['SETTINGS_USE'] === 'Y')
    include(__DIR__.'/modifiers/settings.php');

$arMacros = [
    'SITE_DIR' => SITE_DIR
];

$arImages = [];

$hGetProperty = function (&$arItem, $property) {
    $property = ArrayHelper::getValue($arItem, [
        'PROPERTIES',
        $property,
        'VALUE'
    ]);

    if (!empty($property)) {
        if (Type::isArray($property))
            $property = ArrayHelper::getFirstValue($property);

        return $property;
    }

    return null;
};

foreach ($arResult['ITEMS'] as &$arItem) {
    $arItem['DATA'] = [];

    if (!empty($arParams['PROPERTY_POSITION'])) {
        $arProperty = $hGetProperty($arItem, $arParams['PROPERTY_POSITION']);

        if (!empty($arProperty))
            $arItem['DATA']['POSITION'] = $arProperty;
    }

    if (!empty($arParams['PROPERTY_LOGOTYPE'])) {
        $arProperty = $hGetProperty($arItem, $arParams['PROPERTY_LOGOTYPE']);

        if (!empty($arProperty))
            $arImages[] = $arProperty;
            $arItem['DATA']['LOGOTYPE']['VALUE'] = $arProperty;
    }

    if (!empty($arParams['PROPERTY_LINK'])) {
        $arProperty = $hGetProperty($arItem, $arParams['PROPERTY_LINK']);

        if (!empty($arProperty))
            $arItem['DATA']['LOGOTYPE']['LINK'] = StringHelper::replaceMacros($arProperty, $arMacros);
    }
}

unset($arItem);

if (!empty($arImages)) {
    $arImages = Arrays::fromDBResult(CFile::GetList([], [
        '@ID' => implode(',', $arImages)
    ]))->indexBy('ID');

    foreach ($arResult['ITEMS'] as &$arItem) {
        $arImage = $arItem['DATA']['LOGOTYPE']['VALUE'];

        if ($arImages->exists($arImage)) {
            $arImage = $arImages->get($arImage);
            $arImage['SRC'] = CFile::GetFileSRC($arImage);

            $arItem['DATA']['LOGOTYPE']['VALUE'] = $arImage;
        }
    }

    unset($arItem, $arImage);
}

unset($arImages, $hGetProperty);

$arVisual = [
    'LAZYLOAD' => [
        'USE' => $arParams['LAZYLOAD_USE'] === 'Y',
        'STUB' => null
    ],
    'LINK' => [
        'USE' => $arParams['LINK_USE'] === 'Y',
        'BLANK' => $arParams['LINK_BLANK'] === 'Y'
    ],
    'COUNTER' => [
        'SHOW' => $arParams['COUNTER_SHOW'] === 'Y'
    ],
    'POSITION' => [
        'SHOW' => $arParams['POSITION_SHOW'] === 'Y' && !empty($arParams['PROPERTY_POSITION'])
    ],
    'LOGOTYPE' => [
        'SHOW' => $arParams['LOGOTYPE_SHOW'] === 'Y' && !empty($arParams['PROPERTY_LOGOTYPE']),
        'LINK' => [
            'USE' => $arParams['LOGOTYPE_LINK_USE'] === 'Y' && !empty($arParams['PROPERTY_LINK']),
            'BLANK' => $arParams['LOGOTYPE_LINK_BLANK'] === 'Y'
        ]
    ],
    'SLIDER' => [
        'LOOP' => $arParams['SLIDER_LOOP'] === 'Y',
        'AUTO' => [
            'USE' => $arParams['SLIDER_AUTO_USE'] === 'Y',
            'TIME' => Type::toInteger($arParams['SLIDER_AUTO_TIME']),
            'HOVER' => $arParams['SLIDER_AUTO_HOVER'] === 'Y'
        ]
    ]
];

if (defined('EDITOR'))
    $arVisual['LAZYLOAD']['USE'] = false;

if ($arVisual['LAZYLOAD']['USE'])
    $arVisual['LAZYLOAD']['STUB'] = Properties::get('template-images-lazyload-stub');

if ($arVisual['SLIDER']['AUTO']['TIME'] < 1)
    $arVisual['SLIDER']['AUTO']['TIME'] = 10000;

$arResult['VISUAL'] = ArrayHelper::merge($arResult['VISUAL'], $arVisual);

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