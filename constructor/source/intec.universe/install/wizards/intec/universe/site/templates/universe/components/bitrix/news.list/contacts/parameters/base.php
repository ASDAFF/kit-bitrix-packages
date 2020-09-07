<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Loader;

if (Loader::IncludeModule('form')) {

    $rsForms = CForm::GetList(
        $by = "s_sort",
        $order = "asc",
        [],
        $filtered = false
    );

    while ($arForm = $rsForms->Fetch())
        $arForms[$arForm['ID']] = '['.$arForm['ID'].'] '.$arForm['NAME'];
}