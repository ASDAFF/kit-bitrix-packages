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

$arTemplateParameters = [];

$arTemplateParameters['SETTINGS_USE'] = [
    'PARENT' => 'BASE',
    'NAME' => Loc::getMessage('C_NEWS_LIST_CONTACTS_2_SETTINGS_USE'),
    'TYPE' => 'CHECKBOX'
];

$arTemplateParameters['LAZYLOAD_USE'] = [
    'PARENT' => 'BASE',
    'NAME' => Loc::getMessage('C_NEWS_LIST_CONTACTS_2_LAZYLOAD_USE'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'N'
];

if (!empty($arCurrentValues['IBLOCK_ID'])) {
    $arElements = Arrays::fromDBResult(CIBlockElement::GetList([], [
        'ACTIVE' => 'Y',
        'IBLOCK_ID' => $arCurrentValues['IBLOCK_ID']
    ]))->indexBy('ID');

    $hElements = function ($sKey, $arProperty) {
        if (!empty($arProperty))
            return [
                'key' => $arProperty['ID'],
                'value' => '['.$arProperty['ID'].'] '.$arProperty['NAME']
            ];

        return ['skip' => true];
    };

    $arProperties = Arrays::fromDBResult(CIBlockProperty::GetList([], [
        'ACTIVE' => 'Y',
        'IBLOCK_ID' => $arCurrentValues['IBLOCK_ID']
    ]))->indexBy('ID');

    $hPropertyTextAll = function ($sKey, $arProperty) {
        if ($arProperty['PROPERTY_TYPE'] === 'S' && !$arProperty['USER_TYPE'])
            return [
                'key' => $arProperty['CODE'],
                'value' => '['.$arProperty['CODE'].'] '.$arProperty['NAME']
            ];

        return ['skip' => true];
    };
    $hPropertyText = function ($sKey, $arProperty) {
        if ($arProperty['PROPERTY_TYPE'] === 'S' && $arProperty['MULTIPLE'] === 'N' && !$arProperty['USER_TYPE'])
            return [
                'key' => $arProperty['CODE'],
                'value' => '['.$arProperty['CODE'].'] '.$arProperty['NAME']
            ];

        return ['skip' => true];
    };
    $hPropertyTextMulti = function ($sKey, $arProperty) {
        if ($arProperty['PROPERTY_TYPE'] === 'S' && $arProperty['MULTIPLE'] === 'Y' && !$arProperty['USER_TYPE'])
            return [
                'key' => $arProperty['CODE'],
                'value' => '['.$arProperty['CODE'].'] '.$arProperty['NAME']
            ];

        return ['skip' => true];
    };
    $hPropertyMap = function ($sKey, $arProperty) {
        if ($arProperty['PROPERTY_TYPE'] === 'S' && $arProperty['MULTIPLE'] === 'N' && $arProperty['USER_TYPE'])
            return [
                'key' => $arProperty['CODE'],
                'value' => '['.$arProperty['CODE'].'] '.$arProperty['NAME']
            ];

        return ['skip' => true];
    };

    $arPropertyTextAll = $arProperties->asArray($hPropertyTextAll);
    $arPropertyText = $arProperties->asArray($hPropertyText);
    $arPropertyTextMulti = $arProperties->asArray($hPropertyTextMulti);
    $arPropertyMap = $arProperties->asArray($hPropertyMap);

    $arTemplateParameters['MAIN'] = [
        'PARENT' => 'BASE',
        'NAME' => Loc::getMessage('C_NEWS_LIST_CONTACTS_2_MAIN'),
        'TYPE' => 'LIST',
        'VALUES' => $arElements->asArray($hElements)
    ];

    $arTemplateParameters['PROPERTY_ADDRESS'] = [
        'PARENT' => 'DATA_SOURCE',
        'NAME' => Loc::getMessage('C_NEWS_LIST_CONTACTS_2_PROPERTY_ADDRESS'),
        'TYPE' => 'LIST',
        'VALUES' => $arPropertyText,
        'ADDITIONAL_VALUES' => 'Y'
    ];    
	$arTemplateParameters['PROPERTY_CITY'] = [
        'PARENT' => 'DATA_SOURCE',
        'NAME' => Loc::getMessage('C_NEWS_LIST_CONTACTS_2_PROPERTY_CITY'),
        'TYPE' => 'LIST',
        'VALUES' => $arPropertyText,
        'ADDITIONAL_VALUES' => 'Y'
    ];    
	$arTemplateParameters['PROPERTY_INDEX'] = [
        'PARENT' => 'DATA_SOURCE',
        'NAME' => Loc::getMessage('C_NEWS_LIST_CONTACTS_2_PROPERTY_INDEX'),
        'TYPE' => 'LIST',
        'VALUES' => $arPropertyText,
        'ADDITIONAL_VALUES' => 'Y'
    ];
	$arTemplateParameters['PROPERTY_EMAIL'] = [
        'PARENT' => 'DATA_SOURCE',
        'NAME' => Loc::getMessage('C_NEWS_LIST_CONTACTS_2_PROPERTY_EMAIL'),
        'TYPE' => 'LIST',
        'VALUES' => $arPropertyText,
        'ADDITIONAL_VALUES' => 'Y'
    ];
    $arTemplateParameters['PROPERTY_SCHEDULE'] = [
        'PARENT' => 'DATA_SOURCE',
        'NAME' => Loc::getMessage('C_NEWS_LIST_CONTACTS_2_PROPERTY_SCHEDULE'),
        'TYPE' => 'LIST',
        'VALUES' => $arPropertyTextMulti,
        'ADDITIONAL_VALUES' => 'Y'
    ];
    $arTemplateParameters['PROPERTY_PHONE'] = [
        'PARENT' => 'DATA_SOURCE',
        'NAME' => Loc::getMessage('C_NEWS_LIST_CONTACTS_2_PROPERTY_PHONE'),
        'TYPE' => 'LIST',
        'VALUES' => $arPropertyTextAll,
        'ADDITIONAL_VALUES' => 'Y'
    ];
    $arTemplateParameters['PROPERTY_MAP_LOCATION'] = [
        'PARENT' => 'DATA_SOURCE',
        'NAME' => Loc::getMessage('C_NEWS_LIST_CONTACTS_2_PROPERTY_MAP_LOCATION'),
        'TYPE' => 'LIST',
        'VALUES' => $arPropertyMap,
        'ADDITIONAL_VALUES' => 'Y'
    ];
    $arTemplateParameters['PROPERTY_LIST_NAME'] = [
        'PARENT' => 'DATA_SOURCE',
        'NAME' => Loc::getMessage('C_NEWS_LIST_CONTACTS_2_PROPERTY_LIST_NAME'),
        'TYPE' => 'LIST',
        'VALUES' => $arPropertyText,
        'ADDITIONAL_VALUES' => 'Y'
    ];
    $arTemplateParameters['PROPERTY_MAP_NAME'] = [
        'PARENT' => 'DATA_SOURCE',
        'NAME' => Loc::getMessage('C_NEWS_LIST_CONTACTS_2_PROPERTY_MAP_NAME'),
        'TYPE' => 'LIST',
        'VALUES' => $arPropertyText,
        'ADDITIONAL_VALUES' => 'Y'
    ];
}

$arTemplateParameters['MARKUP_COMPANY'] = [
    'PARENT' => 'DATA_SOURCE',
    'NAME' => Loc::getMessage('C_NEWS_LIST_CONTACTS_2_MARKUP_COMPANY'),
    'TYPE' => 'STRING',
    'VALUES' => '',
    'ADDITIONAL_VALUES' => 'Y'
];

$arTemplateParameters['MAP_VENDOR'] = [
    'PARENT' => 'DATA_SOURCE',
    'NAME' => Loc::getMessage('C_NEWS_LIST_CONTACTS_2_MAP_VENDOR'),
    'TYPE' => 'LIST',
    'VALUES' => array(
        'google' => Loc::getMessage('C_NEWS_LIST_CONTACTS_2_MAP_VENDOR_GOOGLE'),
        'yandex' => Loc::getMessage('C_NEWS_LIST_CONTACTS_2_MAP_VENDOR_YANDEX'),
    ),
    'ADDITIONAL_VALUES' => 'N',
    'DEFAULT' => 'google'
];

$arTemplateParameters['SETTINGS_USE'] = [
    'PARENT' => 'BASE',
    'NAME' => Loc::getMessage('C_NEWS_LIST_CONTACTS_2_SETTINGS_USE'),
    'TYPE' => 'CHECKBOX'
];

$arTemplateParameters['PROPERTY_STORE_ID'] = array(
    'PARENT' => 'DATA_SOURCE',
    'NAME' => Loc::getMessage('C_NEWS_LIST_CONTACTS_2_PROPERTY_STORE_ID'),
    'TYPE' => 'LIST',
    'VALUES' => $arPropertyText,
    'ADDITIONAL_VALUES' => 'Y'
);

if (!empty($arCurrentValues['PROPERTY_ADDRESS'])) {
    $arTemplateParameters['ADDRESS_SHOW'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_NEWS_LIST_CONTACTS_2_ADDRESS_SHOW'),
        'TYPE' => 'CHECKBOX',
        'DEFAULT' => 'N'
    ];
}

if (!empty($arCurrentValues['PROPERTY_PHONE'])) {
    $arTemplateParameters['PHONE_SHOW'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_NEWS_LIST_CONTACTS_2_PHONE_SHOW'),
        'TYPE' => 'CHECKBOX',
        'DEFAULT' => 'N'
    ];
}

if (!empty($arCurrentValues['PROPERTY_EMAIL'])) {
    $arTemplateParameters['EMAIL_SHOW'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_NEWS_LIST_CONTACTS_2_EMAIL_SHOW'),
        'TYPE' => 'CHECKBOX',
        'DEFAULT' => 'N'
    ];
}

if (!empty($arCurrentValues['PROPERTY_SCHEDULE'])) {
    $arTemplateParameters['SCHEDULE_SHOW'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_NEWS_LIST_CONTACTS_2_SCHEDULE_SHOW'),
        'TYPE' => 'CHECKBOX',
        'DEFAULT' => 'N'
    ];
}

$arTemplateParameters['SHOW_FORM'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_NEWS_LIST_CONTACTS_2_SHOW_FORM'),
    'TYPE' => 'CHECKBOX',
    'REFRESH' => 'Y'
];

if ($arCurrentValues['SHOW_FORM'] == 'Y') {
    $arForms = array();

    if (Loader::IncludeModule('form')) {
        include('parameters/base.php');
    } else if (Loader::IncludeModule('intec.startshop')) {
        include('parameters/lite.php');
    }

    $arTemplates = [];

    foreach ($rsTemplates as $arTemplate)
        $arTemplates[$arTemplate['NAME']] = $arTemplate['NAME'].(!empty($arTemplate['TEMPLATE']) ? ' ('.$arTemplate['TEMPLATE'].')' : null);

    $arTemplateParameters['WEB_FORM_TEMPLATE'] = [
        'PARENT' => 'DATA_SOURCE',
        'NAME' => Loc::getMessage('C_NEWS_LIST_CONTACTS_2_WEB_FORM_TEMPLATE'),
        'TYPE' => 'LIST',
        'VALUES' => $arTemplates
    ];

    $arTemplateParameters['WEB_FORM_ID'] = [
        'PARENT' => 'DATA_SOURCE',
        'NAME' => Loc::getMessage('C_NEWS_LIST_CONTACTS_2_WEB_FORM_ID'),
        'TYPE' => 'LIST',
        'VALUES' => $arForms,
        'ADDITIONAL_VALUES' => 'Y'
    ];

    $arTemplateParameters['WEB_FORM_NAME'] = [
        'PARENT' => 'DATA_SOURCE',
        'NAME' => Loc::getMessage('C_NEWS_LIST_CONTACTS_2_WEB_FORM_NAME'),
        'TYPE' => 'TEXT'
    ];

    $arTemplateParameters['WEB_FORM_CONSENT_URL'] = [
        'PARENT' => 'DATA_SOURCE',
        'NAME' => Loc::getMessage('C_NEWS_LIST_CONTACTS_2_WEB_FORM_CONSENT_URL'),
        'TYPE' => 'TEXT'
    ];

    $arTemplateParameters['FEEDBACK_BUTTON_TEXT'] = [
        'PARENT' => 'DATA_SOURCE',
        'NAME' => Loc::getMessage('C_NEWS_LIST_CONTACTS_2_FEEDBACK_BUTTON_TEXT'),
        'TYPE' => 'TEXT'
    ];

    $arTemplateParameters['FEEDBACK_TEXT'] = [
        'PARENT' => 'DATA_SOURCE',
        'NAME' => Loc::getMessage('C_NEWS_LIST_CONTACTS_2_FEEDBACK_TEXT'),
        'TYPE' => 'TEXT'
    ];

    $arTemplateParameters['FEEDBACK_TEXT_SHOW'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_NEWS_LIST_CONTACTS_2_FEEDBACK_TEXT_SHOW'),
        'TYPE' => 'CHECKBOX',
    ];

    $arTemplateParameters['FEEDBACK_IMAGE'] = [
        'PARENT' => 'DATA_SOURCE',
        'NAME' => Loc::getMessage('C_NEWS_LIST_CONTACTS_2_FEEDBACK_IMAGE'),
        'TYPE' => 'TEXT',
        'DEFAULT' => '#TEMPLATE_PATH#/images/face.png'
    ];

    $arTemplateParameters['FEEDBACK_IMAGE_SHOW'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_NEWS_LIST_CONTACTS_2_FEEDBACK_IMAGE_SHOW'),
        'TYPE' => 'CHECKBOX',
    ];
}