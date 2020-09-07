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
    'PROPERTY_CATEGORY' => null,
    'PROPERTY_PRICE' => null,
    'LINK_USE' => 'N',
    'CATEGORY_SHOW' => 'N',
    'PREVIEW_SHOW' => 'N',
    'PRICE_SHOW' => 'N',
    'ORDER_USE' => 'N',
    'ORDER_FORM_ID' => null,
    'ORDER_FORM_TEMPLATE' => null,
    'ORDER_FORM_FIELD' => null,
    'ORDER_FORM_TITLE' => null,
    'ORDER_FORM_CONSENT' => null,
    'ORDER_BUTTON' => null
], $arParams);

$arMacros = [
    'SITE_DIR' => SITE_DIR
];

if ($arParams['SETTINGS_USE'] === 'Y')
    include(__DIR__.'/modifiers/settings.php');

$arVisual = [
    'LAZYLOAD' => [
        'USE' => $arParams['LAZYLOAD_USE'] === 'Y',
        'STUB' => null
    ],
    'LINK' => [
        'USE' => $arParams['LINK_USE'] === 'Y'
    ],
    'CATEGORY' => [
        'SHOW' => $arParams['CATEGORY_SHOW'] === 'Y' && !empty($arParams['PROPERTY_CATEGORY'])
    ],
    'PREVIEW' => [
        'SHOW' => $arParams['PREVIEW_SHOW'] === 'Y'
    ],
    'PRICE' => [
        'SHOW' => $arParams['PRICE_SHOW'] === 'Y' && !empty($arParams['PROPERTY_PRICE'])
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
    if (empty($arForm['ID']) || empty($arForm['TEMPLATE']) || empty($arForm['FIELD']))
        $arForm['USE'] = false;

$arResult['FORM'] = $arForm;

unset($arForm);

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

foreach ($arResult['ITEMS'] as &$arItem) {
    $arItem['DATA'] = [
        'CATEGORY' => null,
        'PRICE' => null
    ];

    if (!empty($arParams['PROPERTY_CATEGORY'])) {
        $arProperty = ArrayHelper::getValue($arItem, [
            'PROPERTIES',
            $arParams['PROPERTY_CATEGORY'],
            'VALUE'
        ]);

        if (!empty($arProperty))
            $arItem['DATA']['CATEGORY'] = $arProperty;
    }

    if (!empty($arParams['PROPERTY_PRICE'])) {
        $arProperty = ArrayHelper::getValue($arItem, [
            'PROPERTIES',
            $arParams['PROPERTY_PRICE'],
            'VALUE'
        ]);

        if (!empty($arProperty)) {
            if (Type::isArray($arProperty))
                $arProperty = ArrayHelper::getFirstValue($arProperty);

            $arItem['DATA']['PRICE'] = $arProperty;
        }
    }
}

unset($arItem);