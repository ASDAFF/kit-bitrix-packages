<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

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
}

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
?>
