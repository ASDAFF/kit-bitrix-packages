<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Loader;
use intec\core\collections\Arrays;
use intec\core\helpers\ArrayHelper;
use intec\core\helpers\StringHelper;
use intec\core\helpers\Type;

/**
 * @var array $arResult
 * @var array $arParams
 */

if (!Loader::includeModule('intec.core'))
    return;

$arParams = ArrayHelper::merge([
    'PROPERTY_VIDEO' => null,
    'PROPERTY_DOCUMENT' => null,
    'PROPERTY_SERVICES' => null,
    'PROPERTY_CASES' => null,
    'PROPERTY_PERSON_NAME' => null,
    'PROPERTY_PERSON_POSITION' => null,
    'PROPERTY_SITE_URL' => null,
    'VIDEO_IBLOCK_TYPE' => null,
    'VIDEO_IBLOCK_ID' => null,
    'VIDEO_PROPERTY_LINK' => null,

    'VIDEO_SHOW' => 'N',
    'DOCUMENT_SHOW' => 'N',
    'SERVICES_SHOW' => 'N',
    'CASE_SHOW' => 'N',
], $arParams);

$arMacros = [
    'SITE_DIR' => SITE_DIR
];

$arVisual = [
    'VIDEO' => [
        'SHOW' => $arParams['VIDEO_SHOW'] === 'Y'
    ],
    'DOCUMENT' => [
        'SHOW' => $arParams['DOCUMENT_SHOW'] === 'Y'
    ],
    'SERVICES' => [
        'SHOW' => $arParams['SERVICES_SHOW'] === 'Y'
    ],
    'CASE' => [
        'SHOW' => $arParams['CASE_SHOW'] === 'Y'
    ],
];

$arResult['VISUAL'] = $arVisual;

unset($arVisual);

$hSetProperty = function (&$arItem, $property = null, $setName = false) {
    if (!empty($property)) {
        $arProperty = ArrayHelper::getValue($arItem, [
            'PROPERTIES',
            $property
        ]);

        if (!empty($arProperty['VALUE'])) {
            if (Type::isArray($arProperty['VALUE'])) {
                if (ArrayHelper::keyExists('TEXT', $arProperty['VALUE']))
                    $sText = $arProperty['TEXT'];
                else
                    $sText = ArrayHelper::getFirstValue($arProperty['VALUE']);
            } else {
                $sText = $arProperty['VALUE'];
            }

            return [
                'SHOW' => true,
                'VALUE' => $sText
            ];
        } else if ($setName)
            return [
                'SHOW' => true,
                'VALUE' => $arItem['NAME']
            ];
    }

    return ['SHOW' => false];
};

$hGetDisplayValue = function ($arItem, $property) {
    $arProperty = ArrayHelper::getValue($arItem, [
        'PROPERTIES',
        $property
    ]);

    if (!empty($arProperty)) {
        $arProperty = CIBlockFormatProperties::GetDisplayValue(
            $arItem,
            $arProperty,
            'newsListReviews'
        );

        return $arProperty;
    }

    return null;
};

$arServicesId = [];

foreach ($arResult['ITEMS'] as &$arItem) {
    $arItem['DATA'] = [
        'VIDEO' => $hSetProperty($arItem, $arParams['PROPERTY_VIDEO']),
        'DOCUMENT' => $hSetProperty($arItem, $arParams['PROPERTY_DOCUMENT']),
        'PERSON' => [
            'NAME' => $hSetProperty($arItem, $arParams['PROPERTY_PERSON_NAME']),
            'POSITION' => $hSetProperty($arItem, $arParams['PROPERTY_PERSON_POSITION']),
            'SITE_URL' => $hSetProperty($arItem, $arParams['PROPERTY_SITE_URL']),
        ],
        'SERVICES' => ArrayHelper::getValue($arItem, ['PROPERTIES', $arParams['PROPERTY_SERVICES']]),
        'CASE' => ArrayHelper::getValue($arItem, ['PROPERTIES', $arParams['PROPERTY_CASES']])
    ];

    $arServicesId = ArrayHelper::merge($arItem['DATA']['SERVICES']['VALUE'], $arServicesId);
    $arCasesId = ArrayHelper::merge($arItem['DATA']['CASE']['VALUE'], $arCasesId);

    $arItemsId[] = $arItem['DOCUMENT']['VALUE'];
}

$arServices = Arrays::fromDBResult(CIBlockElement::GetList([], [
        'IBLOCK_ID' => $arParams['SERVICES_IBLOCK_ID'],
        'ID' => $arServicesId,
    ], false,
    false, [
        'ID',
        'IBLOCK_ID',
        'NAME',
        'DETAIL_PAGE_URL',
        'PROPERTY_'.$arParams['SERVICES_PROPERTY_LINK']
]), true)->indexBy('ID')->asArray();

foreach ($arServices as &$arService) {
    if ($arParams['SERVICES_LINK_MODE'] === 'property') {
        if ($arService['PROPERTY_'.$arParams['SERVICES_PROPERTY_LINK'].'_VALUE']) {
            $arService['DETAIL_PAGE_URL'] = StringHelper::replaceMacros(
                $arService['PROPERTY_' . $arParams['SERVICES_PROPERTY_LINK'] . '_VALUE'],
                $arMacros
            );
        } else {
            $arService['DETAIL_PAGE_URL'] = '';
        }
    }
}

unset($arService);

$arCases = Arrays::fromDBResult(CIBlockElement::GetList([], [
        'IBLOCK_ID' => $arParams['CASES_IBLOCK_ID'],
        'ID' => $arCasesId,
    ],
    false,
    false, [
        'ID',
        'IBLOCK_ID',
        'NAME',
        'DETAIL_PAGE_URL',
        'PROPERTY_'.$arParams['CASES_PROPERTY_LINK']
]), true)->indexBy('ID')->asArray();

foreach ($arCases as &$arCase) {
    if ($arParams['CASES_LINK_MODE'] === 'property') {
        $arCase['DETAIL_PAGE_URL'] = StringHelper::replaceMacros(
            $arCase['PROPERTY_'.$arParams['CASES_PROPERTY_LINK'].'_VALUE'],
            $arMacros
        );
    }
}

unset($arCase);

foreach ($arResult['ITEMS'] as &$arItem) {
    $arServicesItems = [];

    foreach ($arItem['DATA']['SERVICES']['VALUE'] as $arService) {
        $arServicesItems[] = $arServices[$arService];
    }

    $arItem['DATA']['SERVICES'] = $arServicesItems;

    if (Type::isArray($arItem['DATA']['CASE']['VALUE']))
        $arItem['DATA']['CASE'] = $arCases[ArrayHelper::getFirstValue($arItem['DATA']['CASE']['VALUE'])];
    else
        $arItem['DATA']['CASE'] = $arCases[$arItem['DATA']['CASE']['VALUE']];
}

$arElements = Arrays::fromDBResult(CFile::GetList([], [
    'ID' => $arItemsId
]))->indexBy('ID');

$hImages = function ($sKey, $arProperty) {
    if (!empty($arProperty))
        return [
            'key' => $arProperty['ID'],
            'value' => $arProperty
        ];

    return ['skip' => true];
};

$arImages = $arElements->asArray($hImages);

foreach ($arResult['ITEMS'] as &$arItem) {
    $arItem['DATA']['DOCUMENT']['VALUE'] = $arImages[$arItem['DATA']['DOCUMENT']['VALUE']];
}

unset($arItem);