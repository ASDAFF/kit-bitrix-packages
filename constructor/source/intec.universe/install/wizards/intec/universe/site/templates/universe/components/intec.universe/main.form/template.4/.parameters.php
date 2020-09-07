<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;

/**
 * @var array $arCurrentValues
 */

if (Loader::includeModule('form')) {
    include(__DIR__.'/parameters/base.php');
} else if (Loader::includeModule('intec.startshop')) {
    include(__DIR__.'/parameters/lite.php');
} else
    return;

$arTemplateParameters = [];
$arTemplateParameters['SETTINGS_USE'] = [
    'PARENT' => 'BASE',
    'NAME' => Loc::getMessage('C_MAIN_FORM_TEMPLATE_4_SETTINGS_USE'),
    'TYPE' => 'CHECKBOX'
];

$arTemplateParameters['LAZYLOAD_USE'] = [
    'PARENT' => 'BASE',
    'NAME' => Loc::getMessage('C_MAIN_FORM_TEMPLATE_4_LAZYLOAD_USE'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'N'
];
$arTemplateParameters['ID'] = ['HIDDEN' => 'Y'];
$arTemplateParameters['NAME'] = ['HIDDEN' => 'Y'];

$arTemplateParameters['FORMS_1_ID'] = [
    'PARENT' => 'BASE',
    'NAME' => Loc::getMessage('C_MAIN_FORM_TEMPLATE_4_FORMS_1_ID'),
    'TYPE' => 'LIST',
    'VALUES' => $arForms,
    'ADDITIONAL_VALUES' => 'Y',
    'REFRESH' => 'Y'
];

$arTemplateParameters['FORMS_2_ID'] = [
    'PARENT' => 'BASE',
    'NAME' => Loc::getMessage('C_MAIN_FORM_TEMPLATE_4_FORMS_2_ID'),
    'TYPE' => 'LIST',
    'VALUES' => $arForms,
    'ADDITIONAL_VALUES' => 'Y',
    'REFRESH' => 'Y'
];

$arTemplateParameters['FORMS_1_NAME'] = [
    'PARENT' => 'BASE',
    'NAME' => Loc::getMessage('C_MAIN_FORM_TEMPLATE_4_FORMS_1_NAME'),
    'TYPE' => 'STRING'
];

$arTemplateParameters['FORMS_2_NAME'] = [
    'PARENT' => 'BASE',
    'NAME' => Loc::getMessage('C_MAIN_FORM_TEMPLATE_4_FORMS_2_NAME'),
    'TYPE' => 'STRING'
];

$arTemplateParameters['FORMS_1_BUTTON'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_MAIN_FORM_TEMPLATE_4_FORMS_1_BUTTON'),
    'TYPE' => 'STRING',
    'DEFAULT' => Loc::getMessage('C_MAIN_FORM_TEMPLATE_4_FORMS_1_BUTTON_DEFAULT')
];

$arTemplateParameters['FORMS_2_BUTTON'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_MAIN_FORM_TEMPLATE_4_FORMS_2_BUTTON'),
    'TYPE' => 'STRING',
    'DEFAULT' => Loc::getMessage('C_MAIN_FORM_TEMPLATE_4_FORMS_2_BUTTON_DEFAULT')
];

/** VISUAL */

$arTemplateParameters['IMAGE_SHOW'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_MAIN_FORM_TEMPLATE_4_IMAGE_SHOW'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'N',
    'REFRESH' => 'Y'
];

if ($arCurrentValues['IMAGE_SHOW'] == 'Y') {
    $arTemplateParameters['IMAGE'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_MAIN_FORM_TEMPLATE_4_IMAGE'),
        'TYPE' => 'STRING',
        'DEFAULT' => '#TEMPLATE_PATH#images/face.png'
    ];
}

$arTemplateParameters['TITLE'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_MAIN_FORM_TEMPLATE_4_TITLE'),
    'TYPE' => 'STRING',
    'DEFAULT' => Loc::getMessage('C_MAIN_FORM_TEMPLATE_4_TITLE_DEFAULT')
];

$arTemplateParameters['CONSULTANT_NAME'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_MAIN_FORM_TEMPLATE_4_CONSULTANT_NAME'),
    'TYPE' => 'STRING',
    'DEFAULT' => Loc::getMessage('C_MAIN_FORM_TEMPLATE_4_CONSULTANT_NAME_DEFAULT')
];

$arTemplateParameters['CONSULTANT_POST'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_MAIN_FORM_TEMPLATE_4_CONSULTANT_POST'),
    'TYPE' => 'STRING',
    'DEFAULT' => Loc::getMessage('C_MAIN_FORM_TEMPLATE_4_CONSULTANT_POST_DEFAULT')
];

$arTemplateParameters['DESCRIPTION_SHOW'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_MAIN_FORM_TEMPLATE_4_DESCRIPTION_SHOW'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'N',
    'REFRESH' => 'Y'
];

if ($arCurrentValues['DESCRIPTION_SHOW'] == 'Y') {
    $arTemplateParameters['DESCRIPTION'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_MAIN_FORM_TEMPLATE_4_DESCRIPTION'),
        'TYPE' => 'STRING'
    ];
}