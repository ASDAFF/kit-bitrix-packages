<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die() ?>
<?php

use Bitrix\Main\Loader;
use intec\core\helpers\ArrayHelper;

/**
 * @var $arParams
 * @var $arResult
 */

if (!Loader::includeModule('iblock') || !Loader::includeModule('intec.core'))
    return;

$arParams = ArrayHelper::merge([
    'LAZYLOAD_USE' => 'N',
    'PROPERTY_DURATION' => null,
    'PROPERTY_DISCOUNT' => null,
    'IBLOCK_DESCRIPTION_SHOW' => 'Y'
], $arParams);

$arVisual = [
    'LAZYLOAD' => [
        'USE' => $arParams['LAZYLOAD_USE'] === 'Y'
    ],
    'IBLOCK' => [
        'DESCRIPTION' => [
            'SHOW' => $arParams['IBLOCK_DESCRIPTION_SHOW'] === 'Y'
        ]
    ],
    'NAVIGATION' => [
        'TOP' => [
            'SHOW' => $arParams['DISPLAY_TOP_PAGER'] && !empty($arResult['NAV_STRING'])
        ],
        'BOTTOM' => [
            'SHOW' => $arParams['DISPLAY_BOTTOM_PAGER'] && !empty($arResult['NAV_STRING'])
        ]
    ]
];

if (defined('EDITOR'))
    $arVisual['LAZYLOAD']['USE'] = false;

foreach ($arResult['ITEMS'] as &$arItem) {
    $arItem['DATA'] = [
        'DURATION' => null,
        'DISCOUNT' => null
    ];

    $arItem['DATA']['DURATION'] = ArrayHelper::getValue($arItem, ['PROPERTIES', $arParams['PROPERTY_DURATION'], 'VALUE']);
    $arItem['DATA']['DISCOUNT'] = ArrayHelper::getValue($arItem, ['PROPERTIES', $arParams['PROPERTY_DISCOUNT'], 'VALUE']);
}

$arResult['VISUAL'] = $arVisual;