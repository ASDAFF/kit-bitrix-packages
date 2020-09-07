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
    'DATE_SHOW' => 'N',
    'DATE_TYPE' => 'DATE_ACTIVE_FROM',
    'DATE_FORMAT' => 'd.m.Y',
    'IBLOCK_DESCRIPTION_SHOW' => 'Y',
    'DESCRIPTION_SHOW' => 'N'
], $arParams);

$arVisual = [
    'LAZYLOAD' => [
        'USE' => $arParams['LAZYLOAD_USE'] === 'Y'
    ],
    'DATE' => [
        'SHOW' => $arParams['DATE_SHOW'] === 'Y',
        'TYPE' => ArrayHelper::fromRange([
            'DATE_ACTIVE_FROM',
            'DATE_CREATE',
            'DATE_ACTIVE_TO',
            'TIMESTAMP_X'
        ], $arParams['DATE_TYPE']),
        'FORMAT' => $arParams['DATE_FORMAT']
    ],
    'DESCRIPTION' => [
        'SHOW' => $arParams['DESCRIPTION_SHOW'] === 'Y'
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
    $arData = [
        'DATE' => ArrayHelper::getValue($arItem, $arVisual['DATE']['TYPE'])
    ];

    if (empty($arData['DATE']))
        $arData['DATE'] = $arItem['DATE_CREATE'];

    if (!empty($arVisual['DATE']['FORMAT']))
        $arData['DATE'] = CIBlockFormatProperties::DateFormat(
            $arVisual['DATE']['FORMAT'],
            MakeTimeStamp($arData['DATE'], CSite::GetDateFormat())
        );

    $arData['DATE'] = trim($arData['DATE']);
    $arItem['DATA'] = $arData;
}

unset($arItem, $arData);

$arResult['VISUAL'] = $arVisual;