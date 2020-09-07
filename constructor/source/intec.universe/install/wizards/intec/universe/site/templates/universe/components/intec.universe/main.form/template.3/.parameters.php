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

$arTemplateParameters = array();

$arTemplateParameters['SETTINGS_USE'] = [
    'PARENT' => 'BASE',
    'NAME' => Loc::getMessage('C_FORM_TEMP3_SETTINGS_USE'),
    'TYPE' => 'CHECKBOX'
];

$arTemplateParameters['LAZYLOAD_USE'] = [
    'PARENT' => 'BASE',
    'NAME' => Loc::getMessage('C_FORM_TEMP3_LAZYLOAD_USE'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'N'
];

$arTemplateParameters['ID'] = array('HIDDEN' => 'Y');
$arTemplateParameters['NAME'] = array('HIDDEN' => 'Y');

$arTemplateParameters['FORM1_ID'] = array(
    'PARENT' => 'BASE',
    'NAME' => Loc::getMessage('C_FORM_TEMP3_FORM1_ID'),
    'TYPE' => 'LIST',
    'VALUES' => $arForms,
    'ADDITIONAL_VALUES' => 'Y',
    'REFRESH' => 'Y'
);

$arTemplateParameters['FORM2_ID'] = array(
    'PARENT' => 'BASE',
    'NAME' => Loc::getMessage('C_FORM_TEMP3_FORM2_ID'),
    'TYPE' => 'LIST',
    'VALUES' => $arForms,
    'ADDITIONAL_VALUES' => 'Y',
    'REFRESH' => 'Y'
);

$arTemplateParameters['FORM1_NAME'] = array(
    'PARENT' => 'BASE',
    'NAME' => Loc::getMessage('C_FORM_TEMP3_FORM1_NAME'),
    'TYPE' => 'STRING',
    'DEFAULT' => $sFormOneName
);

$arTemplateParameters['FORM2_NAME'] = array(
    'PARENT' => 'BASE',
    'NAME' => Loc::getMessage('C_FORM_TEMP3_FORM2_NAME'),
    'TYPE' => 'STRING',
    'DEFAULT' => $sFormTwoName
);

/** VISUAL */
$arTemplateParameters['IMAGE'] = array(
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_FORM_TEMP3_IMAGE'),
    'TYPE' => 'STRING',
    'DEFAULT' => '#TEMPLATE_PATH#/images/main-form-temp-3.png'
);

$arTemplateParameters['TITLE'] = array(
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_FORM_TEMP3_TITLE'),
    'TYPE' => 'STRING',
    'DEFAULT' => Loc::getMessage('C_FORM_TEMP3_TITLE_DEFAULT')
);

$arTemplateParameters['DESCRIPTION_SHOW'] = array(
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_FORM_TEMP3_DESCRIPTION_SHOW'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'N',
    'REFRESH' => 'Y'
);

if ($arCurrentValues['DESCRIPTION_SHOW'] == 'Y') {
    $arTemplateParameters['DESCRIPTION'] = array(
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_FORM_TEMP3_DESCRIPTION'),
        'TYPE' => 'STRING'
    );
}

$arTemplateParameters['BUTTON1_TEXT'] = array(
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_FORM_TEMP3_BUTTON1_TEXT'),
    'TYPE' => 'STRING',
    'DEFAULT' => Loc::getMessage('C_FORM_TEMP3_BUTTON1_TEXT_DEFAULT')
);

$arTemplateParameters['BUTTON2_TEXT'] = array(
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_FORM_TEMP3_BUTTON2_TEXT'),
    'TYPE' => 'STRING',
    'DEFAULT' => Loc::getMessage('C_FORM_TEMP3_BUTTON2_TEXT_DEFAULT')
);