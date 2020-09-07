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

$arTemplateParameters['ID'] = array('HIDDEN' => 'Y');
$arTemplateParameters['NAME'] = array('HIDDEN' => 'Y');

$arTemplateParameters['FORMS_1_ID'] = array(
    'PARENT' => 'BASE',
    'NAME' => Loc::getMessage('C_MAIN_FORM_TEMPLATE_6_FORMS_1_ID'),
    'TYPE' => 'LIST',
    'VALUES' => $arForms,
    'ADDITIONAL_VALUES' => 'Y',
    'REFRESH' => 'Y'
);

$arTemplateParameters['FORMS_2_ID'] = array(
    'PARENT' => 'BASE',
    'NAME' => Loc::getMessage('C_MAIN_FORM_TEMPLATE_6_FORMS_2_ID'),
    'TYPE' => 'LIST',
    'VALUES' => $arForms,
    'ADDITIONAL_VALUES' => 'Y',
    'REFRESH' => 'Y'
);

$arTemplateParameters['FORMS_1_NAME'] = array(
    'PARENT' => 'BASE',
    'NAME' => Loc::getMessage('C_MAIN_FORM_TEMPLATE_6_FORMS_1_NAME'),
    'TYPE' => 'STRING'
);

$arTemplateParameters['FORMS_2_NAME'] = array(
    'PARENT' => 'BASE',
    'NAME' => Loc::getMessage('C_MAIN_FORM_TEMPLATE_6_FORMS_2_NAME'),
    'TYPE' => 'STRING'
);

$arTemplateParameters['FORMS_1_BUTTON'] = array(
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_MAIN_FORM_TEMPLATE_6_FORMS_1_BUTTON'),
    'TYPE' => 'STRING',
    'DEFAULT' => Loc::getMessage('C_MAIN_FORM_TEMPLATE_6_FORMS_1_BUTTON_DEFAULT')
);

$arTemplateParameters['FORMS_2_BUTTON'] = array(
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_MAIN_FORM_TEMPLATE_6_FORMS_2_BUTTON'),
    'TYPE' => 'STRING',
    'DEFAULT' => Loc::getMessage('C_MAIN_FORM_TEMPLATE_6_FORMS_2_BUTTON_DEFAULT')
);

/** VISUAL */
$arTemplateParameters['TITLE'] = array(
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_MAIN_FORM_TEMPLATE_6_TITLE'),
    'TYPE' => 'STRING',
    'DEFAULT' => Loc::getMessage('C_MAIN_FORM_TEMPLATE_6_TITLE_DEFAULT')
);

$arTemplateParameters['DESCRIPTION_SHOW'] = array(
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_MAIN_FORM_TEMPLATE_6_DESCRIPTION_SHOW'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'N',
    'REFRESH' => 'Y'
);

if ($arCurrentValues['DESCRIPTION_SHOW'] == 'Y') {
    $arTemplateParameters['DESCRIPTION'] = array(
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_MAIN_FORM_TEMPLATE_6_DESCRIPTION'),
        'TYPE' => 'STRING'
    );
}