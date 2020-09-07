<?php
use Kit\Origami\Helper\Config;
$arFilter = array(
    'IBLOCK_ID' => Config::get("IBLOCK_ID_VLOG"),
    "ACTIVE" => "Y"
);
$arVideo = array();
$rsVideo = CIBlockElement::GetList(array(), $arFilter, false);
while ($item = $rsVideo->GetNext()) {
    $arVideo[] = $item;
}
$mTags = array();
foreach ($arVideo as $it) {
    if ($it['TAGS']) {
        foreach (explode(',', $it['TAGS']) as $tag) {
            if (!in_array($tag, $mTags)) {
                $mTags[] = $tag;
            }
        }
    }
}
$arResult['ALL_TAGS'] = $mTags;
$arResult['ALL_VIDEOS'] = array_reverse($arVideo);
