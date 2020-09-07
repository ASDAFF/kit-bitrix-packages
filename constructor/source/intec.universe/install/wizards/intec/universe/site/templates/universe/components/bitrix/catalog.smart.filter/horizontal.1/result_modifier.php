<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)die();

use Bitrix\Main\Loader;
use intec\core\helpers\ArrayHelper;

/**
 * @var array $arResult
 * @var array $arParams
 */
$arParams = ArrayHelper::merge([
    'COLUMNS' => 4,
    'WIDE' => 'N',
    'WIDE_BACKGROUND' => 'dark',
    'COLLAPSED' => 'N',
    'PRICES_EXPANDED' => [],
    'BUTTONS_TOGGLE_TYPE' => 'text-arrow',
    'TYPE_A_PRECISION' => 2,
    'TYPE_F_BACKGROUND' => 'light',
    'TYPE_G_SIZE' => 'default',
    'POPUP_USE' => 'Y'
], $arParams);

$arParams['FILTER_VIEW_MODE'] = 'HORIZONTAL';
$arResult['VISUAL'] = [
    'DISPLAY' => false,
    'COLUMNS' => ArrayHelper::fromRange([
        3,
        4
    ], $arParams['COLUMNS']),
    'VIEW' => $arParams['FILTER_VIEW_MODE'],
    'WIDE' => [
        'USE' => $arParams['WIDE'] === 'Y',
        'BACKGROUND' => ArrayHelper::fromRange([
            'dark',
            'white'
        ], $arParams['WIDE_BACKGROUND'])
    ],
    'COLLAPSED' => $arParams['COLLAPSED'] === 'Y',
    'BUTTONS' => [
        'TOGGLE' => [
            'TYPE' => ArrayHelper::fromRange([
                'text-arrow',
                'arrow'
            ], $arParams['BUTTONS_TOGGLE_TYPE'])
        ]
    ],
    'TYPE' => [
        'A' => [
            'PRECISION' => $arParams['TYPE_A_PRECISION'],
            'DATA' => 'track'
        ],
        'F' => [
            'BACKGROUND' => ArrayHelper::fromRange([
                'light',
                'dark',
                'none'
            ], $arParams['TYPE_F_BACKGROUND']),
            'DATA' => 'checkbox'
        ],
        'G' => [
            'SIZE' => ArrayHelper::fromRange([
                'default',
                'big'
            ], $arParams['TYPE_G_SIZE']),
            'DATA' => 'checkbox-picture'
        ],
        'H' => [
            'DATA' => 'checkbox-text-picture'
        ]
    ],
    'POPUP' => [
        'USE' => $arParams['POPUP_USE'] === 'Y'
    ]
];

if (Loader::includeModule('intec.seo')) {
    $APPLICATION->IncludeComponent('intec.seo:filter.loader', '', [
        'FILTER_RESULT' => $arResult
    ], $component);
}

if (Loader::includeModule('intec.startshop'))
    include(__DIR__.'/modifier/lite.php');

foreach ($arResult['ITEMS'] as $sKey => &$arItem) {
    if ($arItem['PRICE'] && ArrayHelper::isIn($sKey, $arParams['PRICES_EXPANDED']))
        $arItem['DISPLAY_EXPANDED'] = 'Y';

    if (!$arItem['PRICE'] && !empty($arItem['VALUES'])) {
        $arResult['VISUAL']['DISPLAY'] = true;
    } else if ($arItem['PRICE'] && $arItem['VALUES']['MIN']['VALUE'] !== $arItem['VALUES']['MAX']['VALUE']) {
        $arResult['VISUAL']['DISPLAY'] = true;
    }
}

unset($arItem);

if ($arResult['VISUAL']['COLLAPSED'])
    foreach ($arResult['ITEMS'] as $arItem)
        if ($arItem['DISPLAY_EXPANDED'] == 'Y') {
            $arResult['VISUAL']['COLLAPSED'] = false;
            break;
        }