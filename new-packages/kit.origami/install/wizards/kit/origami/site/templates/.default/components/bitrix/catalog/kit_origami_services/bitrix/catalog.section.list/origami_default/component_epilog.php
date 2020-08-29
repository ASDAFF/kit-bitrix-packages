<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}
global $kitSeoMetaBottomDesc;
global $kitSeoMetaTopDesc;
global $kitSeoMetaAddDesc;
global $kitSeoMetaFile;
global $issetCondition;
global ${$arParams["FILTER_NAME"]};
global $origamiSectionDescription;
global $origamiSectionDescriptionBottom;

$arTmpfilter = ${$arParams["FILTER_NAME"]};
if(isset($arTmpfilter["PROPERTY_REGIONS"]))
    unset($arTmpfilter["PROPERTY_REGIONS"]);

if($arResult['SECTION']['~DESCRIPTION'])
    $origamiSectionDescription = $arResult['SECTION']['~DESCRIPTION'];

if($arResult['SECTION']['~UF_DESCR_BOTTOM'])
    $origamiSectionDescriptionBottom = $arResult['SECTION']['~UF_DESCR_BOTTOM'];

if($arParams['SECTION_DESCRIPTION'] == "ABOVE" || $arParams['SECTION_DESCRIPTION'] == "BOTH")
{
    if($arParams['SEO_DESCRIPTION'] == "NOT_HIDE" || ($arParams['SEO_DESCRIPTION'] == "HIDE_IF_RULE_EXIST" && !$issetCondition) || ($arParams['SEO_DESCRIPTION'] == "ANY_FILTERED_PAGE" && empty($arTmpfilter)))
    {
        if($arParams['SECTION_DESCRIPTION_TOP'] == 'SECTION_DESC')
            echo '<div class ="catalog_content__category_comment fonts__main_comment">' . $arResult['SECTION']['~DESCRIPTION'] . '</div>';
        elseif ($arParams['SECTION_DESCRIPTION_TOP'] == 'UF_DESCR_BOTTOM')
            echo '<div class ="catalog_content__category_comment fonts__main_comment">' . $arResult['SECTION']['~UF_DESCR_BOTTOM'] . '</div>';
    }
}
if(!empty($kitSeoMetaTopDesc))
{
    echo '<div class ="catalog_content__category_comment fonts__main_comment">' . $kitSeoMetaTopDesc . '</div>';
}
?>
