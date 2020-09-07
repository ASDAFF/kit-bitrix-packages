<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

/**
 * @var array $arCurrentValues
 * @var array $arForms
 * @var array $rsTemplates
 * @var array $arFormFields
 */


$rsForms = CStartShopForm::GetList();

while ($arForm = $rsForms->Fetch())
    $arForms[$arForm['ID']] = '['.$arForm['ID'].'] '.(!empty($arForm['LANG'][LANGUAGE_ID]['NAME']) ? $arForm['LANG'][LANGUAGE_ID]['NAME'] : $arForm['CODE']);

unset($rsForms);

$rsFormsTemplates = CComponentUtil::GetTemplatesList('intec:startshop.forms.result.new');
