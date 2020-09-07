<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die() ?>
<?php

/**
 * @var array $arCurrentValues
 * @var array $arTemplateParameters
 */

use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use intec\core\bitrix\Component;
use intec\core\helpers\ArrayHelper;
use intec\core\helpers\StringHelper;

$arTemplateParameters = [];
$arTemplateParameters['LOGOTYPE_SHOW'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_MENU_POPUP_2_LOGOTYPE_SHOW'),
    'TYPE' => 'CHECKBOX',
    'REFRESH' => 'Y'
];

if ($arCurrentValues['LOGOTYPE_SHOW'] == 'Y') {
    $arTemplateParameters['LOGOTYPE'] = [
        'PARENT' => 'DATA_SOURCE',
        'NAME' => Loc::getMessage('C_MENU_POPUP_2_LOGOTYPE'),
        'TYPE' => 'STRING'
    ];

    $arTemplateParameters['LOGOTYPE_LINK'] = [
        'PARENT' => 'URL_TEMPLATES',
        'NAME' => Loc::getMessage('C_MENU_POPUP_2_LOGOTYPE_LINK'),
        'TYPE' => 'STRING'
    ];
}

$arTemplateParameters['SEARCH_SHOW'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_MENU_POPUP_2_SEARCH_SHOW'),
    'TYPE' => 'CHECKBOX',
    'REFRESH' => 'Y'
];

$arTemplateParameters = ArrayHelper::merge($arTemplateParameters, Component::getParameters(
    'bitrix:search.title',
    [
        'input.2'
    ],
    $siteTemplate,
    $arCurrentValues,
    'SEARCH_',
    function ($sKey, &$arParameter) {
        $arParameter['NAME'] = Loc::getMessage('C_MENU_POPUP_2_SEARCH').' '.$arParameter['NAME'];

        if (StringHelper::startsWith($sKey, 'CACHE'))
            return false;

        if (StringHelper::startsWith($sKey, 'COMPOSITE'))
            return false;

        if ($sKey == 'PAGE')
            return false;

        return true;
    }
));

$arTemplateParameters['SEARCH_MODE'] = [
    'PARENT' => 'DATA_SOURCE',
    'NAME' => Loc::getMessage('C_MENU_POPUP_2_SEARCH_MODE'),
    'TYPE' => 'LIST',
    'VALUES' => [
        'site' => Loc::getMessage('C_MENU_POPUP_2_SEARCH_MODE_SITE'),
        'catalog' => Loc::getMessage('C_MENU_POPUP_2_SEARCH_MODE_CATALOG'),
    ]
];

$arTemplateParameters['CONTACTS_ADVANCED'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_MENU_POPUP_2_CONTACTS_ADVANCED'),
    'TYPE' => 'CHECKBOX',
    'REFRESH' => 'Y'
];

$arTemplateParameters['CONTACTS_PHONE_SHOW'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_MENU_POPUP_2_CONTACTS_PHONE_SHOW'),
    'TYPE' => 'CHECKBOX'
];

$arTemplateParameters['CONTACTS_EMAIL_SHOW'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_MENU_POPUP_2_CONTACTS_EMAIL_SHOW'),
    'TYPE' => 'CHECKBOX'
];

$arTemplateParameters['CONTACTS_ADDRESS_SHOW'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_MENU_POPUP_2_CONTACTS_ADDRESS_SHOW'),
    'TYPE' => 'CHECKBOX'
];

$arTemplateParameters['CONTACTS_SCHEDULE_SHOW'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_MENU_POPUP_2_CONTACTS_SCHEDULE_SHOW'),
    'TYPE' => 'CHECKBOX'
];

if ($arCurrentValues['CONTACTS_ADVANCED'] === 'Y') {
    $arTemplateParameters['CONTACTS_ADDRESS'] = [
        'PARENT' => 'DATA_SOURCE',
        'NAME' => Loc::getMessage('C_MENU_POPUP_2_CONTACTS_ADDRESS'),
        'TYPE' => 'STRING',
        'MULTIPLE' => 'Y'
    ];

    $arTemplateParameters['CONTACTS_SCHEDULE'] = [
        'PARENT' => 'DATA_SOURCE',
        'NAME' => Loc::getMessage('C_MENU_POPUP_2_CONTACTS_SCHEDULE'),
        'TYPE' => 'STRING',
        'MULTIPLE' => 'Y'
    ];

    $arTemplateParameters['CONTACTS_PHONE'] = [
        'PARENT' => 'DATA_SOURCE',
        'NAME' => Loc::getMessage('C_MENU_POPUP_2_CONTACTS_PHONE'),
        'TYPE' => 'STRING',
        'MULTIPLE' => 'Y'
    ];

    $arTemplateParameters['CONTACTS_EMAIL'] = [
        'PARENT' => 'DATA_SOURCE',
        'NAME' => Loc::getMessage('C_MENU_POPUP_2_CONTACTS_EMAIL'),
        'TYPE' => 'STRING',
        'MULTIPLE' => 'Y'
    ];
} else {
    $arTemplateParameters['CONTACTS_ADDRESS'] = [
        'PARENT' => 'DATA_SOURCE',
        'NAME' => Loc::getMessage('C_MENU_POPUP_2_CONTACTS_ADDRESS'),
        'TYPE' => 'STRING'
    ];

    $arTemplateParameters['CONTACTS_SCHEDULE'] = [
        'PARENT' => 'DATA_SOURCE',
        'NAME' => Loc::getMessage('C_MENU_POPUP_2_CONTACTS_SCHEDULE'),
        'TYPE' => 'STRING',
    ];

    $arTemplateParameters['CONTACTS_PHONE'] = [
        'PARENT' => 'DATA_SOURCE',
        'NAME' => Loc::getMessage('C_MENU_POPUP_2_CONTACTS_PHONE'),
        'TYPE' => 'STRING'
    ];

    $arTemplateParameters['CONTACTS_EMAIL'] = [
        'PARENT' => 'DATA_SOURCE',
        'NAME' => Loc::getMessage('C_MENU_POPUP_2_CONTACTS_EMAIL'),
        'TYPE' => 'STRING'
    ];
}

if (Loader::includeModule('form')) {
    include('parameters/base/forms.php');
} else if (Loader::includeModule('intec.startshop')) {
    include('parameters/lite/forms.php');
} else {
    return;
}

$arTemplateParameters['FORMS_CALL_SHOW'] = [
    'PARENT' => 'PRICES',
    'NAME' => Loc::getMessage('C_MENU_POPUP_2_FORMS_CALL_SHOW'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'N',
    'REFRESH' => 'Y'
];

if ($arCurrentValues['FORMS_CALL_SHOW'] == 'Y') {

    $arTemplateParameters['FORMS_CALL_ID'] = [
        'PARENT' => 'DATA_SOURCE',
        'NAME' => Loc::getMessage('C_MENU_POPUP_2_FORMS_CALL_ID'),
        'TYPE' => 'LIST',
        'VALUES' => $arForms,
        'ADDITIONAL_VALUES' => 'Y'
    ];

    $arTemplates = [];

    foreach ($rsTemplates as $arTemplate) {
        $arTemplates[$arTemplate['NAME']] = $arTemplate['NAME'] . (!empty($arTemplate['TEMPLATE']) ? ' (' . $arTemplate['TEMPLATE'] . ')' : null);
    }

    $arTemplateParameters['FORMS_CALL_TEMPLATE'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_MENU_POPUP_2_FORMS_CALL_TEMPLATE'),
        'TYPE' => 'LIST',
        'VALUES' => $arTemplates,
        'ADDITIONAL_VALUES' => 'Y'
    ];

    $arTemplateParameters['FORMS_CALL_TITLE'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_MENU_POPUP_2_FORMS_CALL_TITLE'),
        'TYPE' => 'STRING',
        'DEFAULT' => Loc::getMessage('C_MENU_POPUP_2_FORMS_CALL_TITLE_DEFAULT')
    ];

    $arTemplateParameters['CONSENT_URL'] = [
        'PARENT' => 'URL_TEMPLATES',
        'NAME' => Loc::getMessage('C_MENU_POPUP_2_CONSENT_URL'),
        'TYPE' => 'STRING'
    ];
}