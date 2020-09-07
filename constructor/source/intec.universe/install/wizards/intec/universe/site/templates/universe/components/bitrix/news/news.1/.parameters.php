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

Loc::loadMessages(__FILE__);

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
    $hPropertyLinkMultiple = function ($id, $value) {
        if ($value['PROPERTY_TYPE'] === 'E' && $value['LIST_TYPE'] === 'L' && $value['MULTIPLE'] === 'Y')
            return [
                'key' => $value['CODE'],
                'value' => '['.$value['CODE'].'] '.$value['NAME']
            ];

        return ['skip' => true];
    };

    $arPropertyListMultiple = $arProperties->asArray($hPropertyListMultiple);
    $arPropertyLinkMultiple = $arProperties->asArray($hPropertyLinkMultiple);
}

$arTemplateParameters = [];

$arTemplateParameters['SETTINGS_USE'] = [
    'PARENT' => 'BASE',
    'NAME' => Loc::getMessage('C_NEWS_NEWS_1_SETTINGS_USE'),
    'TYPE' => 'CHECKBOX',
    'REFRESH' => 'Y'
];

if ($arCurrentValues['SETTINGS_USE'] === 'Y') {
    $arTemplateParameters['SETTINGS_PROFILE'] = [
        'PARENT' => 'BASE',
        'NAME' => Loc::getMessage('C_NEWS_NEWS_1_SETTINGS_PROFILE'),
        'TYPE' => 'LIST',
        'VALUES' => [
            'news' => Loc::getMessage('C_NEWS_NEWS_1_SETTINGS_PROFILE_NEWS'),
            'articles' => Loc::getMessage('C_NEWS_NEWS_1_SETTINGS_PROFILE_ARTICLES'),
            'blog' => Loc::getMessage('C_NEWS_NEWS_1_SETTINGS_PROFILE_BLOG')
        ]
    ];
}

if (Loader::includeModule('intec.regionality'))
    include(__DIR__.'/parameters/regionality.php');

$arTemplateParameters['FILTER'] = [
    'PARENT' => 'BASE',
    'NAME' => Loc::getMessage('C_NEWS_NEWS_1_FILTER_NAME'),
    'TYPE' => 'STRING',
    'DEFAULT' => 'arrFilter'
];
$arTemplateParameters['PROPERTY_TAGS'] = [
    'PARENT' => 'DATA_SOURCE',
    'NAME' => Loc::getMessage('C_NEWS_NEWS_1_PROPERTY_TAGS'),
    'TYPE' => 'LIST',
    'VALUES' => $arPropertyListMultiple,
    'ADDITIONAL_VALUES' => 'Y',
    'REFRESH' => 'Y'
];

if (!empty($arCurrentValues['PROPERTY_TAGS'])) {
    $arTemplateParameters['TAGS_USE'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_NEWS_NEWS_1_TAGS_USE'),
        'TYPE' => 'CHECKBOX',
        'DEFAULT' => 'N',
        'REFRESH' => 'Y'
    ];

    if ($arCurrentValues['TAGS_USE'] === 'Y') {
        $arTemplateParameters['TAGS_VARIABLE'] = [
            'PARENT' => 'VISUAL',
            'NAME' => Loc::getMessage('C_NEWS_NEWS_1_TAGS_VARIABLE'),
            'TYPE' => 'STRING',
            'DEFAULT' => 'tags'
        ];
        $arTemplateParameters['TAGS_HEADER_SHOW'] = [
            'PARENT' => 'VISUAL',
            'NAME' => Loc::getMessage('C_NEWS_NEWS_1_TAGS_HEADER_SHOW'),
            'TYPE' => 'CHECKBOX',
            'DEFAULT' => 'N',
            'REFRESH' => 'Y'
        ];

        if ($arCurrentValues['TAGS_HEADER_SHOW'] === 'Y') {
            $arTemplateParameters['TAGS_HEADER_TEXT'] = [
                'PARENT' => 'VISUAL',
                'NAME' => Loc::getMessage('C_NEWS_NEWS_1_TAGS_HEADER_TEXT'),
                'TYPE' => 'STRING',
                'DEFAULT' => Loc::getMessage('C_NEWS_NEWS_1_TAGS_HEADER_TEXT_DEFAULT')
            ];
        }

        include(__DIR__.'/parameters/tags.php');
    }
}

$arTemplateParameters['TOP_USE'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_NEWS_NEWS_1_TOP_USE'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'N',
    'REFRESH' => 'Y'
];

if ($arCurrentValues['TOP_USE'] === 'Y') {
    $arTemplateParameters['TOP_PAGES'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_NEWS_NEWS_1_TOP_PAGES'),
        'TYPE' => 'LIST',
        'VALUES' => [
            'list' => Loc::getMessage('C_NEWS_NEWS_1_TOP_PAGES_LIST'),
            'detail' => Loc::getMessage('C_NEWS_NEWS_1_TOP_PAGES_DETAIL'),
            'all' => Loc::getMessage('C_NEWS_NEWS_1_TOP_PAGES_ALL')
        ],
        'DEFAULT' => 'list'
    ];
    $arTemplateParameters['TOP_COUNT'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_NEWS_NEWS_1_TOP_COUNT'),
        'TYPE' => 'STRING',
        'DEFAULT' => '5'
    ];
    $arTemplateParameters['TOP_HEADER_SHOW'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_NEWS_NEWS_1_TOP_HEADER_SHOW'),
        'TYPE' => 'CHECKBOX',
        'DEFAULT' => 'N',
        'REFRESH' => 'Y'
    ];

    if ($arCurrentValues['TOP_HEADER_SHOW'] === 'Y') {
        $arTemplateParameters['TOP_HEADER_TEXT'] = [
            'PARENT' => 'VISUAL',
            'NAME' => Loc::getMessage('C_NEWS_NEWS_1_TOP_HEADER_TEXT'),
            'TYPE' => 'STRING',
            'DEFAULT' => Loc::getMessage('C_NEWS_NEWS_1_TOP_HEADER_TEXT_DEFAULT')
        ];
    }

    include(__DIR__.'/parameters/top.php');
}

if (Loader::includeModule('subscribe')) {
    $arTemplateParameters['SUBSCRIBE_USE'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_NEWS_NEWS_1_SUBSCRIBE_USE'),
        'TYPE' => 'CHECKBOX',
        'DEFAULT' => 'N',
        'REFRESH' => 'Y'
    ];

    if ($arCurrentValues['SUBSCRIBE_USE'] === 'Y') {
        $arTemplateParameters['SUBSCRIBE_PAGES'] = [
            'PARENT' => 'VISUAL',
            'NAME' => Loc::getMessage('C_NEWS_NEWS_1_SUBSCRIBE_PAGES'),
            'TYPE' => 'LIST',
            'VALUES' => [
                'list' => Loc::getMessage('C_NEWS_NEWS_1_SUBSCRIBE_PAGES_LIST'),
                'detail' => Loc::getMessage('C_NEWS_NEWS_1_SUBSCRIBE_PAGES_DETAIL'),
                'all' => Loc::getMessage('C_NEWS_NEWS_1_SUBSCRIBE_PAGES_ALL')
            ],
            'DEFAULT' => 'list'
        ];

        include(__DIR__ . '/parameters/subscribe.php');
    }
}

if (!empty($arCurrentValues['IBLOCK_ID'])) {
    $arTemplateParameters['PANEL_SHOW'] = [
        'PARENT' => 'LIST_SETTINGS',
        'NAME' => Loc::getMessage('C_NEWS_NEWS_1_PANEL_SHOW'),
        'TYPE' => 'CHECKBOX',
        'DEFAULT' => 'N',
        'REFRESH' => 'Y'
    ];

    if ($arCurrentValues['PANEL_SHOW'] === 'Y') {
        $arTemplateParameters['PANEL_VARIABLE'] = [
            'PARENT' => 'LIST_SETTINGS',
            'NAME' => Loc::getMessage('C_NEWS_NEWS_1_PANEL_VARIABLE'),
            'TYPE' => 'STRING',
            'DEFAULT' => 'year'
        ];
        $arTemplateParameters['PANEL_VIEW'] = [
            'PARENT' => 'LIST_SETTINGS',
            'NAME' => Loc::getMessage('C_NEWS_NEWS_1_PANEL_VIEW'),
            'TYPE' => 'LIST',
            'VALUES' => [
                1 => Loc::getMessage('C_NEWS_NEWS_1_PANEL_VIEW_1'),
                2 => Loc::getMessage('C_NEWS_NEWS_1_PANEL_VIEW_2')
            ],
            'DEFAULT' => 1
        ];
    }
}

include(__DIR__.'/parameters/list.php');
include(__DIR__.'/parameters/detail.php');