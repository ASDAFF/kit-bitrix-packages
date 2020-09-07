<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

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

$sSite = $_REQUEST['site'];

if (empty($sSite) && !empty($_REQUEST['src_site'])) {
    $sSite = $_REQUEST['src_site'];
}

$arIBlocksList = Arrays::fromDBResult(CIBlock::GetList(['SORT' => 'ASC'], [
    'ACTIVE' => 'Y',
    'SITE_ID' => $sSite
]))->indexBy('ID');

if (!empty($arCurrentValues['IBLOCK_TYPE'])) {
    $arIBlocksList = $arIBlocksList->asArray(function ($id, $value) use ($arCurrentValues) {
        if ($value['IBLOCK_TYPE_ID'] === $arCurrentValues['IBLOCK_TYPE'])
            return [
                'key' => $value['ID'],
                'value' => '['.$value['ID'].'] '.$value['NAME']
            ];

        return ['skip' => true];
    });
} else {
    $arIBlocksList = $arIBlocksList->asArray(function ($id, $value) {
        return [
            'key' => $value['ID'],
            'value' => '['.$value['ID'].'] '.$value['NAME']
        ];
    });
}

if (!empty($arCurrentValues['IBLOCK_ID'])) {
    $arSections = Arrays::fromDBResult(CIBlockSection::GetList(['SORT' => 'ASC'], [
        'GLOBAL_ACTIVE' => 'Y',
        'ACTIVE' => 'Y',
        'IBLOCK_ID' => $arCurrentValues['IBLOCK_ID']
    ]))->indexBy('ID');

    if ($arCurrentValues['SECTIONS_MODE'] === 'code') {
        $arSections = $arSections->asArray(function ($id, $value) {
            if (!empty($value['CODE']))
                return [
                    'key' => $value['CODE'],
                    'value' => '['.$value['CODE'].'] '.$value['NAME']
                ];

            return ['skip' => true];
        });
    } else {
        $arSections = $arSections->asArray(function ($id, $value) {
            return [
                'key' => $value['ID'],
                'value' => '['.$value['ID'].'] '.$value['NAME']
            ];
        });
    }
}

$arParameters = [];

$arParameters['IBLOCK_TYPE'] = [
    'PARENT' => 'BASE',
    'NAME' => Loc::getMessage('C_MAIN_BRANDS_IBLOCK_TYPE'),
    'TYPE' => 'LIST',
    'VALUES' => CIBlockParameters::GetIBlockTypes(),
    'ADDITIONAL_VALUES' => 'Y',
    'REFRESH' => 'Y'
];
$arParameters['IBLOCK_ID'] = [
    'PARENT' => 'BASE',
    'NAME' => Loc::getMessage('C_MAIN_BRANDS_IBLOCK_ID'),
    'TYPE' => 'LIST',
    'VALUES' => $arIBlocksList,
    'ADDITIONAL_VALUES' => 'Y',
    'REFRESH' => 'Y'
];

if (!empty($arCurrentValues['IBLOCK_ID'])) {
    $arParameters['SECTIONS_MODE'] = [
        'PARENT' => 'BASE',
        'NAME' => Loc::getMessage('C_MAIN_BRANDS_SECTIONS_MODE'),
        'TYPE' => 'LIST',
        'VALUES' => [
            'id' => Loc::getMessage('C_MAIN_BRANDS_SECTIONS_MODE_ID'),
            'code' => Loc::getMessage('C_MAIN_BRANDS_SECTIONS_MODE_CODE')
        ],
        'DEFAULT' => 'id',
        'REFRESH' => 'Y'
    ];
    $arParameters['SECTIONS'] = [
        'PARENT' => 'BASE',
        'NAME' => Loc::getMessage('C_MAIN_BRANDS_SECTIONS'),
        'TYPE' => 'LIST',
        'VALUES' => $arSections,
        'MULTIPLE' => 'Y',
        'SIZE' => 6,
        'ADDITIONAL_VALUES' => 'Y'
    ];
}

$arParameters['ELEMENTS_COUNT'] = [
    'PARENT' => 'BASE',
    'NAME' => Loc::getMessage('C_MAIN_BRANDS_ELEMENTS_COUNT'),
    'TYPE' => 'STRING'
];
$arParameters['HEADER_SHOW'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_MAIN_BRANDS_HEADER_SHOW'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'N',
    'REFRESH' => 'Y'
];

if ($arCurrentValues['HEADER_SHOW'] === 'Y') {
    $arParameters['HEADER_POSITION'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_MAIN_BRANDS_HEADER_POSITION'),
        'TYPE' => 'LIST',
        'VALUES' => [
            'left' => Loc::getMessage('C_MAIN_BRANDS_POSITION_LEFT'),
            'center' => Loc::getMessage('C_MAIN_BRANDS_POSITION_CENTER'),
            'right' => Loc::getMessage('C_MAIN_BRANDS_POSITION_RIGHT')
        ],
        'DEFAULT' => 'center'
    ];
    $arParameters['HEADER_TEXT'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_MAIN_BRANDS_HEADER_TEXT'),
        'TYPE' => 'STRING',
        'DEFAULT' => Loc::getMessage('C_MAIN_BRANDS_HEADER_TEXT_DEFAULT')
    ];
}

$arParameters['DESCRIPTION_SHOW'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_MAIN_BRANDS_DESCRIPTION_SHOW'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'N',
    'REFRESH' => 'Y'
];

if ($arCurrentValues['DESCRIPTION_SHOW'] === 'Y') {
    $arParameters['DESCRIPTION_POSITION'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_MAIN_BRANDS_DESCRIPTION_POSITION'),
        'TYPE' => 'LIST',
        'VALUES' => [
            'left' => Loc::getMessage('C_MAIN_BRANDS_POSITION_LEFT'),
            'center' => Loc::getMessage('C_MAIN_BRANDS_POSITION_CENTER'),
            'right' => Loc::getMessage('C_MAIN_BRANDS_POSITION_RIGHT')
        ],
        'DEFAULT' => 'center'
    ];
    $arParameters['DESCRIPTION_TEXT'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_MAIN_BRANDS_DESCRIPTION_TEXT'),
        'TYPE' => 'STRING'
    ];
}

$arParameters['LIST_PAGE_URL'] = [
    'PARENT' => 'URL_TEMPLATES',
    'NAME' => Loc::getMessage('C_MAIN_BRANDS_LIST_PAGE_URL'),
    'TYPE' => 'STRING'
];
$arParameters['SECTION_URL'] = CIBlockParameters::GetPathTemplateParam(
    'SECTION',
    'SECTION_URL',
    Loc::getMessage('C_MAIN_BRANDS_SECTION_URL'),
    '',
    'URL_TEMPLATES'
);
$arParameters['DETAIL_URL'] = CIBlockParameters::GetPathTemplateParam(
    'DETAIL',
    'DETAIL_URL',
    Loc::getMessage('C_MAIN_BRANDS_DETAIL_URL'),
    '',
    'URL_TEMPLATES'
);
$arParameters['SORT_BY'] = [
    'PARENT' => 'SORT',
    'NAME' => Loc::getMessage('C_MAIN_BRANDS_SORT_BY'),
    'TYPE' => 'LIST',
    'VALUES' => CIBlockParameters::GetElementSortFields(),
    'DEFAULT' => 'SORT',
    'ADDITIONAL_VALUES' => 'Y'
];
$arParameters['ORDER_BY'] = [
    'PARENT' => 'SORT',
    'NAME' => Loc::getMessage('C_MAIN_BRANDS_ORDER_BY'),
    'TYPE' => 'LIST',
    'VALUES' => [
        'ASC' => Loc::getMessage('C_MAIN_BRANDS_ORDER_BY_ASC'),
        'DESC' => Loc::getMessage('C_MAIN_BRANDS_ORDER_BY_DESC')
    ],
    'DEFAULT' => 'ASC'
];
$arParameters['CACHE_TIME'] = [];

$arComponentParameters = [
    'GROUPS' => [
        'SORT' => [
            'NAME' => Loc::getMessage('C_MAIN_BRANDS_GROUPS_SORT'),
            'SORT' => 800
        ]
    ],
    'PARAMETERS' => $arParameters
];