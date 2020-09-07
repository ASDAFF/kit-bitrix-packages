<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

/**
 * @var array $arCurrentValues
 * @var array $arForms
 */

$rsForms = CForm::GetList($by = 'sort', $order = 'asc', [], $filtered = false);

while ($arForm = $rsForms->Fetch())
    $arForms['ID'][$arForm['ID']] = '['.$arForm['ID'].'] '.$arForm['NAME'];

unset($rsForms);

$rsTemplates = CComponentUtil::GetTemplatesList('bitrix:form.result.new');

foreach ($rsTemplates as $arTemplate)
    $arForms['TEMPLATE'][$arTemplate['NAME']] = $arTemplate['NAME'].(!empty($arTemplate['TEMPLATE']) ? ' ('.$arTemplate['TEMPLATE'].')' : null);

if (!empty($arCurrentValues['FORM_ORDER_ID'])) {
    $rsFormFields = CFormField::GetList(
        $arCurrentValues['FORM_ORDER_ID'],
        'N',
        $by = null,
        $asc = null,
        ['ACTIVE' => 'Y'],
        $filtered = false
    );

    $arFields = [];

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
            $arFields[$sId] = '['.$arFormAnswer['ID'].'] '.$arFormField['TITLE'];
        }
    }

    if (!empty($arFields))
        $arForms['FIELDS']['ORDER'] = $arFields;

    unset($rsFormFields, $arFields, $arFormField, $rsFormAnswers, $arFormAnswer, $sId);
}