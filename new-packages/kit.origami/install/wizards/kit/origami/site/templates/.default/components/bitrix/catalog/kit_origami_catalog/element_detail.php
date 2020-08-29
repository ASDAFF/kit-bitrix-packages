<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
use Bitrix\Main\Loader;
use Sotbit\Origami\Helper\Config;
use Sotbit\Origami\Config\Option, \Bitrix\Main\Localization\Loc;

global $analogProducts;

$selectSectionMain = unserialize(\Sotbit\Origami\Config\Option::get('SECTIONS_'));
$selectSectionNoTabs = unserialize(\Sotbit\Origami\Config\Option::get('SECTIONS_NO_TABS'));
$catalogId = Option::get('IBLOCK_ID', $site);
$ar = array();
$rsSection = \CIBlockSection::GetTreeList(Array("IBLOCK_ID" => $catalogId ), array("ID", "NAME", "DEPTH_LEVEL"));
$i = 0;
while($ar_l = $rsSection->GetNext()){
    $ar[$i] = array(
        'ID' => $ar_l["ID"],
        'DEPTH_LEVEL' => $ar_l["DEPTH_LEVEL"],
    );
    $i++;
}

function getSectionByCode($id, $code) {
    return $result = CIBlockElement::GetList(
        array(),
        array("IBLOCK_ID" => $id, "CODE" => $code)
    )->Fetch()
    ['IBLOCK_SECTION_ID'];
}

function getChildrenSection($arrayTree, $selectsSection) {
    $arSections = $selectsSection;
    foreach ($selectsSection as $sectionId) { // 1
        foreach ($arrayTree as $key => $value) { // id: 1 del: 2
            if ($sectionId == $value['ID']) { // 1 == 1
                $depthLv = $value['DEPTH_LEVEL'];
                $sort = array();
                $j = $key+1;
                while ($arrayTree[$j]['DEPTH_LEVEL'] > $depthLv) {
                    $sort[] = $arrayTree[$j]['ID'];
                    $j++;
                }
                $arSections = array_merge($sort, $arSections);
                break;
            }
        }
    }
    return $arSections;
}

$arSectionsMain = getChildrenSection($ar, $selectSectionMain);
$arSectionsNoTabs = getChildrenSection($ar, $selectSectionNoTabs);
$sectionIdByCode = getSectionByCode($catalogId, $componentElementParams['ELEMENT_CODE']);

$APPLICATION->IncludeComponent('bitrix:breadcrumb', 'origami_default',
    [
        "START_FROM" => "0",
        "PATH"       => "",
        "SITE_ID"    => SITE_ID,
    ], false, [
        'HIDE_ICONS' => 'N',
]);

if (in_array($sectionIdByCode, $arSectionsMain)) {
    $templateName = "";
    $template = "";
} else if (in_array($sectionIdByCode, $arSectionsNoTabs)) {
    $templateName = "origami_no_tabs";
    $template = "NO_TABS";
} else {
    if (\Sotbit\Origami\Config\Option::get('DETAIL_TEMPLATE') == "") {
        $templateName = "";
        $template = "";
    } else if (\Sotbit\Origami\Config\Option::get('DETAIL_TEMPLATE') == "NO_TABS") {
        $templateName = "origami_no_tabs";
        $template = "NO_TABS";
    }
}

$elementId = $APPLICATION->IncludeComponent(
    'bitrix:catalog.element',
    $templateName,
    $componentElementParams,
    $component
);

\SotbitOrigami::setSeoOffer();

$GLOBALS['CATALOG_CURRENT_ELEMENT_ID'] = $elementId;

if ($elementId > 0) {
    //////analog/////

    $showCrosssell = (\Sotbit\Origami\Config\Option::get('SHOW_CROSSSELL_' . $template));
    if (!in_array($showCrosssell, array('Y', 'N'))) {
        $showCrosssell = 'N';
    }

    if ($showCrosssell == 'Y') {
        $APPLICATION->IncludeComponent(
	"kit:crosssell.crosssell.list", 
	"origami_default", 
	array(
		"ACTION_VARIABLE" => $arParams["ACTION_VARIABLE"],
		"ADD_PROPERTIES_TO_BASKET" => "N",
		"ADD_SECTIONS_CHAIN" => "N",
		"AJAX_MODE" => "Y",
		"AJAX_OPTION_ADDITIONAL" => "",
		"AJAX_OPTION_HISTORY" => "N",
		"AJAX_OPTION_JUMP" => "N",
		"AJAX_OPTION_STYLE" => "Y",
		"SECTION_ID" => "",
		"SHOW_ALL_WO_SECTION" => "Y",
		"BACKGROUND_IMAGE" => "-",
		"BASKET_URL" => $arParams["BASKET_URL"],
		"BROWSER_TITLE" => "-",
		"CACHE_FILTER" => "Y",
		"CACHE_GROUPS" => "Y",
		"CACHE_TYPE" => "A",
		"CACHE_TIME" => $arParams["CACHE_TIME"],
		"COMPATIBLE_MODE" => "N",
		"CONVERT_CURRENCY" => "N",
		"CROSSSELL_LIST" => array(
		),
		"DETAIL_URL" => "",
		"DISABLE_INIT_JS_IN_COMPONENT" => "N",
		"DISPLAY_BOTTOM_PAGER" => "N",
		"DISPLAY_COMPARE" => "Y",
		"DISPLAY_TOP_PAGER" => "N",
		"ELEMENT_SORT_FIELD" => "",
		"ELEMENT_SORT_FIELD2" => "id",
		"ELEMENT_SORT_ORDER" => "",
		"ELEMENT_SORT_ORDER2" => "desc",
		"FILTER_NAME" => "arrFilter",
		"HIDE_NOT_AVAILABLE" => "N",
		"HIDE_NOT_AVAILABLE_OFFERS" => "N",
		"IBLOCK_TYPE" => "kit_origami_catalog",
		"IBLOCK_ID" => $arParams["IBLOCK_ID"],
		"INCLUDE_SUBSECTIONS" => "Y",
		"LINE_ELEMENT_COUNT" => "5",
		"MESSAGE_404" => "",
		"META_DESCRIPTION" => "-",
		"META_KEYWORDS" => "-",
		"OFFERS_LIMIT" => $arParams["OFFERS_LIMIT"],
		"OFFER_TREE_PROPS" => $arParams["OFFER_TREE_PROPS"],
		"OFFERS_SORT_FIELD" => $arParams["OFFERS_SORT_FIELD"],
		"OFFERS_SORT_ORDER" => $arParams["OFFERS_SORT_ORDER"],
		"OFFERS_SORT_FIELD2" => $arParams["OFFERS_SORT_FIELD2"],
		"OFFERS_SORT_ORDER2" => $arParams["OFFERS_SORT_ORDER2"],
		"PAGER_BASE_LINK_ENABLE" => "N",
		"PAGER_DESC_NUMBERING" => "N",
		"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
		"PAGER_SHOW_ALL" => "N",
		"PAGER_SHOW_ALWAYS" => "N",
		"PAGER_TEMPLATE" => ".default",
		"PAGER_TITLE" => "",
		"PARTIAL_PRODUCT_PROPERTIES" => "N",
		"PRICE_CODE" => \SotbitOrigami::GetComponentPrices(["BASE","OPT","SMALL_OPT"]),
		"PRICE_VAT_INCLUDE" => "N",
		"PRODUCT_ID" => $elementId,
		"PRODUCT_ID_VARIABLE" => $arParams["PRODUCT_ID_VARIABLE"],
		"PRODUCT_PROPS_VARIABLE" => $arParams["PRODUCT_PROPS_VARIABLE"],
		"PRODUCT_QUANTITY_VARIABLE" => $arParams["PRODUCT_QUANTITY_VARIABLE"],
		"SECTION_URL" => "",
		"SECTION_TEMPLATE" => "origami_section",
		"SEF_MODE" => "N",
		"SET_BROWSER_TITLE" => "N",
		"SET_LAST_MODIFIED" => "N",
		"SET_META_DESCRIPTION" => "N",
		"SET_META_KEYWORDS" => "N",
		"SET_STATUS_404" => "N",
		"SET_TITLE" => "N",
		"SHOW_404" => "N",
		"SHOW_PRICE_COUNT" => $arParams["SHOW_PRICE_COUNT"],
		"SHOW_SLIDER" => "Y",
		"SHOW_TABS" => "Y",
		"USE_MAIN_ELEMENT_SECTION" => "N",
		"USE_PRICE_COUNT" => "N",
		"USE_PRODUCT_QUANTITY" => "N",
		"COMPONENT_TEMPLATE" => "origami_default",
		"SECTION_MODE" => "Y",
		"INTERRUPT_MODE" => "N",
		"PAGE_ELEMENT_COUNT" => "",
		"PRODUCT_DISPLAY_MODE" => "Y",
		"ADD_PICT_PROP" => $arParams["ADD_PICT_PROP"],
		"OFFER_ADD_PICT_PROP" => $arParams["OFFER_ADD_PICT_PROP"],
		"PRODUCT_SUBSCRIPTION" => $arParams["PRODUCT_SUBSCRIPTION"],
		"SHOW_DISCOUNT_PERCENT" => $arParams["SHOW_DISCOUNT_PERCENT"],
		"SHOW_OLD_PRICE" => $arParams["SHOW_OLD_PRICE"],
		"SHOW_MAX_QUANTITY" => $arParams["SHOW_MAX_QUANTITY"],
		"USE_VOTE_RATING" => $arParams["DETAIL_USE_VOTE_RATING"],
		"COMPARE_PATH" => Config::get("COMPARE_PAGE"),
		"COMPARE_NAME" => "CATALOG_COMPARE_LIST",
		"USE_COMPARE_LIST" => "Y",
		"OFFERS_FIELD_CODE" => $arParams["LIST_OFFERS_FIELD_CODE"],
		"OFFERS_PROPERTY_CODE" => $arParams["LIST_OFFERS_PROPERTY_CODE"],
		"PRODUCT_PROPERTIES" => $arParams["PRODUCT_PROPERTIES"],
		"OFFERS_CART_PROPERTIES" => $arParams["OFFERS_CART_PROPERTIES"],
		"VARIANT_LIST_VIEW" => "template_3",
		"ACTION_PRODUCTS" => array(
			0 => "ADMIN",
		),
		"LIST_PROPERTY_CODE" => array(
		)
	),
	false
);
    }
}

$componentElementParams += array('TEMPLATE_NAMED' => $template);

$APPLICATION->IncludeComponent(
    'kit:catalog.bitrix.resale',
    'origami_default',
    $componentElementParams,
    $component
);

?>
