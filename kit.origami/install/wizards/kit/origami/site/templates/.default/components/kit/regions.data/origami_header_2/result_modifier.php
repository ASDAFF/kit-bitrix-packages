<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

if(is_array($arResult['FIELDS']['UF_PHONE']['VALUE'])){
    $arResult['FIELDS']['UF_PHONE']['VALUE'] = array_unique(array_filter($arResult['FIELDS']['UF_PHONE']['VALUE'], function($v, $k) {
        return !empty(trim($v));
    }, ARRAY_FILTER_USE_BOTH));
    $arResult['FIELDS']['UF_PHONE']['VALUE'] = array_diff(
        $arResult['FIELDS']['UF_PHONE']['VALUE'],
        ['', null]
    );
}
if(is_array($arResult['FIELDS']['UF_EMAIL']['VALUE'])){
    $arResult['FIELDS']['UF_EMAIL']['VALUE'] = array_unique(array_filter($arResult['FIELDS']['UF_EMAIL']['VALUE'], function($v, $k) {
        return !empty(trim($v));
    }, ARRAY_FILTER_USE_BOTH));
    $arResult['FIELDS']['UF_EMAIL']['VALUE'] = array_diff(
        $arResult['FIELDS']['UF_EMAIL']['VALUE'],
        ['', null]
    );
}

