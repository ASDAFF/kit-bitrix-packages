<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die() ?>
<?php

/**
 * @var array $arCurrentValues
 * @var array $arTemplateParameters
 */

use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ModuleManager;
use intec\core\bitrix\Component;
use intec\core\helpers\ArrayHelper;
use intec\core\helpers\StringHelper;

$rsTemplates = null;
$arForms = [];
$arTemplates = [];

if ($arCurrentValues['FORMS_CALL_SHOW'] == 'Y' || $arCurrentValues['FORMS_FEEDBACK_SHOW'] == 'Y') {
    if (Loader::includeModule('form')) {
        $rsForms = CForm::GetList(
            $by = 'sort',
            $order = 'asc',
            [],
            $filtered = false
        );

        while ($arForm = $rsForms->Fetch())
            $arForms[$arForm['ID']] = '['.$arForm['ID'].'] '.$arForm['NAME'];

        unset($rsForms);

        $rsTemplates = CComponentUtil::GetTemplatesList('bitrix:form.result.new', $siteTemplate);
    } else if (Loader::includeModule('intec.startshop')) {
        $rsForms = CStartShopForm::GetList();

        while ($arForm = $rsForms->Fetch())
            $arForms[$arForm['ID']] = '['.$arForm['ID'].'] '.(!empty($arForm['LANG'][LANGUAGE_ID]['NAME']) ? $arForm['LANG'][LANGUAGE_ID]['NAME'] : $arForm['CODE']);

        unset($rsForms);

        $rsTemplates = CComponentUtil::GetTemplatesList('intec:startshop.forms.result.new', $siteTemplate);
    } else {
        return;
    }

    foreach ($rsTemplates as $arTemplate) {
        $arTemplates[$arTemplate['NAME']] = $arTemplate['NAME'].(!empty($arTemplate['TEMPLATE']) ? ' ('.$arTemplate['TEMPLATE'].')' : null);
    }
}

$arTemplateParameters = [];

$arTemplateParameters['MODE'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_MENU_POPUP_3_MODE'),
    'TYPE' => 'LIST',
    'VALUES' => [
        'simple' => Loc::getMessage('C_MENU_POPUP_3_MODE_SIMPLE'),
        'extended' => Loc::getMessage('C_MENU_POPUP_3_MODE_EXTENDED')
    ]
];

$arTemplateParameters['THEME'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_MENU_POPUP_3_THEME'),
    'TYPE' => 'LIST',
    'VALUES' => [
        'light' => Loc::getMessage('C_MENU_POPUP_3_THEME_LIGHT'),
        'dark' => Loc::getMessage('C_MENU_POPUP_3_THEME_DARK')
    ]
];

$arTemplateParameters['BACKGROUND'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_MENU_POPUP_3_BACKGROUND'),
    'TYPE' => 'LIST',
    'REFRESH' => 'Y',
    'VALUES' => [
        'none' => Loc::getMessage('C_MENU_POPUP_3_BACKGROUND_NONE'),
        'color' => Loc::getMessage('C_MENU_POPUP_3_BACKGROUND_COLOR'),
        'picture' => Loc::getMessage('C_MENU_POPUP_3_BACKGROUND_PICTURE')
    ],
    'DEFAULT' => 'none'
];

if ($arCurrentValues['BACKGROUND'] == 'color') {
    $arTemplateParameters['BACKGROUND_COLOR'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_MENU_POPUP_3_BACKGROUND_COLOR_PICK'),
        "TYPE" => 'COLORPICKER',
        "DEFAULT" => '#FFFFFF'
    ];
} else if ($arCurrentValues['BACKGROUND'] == 'picture') {
    $arTemplateParameters['BACKGROUND_PICTURE'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_MENU_POPUP_3_BACKGROUND_PICTURE_URL'),
        'TYPE' => 'STRING'
    ];
}

$arTemplateParameters['LOGOTYPE_SHOW'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_MENU_POPUP_3_LOGOTYPE_SHOW'),
    'TYPE' => 'CHECKBOX',
    'REFRESH' => 'Y'
];

if ($arCurrentValues['LOGOTYPE_SHOW'] == 'Y') {
    $arTemplateParameters['LOGOTYPE'] = [
        'PARENT' => 'DATA_SOURCE',
        'NAME' => Loc::getMessage('C_MENU_POPUP_3_LOGOTYPE'),
        'TYPE' => 'STRING'
    ];

    $arTemplateParameters['LOGOTYPE_LINK'] = [
        'PARENT' => 'URL_TEMPLATES',
        'NAME' => Loc::getMessage('C_MENU_POPUP_3_LOGOTYPE_LINK'),
        'TYPE' => 'STRING'
    ];
}

$arTemplateParameters['CONTACTS_SHOW'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_MENU_POPUP_3_CONTACTS_SHOW'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'N',
    'REFRESH' => 'Y'
];

if ($arCurrentValues['CONTACTS_SHOW'] == 'Y') {

    $arTemplateParameters['CONTACTS_CITY'] = [
        'PARENT' => 'DATA_SOURCE',
        'NAME' => Loc::getMessage('C_MENU_POPUP_3_CONTACTS_CITY'),
        'TYPE' => 'STRING'
    ];

    $arTemplateParameters['CONTACTS_ADDRESS'] = [
        'PARENT' => 'DATA_SOURCE',
        'NAME' => Loc::getMessage('C_MENU_POPUP_3_CONTACTS_ADDRESS'),
        'TYPE' => 'STRING'
    ];

    $arTemplateParameters['CONTACTS_SCHEDULE'] = [
        'PARENT' => 'DATA_SOURCE',
        'NAME' => Loc::getMessage('C_MENU_POPUP_3_CONTACTS_SCHEDULE'),
        'TYPE' => 'STRING',
        'MULTIPLE' => 'Y'
    ];

    $arTemplateParameters['CONTACTS_PHONE'] = [
        'PARENT' => 'DATA_SOURCE',
        'NAME' => Loc::getMessage('C_MENU_POPUP_3_CONTACTS_PHONE'),
        'TYPE' => 'STRING'
    ];

    $arTemplateParameters['CONTACTS_EMAIL'] = [
        'PARENT' => 'DATA_SOURCE',
        'NAME' => Loc::getMessage('C_MENU_POPUP_3_CONTACTS_EMAIL'),
        'TYPE' => 'STRING'
    ];

    $arTemplateParameters['FORMS_CALL_SHOW'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_MENU_POPUP_3_FORMS_CALL_SHOW'),
        'TYPE' => 'CHECKBOX',
        'DEFAULT' => 'N',
        'REFRESH' => 'Y'
    ];

    if ($arCurrentValues['FORMS_CALL_SHOW'] == 'Y') {
        $arTemplateParameters['FORMS_CALL_ID'] = [
            'PARENT' => 'DATA_SOURCE',
            'NAME' => Loc::getMessage('C_MENU_POPUP_3_FORMS_CALL_ID'),
            'TYPE' => 'LIST',
            'VALUES' => $arForms,
            'ADDITIONAL_VALUES' => 'Y'
        ];
        $arTemplateParameters['FORMS_CALL_TEMPLATE'] = [
            'PARENT' => 'DATA_SOURCE',
            'NAME' => Loc::getMessage('C_MENU_POPUP_3_FORMS_CALL_TEMPLATE'),
            'TYPE' => 'LIST',
            'VALUES' => $arTemplates,
            'DEFAULT' => '.default',
            'ADDITIONAL_VALUES' => 'Y'
        ];
        $arTemplateParameters['FORMS_CALL_TITLE'] = [
            'PARENT' => 'VISUAL',
            'NAME' => Loc::getMessage('C_MENU_POPUP_3_FORMS_CALL_TITLE'),
            'TYPE' => 'STRING',
            'DEFAULT' => Loc::getMessage('C_MENU_POPUP_3_FORMS_CALL_TITLE_DEFAULT')
        ];
    }

    $arTemplateParameters['FORMS_FEEDBACK_SHOW'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_MENU_POPUP_3_FORMS_FEEDBACK_SHOW'),
        'TYPE' => 'CHECKBOX',
        'DEFAULT' => 'N',
        'REFRESH' => 'Y'
    ];

    if ($arCurrentValues['FORMS_FEEDBACK_SHOW'] == 'Y') {
        $arTemplateParameters['FORMS_FEEDBACK_ID'] = [
            'PARENT' => 'DATA_SOURCE',
            'NAME' => Loc::getMessage('C_MENU_POPUP_3_FORMS_FEEDBACK_ID'),
            'TYPE' => 'LIST',
            'VALUES' => $arForms,
            'ADDITIONAL_VALUES' => 'Y'
        ];
        $arTemplateParameters['FORMS_FEEDBACK_TEMPLATE'] = [
            'PARENT' => 'DATA_SOURCE',
            'NAME' => Loc::getMessage('C_MENU_POPUP_3_FORMS_FEEDBACK_TEMPLATE'),
            'TYPE' => 'LIST',
            'VALUES' => $arTemplates,
            'DEFAULT' => '.default',
            'ADDITIONAL_VALUES' => 'Y'
        ];
        $arTemplateParameters['FORMS_FEEDBACK_TITLE'] = [
            'PARENT' => 'VISUAL',
            'NAME' => Loc::getMessage('C_MENU_POPUP_3_FORMS_FEEDBACK_TITLE'),
            'TYPE' => 'STRING',
            'DEFAULT' => Loc::getMessage('C_MENU_POPUP_3_FORMS_FEEDBACK_TITLE_DEFAULT')
        ];
    }

    if ($arCurrentValues['FORMS_CALL_SHOW'] == 'Y' || $arCurrentValues['FORMS_FEEDBACK_SHOW'] == 'Y') {
        $arTemplateParameters['CONSENT_URL'] = [
            'PARENT' => 'URL_TEMPLATES',
            'NAME' => Loc::getMessage('C_MENU_POPUP_3_CONSENT_URL'),
            'TYPE' => 'STRING'
        ];
    }
}

$arTemplateParameters['SOCIAL_SHOW'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_MENU_POPUP_3_SOCIAL_SHOW'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'N',
    'REFRESH' => 'Y'
];

if ($arCurrentValues['SOCIAL_SHOW'] == 'Y') {

    $arTemplateParameters['SOCIAL_VK_LINK'] = [
        'PARENT' => 'DATA_SOURCE',
        'NAME' => Loc::getMessage('C_MENU_POPUP_3_SOCIAL_VK_LINK'),
        'TYPE' => 'STRING'
    ];

    $arTemplateParameters['SOCIAL_INSTAGRAM_LINK'] = [
        'PARENT' => 'DATA_SOURCE',
        'NAME' => Loc::getMessage('C_MENU_POPUP_3_SOCIAL_INSTAGRAM_LINK'),
        'TYPE' => 'STRING'
    ];

    $arTemplateParameters['SOCIAL_FACEBOOK_LINK'] = [
        'PARENT' => 'DATA_SOURCE',
        'NAME' => Loc::getMessage('C_MENU_POPUP_3_SOCIAL_FACEBOOK_LINK'),
        'TYPE' => 'STRING'
    ];

    $arTemplateParameters['SOCIAL_TWITTER_LINK'] = [
        'PARENT' => 'DATA_SOURCE',
        'NAME' => Loc::getMessage('C_MENU_POPUP_3_SOCIAL_TWITTER_LINK'),
        'TYPE' => 'STRING'
    ];
}

$arTemplateParameters['CATALOG_LINKS'] = [
    'NAME' => Loc::getMessage('C_MENU_POPUP_3_CATALOG_LINKS'),
    'PARENT' => 'VISUAL',
    'TYPE' => 'STRING',
    'MULTIPLE' => 'Y'
];

$arTemplateParameters['BASKET_SHOW'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_MENU_POPUP_3_BASKET_SHOW'),
    'TYPE' => 'CHECKBOX',
];

$arTemplateParameters['BASKET_URL'] = [
    'PARENT' => 'URL_TEMPLATES',
    'NAME' => Loc::getMessage('C_MENU_POPUP_3_BASKET_URL'),
    'TYPE' => 'STRING'
];

$arTemplateParameters['ORDER_URL'] = [
    'PARENT' => 'URL_TEMPLATES',
    'NAME' => Loc::getMessage('C_MENU_POPUP_3_ORDER_URL'),
    'TYPE' => 'STRING'
];

if (
    ModuleManager::isModuleInstalled('catalog') &&
    ModuleManager::isModuleInstalled('sale')
) {
    $arTemplateParameters['DELAY_SHOW'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_MENU_POPUP_3_DELAY_SHOW'),
        'TYPE' => 'CHECKBOX'
    ];
}

$arTemplateParameters['COMPARE_SHOW'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_MENU_POPUP_3_COMPARE_SHOW'),
    'TYPE' => 'CHECKBOX',
    'REFRESH' => 'Y'
];

if (Loader::includeModule('iblock')) {
    $arIBlocksTypes = CIBlockParameters::GetIBlockTypes();

    $arTemplateParameters['COMPARE_IBLOCK_TYPE'] = [
        'PARENT' => 'BASE',
        'NAME' => Loc::getMessage('C_MENU_POPUP_3_COMPARE_IBLOCK_TYPE'),
        'TYPE' => 'LIST',
        'VALUES' => $arIBlocksTypes,
        'ADDITIONAL_VALUES' => 'Y',
        'REFRESH' => 'Y'
    ];

    $arIBlocks = [];
    $rsIBlocks = CIBlock::GetList([], [
        'ACTIVE' => 'Y',
        'TYPE' => $arCurrentValues['COMPARE_IBLOCK_TYPE']
    ]);

    while ($arIBlock = $rsIBlocks->GetNext())
        $arIBlocks[$arIBlock['ID']] = '['.$arIBlock['ID'].'] '.$arIBlock['NAME'];

    $arTemplateParameters['COMPARE_IBLOCK_ID'] = [
        'PARENT' => 'BASE',
        'NAME' => Loc::getMessage('C_MENU_POPUP_3_COMPARE_IBLOCK_ID'),
        'TYPE' => 'LIST',
        'VALUES' => $arIBlocks,
        'ADDITIONAL_VALUES' => 'Y'
    ];
}

$arTemplateParameters['COMPARE_CODE'] = [
    'PARENT' => 'BASE',
    'NAME' => Loc::getMessage('C_MENU_POPUP_3_COMPARE_CODE'),
    'TYPE' => 'STRING'
];

$arTemplateParameters['COMPARE_URL'] = [
    'PARENT' => 'URL_TEMPLATES',
    'NAME' => Loc::getMessage('C_MENU_POPUP_3_COMPARE_URL'),
    'TYPE' => 'STRING'
];

$arTemplateParameters['AUTHORIZATION_SHOW'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_MENU_POPUP_3_AUTHORIZATION_SHOW'),
    'TYPE' => 'CHECKBOX',
    'REFRESH' => 'Y'
];

if ($arCurrentValues['AUTHORIZATION_SHOW'] === 'Y') {
    $arTemplateParameters['LOGIN_URL'] = [
        'PARENT' => 'URL_TEMPLATES',
        'NAME' => Loc::getMessage('C_MENU_POPUP_3_LOGIN_URL'),
        'TYPE' => 'STRING'
    ];

    $arTemplateParameters['PROFILE_URL'] = [
        'PARENT' => 'URL_TEMPLATES',
        'NAME' => Loc::getMessage('C_MENU_POPUP_3_PROFILE_URL'),
        'TYPE' => 'STRING'
    ];

    $arTemplateParameters['PASSWORD_URL'] = [
        'PARENT' => 'URL_TEMPLATES',
        'NAME' => Loc::getMessage('C_MENU_POPUP_3_PASSWORD_URL'),
        'TYPE' => 'STRING'
    ];

    $arTemplateParameters['REGISTER_URL'] = [
        'PARENT' => 'URL_TEMPLATES',
        'NAME' => Loc::getMessage('C_MENU_POPUP_3_REGISTER_URL'),
        'TYPE' => 'STRING'
    ];
}


$arTemplateParameters['SEARCH_SHOW'] = [
    'NAME' => Loc::getMessage('C_MENU_POPUP_3_SEARCH_SHOW'),
    'PARENT' => 'VISUAL',
    'TYPE' => 'CHECKBOX'
];

$arTemplateParameters = ArrayHelper::merge($arTemplateParameters, Component::getParameters(
    'bitrix:search.title',
    [
        'input.1'
    ],
    $siteTemplate,
    $arCurrentValues,
    'SEARCH_',
    function ($sKey, &$arParameter) {
        $arParameter['NAME'] = Loc::getMessage('C_MENU_POPUP_3_SEARCH').' '.$arParameter['NAME'];

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
    'NAME' => Loc::getMessage('C_MENU_POPUP_3_SEARCH_MODE'),
    'PARENT' => 'BASE',
    'TYPE' => 'LIST',
    'VALUES' => [
        'site' => Loc::getMessage('C_MENU_POPUP_3_SEARCH_MODE_SITE'),
        'catalog' => Loc::getMessage('C_MENU_POPUP_3_SEARCH_MODE_CATALOG')
    ]
];

$arTemplateParameters['SEARCH_URL'] = [
    'NAME'  => Loc::getMessage('C_MENU_POPUP_3_SEARCH_URL'),
    'PARENT' => 'URL_TEMPLATES',
    'TYPE' => 'STRING'
];