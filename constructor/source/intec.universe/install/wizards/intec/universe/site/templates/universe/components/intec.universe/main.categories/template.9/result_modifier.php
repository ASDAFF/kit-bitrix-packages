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
    'PROPERTY_LINK_ADDITIONAL' => null,
    'PROPERTY_LINK_ADDITIONAL_TEXT' => null,
    'PROPERTY_PRICE' => null,
    'PROPERTY_OLD_PRICE' => null,
    'PROPERTY_MARKS' => null,
    'PROPERTY_FEATURES' => null,
    'PRICE_USE' => 'N',
    'MARKS_USE' => 'N',
    'FEATURES_USE' => 'N',
    'LINK_USE' => 'N',
    'LINK_BLANK' => 'N'
], $arParams);

if ($arParams['SETTINGS_USE'] === 'Y')
    include(__DIR__.'/modifiers/settings.php');

$arVisual = [
    'LAZYLOAD' => [
        'USE' => $arParams['LAZYLOAD_USE'] === 'Y',
        'STUB' => null
    ],
    'LINK' => [
        'USE' => $arParams['LINK_USE'] === 'Y',
        'BLANK' => $arParams['LINK_BLANK'] === 'Y'
    ],
    'PRICE' => [
        'USE' => !empty($arParams['PROPERTY_PRICE']) && $arParams['PRICE_USE'] === 'Y'
    ],
    'MARKS' => [
        'USE' => $arParams['MARKS_USE'] === 'Y'
    ],
    'FEATURES' => [
        'USE' => $arParams['FEATURES_USE'] === 'Y'
    ]
];

if (defined('EDITOR'))
    $arVisual['LAZYLOAD']['USE'] = false;

if ($arVisual['LAZYLOAD']['USE'])
    $arVisual['LAZYLOAD']['STUB'] = Properties::get('template-images-lazyload-stub');

$arResult['VISUAL'] = $arVisual;

unset($arVisual);

$hSetPropertyList = function (&$arItem, $property) {
    $arReturn = [
        'SHOW' => false,
        'VALUE' => null,
        'TITLE' => null
    ];

    if (!empty($property)) {
        $arProperty = ArrayHelper::getValue($arItem, [
            'PROPERTIES',
            $property
        ]);

        if (!empty($arProperty['VALUE'])) {
            $arReturn['SHOW'] = true;
            $arReturn['VALUE'] = $arProperty['VALUE'];
            $arReturn['TITLE'] = $arProperty['NAME'];
        }
    }

    return $arReturn;
};

$hSetPropertyText = function (&$arItem, $property) {
    $arReturn = [
        'SHOW' => false,
        'VALUE' => null
    ];

    if (!empty($property)) {
        $arProperty = ArrayHelper::getValue($arItem, [
            'PROPERTIES',
            $property
        ]);

        if (!empty($arProperty['VALUE'])) {
            $arReturn['SHOW'] = true;

            if (Type::isArray($arProperty['VALUE'])) {
                foreach ($arProperty['VALUE'] as $sKey => $sProperty) {
                    $arReturn['VALUE'][$sKey] = [
                        'NAME' => $sProperty,
                        'DESCRIPTION' => $arProperty['DESCRIPTION'][$sKey]
                    ];
                }
            } else {
                $arReturn['VALUE'] = $arProperty['VALUE'];
            }
        }
    }

    return $arReturn;
};

foreach ($arResult['ITEMS'] as &$arItem) {
    $arItem['DATA'] = [
        'PRICE' => [
            'NEW' => $hSetPropertyText($arItem, $arParams['PROPERTY_PRICE']),
            'OLD' => $hSetPropertyText($arItem, $arParams['PROPERTY_OLD_PRICE'])
        ],
        'MARKS' => $hSetPropertyList($arItem, $arParams['PROPERTY_MARKS']),
        'LINK_ADDITIONAL' => $hSetPropertyText($arItem, $arParams['PROPERTY_LINK_ADDITIONAL']),
        'LINK_ADDITIONAL_TEXT' => $hSetPropertyText($arItem, $arParams['PROPERTY_LINK_ADDITIONAL_TEXT']),
        'FEATURES' => $hSetPropertyText($arItem, $arParams['PROPERTY_FEATURES'])
    ];

    if (empty($arItem['DATA']['LINK_ADDITIONAL_TEXT']['VALUE']))
        $arItem['DATA']['LINK_ADDITIONAL']['SHOW'] = false;
}

unset($arItem);