<?php
$arResult['TAGS'] = [];
if ($arResult['FIELDS']['TAGS']) {
    $arResult['TAGS'] = explode(',', $arResult['FIELDS']['TAGS']);
}

$arResult['PHOTOS'] = [];
$arResult["LINK_PRODUCTS"] = array();

if ($arResult['PROPERTIES']) {
    foreach ($arResult['PROPERTIES'] as $code => $prop) {
        if ($prop['PROPERTY_TYPE'] == 'F' && $prop['MULTIPLE'] == 'Y'
            && $prop['VALUE']
        ) {
            foreach ($prop['VALUE'] as $id) {
                $arResult['PHOTOS'][] = \CFile::ResizeImageGet(
                    $id,
                    [
                        'width'  => 270,
                        'height' => 200,
                    ]
                );
            }
            break;
        }
    }

    if(isset($arResult["PROPERTIES"]["LINK_PRODUCTS"]["VALUE"]) && !empty($arResult["PROPERTIES"]["LINK_PRODUCTS"]["VALUE"]))
    {
        $arResult["LINK_PRODUCTS"] = $arResult["PROPERTIES"]["LINK_PRODUCTS"]["VALUE"];
    }
}

if(is_array($arResult['TAGS'])){
    foreach ($arResult['TAGS'] as $key=> $tag){
        $arResult['TAGS'][$key] = trim($tag);
    }
}

$this->__component->SetResultCacheKeys(array(
    "NAME",
    "PREVIEW_TEXT",
    "DETAIL_PICTURE",
    "LINK_PRODUCTS"
));