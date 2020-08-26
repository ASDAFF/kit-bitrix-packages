<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

$maxCategories = 6;
$childCategories = array();

foreach($arResult["SECTIONS"] as $key => $arSection)
{
    if(($arSection["DEPTH_LEVEL"] == 1 && !$arSection["UF_SHOW_ON_MAIN_PAGE"]) || $arSection["DEPTH_LEVEL"] > 2)
    {
        unset($arResult["SECTIONS"][$key]);
    }
    else
    {
        if($arSection["IBLOCK_SECTION_ID"])
        {
            $childCategories[$arSection["IBLOCK_SECTION_ID"]][$arSection["ID"]]["NAME"] = $arSection["NAME"];
            $childCategories[$arSection["IBLOCK_SECTION_ID"]][$arSection["ID"]]["ELEMENT_CNT"] = $arSection["ELEMENT_CNT"];
            $childCategories[$arSection["IBLOCK_SECTION_ID"]][$arSection["ID"]]["SECTION_PAGE_URL"] = $arSection["SECTION_PAGE_URL"];
            $childCategories[$arSection["IBLOCK_SECTION_ID"]][$arSection["ID"]]["IBLOCK_ID"] = $arSection["IBLOCK_ID"];
            $childCategories[$arSection["IBLOCK_SECTION_ID"]][$arSection["ID"]]["EDIT_LINK"] = $arSection["EDIT_LINK"];
            $childCategories[$arSection["IBLOCK_SECTION_ID"]][$arSection["ID"]]["DELETE_LINK"] = $arSection["DELETE_LINK"];
            unset($arResult["SECTIONS"][$key]);
        }
    }
}

if($childCategories)
{
    $keys = array_keys($childCategories);
    foreach($arResult["SECTIONS"] as $key => $arSection)
    {
        if(in_array($arSection["ID"], $keys))
        {
            $arResult["SECTIONS"][$key]["CHILD_CATEGORIES"] = $childCategories[$arSection["ID"]];
        }
    }
}

$arResult["SECTIONS"] = array_slice($arResult["SECTIONS"], 0, $maxCategories);
?>