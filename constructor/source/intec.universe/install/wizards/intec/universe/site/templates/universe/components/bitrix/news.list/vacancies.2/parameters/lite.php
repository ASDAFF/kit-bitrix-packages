<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

if ($arCurrentValues['FORM_SUMMARY_SHOW'] == 'Y') {
    /** Forms list */
    $rsForms = CStartShopForm::GetList();

    while ($arForm = $rsForms->GetNext()) {
        $arForms[$arForm['ID']] = '[' . $arForm['ID'] . '] ' . $arForm['LANG'][LANGUAGE_ID]['NAME'];
    }

    /** Form fields */
    if (!empty($arCurrentValues['FORM_SUMMARY_ID'])) {
        $rsFormFields = CStartShopFormProperty::GetList(array(), array('FORM' => $arCurrentValues['FORM_SUMMARY_ID']));

        while ($arFormField = $rsFormFields->GetNext()) {
            $arFormFields[$arFormField['ID']] = '['.$arFormField['ID'].'] '.$arFormField['LANG'][LANGUAGE_ID]['NAME'];
        }
    }
}