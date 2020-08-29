<?
global $settings, $arrFilter;

$picture = $settings['fields']['image_from']['value'];
$showDescription = ($settings['fields']['show_description']['value'] == "Y") ? true : false;

$arrFilter = array();
$arrFilter[">UF_SHOW_ON_MAIN_PAGE"] = 0;
$arrFilter[">" . $picture] = 0;

use Sotbit\Origami\Helper\Config;
$APPLICATION->IncludeComponent(
    "bitrix:catalog.section.list",
    "origami_popular_categories_advanced",
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
            2 => $showDescription ? "DESCRIPTION" : "",
            3 => ($picture == "PICTURE") ? "PICTURE" : ""
        ),
        "SECTION_URL" => "",
        "SECTION_USER_FIELDS" => array(
            0 => "UF_SHOW_ON_MAIN_PAGE",
            1 => ($picture == "UF_PHOTO_DETAIL") ? "UF_PHOTO_DETAIL" : "",
        ),
        "SHOW_PARENT_NAME" => "Y",
        "TOP_DEPTH" => $settings['fields']['top_depth']['value'],
        "SHOW_SUBSECTIONS" => $settings['fields']['show_subsections']['value'],
        "VIEW_MODE" => "LINE",
        "LINK_TO_THE_CATALOG" => $settings['fields']['link_catalog']['value'],
        "BLOCK_NAME" => $settings['fields']['title']['value'],
        "COUNT_SECTIONS" => $settings['fields']['count_sections']['value'],
        "COMPONENT_TEMPLATE" => "kit_popular_categories_advanced"
    ),
    false
);
?>