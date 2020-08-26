<?php
$arResult['TAGS'] = [];
if ($arResult['FIELDS']['TAGS']) {
    $arResult['TAGS'] = explode(',', $arResult['FIELDS']['TAGS']);
}

$arResult['PHOTOS'] = [];

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
}

$this->__component->SetResultCacheKeys(array(
    "NAME",
    "PREVIEW_TEXT",
    "DETAIL_PICTURE"
));