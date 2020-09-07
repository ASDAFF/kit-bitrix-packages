<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die() ?>
<?php

use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use intec\core\collections\Arrays;

/**
 * @var array $arCurrentValues
 */

if (!Loader::includeModule('iblock'))
    return;

if (!Loader::includeModule('intec.core'))
    return;

$arIBlocksTypes = CIBlockParameters::GetIBlockTypes();
$arIBlock = null;
$arFilter = [
    'ACTIVE' => 'Y'
];

if (!empty($arCurrentValues['IBLOCK_TYPE']))
    $arFilter['TYPE'] = $arCurrentValues['IBLOCK_TYPE'];

$arIBlocks = Arrays::fromDBResult(CIBlock::GetList([
    'SORT' => 'ASC'
], $arFilter))->indexBy('ID');

if (!empty($arCurrentValues['IBLOCK_ID']))
    $arIBlock = $arIBlocks->get($arCurrentValues['IBLOCK_ID']);

$arSections = Arrays::from([]);

if (!empty($arIBlock)) {
    $arSections = Arrays::fromDBResult(CIBlockSection::GetList([
        'SORT' => 'ASC'
    ], [
        'IBLOCK_ID' => $arIBlock['ID'],
        'GLOBAL_ACTIVE' => 'Y'
    ]))->indexBy('ID');

    $arProperties = Arrays::fromDBResult(CIBlockProperty::GetList([], [
        'ACTIVE' => 'Y',
        'IBLOCK_ID' => $arIBlock['ID']
    ]))->indexBy('ID');

    $hPropertiesList = function ($sKey, $arProperty) {
        return [
            'key' => $arProperty['CODE'],
            'value' => '['.$arProperty['CODE'].'] '.$arProperty['NAME']
        ];
    };
}

$arParameters = [];

/** BASE */
$arParameters['IBLOCK_TYPE'] = [
    'PARENT' => 'BASE',
    'NAME' => Loc::getMessage('C_MAIN_RATES_IBLOCK_TYPE'),
    'TYPE' => 'LIST',
    'VALUES' => $arIBlocksTypes,
    'ADDITIONAL_VALUES' => 'Y',
    'REFRESH' => 'Y'
];
$arParameters['IBLOCK_ID'] = [
    'PARENT' => 'BASE',
    'NAME' => Loc::getMessage('C_MAIN_RATES_IBLOCK_ID'),
    'TYPE' => 'LIST',
    'VALUES' => $arIBlocks->asArray(function ($iId, $arIBlock) {
        return [
            'key' => $arIBlock['ID'],
            'value' => '['.$arIBlock['ID'].'] '.$arIBlock['NAME']
        ];
    }),
    'ADDITIONAL_VALUES' => 'Y',
    'REFRESH' => 'Y'
];

if (!empty($arIBlock)) {
    $arParameters['SECTIONS'] = [
        'PARENT' => 'BASE',
        'NAME' => Loc::getMessage('C_MAIN_RATES_SECTIONS'),
        'TYPE' => 'LIST',
        'VALUES' => $arSections->asArray(function ($iIndex, $arSection) {
            return [
                'key' => $arSection['ID'],
                'value' => '['.$arSection['ID'].'] '.$arSection['NAME']
            ];
        }),
        'MULTIPLE' => 'Y',
        'ADDITIONAL_VALUES' => 'Y',
        'REFRESH' => 'Y'
    ];
}

$arParameters['ELEMENTS_COUNT'] = [
    'PARENT' => 'BASE',
    'NAME' => Loc::getMessage('C_MAIN_RATES_ELEMENTS_COUNT'),
    'TYPE' => 'STRING'
];

/** DATA_SOURCE */
if (!empty($arIBlock)) {
    $arParameters['PROPERTY_LIST'] = [
        'PARENT' => 'DATA_SOURCE',
        'NAME' => Loc::getMessage('C_MAIN_RATES_PROPERTY_LIST'),
        'TYPE' => 'LIST',
        'VALUES' => $arProperties->asArray($hPropertiesList),
        'ADDITIONAL_VALUES' => 'Y',
        'MULTIPLE' => 'Y'
    ];
}

/** VISUAL */
$arParameters['HEADER_SHOW'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_MAIN_RATES_HEADER_SHOW'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'N',
    'REFRESH' => 'Y'
];

if ($arCurrentValues['HEADER_SHOW'] === 'Y') {
    $arParameters['HEADER_POSITION'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_MAIN_RATES_HEADER_POSITION'),
        'TYPE' => 'LIST',
        'VALUES' => [
            'left' => Loc::getMessage('C_MAIN_RATES_POSITION_LEFT'),
            'center' => Loc::getMessage('C_MAIN_RATES_POSITION_CENTER'),
            'right' => Loc::getMessage('C_MAIN_RATES_POSITION_RIGHT')
        ],
        'DEFAULT' => 'center'
    ];
    $arParameters['HEADER_TEXT'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_MAIN_RATES_HEADER_TEXT'),
        'TYPE' => 'STRING',
        'DEFAULT' => Loc::getMessage('C_MAIN_RATES_HEADER_TEXT_DEFAULT')
    ];
}

$arParameters['DESCRIPTION_SHOW'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_MAIN_RATES_DESCRIPTION_SHOW'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'N',
    'REFRESH' => 'Y'
];

if ($arCurrentValues['DESCRIPTION_SHOW'] === 'Y') {
    $arParameters['DESCRIPTION_POSITION'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_MAIN_RATES_DESCRIPTION_POSITION'),
        'TYPE' => 'LIST',
        'VALUES' => [
            'left' => Loc::getMessage('C_MAIN_RATES_POSITION_LEFT'),
            'center' => Loc::getMessage('C_MAIN_RATES_POSITION_CENTER'),
            'right' => Loc::getMessage('C_MAIN_RATES_POSITION_RIGHT')
        ],
        'DEFAULT' => 'center'
    ];
    $arParameters['DESCRIPTION_TEXT'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_MAIN_RATES_DESCRIPTION_TEXT'),
        'TYPE' => 'STRING'
    ];
}

/** SORT */
$arParameters['SORT_BY'] = [
    'PARENT' => 'SORT',
    'NAME' => Loc::getMessage('C_MAIN_RATES_SORT_BY'),
    'TYPE' => 'LIST',
    'VALUES' => CIBlockParameters::GetElementSortFields(),
    'DEFAULT' => 'SORT',
    'ADDITIONAL_VALUES' => 'Y'
];
$arParameters['ORDER_BY'] = [
    'PARENT' => 'SORT',
    'NAME' => Loc::getMessage('C_MAIN_RATES_ORDER_BY'),
    'TYPE' => 'LIST',
    'VALUES' => [
        'ASC' => Loc::getMessage('C_MAIN_RATES_ORDER_BY_ASC'),
        'DESC' => Loc::getMessage('C_MAIN_RATES_ORDER_BY_DESC')
    ],
    'DEFAULT' => 'ASC'
];

/** CACHE */
$arParameters['CACHE_TIME'] = [];

$arComponentParameters = [
    'GROUPS' => [
        'SORT' => [
            'NAME' => Loc::getMessage('C_MAIN_RATES_GROUPS_SORT'),
            'SORT' => 800
        ]
    ],
    'PARAMETERS' => $arParameters
];