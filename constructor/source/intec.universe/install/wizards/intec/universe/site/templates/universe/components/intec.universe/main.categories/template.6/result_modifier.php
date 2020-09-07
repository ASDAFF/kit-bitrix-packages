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
    'PROPERTY_STICKER' => null,
    'VIEW_STYLE' => 'chess',
    'NAME_HORIZONTAL' => 'left',
    'NAME_VERTICAL' => 'bottom',
    'STICKER_SHOW' => 'N',
    'STICKER_HORIZONTAL' => 'left',
    'STICKER_VERTICAL' => 'top',
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
    'VIEW' => ArrayHelper::fromRange(['chess', 'standard'], $arParams['VIEW_STYLE']),
    'LINK' => [
        'USE' => $arParams['LINK_USE'] === 'Y',
        'BLANK' => $arParams['LINK_BLANK'] === 'Y'
    ],
    'NAME' => [
        'HORIZONTAL' => ArrayHelper::fromRange(['left', 'center', 'right'], $arParams['NAME_HORIZONTAL']),
        'VERTICAL' => ArrayHelper::fromRange(['bottom', 'middle', 'top'], $arParams['NAME_VERTICAL'])
    ],
    'STICKER' => [
        'SHOW' => !empty($arParams['PROPERTY_STICKER']) && $arParams['STICKER_SHOW'] === 'Y',
        'HORIZONTAL' => ArrayHelper::fromRange(['left', 'center', 'right'], $arParams['STICKER_HORIZONTAL']),
        'VERTICAL' => ArrayHelper::fromRange(['top', 'middle', 'bottom'], $arParams['STICKER_VERTICAL'])
    ]
];

if (defined('EDITOR'))
    $arVisual['LAZYLOAD']['USE'] = false;

if ($arVisual['LAZYLOAD']['USE'])
    $arVisual['LAZYLOAD']['STUB'] = Properties::get('template-images-lazyload-stub');

$arResult['VISUAL'] = $arVisual;

unset($arVisual);

foreach ($arResult['ITEMS'] as &$arItem) {
    $arItem['DATA'] = [];

    if (!empty($arParams['PROPERTY_STICKER'])) {
        $arProperty = ArrayHelper::getValue($arItem, [
            'PROPERTIES',
            $arParams['PROPERTY_STICKER']
        ]);

        if (!empty($arProperty['VALUE'])) {
            $arProperty = CIBlockFormatProperties::GetDisplayValue(
                $arItem,
                $arProperty,
                false
            );

            if (Type::isArray($arProperty['DISPLAY_VALUE']))
                $arProperty['DISPLAY_VALUE'] = ArrayHelper::getFirstValue($arProperty['DISPLAY_VALUE']);

            $arItem['DATA']['STICKER'] = $arProperty['DISPLAY_VALUE'];
        }

        unset($arProperty);
    }
}

unset($arItem);