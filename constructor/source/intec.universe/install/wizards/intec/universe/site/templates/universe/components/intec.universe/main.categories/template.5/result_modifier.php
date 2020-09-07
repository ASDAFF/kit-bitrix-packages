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
    'PROPERTY_SIZE' => null,
    'POSITION_HORIZONTAL' => 'left',
    'POSITION_VERTICAL' => 'bottom',
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
    'POSITION' => [
        'HORIZONTAL' => ArrayHelper::fromRange(['left', 'right'], $arParams['POSITION_HORIZONTAL']),
        'VERTICAL' => ArrayHelper::fromRange(['bottom', 'top'], $arParams['POSITION_VERTICAL'])
    ],
    'LINK' => [
        'USE' => $arParams['LINK_USE'] === 'Y',
        'BLANK' => $arParams['LINK_BLANK'] === 'Y'
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
        'SIZE' => 'small'
    ];

    if (!empty($arParams['PROPERTY_SIZE'])) {
        $arProperty = ArrayHelper::getValue($arItem, [
            'PROPERTIES',
            $arParams['PROPERTY_SIZE']
        ]);

        if (Type::isArray($arProperty['VALUE_XML_ID']))
            $arProperty['VALUE_XML_ID'] = ArrayHelper::getFirstValue($arProperty['VALUE_XML_ID']);

        if (!empty($arProperty['VALUE_XML_ID']))
            $arItem['DATA']['SIZE'] = ArrayHelper::fromRange([
                'small',
                'medium'
            ], $arProperty['VALUE_XML_ID']);
    }

    unset($arProperty);
}

unset($arItem);