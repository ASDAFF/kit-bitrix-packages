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
}

$arParameters = [];

/** BASE */
$arParameters['IBLOCK_TYPE'] = [
    'PARENT' => 'BASE',
    'NAME' => Loc::getMessage('C_MAIN_SERVICES_IBLOCK_TYPE'),
    'TYPE' => 'LIST',
    'VALUES' => $arIBlocksTypes,
    'ADDITIONAL_VALUES' => 'Y',
    'REFRESH' => 'Y'
];

$arParameters['IBLOCK_ID'] = [
    'PARENT' => 'BASE',
    'NAME' => Loc::getMessage('C_MAIN_SERVICES_IBLOCK_ID'),
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
        'NAME' => Loc::getMessage('C_MAIN_SERVICES_SECTIONS'),
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
    'NAME' => Loc::getMessage('C_MAIN_SERVICES_ELEMENTS_COUNT'),
    'TYPE' => 'STRING'
];

/** VISUAL */
$arParameters['HEADER_BLOCK_SHOW'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_MAIN_SERVICES_HEADER_BLOCK_SHOW'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'N',
    'REFRESH' => 'Y'
];

if ($arCurrentValues['HEADER_BLOCK_SHOW'] === 'Y') {
    $arParameters['HEADER_BLOCK_POSITION'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_MAIN_SERVICES_HEADER_BLOCK_POSITION'),
        'TYPE' => 'LIST',
        'VALUES' => [
            'left' => Loc::getMessage('C_MAIN_SERVICES_POSITION_LEFT'),
            'center' => Loc::getMessage('C_MAIN_SERVICES_POSITION_CENTER'),
            'right' => Loc::getMessage('C_MAIN_SERVICES_POSITION_RIGHT')
        ],
        'DEFAULT' => 'center'
    ];

    $arParameters['HEADER_BLOCK_TEXT'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_MAIN_SERVICES_HEADER_BLOCK_TEXT'),
        'TYPE' => 'STRING',
        'DEFAULT' => Loc::getMessage('C_MAIN_SERVICES_HEADER_BLOCK_TEXT_DEFAULT')
    ];
}

$arParameters['DESCRIPTION_BLOCK_SHOW'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_MAIN_SERVICES_DESCRIPTION_BLOCK_SHOW'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'N',
    'REFRESH' => 'Y'
];

if ($arCurrentValues['DESCRIPTION_BLOCK_SHOW'] === 'Y') {
    $arParameters['DESCRIPTION_BLOCK_POSITION'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_MAIN_SERVICES_DESCRIPTION_BLOCK_POSITION'),
        'TYPE' => 'LIST',
        'VALUES' => [
            'left' => Loc::getMessage('C_MAIN_SERVICES_POSITION_LEFT'),
            'center' => Loc::getMessage('C_MAIN_SERVICES_POSITION_CENTER'),
            'right' => Loc::getMessage('C_MAIN_SERVICES_POSITION_RIGHT')
        ],
        'DEFAULT' => 'center'
    ];

    $arParameters['DESCRIPTION_BLOCK_TEXT'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_MAIN_SERVICES_DESCRIPTION_BLOCK_TEXT'),
        'TYPE' => 'STRING'
    ];
}

/** URL_TEMPLATES */
$arParameters['LIST_PAGE_URL'] = [
    'PARENT' => 'URL_TEMPLATES',
    'NAME' => Loc::getMessage('C_MAIN_SERVICES_LIST_PAGE_URL'),
    'TYPE' => 'STRING'
];

$arParameters['SECTION_URL'] = CIBlockParameters::GetPathTemplateParam(
    'SECTION',
    'SECTION_URL',
    Loc::getMessage('C_MAIN_SERVICES_SECTION_URL'),
    '',
    'URL_TEMPLATES'
);

$arParameters['DETAIL_URL'] = CIBlockParameters::GetPathTemplateParam(
    'DETAIL',
    'DETAIL_URL',
    Loc::getMessage('C_MAIN_SERVICES_DETAIL_URL'),
    '',
    'URL_TEMPLATES'
);

/** SORT */
$arParameters['SORT_BY'] = [
    'PARENT' => 'SORT',
    'NAME' => Loc::getMessage('C_MAIN_SERVICES_SORT_BY'),
    'TYPE' => 'LIST',
    'VALUES' => CIBlockParameters::GetElementSortFields(),
    'DEFAULT' => 'SORT',
    'ADDITIONAL_VALUES' => 'Y'
];

$arParameters['ORDER_BY'] = [
    'PARENT' => 'SORT',
    'NAME' => Loc::getMessage('C_MAIN_SERVICES_ORDER_BY'),
    'TYPE' => 'LIST',
    'VALUES' => [
        'ASC' => Loc::getMessage('C_MAIN_SERVICES_ORDER_BY_ASC'),
        'DESC' => Loc::getMessage('C_MAIN_SERVICES_ORDER_BY_DESC')
    ],
    'DEFAULT' => 'ASC'
];

/** CACHE */
$arParameters['CACHE_TIME'] = [];

$arComponentParameters = [
    'GROUPS' => [
        'SORT' => [
            'NAME' => Loc::getMessage('C_MAIN_SERVICES_GROUPS_SORT'),
            'SORT' => 800
        ]
    ],
    'PARAMETERS' => $arParameters
];