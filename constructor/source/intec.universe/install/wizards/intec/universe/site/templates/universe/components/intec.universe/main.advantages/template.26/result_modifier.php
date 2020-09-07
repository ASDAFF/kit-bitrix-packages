<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use intec\core\helpers\ArrayHelper;
use intec\core\helpers\Type;
use intec\template\Properties;

/**
 * @var array $arResult
 * @var array $arParams
 */

if (empty($arResult['ITEMS']))
    return;

$arParams = ArrayHelper::merge([
    'SETTINGS_USE' => 'N',
    'LAZYLOAD_USE' => 'N',
    'PROPERTY_IMAGES' => null,
    'PROPERTY_TEXT_ADDITIONAL' => null,
    'PROPERTY_THEME' => null,
    'PROPERTY_VIEW' => null,
    'PROPERTY_DETAIL_NARROW' => null,
    'PROPERTY_DEFAULT_TEXT_ADDITIONAL_NARROW' => null,
    'PROPERTY_COMPACT_POSITION' => null,
    'IMAGES_SHOW' => 'N',
    'DETAIL_NARROW' => 'N',
    'BUTTON_TEXT' => null
], $arParams);

if ($arParams['SETTINGS_USE'] === 'Y')
    include(__DIR__.'/modifiers/settings.php');

$arVisual = [
    'LAZYLOAD' => [
        'USE' => $arParams['LAZYLOAD_USE'] === 'Y',
        'STUB' => null
    ],
    'IMAGES' => [
        'SHOW' => $arParams['IMAGES_SHOW'] === 'Y' && !empty($arParams['PROPERTY_IMAGES'])
    ],
    'BUTTON' => [
        'TEXT' => $arParams['BUTTON_TEXT']
    ]
];

if (defined('EDITOR'))
    $arVisual['LAZYLOAD']['USE'] = false;

if ($arVisual['LAZYLOAD']['USE'])
    $arVisual['LAZYLOAD']['STUB'] = Properties::get('template-images-lazyload-stub');

$arResult['VISUAL'] = $arVisual;

unset($arVisual);

foreach ($arResult['ITEMS'] as &$arItem) {
    $arItem['DATA'] = [
        'NAME' => null,
        'IMAGES' => [],
        'ADDITIONAL_TEXT' => null,
        'THEME' => 'white',
        'VIEW' => 'default',
        'DETAIL' => [
            'NARROW' => false
        ],
        'DEFAULT' => [
            'ADDITIONAL_TEXT' => [
                'NARROW' => false
            ]
        ],
        'COMPACT' => [
            'POSITION' => 'left'
        ]
    ];

    if (!empty($arParams['PROPERTY_NAME'])) {
        $arProperty = ArrayHelper::getValue($arItem, [
            'PROPERTIES',
            $arParams['PROPERTY_NAME']
        ]);

        if (!empty($arProperty['VALUE'])) {
            $arProperty = CIBlockFormatProperties::GetDisplayValue(
                $arItem,
                $arProperty,
                false
            );

            if (!empty($arProperty['DISPLAY_VALUE'])) {
                if (Type::isArray($arProperty['DISPLAY_VALUE']))
                    $arProperty['DISPLAY_VALUE'] = ArrayHelper::getFirstValue($arProperty['DISPLAY_VALUE']);

                $arItem['DATA']['NAME'] = $arProperty['DISPLAY_VALUE'];
            }
        }
    }

    if (!empty($arParams['PROPERTY_IMAGES'])) {
        $arProperty = ArrayHelper::getValue($arItem, [
            'PROPERTIES',
            $arParams['PROPERTY_IMAGES']
        ]);

        if (!empty($arProperty['VALUE']) && !empty($arProperty['LINK_IBLOCK_ID'])) {
            if (!Type::isArray($arProperty['VALUE']))
                $arProperty['VALUE'] = explode(',', $arProperty['VALUE']);

            $arItem['DATA']['IMAGES'] = [
                'IBLOCK' => $arProperty['LINK_IBLOCK_ID'],
                'ID' => $arProperty['VALUE']
            ];
        }
    }

    if (!empty($arParams['PROPERTY_TEXT_ADDITIONAL'])) {
        $arProperty = ArrayHelper::getValue($arItem, [
            'PROPERTIES',
            $arParams['PROPERTY_TEXT_ADDITIONAL']
        ]);

        if (!empty($arProperty['VALUE'])) {
            $arProperty = CIBlockFormatProperties::GetDisplayValue(
                $arItem,
                $arProperty,
                false
            );

            if (!empty($arProperty['DISPLAY_VALUE']))
                if (Type::isArray($arProperty['DISPLAY_VALUE']))
                    $arProperty['DISPLAY_VALUE'] = ArrayHelper::getFirstValue($arProperty['DISPLAY_VALUE']);

                $arItem['DATA']['ADDITIONAL_TEXT'] = $arProperty['DISPLAY_VALUE'];
        }
    }

    if (!empty($arParams['PROPERTY_THEME'])) {
        $arProperty = ArrayHelper::getValue($arItem, [
            'PROPERTIES',
            $arParams['PROPERTY_THEME']
        ]);

        if (!empty($arProperty['VALUE_XML_ID'])) {
            if (Type::isArray($arProperty['VALUE_XML_ID']))
                $arProperty['VALUE_XML_ID'] = ArrayHelper::getFirstValue($arProperty['VALUE_XML_ID']);

            $arItem['DATA']['THEME'] = ArrayHelper::fromRange([
                'white',
                'black',
                'gray'
            ], $arProperty['VALUE_XML_ID']);
        }
    }

    if (!empty($arParams['PROPERTY_VIEW'])) {
        $arProperty = ArrayHelper::getValue($arItem, [
            'PROPERTIES',
            $arParams['PROPERTY_VIEW']
        ]);

        if (!empty($arProperty['VALUE_XML_ID'])) {
            if (Type::isArray($arProperty['VALUE_XML_ID']))
                $arProperty['VALUE_XML_ID'] = ArrayHelper::getFirstValue($arProperty['VALUE_XML_ID']);

            $arItem['DATA']['VIEW'] = ArrayHelper::fromRange([
                'default',
                'compact'
            ], $arProperty['VALUE_XML_ID']);
        }
    }

    if (!empty($arParams['PROPERTY_DETAIL_NARROW'])) {
        $arProperty = ArrayHelper::getValue($arItem, [
            'PROPERTIES',
            $arParams['PROPERTY_DETAIL_NARROW']
        ]);

        if (!empty($arProperty['VALUE']) || !empty($arProperty['VALUE_XML_ID'])) {
            $arItem['DATA']['DETAIL']['NARROW'] = true;
        }
    }

    if (!empty($arParams['PROPERTY_DEFAULT_TEXT_ADDITIONAL_NARROW'])) {
        $arProperty = ArrayHelper::getValue($arItem, [
            'PROPERTIES',
            $arParams['PROPERTY_DEFAULT_TEXT_ADDITIONAL_NARROW']
        ]);

        if (!empty($arProperty['VALUE']) || !empty($arProperty['VALUE_XML_ID'])) {
            $arItem['DATA']['DEFAULT']['ADDITIONAL_TEXT']['NARROW'] = true;
        }
    }

    if (!empty($arParams['PROPERTY_COMPACT_POSITION'])) {
        $arProperty = ArrayHelper::getValue($arItem, [
            'PROPERTIES',
            $arParams['PROPERTY_COMPACT_POSITION']
        ]);

        if (!empty($arProperty['VALUE_XML_ID'])) {
            if (Type::isArray($arProperty['VALUE_XML_ID']))
                $arProperty['VALUE_XML_ID'] = ArrayHelper::getFirstValue($arProperty['VALUE_XML_ID']);

            $arItem['DATA']['COMPACT']['POSITION'] = ArrayHelper::fromRange([
                'left',
                'right'
            ], $arProperty['VALUE_XML_ID']);
        }
    }
}