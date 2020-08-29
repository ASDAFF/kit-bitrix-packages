<?php
use Kit\Origami\Helper\Config;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

$tmp = [];
$tmpD = [];
if($arResult['ITEMS']){
    foreach($arResult['ITEMS'] as $j => $item){
        $tmp[$item['ID']] = $item['PREVIEW_PICTURE'];
        $tmpD[$item['ID']] = $item['DETAIL_PICTURE'];
        if($item['OFFERS']){
            foreach($item['OFFERS'] as $offer){
                $tmp[$offer['ID']] = $offer['PREVIEW_PICTURE'];
                $tmpD[$offer['ID']] = $offer['DETAIL_PICTURE'];
            }
        }
    }
}

$component = $this->getComponent();
$arParams = $component->applyTemplateModifications();

if($arResult['ITEMS']) {
    foreach ($arResult['ITEMS'] as $j => $item) {
        $arResult['ITEMS'][$j]['PREVIEW_PICTURE'] = $tmp[$item['ID']];
        $arResult['ITEMS'][$j]['DETAIL_PICTURE'] = $tmpD[$item['ID']];
        if ($item['JS_OFFERS']) {
            foreach ($item['JS_OFFERS'] as $i => $offer) {
                $arResult['ITEMS'][$j]['JS_OFFERS'][$i]['PREVIEW_PICTURE']
                    = $tmp[$offer['ID']];
                $arResult['ITEMS'][$j]['JS_OFFERS'][$i]['DETAIL_PICTURE']
                    = $tmpD[$offer['ID']];
            }
        }
    }
}

$arResult = \KitOrigami::getAllPrices($arResult);

$arResult['TABS'] = [];
$arResult['TABS_NAMES'] = [];
global ${$arParams['FILTER_NAME']};
$filter = ${$arParams['FILTER_NAME']};
if($filter[0] && $arResult['ITEMS'])
{
    $promotions = [];
    foreach ($filter[0] as $k => $v)
    {
        if(is_array($v) && isset($v['ID']))
        {
            $promotions = $v['ID'];
            break;
        }
    }

    $labelProps = unserialize(Config::get('LABEL_PROPS_MAIN'));
    if(!is_array($labelProps)){
        $labelProps = [];
    }

    foreach($arResult['ITEMS'] as $item)
    {
        foreach ($labelProps as $label){
            if($item['PROPERTIES'][$label]['VALUE'])
            {
                $arResult['TABS_NAMES'][$label] = $item['PROPERTIES'][$label]['NAME'];
                $arResult['TABS'][$label][] = $item['ID'];
            }
        }
        if(in_array($item['ID'],$promotions))
        {
            $arResult['TABS']['PROMOTION'][] = $item['ID'];
        }
    }
}