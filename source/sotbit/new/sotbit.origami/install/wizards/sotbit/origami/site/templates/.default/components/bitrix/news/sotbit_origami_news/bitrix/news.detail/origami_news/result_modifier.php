<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

$arResult["LINK_PRODUCTS"] = array();

if(isset($arResult["PROPERTIES"]["LINK_PRODUCTS"]["VALUE"]) && !empty($arResult["PROPERTIES"]["LINK_PRODUCTS"]["VALUE"]))
{
    $arResult["LINK_PRODUCTS"] = $arResult["PROPERTIES"]["LINK_PRODUCTS"]["VALUE"];
}

$this->__component->SetResultCacheKeys(array(
    "NAME",
    "PREVIEW_TEXT",
    "DETAIL_PICTURE",
    "LINK_PRODUCTS"
));
?>
