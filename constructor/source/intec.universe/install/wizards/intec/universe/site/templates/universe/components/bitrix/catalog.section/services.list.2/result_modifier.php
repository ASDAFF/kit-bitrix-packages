<?php if (defined("B_PROLOG_INCLUDED") && B_PROLOG_INCLUDED === true) ?>
<?php

use intec\core\helpers\ArrayHelper;
use intec\core\helpers\Type;

$arParams = ArrayHelper::merge([
    'COLUMNS' => 2,
    'ROUNDING_USE' => 'N',
    'ROUNDING_VALUE' => 0,
    'WIDE' => 'Y',
    'LAZY_LOAD' => 'N'
], $arParams);

$arVisual = [
    'COLUMNS' => ArrayHelper::fromRange([2, 3], $arParams['COLUMNS']),
    'NAVIGATION' => [
        'TOP' => [
            'SHOW' => $arParams['DISPLAY_TOP_PAGER']
        ],
        'BOTTOM' => [
            'SHOW' => $arParams['DISPLAY_BOTTOM_PAGER']
        ],
        'LAZY' => [
            'BUTTON' => $arParams['LAZY_LOAD'] === 'Y',
            'SCROLL' => $arParams['LOAD_ON_SCROLL'] === 'Y'
        ]
    ],
    'ROUNDING' => [
        'USE' => $arParams['ROUNDING_USE'] === 'Y',
        'VALUE' => Type::toInteger($arParams['ROUNDING_VALUE'])
    ],
    'WIDE' => $arParams['WIDE'] === 'Y'
];

if ($arVisual['ROUNDING']['VALUE'] < 0)
    $arVisual['ROUNDING']['VALUE'] = 0;

if ($arVisual['ROUNDING']['VALUE'] > 100)
    $arVisual['ROUNDING']['VALUE'] = 100;

if ($arVisual['ROUNDING']['VALUE'] == 0)
    $arVisual['ROUNDING']['USE'] = false;

if ($arVisual['COLUMNS'] > 2 && !$arVisual['WIDE'])
    $arVisual['COLUMNS'] = 2;

$arResult['VISUAL'] = $arVisual;