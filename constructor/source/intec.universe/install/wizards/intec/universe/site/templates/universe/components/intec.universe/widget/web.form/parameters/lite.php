<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

$arTemplateParameters = array();

$arForms = array();
$dbForms = CStartShopForm::GetList();
while ($arForm = $dbForms->Fetch())
    $arForms[$arForm['ID']] = '['.$arForm['ID'].'] '.(!empty($arForm['LANG'][LANGUAGE_ID]['NAME']) ? $arForm['LANG'][LANGUAGE_ID]['NAME'] : $arForm['CODE']);

$rsTemplates = CComponentUtil::GetTemplatesList('intec:startshop.forms.result.new');

$arTemplateParameters['FORM_TEXT'] = array(
    'PARENT' => 'VISUAL',
    'NAME' => GetMessage('W_WEB_FORM_PARAMETERS_WEB_FORM_TEXT'),
    'TYPE' => 'STRING'
);