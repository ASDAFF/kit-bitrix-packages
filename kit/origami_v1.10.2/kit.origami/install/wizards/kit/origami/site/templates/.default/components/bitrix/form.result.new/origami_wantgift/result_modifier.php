<?php

use Bitrix\Main\Localization\Loc;

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

global $USER;
$userName = $USER->GetFullName();

try {
    $arResult["IMG_PRODUCT"]["SRC"] = $_REQUEST["img"];
    $arResult["IMG_PRODUCT"]["NAME"] = $_REQUEST["name"];
    $arResult["IMG_PRODUCT"]["PRICE"] = $_REQUEST["price"];
    $arResult["IMG_PRODUCT"]["OLD_PRICE"] = $_REQUEST["oldPrice"];

    if ($arResult["QUESTIONS"]) {
        foreach ($arResult["QUESTIONS"] as &$question) {
            if(mb_stripos($question['CAPTION'], Loc::getMessage('OK_NAME')) !== false) {
                $question['STRUCTURE'][0]['VALUE'] = $userName;
            }
        }
    }

} catch (\Bitrix\Main\LoaderException $e) {
}

if(!empty($arResult['FORM_NOTE'])) {
    $arResult['FORM_NOTE'] = Loc::getMessage("FORM_ADD_SUCCESS_MESSAGE");
}
?>
