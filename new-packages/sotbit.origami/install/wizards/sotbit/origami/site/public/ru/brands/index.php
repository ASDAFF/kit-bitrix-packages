<?php
use Sotbit\Origami\Helper\Config;

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Бренды");
$APPLICATION->IncludeComponent(
	"sotbit:news",
	"sotbit_origami_brands",
	array(
		"ADD_ELEMENT_CHAIN" => "Y",
		"ADD_SECTIONS_CHAIN" => "Y",
		"AJAX_MODE" => "N",
		"AJAX_OPTION_ADDITIONAL" => "",
		"AJAX_OPTION_HISTORY" => "N",
		"AJAX_OPTION_JUMP" => "N",
		"AJAX_OPTION_STYLE" => "Y",
		"BROWSER_TITLE" => "-",
		"CACHE_FILTER" => "Y",
		"CACHE_GROUPS" => "N",
		"CACHE_TIME" => "100000",
		"CACHE_TYPE" => "A",
		"CHECK_DATES" => "Y",
		"COMPONENT_TEMPLATE" => "sotbit_origami_brands",
		"COMPOSITE_FRAME_MODE" => "A",
		"COMPOSITE_FRAME_TYPE" => "AUTO",
		"CONVERT_CURRENCY" => "N",
		"COUNT_IN_LINE" => "3",
		"CURRENCY_ID" => "RUB",
		"FILTER_NAME" => "brands",
		"DETAIL_ACTIVE_DATE_FORMAT" => "d.m.Y",
		"BRAND_PROP_CODE" => "BRANDS",
		"DETAIL_DISPLAY_BOTTOM_PAGER" => "Y",
		"DETAIL_DISPLAY_TOP_PAGER" => "N",
		"DETAIL_FIELD_CODE" => array(
			0 => "NAME",
			1 => "PREVIEW_TEXT",
			2 => "PREVIEW_PICTURE",
			3 => "DETAIL_TEXT",
			4 => "DETAIL_PICTURE",
			5 => "",
		),
		"DETAIL_PAGER_SHOW_ALL" => "Y",
		"DETAIL_PAGER_TEMPLATE" => "origami",
		"DETAIL_PAGER_TITLE" => "Страница",
		"DETAIL_PROPERTY_CODE" => array(
			0 => "",
			1 => "SITE",
			2 => "PHONE",
			3 => "DOCUMENTS",
			4 => "PHOTOS",
			5 => "",
		),
		"DETAIL_SET_CANONICAL_URL" => "N",
		"DISPLAY_BOTTOM_PAGER" => "Y",
		"DISPLAY_NAME" => "N",
		"DISPLAY_TOP_PAGER" => "N",
		"HIDE_LINK_WHEN_NO_DETAIL" => "Y",
		"IBLOCK_ID" => Config::get("IBLOCK_ID_BRANDS"),
		"IBLOCK_TYPE" => Config::get("IBLOCK_TYPE_BRANDS"),
		"IMAGE_POSITION" => "left",
		"INCLUDE_IBLOCK_INTO_CHAIN" => "N",
		"LIST_ACTIVE_DATE_FORMAT" => "d.m.Y",
		"LIST_FIELD_CODE" => array(
			0 => "NAME",
			1 => "PREVIEW_TEXT",
			2 => "PREVIEW_PICTURE",
			3 => "",
		),
		"LIST_PROPERTY_CODE" => array(
			0 => "",
			1 => "",
		),
		"MESSAGE_404" => "",
		"META_DESCRIPTION" => "-",
		"META_KEYWORDS" => "-",
		"NEWS_COUNT" => "80",
		"PAGER_BASE_LINK_ENABLE" => "N",
		"PAGER_DESC_NUMBERING" => "N",
		"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
		"PAGER_SHOW_ALL" => "N",
		"PAGER_SHOW_ALWAYS" => "N",
		"PAGER_TEMPLATE" => "origami",
		"PAGER_TITLE" => "Новости",
		"PREVIEW_TRUNCATE_LEN" => "",
		"PRICE_CODE" => \SotbitOrigami::GetComponentPrices(["OPT","SMALL_OPT","BASE"]),
		"REGION" => $_SESSION["SOTBIT_REGIONS"]["ID"],
		"SEF_FOLDER" => "/brands/",
		"SEF_MODE" => "Y",
		"SET_LAST_MODIFIED" => "N",
		"SET_STATUS_404" => "Y",
		"SET_TITLE" => "Y",
		"SHOW_404" => "Y",
		"SHOW_DETAIL_LINK" => "Y",
		"SHOW_SECTION_PREVIEW_DESCRIPTION" => "Y",
		"SORT_BY1" => "SORT",
		"SORT_BY2" => "ID",
		"SORT_ORDER1" => "ASC",
		"SORT_ORDER2" => "DESC",
		"STORES" => array(
			0 => "1",
			1 => "",
		),
		"USE_CATEGORIES" => "N",
		"USE_FILTER" => "N",
		"USE_PERMISSIONS" => "N",
		"USE_RATING" => "N",
		"USE_REVIEW" => "N",
		"USE_RSS" => "N",
		"USE_SEARCH" => "N",
		"LIST_OFFERS_FIELD_CODE" => "",
		"LIST_OFFERS_PROPERTY_CODE" => "",
		"SHOW_RATING" => "Y",
		"DISPLAY_COMPARE" => "Y",
		"DISPLAY_WISH_BUTTONS" => "Y",
		"OFFER_ADD_PICT_PROP" => "-",
		"OFFER_TREE_PROPS" => array(
			0 => "PROTSESSOR",
			1 => "OBEM_OPERATICHNOY_PAMYATI",
			2 => "OBEM_PAMYATI",
			3 => "RAZMER",
			4 => "CHASTOTA_PROTSESSORA",
			5 => "TIP_VIDEOKARTY",
			6 => "TSVET",
			7 => "KOLICHESTVO_YADER_PROTSESORA",
			8 => "OBEM_VIDEOPAMYATI",
			9 => "TSVET_1",
			10 => "USTANOVLENNAYA_OS",
			11 => "CML2_MANUFACTURER",
		),
		"LIST_OFFERS_LIMIT" => "20",
		"STRICT_SECTION_CHECK" => "N",
		"DISPLAY_DATE" => "Y",
		"DISPLAY_PICTURE" => "Y",
		"DISPLAY_PREVIEW_TEXT" => "Y",
		"USE_SHARE" => "N",
		"FILE_404" => "",
		"DISPLAY_VIEW_TITLE" => "Y",
		"SEF_URL_TEMPLATES" => array(
			"news" => "",
			"section" => "",
			"detail" => "#ELEMENT_CODE#/",
		)
	),
	false
);
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>
