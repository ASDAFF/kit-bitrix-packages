<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)
{
    die();
}
$arResult['SIDE'] = [];
$arResult['MAIN'] = [];
foreach($arResult['ITEMS'] as $item)
{
    if(in_array('SIDE',$item['PROPERTIES']['BANNER_TYPE']['VALUE_XML_ID']))
    {
        $arResult['SIDE'][] = $item;
    }
    if(in_array('MAIN',$item['PROPERTIES']['BANNER_TYPE']['VALUE_XML_ID']))
    {
        $arResult['MAIN'][] = $item;
    }
}
unset($arResult['ITEMS']);

