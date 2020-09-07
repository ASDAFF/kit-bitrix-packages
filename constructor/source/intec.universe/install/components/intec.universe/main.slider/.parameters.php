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

if (!empty($_REQUEST['site'])) {
    $sSite = $_REQUEST['site'];
} else if (!empty($_REQUEST['src_site'])) {
    $sSite = $_REQUEST['src_site'];
}

$arIBlockTypes = CIBlockParameters::GetIBlockTypes();
$arIBlockList = Arrays::fromDBResult(CIBlock::GetList(['SORT' => 'ASC'], [
    'ACTIVE' => 'Y',
    'SITE_ID' => $sSite
]))->indexBy('ID');

if (!empty($arCurrentValues['IBLOCK_TYPE']))
    $arIBlocks = $arIBlockList->asArray(function ($sKey, $arProperty) use (&$arCurrentValues) {
        if ($arProperty['IBLOCK_TYPE_ID'] === $arCurrentValues['IBLOCK_TYPE'])
            return [
                'key' => $arProperty['ID'],
                'value' => '['.$arProperty['ID'].'] '.$arProperty['NAME']
            ];

        return ['skip' => true];
    });
else
    $arIBlocks = $arIBlockList->asArray(function ($sKey, $arProperty) {
        return [
            'key' => $arProperty['ID'],
            'value' => '['.$arProperty['ID'].'] '.$arProperty['NAME']
        ];
    });

if (!empty($arCurrentValues['IBLOCK_ID'])) {
    $arSections = Arrays::fromDBResult(CIBlockSection::GetList(['SORT' => 'ASC'], [
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

    $arProperties = Arrays::fromDBResult(CIBlockProperty::GetList(['SORT' => 'ASC'], [
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
    'NAME' => Loc::getMessage('C_MAIN_SLIDER_IBLOCK_TYPE'),
    'TYPE' => 'LIST',
    'VALUES' => $arIBlockTypes,
    'ADDITIONAL_VALUES' => 'Y',
    'REFRESH' => 'Y'
];
$arParameters['IBLOCK_ID'] = [
    'PARENT' => 'BASE',
    'NAME' => Loc::getMessage('C_MAIN_SLIDER_IBLOCK_ID'),
    'TYPE' => 'LIST',
    'VALUES' => $arIBlocks,
    'ADDITIONAL_VALUES' => 'Y',
    'REFRESH' => 'Y'
];
$arParameters['ELEMENTS_COUNT'] = [
    'PARENT' => 'BASE',
    'NAME' => Loc::getMessage('C_MAIN_SLIDER_ELEMENTS_COUNT'),
    'TYPE' => 'STRING'
];

if (!empty($arCurrentValues['IBLOCK_ID'])) {
    $arParameters['SECTIONS_MODE'] = [
        'PARENT' => 'BASE',
        'NAME' => Loc::getMessage('C_MAIN_SLIDER_SECTIONS_MODE'),
        'TYPE' => 'LIST',
        'VALUES' => [
            'id' => Loc::getMessage('C_MAIN_SLIDER_SECTIONS_MODE_ID'),
            'code' => Loc::getMessage('C_MAIN_SLIDER_SECTIONS_MODE_CODE')
        ],
        'DEFAULT' => 'id',
        'REFRESH' => 'Y'
    ];
    $arParameters['SECTIONS'] = [
        'PARENT' => 'BASE',
        'NAME' => Loc::getMessage('C_MAIN_SLIDER_SECTIONS'),
        'TYPE' => 'LIST',
        'VALUES' => $arSections,
        'ADDITIONAL_VALUES' => 'Y',
        'MULTIPLE' => 'Y'
    ];
    $arParameters['PROPERTY_HEADER'] = [
        'PARENT' => 'DATA_SOURCE',
        'NAME' => Loc::getMessage('C_MAIN_SLIDER_PROPERTY_HEADER'),
        'TYPE' => 'LIST',
        'VALUES' => $arPropertyText,
        'ADDITIONAL_VALUES' => 'Y',
        'REFRESH' => 'Y'
    ];
    $arParameters['PROPERTY_DESCRIPTION'] = [
        'PARENT' => 'DATA_SOURCE',
        'NAME' => Loc::getMessage('C_MAIN_SLIDER_PROPERTY_DESCRIPTION'),
        'TYPE' => 'LIST',
        'VALUES' => $arPropertyText,
        'ADDITIONAL_VALUES' => 'Y',
        'REFRESH' => 'Y'
    ];
}

$arParameters['SORT_BY'] = [
    'PARENT' => 'SORT',
    'NAME' => Loc::getMessage('C_MAIN_SLIDER_SORT_BY'),
    'TYPE' => 'LIST',
    'VALUES' => [
        'ID' => Loc::getMessage('C_MAIN_SLIDER_SORT_BY_ID'),
        'SORT' => Loc::getMessage('C_MAIN_SLIDER_SORT_BY_SORT'),
        'NAME' => Loc::getMessage('C_MAIN_SLIDER_SORT_BY_NAME')
    ],
    'ADDITIONAL_VALUES' => 'Y',
    'DEFAULT' => 'SORT'
];
$arParameters['ORDER_BY'] = [
    'PARENT' => 'SORT',
    'NAME' => Loc::getMessage('C_MAIN_SLIDER_ORDER_BY'),
    'TYPE' => 'LIST',
    'VALUES' => [
        'ASC' => Loc::getMessage('C_MAIN_SLIDER_ORDER_BY_ASC'),
        'DESC' => Loc::getMessage('C_MAIN_SLIDER_ORDER_BY_DESC')
    ],
    'DEFAULT' => 'SORT'
];

$arParameters['CACHE_TIME'] = [];

$arComponentParameters = [
    'GROUPS' => [
        'SORT' => [
            'NAME' => Loc::getMessage('C_MAIN_SLIDER_GROUP_SORT'),
            'SORT' => 800
        ]
    ],
    'PARAMETERS' => $arParameters
];