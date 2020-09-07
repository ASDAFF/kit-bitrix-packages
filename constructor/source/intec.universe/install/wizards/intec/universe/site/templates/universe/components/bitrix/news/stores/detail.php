<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die() ?>
<?php

use Bitrix\Main\Loader;

/**
 * @var array $arParams
 * @var array $arResult
 * @global CMain $APPLICATION
 * @global CUser $USER
 * @global CDatabase $DB
 * @var CBitrixComponentTemplate $this
 * @var string $templateName
 * @var string $templateFile
 * @var string $templateFolder
 * @var string $componentPath
 * @var CBitrixComponent $component
 */

$this->setFrameMode(true);
$arElement = null;

if(Loader::includeModule('catalog'))
    $arElement = CCatalogStore::GetList(
        array('ID' => 'ASC'),
        array('ID' => $arResult['VARIABLES']['ELEMENT_ID']),
        false,
        false,
        array("ID")
    )->Fetch();

if ($arElement) {
    $APPLICATION->IncludeComponent(
        'bitrix:catalog.store.detail',
        'template.1',
        array(
            'CACHE_TIME' => $arParams['CACHE_TIME'],
            'CACHE_TYPE' => $arParams['CACHE_TYPE'],
            'STORE' => $arElement['ID'],
            'PATH_TO_LISTSTORES' => $arResult['PATH_TO_LISTSTORES'],
            'SET_TITLE' => $arParams['SET_TITLE'],
            'MAP_TYPE' => $arParams['MAP_VENDOR'] === 'yandex' ? 0 : 1,
            'MAP_ID' => $arParams['MAP_ID']
        ),
        $component
    );
} else {
    $iElementId = $APPLICATION->IncludeComponent(
        'bitrix:news.detail',
        'store.1',
        Array(
            'IBLOCK_TYPE' => $arParams['IBLOCK_TYPE'],
            'IBLOCK_ID' => $arParams['IBLOCK_ID'],
            'IBLOCK_TYPE_SERVICES' => $arParams['IBLOCK_TYPE_SERVICES'],
            'IBLOCK_ID_SERVICES' => $arParams['IBLOCK_ID_SERVICES'],
            'IBLOCK_TYPE_REVIEWS' => $arParams['IBLOCK_TYPE_REVIEWS'],
            'IBLOCK_ID_REVIEWS' => $arParams['IBLOCK_ID_REVIEWS'],
            'FIELD_CODE' => $arParams['DETAIL_FIELD_CODE'],
            'PROPERTY_CODE' => $arParams['DETAIL_PROPERTY_CODE'],
            'DETAIL_URL' => $arResult['FOLDER'].$arResult['URL_TEMPLATES']['detail'],
            'SECTION_URL' => $arResult['FOLDER'].$arResult['URL_TEMPLATES']['section'],
            'IBLOCK_URL' => $arResult['FOLDER'].$arResult['URL_TEMPLATES']['news'],
            'META_KEYWORDS' => $arParams['META_KEYWORDS'],
            'META_DESCRIPTION' => $arParams['META_DESCRIPTION'],
            'BROWSER_TITLE' => $arParams['BROWSER_TITLE'],
            'SET_CANONICAL_URL' => $arParams['DETAIL_SET_CANONICAL_URL'],
            'DISPLAY_PANEL' => $arParams['DISPLAY_PANEL'],
            'SET_LAST_MODIFIED' => $arParams['SET_LAST_MODIFIED'],
            'SET_TITLE' => $arParams['SET_TITLE'],
            'MESSAGE_404' => $arParams['MESSAGE_404'],
            'SET_STATUS_404' => $arParams['SET_STATUS_404'],
            'SHOW_404' => $arParams['SHOW_404'],
            'FILE_404' => $arParams['FILE_404'],
            'INCLUDE_IBLOCK_INTO_CHAIN' => $arParams['INCLUDE_IBLOCK_INTO_CHAIN'],
            'ADD_SECTIONS_CHAIN' => $arParams['ADD_SECTIONS_CHAIN'],
            'ACTIVE_DATE_FORMAT' => $arParams['DETAIL_ACTIVE_DATE_FORMAT'],
            'CACHE_TYPE' => $arParams['CACHE_TYPE'],
            'CACHE_TIME' => $arParams['CACHE_TIME'],
            'CACHE_GROUPS' => $arParams['CACHE_GROUPS'],
            'CACHE_FILTER' => $arParams['CACHE_FILTER'],
            'USE_PERMISSIONS' => $arParams['USE_PERMISSIONS'],
            'GROUP_PERMISSIONS' => $arParams['GROUP_PERMISSIONS'],
            'DISPLAY_TOP_PAGER' => $arParams['DETAIL_DISPLAY_TOP_PAGER'],
            'DISPLAY_BOTTOM_PAGER' => $arParams['DETAIL_DISPLAY_BOTTOM_PAGER'],
            'PAGER_TITLE' => $arParams['DETAIL_PAGER_TITLE'],
            'PAGER_SHOW_ALWAYS' => 'N',
            'PAGER_TEMPLATE' => $arParams['DETAIL_PAGER_TEMPLATE'],
            'PAGER_SHOW_ALL' => $arParams['DETAIL_PAGER_SHOW_ALL'],
            'CHECK_DATES' => $arParams['CHECK_DATES'],
            'ELEMENT_ID' => $arResult['VARIABLES']['ELEMENT_ID'],
            'ELEMENT_CODE' => $arResult['VARIABLES']['ELEMENT_CODE'],
            'ADD_ELEMENT_CHAIN' => (isset($arParams['ADD_ELEMENT_CHAIN']) ? $arParams['ADD_ELEMENT_CHAIN'] : ''),

            "MAP_VENDOR" => $arParams['MAP_VENDOR'],
            "PROPERTY_MAP" => $arParams['PROPERTY_MAP'],
            "API_KEY_MAP" => $arParams['API_KEY_MAP'],
            "PROPERTY_ADDRESS" => $arParams['PROPERTY_ADDRESS'],
            "PROPERTY_PHONE" => $arParams['PROPERTY_PHONE'],
            "PROPERTY_EMAIL" => $arParams['PROPERTY_EMAIL'],
            "PROPERTY_SCHEDULE" => $arParams['PROPERTY_SCHEDULE'],
            "SHOW_MAP" => $arParams['SHOW_MAP']
        ),
        $component
    );
}?>