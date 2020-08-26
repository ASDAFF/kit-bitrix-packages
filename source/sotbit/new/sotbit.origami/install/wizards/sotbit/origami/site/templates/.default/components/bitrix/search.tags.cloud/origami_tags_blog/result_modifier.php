<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

foreach ($arResult["SEARCH"] as $search){
    $arSerch[$search["NAME"]] = $search;
}
$arResult["SEARCH"] = $arSerch;

if($arResult["TAGS_CHAIN"]){
    foreach ($arResult["TAGS_CHAIN"] as $chain){
        $arChain[$chain["TAG_NAME"]] = $chain;
    }
}
$arResult["TAGS_CHAIN"] = $arChain;
?>