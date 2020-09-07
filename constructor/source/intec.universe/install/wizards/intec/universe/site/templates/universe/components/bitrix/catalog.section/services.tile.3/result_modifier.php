<?php if (defined("B_PROLOG_INCLUDED") && B_PROLOG_INCLUDED === true) ?>
<?php

use intec\core\collections\Arrays;
use intec\core\helpers\ArrayHelper;
use intec\core\helpers\Type;

$arParams = ArrayHelper::merge([
    'COLUMNS' => 3,
    'CATEGORY_SHOW' => 'N',
    'PROPERTY_CATEGORY' => null,
    'CATEGORY_POSITION' => 'center',
    'PRICE_SHOW' => 'N',
    'PRICE_CATEGORY' => null,
    'PRICE_POSITION' => 'center',
    'NAME_POSITION' => 'center',
    'DESCRIPTION_SHOW' => 'Y',
    'DESCRIPTION_POSITION' => 'center',
    'ORDER_USE' => 'N',
    'ORDER_FORM_ID' => null,
    'ORDER_FORM_TEMPLATE' => null,
    'ORDER_FORM_FIELD' => null,
    'ORDER_FORM_TITLE' => null,
    'ORDER_FORM_CONSENT' => null,
    'ORDER_BUTTON' => null,
    'ORDER_BUTTON_POSITION' => 'center',
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
    'CATEGORY' => [
        'SHOW' => $arParams['CATEGORY_SHOW'] === 'Y',
        'POSITION' => ArrayHelper::fromRange([
            'left',
            'center',
            'right'
        ], $arParams['CATEGORY_POSITION'])
    ],
    'PRICE' => [
        'SHOW' => $arParams['PRICE_SHOW'] === 'Y',
        'POSITION' => ArrayHelper::fromRange([
            'left',
            'center',
            'right'
        ], $arParams['PRICE_POSITION'])
    ],
    'ORDER_BUTTON' => [
        'POSITION' => ArrayHelper::fromRange([
            'left',
            'center',
            'right'
        ], $arParams['ORDER_BUTTON_POSITION'])
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

$arForm = [
    'USE' => $arParams['ORDER_USE'] === 'Y',
    'ID' => $arParams['ORDER_FORM_ID'],
    'TEMPLATE' => $arParams['ORDER_FORM_TEMPLATE'],
    'FIELD' => $arParams['ORDER_FORM_FIELD'],
    'TITLE' => $arParams['ORDER_FORM_TITLE'],
    'CONSENT' => $arParams['ORDER_FORM_CONSENT'],
    'BUTTON' => $arParams['ORDER_BUTTON']
];

if ($arForm['USE'])
    if (empty($arForm['ID']) || empty($arForm['TEMPLATE']) || empty($arForm['FIELD']))
        $arForm['USE'] = false;

$arResult['FORM'] = $arForm;

unset($arForm);

$arSectionsID = [];

foreach ($arResult['ITEMS'] as $arItem) {
    $arSectionsID[] = $arItem['~IBLOCK_SECTION_ID'];
}

$arSectionsID = array_unique($arSectionsID);

$arSections = Arrays::fromDBResult(CIBlockSection::GetList([], [
    'ACTIVE' => 'Y',
    'IBLOCK_ID' => $arParams['IBLOCK_ID'],
    'ID' => $arSectionsID
]))->indexBy('ID');

$hSetPropertyList = function (&$arItem, $property) {
    $arReturn = [
        'VALUE' => null,
        'TITLE' => null
    ];

    if (!empty($property)) {
        $arProperty = ArrayHelper::getValue($arItem, [
            'PROPERTIES',
            $property
        ]);

        if (!empty($arProperty['VALUE'])) {
            if (Type::isArray($arProperty['VALUE']))
                $arReturn['VALUE'] = implode(', ', $arProperty['VALUE']);
            else
                $arReturn['VALUE'] = $arProperty['VALUE'];

            $arReturn['TITLE'] = $arProperty['NAME'];
        }
    }

    return $arReturn;
};

foreach ($arResult['ITEMS'] as &$arItem) {
    $arItem['DATA'] = [
        'CATEGORY' => $hSetPropertyList($arItem, $arParams['PROPERTY_CATEGORY']),
        'PRICE' => ArrayHelper::getValue($arItem, ['PROPERTIES', $arParams['PROPERTY_PRICE'], 'VALUE'])
    ];
}