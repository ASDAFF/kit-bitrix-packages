<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

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
    'PROPERTY_PRICE' => null,
    'PROPERTY_CURRENCY' => null,
    'COLUMNS' => 4,
    'TABS_USE' => 'N',
    'TABS_POSITION' => 'center',
    'TABS_VIEW' => 1,
    'PROPERTIES_SHOW' => 'N',
    'PRICE_SHOW' => 'N',
    'ORDER_USE' => 'N',
    'ORDER_FORM_ID' => null,
    'ORDER_FORM_TEMPLATE' => null,
    'ORDER_FORM_FIELD' => null,
    'ORDER_FORM_TITLE' => null,
    'ORDER_FORM_CONSENT' => null,
    'ORDER_BUTTON' => null
], $arParams);

if ($arParams['SETTINGS_USE'] === 'Y')
    include(__DIR__.'/modifiers/settings.php');

$arMacros = [
    'SITE_DIR' => SITE_DIR
];

$arVisual = [
    'LAZYLOAD' => [
        'USE' => $arParams['LAZYLOAD_USE'] === 'Y',
        'STUB' => null
    ],
    'COLUMNS' => ArrayHelper::fromRange([4, 3], $arParams['COLUMNS']),
    'TABS' => [
        'USE' => $arParams['TABS_USE'] === 'Y',
        'POSITION' => ArrayHelper::fromRange(['center', 'left', 'right'], $arParams['TABS_POSITION']),
        'VIEW' => ArrayHelper::fromRange([1, 2], $arParams['TABS_VIEW']),
    ],
    'PRICE' => [
        'SHOW' => $arParams['PRICE_SHOW'] === 'Y'
    ]
];

if (defined('EDITOR'))
    $arVisual['LAZYLOAD']['USE'] = false;

if ($arVisual['LAZYLOAD']['USE'])
    $arVisual['LAZYLOAD']['STUB'] = Properties::get('template-images-lazyload-stub');

$arResult['VISUAL'] = ArrayHelper::merge($arVisual, $arResult['VISUAL']);

unset($arVisual);

$arForm = [
    'USE' => $arParams['ORDER_USE'] === 'Y',
    'ID' => $arParams['ORDER_FORM_ID'],
    'TEMPLATE' => $arParams['ORDER_FORM_TEMPLATE'],
    'FIELD' => $arParams['ORDER_FORM_FIELD'],
    'TITLE' => $arParams['ORDER_FORM_TITLE'],
    'CONSENT' => $arParams['ORDER_FORM_CONSENT'],
    'BUTTON' => $arParams['ORDER_BUTTON']
];

if ($arForm['USE'])
    if (empty($arForm['ID']) || empty($arForm['TEMPLATE']))
        $arForm['USE'] = false;

$arResult['FORM'] = $arForm;

unset($arForm);

foreach ($arResult['ITEMS'] as &$arItem) {
    $arItem['DATA'] = [
        'PRICE' => null,
        'CURRENCY' => null,
        'PROPERTIES' => false
    ];

    if (!empty($arParams['PROPERTY_PRICE'])) {
        $arProperty = ArrayHelper::getValue($arItem, [
            'PROPERTIES',
            $arParams['PROPERTY_PRICE'],
            'VALUE'
        ]);

        if (!Type::isArray($arProperty))
            $arItem['DATA']['PRICE'] = $arProperty;
        else
            $arItem['DATA']['PRICE'] = ArrayHelper::getFirstValue($arProperty);
    }

    if (!empty($arParams['PROPERTY_CURRENCY'])) {
        $arProperty = ArrayHelper::getValue($arItem, [
            'PROPERTIES',
            $arParams['PROPERTY_CURRENCY'],
            'VALUE'
        ]);

        if (!Type::isArray($arProperty))
            $arItem['DATA']['CURRENCY'] = $arProperty;
        else
            $arItem['DATA']['CURRENCY'] = ArrayHelper::getFirstValue($arProperty);
    }

    if ($arParams['PROPERTIES_SHOW'] === 'Y')
        foreach ($arItem['DISPLAY_PROPERTIES'] as $arProperty) {
            if (!empty($arProperty['VALUE']) && !$arItem['DATA']['PROPERTIES']) {
                $arItem['DATA']['PROPERTIES'] = true;
                break;
            }
        }
}