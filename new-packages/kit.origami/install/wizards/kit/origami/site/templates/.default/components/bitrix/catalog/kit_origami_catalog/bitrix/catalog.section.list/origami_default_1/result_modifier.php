<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

if(count($arResult['SECTIONS']) > 0)
{
    $arID = $childCategories = array();

    $obImg = new \Kit\Origami\Image\Item();

    foreach($arResult['SECTIONS'] as &$arSection)
    {
        if($arSection["DEPTH_LEVEL"] == 1)
        {
            $arID[] = $arSection["ID"];

            if($arSection["PICTURE"])
            {
                $arImg = $obImg->resize($arSection["PICTURE"], ['width' => 100, 'height' => 100], BX_RESIZE_IMAGE_PROPORTIONAL);
                $arSection["PICTURE"]["SRC"] = $arImg["SRC"];
                $arSection["PICTURE"]["WIDTH"] = $arImg["WIDTH"];
                $arSection["PICTURE"]["HEIGHT"] = $arImg["HEIGHT"];
            }

            if(!$arSection["PICTURE"])
            {
                $arSection["PICTURE"]["SRC"] = $obImg->getNoImageSmall();
            }
        }
    }

    if($arParams["TOP_DEPTH_CURRENT"] > 1)
    {
        $arSort = array(
            "left_margin"=>"asc",
        );

        $arFilter = array(
            "ACTIVE" => "Y",
            "GLOBAL_ACTIVE" => "Y",
            "IBLOCK_ID" => $arParams["IBLOCK_ID"],
            "CNT_ACTIVE" => "Y",
            "SECTION_ID" => $arID
        );

        $arSelect = array("NAME", "SECTION_PAGE_URL", "IBLOCK_SECTION_ID");

        $rsSections = CIBlockSection::GetList($arSort, $arFilter, ($arParams["COUNT_ELEMENTS_CURRENT"] == "Y"), $arSelect);

        while($arSect = $rsSections->GetNext())
        {
            $childCategories[$arSect["IBLOCK_SECTION_ID"]][$arSect["ID"]] = $arSect;
        }

        foreach($arResult["SECTIONS"] as $key => &$arSection)
        {
            if(isset($childCategories[$arSection["ID"]]))
            {
                $arSection["CHILD_CATEGORIES"] = $childCategories[$arSection["ID"]];
            }
        }
    }
}