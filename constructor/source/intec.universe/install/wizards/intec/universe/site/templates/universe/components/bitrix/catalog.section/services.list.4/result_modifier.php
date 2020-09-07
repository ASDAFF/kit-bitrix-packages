<?php if (defined("B_PROLOG_INCLUDED") && B_PROLOG_INCLUDED === true) ?>
<?php

use Bitrix\Main\Loader;
use intec\core\collections\Arrays;
use intec\core\helpers\ArrayHelper;
use intec\core\helpers\StringHelper;

if (!Loader::includeModule('intec.core'))
    return;

$arParams = ArrayHelper::merge([
    'WIDE' => 'Y',
    'LAZY_LOAD' => 'N',
    'PROPERTY_PRICE' => null
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
    'WIDE' => $arParams['WIDE'] === 'Y',
    'PRICE' => [
        'SHOW' => $arParams['PRICE_SHOW'] === 'Y'
    ]
];

$arResult['VISUAL'] = $arVisual;


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

foreach ($arResult['ITEMS'] as &$arItem) {
    $arItem['DATA'] = [];

    if ($arSections->exists($arItem['~IBLOCK_SECTION_ID'])) {
        $arSection = $arSections->get($arItem['~IBLOCK_SECTION_ID']);

        $arMacros = [
            'SITE_DIR' => SITE_DIR,
            'SECTION_CODE' => $arSection['CODE'],
            'SECTION_ID' => $arSection['ID']
        ];

        $arItem['DATA']['SECTION'] = [
            'NAME' => $arSection['NAME'],
            'LINK' => StringHelper::replaceMacros($arSection['SECTION_PAGE_URL'], $arMacros)
        ];
    }

    $arItem['DATA']['PRICE'] = [
        'VALUE' => ArrayHelper::getValue($arItem, ['PROPERTIES', $arParams['PROPERTY_PRICE'], 'VALUE'])
    ];
}