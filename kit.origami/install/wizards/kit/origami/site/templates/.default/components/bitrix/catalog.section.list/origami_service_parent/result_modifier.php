<?php
/**
 * Copyright (c) 27/8/2020 Created By/Edited By ASDAFF asdaff.asad@yandex.ru
 */

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

if($arResult["SECTIONS"] && !empty($arResult["SECTIONS"]))
{
    foreach($arResult["SECTIONS"] as &$arSection)
    {
        if(!$arSection["PICTURE"])
        {
            $arSection["PICTURE"]["ALT"] = "";
            $arSection["PICTURE"]["TITLE"] = "";
            $image = new Kit\Origami\Image\Item();
            $arSection["PICTURE"]["SRC"] = $image->getNoImageMedium();
        }
    }
}

if(!$arParams["SECTION_ID"] && $arParams["IBLOCK_ID"])
{
    $dbBlock = \CIBlock::GetList(
        array("SORT"=>"ASC"),
        array("ID" => $arParams["IBLOCK_ID"]),
        false
    );

    while($arBlock = $dbBlock->GetNext())
    {
        if($arBlock["PICTURE"])
        {
            $arResult["SECTION"] = $arBlock;
            $arResult["SECTION"]["DETAIL_PICTURE"] = $arBlock["PICTURE"];
        }
    }
}

if($arResult["SECTION"]["DETAIL_PICTURE"])
{
    \Bitrix\Iblock\Component\Tools::getFieldImageData(
        $arResult["SECTION"],
        array('DETAIL_PICTURE'),
        \Bitrix\Iblock\Component\Tools::IPROPERTY_ENTITY_SECTION,
        'IPROPERTY_VALUES'
    );
}

if($arParams["SECTION_ROOT_TEMPLATE"] == "products")
    $arResult['SECTIONS'] = array();