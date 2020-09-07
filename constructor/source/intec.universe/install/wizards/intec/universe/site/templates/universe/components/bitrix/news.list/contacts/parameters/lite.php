<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Loader;

if (Loader::IncludeModule('intec.startshop'))
    return;

$dbForms = CStartShopForm::GetList();
while ($arForm = $dbForms->Fetch())
    $arForms[$arForm['ID']] = '['.$arForm['ID'].'] '.(!empty($arForm['LANG'][LANGUAGE_ID]['NAME']) ? $arForm['LANG'][LANGUAGE_ID]['NAME'] : $arForm['CODE']);