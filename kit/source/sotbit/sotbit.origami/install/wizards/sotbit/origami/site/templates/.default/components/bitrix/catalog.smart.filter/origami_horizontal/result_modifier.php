<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

use Sotbit\Origami\Helper\Config;
use \Sotbit\Origami\Helper\Filter;

if($arResult["ITEMS"])
{
    $arProps = $arLabelProps = $arLabelPropsValues = array();
    $arProps = Config::getArray("LABEL_PROPS");
    $keyCur = 0;
    // Label Props
    if(!empty($arProps))
    {
        foreach($arResult["ITEMS"] as $key => &$arItem)
        {
            $code = $arItem["CODE"];
            if(in_array($code, $arProps))
            {
                if(!empty($arItem["VALUES"]))
                {
                    foreach($arItem["VALUES"] as $k => $arVal)
                    {
                        $arVal["VALUE"] = $arItem["NAME"];
                        $arVal["CODE"] = $arItem["CODE"];
                        $arLabelPropsValues[$k] = $arVal;
                    }

                    if($keyCur == 0)
                    {
                        $arItem["NAME"] = $arItem["~NAME"] = GetMessage('CT_BCSF_LABEL_PROPS');
                        $keyCur = $key;
                    }else{
                        unset($arResult["ITEMS"][$key]);
                    }
                }
            }
        }

        if($keyCur > 0)
        {
            $arResult["ITEMS"][$keyCur]["VALUES"] = $arLabelPropsValues;
        }
    }
}

$seoModule = false;
// get SEO link or not
$linkMode = Config::get('SEO_LINK_MODE');
$seoModule = \CModule::IncludeModule("sotbit.seometa");

// get current page
if(isset($arResult["JS_FILTER_PARAMS"]["SEF_DEL_FILTER_URL"]))
{
    $curSec = $arResult["JS_FILTER_PARAMS"]["SEF_DEL_FILTER_URL"];
}

$arParams["FILTER_VIEW_MODE"] = (isset($arParams["FILTER_VIEW_MODE"])
    && toUpper($arParams["FILTER_VIEW_MODE"]) == "HORIZONTAL") ? "HORIZONTAL"
    : "VERTICAL";
$arParams["POPUP_POSITION"] = (isset($arParams["POPUP_POSITION"])
    && in_array($arParams["POPUP_POSITION"], ["left", "right"]))
    ? $arParams["POPUP_POSITION"] : "left";

if($linkMode != 'DISABLED') {
    $arResult['ISSET_SEO_RULE'] = false;
    $arResult['SEO_LINK_MODE'] = $linkMode;
    $tmpFilterCode = Filter::getFilterOutUrl($arResult['FILTER_URL']);
    foreach($arResult["ITEMS"] as $key => &$arItem)
    {
        foreach($arItem["VALUES"] as $i => &$ar)
        {
            if( ($arItem['PROPERTY_TYPE'] == 'L' || $arItem['PROPERTY_TYPE'] == 'S') && $ar['CHECKED'] !== true && $ar['DISABLED'] !== true)
            {
                if(isset($ar['CODE']))
                    $arItem['CODE'] = $ar['CODE'];

                $url = Filter::createUrl($curSec, $ar['URL_ID'], $arItem, $tmpFilterCode, $linkMode);

                if($seoModule && $linkMode == 'SEOMETA_MODE')
                {
                    if(!$seoUrl = \Sotbit\Seometa\SeometaUrlTable::getByNewUrl($curSec))
                        $seoUrl = \Sotbit\Seometa\SeometaUrlTable::getByRealUrl($url);

                    if(empty($arResult['ISSET_SEO_RULE']) && !empty($seoUrl))
                        $arResult['ISSET_SEO_RULE'] = true;

                    if($seoUrl['NEW_URL'] == $curSec || $seoUrl['NEW_URL'] == $_SERVER['REDIRECT_URL'])
                    {
                        $url = "";
                        unset($seoUrl);
                    }
                }

                $ar['section_filter_link'] = (!empty($seoUrl['NEW_URL']) ? $seoUrl['NEW_URL'] : $url);
                $ar['SEO_LINK'] = true;
            }
        }
    }
}

global $sotbitFilterResult;
$sotbitFilterResult = $arResult;
