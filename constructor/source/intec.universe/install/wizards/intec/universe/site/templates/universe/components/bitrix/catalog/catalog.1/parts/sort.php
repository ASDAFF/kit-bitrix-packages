<?php if (defined("B_PROLOG_INCLUDED") && B_PROLOG_INCLUDED === true) ?>
<?php

use Bitrix\Main\Localization\Loc;
use intec\Core;
use intec\core\helpers\ArrayHelper;

/**
 * @var array $arParams
 * @var array $arResult
 * @var string $sLevel
 */

Loc::loadMessages(__FILE__);

$arParams = ArrayHelper::merge([
    'LIST_SORT_PRICE' => null
], $arParams);

$arSort = [
    'PROPERTY' => Core::$app->request->get('sort'),
    'ORDER' => Core::$app->request->get('order'),
    'PROPERTIES' => [
        'POPULAR' => [
            'NAME' => Loc::getMessage('C_CATALOG_CATALOG_1_SORTING_POPULAR'),
            'FIELD' => 'show_counter',
            'ICON' => 'far fa-star',
            'VALUE' => 'popular'
        ],
        'NAME' => [
            'NAME' => Loc::getMessage('C_CATALOG_CATALOG_1_SORTING_NAME'),
            'FIELD' => 'name',
            'ICON' => 'fal fa-font',
            'VALUE' => 'name'
        ],
        'PRICE' => [
            'NAME' => Loc::getMessage('C_CATALOG_CATALOG_1_SORTING_PRICE'),
            'FIELD' => null,
            'ICON' => 'fal fa-ruble-sign',
            'VALUE' => 'price'
        ]
    ]
];

if (!empty($arParams['LIST_SORT_PRICE'])) {
    if ($bIsBase) {
        $arSort['PROPERTIES']['PRICE']['FIELD'] = 'catalog_PRICE_'.$arParams['LIST_SORT_PRICE'];
    } else if ($bIsLite) {
        $arSort['PROPERTIES']['PRICE']['FIELD'] = 'property_STARTSHOP_PRICE_'.$arParams['LIST_SORT_PRICE'];
    }
}

if (empty($arSort['PROPERTIES']['PRICE']['FIELD']))
    unset($arSort['PROPERTIES']['PRICE']);

$arSort['ORDER'] = ArrayHelper::fromRange([
    'asc',
    'desc'
], $arSort['ORDER']);

foreach ($arSort['PROPERTIES'] as &$arSortProperty) {
    $arSortProperty['ACTIVE'] = $arSortProperty['VALUE'] === $arSort['PROPERTY'];
}

unset($arSort['PROPERTY']);
unset($arSortProperty);