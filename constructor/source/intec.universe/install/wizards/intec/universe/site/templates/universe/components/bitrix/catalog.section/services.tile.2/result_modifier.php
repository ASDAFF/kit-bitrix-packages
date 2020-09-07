<?php if (defined("B_PROLOG_INCLUDED") && B_PROLOG_INCLUDED === true) ?>
<?php

use intec\core\helpers\ArrayHelper;
use intec\core\helpers\Type;

$arParams = ArrayHelper::merge([
    'COLUMNS' => 3,
    'PICTURE_TYPE' => 'square',
    'PICTURE_INDENTS' => 'N',
    'NAME_POSITION' => 'center',
    'DESCRIPTION_SHOW' => 'Y',
    'DESCRIPTION_POSITION' => 'center',
    'WIDE' => 'Y',
    'LAZY_LOAD' => 'N'
], $arParams);

$arVisual = [
    'COLUMNS' => Type::toInteger($arParams['COLUMNS']),
    'NAME' => [
        'POSITION' => ArrayHelper::fromRange([
            'left',
            'center',
            'right'
        ], $arParams['NAME_POSITION'])
    ],
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
    'DESCRIPTION' => [
        'SHOW' => $arParams['DESCRIPTION_SHOW'] === 'Y',
        'POSITION' => ArrayHelper::fromRange([
            'left',
            'center',
            'right'
        ], $arParams['DESCRIPTION_POSITION'])
    ],
    'PICTURE' => [
        'TYPE' => ArrayHelper::fromRange([
            'square',
            'round'
        ], $arParams['PICTURE_TYPE']),
        'INDENTS' => $arParams['PICTURE_INDENTS'] === 'Y'
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