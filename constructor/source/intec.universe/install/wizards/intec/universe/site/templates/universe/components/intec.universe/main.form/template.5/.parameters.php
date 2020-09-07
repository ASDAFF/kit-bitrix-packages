<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;

/**
 * @var array $arCurrentValues
 */

$sFormOneName = null;
$sFormTwoName = null;

if (Loader::includeModule('form')) {
    include('parameters/base.php');
} elseif (Loader::includeModule('intec.startshop')) {
    include('parameters/lite.php');
} else
    return;

$arTemplateParameters = [];
$arTemplateParameters['SETTINGS_USE'] = [
    'PARENT' => 'BASE',
    'NAME' => Loc::getMessage('C_MAIN_FORM_TEMPLATE_5_SETTINGS_USE'),
    'TYPE' => 'CHECKBOX'
];

$arTemplateParameters['LAZYLOAD_USE'] = [
    'PARENT' => 'BASE',
    'NAME' => Loc::getMessage('C_MAIN_FORM_TEMPLATE_5_LAZYLOAD_USE'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'N'
];
$arTemplateParameters['ID'] = ['HIDDEN' => 'Y'];
$arTemplateParameters['NAME'] = ['HIDDEN' => 'Y'];

$arTemplateParameters['FORM_ID'] = [
    'PARENT' => 'BASE',
    'NAME' => Loc::getMessage('C_MAIN_FORM_TEMPLATE_5_FORM_ID'),
    'TYPE' => 'LIST',
    'VALUES' => $arForms,
    'ADDITIONAL_VALUES' => 'Y',
    'REFRESH' => 'Y'
];

$arTemplateParameters['FORM_NAME'] = [
    'PARENT' => 'BASE',
    'NAME' => Loc::getMessage('C_MAIN_FORM_TEMPLATE_5_FORM_NAME'),
    'TYPE' => 'STRING'
];

$arTemplateParameters['FORM_BUTTON'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_MAIN_FORM_TEMPLATE_5_FORM_BUTTON'),
    'TYPE' => 'STRING',
    'DEFAULT' => Loc::getMessage('C_MAIN_FORM_TEMPLATE_5_FORM_BUTTON_DEFAULT')
];

$arTemplateParameters['TEXT_COLOR'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_MAIN_FORM_TEMPLATE_5_TEXT_COLOR'),
    'TYPE' => 'STRING'
];

$arTemplateParameters['BACKGROUND_IMAGE'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_MAIN_FORM_TEMPLATE_5_BACKGROUND_IMAGE'),
    'TYPE' => 'STRING'
];

$arTemplateParameters['BACKGROUND_COLOR'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_MAIN_FORM_TEMPLATE_5_BACKGROUND_COLOR'),
    'TYPE' => 'STRING'
];

/** VISUAL */

$arTemplateParameters['IMAGE_SHOW'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_MAIN_FORM_TEMPLATE_5_IMAGE_SHOW'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'N',
    'REFRESH' => 'Y'
];

if ($arCurrentValues['IMAGE_SHOW'] == 'Y') {
    $arTemplateParameters['IMAGE'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_MAIN_FORM_TEMPLATE_5_IMAGE'),
        'TYPE' => 'STRING',
        'DEFAULT' => '#TEMPLATE_PATH#images/face.png'
    ];
}

$arTemplateParameters['TITLE'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_MAIN_FORM_TEMPLATE_5_TITLE'),
    'TYPE' => 'STRING',
    'DEFAULT' => Loc::getMessage('C_MAIN_FORM_TEMPLATE_5_TITLE_DEFAULT')
];

$arTemplateParameters['DESCRIPTION_SHOW'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_MAIN_FORM_TEMPLATE_5_DESCRIPTION_SHOW'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'N',
    'REFRESH' => 'Y'
];

if ($arCurrentValues['DESCRIPTION_SHOW'] == 'Y') {
    $arTemplateParameters['DESCRIPTION'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_MAIN_FORM_TEMPLATE_5_DESCRIPTION'),
        'TYPE' => 'STRING'
    ];
}