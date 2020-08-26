<?php

if(count($arResult['SECTIONS']) > 0)
{
    $arID = $childCategories = $arID_2 = array();

    $obImg = new \Sotbit\Origami\Image\Item();

    foreach($arResult['SECTIONS'] as &$arSection)
    {
        if($arSection["DEPTH_LEVEL"] == 1)
        {
            $arID[] = $arSection["ID"];

            if($arSection["PICTURE"])
            {
                $arImg = $obImg->resize($arSection["PICTURE"], ['width' => 220, 'height' => 220], BX_RESIZE_IMAGE_PROPORTIONAL);
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

        $arSelect = array("NAME", "ID", "SECTION_PAGE_URL", "IBLOCK_SECTION_ID", "PICTURE");

        $rsSections = CIBlockSection::GetList($arSort, $arFilter, false, $arSelect);

        while($arSect = $rsSections->GetNext())
        {
            if($arSect["PICTURE"])
            {
                \Bitrix\Iblock\Component\Tools::getFieldImageData(
                    $arSect,
                    array('PICTURE'),
                    \Bitrix\Iblock\Component\Tools::IPROPERTY_ENTITY_SECTION,
                    'IPROPERTY_VALUES'
                );

                $arImg = $obImg->resize($arSect["PICTURE"], ['width' => 30, 'height' => 30], BX_RESIZE_IMAGE_PROPORTIONAL);
                $arSect["PICTURE"]["SRC"] = $arImg["SRC"];
                $arSect["PICTURE"]["WIDTH"] = $arImg["WIDTH"];
                $arSect["PICTURE"]["HEIGHT"] = $arImg["HEIGHT"];

            }

            if(!$arSect["PICTURE"])
            {
                $arSect["PICTURE"]["SRC"] = $obImg->getNoImageSmall();
            }

            $arID_2[] = $arSect["ID"];

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

    if($arParams["TOP_DEPTH_CURRENT"] > 2 && !empty($arID_2))
    {
        $arFilter = array(
            "ACTIVE" => "Y",
            "GLOBAL_ACTIVE" => "Y",
            "IBLOCK_ID" => $arParams["IBLOCK_ID"],
            "CNT_ACTIVE" => "Y",
            "SECTION_ID" => $arID_2
        );

        $arSelect = array("NAME", "ID", "SECTION_PAGE_URL", "IBLOCK_SECTION_ID");

        $rsSections = CIBlockSection::GetList($arSort, $arFilter, false, $arSelect);

        while($arSect = $rsSections->GetNext())
        {
            $childCategories[$arSect["IBLOCK_SECTION_ID"]][$arSect["ID"]] = $arSect;
        }

        foreach($arResult["SECTIONS"] as $key => &$arSection)
        {
            if(isset($arSection["CHILD_CATEGORIES"]))
            {
                foreach($arSection["CHILD_CATEGORIES"] as &$arChild)
                {
                    $arChild["CHILD_CATEGORIES"] = $childCategories[$arChild["ID"]];
                }
            }
        }
    }
}