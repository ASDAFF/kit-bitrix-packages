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

$APPLICATION->IncludeComponent(
    "bitrix:news.list",
    "popular",
    [
        "IBLOCK_ID" => Config::get('IBLOCK_ID_NEWS'),
        "IBLOCK_TYPE" => Config::get('IBLOCK_TYPE_NEWS'),
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
$APPLICATION->ShowViewContent('news_detail');
?>