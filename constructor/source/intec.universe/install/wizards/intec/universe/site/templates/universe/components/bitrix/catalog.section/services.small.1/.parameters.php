<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die() ?>
<?php

use Bitrix\Main\Localization\Loc;
use intec\core\collections\Arrays;

/**
 * @var array $arCurrentValues
 * @var string $componentName
 * @var string $componentTemplate
 * @var string $siteTemplate
 */

if (!empty($arCurrentValues['IBLOCK_ID'])) {
    $arProperties = Arrays::fromDBResult(CIBlockProperty::GetList([
        'SORT' => 'ASC'
    ], [
        'ACTIVE' => 'Y',
        'IBLOCK_ID' => $arCurrentValues['IBLOCK_ID']
    ]));

    $hPropertiesString = function ($iIndex, $arProperty) {
        if (empty($arProperty['CODE']))
            return ['skip' => true];

        if ($arProperty['PROPERTY_TYPE'] === 'S' && $arProperty['MULTIPLE'] !== 'Y')
            return [
                'key' => $arProperty['CODE'],
                'value' => '[' . $arProperty['CODE'] . '] ' . $arProperty['NAME']
            ];

        return ['skip' => true];
    };

    $hPropertiesList = function ($iIndex, $arProperty) {
        if (empty($arProperty['CODE']))
            return ['skip' => true];

        if ($arProperty['PROPERTY_TYPE'] === 'L' && $arProperty['LIST_TYPE'] === 'L' && empty($arProperty['USER_TYPE']))
            return [
                'key' => $arProperty['CODE'],
                'value' => '[' . $arProperty['CODE'] . '] ' . $arProperty['NAME']
            ];

        return ['skip' => true];
    };
}

$arTemplateParameters = [];

$arTemplateParameters['PROPERTY_PRICE'] = [
    'PARENT' => 'DATA_SOURCE',
    'NAME' => Loc::getMessage('C_CATALOG_SECTION_SERVICES_SMALL_1_PROPERTY_PRICE'),
    'TYPE' => 'LIST',
    'VALUES' => $arProperties->asArray($hPropertiesString)
];

$arTemplateParameters['CURRENCY'] = [
    'PARENT' => 'DATA_SOURCE',
    'NAME' => Loc::getMessage('C_CATALOG_SECTION_SERVICES_SMALL_1_CURRENCY'),
    'TYPE' => 'STRING'
];

$arTemplateParameters['PROPERTY_CURRENCY'] = [
    'PARENT' => 'DATA_SOURCE',
    'NAME' => Loc::getMessage('C_CATALOG_SECTION_SERVICES_SMALL_1_PROPERTY_CURRENCY'),
    'TYPE' => 'LIST',
    'VALUES' => $arProperties->asArray($hPropertiesList)
];

$arTemplateParameters['PRICE_FORMAT'] = [
    'PARENT' => 'DATA_SOURCE',
    'NAME' => Loc::getMessage('C_CATALOG_SECTION_SERVICES_SMALL_1_PRICE_FORMAT'),
    'TYPE' => 'STRING',
    'DEFAULT' => '#VALUE# #CURRENCY#'
];

$arTemplateParameters['PROPERTY_PRICE_FORMAT'] = [
    'PARENT' => 'DATA_SOURCE',
    'NAME' => Loc::getMessage('C_CATALOG_SECTION_SERVICES_SMALL_1_PROPERTY_PRICE_FORMAT'),
    'TYPE' => 'LIST',
    'VALUES' => $arProperties->asArray($hPropertiesString)
];

$arTemplateParameters['BORDERS'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_CATALOG_SECTION_SERVICES_SMALL_1_BORDERS'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'Y'
];

$arTemplateParameters['COLUMNS'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_CATALOG_SECTION_SERVICES_SMALL_1_COLUMNS'),
    'TYPE' => 'LIST',
    'VALUES' => [
        2 => 2,
        3 => 3,
        4 => 4
    ],
    'DEFAULT' => 3
];

$arTemplateParameters['POSITION'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_CATALOG_SECTION_SERVICES_SMALL_1_POSITION'),
    'TYPE' => 'LIST',
    'VALUES' => [
        'left' => Loc::getMessage('C_CATALOG_SECTION_SERVICES_SMALL_1_POSITION_LEFT'),
        'center' => Loc::getMessage('C_CATALOG_SECTION_SERVICES_SMALL_1_POSITION_CENTER'),
        'right' => Loc::getMessage('C_CATALOG_SECTION_SERVICES_SMALL_1_POSITION_RIGHT')
    ],
    'DEFAULT' => 'left'
];

$arTemplateParameters['SIZE'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_CATALOG_SECTION_SERVICES_SMALL_1_SIZE'),
    'TYPE' => 'LIST',
    'VALUES' => [
        'small' => Loc::getMessage('C_CATALOG_SECTION_SERVICES_SMALL_1_SIZE_SMALL'),
        'big' => Loc::getMessage('C_CATALOG_SECTION_SERVICES_SMALL_1_SIZE_BIG')
    ],
    'DEFAULT' => 'big'
];

$arTemplateParameters['SLIDER_USE'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_CATALOG_SECTION_SERVICES_SMALL_1_SLIDER_USE'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'N'
];

if ($arCurrentValues['SLIDER_USE'] === 'Y') {
    $arTemplateParameters['SLIDER_DOTS'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_CATALOG_SECTION_SERVICES_SMALL_1_SLIDER_DOTS'),
        'TYPE' => 'CHECKBOX',
        'DEFAULT' => 'N'
    ];

    $arTemplateParameters['SLIDER_NAVIGATION'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_CATALOG_SECTION_SERVICES_SMALL_1_SLIDER_NAVIGATION'),
        'TYPE' => 'CHECKBOX',
        'DEFAULT' => 'N'
    ];

    $arTemplateParameters['SLIDER_LOOP'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_CATALOG_SECTION_SERVICES_SMALL_1_SLIDER_LOOP'),
        'TYPE' => 'CHECKBOX',
        'DEFAULT' => 'N'
    ];

    $arTemplateParameters['SLIDER_AUTO_PLAY_USE'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_CATALOG_SECTION_SERVICES_SMALL_1_SLIDER_AUTO_PLAY_USE'),
        'TYPE' => 'CHECKBOX',
        'DEFAULT' => 'N'
    ];

    if ($arTemplateParameters['SLIDER_AUTO_PLAY'] === 'Y') {
        $arTemplateParameters['SLIDER_AUTO_PLAY_TIME'] = [
            'PARENT' => 'VISUAL',
            'NAME' => Loc::getMessage('C_CATALOG_SECTION_SERVICES_SMALL_1_SLIDER_AUTO_PLAY_TIME'),
            'TYPE' => 'STRING',
            'DEFAULT' => 1000
        ];

        $arTemplateParameters['SLIDER_AUTO_PLAY_SPEED'] = [
            'PARENT' => 'VISUAL',
            'NAME' => Loc::getMessage('C_CATALOG_SECTION_SERVICES_SMALL_1_SLIDER_AUTO_PLAY_SPEED'),
            'TYPE' => 'STRING',
            'DEFAULT' => 500
        ];

        $arTemplateParameters['SLIDER_AUTO_PLAY_HOVER_PAUSE'] = [
            'PARENT' => 'VISUAL',
            'NAME' => Loc::getMessage('C_CATALOG_SECTION_SERVICES_SMALL_1_SLIDER_AUTO_PLAY_HOVER_PAUSE'),
            'TYPE' => 'CHECKBOX',
            'DEFAULT' => 'N'
        ];
    }
}