<?php

use Bitrix\Main\Localization\Loc;

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

global $USER;
$userName = $USER->GetFullName();
$userEmail = $USER->GetEmail();

try {
    \Bitrix\Main\Loader::includeModule('kit.origami');
    $Phone = new \Sotbit\Origami\Helper\Phone();
    $Phone->setMask(\Sotbit\Origami\Config\Option::get(
        'MASK',
        $arParams['SITE_ID']
    ));

    if ($arResult["QUESTIONS"]) {
        foreach ($arResult["QUESTIONS"] as &$question) {
            if(mb_stripos($question['CAPTION'], Loc::getMessage('OK_PHONE')) !== false) {
                    $question['STRUCTURE'][0]['FIELD_TYPE'] = 'tel';
                    $question['STRUCTURE'][0]['PATTERN']=$Phone->genHtmlMask($Phone->getMask());
                    $question['STRUCTURE'][0]['MASK']=$Phone->getMask();
                    $question['HTML_CODE'] = $Phone->changeHtmlField($question['HTML_CODE']);
            } elseif ($question['CAPTION'] == Loc::getMessage('OK_NAME_PRODUCT')) {
                    $question['STRUCTURE'][0]['VALUE'] = $_REQUEST["name"];
            } elseif ($question['CAPTION'] == Loc::getMessage('OK_ID_PRODUCT')) {
                    $question['STRUCTURE'][0]['VALUE'] = $_REQUEST["id"];
            } elseif (mb_stripos($question['CAPTION'], Loc::getMessage('OK_NAME')) !== false) {
                    $question['STRUCTURE'][0]['VALUE'] = $userName;
            } elseif ($question['CAPTION'] == Loc::getMessage('OK_EMAIL')) {
                    $question['STRUCTURE'][0]['VALUE'] = $userEmail;
            }
        }
    }

} catch (\Bitrix\Main\LoaderException $e) {
}
?>
