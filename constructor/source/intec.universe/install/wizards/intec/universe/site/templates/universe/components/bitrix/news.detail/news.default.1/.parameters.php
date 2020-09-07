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

    $hPropertyListMultiple = function ($key, $value) {
        if ($value['PROPERTY_TYPE'] === 'L' && $value['LIST_TYPE'] === 'L' && $value['MULTIPLE'] === 'Y')
            return [
                'key' => $value['CODE'],
                'value' => '['.$value['CODE'].'] '.$value['NAME']
            ];

        return ['skip' => true];
    };
    $hPropertyLinkMultiple = function ($key, $value) {
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
$arTemplateParameters['LAZYLOAD_USE'] = [
    'PARENT' => 'BASE',
    'NAME' => Loc::getMessage('C_NEWS_DETAIL_DEFAULT_1_LAZYLOAD_USE'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'N'
];

if (!empty($arCurrentValues['IBLOCK_ID'])) {
    $arTemplateParameters['PROPERTY_TAGS'] = [
        'PARENT' => 'DATA_SOURCE',
        'NAME' => Loc::getMessage('C_NEWS_DETAIL_DEFAULT_1_PROPERTY_TAGS'),
        'TYPE' => 'LIST',
        'VALUES' => $arPropertyListMultiple,
        'ADDITIONAL_VALUES' => 'Y',
        'REFRESH' => 'Y'
    ];
    $arTemplateParameters['PROPERTY_ADDITIONAL_NEWS'] = [
        'PARENT' => 'DATA_SOURCE',
        'NAME' => Loc::getMessage('C_NEWS_DETAIL_DEFAULT_1_PROPERTY_ADDITIONAL_NEWS'),
        'TYPE' => 'LIST',
        'VALUES' => $arPropertyLinkMultiple,
        'ADDITIONAL_VALUES' => 'Y',
        'REFRESH' => 'Y'
    ];
}

$arTemplateParameters['DATE_SHOW'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_NEWS_DETAIL_DEFAULT_1_DATE_SHOW'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'N',
    'REFRESH' => 'Y'
];

if ($arCurrentValues['DATE_SHOW'] === 'Y') {
    $arTemplateParameters['DATE_TYPE'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_NEWS_DETAIL_DEFAULT_1_DATE_TYPE'),
        'TYPE' => 'LIST',
        'VALUES' => [
            'DATE_CREATE' => Loc::getMessage('C_NEWS_DETAIL_DEFAULT_1_DATE_TYPE_DATE_CREATE'),
            'DATE_ACTIVE_FROM' => Loc::getMessage('C_NEWS_DETAIL_DEFAULT_1_DATE_TYPE_DATE_ACTIVE_FROM'),
            'DATE_ACTIVE_TO' => Loc::getMessage('C_NEWS_DETAIL_DEFAULT_1_DATE_TYPE_DATE_ACTIVE_TO'),
            'TIMESTAMP_X' => Loc::getMessage('C_NEWS_DETAIL_DEFAULT_1_DATE_TYPE_TIMESTAMP_X')
        ],
        'DEFAULT' => 'DATE_ACTIVE_FROM'
    ];
    $arTemplateParameters['DATE_FORMAT'] = CIBlockParameters::GetDateFormat(
        Loc::getMessage('C_NEWS_DETAIL_DEFAULT_1_DATE_FORMAT'),
        'VISUAL'
    );
}

if (!empty($arCurrentValues['PROPERTY_TAGS'])) {
    $arTemplateParameters['TAGS_SHOW'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_NEWS_DETAIL_DEFAULT_1_TAGS_SHOW'),
        'TYPE' => 'CHECKBOX',
        'DEFAULT' => 'N',
        'REFRESH' => 'Y'
    ];

    if ($arCurrentValues['TAGS_SHOW'] === 'Y') {
        $arTemplateParameters['TAGS_POSITION'] = [
            'PARENT' => 'VISUAL',
            'NAME' => Loc::getMessage('C_NEWS_DETAIL_DEFAULT_1_TAG_POSITION'),
            'TYPE' => 'LIST',
            'VALUES' => [
                'top' => Loc::getMessage('C_NEWS_DETAIL_DEFAULT_1_TAG_POSITION_TOP'),
                'bottom' => Loc::getMessage('C_NEWS_DETAIL_DEFAULT_1_TAG_POSITION_BOTTOM'),
                'both' => Loc::getMessage('C_NEWS_DETAIL_DEFAULT_1_TAG_POSITION_BOTH')
            ],
            'DEFAULT' => 'top',
        ];
    }
}

$arTemplateParameters['PRINT_SHOW'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_NEWS_DETAIL_DEFAULT_1_PRINT_SHOW'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'N'
];

$arTemplateParameters['PREVIEW_SHOW'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_NEWS_DETAIL_DEFAULT_1_PREVIEW_SHOW'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'N'
];
$arTemplateParameters['IMAGE_SHOW'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_NEWS_DETAIL_DEFAULT_1_IMAGE_SHOW'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'N'
];

if (!empty($arCurrentValues['PROPERTY_ADDITIONAL_NEWS'])) {
    $arTemplateParameters['ADDITIONAL_NEWS_SHOW'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_NEWS_DETAIL_DEFAULT_1_ADDITIONAL_NEWS_SHOW'),
        'TYPE' => 'CHECKBOX',
        'DEFAULT' => 'N',
        'REFRESH' => 'Y'
    ];

    if ($arCurrentValues['ADDITIONAL_NEWS_SHOW'] === 'Y') {
        $arTemplateParameters['ADDITIONAL_NEWS_HEADER_SHOW'] = [
            'PARENT' => 'VISUAL',
            'NAME' => Loc::getMessage('C_NEWS_DETAIL_DEFAULT_1_ADDITIONAL_NEWS_HEADER_SHOW'),
            'TYPE' => 'CHECKBOX',
            'DEFAULT' => 'N',
            'REFRESH' => 'Y'
        ];

        if ($arCurrentValues['ADDITIONAL_NEWS_HEADER_SHOW'] === 'Y') {
            $arTemplateParameters['ADDITIONAL_NEWS_HEADER_TEXT'] = [
                'PARENT' => 'VISUAL',
                'NAME' => Loc::getMessage('C_NEWS_DETAIL_DEFAULT_1_ADDITIONAL_NEWS_HEADER_TEXT'),
                'TYPE' => 'STRING',
                'DEFAULT' => Loc::getMessage('C_NEWS_DETAIL_DEFAULT_1_ADDITIONAL_NEWS_HEADER_TEXT_DEFAULT')
            ];
        }

        include(__DIR__.'/parameters/news.php');
    }
}

$arTemplateParameters['BUTTON_BACK_SHOW'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_NEWS_DETAIL_DEFAULT_1_BUTTON_BACK_SHOW'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'N',
    'REFRESH' => 'Y'
];
$arTemplateParameters['BUTTON_SOCIAL_SHOW'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_NEWS_DETAIL_DEFAULT_1_BUTTON_SOCIAL_SHOW'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'N',
    'REFRESH' => 'Y'
];

if ($arCurrentValues['BUTTON_SOCIAL_SHOW'] === 'Y') {
    include(__DIR__.'/parameters/social.php');
}


$arTemplateParameters['MICRODATA_TYPE'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_NEWS_DETAIL_DEFAULT_1_MICRODATA_TYPE'),
    'TYPE' => 'LIST',
    'VALUES' => [
        'Article' => Loc::getMessage('C_NEWS_DETAIL_DEFAULT_1_MICRODATA_TYPE_ARTICLE'),
        'NewsArticle' => Loc::getMessage('C_NEWS_DETAIL_DEFAULT_1_MICRODATA_TYPE_NEWS_ARTICLE'),
        'BlogPosting' => Loc::getMessage('C_NEWS_DETAIL_DEFAULT_1_MICRODATA_TYPE_BLOG_POSTING')
    ],
    'DEFAULT' => 'Article',
];

$arTemplateParameters['MICRODATA_AUTHOR'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_NEWS_DETAIL_DEFAULT_1_MICRODATA_AUTHOR'),
    'TYPE' => 'STRING',
    'DEFAULT' => ''
];

$arTemplateParameters['MICRODATA_PUBLISHER'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_NEWS_DETAIL_DEFAULT_1_MICRODATA_PUBLISHER'),
    'TYPE' => 'STRING',
    'DEFAULT' => ''
];