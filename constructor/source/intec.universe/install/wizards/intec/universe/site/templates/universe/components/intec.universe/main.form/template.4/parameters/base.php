<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

/**
 * @var array $arCurrentValues
 */

$arForms = [];
$rsForms = CForm::GetList($by = 'sort', $order = 'asc', [], $filtered = false);

while ($arForm = $rsForms->Fetch())
    $arForms[$arForm['ID']] = '[' . $arForm['ID'] . '] ' . $arForm['NAME'];

unset($arForm);
unset($rsForms);