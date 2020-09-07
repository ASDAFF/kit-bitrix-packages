<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
use Bitrix\Main\Localization\Loc;


if ($arResult) {
    $oMenu = new \Kit\Origami\Helper\Menu();
    $menu = [];
    $arSectURL = $arSectPict = array();
    $imgW = $imgH = 60;

    if(isset($arParams["IBLOCK_ID"]))
        $iblockID = $arParams["IBLOCK_ID"];
    else
        $iblockID = \Kit\Origami\Config\Option::get('IBLOCK_ID');

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

        if($arItem["DEPTH_LEVEL"] == 1 && isset($arItem["PARAMS"]["FROM_IBLOCK"]) && $arItem["PARAMS"]["FROM_IBLOCK"])
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


    foreach ($arResult as $key => $arItem) {
        if ($arItem['DEPTH_LEVEL'] == 1) {
            $children = $oMenu->findMenuChildren($key, $arResult);
            $arItem['CHILDREN'] = $children;
            $menu[] = $arItem;
        }
    }

    $arResult = $menu;

    if(!empty($arSectURL))
        $arResult = $oMenu->getMenuRootCatalog($arResult, $bWasSelected, $iblockID);


    //printr($arResult);
}
?>
