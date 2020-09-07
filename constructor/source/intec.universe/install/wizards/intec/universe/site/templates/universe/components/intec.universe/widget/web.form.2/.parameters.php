<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Loader;

/**
 * @var array $arCurrentValues
 */

if (Loader::includeModule('form')) {
    include('parameters/base.php');
} else if (Loader::includeModule('intec.startshop')) {
    include('parameters/lite.php');
} else {
    return;
}


$arTemplateParameters['GRAB_DATA'] = array(
    'PARENT' => 'BASE',
    'NAME' => GetMessage('W_WEB_FORM_2_PARAMETERS_GRAB_DATA'),
    'TYPE' => 'CHECKBOX',
    'REFRESH' => 'Y'
);

if ($arCurrentValues['GRAB_DATA'] != 'Y') {
    $arTemplateParameters['TITLE'] = array(
        'PARENT' => 'BASE',
        'NAME' => GetMessage('W_WEB_FORM_2_PARAMETERS_TITLE'),
        'TYPE' => 'STRING'
    );

    $arTemplateParameters['DESCRIPTION'] = array(
        'PARENT' => 'BASE',
        'NAME' => GetMessage('W_WEB_FORM_2_PARAMETERS_DESCRIPTION'),
        'TYPE' => 'STRING'
    );

    $arTemplateParameters['BUTTON'] = array(
        'PARENT' => 'BASE',
        'NAME' => GetMessage('W_WEB_FORM_2_PARAMETERS_BUTTON'),
        'TYPE' => 'STRING'
    );

    $arTemplateParameters['FORM'] = array(
        'PARENT' => 'BASE',
        'NAME' => GetMessage('W_WEB_FORM_2_PARAMETERS_FORM'),
        'TYPE' => 'STRING'
    );
}

$arTemplateParameters['WEB_FORM_ID'] = array(
    'PARENT' => 'DATA_SOURCE',
    'NAME' => GetMessage('W_WEB_FORM_2_PARAMETERS_WEB_FORM_ID'),
    'TYPE' => 'LIST',
    'VALUES' => $arForms,
    'ADDITIONAL_VALUES' => 'Y'
);

$arTemplates = array();

foreach ($rsTemplates as $arTemplate) {
    $arTemplates[$arTemplate['NAME']] = $arTemplate['NAME'].(!empty($arTemplate['TEMPLATE']) ? ' ('.$arTemplate['TEMPLATE'].')' : null);
}

$arTemplateParameters['WEB_FORM_TEMPLATE'] = array(
    'PARENT' => 'VISUAL',
    'NAME' => GetMessage('W_WEB_FORM_2_PARAMETERS_WEB_FORM_TEMPLATE'),
    'TYPE' => 'LIST',
    'VALUES' => $arTemplates,
    'ADDITIONAL_VALUES' => 'Y'
);

$arTemplateParameters['CONSENT_URL'] = array(
    'PARENT' => 'URL_TEMPLATES',
    'NAME' => GetMessage('W_WEB_FORM_2_PARAMETERS_CONSENT_URL'),
    'TYPE' => 'STRING'
);