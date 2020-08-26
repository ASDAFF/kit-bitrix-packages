<?php
use Sotbit\Origami\Helper\Config;

global $filterAdvantages;
$filterAdvantages = ['PROPERTY_SHOW_ON_MAINPAGE_VALUE' => 'Y'];
$useRegion = (Config::get('USE_REGIONS') == 'Y') ? true : false;

if ($useRegion && $_SESSION['SOTBIT_REGIONS']['ID']) {
    $filterAdvantages['PROPERTY_REGIONS'] = [
        false,
        $_SESSION['SOTBIT_REGIONS']['ID'],
    ];
}

$APPLICATION->IncludeComponent(
    "bitrix:news.list",
    "origami_advantages_description",
    [
        "ACTIVE_DATE_FORMAT"              => "d.m.Y",
        "ADD_SECTIONS_CHAIN"              => "N",
        "AJAX_MODE"                       => "N",
        "AJAX_OPTION_ADDITIONAL"          => "",
        "AJAX_OPTION_HISTORY"             => "N",
        "AJAX_OPTION_JUMP"                => "N",
        "AJAX_OPTION_STYLE"               => "Y",
        "CACHE_FILTER"                    => "N",
        "CACHE_GROUPS"                    => "Y",
        "CACHE_TIME"                      => "36000000",
        "CACHE_TYPE"                      => "A",
        "CHECK_DATES"                     => "Y",
        "COMPOSITE_FRAME_MODE"            => "A",
        "COMPOSITE_FRAME_TYPE"            => "AUTO",
        "DETAIL_URL"                      => "",
        "DISPLAY_BOTTOM_PAGER"            => "Y",
        "DISPLAY_DATE"                    => "Y",
        "DISPLAY_NAME"                    => "Y",
        "DISPLAY_PICTURE"                 => "Y",
        "DISPLAY_PREVIEW_TEXT"            => "Y",
        "DISPLAY_TOP_PAGER"               => "N",
        "FIELD_CODE"                      => [
            0 => "",
            1 => "",
        ],
        "FILTER_NAME"                     => "filterAdvantages",
        "HIDE_LINK_WHEN_NO_DETAIL"        => "N",
        "IBLOCK_ID"                       => Config::get('IBLOCK_ID_ADVANTAGES'),
        "IBLOCK_TYPE"                     => Config::get('IBLOCK_TYPE_ADVANTAGES'),
        "INCLUDE_IBLOCK_INTO_CHAIN"       => "N",
        "INCLUDE_SUBSECTIONS"             => "Y",
        "MESSAGE_404"                     => "",
        "NEWS_COUNT"                      => "4",
        "PAGER_BASE_LINK_ENABLE"          => "N",
        "PAGER_DESC_NUMBERING"            => "N",
        "PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
        "PAGER_SHOW_ALL"                  => "N",
        "PAGER_SHOW_ALWAYS"               => "N",
        "PAGER_TEMPLATE"                  => ".default",
        "PAGER_TITLE"                     => "Преимущества",
        "PARENT_SECTION"                  => "",
        "PARENT_SECTION_CODE"             => "",
        "PREVIEW_TRUNCATE_LEN"            => "",
        "PROPERTY_CODE"                   => [
            0 => "URL",
            1 => "",
        ],
        "SET_BROWSER_TITLE"               => "Y",
        "SET_LAST_MODIFIED"               => "N",
        "SET_META_DESCRIPTION"            => "Y",
        "SET_META_KEYWORDS"               => "Y",
        "SET_STATUS_404"                  => "N",
        "SET_TITLE"                       => "N",
        "SHOW_404"                        => "N",
        "SORT_BY1"                        => "ACTIVE_FROM",
        "SORT_BY2"                        => "SORT",
        "SORT_ORDER1"                     => "DESC",
        "SORT_ORDER2"                     => "ASC",
        "STRICT_SECTION_CHECK"            => "N",
        "COMPONENT_TEMPLATE"              => "sotbit_advantages_simple",
    ],
    false
);
?>