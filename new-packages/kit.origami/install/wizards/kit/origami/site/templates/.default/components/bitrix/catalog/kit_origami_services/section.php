<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();use Bitrix\Main\Loader;
use Sotbit\Origami\Helper\Config;

if(\SotbitOrigami::getRootComponentPath($this, $arResult, $arParams))
{
    include($_SERVER["DOCUMENT_ROOT"]."/".$this->GetFolder()."/sections.php");
    return;
}

if(\SotbitOrigami::getOfferUrlComponentPath($this, $arResult, $arParams))
{
    include($_SERVER["DOCUMENT_ROOT"]."/".$this->GetFolder()."/element.php");
    return;
}

\SotbitOrigami::process404($this, $arResult, $arParams);

global $APPLICATION;

$request = \Bitrix\Main\Application::getInstance()->getContext()->getRequest();

if($request->get('ajaxFilter') == 'Y')
{
    $GLOBALS['APPLICATION']->RestartBuffer();

}

global $sotbitSeoMetaBottomDesc;
global $sotbitSeoMetaTopDesc;
global $sotbitSeoMetaAddDesc;
global $sotbitSeoMetaFile;

$this->setFrameMode(true);

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

$descriptionSection = Config::get('SECTION_DESCRIPTION');
if (isset($descriptionSection)){
    $arParams['SECTION_DESCRIPTION'] = $descriptionSection;
}

$sectionTopDescription = Config::get('SECTION_DESCRIPTION_TOP');
if (isset($sectionTopDescription)){
    $arParams['SECTION_DESCRIPTION_TOP'] = $sectionTopDescription;
}

$sectionBottomDescription = Config::get('SECTION_DESCRIPTION_BOTTOM');
if (isset($sectionBottomDescription)){
    $arParams['SECTION_DESCRIPTION_BOTTOM'] = $sectionBottomDescription;
}

$topDescription = Config::get('DESCRIPTION_TOP');
if (isset($topDescription)){
    $arParams['DESCRIPTION_TOP'] = $topDescription;
}

$bottomDescription = Config::get('DESCRIPTION_BOTTOM');
if (isset($bottomDescription)){
    $arParams['DESCRIPTION_BOTTOM'] = $bottomDescription;
}

$additionalDescription = Config::get('DESCRIPTION_ADDITIONAL');
if (isset($additionalDescription)){
    $arParams['DESCRIPTION_ADDITIONAL'] = $additionalDescription;
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

if ($isFilter)
{
	$arFilter = array(
		"IBLOCK_ID" => $arParams["IBLOCK_ID"],
		"ACTIVE" => "Y",
		"GLOBAL_ACTIVE" => "Y",
	);
	if (0 < intval($arResult["VARIABLES"]["SECTION_ID"]))
		$arFilter["ID"] = $arResult["VARIABLES"]["SECTION_ID"];
	elseif ('' != $arResult["VARIABLES"]["SECTION_CODE"])
		$arFilter["=CODE"] = $arResult["VARIABLES"]["SECTION_CODE"];

	$obCache = new CPHPCache();
	if ($obCache->InitCache(36000, serialize($arFilter), "/iblock/catalog"))
	{
		$arCurSection = $obCache->GetVars();
	}
	elseif ($obCache->StartDataCache())
	{
		$arCurSection = array();
		if (Loader::includeModule("iblock"))
		{
			$dbRes = CIBlockSection::GetList(array(), $arFilter, false, array("ID"));

			if(defined("BX_COMP_MANAGED_CACHE"))
			{
				global $CACHE_MANAGER;
				$CACHE_MANAGER->StartTagCache("/iblock/catalog");

				if ($arCurSection = $dbRes->Fetch())
					$CACHE_MANAGER->RegisterTag("iblock_id_".$arParams["IBLOCK_ID"]);

				$CACHE_MANAGER->EndTagCache();
			}
			else
			{
				if(!$arCurSection = $dbRes->Fetch())
					$arCurSection = array();
			}
		}
		$obCache->EndDataCache($arCurSection);
	}
	if (!isset($arCurSection))
		$arCurSection = array();
}

include($_SERVER["DOCUMENT_ROOT"]."/".$this->GetFolder()."/section_horizontal.php");

global $sotbitSeoMetaTitle;
global $sotbitSeoMetaKeywords;
global $sotbitSeoMetaDescription;
global $sotbitSeoMetaH1;

if(isset( $sotbitSeoMetaTitle) && !empty($sotbitSeoMetaTitle))
{
    $APPLICATION->SetPageProperty('title', $sotbitSeoMetaTitle);
}
if( isset($sotbitSeoMetaKeywords) && !empty($sotbitSeoMetaKeywords))
{
    $APPLICATION->SetPageProperty('keywords', $sotbitSeoMetaKeywords);
}
if( isset($sotbitSeoMetaDescription) && !empty($sotbitSeoMetaDescription))
{
    $APPLICATION->SetPageProperty('description', $sotbitSeoMetaDescription);
}
if( isset($sotbitSeoMetaH1) && !empty($sotbitSeoMetaH1))
{
    $APPLICATION->SetTitle($sotbitSeoMetaH1);
}

global $sotbitSeoMetaBreadcrumbLink;
global $sotbitSeoMetaBreadcrumbTitle;
if( isset( $sotbitSeoMetaBreadcrumbTitle ) && !empty( $sotbitSeoMetaBreadcrumbTitle ) )
{
    $APPLICATION->AddChainItem( $sotbitSeoMetaBreadcrumbTitle, $sotbitSeoMetaBreadcrumbLink );
}

if(Bitrix\Main\Loader::includeModule('sotbit.opengraph'))
{
    OpengraphMain::setImageMeta('og:image', $ar_section["PICTURE"]["SRC"]);
    OpengraphMain::setImageMeta('twitter:image', $ar_section["PICTURE"]["SRC"]);
    OpengraphMain::setMeta('og:type', 'product');
    OpengraphMain::setMeta('og:title', $ar_section["NAME"]);
    OpengraphMain::setMeta('og:description', $ar_section["DESCRIPTION"]);
    OpengraphMain::setMeta('twitter:title', $ar_section["NAME"]);
    OpengraphMain::setMeta('twitter:description', $ar_section["DESCRIPTION"]);
}
if($request->get('ajaxFilter') == 'Y')
{
    echo \SotbitOrigami::prepareJSData($this, $arParams);
    die();
}

?>
