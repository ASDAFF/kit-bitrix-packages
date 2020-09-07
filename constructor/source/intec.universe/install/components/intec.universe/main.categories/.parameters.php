<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use intec\core\collections\Arrays;

/**
 * @var array $arCurrentValues
 */

if (!Loader::includeModule('intec.core'))
    return;

if (!Loader::includeModule('iblock'))
    return;

if (!empty($_REQUEST['site'])) {
    $sSite = $_REQUEST['site'];
} else if (!empty($_REQUEST['src_site'])) {
    $sSite = $_REQUEST['src_site'];
}

$arIBlockTypes = CIBlockParameters::GetIBlockTypes();

$arFilter = [
    'ACTIVE' => 'Y',
    'SITE_ID' => $sSite
];

if (!empty($arCurrentValues['IBLOCK_TYPE']))
    $arFilter['TYPE'] = $arCurrentValues['IBLOCK_TYPE'];

$arIBlocks = Arrays::fromDBResult(CIBlock::GetList(['SORT' => 'ASC'], $arFilter))->indexBy('ID');

$arIBlocks = $arIBlocks->asArray(function ($id, $arIBlock) {
    return [
        'key' => $arIBlock['ID'],
        'value' => '['.$arIBlock['ID'].'] '.$arIBlock['NAME']
    ];
});

$arSections = [];

if (!empty($arCurrentValues['IBLOCK_ID'])) {
    $arSections = Arrays::fromDBResult(CIBlockSection::GetList(['SORT' => 'ASC'], [
        'GLOBAL_ACTIVE' => 'Y',
        'IBLOCK_ID' => $arCurrentValues['IBLOCK_ID']
    ]))->indexBy('ID');

    if ($arCurrentValues['SECTIONS_MODE'] === 'code') {
        $arSections = $arSections->asArray(function ($id, $arSection) {
            if (!empty($arSection['CODE']))
                return [
                    'key' => $arSection['CODE'],
                    'value' => '[' . $arSection['CODE'] . '] ' . $arSection['NAME']
                ];

            return ['skip' => true];
        });
    } else {
        $arSections = $arSections->asArray(function ($id, $arSection) {
            return [
                'key' => $arSection['ID'],
                'value' => '[' . $arSection['ID'] . '] ' . $arSection['NAME']
            ];
        });
    }

    $arProperties = Arrays::fromDBResult(CIBlockProperty::GetList([], [
        'ACTIVE' => 'Y',
        'IBLOCK_ID' => $arCurrentValues['IBLOCK_ID']
    ]))->indexBy('ID');

    $hPropertyText = function ($sKey, $arProperty) {
        if ($arProperty['PROPERTY_TYPE'] == 'S' && $arProperty['LIST_TYPE'] == 'L' && $arProperty['MULTIPLE'] === 'N')
            return [
                'key' => $arProperty['CODE'],
                'value' => '['.$arProperty['CODE'].'] '.$arProperty['NAME']
            ];

        return ['skip' => true];
    };

    $arPropertyText = $arProperties->asArray($hPropertyText);
}

$arParameters = [];

$arParameters['IBLOCK_TYPE'] = [
    'PARENT' => 'BASE',
    'NAME' => Loc::getMessage('C_MAIN_CATEGORIES_IBLOCK_TYPE'),
    'TYPE' => 'LIST',
    'VALUES' => CIBlockParameters::GetIBlockTypes(),
    'ADDITIONAL_VALUES' => 'Y',
    'REFRESH' => 'Y'
];
$arParameters['IBLOCK_ID'] = [
    'PARENT' => 'BASE',
    'NAME' => Loc::getMessage('C_MAIN_CATEGORIES_IBLOCK_ID'),
    'TYPE' => 'LIST',
    'VALUES' => $arIBlocks,
    'ADDITIONAL_VALUES' => 'Y',
    'REFRESH' => 'Y'
];

if (!empty($arCurrentValues['IBLOCK_ID'])) {
    $arParameters['SECTIONS_MODE'] = [
        'PARENT' => 'BASE',
        'NAME' => Loc::getMessage('C_MAIN_CATEGORIES_SECTIONS_MODE'),
        'TYPE' => 'LIST',
        'VALUES' => [
            'id' => Loc::getMessage('C_MAIN_CATEGORIES_SECTIONS_MODE_ID'),
            'code' => Loc::getMessage('C_MAIN_CATEGORIES_SECTIONS_MODE_CODE')
        ],
        'DEFAULT' => 'id',
        'REFRESH' => 'Y'
    ];
    $arParameters['SECTIONS'] = [
        'PARENT' => 'BASE',
        'NAME' => Loc::getMessage('C_MAIN_CATEGORIES_SECTIONS'),
        'TYPE' => 'LIST',
        'VALUES' => $arSections,
        'ADDITIONAL_VALUES' => 'Y',
        'MULTIPLE' => 'Y'
    ];
    $arParameters['ELEMENTS_COUNT'] = [
        'PARENT' => 'BASE',
        'NAME' => Loc::getMessage('C_MAIN_CATEGORIES_ELEMENTS_COUNT'),
        'TYPE' => 'STRING'
    ];
    $arParameters['LINK_MODE'] = [
        'PARENT' => 'BASE',
        'NAME' => Loc::getMessage('C_MAIN_CATEGORIES_LINK_MODE'),
        'TYPE' => 'LIST',
        'VALUES' => [
            'property' => Loc::getMessage('C_MAIN_CATEGORIES_LINK_MODE_PROPERTY'),
            'component' => Loc::getMessage('C_MAIN_CATEGORIES_LINK_MODE_COMPONENT')
        ],
        'DEFAULT' => 'property',
        'REFRESH' => 'Y'
    ];

    if ($arCurrentValues['LINK_MODE'] !== 'component') {
        $arParameters['PROPERTY_LINK'] = [
            'PARENT' => 'DATA_SOURCE',
            'NAME' => Loc::getMessage('C_MAIN_CATEGORIES_PROPERTY_LINK'),
            'TYPE' => 'LIST',
            'VALUES' => $arPropertyText,
            'ADDITIONAL_VALUES' => 'Y',
            'REFRESH' => 'Y'
        ];
    }
}

$arParameters['HEADER_SHOW'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_MAIN_CATEGORIES_HEADER_SHOW'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'N',
    'REFRESH' => 'Y'
];

if ($arCurrentValues['HEADER_SHOW'] === 'Y') {
    $arParameters['HEADER_POSITION'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_MAIN_CATEGORIES_HEADER_POSITION'),
        'TYPE' => 'LIST',
        'VALUES' => [
            'left' => Loc::getMessage('C_MAIN_CATEGORIES_POSITION_LEFT'),
            'center' => Loc::getMessage('C_MAIN_CATEGORIES_POSITION_CENTER'),
            'right' => Loc::getMessage('C_MAIN_CATEGORIES_POSITION_RIGHT')
        ],
        'DEFAULT' => 'center'
    ];
    $arParameters['HEADER_TEXT'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_MAIN_CATEGORIES_HEADER_TEXT'),
        'TYPE' => 'STRING',
        'DEFAULT' => Loc::getMessage('C_MAIN_CATEGORIES_HEADER_TEXT_DEFAULT')
    ];
}

$arParameters['DESCRIPTION_SHOW'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_MAIN_CATEGORIES_DESCRIPTION_SHOW'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'N',
    'REFRESH' => 'Y'
];

if ($arCurrentValues['DESCRIPTION_SHOW'] === 'Y') {
    $arParameters['DESCRIPTION_POSITION'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_MAIN_CATEGORIES_DESCRIPTION_POSITION'),
        'TYPE' => 'LIST',
        'VALUES' => [
            'left' => Loc::getMessage('C_MAIN_CATEGORIES_POSITION_LEFT'),
            'center' => Loc::getMessage('C_MAIN_CATEGORIES_POSITION_CENTER'),
            'right' => Loc::getMessage('C_MAIN_CATEGORIES_POSITION_RIGHT')
        ],
        'DEFAULT' => 'center'
    ];
    $arParameters['DESCRIPTION_TEXT'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_MAIN_CATEGORIES_DESCRIPTION_TEXT'),
        'TYPE' => 'STRING'
    ];
}

if ($arCurrentValues['LINK_MODE'] === 'component') {
    $arParameters['LIST_PAGE_URL'] = [
        'PARENT' => 'URL_TEMPLATES',
        'NAME' => Loc::getMessage('C_MAIN_CATEGORIES_LIST_PAGE_URL'),
        'TYPE' => 'STRING'
    ];
    $arParameters['SECTION_URL'] = CIBlockParameters::GetPathTemplateParam(
        'SECTION',
        'SECTION_URL',
        Loc::getMessage('C_MAIN_CATEGORIES_SECTION_URL'),
        '',
        'URL_TEMPLATES'
    );
    $arParameters['DETAIL_URL'] = CIBlockParameters::GetPathTemplateParam(
        'DETAIL',
        'DETAIL_URL',
        Loc::getMessage('C_MAIN_CATEGORIES_DETAIL_URL'),
        '',
        'URL_TEMPLATES'
    );
}

$arParameters['CACHE_TIME'] = [];
$arParameters['SORT_BY'] = [
    'PARENT' => 'SORT',
    'NAME' => Loc::getMessage('C_MAIN_CATEGORIES_SORT_BY'),
    'TYPE' => 'LIST',
    'VALUES' => [
        'ID' => Loc::getMessage('C_MAIN_CATEGORIES_SORT_BY_ID'),
        'SORT' => Loc::getMessage('C_MAIN_CATEGORIES_SORT_BY_SORT'),
        'NAME' => Loc::getMessage('C_MAIN_CATEGORIES_SORT_BY_NAME')
    ],
    'ADDITIONAL_VALUES' => 'Y',
    'DEFAULT' => 'SORT'
];
$arParameters['SORT_ORDER'] = [
    'PARENT' => 'SORT',
    'NAME' => Loc::getMessage('C_MAIN_CATEGORIES_SORT_ORDER'),
    'TYPE' => 'LIST',
    'VALUES' => [
        'ASC' => Loc::getMessage('C_MAIN_CATEGORIES_SORT_ORDER_ASC'),
        'DESC' => Loc::getMessage('C_MAIN_CATEGORIES_SORT_ORDER_DESC')
    ],
    'DEFAULT' => 'ASC'
];

$arComponentParameters = [
    'GROUPS' => [
        'SORT' => [
            'NAME' => Loc::getMessage('C_MAIN_CATEGORIES_GROUP_SORT')
        ]
    ],
    'PARAMETERS' => $arParameters
];