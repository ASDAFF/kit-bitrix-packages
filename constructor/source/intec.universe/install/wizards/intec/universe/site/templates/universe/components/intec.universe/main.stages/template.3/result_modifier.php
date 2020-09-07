<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use intec\core\helpers\ArrayHelper;

/**
 * @var array $arResult
 * @var array $arParams
 */

/** Параметры отображения */

$arParams = ArrayHelper::merge([
    'PROPERTY_TIME' => null,
    'PROPERTY_TEXT_SOURCE' => 'preview',
    'ELEMENT_NAME_SIZE' => 'big'
], $arParams);

$arVisual = [
    'TEXT' => [
        'SOURCE' => strtoupper($arParams['PROPERTY_TEXT_SOURCE']).'_TEXT'
    ],
    'NAME' => [
        'SIZE' => ArrayHelper::fromRange(['big', 'normal'], $arParams['ELEMENT_NAME_SIZE'])
    ]
];

foreach ($arResult['ITEMS'] as &$arItem) {
    $arFields = [
        'TIME' => [
            'VALUE' => ArrayHelper::getValue($arItem, ['PROPERTIES', $arParams['PROPERTY_TIME'], 'VALUE'])
        ]
    ];

    $arItem['DATA'] = $arFields;

    unset($arFields);
}

$arResult['VISUAL'] = $arVisual;
