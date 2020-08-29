<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}
use Sotbit\Origami\Helper\Config;

$imgH = 42;
$imgW = 42;

$usePict = Config::get('PICTURE_SIDE_SECTIONS');

$arSectURL = $arSectPict = array();

foreach ($arResult as $key => $arItem) {
    if($arItem["DEPTH_LEVEL"] > $arParams["MAX_LEVEL"])
    {
        unset($arResult[$key]);
        continue;
    }
}

foreach ($arResult as $key => $arItem) {
    if($arItem["DEPTH_LEVEL"] == $arParams["MAX_LEVEL"])
    {
        $arResult[$key]["IS_PARENT"] = 0;
    }

    if($usePict == "Y" && isset($arItem["PARAMS"]["FROM_IBLOCK"]) && $arItem["PARAMS"]["FROM_IBLOCK"])
    {
        $sect_id = crc32($arItem["LINK"]);
        $arSectURL[$sect_id] = $arItem["LINK"];
        $arResult[$key]["SECTION_ID"] = $sect_id;
    }
}

$arResult = array_values($arResult);

$bWasSelected = false;
$arParents = array();
$depth = 1;

foreach($arResult as $i=>$arMenu)
{
    $depth = $arMenu['DEPTH_LEVEL'];

    //if($arMenu['IS_PARENT'])
    {
        $arParents[$arMenu['DEPTH_LEVEL']-1] = $i;
    }
    //else
    if($arMenu['SELECTED'])
    {
        $bWasSelected = true;
        break;
    }
}

if($bWasSelected)
{
    for($i=0; $i<$depth-1; $i++)
        $arResult[$arParents[$i]]['CHILD_SELECTED'] = true;
}

if($usePict == "Y" && !empty($arSectURL))
{
    if(isset($arParams["IBLOCK_ID"]))
        $iblockID = $arParams["IBLOCK_ID"];
    else
        $iblockID = Config::get("IBLOCK_ID");

    $obCache = new CPHPCache();
    if ($obCache->InitCache(36000, serialize(array($arSectURL, $usePict)), "/kit.origami/left_menu"))
    {
        $arSectPict = $obCache->GetVars();
    }elseif ($obCache->StartDataCache())
    {

        $ItemImg = new \Sotbit\Origami\Image\Item();
        $dbSections = CIBlockSection::GetList(array(), array("IBLOCK_ID" => $iblockID, "ACTIVE" => "Y", "GLOBAL_ACTIVE" => "Y"), false, array("ID", "SECTION_PAGE_URL", "PICTURE"));
        while($arSections = $dbSections->GetNext())
        {
            $sect_id = crc32($arSections["SECTION_PAGE_URL"]);
            if($arSections["PICTURE"])
            {
                if ($arSections["PICTURE"])
                {
                    $arResizePicture = $ItemImg->resize($arSections['PICTURE'],['width' => $imgW,'height' => $imgH], $ItemImg->getResizeType());

                    $arSectPict[$sect_id] = $arResizePicture;
                }
            }else{
                $arSectPict[$sect_id]["src"] = $ItemImg->getNoImageSmall();
                $arSectPict[$sect_id]["width"] = $imgW;
                $arSectPict[$sect_id]["height"] = $imgH;
            }
        }

        if(defined("BX_COMP_MANAGED_CACHE"))
        {
            global $CACHE_MANAGER;
            $CACHE_MANAGER->StartTagCache("/kit.origami/left_menu");
            $CACHE_MANAGER->RegisterTag("iblock_id_".$iblockID);
            $CACHE_MANAGER->EndTagCache();
        }

        $obCache->EndDataCache($arSectPict);
    }
}

if(!empty($arSectPict))
{
    foreach ($arResult as $key => $arItem)
    {
        if(isset($arItem["SECTION_ID"]))
        {
            $sect_id = $arItem["SECTION_ID"];
            $arResult[$key]["PICTURE"] = $arSectPict[$sect_id];
        }
    }
}

if ($arResult) {
    $oMenu = new \Sotbit\Origami\Helper\Menu();
    $menu = [];

    foreach ($arResult as $key => $arItem) {
        if ($arItem['DEPTH_LEVEL'] == 1) {
            $children = $oMenu->findMenuChildren($key, $arResult);
            $arItem['CHILDREN'] = $children;
            $menu[] = $arItem;
        }
    }
    $arResult = $menu;
}