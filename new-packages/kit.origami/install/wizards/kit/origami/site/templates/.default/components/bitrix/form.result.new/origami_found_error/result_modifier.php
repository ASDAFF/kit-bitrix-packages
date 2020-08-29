<?php

use Bitrix\Main\Localization\Loc;

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}
try {
    \Bitrix\Main\Loader::includeModule('kit.origami');
    $Phone = new \Sotbit\Origami\Helper\Phone();
    $Phone->setMask(\Sotbit\Origami\Config\Option::get(
        'MASK',
        $arParams['SITE_ID']
    ));
    if ($arResult["QUESTIONS"]) {
        foreach ($arResult["QUESTIONS"] as &$question) {
            if (mb_stripos($question['CAPTION'] , Loc::getMessage('OK_PHONE')) !== false) {
                $question['STRUCTURE'][0]['FIELD_TYPE'] = 'tel';
                $question['STRUCTURE'][0]['PATTERN'] = $Phone->genHtmlMask($Phone->getMask());
                $question['STRUCTURE'][0]['MASK'] = $Phone->getMask();
                $question['HTML_CODE'] = $Phone->changeHtmlField($question['HTML_CODE']);
            }
        }
    }

} catch (\Bitrix\Main\LoaderException $e) {
}

if(!empty($arResult['FORM_NOTE'])) {
    $arResult['FORM_NOTE'] = Loc::getMessage("FORM_ADD_SUCCESS_MESSAGE");
}
?>
