<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;

/**
 * @var array $arCurrentValues
 */

$arTemplateParameters = [];

$arTemplateParameters['SETTINGS_USE'] = [
    'PARENT' => 'BASE',
    'NAME' => Loc::getMessage('C_FORM_TEMP2_SETTINGS_USE'),
    'TYPE' => 'CHECKBOX'
];

$arTemplateParameters['LAZYLOAD_USE'] = [
    'PARENT' => 'BASE',
    'NAME' => Loc::getMessage('C_FORM_TEMP2_LAZYLOAD_USE'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'N'
];

/** DATA_SOURCE */
$arTemplateParameters['CONSENT'] = [
    'PARENT' => 'DATA_SOURCE',
    'NAME' => Loc::getMessage('C_FORM_TEMP2_CONSENT'),
    'TYPE' => 'STRING',
    'DEFAULT' => '#SITE_DIR#company/consent/'
];

/** VISUAL */

$arTemplateParameters['BODY_TEXT'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_FORM_TEMP2_BODY_TEXT'),
    'TYPE' => 'STRING',
    'DEFAULT' => Loc::getMessage('C_FORM_TEMP2_BODY_TEXT_DEFAULT')
];
$arTemplateParameters['BODY_DESCRIPTION_SHOW'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_FORM_TEMP2_BODY_DESCRIPTION_SHOW'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'N',
    'REFRESH' => 'Y'
];

if ($arCurrentValues['BODY_DESCRIPTION_SHOW'] == 'Y') {
    $arTemplateParameters['BODY_DESCRIPTION_TEXT'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_FORM_TEMP2_BODY_DESCRIPTION_TEXT'),
        'TYPE' => 'STRING'
    ];
}

$arTemplateParameters['BUTTON_TEXT'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_FORM_TEMP2_BUTTON_TEXT'),
    'TYPE' => 'STRING',
    'DEFAULT' => Loc::getMessage('C_FORM_TEMP2_BUTTON_TEXT_DEFAULT')
];
$arTemplateParameters['BUTTON_POSITION'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_FORM_TEMP2_BUTTON_POSITION'),
    'TYPE' => 'LIST',
    'VALUES' => [
        'right' => Loc::getMessage('C_FORM_TEMP2_BUTTON_POSITION_RIGHT'),
        'center' => Loc::getMessage('C_FORM_TEMP2_BUTTON_POSITION_CENTER')
    ],
    'DEFAULT' => 'right'
];
$arTemplateParameters['BUTTON_UNDER_SHOW'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_FORM_TEMP2_BUTTON_UNDER_SHOW'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'N',
    'REFRESH' => 'Y'
];

if ($arCurrentValues['BUTTON_UNDER_SHOW'] == 'Y') {
    $arTemplateParameters['BUTTON_UNDER_TEXT'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_FORM_TEMP2_BUTTON_UNDER_TEXT'),
        'TYPE' => 'STRING',
        'DEFAULT' => Loc::getMessage('C_FORM_TEMP2_BUTTON_UNDER_TEXT_DEFAULT')
    ];
}

$arTemplateParameters['THEME'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_FORM_TEMP2_THEME'),
    'TYPE' => 'LIST',
    'VALUES' => [
        'dark' => Loc::getMessage('C_FORM_TEMP2_THEME_DARK'),
        'light' => Loc::getMessage('C_FORM_TEMP2_THEME_LIGHT')
    ],
    'DEFAULT' => 'dark'
];

$arTemplateParameters['BACKGROUND_USE'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_FORM_TEMP2_BACKGROUND_USE'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'N',
    'REFRESH' => 'Y'
];

if ($arCurrentValues['BACKGROUND_USE'] == 'Y') {
    $arTemplateParameters['BACKGROUND_PATH'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_FORM_TEMP2_BACKGROUND_PATH'),
        'TYPE' => 'STRING',
        'DEFAULT' => '#TEMPLATE_PATH#images/background.png'
    ];
}