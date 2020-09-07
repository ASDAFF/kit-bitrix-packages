<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use intec\core\collections\Arrays;

/**
 * @var array $arCurrentValues
 */

if (!Loader::IncludeModule('iblock'))
    return;

if (!Loader::includeModule('intec.core'))
    return;

$arIBlock = null;
$arProperties = Arrays::from([]);
$arElements = Arrays::from([]);

if (!empty($arCurrentValues['IBLOCK_ID'])) {
    $arIBlock = CIBlock::GetList([], [
        'ID' => $arCurrentValues['IBLOCK_ID']
    ])->Fetch();
}

$arTemplateParameters['SETTINGS_USE'] = [
    'PARENT' => 'BASE',
    'NAME' => Loc::getMessage('C_MAIN_SECTIONS_TEMPLATE_2_SETTINGS_USE'),
    'TYPE' => 'CHECKBOX'
];

$arTemplateParameters['LAZYLOAD_USE'] = [
    'PARENT' => 'BASE',
    'NAME' => Loc::getMessage('C_MAIN_SECTIONS_TEMPLATE_2_LAZYLOAD_USE'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'N'
];

$arTemplateParameters = [
    'SHOW_MAP' => [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_NEWS_LIST_CONTACTS_SHOW_MAP'),
        'TYPE' => 'CHECKBOX',
        'REFRESH' => 'Y'
    ],
    'SHOW_FORM' => [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_NEWS_LIST_CONTACTS_SHOW_FORM'),
        'TYPE' => 'CHECKBOX',
        'REFRESH' => 'Y'
    ],
    'SHOW_LIST' => [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_NEWS_LIST_CONTACTS_SHOW_LIST'),
        'TYPE' => 'LIST',
        'VALUES' => [
            'NONE' => Loc::getMessage('C_NEWS_LIST_CONTACTS_SHOW_LIST_NONE'),
            'SHOPS' => Loc::getMessage('C_NEWS_LIST_CONTACTS_SHOW_LIST_SHOPS'),
            'OFFICES' => Loc::getMessage('C_NEWS_LIST_CONTACTS_SHOW_LIST_OFFICES')
        ]
    ],
    'MAP_VENDOR' => [
        'PARENT' => 'DATA_SOURCE',
        'NAME' => Loc::getMessage('C_NEWS_LIST_CONTACTS_MAP_VENDOR'),
        'TYPE' => 'LIST',
        'VALUES' => [
            'google' => Loc::getMessage('C_NEWS_LIST_CONTACTS_MAP_VENDOR_GOOGLE'),
            'yandex' => Loc::getMessage('C_NEWS_LIST_CONTACTS_MAP_VENDOR_YANDEX'),
        ],
        'ADDITIONAL_VALUES' => 'N',
        'DEFAULT' => 'google'
    ],
    'SETTINGS_USE' => [
        'PARENT' => 'BASE',
        'NAME' => Loc::getMessage('C_NEWS_LIST_CONTACTS_SETTINGS_USE'),
        'TYPE' => 'CHECKBOX'
    ]
];

if (!empty($arIBlock)) {
    $arProperties = Arrays::fromDBResult(CIBlockProperty::GetList([
        'SORT' => 'ASC'
    ], [
        'ACTIVE' => 'Y',
        'IBLOCK_ID' => $arIBlock['ID']
    ]))->indexBy('ID');

    $arPropertiesString = $arProperties->asArray(function ($iId, $arProperty) {
        if (
            empty($arProperty['CODE']) ||
            $arProperty['PROPERTY_TYPE'] !== 'S' ||
            $arProperty['USER_TYPE'] !== null
        ) return ['skip' => true];

        return [
            'key' => $arProperty['CODE'],
            'value' => '['.$arProperty['CODE'].'] '.$arProperty['NAME']
        ];
    });

    $arPropertiesMap = $arProperties->asArray(function ($iId, $arProperty) {
        if (
            empty($arProperty['CODE']) ||
            $arProperty['PROPERTY_TYPE'] !== 'S' ||
            $arProperty['USER_TYPE'] !== 'map_google' &&
            $arProperty['USER_TYPE'] !== 'map_yandex'
        ) return ['skip' => true];

        return [
            'key' => $arProperty['CODE'],
            'value' => '['.$arProperty['CODE'].'] '.$arProperty['NAME']
        ];
    });

    $arElements = Arrays::fromDBResult(CIBlockElement::GetList([
        'SORT' => 'ASC'
    ], [
        'ACTIVE' => 'Y',
        'IBLOCK_ID' => $arIBlock['ID']
    ]))->indexBy('ID');

    $arTemplateParameters['CONTACT_ID'] = [
        'PARENT' => 'DATA_SOURCE',
        'NAME' => Loc::getMessage('C_NEWS_LIST_CONTACTS_CONTACT_ID'),
        'TYPE' => 'LIST',
        'VALUES' => $arElements->asArray(function ($iId, $arElement) {
            return [
                'key' => $iId,
                'value' => '['.$iId.'] '.$arElement['NAME']
            ];
        }),
        'ADDITIONAL_VALUES' => 'Y'
    ];

    if ($arCurrentValues['SHOW_MAP'] == 'Y') {
        $arTemplateParameters['PROPERTY_MAP'] = [
            'PARENT' => 'DATA_SOURCE',
            'NAME' => Loc::getMessage('C_NEWS_LIST_CONTACTS_PROPERTY_MAP'),
            'TYPE' => 'LIST',
            'VALUES' => $arPropertiesMap,
            'ADDITIONAL_VALUES' => 'Y'
        ];

        $arTemplateParameters['API_KEY_MAP'] = [
            'PARENT' => 'DATA_SOURCE',
            'NAME' => Loc::getMessage('C_NEWS_LIST_CONTACTS_API_KEY_MAP'),
            'TYPE' => 'STRING'
        ];
    }

    $arTemplateParameters['PROPERTY_STORE_ID'] = [
        'PARENT' => 'DATA_SOURCE',
        'NAME' => Loc::getMessage('C_NEWS_LIST_CONTACTS_PROPERTY_STORE_ID'),
        'TYPE' => 'LIST',
        'VALUES' => $arPropertiesString,
        'ADDITIONAL_VALUES' => 'Y'
    ];

    $arTemplateParameters['PROPERTY_CITY'] = [
        'PARENT' => 'DATA_SOURCE',
        'NAME' => Loc::getMessage('C_NEWS_LIST_CONTACTS_PROPERTY_CITY'),
        'TYPE' => 'LIST',
        'VALUES' => $arPropertiesString,
        'ADDITIONAL_VALUES' => 'Y'
    ];

    $arTemplateParameters['PROPERTY_ADDRESS'] = [
        'PARENT' => 'DATA_SOURCE',
        'NAME' => Loc::getMessage('C_NEWS_LIST_CONTACTS_PROPERTY_ADDRESS'),
        'TYPE' => 'LIST',
        'VALUES' => $arPropertiesString,
        'ADDITIONAL_VALUES' => 'Y'
    ];

    $arTemplateParameters['PROPERTY_PHONE'] = [
        'PARENT' => 'DATA_SOURCE',
        'NAME' => Loc::getMessage('C_NEWS_LIST_CONTACTS_PROPERTY_PHONE'),
        'TYPE' => 'LIST',
        'VALUES' => $arPropertiesString,
        'ADDITIONAL_VALUES' => 'Y'
    ];

    $arTemplateParameters['PROPERTY_EMAIL'] = [
        'PARENT' => 'DATA_SOURCE',
        'NAME' => Loc::getMessage('C_NEWS_LIST_CONTACTS_PROPERTY_EMAIL'),
        'TYPE' => 'LIST',
        'VALUES' => $arPropertiesString,
        'ADDITIONAL_VALUES' => 'Y'
    ];

    $arTemplateParameters['PROPERTY_WORK_TIME'] = [
        'PARENT' => 'DATA_SOURCE',
        'NAME' => Loc::getMessage('C_NEWS_LIST_CONTACTS_PROPERTY_WORK_TIME'),
        'TYPE' => 'LIST',
        'VALUES' => $arPropertiesString,
        'ADDITIONAL_VALUES' => 'Y'
    ];

    $arTemplateParameters['PROPERTY_OPENING_HOURS'] = [
        'PARENT' => 'DATA_SOURCE',
        'NAME' => Loc::getMessage('C_NEWS_LIST_CONTACTS_PROPERTY_OPENING_HOURS'),
        'TYPE' => 'LIST',
        'VALUES' => $arPropertiesString,
        'ADDITIONAL_VALUES' => 'Y'
    ];
}

$arTemplateParameters['TITLE_SHOW'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_NEWS_LIST_CONTACTS_TITLE_SHOW'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'N',
    'REFRESH' => 'Y'
];

if ($arCurrentValues['TITLE_SHOW'] == 'Y') {
    $arTemplateParameters['TITLE_TEXT'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_NEWS_LIST_CONTACTS_TITLE_TEXT'),
        'TYPE' => 'TEXT',
        'VALUES' => ''
   ];
}

$arTemplateParameters['DESCRIPTION_SHOW'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_NEWS_LIST_CONTACTS_DESCRIPTION_SHOW'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'N',
    'REFRESH' => 'Y'
];

if ($arCurrentValues['DESCRIPTION_SHOW'] == 'Y') {
    $arTemplateParameters['DESCRIPTION_TEXT'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_NEWS_LIST_CONTACTS_DESCRIPTION_TEXT'),
        'TYPE' => 'TEXT',
        'VALUES' => ''
    ];
}

include(__DIR__.'/parameters/regionality.php');

if ($arCurrentValues['SHOW_FORM'] == 'Y') {
    $arForms = [];

    if (Loader::IncludeModule('form')) {
        include(__DIR__.'/parameters/base.php');
    } else if (Loader::IncludeModule('intec.startshop')) {
        include(__DIR__.'/parameters/lite.php');
    }

    $arTemplateParameters['WEB_FORM_ID'] = [
        'PARENT' => 'DATA_SOURCE',
        'NAME' => Loc::getMessage('C_NEWS_LIST_CONTACTS_WEB_FORM_ID'),
        'TYPE' => 'LIST',
        'VALUES' => $arForms,
        'ADDITIONAL_VALUES' => 'Y'
    ];

    $arTemplateParameters['WEB_FORM_CONSENT_URL'] = [
        'PARENT' => 'DATA_SOURCE',
        'NAME' => Loc::getMessage('C_NEWS_LIST_CONTACTS_WEB_FORM_CONSENT_URL'),
        'TYPE' => 'TEXT'
    ];
}