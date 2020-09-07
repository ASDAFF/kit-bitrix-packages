<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

/**
 * @var array $arCurrentValues
 * @var array $arForms
 * @var array $rsTemplates
 * @var array $arFormFields
 */

$rsForms = CForm::GetList($by = 'sort', $order = 'asc', [], $filtered = false);

while ($arForm = $rsForms->Fetch())
    $arForms[$arForm['ID']] = '['.$arForm['ID'].'] '.$arForm['NAME'];

unset($rsForms);

$rsTemplates = CComponentUtil::GetTemplatesList('bitrix:form.result.new');

if (!empty($arCurrentValues['BUTTON_FORM_ID'])) {
    $rsFormFields = CFormField::GetList(
        $arCurrentValues['BUTTON_FORM_ID'],
        'N',
        $by = null,
        $asc = null,
        ['ACTIVE' => 'Y'],
        $filtered = false
    );

    while ($arFormField = $rsFormFields->GetNext()) {
        $rsFormAnswers = CFormAnswer::GetList(
            $arFormField['ID'],
            $sort = '',
            $order = '',
            [],
            $filtered = false
        );

        while ($arFormAnswer = $rsFormAnswers->GetNext()) {
            $sType = $arFormAnswer['FIELD_TYPE'];

            if (empty($sType))
                continue;

            $sId = 'form_'.$sType.'_'.$arFormAnswer['ID'];
            $arFormFields[$sId] = '['.$arFormAnswer['ID'].'] '.$arFormField['TITLE'];
        }
    }
}