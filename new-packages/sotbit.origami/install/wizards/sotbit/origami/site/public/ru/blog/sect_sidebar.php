<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
use Sotbit\Origami\Helper\Config;

global $filterSideFilter;
$useRegion = (Config::get('USE_REGIONS') == 'Y') ? true : false;
if ($useRegion && $_SESSION['SOTBIT_REGIONS']['ID']) {
    $filterSideFilter['PROPERTY_REGIONS'] = [
        false,
        $_SESSION['SOTBIT_REGIONS']['ID']
    ];
}
$APPLICATION->ShowViewContent('blog_tags');

$APPLICATION->IncludeComponent(
    "bitrix:news.list",
    "popular_blog_2",
    [
        "IBLOCK_ID" => Config::get("IBLOCK_ID_BLOG"),
        "IBLOCK_TYPE" => Config::get("IBLOCK_TYPE_BLOG"),
        "NEWS_COUNT"                      => 5,
        "SORT_BY1"                        => 'show_counter',
        "SORT_ORDER1"                     => 'asc',
        "DISPLAY_PANEL"                   => 'N',
        "SET_TITLE"                       => 'N',
        "SET_LAST_MODIFIED"               => 'N',
        "MESSAGE_404"                     => '',
        "SET_STATUS_404"                  => 'N',
        "SHOW_404"                        => 'N',
        "FILE_404"                        => 'N',
        "INCLUDE_IBLOCK_INTO_CHAIN"       => 'N',
        "CACHE_TIME"                      => 36000000,
        "DISPLAY_TOP_PAGER"               => 'N',
        "DISPLAY_BOTTOM_PAGER"            => 'N',
        "DISPLAY_NAME"                    => "Y",
        "ACTIVE_DATE_FORMAT"              => 'd.m.Y',
        "FILTER_NAME"                     => '',
    ],
    $component
);

$APPLICATION->IncludeComponent(
	"bitrix:sender.subscribe", 
	"sotbit_sender_subscribe_campaign", 
	array(
		"AJAX_MODE" => "Y",
		"AJAX_OPTION_ADDITIONAL" => "",
		"AJAX_OPTION_HISTORY" => "N",
		"AJAX_OPTION_JUMP" => "N",
		"AJAX_OPTION_STYLE" => "Y",
		"CACHE_TIME" => "3600",
		"CACHE_TYPE" => "A",
		"COMPONENT_TEMPLATE" => "sotbit_sender_subscribe_campaign",
		"COMPOSITE_FRAME_MODE" => "A",
		"COMPOSITE_FRAME_TYPE" => "AUTO",
		"CONFIRMATION" => "N",
		"FORM_SUBSCRIBE_TITLE" => "Подписаться на блог",
		"HIDE_MAILINGS" => "N",
		"MAILING_LISTS" => array(
			0 => "1",
			1 => "2",
			2 => "3",
		),
		"SET_TITLE" => "N",
		"SHOW_HIDDEN" => "Y",
		"USER_CONSENT" => "N",
		"USER_CONSENT_ID" => "0",
		"USER_CONSENT_IS_CHECKED" => "Y",
		"USER_CONSENT_IS_LOADED" => "N",
		"USE_PERSONALIZATION" => "Y"
	),
	false
);

global $bannerFilter;
$bannerFilter = [
    'ACTIVE' => 'Y',
    'PROPERTY_BANNER_TYPE' => Config::getBanner(['INNER']),

    array(
        "LOGIC" => "OR",
        array("PROPERTY_SHOW_SECTIONS" => $APPLICATION->GetCurPage(false)),
        array("PROPERTY_SHOW_SECTIONS" => false)
    ),
];
$useRegion = (Config::get('USE_REGIONS') == 'Y') ? true : false;
if ($useRegion && $_SESSION['SOTBIT_REGIONS']['ID']) {
    $bannerFilter['PROPERTY_REGIONS'] = [
        false,
        $_SESSION['SOTBIT_REGIONS']['ID']
    ];
}
$APPLICATION->IncludeComponent(
"bitrix:news.list",
"origami_inner_banner",
	array(
		"IBLOCK_TYPE" => Config::get("IBLOCK_TYPE_BANNERS"),
		"IBLOCK_ID" => Config::get("IBLOCK_ID_BANNERS"),
		"NEWS_COUNT" => "3",
		"SORT_BY1" => "sort",
		"SORT_ORDER1" => "desc",
		"SORT_BY2" => "id",
		"SORT_ORDER2" => "asc",
		"FIELD_CODE" => array(
			0 => "PREVIEW_PICTURE",
			1 => "DETAIL_PICTURE",
			2 => "",
		),
		"PROPERTY_CODE" => array(
			0 => "",
			1 => "BUTTON_TEXT",
			2 => "URL",
			3 => "NEW_TAB",
			4 => "",
		),
		"SET_TITLE" => "N",
		"SET_LAST_MODIFIED" => "N",
		"MESSAGE_404" => "",
		"SET_STATUS_404" => "N",
		"SHOW_404" => "N",
		"INCLUDE_IBLOCK_INTO_CHAIN" => "N",
		"CACHE_TYPE" => "A",
		"CACHE_TIME" => "36000000",
		"CACHE_FILTER" => "N",
		"CACHE_GROUPS" => "N",
		"DISPLAY_TOP_PAGER" => "N",
		"DISPLAY_BOTTOM_PAGER" => "N",
		"PAGER_TITLE" => "",
		"PAGER_TEMPLATE" => ".default",
		"PAGER_SHOW_ALWAYS" => "N",
		"PAGER_DESC_NUMBERING" => "N",
		"PAGER_DESC_NUMBERING_CACHE_TIME" => "",
		"PAGER_SHOW_ALL" => "N",
		"PAGER_BASE_LINK_ENABLE" => "N",

		"DISPLAY_DATE" => "N",
		"DISPLAY_NAME" => "Y",
		"DISPLAY_PICTURE" => "N",
		"DISPLAY_PREVIEW_TEXT" => "N",
		"PREVIEW_TRUNCATE_LEN" => "",
		"ACTIVE_DATE_FORMAT" => "d.m.Y",
		"USE_PERMISSIONS" => "N",
		"FILTER_NAME" => "bannerFilter",
		"HIDE_LINK_WHEN_NO_DETAIL" => "N",
		"CHECK_DATES" => "N",
		"COMPONENT_TEMPLATE" => "origami_inner_banner",
		"AJAX_MODE" => "N",
		"AJAX_OPTION_JUMP" => "N",
		"AJAX_OPTION_STYLE" => "Y",
		"AJAX_OPTION_HISTORY" => "N",
		"AJAX_OPTION_ADDITIONAL" => "",
		"SET_BROWSER_TITLE" => "Y",
		"SET_META_KEYWORDS" => "Y",
		"SET_META_DESCRIPTION" => "Y",
		"ADD_SECTIONS_CHAIN" => "Y",
		"PARENT_SECTION" => "",
		"PARENT_SECTION_CODE" => "",
		"INCLUDE_SUBSECTIONS" => "Y",
		"STRICT_SECTION_CHECK" => "N"
	),
	false
);
?>