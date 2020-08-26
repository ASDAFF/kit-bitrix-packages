<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)
{
    die();
}

if($arResult["ITEMS"])
{
    foreach ($arResult["ITEMS"] as $i => &$arItem)
    {
        if(strlen($arItem["DATE_ACTIVE_TO"])>0)
            $arItem["DISPLAY_ACTIVE_TO"] = CIBlockFormatProperties::DateFormat($arParams["ACTIVE_DATE_FORMAT"], MakeTimeStamp($arItem["DATE_ACTIVE_TO"], CSite::GetDateFormat()));
        else
            $arItem["DISPLAY_ACTIVE_TO"] = "";
    }
}

