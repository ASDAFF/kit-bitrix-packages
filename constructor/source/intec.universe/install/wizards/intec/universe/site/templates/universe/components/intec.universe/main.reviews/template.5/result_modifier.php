<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

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
    'COLUMNS' => 4,
    'LINK_USE' => 'N',
    'LINK_BLANK' => 'N',
    'POSITION_SHOW' => 'N',
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
}

unset($arItem, $hGetProperty);

$arVisual = [
    'LAZYLOAD' => [
        'USE' => $arParams['LAZYLOAD_USE'] === 'Y',
        'STUB' => null
    ],
    'COLUMNS' => ArrayHelper::fromRange([4, 3, 2], $arParams['COLUMNS']),
    'LINK' => [
        'USE' => $arParams['LINK_USE'] === 'Y',
        'BLANK' => $arParams['LINK_BLANK'] === 'Y'
    ],
    'POSITION' => [
        'SHOW' => $arParams['POSITION_SHOW'] === 'Y' && !empty($arParams['PROPERTY_POSITION'])
    ]
];

if (defined('EDITOR'))
    $arVisual['LAZYLOAD']['USE'] = false;

if ($arVisual['LAZYLOAD']['USE'])
    $arVisual['LAZYLOAD']['STUB'] = Properties::get('template-images-lazyload-stub');

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