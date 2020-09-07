<?
foreach ($arResult["ITEMS"] as $key=> $arItem){
    if($arItem["TAGS"]){
        $tags = explode(',',$arItem['TAGS']);
        $arResult["ITEMS"][$key]['SHOW_TAGS'] = $tags;
    }
    foreach ($arResult["ITEMS"][$key]['SHOW_TAGS'] as &$tag){
        $tag = trim($tag);
    }
}
?>