<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use intec\core\collections\Arrays;
use intec\core\helpers\ArrayHelper;
use intec\core\helpers\Type;

/**
 * @var array $arCurrentValues
 */

if (!Loader::includeModule('intec.core'))
    return;

if (!Loader::includeModule('iblock'))
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

    $arProperties = Arrays::fromDBResult(CIBlockProperty::GetList([], [
        'ACTIVE' => 'Y',
        'IBLOCK_ID' => $arCurrentValues['IBLOCK_ID']
    ]))->indexBy('ID');

    $arPropertyText = $arProperties->asArray(function ($id, $value) {
        if (!empty($value['CODE']) && $value['PROPERTY_TYPE'] === 'S' && $value['LIST_TYPE'] === 'L' && $value['MULTIPLE'] === 'N') {
            return [
                'key' => $value['CODE'],
                'value' => '['.$value['CODE'].'] '.$value['NAME']
            ];
        }

        return ['skip' => true];
    });
}

$arParameters = [];

$arParameters['IBLOCK_TYPE'] = [
    'PARENT' => 'BASE',
    'NAME' => Loc::getMessage('C_MAIN_VIDEOS_IBLOCK_TYPE'),
    'TYPE' => 'LIST',
    'VALUES' => CIBlockParameters::GetIBlockTypes(),
    'ADDITIONAL_VALUES' => 'Y',
    'REFRESH' => 'Y'
];
$arParameters['IBLOCK_ID'] = [
    'PARENT' => 'BASE',
    'NAME' => Loc::getMessage('C_MAIN_VIDEOS_IBLOCK_ID'),
    'TYPE' => 'LIST',
    'VALUES' => $arIBlocksList,
    'ADDITIONAL_VALUES' => 'Y',
    'REFRESH' => 'Y'
];

if (!empty($arCurrentValues['IBLOCK_ID'])) {
    $arParameters['SECTIONS_MODE'] = [
        'PARENT' => 'BASE',
        'NAME' => Loc::getMessage('C_MAIN_VIDEOS_SECTIONS_MODE'),
        'TYPE' => 'LIST',
        'VALUES' => [
            'id' => Loc::getMessage('C_MAIN_VIDEOS_SECTIONS_MODE_ID'),
            'code' => Loc::getMessage('C_MAIN_VIDEOS_SECTIONS_MODE_CODE')
        ],
        'DEFAULT' => 'id',
        'REFRESH' => 'Y'
    ];
    $arParameters['SECTIONS'] = [
        'PARENT' => 'BASE',
        'NAME' => Loc::getMessage('C_MAIN_VIDEOS_SECTIONS'),
        'TYPE' => 'LIST',
        'VALUES' => $arSections,
        'MULTIPLE' => 'Y',
        'SIZE' => 6,
        'ADDITIONAL_VALUES' => 'Y'
    ];
}

$arParameters['ELEMENTS_COUNT'] = [
    'PARENT' => 'BASE',
    'NAME' => Loc::getMessage('C_MAIN_VIDEOS_ELEMENTS_COUNT'),
    'TYPE' => 'STRING'
];

if (!empty($arCurrentValues['IBLOCK_ID'])) {
    $arParameters['PROPERTY_URL'] = [
        'PARENT' => 'DATA_SOURCE',
        'NAME' => Loc::getMessage('C_MAIN_VIDEOS_PROPERTY_URL'),
        'TYPE' => 'LIST',
        'VALUES' => $arPropertyText,
        'ADDITIONAL_VALUES' => 'Y'
    ];
}

$arParameters['HEADER_SHOW'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_MAIN_VIDEOS_HEADER_SHOW'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'N',
    'REFRESH' => 'Y'
];

if ($arCurrentValues['HEADER_SHOW'] == 'Y') {
    $arParameters['HEADER_POSITION'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_MAIN_VIDEOS_HEADER_POSITION'),
        'TYPE' => 'LIST',
        'VALUES' => [
            'left' => Loc::getMessage('C_MAIN_VIDEOS_POSITION_LEFT'),
            'center' => Loc::getMessage('C_MAIN_VIDEOS_POSITION_CENTER'),
            'right' => Loc::getMessage('C_MAIN_VIDEOS_POSITION_RIGHT')
        ],
        'DEFAULT' => 'center'
    ];
    $arParameters['HEADER'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_MAIN_VIDEOS_HEADER'),
        'TYPE' => 'STRING',
        'DEFAULT' => Loc::getMessage('C_MAIN_VIDEOS_HEADER_DEFAULT')
    ];
}

$arParameters['DESCRIPTION_SHOW'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_MAIN_VIDEOS_DESCRIPTION_SHOW'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'N',
    'REFRESH' => 'Y'
];

if ($arCurrentValues['DESCRIPTION_SHOW'] == 'Y') {
    $arParameters['DESCRIPTION_POSITION'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_MAIN_VIDEOS_DESCRIPTION_POSITION'),
        'TYPE' => 'LIST',
        'VALUES' => [
            'left' => Loc::getMessage('C_MAIN_VIDEOS_POSITION_LEFT'),
            'center' => Loc::getMessage('C_MAIN_VIDEOS_POSITION_CENTER'),
            'right' => Loc::getMessage('C_MAIN_VIDEOS_POSITION_RIGHT')
        ],
        'DEFAULT' => 'center'
    ];
    $arParameters['DESCRIPTION'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_MAIN_VIDEOS_DESCRIPTION'),
        'TYPE' => 'STRING'
    ];
}

$arParameters['PICTURE_SOURCES'] = [
    'PARENT' => 'BASE',
    'NAME' => Loc::getMessage('C_MAIN_VIDEOS_PICTURE_SOURCES'),
    'TYPE' => 'LIST',
    'MULTIPLE' => 'Y',
    'VALUES' => [
        'service' => Loc::getMessage('C_MAIN_VIDEOS_PICTURE_SOURCES_SERVICE'),
        'preview' => Loc::getMessage('C_MAIN_VIDEOS_PICTURE_SOURCES_PREVIEW'),
        'detail' => Loc::getMessage('C_MAIN_VIDEOS_PICTURE_SOURCES_DETAIL')
    ],
    'REFRESH' => 'Y'
];

$arPictureSources = ArrayHelper::getValue($arCurrentValues, 'PICTURE_SOURCES');

if (Type::isArray($arPictureSources)) {
    if (ArrayHelper::isIn('service', $arPictureSources)) {
        $arParameters['PICTURE_SERVICE_QUALITY'] = [
            'PARENT' => 'BASE',
            'NAME' => Loc::getMessage('C_MAIN_VIDEOS_PICTURE_SERVICE_QUALITY'),
            'TYPE' => 'LIST',
            'VALUES' => [
                'mqdefault' => Loc::getMessage('C_MAIN_VIDEOS_PICTURE_SERVICE_QUALITY_MQ'),
                'hqdefault' => Loc::getMessage('C_MAIN_VIDEOS_PICTURE_SERVICE_QUALITY_HQ'),
                'sddefault' => Loc::getMessage('C_MAIN_VIDEOS_PICTURE_SERVICE_QUALITY_SD'),
                'maxresdefault' => Loc::getMessage('C_MAIN_VIDEOS_PICTURE_SERVICE_QUALITY_MAX')
            ],
            'DEFAULT' => 'sddefault'
        ];
    }
}

$arParameters['SORT_BY'] = [
    'PARENT' => 'SORT',
    'NAME' => Loc::getMessage('C_MAIN_VIDEOS_SORT_BY'),
    'TYPE' => 'LIST',
    'VALUES' => CIBlockParameters::GetElementSortFields(),
    'DEFAULT' => 'SORT',
    'ADDITIONAL_VALUES' => 'Y'
];
$arParameters['ORDER_BY'] = [
    'PARENT' => 'SORT',
    'NAME' => Loc::getMessage('C_MAIN_VIDEOS_ORDER_BY'),
    'TYPE' => 'LIST',
    'VALUES' => [
        'ASC' => Loc::getMessage('C_MAIN_VIDEOS_ORDER_BY_ASC'),
        'DESC' => Loc::getMessage('C_MAIN_VIDEOS_ORDER_BY_DESC')
    ],
    'DEFAULT' => 'ASC'
];
$arParameters['CACHE_TIME'] = [];

$arComponentParameters = [
    'GROUPS' => [
        'SORT' => [
            'NAME' => Loc::getMessage('C_MAIN_VIDEOS_GROUP_SORT'),
            'SORT' => 800
        ]
    ],
    'PARAMETERS' => $arParameters
];