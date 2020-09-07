<?php if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

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

$arIBlockTypes = CIBlockParameters::GetIBlockTypes();
$arIBlocksList = Arrays::fromDBResult(CIBlock::GetList())->indexBy('ID');
$arSections = [];

if (!empty($arCurrentValues['IBLOCK_TYPE']))
    $arIBlocks = $arIBlocksList->asArray(function ($sKey, $arProperty) use (&$arCurrentValues) {
        if ($arProperty['IBLOCK_TYPE_ID'] === $arCurrentValues['IBLOCK_TYPE'])
            return [
                'key' => $arProperty['ID'],
                'value' => '[' . $arProperty['ID'] . '] ' . $arProperty['NAME']
            ];

        return ['skip' => true];
    });
else
    $arIBlocks = $arIBlocksList->asArray(function ($sKey, $arProperty) {
        return [
            'key' => $arProperty['ID'],
            'value' => '['.$arProperty['ID'].'] '.$arProperty['NAME']
        ];
    });

if (!empty($arCurrentValues['IBLOCK_ID'])) {
    $arSections = Arrays::fromDBResult(CIBlockSection::GetList([], [
        'IBLOCK_ID' => $arCurrentValues['IBLOCK_ID'],
        'GLOBAL_ACTIVE' => 'Y'
    ]))->indexBy('ID');

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

    $arProperties = Arrays::fromDBResult(CIBlockProperty::GetList([], [
        'IBLOCK_ID' => $arCurrentValues['IBLOCK_ID'],
        'ACTIVE' => 'Y'
    ]))->indexBy('ID');

    $hPropertyCheckbox = function ($sKey, $arProperty) {
        if ($arProperty['PROPERTY_TYPE'] === 'L' && $arProperty['LIST_TYPE'] === 'C' && $arProperty['MULTIPLE'] === 'N')
            return [
                'key' => $arProperty['CODE'],
                'value' => '['.$arProperty['CODE'].'] '.$arProperty['NAME']
            ];

        return ['skip' => true];
    };

    $arPropertyChecbox = $arProperties->asArray($hPropertyCheckbox);
}

$arParameters = [];

$arParameters['CACHE_TIME'] = [];
$arParameters['IBLOCK_TYPE'] = [
    'PARENT' => 'BASE',
    'NAME' => Loc::getMessage('C_MAIN_REVIEWS_IBLOCK_TYPE'),
    'TYPE' => 'LIST',
    'VALUES' => $arIBlockTypes,
    'ADDITIONAL_VALUES' => 'Y',
    'REFRESH' => 'Y'
];
$arParameters['IBLOCK_ID'] = [
    'PARENT' => 'BASE',
    'NAME' => Loc::getMessage('C_MAIN_REVIEWS_IBLOCK_ID'),
    'TYPE' => 'LIST',
    'VALUES' => $arIBlocks,
    'ADDITIONAL_VALUES' => 'Y',
    'REFRESH' => 'Y'
];
$arParameters['ELEMENTS_COUNT'] = [
    'PARENT' => 'BASE',
    'NAME' => Loc::getMessage('C_MAIN_REVIEWS_ELEMENTS_COUNT'),
    'TYPE' => 'STRING'
];

if (!empty($arCurrentValues['IBLOCK_ID'])) {
    $arParameters['SECTIONS_MODE'] = [
        'PARENT' => 'BASE',
        'NAME' => Loc::getMessage('C_MAIN_REVIEWS_SECTIONS_MODE'),
        'TYPE' => 'LIST',
        'VALUES' => [
            'id' => Loc::getMessage('C_MAIN_REVIEWS_SECTIONS_MODE_ID'),
            'code' => Loc::getMessage('C_MAIN_REVIEWS_SECTIONS_MODE_CODE')
        ],
        'DEFAULT' => 'id',
        'REFRESH' => 'Y'
    ];
    $arParameters['SECTIONS'] = [
        'PARENT' => 'BASE',
        'NAME' => Loc::getMessage('C_MAIN_REVIEWS_SECTIONS'),
        'TYPE' => 'LIST',
        'VALUES' => $arSections,
        'MULTIPLE' => 'Y',
        'ADDITIONAL_VALUES' => 'Y'
    ];
}

$arParameters['HEADER_SHOW'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_MAIN_REVIEWS_HEADER_SHOW'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'N',
    'REFRESH' => 'Y'
];

if ($arCurrentValues['HEADER_SHOW'] === 'Y') {
    $arParameters['HEADER_POSITION'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_MAIN_REVIEWS_HEADER_POSITION'),
        'TYPE' => 'LIST',
        'VALUES' => [
            'left' => Loc::getMessage('C_MAIN_REVIEWS_POSITION_LEFT'),
            'center' => Loc::getMessage('C_MAIN_REVIEWS_POSITION_CENTER'),
            'right' => Loc::getMessage('C_MAIN_REVIEWS_POSITION_RIGHT')
        ],
        'DEFAULT' => 'center'
    ];
    $arParameters['HEADER_TEXT'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_MAIN_REVIEWS_HEADER_TEXT'),
        'TYPE' => 'STRING',
        'DEFAULT' => Loc::getMessage('C_MAIN_REVIEWS_HEADER_TEXT_DEFAULT')
    ];
}

$arParameters['DESCRIPTION_SHOW'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_MAIN_REVIEWS_DESCRIPTION_SHOW'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'N',
    'REFRESH' => 'Y'
];

if ($arCurrentValues['DESCRIPTION_SHOW'] === 'Y') {
    $arParameters['DESCRIPTION_POSITION'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_MAIN_REVIEWS_DESCRIPTION_POSITION'),
        'TYPE' => 'LIST',
        'VALUES' => [
            'left' => Loc::getMessage('C_MAIN_REVIEWS_POSITION_LEFT'),
            'center' => Loc::getMessage('C_MAIN_REVIEWS_POSITION_CENTER'),
            'right' => Loc::getMessage('C_MAIN_REVIEWS_POSITION_RIGHT')
        ],
        'DEFAULT' => 'center'
    ];
    $arParameters['DESCRIPTION_TEXT'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_MAIN_REVIEWS_DESCRIPTION_TEXT'),
        'TYPE' => 'STRING'
    ];
}

$arParameters['LIST_PAGE_URL'] = [
    'PARENT' => 'URL_TEMPLATES',
    'NAME' => Loc::getMessage('C_MAIN_REVIEWS_LIST_PAGE_URL'),
    'TYPE' => 'STRING'
];

$arParameters['SECTION_URL'] = CIBlockParameters::GetPathTemplateParam(
    'SECTION',
    'SECTION_URL',
    Loc::getMessage('C_MAIN_REVIEWS_SECTION_URL'),
    '',
    'URL_TEMPLATES'
);

$arParameters['DETAIL_URL'] = CIBlockParameters::GetPathTemplateParam(
    'DETAIL',
    'DETAIL_URL',
    Loc::getMessage('C_MAIN_REVIEWS_DETAIL_URL'),
    '',
    'URL_TEMPLATES'
);
$arParameters['SORT_BY'] = [
    'PARENT' => 'SORT',
    'NAME' => Loc::getMessage('C_MAIN_REVIEWS_SORT_BY'),
    'TYPE' => 'LIST',
    'VALUES' => [
        'ID' => Loc::getMessage('C_MAIN_REVIEWS_SORT_BY_ID'),
        'SORT' => Loc::getMessage('C_MAIN_REVIEWS_SORT_BY_SORT'),
        'NAME' => Loc::getMessage('C_MAIN_REVIEWS_SORT_BY_NAME')
    ],
    'DEFAULT' => 'SORT'
];
$arParameters['ORDER_BY'] = [
    'PARENT' => 'SORT',
    'NAME' => Loc::getMessage('C_MAIN_REVIEWS_ORDER_BY'),
    'TYPE' => 'LIST',
    'VALUES' => [
        'ASC' => Loc::getMessage('C_MAIN_REVIEWS_ORDER_BY_ASC'),
        'DESC' => Loc::getMessage('C_MAIN_REVIEWS_ORDER_BY_DESC')
    ],
    'DEFAULT' => 'ASC'
];

$arComponentParameters = [
    'GROUPS' => [
        'SORT' => [
            'NAME' => Loc::getMessage('C_MAIN_REVIEWS_GROUPS_SORT'),
            'SORT' => 800
        ]
    ],
    'PARAMETERS' => $arParameters
];