<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

$arResult['BIG_CANVAS'] = [];
foreach ($arResult['ITEMS'] as $item) {
    if (in_array(
        'BIG_CANVAS',
        $item['PROPERTIES']['BANNER_TYPE']['VALUE_XML_ID']
    )
    ) {
        $arResult['BIG_CANVAS'][] = $item;
    }

}
unset($arResult['ITEMS']);
