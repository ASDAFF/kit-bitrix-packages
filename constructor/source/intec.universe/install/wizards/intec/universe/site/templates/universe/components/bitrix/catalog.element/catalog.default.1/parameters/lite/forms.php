<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

$arForms = array();
$dbForms = CStartShopForm::GetList();

while ($arForm = $dbForms->Fetch())
    $arForms[$arForm['ID']] = '['.$arForm['ID'].'] '.(!empty($arForm['LANG'][LANGUAGE_ID]['NAME']) ? $arForm['LANG'][LANGUAGE_ID]['NAME'] : $arForm['CODE']);

$rsTemplates = CComponentUtil::GetTemplatesList('intec:startshop.forms.result.new');

$arTemplateParameters['WEB_FORM_TEXT'] = array(
    'PARENT' => 'VISUAL',
    'NAME' => GetMessage('C_CATALOG_ELEMENT_CATALOG_DEFAULT_1_WEB_FORM_TEXT'),
    'TYPE' => 'STRING'
);