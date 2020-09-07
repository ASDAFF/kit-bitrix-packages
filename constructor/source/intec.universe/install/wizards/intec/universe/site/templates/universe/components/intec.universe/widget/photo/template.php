<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die() ?>
<?php

/**
 * @var array $arParams
 * @var array $arResult
 * @var CBitrixComponent $component
 * @var CBitrixComponentTemplate $this
 * @global CMain $APPLICATION
 */

$this->setFrameMode(true);

if (!CModule::IncludeModule('intec.core'))
    return;
$APPLICATION->IncludeComponent(
    "bitrix:news.list",
    "",
    array(
        'IBLOCK_TYPE' => $arParams['IBLOCK_TYPE'],
        'IBLOCK_ID' => $arParams['IBLOCK_ID'],
        'NEWS_COUNT' => $arParams['ITEMS_LIMIT'],
        'SORT_BY1' => $arParams['SORT_BY1'],
        'SORT_ORDER1' =>  $arParams['SORT_ORDER1'],
        'SORT_BY2' => $arParams['SORT_BY2'],
        'SORT_ORDER2' =>$arParams['SORT_ORDER2'],
        'FILTER_NAME' => $arParams['FILTER_NAME'],
        'FIELD_CODE' => array(),
        'PROPERTY_CODE' => array(),
        'CHECK_DATES' => 'Y',
        'VIEW_DESKTOP' => $arParams['VIEW_DESKTOP'],
        'LINE_COUNT_DESKTOP' => $arParams['LINE_COUNT_DESKTOP'],
        'VIEW_MOBILE' => $arParams['VIEW_MOBILE'],
        'LINE_COUNT_MOBILE' => $arParams['LINE_COUNT_MOBILE'],
        'DETAIL_URL' => $arParams['DETAIL_URL'],
        'AJAX_MODE' => 'N',
        'AJAX_OPTION_JUMP' => 'N',
        'AJAX_OPTION_STYLE' => 'Y',
        'AJAX_OPTION_HISTORY' => 'N',
        'AJAX_OPTION_ADDITIONAL' => '',
        'CACHE_TYPE' => $arParams['CACHE_TYPE'],
        'CACHE_TIME' => $arParams['CACHE_TIME'],
        'CACHE_GROUPS' => $arParams['CACHE_GROUPS'],
        'CACHE_FILTER' => 'N',
        'PREVIEW_TRUNCATE_LEN' => null,
        'ACTIVE_DATE_FORMAT' => $arParams['DATE_FORMAT'],
        'SET_TITLE' => 'N',
        'SET_BROWSER_TITLE' => 'N',
        'SET_META_KEYWORDS' => 'N',
        'SET_META_DESCRIPTION' => 'N',
        'SET_LAST_MODIFIED' => 'N',
        'INCLUDE_IBLOCK_INTO_CHAIN' => 'N',
        'ADD_SECTIONS_CHAIN' => 'N',
        'HIDE_LINK_WHEN_NO_DETAIL' => 'N',
        'PARENT_SECTION' => null,
        'PARENT_SECTION_CODE' => null,
        'INCLUDE_SUBSECTIONS' => 'N',
        'STRICT_SECTION_CHECK' => 'N',
        'DISPLAY_TOP_PAGER' => 'N',
        'DISPLAY_BOTTOM_PAGER' => 'N',
        'PAGER_SHOW_ALWAYS' => 'N',
        'PAGER_DESC_NUMBERING' => 'N',
        'PAGER_DESC_NUMBERING_CACHE_TIME' => $arParams["CACHE_TIME"],
        'PAGER_SHOW_ALL' => 'N',
        'PAGER_BASE_LINK_ENABLE' => 'N',
        'SET_STATUS_404' => 'N',
        'SHOW_404' => 'N',
        'SHOW_TITLE' => $arParams['SHOW_TITLE'],
        'TITLE' => $arParams['TITLE'],
        'ALIGN_TITLE' => $arParams["ALIGN_TITLE"],
        'DISPLAY_DESCRIPTION' => $arParams["DISPLAY_DESCRIPTION"],
        "DESCRIPTION" => $arParams["DESCRIPTION"],
        "SHOW_DETAIL_LINK" => $arParams["SHOW_DETAIL_LINK"],
        "LIST_URL" => $arParams["LIST_URL"],
        'ROWS_COUNT' => $arParams["ROWS_COUNT"],
        "DETAIL_LINK_TEXT" => $arParams["DETAIL_LINK_TEXT"],
        "USE_CAROUSEL" => $arParams["USE_CAROUSEL"],
        "COLUMNS_COUNT" => $arParams['COLUMNS_COUNT'],
        "ALIGHT_HEADER" => $arParams["ALIGHT_HEADER"],
        "SETTINGS_USE" => $arParams['SETTINGS_USE'],
        "LAZYLOAD_USE" => $arParams['LAZYLOAD_USE'],
    ),
    $component
);?>