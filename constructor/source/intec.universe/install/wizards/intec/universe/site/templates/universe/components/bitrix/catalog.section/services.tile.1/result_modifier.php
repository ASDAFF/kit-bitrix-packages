<?php if (defined("B_PROLOG_INCLUDED") && B_PROLOG_INCLUDED === true) ?>
<?php

use intec\core\helpers\ArrayHelper;
use intec\core\helpers\Type;

$arParams = ArrayHelper::merge([
    'COLUMNS' => 3,
    'WIDE' => 'Y',
    'LAZY_LOAD' => 'N'
], $arParams);

$arVisual = [
    'COLUMNS' => Type::toInteger($arParams['COLUMNS']),
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
    'WIDE' => $arParams['WIDE'] === 'Y'
];

if ($arVisual['COLUMNS'] < 2)
    $arVisual['COLUMNS'] = 2;

if ($arVisual['COLUMNS'] > 4)
    $arVisual['COLUMNS'] = 4;

if (!$arVisual['WIDE'] && $arVisual['COLUMNS'] > 3)
    $arVisual['COLUMNS'] = 3;

$arResult['VISUAL'] = $arVisual;