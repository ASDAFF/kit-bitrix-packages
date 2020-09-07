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
    'PROPERTY_PRICE' => null,
    'PROPERTY_CURRENCY' => null,
    'PROPERTY_PRICE_FORMAT' => null,
    'LINK_USE' => 'N',
    'LINK_BLANK' => 'N',
    'PRICE_SHOW' => 'N',
    'SLIDER_NAV' => 'N',
    'SLIDER_NAV_VIEW' => 1,
    'SLIDER_DOTS' => 'N',
    'SLIDER_DOTS_VIEW' => 1,
    'SLIDER_LOOP' => 'N',
    'SLIDER_AUTOPLAY' => 'N',
    'SLIDER_AUTOPLAY_TIME' => 10000,
    'SLIDER_AUTOPLAY_HOVER' => 'N'
], $arParams);

if ($arParams['SETTINGS_USE'] === 'Y')
    include(__DIR__.'/modifiers/settings.php');

$arVisual = [
    'LAZYLOAD' => [
        'USE' => !defined('EDITOR') ? $arParams['LAZYLOAD_USE'] === 'Y' : false,
        'STUB' => !defined('EDITOR') ? Properties::get('template-images-lazyload-stub') : null
    ],
    'LINK' => [
        'USE' => $arParams['LINK_USE'] === 'Y',
        'BLANK' => $arParams['LINK_BLANK'] === 'Y'
    ],
    'PRICE' => [
        'SHOW' => $arParams['PRICE_SHOW'] === 'Y' && !empty($arParams['PROPERTY_PRICE'])
    ],
    'SLIDER' => [
        'NAV' => [
            'SHOW' => $arParams['SLIDER_NAV'] === 'Y',
            'VIEW' => ArrayHelper::fromRange([1], $arParams['SLIDER_NAV_VIEW'])
        ],
        'DOTS' => [
            'SHOW' => $arParams['SLIDER_DOTS'] === 'Y',
            'VIEW' => ArrayHelper::fromRange([1, 2], $arParams['SLIDER_DOTS_VIEW'])
        ],
        'LOOP' => $arParams['SLIDER_LOOP'] === 'Y',
        'AUTO' => [
            'USE' => $arParams['SLIDER_AUTOPLAY'] === 'Y',
            'TIME' => Type::toInteger($arParams['SLIDER_AUTOPLAY_TIME']),
            'HOVER' => $arParams['SLIDER_AUTOPLAY_HOVER'] === 'Y'
        ]
    ]
];

if (!$arVisual['SLIDER']['AUTO']['TIME'])
    $arVisual['SLIDER']['AUTO']['TIME'] = 10000;

$arResult['VISUAL'] = $arVisual;

unset($arVisual);

foreach ($arResult['ITEMS'] as &$arItem) {
    $arItem['DATA'] = [
        'PRICE' => [
            'SHOW' => false,
            'VALUE' => null,
            'CURRENCY' => null,
            'FORMAT' => '#VALUE# #CURRENCY#',
            'PRINT' => null
        ]
    ];

    if (!empty($arParams['PROPERTY_PRICE'])) {
        $arProperty = ArrayHelper::getValue($arItem, [
            'PROPERTIES',
            $arParams['PROPERTY_PRICE']
        ]);

        if (!empty($arProperty)) {
            $arProperty = CIBlockFormatProperties::GetDisplayValue(
                $arItem,
                $arProperty,
                false
            );

            if (!empty($arProperty['DISPLAY_VALUE'])) {
                if (Type::isArray($arProperty['DISPLAY_VALUE']))
                    $arProperty['DISPLAY_VALUE'] = ArrayHelper::getFirstValue($arProperty['DISPLAY_VALUE']);

                if (!empty($arProperty['DISPLAY_VALUE']))
                    $arItem['DATA']['PRICE']['VALUE'] = $arProperty['DISPLAY_VALUE'];
            }
        }
    }

    if (!empty($arParams['PROPERTY_CURRENCY'])) {
        $arProperty = ArrayHelper::getValue($arItem, [
            'PROPERTIES',
            $arParams['PROPERTY_CURRENCY'],
            'VALUE'
        ]);

        if (!empty($arProperty)) {
            if (Type::isArray($arProperty))
                $arProperty = ArrayHelper::getFirstValue($arProperty);

            $arItem['DATA']['PRICE']['CURRENCY'] = $arProperty;
        }
    }

    if (!empty($arParams['PROPERTY_PRICE_FORMAT'])) {
        $arProperty = ArrayHelper::getValue($arItem, [
            'PROPERTIES',
            $arParams['PROPERTY_PRICE_FORMAT']
        ]);

        if (!empty($arProperty)) {
            $arProperty = CIBlockFormatProperties::GetDisplayValue(
                $arItem,
                $arProperty,
                false
            );

            if (!empty($arProperty['DISPLAY_VALUE'])) {
                if (Type::isArray($arProperty['DISPLAY_VALUE']))
                    $arProperty['DISPLAY_VALUE'] = ArrayHelper::getFirstValue($arProperty['DISPLAY_VALUE']);

                if (!empty($arProperty['DISPLAY_VALUE']))
                    $arItem['DATA']['PRICE']['FORMAT'] = $arProperty['DISPLAY_VALUE'];
            }
        }
    }

    if (!empty($arItem['DATA']['PRICE']['VALUE'])) {
        $arItem['DATA']['PRICE']['PRINT'] = trim(
            StringHelper::replaceMacros($arItem['DATA']['PRICE']['FORMAT'], [
                'VALUE' => $arItem['DATA']['PRICE']['VALUE'],
                'CURRENCY' => $arItem['DATA']['PRICE']['CURRENCY']
            ])
        );

        $arItem['DATA']['PRICE']['SHOW'] = $arResult['VISUAL']['PRICE']['SHOW'];
    }
}

unset($arItem, $arProperty);