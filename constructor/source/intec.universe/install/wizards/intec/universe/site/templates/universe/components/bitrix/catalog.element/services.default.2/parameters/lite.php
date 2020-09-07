<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

/**
 * @var array $arCurrentValues
 * @var array $arForms
 * @var array $rsTemplates
 * @var array $arFormFields
 */

$rsForms = CStartShopForm::GetList();

while ($arForm = $rsForms->Fetch())
    $arForms['ID'][$arForm['ID']] = '['.$arForm['ID'].'] '.(!empty($arForm['LANG'][LANGUAGE_ID]['NAME']) ?
            $arForm['LANG'][LANGUAGE_ID]['NAME'] :
            $arForm['CODE']);

unset($rsForms, $arForm);

$rsTemplates = CComponentUtil::GetTemplatesList('intec:startshop.forms.result.new');

foreach ($rsTemplates as $arTemplate)
    $arForms['TEMPLATE'][$arTemplate['NAME']] = $arTemplate['NAME'].(!empty($arTemplate['TEMPLATE']) ? ' ('.$arTemplate['TEMPLATE'].')' : null);

unset($rsTemplates, $arTemplate);

if (!empty($arCurrentValues['FORM_ORDER_ID'])) {
    $rsFormFields = CStartShopFormProperty::GetList([], [
        'FORM' => $arCurrentValues['FORM_ORDER_ID']
    ]);

    $arFields = [];

    while ($arFormField = $rsFormFields->GetNext())
        $arFields[$arFormField['ID']] = '['.$arFormField['ID'].'] '.$arFormField['LANG']['ru']['NAME'];

    $arForms['FIELDS']['ORDER'] = $arFields;

    unset($rsFormFields, $arFields, $arFormField);
}