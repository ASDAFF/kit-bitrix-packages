<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}
\Bitrix\Main\Loader::includeModule('kit.origami');

$tmp = array();
$tmpD = array();
$tmpPhoto = array();

if($arResult['ITEMS'])
{
    foreach($arResult['ITEMS'] as $j => $item)
    {
        $tmp[$item['ID']] = $item['PREVIEW_PICTURE'];
        $tmpD[$item['ID']] = $item['DETAIL_PICTURE'];

        if(isset($item["DISPLAY_PROPERTIES"]["MORE_PHOTO"]))
        {
            if(isset($item["DISPLAY_PROPERTIES"]["MORE_PHOTO"]["FILE_VALUE"]["ID"]))
                $tmpPhoto[$item['ID']][] = $item["DISPLAY_PROPERTIES"]["MORE_PHOTO"]["FILE_VALUE"];
            else $tmpPhoto[$item['ID']] = $item["DISPLAY_PROPERTIES"]["MORE_PHOTO"]["FILE_VALUE"];
        }

        if($item['OFFERS'])
        {
            foreach($item['OFFERS'] as $offer)
            {

                $tmp[$offer['ID']] = $offer['PREVIEW_PICTURE'];
                $tmpD[$offer['ID']] = $offer['DETAIL_PICTURE'];
            }
        }
    }
}

$component = $this->getComponent();
$arParams = $component->applyTemplateModifications();

if($arResult['ITEMS'])
{

    foreach ($arResult['ITEMS'] as $j => $item)
    {

        $arResult['ITEMS'][$j]['PREVIEW_PICTURE'] = $tmp[$item['ID']];
        $arResult['ITEMS'][$j]['DETAIL_PICTURE'] = $tmpD[$item['ID']];
        $arResult['ITEMS'][$j]['MORE_PHOTO'] = $tmpPhoto[$item['ID']];

        if ($item['JS_OFFERS'])
        {
            foreach ($item['JS_OFFERS'] as $i => $offer)
            {
                $arResult['ITEMS'][$j]['JS_OFFERS'][$i]['PREVIEW_PICTURE']
                    = $tmp[$offer['ID']];
                $arResult['ITEMS'][$j]['JS_OFFERS'][$i]['DETAIL_PICTURE']
                    = $tmpD[$offer['ID']];
            }
        }
    }
}

$arResult = \SotbitOrigami::getAllPrices($arResult);
