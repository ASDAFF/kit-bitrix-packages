<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;

/**
 * @var array $arCurrentValues
 */

$arForms = [];
$bBase = false;
$rsTemplates = null;

if (Loader::includeModule('form')) {
    $bBase = true;
    include(__DIR__.'/parameters/base.php');
} elseif (Loader::includeModule('intec.startshop')) {
    include(__DIR__.'/parameters/lite.php');
} else
    return;

foreach ($rsTemplates as $arTemplate) {
    $arTemplates[$arTemplate['NAME']] = $arTemplate['NAME'].(!empty($arTemplate['TEMPLATE']) ? ' ('.$arTemplate['TEMPLATE'].')' : null);
}

unset($rsTemplates, $arTemplate);

$arTemplateParameters = [];

/** BASE */
$arTemplateParameters['WEB_FORM_ID'] = [
    'PARENT' => 'BASE',
    'NAME' => Loc::getMessage('C_WIDGET_FORM_3_WEB_FORM_ID'),
    'TYPE' => 'LIST',
    'VALUES' => $arForms,
    'ADDITIONAL_VALUES' => 'Y',
    'REFRESH' => 'Y'
];

/** DATA_SOURCE */
$arTemplateParameters['WEB_FORM_CONSENT_LINK'] = [
    'PARENT' => 'DATA_SOURCE',
    'NAME' => Loc::getMessage('C_WIDGET_FORM_3_WEB_FORM_CONSENT_LINK'),
    'TYPE' => 'STRING'
];

/** VISUAL */
$arTemplateParameters['WEB_FORM_TITLE_SHOW'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_WIDGET_FORM_3_WEB_FORM_TITLE_SHOW'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'N',
    'REFRESH' => 'Y'
];

if ($arCurrentValues['WEB_FORM_TITLE_SHOW'] == 'Y') {
    $arTemplateParameters['WEB_FORM_TITLE_POSITION'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_WIDGET_FORM_3_WEB_FORM_TITLE_POSITION'),
        'TYPE' => 'LIST',
        'VALUES' => [
            'left' => Loc::getMessage('C_WIDGET_FORM_3_WEB_FORM_POSITION_LEFT'),
            'center' => Loc::getMessage('C_WIDGET_FORM_3_WEB_FORM_POSITION_CENTER'),
            'right' => Loc::getMessage('C_WIDGET_FORM_3_WEB_FORM_POSITION_RIGHT')
        ],
        'DEFAULT' => 'center'
    ];
}

if ($bBase) {
    $arTemplateParameters['WEB_FORM_DESCRIPTION_SHOW'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_WIDGET_FORM_3_WEB_FORM_DESCRIPTION_SHOW'),
        'TYPE' => 'CHECKBOX',
        'DEFAULT' => 'N'
    ];

    if ($arCurrentValues['WEB_FORM_DESCRIPTION_SHOW'] == 'Y') {
        $arTemplateParameters['WEB_FORM_DESCRIPTION_POSITION'] = [
            'PARENT' => 'VISUAL',
            'NAME' => Loc::getMessage('C_WIDGET_FORM_3_WEB_FORM_DESCRIPTION_POSITION'),
            'TYPE' => 'LIST',
            'VALUES' => [
                'left' => Loc::getMessage('C_WIDGET_FORM_3_WEB_FORM_POSITION_LEFT'),
                'center' => Loc::getMessage('C_WIDGET_FORM_3_WEB_FORM_POSITION_CENTER'),
                'right' => Loc::getMessage('C_WIDGET_FORM_3_WEB_FORM_POSITION_RIGHT')
            ],
            'DEFAULT' => 'center'
        ];
    }
}

$arTemplateParameters['WEB_FORM_THEME'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_WIDGET_FORM_3_WEB_FORM_THEME'),
    'TYPE' => 'LIST',
    'VALUES' => [
        'dark' => Loc::getMessage('C_WIDGET_FORM_3_WEB_FORM_THEME_DARK'),
        'light' => Loc::getMessage('C_WIDGET_FORM_3_WEB_FORM_THEME_LIGHT')
    ],
    'DEFAULT' => 'dark'
];

$arTemplateParameters['WEB_FORM_BACKGROUND_USE'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_WIDGET_FORM_3_WEB_FORM_BACKGROUND_USE'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'N',
    'REFRESH' => 'Y'
];

if ($arCurrentValues['WEB_FORM_BACKGROUND_USE'] == 'Y') {
    $arTemplateParameters['WEB_FORM_BACKGROUND_COLOR'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_WIDGET_FORM_3_WEB_FORM_BACKGROUND_COLOR'),
        'TYPE' => 'LIST',
        'VALUES' => [
            'theme' => Loc::getMessage('C_WIDGET_FORM_3_WEB_FORM_COLOR_THEME'),
            'custom' => Loc::getMessage('C_WIDGET_FORM_3_WEB_FORM_COLOR_CUSTOM')
        ],
        'DEFAULT' => 'theme',
        'REFRESH' => 'Y'
    ];

    if ($arCurrentValues['WEB_FORM_BACKGROUND_COLOR'] == 'custom') {
        $arTemplateParameters['WEB_FORM_BACKGROUND_COLOR_CUSTOM'] = [
            'PARENT' => 'VISUAL',
            'NAME' => Loc::getMessage('C_WIDGET_FORM_3_WEB_FORM_BACKGROUND_COLOR_CUSTOM'),
            'TYPE' => 'STRING'
        ];
    }

    $arTemplateParameters['WEB_FORM_BACKGROUND_OPACITY'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_WIDGET_FORM_3_WEB_FORM_BACKGROUND_OPACITY'),
        'TYPE' => 'STRING'
    ];
}

$arTemplateParameters['WEB_FORM_CONSENT_SHOW'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_WIDGET_FORM_3_WEB_FORM_CONSENT_SHOW'),
    'TYPE' => 'LIST',
    'VALUES' => [
        'Y' => Loc::getMessage('C_WIDGET_FORM_3_WEB_FORM_CONSENT_SHOW_Y'),
        'N' => Loc::getMessage('C_WIDGET_FORM_3_WEB_FORM_CONSENT_SHOW_N'),
        'parameters' => Loc::getMessage('C_WIDGET_FORM_3_WEB_FORM_CONSENT_SHOW_PARAMETERS')
    ],
    'DEFAULT' => 'parameters'
];