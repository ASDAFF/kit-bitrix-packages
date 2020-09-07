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

if (!empty($arCurrentValues['IBLOCK_ID'])) {
    $arProperties = Arrays::fromDBResult(CIBlockProperty::GetList(['SORT' => 'ASC'], [
        'IBLOCK_ID' => $arCurrentValues['IBLOCK_ID'],
        'ACTIVE' => 'Y'
    ]));

    $hPropertyListMultiple = function ($id, $value) {
        if ($value['PROPERTY_TYPE'] === 'L' && $value['LIST_TYPE'] === 'L' && $value['MULTIPLE'] === 'Y')
            return [
                'key' => $value['CODE'],
                'value' => '['.$value['CODE'].'] '.$value['NAME']
            ];

        return ['skip' => true];
    };

    $arPropertyListMultiple = $arProperties->asArray($hPropertyListMultiple);
}

$arTemplateParameters = [];

$arTemplateParameters['LAZYLOAD_USE'] = [
    'PARENT' => 'BASE',
    'NAME' => Loc::getMessage('C_NEWS_LIST_TILE_1_LAZYLOAD_USE'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'N'
];

if (!empty($arCurrentValues['IBLOCK_ID'])) {
    $arTemplateParameters['PROPERTY_TAGS'] = [
        'PARENT' => 'DATA_SOURCE',
        'NAME' => Loc::getMessage('C_NEWS_LIST_TILE_1_PROPERTY_TAGS'),
        'TYPE' => 'LIST',
        'VALUES' => $arPropertyListMultiple,
        'ADDITIONAL_VALUES' => 'Y',
        'REFRESH' => 'Y'
    ];
}

$arTemplateParameters['COLUMNS'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_NEWS_LIST_TILE_1_COLUMNS'),
    'TYPE' => 'LIST',
    'VALUES' => [
        3 => '3',
        4 => '4'
    ],
    'DEFAULT' => 4
];
$arTemplateParameters['VIEW'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_NEWS_LIST_TILE_1_VIEW'),
    'TYPE' => 'LIST',
    'VALUES' => [
        'default' => Loc::getMessage('C_NEWS_LIST_TILE_1_VIEW_DEFAULT'),
        'big' => Loc::getMessage('C_NEWS_LIST_TILE_1_VIEW_BIG')
    ],
    'DEFAULT' => 'default'
];
$arTemplateParameters['LINK_BLANK'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_NEWS_LIST_TILE_1_LINK_BLANK'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'N'
];
$arTemplateParameters['DATE_SHOW'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_NEWS_LIST_TILE_1_DATE_SHOW'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'N',
    'REFRESH' => 'Y'
];

if ($arCurrentValues['DATE_SHOW'] === 'Y') {
    $arTemplateParameters['DATE_TYPE'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_NEWS_LIST_TILE_1_DATE_TYPE'),
        'TYPE' => 'LIST',
        'VALUES' => [
            'DATE_CREATE' => Loc::getMessage('C_NEWS_LIST_TILE_1_DATE_TYPE_CREATE'),
            'DATE_ACTIVE_FROM' => Loc::getMessage('C_NEWS_LIST_TILE_1_DATE_TYPE_ACTIVE_FROM'),
            'DATE_ACTIVE_TO' => Loc::getMessage('C_NEWS_LIST_TILE_1_DATE_TYPE_ACTIVE_TO'),
            'TIMESTAMP_X' => Loc::getMessage('C_NEWS_LIST_TILE_1_DATE_TYPE_TIMESTAMP_X')
        ],
        'DEFAULT' => 'DATE_ACTIVE_FROM'
    ];
    $arTemplateParameters['DATE_FORMAT'] = CIBlockParameters::GetDateFormat(
        Loc::getMessage('C_NEWS_LIST_TILE_1_DATE_FORMAT'),
        'VISUAL'
    );
}

$arTemplateParameters['PREVIEW_SHOW'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_NEWS_LIST_TILE_1_PREVIEW_SHOW'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'N',
    'REFRESH' => 'Y'
];

if ($arCurrentValues['PREVIEW_SHOW'] === 'Y') {
    $arTemplateParameters['PREVIEW_TRUNCATE_USE'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_NEWS_LIST_TILE_1_PREVIEW_TRUNCATE_USE'),
        'TYPE' => 'CHECKBOX',
        'DEFAULT' => 'N',
        'REFRESH' => 'Y'
    ];

    if ($arCurrentValues['PREVIEW_TRUNCATE_USE'] === 'Y') {
        $arTemplateParameters['PREVIEW_TRUNCATE_COUNT'] = [
            'PARENT' => 'VISUAL',
            'NAME' => Loc::getMessage('C_NEWS_LIST_TILE_1_PREVIEW_TRUNCATE_COUNT'),
            'TYPE' => 'STRING',
            'DEFAULT' => 30
        ];
    }
}

if (!empty($arCurrentValues['PROPERTY_TAGS'])) {
    $arTemplateParameters['TAGS_SHOW'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_NEWS_LIST_TILE_1_TAGS_SHOW'),
        'TYPE' => 'CHECKBOX',
        'DEFAULT' => 'N',
        'REFRESH' => 'Y'
    ];

    if ($arCurrentValues['TAGS_SHOW'] === 'Y') {
        $arTemplateParameters['TAGS_VARIABLE'] = [
            'PARENT' => 'VISUAL',
            'NAME' => Loc::getMessage('C_NEWS_LIST_TILE_1_TAGS_VARIABLE'),
            'TYPE' => 'STRING',
            'DEFAULT' => 'tag'
        ];
        $arTemplateParameters['TAGS_MODE'] = [
            'PARENT' => 'VISUAL',
            'NAME' => Loc::getMessage('C_NEWS_LIST_TILE_1_TAGS_MODE'),
            'TYPE' => 'LIST',
            'VALUES' => [
                'default' => Loc::getMessage('C_NEWS_LIST_TILE_1_TAGS_MODE_DEFAULT'),
                'active' => Loc::getMessage('C_NEWS_LIST_TILE_1_TAGS_MODE_ACTIVE')
            ],
            'DEFAULT' => 'default'
        ];
    }
}