<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

/**
 * @var array $arCurrentValues
 */

$arForms = array();
$rsForms = CStartShopForm::GetList();

while ($arForm = $rsForms->Fetch())
    $arForms[$arForm['ID']] = '[' . $arForm['ID'] . '] ' . (!empty($arForm['LANG'][LANGUAGE_ID]['NAME']) ? $arForm['LANG'][LANGUAGE_ID]['NAME'] : $arForm['CODE']);

unset($arForm);
unset($rsForms);