<?php
use Sotbit\Origami\Helper\Config;

global $filterPromotions;
global $settings;
global $DB;

$filterPromotions = [
    'PROPERTY_INDEX_PAGE_VALUE' => 'Y',
    'ACTIVE' => 'Y',
    0 => array(
        'LOGIC' => 'OR',
        array('<DATE_ACTIVE_FROM' => date($DB->DateFormatToPHP
        (CLang::GetDateFormat("LONG")))),
        array('=DATE_ACTIVE_FROM' => false)
    )
];
$useRegion = (Config::get('USE_REGIONS') == 'Y') ? true : false;
if ($useRegion && $_SESSION['SOTBIT_REGIONS']['ID']) {
    $filterPromotions['PROPERTY_REGIONS'] = [
        false,
        $_SESSION['SOTBIT_REGIONS']['ID']
    ];
}
$APPLICATION->IncludeComponent(
	"bitrix:news.list",
	"origami_promotions_vertical",
	array(
		"ACTIVE_DATE_FORMAT" => "d.m.Y",
		"ADD_SECTIONS_CHAIN" => "N",
		"AJAX_MODE" => "N",
		"AJAX_OPTION_ADDITIONAL" => "",
		"AJAX_OPTION_HISTORY" => "N",
		"AJAX_OPTION_JUMP" => "N",
		"AJAX_OPTION_STYLE" => "Y",
		"CACHE_FILTER" => "Y",
		"CACHE_GROUPS" => "Y",
		"CACHE_TIME" => "36000000",
		"CACHE_TYPE" => "A",
		"CHECK_DATES" => "Y",
		"DETAIL_URL" => "",
		"DISPLAY_BOTTOM_PAGER" => "N",
		"DISPLAY_DATE" => "Y",
		"DISPLAY_NAME" => "Y",
		"DISPLAY_PICTURE" => "Y",
        "DISPLAY_PREVIEW_TEXT" => "Y",
        "LINK_TO_THE_FULL_LIST" => SITE_DIR."promotions/",
        "BLOCK_NAME" => $settings['fields']['title']['value'],
		"DISPLAY_TOP_PAGER" => "N",
		"FIELD_CODE" => array(
			0 => "DETAIL_PICTURE",
			1 => "DATE_ACTIVE_FROM",
			2 => "DATE_ACTIVE_TO",
			3 => "",
		),
		"FILTER_NAME" => "filterPromotions",
		"HIDE_LINK_WHEN_NO_DETAIL" => "N",
        "IBLOCK_ID" => Config::get('IBLOCK_ID_PROMOTION'),
        "IBLOCK_TYPE" => Config::get('IBLOCK_TYPE_PROMOTION'),
		"INCLUDE_IBLOCK_INTO_CHAIN" => "N",
		"INCLUDE_SUBSECTIONS" => "Y",
		"MESSAGE_404" => "",
		"NEWS_COUNT" => "5",
		"PAGER_BASE_LINK_ENABLE" => "N",
		"PAGER_DESC_NUMBERING" => "N",
		"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
		"PAGER_SHOW_ALL" => "N",
		"PAGER_SHOW_ALWAYS" => "N",
		"PAGER_TEMPLATE" => ".default",
		"PAGER_TITLE" => $settings['fields']['title']['value'],
		"PARENT_SECTION" => "",
		"PARENT_SECTION_CODE" => "",
		"PREVIEW_TRUNCATE_LEN" => "",
		"PROPERTY_CODE" => array(
			0 => "PERIOD",
			1 => "",
		),
		"SET_BROWSER_TITLE" => "Y",
		"SET_LAST_MODIFIED" => "N",
		"SET_META_DESCRIPTION" => "Y",
		"SET_META_KEYWORDS" => "Y",
		"SET_STATUS_404" => "N",
		"SET_TITLE" => "N",
		"SHOW_404" => "N",
		"SORT_BY1" => "ACTIVE_FROM",
		"SORT_BY2" => "SORT",
		"SORT_ORDER1" => "DESC",
		"SORT_ORDER2" => "ASC",
		"STRICT_SECTION_CHECK" => "N",
		"COMPONENT_TEMPLATE" => "sotbit_promotions_hybrid",
		"TEMPLATE_THEME" => "blue",
		"MEDIA_PROPERTY" => "",
		"SLIDER_PROPERTY" => "",
		"SEARCH_PAGE" => "/search/",
		"USE_RATING" => "N",
		"USE_SHARE" => "N",
		"COMPOSITE_FRAME_MODE" => "A",
		"COMPOSITE_FRAME_TYPE" => "AUTO"
	),
	false
);
?>