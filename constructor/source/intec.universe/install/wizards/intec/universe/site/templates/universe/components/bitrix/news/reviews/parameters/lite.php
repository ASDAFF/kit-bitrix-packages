<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

/**
 * @var array $arCurrentValues
 * @var array $arForms
 * @var array $rsTemplates
 * @var array $arFormFields
 */


$rsForms = CStartShopForm::GetList();

while ($arForm = $rsForms->Fetch())
    $arForms[$arForm['ID']] = '[' . $arForm['ID'] . '] ' . (!empty($arForm['LANG'][LANGUAGE_ID]['NAME']) ? $arForm['LANG'][LANGUAGE_ID]['NAME'] : $arForm['CODE']);

unset($rsForms);

$rsTemplates = CComponentUtil::GetTemplatesList('intec:startshop.forms.result.new');

if (!empty($arCurrentValues['ORDER_FORM_ID'])) {
    $rsFormFields = CStartShopFormProperty::GetList([], ['FORM' => $arCurrentValues['ORDER_FORM_ID']]);

    while ($arFormField = $rsFormFields->GetNext())
        $arFormFields[$arFormField['ID']] = '['.$arFormField['ID'].'] '.$arFormField['LANG']['ru']['NAME'];
}
