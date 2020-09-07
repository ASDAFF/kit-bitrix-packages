<?php if (defined("B_PROLOG_INCLUDED") && B_PROLOG_INCLUDED === true) ?>
<?php

use intec\core\helpers\ArrayHelper;

$arParams = ArrayHelper::merge([
    'WIDE' => 'Y',
    'LAZY_LOAD' => 'N'
], $arParams);

$arVisual = [
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

$arResult['VISUAL'] = $arVisual;