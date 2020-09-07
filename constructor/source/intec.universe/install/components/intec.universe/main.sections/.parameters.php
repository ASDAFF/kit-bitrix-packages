<?php if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die() ?>
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

if ($arIBlock) {
    $arFilter = [
        'ACTIVE' => 'Y',
        'IBLOCK_ID' => $arIBlock['ID']
    ];

    $arSections = Arrays::fromDBResult(CIBlockSection::GetList([
        'SORT' => 'ASC'
    ], $arFilter))->indexBy('ID');

    if ($arCurrentValues['SECTIONS_MODE'] === 'code')
        $arSections = $arSections->asArray(function ($id, $arSection) {
            if (!empty($arSection['CODE']))
                return [
                    'key' => $arSection['CODE'],
                    'value' => '['.$arSection['CODE'].'] '.$arSection['NAME']
                ];

            return ['skip' => true];
        });
    else
        $arSections = $arSections->asArray(function ($id, $arSection) {
            return [
                'key' => $arSection['ID'],
                'value' => '['.$arSection['ID'].'] '.$arSection['NAME']
            ];
        });
}

$arParameters = [];

/** BASE */
$arParameters['IBLOCK_TYPE'] = [
    'PARENT' => 'BASE',
    'NAME' => Loc::getMessage('C_MAIN_SECTIONS_IBLOCK_TYPE'),
    'TYPE' => 'LIST',
    'VALUES' => $arIBlocksTypes,
    'ADDITIONAL_VALUES' => 'Y',
    'REFRESH' => 'Y'
];

$arParameters['IBLOCK_ID'] = [
    'PARENT' => 'BASE',
    'NAME' => Loc::getMessage('C_MAIN_SECTIONS_IBLOCK_ID'),
    'TYPE' => 'LIST',
    'VALUES' => $arIBlocks->asArray(function ($sKey, $arIBlock) {
        return [
            'key' => $arIBlock['ID'],
            'value' => '['.$arIBlock['ID'].'] '.$arIBlock['NAME']
        ];
    }),
    'ADDITIONAL_VALUES' => 'Y',
    'REFRESH' => 'Y'
];

if (!empty($arIBlock)) {
    $arParameters['QUANTITY'] = [
        'PARENT' => 'BASE',
        'NAME' => Loc::getMessage('C_MAIN_SECTIONS_QUANTITY'),
        'TYPE' => 'CHECKBOX'
    ];
    $arParameters['SECTIONS_MODE'] = [
        'PARENT' => 'BASE',
        'NAME' => Loc::getMessage('C_MAIN_SECTIONS_SECTIONS_MODE'),
        'TYPE' => 'LIST',
        'VALUES' => [
            'id' => Loc::getMessage('C_MAIN_SECTIONS_SECTIONS_MODE_ID'),
            'code' => Loc::getMessage('C_MAIN_SECTIONS_SECTIONS_MODE_CODE')
        ],
        'DEFAULT' => 'id',
        'REFRESH' => 'Y'
    ];
    $arParameters['SECTIONS'] = [
        'PARENT' => 'BASE',
        'NAME' => Loc::getMessage('C_MAIN_SECTIONS_SECTIONS'),
        'TYPE' => 'LIST',
        'VALUES' => $arSections,
        'MULTIPLE' => 'Y',
        'ADDITIONAL_VALUES' => 'Y'
    ];
    $arParameters['DEPTH'] = [
        'PARENT' => 'BASE',
        'NAME' => Loc::getMessage('C_MAIN_SECTIONS_DEPTH'),
        'TYPE' => 'LIST',
        'VALUES' => [
            1 => '1',
            2 => '2',
            3 => '3'
        ]
    ];
}

$arParameters['ELEMENTS_COUNT'] = [
    'PARENT' => 'BASE',
    'NAME' => Loc::getMessage('C_MAIN_SECTIONS_ELEMENTS_COUNT'),
    'TYPE' => 'STRING'
];

/** VISUAL */
$arParameters['HEADER_SHOW'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_MAIN_SECTIONS_HEADER_SHOW'),
    'TYPE' => 'CHECKBOX',
    'DEFUALT' => 'N',
    'REFRESH' => 'Y'
];

if ($arCurrentValues['HEADER_SHOW'] === 'Y') {
    $arParameters['HEADER_POSITION'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_MAIN_SECTIONS_HEADER_POSITION'),
        'TYPE' => 'LIST',
        'VALUES' => [
            'left' => Loc::getMessage('C_MAIN_SECTIONS_POSITION_LEFT'),
            'center' => Loc::getMessage('C_MAIN_SECTIONS_POSITION_CENTER'),
            'right' => Loc::getMessage('C_MAIN_SECTIONS_POSITION_RIGHT')
        ],
        'DEFAULT' => 'center'
    ];

    $arParameters['HEADER_TEXT'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_MAIN_SECTIONS_HEADER_TEXT'),
        'TYPE' => 'STRING',
        'DEFAULT' => Loc::getMessage('C_MAIN_SECTIONS_HEADER_TEXT_DEFAULT')
    ];
}

$arParameters['DESCRIPTION_SHOW'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_MAIN_SECTIONS_DESCRIPTION_SHOW'),
    'TYPE' => 'CHECKBOX',
    'DEFUALT' => 'N',
    'REFRESH' => 'Y'
];

if ($arCurrentValues['DESCRIPTION_SHOW'] === 'Y') {
    $arParameters['DESCRIPTION_POSITION'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_MAIN_SECTIONS_DESCRIPTION_POSITION'),
        'TYPE' => 'LIST',
        'VALUES' => [
            'left' => Loc::getMessage('C_MAIN_SECTIONS_POSITION_LEFT'),
            'center' => Loc::getMessage('C_MAIN_SECTIONS_POSITION_CENTER'),
            'right' => Loc::getMessage('C_MAIN_SECTIONS_POSITION_RIGHT')
        ],
        'DEFAULT' => 'center'
    ];

    $arParameters['DESCRIPTION_TEXT'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_MAIN_SECTIONS_DESCRIPTION_TEXT'),
        'TYPE' => 'STRING'
    ];
}

/** URL_TEMPLATES */
$arParameters['LIST_PAGE_URL'] = [
    'PARENT' => 'URL_TEMPLATES',
    'NAME' => Loc::getMessage('C_MAIN_SECTIONS_LIST_PAGE_URL'),
    'TYPE' => 'STRING'
];

$arParameters['SECTION_URL'] = CIBlockParameters::GetPathTemplateParam(
    'SECTION',
    'SECTION_URL',
    Loc::getMessage('C_MAIN_SECTIONS_SECTION_URL'),
    '',
    'URL_TEMPLATES'
);

/** SORT */
$arParameters['SORT_BY'] = [
    'PARENT' => 'SORT',
    'NAME' => Loc::getMessage('C_MAIN_SECTIONS_SORT_BY'),
    'TYPE' => 'LIST',
    'VALUES' => CIBlockParameters::GetSectionSortFields(),
    'DEFAULT' => 'SORT',
    'ADDITIONAL_VALUES' => 'Y'
];

$arParameters['ORDER_BY'] = [
    'PARENT' => 'SORT',
    'NAME' => Loc::getMessage('C_MAIN_SECTIONS_ORDER_BY'),
    'TYPE' => 'LIST',
    'VALUES' => [
        'ASC' => Loc::getMessage('C_MAIN_SECTIONS_ORDER_BY_ASC'),
        'DESC' => Loc::getMessage('C_MAIN_SECTIONS_ORDER_BY_DESC')
    ],
    'DEFAULT' => 'ASC'
];

/** CACHE */
$arParameters['CACHE_TIME'] = [];

$arComponentParameters = [
    'GROUPS' => [
        'SORT' => [
            'NAME' => Loc::getMessage('C_MAIN_SECTIONS_GROUPS_SORT'),
            'SORT' => 800
        ]
    ],
    'PARAMETERS' => $arParameters
];