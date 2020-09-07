<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use intec\core\collections\Arrays;
use intec\core\helpers\ArrayHelper;

/**
 * @var array $arResult
 * @var array $arParams
 */

$arParams = ArrayHelper::merge([
    'PROPERTY_TYPE' => null,
    'WIDE' => 'N',
    'COLUMNS' => 4,
    'TABS_USE' => 'N',
    'TABS_POSITION' => 'center'
], $arParams);

$arVisual = [
    'WIDE' => $arParams['WIDE'] === 'Y',
    'COLUMNS' => ArrayHelper::fromRange([4, 3, 5], $arParams['COLUMNS']),
    'TABS' => [
        'USE' => !empty($arParams['PROPERTY_TYPE']) && $arParams['TABS_USE'] === 'Y',
        'POSITION' => ArrayHelper::fromRange(['center', 'left', 'right'], $arParams['TABS_POSITION'])
    ]
];

if (!$arVisual['WIDE'] && ($arVisual['COLUMNS'] > 4 || $arVisual['COLUMNS'] < 3))
    $arVisual['COLUMNS'] = 4;

$arResult['VISUAL'] = $arVisual;

unset($arVisual);

$arTabs = [];

if (!empty($arParams['PROPERTY_TYPE'])) {
    $arProperty = CIBlockProperty::GetList([], [
        'IBLOCK_ID' => $arParams['IBLOCK_ID'],
        'CODE' => $arParams['PROPERTY_TYPE']
    ])->GetNext();
}

if (!empty($arProperty)) {
    $rsTabs = CIBlockPropertyEnum::GetList(['SORT' => 'ASC'], [
        'PROPERTY_ID' => $arProperty['ID']
    ]);

    while ($arTab = $rsTabs->GetNext()) {
        if (empty($arTab['XML_ID']))
            continue;

        $arTabs[$arTab['XML_ID']] = [
            'CODE' => $arTab['XML_ID'],
            'NAME' => $arTab['VALUE'],
            'SORT' => $arTab['SORT'],
            'SHOW' => false
        ];
    }

    unset($arTab);
    unset($rsTabs);
}

foreach ($arResult['ITEMS'] as &$arItem) {
    $arItem['DATA'] = [
        'TYPE' => 'all'
    ];

    $arCategory = ArrayHelper::getValue($arItem, [
        'PROPERTIES',
        $arParams['PROPERTY_TYPE'],
        'VALUE_XML_ID'
    ]);

    if (!empty($arCategory) && ArrayHelper::keyExists($arCategory, $arTabs)) {
        $arItem['DATA']['TYPE'] = $arCategory;
        $arTabs[$arCategory]['SHOW'] = true;
    }
}

unset($arItem, $arCategory);

foreach ($arTabs as $iKey => $arTab) {
    if (!$arTab['SHOW']) {
        unset($arTabs[$iKey]);
    } else {
        unset($arTabs[$iKey]['SHOW']);
    }
}

$arResult['TABS'] = $arTabs;

unset($arTabs, $arTabsShow, $arCategories);

if ($arResult['VISUAL']['TABS']['USE'] && empty($arResult['TABS']))
    $arResult['VISUAL']['TABS']['USE'] = false;