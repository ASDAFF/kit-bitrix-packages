<?php

use Bitrix\Main\Localization\Loc;

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

global $USER;
$userName = $USER->GetFullName();
$userEmail = $USER->GetEmail();

try {
    \Bitrix\Main\Loader::includeModule('sotbit.origami');
    $Phone = new \Sotbit\Origami\Helper\Phone();
    $Phone->setMask(\Sotbit\Origami\Config\Option::get(
        'MASK',
        $arParams['SITE_ID']
    ));

    if ($arResult["QUESTIONS"]) {
        foreach ($arResult["QUESTIONS"] as &$question) {
            switch ($question['CAPTION']) {
                case Loc::getMessage('OK_PHONE'):
                    $question['STRUCTURE'][0]['FIELD_TYPE'] = 'tel';
                    $question['STRUCTURE'][0]['PATTERN']=$Phone->genHtmlMask($Phone->getMask());
                    $question['STRUCTURE'][0]['MASK']=$Phone->getMask();
                    $question['HTML_CODE'] = $Phone->changeHtmlField($question['HTML_CODE']);
                    break;
                case Loc::getMessage('OK_NAME_PRODUCT'):
                    $question['STRUCTURE'][0]['VALUE'] = $_REQUEST["name"];
                    break;
                case Loc::getMessage('OK_ID_PRODUCT'):
                    $question['STRUCTURE'][0]['VALUE'] = $_REQUEST["id"];
                    break;
                case Loc::getMessage('OK_NAME'):
                    $question['STRUCTURE'][0]['VALUE'] = $userName;
                    break;
                case Loc::getMessage('OK_EMAIL'):
                    $question['STRUCTURE'][0]['VALUE'] = $userEmail;
                    break;
            }
        }
        unset($question);
    }

} catch (\Bitrix\Main\LoaderException $e) {
}

if(!empty($arResult['FORM_NOTE'])) {
    $arResult['FORM_NOTE'] = Loc::getMessage("FORM_ADD_SUCCESS_MESSAGE");
}
?>
