<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
$this->setFrameMode(true);

use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

global ${$arParams["FILTER_NAME"]};
// FILTER // ------------------------------------------------------------------------------------------------------------------------------------------------------------------
${$arParams["FILTER_NAME"]} = $arResult['COND'];

if($arResult['SAFE'])
{
    if ($arParams['FROM_COMPLEX'] != true)
    {
        echo "<h2>" . $arResult['COND_NAME'] . "</h2>";
    }

    $APPLICATION->IncludeComponent(
        "bitrix:news.list",
        'crosssell_news',
        Array(
            "IBLOCK_ID" => $arParams['IBLOCK_ID'],
            "IBLOCK_TYPE" => $arParams['IBLOCK_TYPE'],
            "NEWS_COUNT" => $arParams['LINE_ELEMENT_COUNT'],

            "SORT_BY1" => $arParams["ELEMENT_SORT_FIELD"],
            "SORT_BY2" => $arParams["ELEMENT_SORT_FIELD2"],
            "SORT_ORDER1" => $arParams["ELEMENT_SORT_ORDER"],
            "SORT_ORDER2" => $arParams["ELEMENT_SORT_ORDER2"],
            "FILTER_NAME" => $arParams["FILTER_NAME"],
            "FIELD_CODE" => $arParams['FIELD_CODE'],
            "PROPERTY_CODE" => $arParams['PROPERTY_CODE'],

            "AJAX_MODE" => $arParams['AJAX_MODE'],
            "AJAX_OPTION_JUMP" => $arParams['AJAX_OPTION_JUMP'],
            "AJAX_OPTION_STYLE" => $arParams['AJAX_OPTION_STYLE'],
            "AJAX_OPTION_HISTORY" => $arParams['AJAX_OPTION_HISTORY'],

            "PREVIEW_TRUNCATE_LEN" => $arParams['PREVIEW_TRUNCATE_LEN'],
            "SET_BROWSER_TITLE" => $arParams['SET_BROWSER_TITLE'],
            "SET_LAST_MODIFIED" => $arParams['PRODUCT_PROPS_VARIABLE'],
            "SET_META_DESCRIPTION" => $arParams['SET_META_DESCRIPTION'],
            "SET_META_KEYWORDS" => $arParams['SET_META_KEYWORDS'],
            "SET_LAST_MODIFIED" => $arParams['SET_LAST_MODIFIED'],
            "INCLUDE_IBLOCK_INTO_CHAIN" => $arParams['INCLUDE_IBLOCK_INTO_CHAIN'],
            "ADD_SECTIONS_CHAIN" => $arParams['ADD_SECTIONS_CHAIN'],
            "HIDE_LINK_WHEN_NO_DETAIL" => $arParams['HIDE_LINK_WHEN_NO_DETAIL'],
            "ACTIVE_DATE_FORMAT" => $arParams['ACTIVE_DATE_FORMAT'],
            "SET_TITLE" => $arParams['SET_TITLE'],
            "PARENT_SECTION" => $arParams['PARENT_SECTION'],
            "PARENT_SECTION_CODE" => $arParams['PARENT_SECTION_CODE'],
            "INCLUDE_SUBSECTIONS" => $arParams['INCLUDE_SUBSECTIONS'],
            "DISPLAY_DATE" => $arParams['DISPLAY_DATE'],
            "DISPLAY_NAME" => $arParams['DISPLAY_NAME'],
            "DISPLAY_PICTURE" => $arParams['DISPLAY_PICTURE'],
            "DISPLAY_PREVIEW_TEXT" => $arParams['DISPLAY_PREVIEW_TEXT'],

            "CACHE_TYPE" => $arParams["CACHE_TYPE"],
            "CACHE_TIME" => $arParams["CACHE_TIME"],
            "CACHE_FILTER" => $arParams["CACHE_FILTER"],
            "CACHE_GROUPS" => $arParams["CACHE_GROUPS"],

            "PAGER_TEMPLATE" => $arParams['PAGER_TEMPLATE'],
            "DISPLAY_TOP_PAGER" => $arParams['DISPLAY_TOP_PAGER'],
            "DISPLAY_BOTTOM_PAGER" => $arParams['DISPLAY_BOTTOM_PAGER'],
            "PAGER_TITLE" => $arParams['PAGER_TITLE'],
            "PAGER_SHOW_ALWAYS" => $arParams['PAGER_SHOW_ALWAYS'],
            "PAGER_DESC_NUMBERING" => $arParams['PAGER_DESC_NUMBERING'],
            "PAGER_DESC_NUMBERING_CACHE_TIME" => $arParams['PAGER_DESC_NUMBERING_CACHE_TIME'],
            "PAGER_SHOW_ALL" => $arParams['PAGER_SHOW_ALL'],
            "PAGER_BASE_LINK_ENABLE" => $arParams['PAGER_BASE_LINK_ENABLE'],

            "SET_STATUS_404" => $arParams['SET_STATUS_404'],
            "SHOW_404" => $arParams['SHOW_404'],

            "LAZY_LOAD" => $arParams['LAZY_LOAD'],
//        "ITEM_TEMPLATE" => $crosssell['ITEM_TEMPLATE'],
        ),
        $component
    );
}

