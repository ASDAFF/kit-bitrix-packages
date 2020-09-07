<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}
$arResult['BACKGROUND'] = [];
if($arResult['PROPERTIES']['BACKGROUND']['VALUE'])
{
    $arResult['BACKGROUND'] = CFile::GetFileArray($arResult['PROPERTIES']['BACKGROUND']['VALUE']);
    $arResult['BACKGROUND']['SRC'] = CFile::GetFileSRC($arResult['BACKGROUND']);
}
else
{
    $arResult['BACKGROUND']['SRC'] = SITE_TEMPLATE_PATH.'/assets/img/empty_h.jpg';
    $size = getimagesize($_SERVER['DOCUMENT_ROOT'].$arResult['BACKGROUND']['SRC']);
    $arResult['BACKGROUND']['HEIGHT'] = $size[1];
}
$arResult['ACTIVE_IMG'] = [];
if($arResult['PROPERTIES']['ACTIVE_IMG']['VALUE'])
{
    $arResult['ACTIVE_IMG'] = CFile::GetFileArray($arResult['PROPERTIES']['ACTIVE_IMG']['VALUE']);
    $arResult['ACTIVE_IMG']['SRC'] = CFile::GetFileSRC($arResult['ACTIVE_IMG']);
}
if($arResult['DATE_ACTIVE_FROM'])
{
    $arResult['DATE_ACTIVE_TO_DISPLAY'] =
        strtolower(\CIBlockFormatProperties::DateFormat
("j F Y", MakeTimeStamp($arResult['DATE_ACTIVE_TO'], CSite::GetDateFormat())));
}


$this->__component->SetResultCacheKeys(array(
    "NAME",
    "PREVIEW_TEXT",
    "DETAIL_PICTURE",
    "PROPERTIES"
));