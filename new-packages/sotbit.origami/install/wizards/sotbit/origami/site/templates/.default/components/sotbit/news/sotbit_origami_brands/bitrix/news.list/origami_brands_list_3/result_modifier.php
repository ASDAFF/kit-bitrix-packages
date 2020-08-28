<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
foreach ($arResult['ITEMS'] as &$item) {
    $rsProps = CIBlockElement::GetProperty(
        $arResult['ID'],
        $item['ID'],
        array("sort"=>"asc"),
        array('CODE' => 'BRAND_SITE')
        );
    while ($it = $rsProps->Fetch()) {
        $item['BRAND_SITE'] = $it['VALUE'];
    }
}

