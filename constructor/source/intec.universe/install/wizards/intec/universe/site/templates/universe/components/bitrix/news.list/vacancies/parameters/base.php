<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

if ($arCurrentValues['FORM_SUMMARY_DISPLAY'] == 'Y') {

    $rsForms = CForm::GetList($by = 'soty',$order = 'asc', array(), $isFiltered = false);

    while ($arForm = $rsForms->GetNext()) {
        $arForms[$arForm['ID']] = '['.$arForm['ID'].'] '.$arForm['NAME'];
    }

    if (!empty($arCurrentValues['FORM_SUMMARY_ID'])) {
        $rsFormFields = CFormField::GetList(
            $arCurrentValues['FORM_SUMMARY_ID'],
            'N',
            $by = 'sort',
            $order = 'asc',
            array(
                'ACTIVE' => 'Y'
            ),
            $isFiltered = false
        );

        while ($arFormField = $rsFormFields->GetNext()) {
            $sType = $arFormField['FIELD_TYPE'];

            if (empty($sType))
                continue;

            $sId = 'form_' . $sType . '_' . $arFormField['ID'];
            $arFormFields[$sId] = '[' . $arFormField['ID'] . '] ' . $arFormField['TITLE'];
        }
    }
}