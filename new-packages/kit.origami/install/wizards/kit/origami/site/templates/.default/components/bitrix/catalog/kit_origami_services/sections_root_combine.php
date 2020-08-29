<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}
use Bitrix\Main\Loader;
use Sotbit\Origami\Helper\Config;

global $kitSeoMetaBottomDesc;
global $kitSeoMetaTopDesc;
global $kitSeoMetaAddDesc;
global $kitSeoMetaFile;
global $kitSeoMetaH1;
global $kitSeoMetaTitle;
global $kitSeoMetaKeywords;
global $kitSeoMetaDescription;

$labelProps = unserialize(Config::get('LABEL_PROPS'));
if(!is_array($labelProps)){
    $labelProps = [];
}
$arParams['LABEL_PROPS'] = $labelProps;

global ${$arParams['FILTER_NAME']};
$useRegion = (Config::get('USE_REGIONS') == 'Y') ? true : false;
if ($useRegion && $_SESSION['SOTBIT_REGIONS']['ID']) {
    ${$arParams['FILTER_NAME']}['PROPERTY_REGIONS'] = [
        false,
        $_SESSION['SOTBIT_REGIONS']['ID']
    ];
}

$t = Config::get('FILTER_TEMPLATE');
if($t)
{
    $arParams['FILTER_VIEW_MODE'] = $t;
}


$arParams['SEO_DESCRIPTION'] = Config::get('SEO_DESCRIPTION');
$arParams['TAGS_POSITION'] = Config::get('TAGS_POSITION');
$arParams['SECTION_DESCRIPTION'] = Config::get('SECTION_DESCRIPTION');
$arParams['SECTION_DESCRIPTION_TOP'] = Config::get('SECTION_DESCRIPTION_TOP');
$arParams['SECTION_DESCRIPTION_BOTTOM'] = Config::get('SECTION_DESCRIPTION_BOTTOM');

if(\SotbitOrigami::isUseRegions())
{
    if($_SESSION["SOTBIT_REGIONS"]["PRICE_CODE"])
    {
        $arParams['PRICE_CODE'] = $_SESSION["SOTBIT_REGIONS"]["PRICE_CODE"];
        $arParams['~PRICE_CODE'] = $_SESSION["SOTBIT_REGIONS"]["PRICE_CODE"];
    }
    if($_SESSION["SOTBIT_REGIONS"]["STORE"])
    {
        $arParams['STORES'] = $_SESSION["SOTBIT_REGIONS"]["STORE"];
    }
}

if (!isset($arParams['FILTER_VIEW_MODE']) || (string)$arParams['FILTER_VIEW_MODE'] == '')
    $arParams['FILTER_VIEW_MODE'] = 'VERTICAL';
$arParams['USE_FILTER'] = (isset($arParams['USE_FILTER']) && $arParams['USE_FILTER'] == 'Y' ? 'Y' : 'N');

$isVerticalFilter = ('Y' == $arParams['USE_FILTER'] && $arParams["FILTER_VIEW_MODE"] == "VERTICAL");
$isSidebar = ($arParams["SIDEBAR_SECTION_SHOW"] == "Y" && isset($arParams["SIDEBAR_PATH"]) && !empty($arParams["SIDEBAR_PATH"]));
$isFilter = ($arParams['USE_FILTER'] == 'Y');

$arCurSection = array();
$arParams["SHOW_ALL_WO_SECTION"] = "Y";

include($_SERVER["DOCUMENT_ROOT"]."/".$this->GetFolder()."/section_horizontal.php");


if(isset( $kitSeoMetaTitle) && !empty($kitSeoMetaTitle))
{
    $APPLICATION->SetPageProperty('title', $kitSeoMetaTitle);
}
if( isset($kitSeoMetaKeywords) && !empty($kitSeoMetaKeywords))
{
    $APPLICATION->SetPageProperty('keywords', $kitSeoMetaKeywords);
}
if( isset($kitSeoMetaDescription) && !empty($kitSeoMetaDescription))
{
    $APPLICATION->SetPageProperty('description', $kitSeoMetaDescription);
}
if( isset($kitSeoMetaH1) && !empty($kitSeoMetaH1))
{
    $APPLICATION->SetTitle($kitSeoMetaH1);
}

global $kitSeoMetaBreadcrumbLink;
global $kitSeoMetaBreadcrumbTitle;
if( isset( $kitSeoMetaBreadcrumbTitle ) && !empty( $kitSeoMetaBreadcrumbTitle ) )
{
    $APPLICATION->AddChainItem( $kitSeoMetaBreadcrumbTitle, $kitSeoMetaBreadcrumbLink );
}

if(Bitrix\Main\Loader::includeModule('kit.opengraph')) {
    OpengraphMain::setImageMeta('og:image', $ar_section["PICTURE"]["SRC"]);
    OpengraphMain::setImageMeta('twitter:image', $ar_section["PICTURE"]["SRC"]);
    OpengraphMain::setMeta('og:type', 'product');
    OpengraphMain::setMeta('og:title', $ar_section["NAME"]);
    OpengraphMain::setMeta('og:description', $ar_section["DESCRIPTION"]);
    OpengraphMain::setMeta('twitter:title', $ar_section["NAME"]);
    OpengraphMain::setMeta('twitter:description', $ar_section["DESCRIPTION"]);
}
