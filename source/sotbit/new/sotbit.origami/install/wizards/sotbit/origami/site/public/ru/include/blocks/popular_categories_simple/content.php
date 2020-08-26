<?
global $settings, $arrFilter;
use Sotbit\Origami\Helper\Config;

$arrFilter = array();
$arrFilter["UF_SHOW_ON_MAIN_PAGE"] = 1;
$arrFilter[">PICTURE"] = 0;

$APPLICATION->IncludeComponent(
    "bitrix:catalog.section.list",
    "origami_popular_categories_simple",
    array(
        "ADD_SECTIONS_CHAIN" => "N",
        "CACHE_GROUPS" => "Y",
        "CACHE_TIME" => "36000000",
        "CACHE_TYPE" => "N",
        "FILTER_NAME" => "arrFilter",
        "COMPOSITE_FRAME_MODE" => "A",
        "COMPOSITE_FRAME_TYPE" => "AUTO",
        "COUNT_ELEMENTS" => "N",
        "IBLOCK_ID" => Config::get('IBLOCK_ID'),
        "IBLOCK_TYPE" => Config::get('IBLOCK_TYPE'),
        "SECTION_FIELDS" => array(
            0 => "NAME",
            1 => "SECTION_PAGE_URL",
            2 => "PICTURE",
        ),
        "SECTION_URL" => "",
        "SECTION_USER_FIELDS" => array(
            0 => "UF_SHOW_ON_MAIN_PAGE",
        ),
        "SHOW_PARENT_NAME" => "Y",
        "TOP_DEPTH" => $settings['fields']['top_depth']['value'],
        "VIEW_MODE" => "LINE",
        "LINK_TO_THE_CATALOG" => $settings['fields']['link_catalog']['value'],
        "BLOCK_NAME" => $settings['fields']['title']['value'],
        "COUNT_SECTIONS" => $settings['fields']['count_sections']['value']
    ),
    false
);
?>