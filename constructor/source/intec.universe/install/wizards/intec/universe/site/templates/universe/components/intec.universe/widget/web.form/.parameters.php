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

$arTemplateParameters['WEB_FORM_ID'] = array(
    'PARENT' => 'DATA_SOURCE',
    'NAME' => GetMessage('W_WEB_FORM_PARAMETERS_WEB_FORM_ID'),
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
    'NAME' => GetMessage('W_WEB_FORM_PARAMETERS_WEB_FORM_TEMPLATE'),
    'TYPE' => 'LIST',
    'VALUES' => $arTemplates,
    'ADDITIONAL_VALUES' => 'Y'
);

$arTemplateParameters['CONSENT_URL'] = array(
    'PARENT' => 'URL_TEMPLATES',
    'NAME' => GetMessage('W_WEB_FORM_PARAMETERS_CONSENT_URL'),
    'TYPE' => 'STRING'
);