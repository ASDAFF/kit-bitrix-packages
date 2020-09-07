<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

$arFormsTemp = array();

$arForms = array();
$rsForms = CForm::GetList($by = 'sort', $order = 'asc', array(), $filtered = false);

while ($arForm = $rsForms->Fetch()) {
    $arForms[$arForm['ID']] = '[' . $arForm['ID'] . '] ' . $arForm['NAME'];
    $arFormsTemp[$arForm['ID']] = $arForm['NAME'];
}

$rsTemplates = CComponentUtil::GetTemplatesList('bitrix:form.result.new');

if (!empty($arCurrentValues['FORM1_ID'])) {
    $sFormOneName = $arFormsTemp[$arCurrentValues['FORM1_ID']];
}

if (!empty($arCurrentValues['FORM2_ID'])) {
    $sFormTwoName = $arFormsTemp[$arCurrentValues['FORM2_ID']];
}