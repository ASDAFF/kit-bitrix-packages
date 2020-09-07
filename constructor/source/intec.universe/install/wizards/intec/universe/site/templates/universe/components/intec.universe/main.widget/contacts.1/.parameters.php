<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Currency;
use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use intec\core\collections\Arrays;

/**
 * @var array $arCurrentValues
 * @var string $componentName
 * @var string $componentTemplate
 * @var string $siteTemplate
 */

if (!Loader::includeModule('iblock'))
    return;

if (!Loader::includeModule('intec.core'))
    return;

Loc::loadMessages(__FILE__);

$bBase = false;
$bLite = false;

$arIBlocksTypes = CIBlockParameters::GetIBlockTypes();
$arIBlocksFilter = [
    'ACTIVE' => 'Y'
];

$sIBlockType = $arCurrentValues['IBLOCK_TYPE'];

if (!empty($sIBlockType))
    $arIBlocksFilter['TYPE'] = $sIBlockType;

$arIBlocks = Arrays::fromDBResult(CIBlock::GetList(['SORT' => 'ASC'], $arIBlocksFilter))->indexBy('ID');
$arIBlock = $arIBlocks->get($arCurrentValues['IBLOCK_ID']);

$bOffersIblockExist = false;

$arOfferProperties = [];


$arTemplateParameters = [];

$arTemplateParameters['LAZYLOAD_USE'] = [
    'PARENT' => 'BASE',
    'NAME' => Loc::getMessage('C_WIDGET_CONTACTS_1_LAZYLOAD_USE'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'N'
];

$arTemplateParameters['IBLOCK_TYPE'] = [
    'PARENT' => 'BASE',
    'NAME' => Loc::getMessage('C_WIDGET_CONTACTS_1_IBLOCK_TYPE'),
    'TYPE' => 'LIST',
    'VALUES' => $arIBlocksTypes,
    'ADDITIONAL_VALUES' => 'Y',
    'REFRESH' => 'Y'
];

$arTemplateParameters['IBLOCK_ID'] = [
    'PARENT' => 'BASE',
    'NAME' => Loc::getMessage('C_WIDGET_CONTACTS_1_IBLOCK_ID'),
    'TYPE' => 'LIST',
    'VALUES' => $arIBlocks->asArray(function ($iId, $arIBlock) {
        return [
            'key' => $arIBlock['ID'],
            'value' => '['.$arIBlock['ID'].'] '.$arIBlock['NAME']
        ];
    }),
    'ADDITIONAL_VALUES' => 'Y',
    'REFRESH' => 'Y'
];

$arTemplateParameters['SETTINGS_USE'] = [
    'PARENT' => 'BASE',
    'NAME' => Loc::getMessage('C_WIDGET_CONTACTS_1_SETTINGS_USE'),
    'TYPE' => 'CHECKBOX'
];

$arTemplateParameters['NEWS_COUNT'] = [
    'PARENT' => 'BASE',
    'NAME' => Loc::getMessage('C_WIDGET_CONTACTS_1_NEWS_COUNT'),
    'TYPE' => 'STRING'
];

/** DATA_SOURCE */
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

    $hPropertyAll = function ($sKey, $arProperty) {
        if ($arProperty)
            return [
                'key' => $arProperty['CODE'],
                'value' => '['.$arProperty['CODE'].'] '.$arProperty['NAME']
            ];

        return ['skip' => true];
    };

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
        'NAME' => Loc::getMessage('C_WIDGET_CONTACTS_1_MAIN'),
        'TYPE' => 'LIST',
        'VALUES' => $arElements->asArray($hElements)
    ];

    $arTemplateParameters['PROPERTY_CODE'] = [
        'PARENT' => 'DATA_SOURCE',
        'NAME' => Loc::getMessage('C_WIDGET_CONTACTS_1_PROPERTY_CODE'),
        'TYPE' => 'LIST',
        'VALUES' => $arProperties->asArray($hPropertyAll),
        'ADDITIONAL_VALUES' => 'Y',
        'MULTIPLE' => 'Y'
    ];

    $arTemplateParameters['PROPERTY_ADDRESS'] = [
        'PARENT' => 'DATA_SOURCE',
        'NAME' => Loc::getMessage('C_WIDGET_CONTACTS_1_PROPERTY_ADDRESS'),
        'TYPE' => 'LIST',
        'VALUES' => $arPropertyText,
        'ADDITIONAL_VALUES' => 'Y'
    ];
    $arTemplateParameters['PROPERTY_CITY'] = [
        'PARENT' => 'DATA_SOURCE',
        'NAME' => Loc::getMessage('C_WIDGET_CONTACTS_1_PROPERTY_CITY'),
        'TYPE' => 'LIST',
        'VALUES' => $arPropertyText,
        'ADDITIONAL_VALUES' => 'Y'
    ];
    $arTemplateParameters['PROPERTY_PHONE'] = [
        'PARENT' => 'DATA_SOURCE',
        'NAME' => Loc::getMessage('C_WIDGET_CONTACTS_1_PROPERTY_PHONE'),
        'TYPE' => 'LIST',
        'VALUES' => $arPropertyTextAll,
        'ADDITIONAL_VALUES' => 'Y'
    ];
    $arTemplateParameters['PROPERTY_MAP'] = [
        'PARENT' => 'DATA_SOURCE',
        'NAME' => Loc::getMessage('C_WIDGET_CONTACTS_1_PROPERTY_MAP'),
        'TYPE' => 'LIST',
        'VALUES' => $arPropertyMap,
        'ADDITIONAL_VALUES' => 'Y'
    ];
}

$arTemplateParameters['MAP_VENDOR'] = [
    'PARENT' => 'DATA_SOURCE',
    'NAME' => Loc::getMessage('C_WIDGET_CONTACTS_1_MAP_VENDOR'),
    'TYPE' => 'LIST',
    'VALUES' => array(
        'google' => Loc::getMessage('C_WIDGET_CONTACTS_1_MAP_VENDOR_GOOGLE'),
        'yandex' => Loc::getMessage('C_WIDGET_CONTACTS_1_MAP_VENDOR_YANDEX'),
    ),
    'ADDITIONAL_VALUES' => 'N',
    'DEFAULT' => 'google'
];

$arTemplateParameters['SETTINGS_USE'] = [
    'PARENT' => 'BASE',
    'NAME' => Loc::getMessage('C_WIDGET_CONTACTS_1_SETTINGS_USE'),
    'TYPE' => 'CHECKBOX'
];

if (!empty($arCurrentValues['PROPERTY_ADDRESS'])) {
    $arTemplateParameters['ADDRESS_SHOW'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_WIDGET_CONTACTS_1_ADDRESS_SHOW'),
        'TYPE' => 'CHECKBOX',
        'DEFAULT' => 'N'
    ];
}

if (!empty($arCurrentValues['PROPERTY_PHONE'])) {
    $arTemplateParameters['PHONE_SHOW'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_WIDGET_CONTACTS_1_PHONE_SHOW'),
        'TYPE' => 'CHECKBOX',
        'DEFAULT' => 'N'
    ];
}

$arTemplateParameters['SHOW_FORM'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_WIDGET_CONTACTS_1_SHOW_FORM'),
    'TYPE' => 'CHECKBOX',
    'REFRESH' => 'Y'
];

if ($arCurrentValues['SHOW_FORM'] == 'Y') {
    $arForms = array();

    if (Loader::IncludeModule('form')) {
        include('parameters/base/forms.php');
    } else if (Loader::IncludeModule('intec.startshop')) {
        include('parameters/lite/forms.php');
    }

    $arTemplates = [];

    foreach ($rsTemplates as $arTemplate)
        $arTemplates[$arTemplate['NAME']] = $arTemplate['NAME'].(!empty($arTemplate['TEMPLATE']) ? ' ('.$arTemplate['TEMPLATE'].')' : null);

    $arTemplateParameters['WEB_FORM_TEMPLATE'] = [
        'PARENT' => 'DATA_SOURCE',
        'NAME' => Loc::getMessage('C_WIDGET_CONTACTS_1_WEB_FORM_TEMPLATE'),
        'TYPE' => 'LIST',
        'VALUES' => $arTemplates
    ];

    $arTemplateParameters['WEB_FORM_ID'] = [
        'PARENT' => 'DATA_SOURCE',
        'NAME' => Loc::getMessage('C_WIDGET_CONTACTS_1_WEB_FORM_ID'),
        'TYPE' => 'LIST',
        'VALUES' => $arForms,
        'ADDITIONAL_VALUES' => 'Y'
    ];

    $arTemplateParameters['WEB_FORM_NAME'] = [
        'PARENT' => 'DATA_SOURCE',
        'NAME' => Loc::getMessage('C_WIDGET_CONTACTS_1_WEB_FORM_NAME'),
        'TYPE' => 'TEXT'
    ];

    $arTemplateParameters['WEB_FORM_CONSENT_URL'] = [
        'PARENT' => 'DATA_SOURCE',
        'NAME' => Loc::getMessage('C_WIDGET_CONTACTS_1_WEB_FORM_CONSENT_URL'),
        'TYPE' => 'TEXT'
    ];

    $arTemplateParameters['FEEDBACK_BUTTON_TEXT'] = [
        'PARENT' => 'DATA_SOURCE',
        'NAME' => Loc::getMessage('C_WIDGET_CONTACTS_1_FEEDBACK_BUTTON_TEXT'),
        'TYPE' => 'TEXT'
    ];

    $arTemplateParameters['FEEDBACK_TEXT'] = [
        'PARENT' => 'DATA_SOURCE',
        'NAME' => Loc::getMessage('C_WIDGET_CONTACTS_1_FEEDBACK_TEXT'),
        'TYPE' => 'TEXT'
    ];

    $arTemplateParameters['FEEDBACK_TEXT_SHOW'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_WIDGET_CONTACTS_1_FEEDBACK_TEXT_SHOW'),
        'TYPE' => 'CHECKBOX',
    ];

    $arTemplateParameters['FEEDBACK_IMAGE'] = [
        'PARENT' => 'DATA_SOURCE',
        'NAME' => Loc::getMessage('C_WIDGET_CONTACTS_1_FEEDBACK_IMAGE'),
        'TYPE' => 'TEXT',
        'DEFAULT' => '#TEMPLATE_PATH#/images/face.png'
    ];

    $arTemplateParameters['FEEDBACK_IMAGE_SHOW'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_WIDGET_CONTACTS_1_FEEDBACK_IMAGE_SHOW'),
        'TYPE' => 'CHECKBOX',
    ];
}

include(__DIR__.'/parameters/regionality.php');