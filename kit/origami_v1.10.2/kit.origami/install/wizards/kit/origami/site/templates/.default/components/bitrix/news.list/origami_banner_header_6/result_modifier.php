<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)
{
    die();
}

$url = $APPLICATION->GetCurPage();

foreach ($arResult["ITEMS"] as $item=>$arItem) {

    if($arItem["PROPERTIES"]["SHOW_SECTIONS"]["VALUE"]) {
        foreach ($arItem["PROPERTIES"]["SHOW_SECTIONS"]["VALUE"] as $sectionValue) {
            $sectionValue = str_replace(array(
                '*',
                '/'
            ), array(
                '.*',
                "\/"
            ), $sectionValue);

            if ($sectionValue == "\/") {
                $sectionValue = "^\/$";
            }
            $result = preg_match('/' . $sectionValue . '/ui', $url);
            if ($result) {
                $newItems[] = $arItem;
            }
        }
    }
    else {
        $newItems[] = $arItem;
    }
}

$arResult["ITEMS"] = $newItems;

foreach ($arResult["ITEMS"] as &$arItem){
    if(isset($arItem["PROPERTIES"]["IMAGES_WEBP"]["VALUE"])){
        foreach ($arItem["PROPERTIES"]["IMAGES_WEBP"]["VALUE"] as $key=>$imageWebp){
            $arItem["PROPERTIES"]["IMAGES_WEBP"]["VALUE"][$key] = CFile::GetFileArray($imageWebp);
            $originalName[$key] = $arItem["PROPERTIES"]["IMAGES_WEBP"]["VALUE"][$key]["ORIGINAL_NAME"];
        }
    }
    $arItem["PROPERTIES"]["IMAGES_WEBP"]["WEBP_ORIGINAL_NAME"] = $originalName;
}