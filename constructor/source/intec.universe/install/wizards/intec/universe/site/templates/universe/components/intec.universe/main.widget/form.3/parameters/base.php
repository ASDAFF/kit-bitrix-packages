<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

$rsForms = CForm::GetList($by = 'sort', $order = 'asc', array(), $filtered = false);

while ($arForm = $rsForms->Fetch())
    $arForms[$arForm['ID']] = '['. $arForm['ID'] .'] '. $arForm['NAME'];

$rsTemplates = CComponentUtil::GetTemplatesList('bitrix:form.result.new', $siteTemplate);