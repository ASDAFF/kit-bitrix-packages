<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

/**
 * @var array $arCurrentValues
 * @var array $arForms
 * @var array $rsTemplates
 * @var array $arFormFields
 */

$rsForms = CForm::GetList($by = 'sort', $order = 'asc', array(), $filtered = false);

while ($arForm = $rsForms->Fetch())
    $arForms[$arForm['ID']] = '['.$arForm['ID'].'] '.$arForm['NAME'];

unset($rsForms);

$rsFormsTemplates = CComponentUtil::GetTemplatesList('bitrix:form.result.new');