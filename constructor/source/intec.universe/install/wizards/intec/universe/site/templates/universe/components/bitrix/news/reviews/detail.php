<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

/**
 * @var CMain $APPLICATION
 * @var CBitrixComponent $component
 * @var array $arResult
 * @var array $arParams
 */

?>
<?php $APPLICATION->IncludeComponent(
    'bitrix:news.detail',
    '.default',
    array(
        'IBLOCK_TYPE' => $arParams['IBLOCK_TYPE'],
        'IBLOCK_ID' => $arParams['IBLOCK_ID'],
        'ELEMENT_ID' => $arResult['VARIABLES']['ELEMENT_ID'],
        'ELEMENT_CODE' => $arResult['VARIABLES']['ELEMENT_CODE'],
        'CHECK_DATES' => $arParams['CHECK_DATES'],
        'FIELD_CODE' => $arParams['DETAIL_FIELD_CODE'],
        'PROPERTY_CODE' => $arParams['DETAIL_PROPERTY_CODE'],
        'IBLOCK_URL' => $arResult['FOLDER'].$arResult['URL_TEMPLATES']['news'],
        'DETAIL_URL' => $arResult['FOLDER'].$arResult['URL_TEMPLATES']['section'],
        'CACHE_TYPE' => $arParams['CACHE_TYPE'],
        'CACHE_TIME' => $arParams['CACHE_TIME'],
        'CACHE_GROUPS' => $arParams['CACHE_GROUPS'],
        'SET_TITLE' => $arParams['SET_TITLE'],
        'SET_CANONICAL_URL' => $arParams['DETAIL_SET_CANONICAL_URL'],
        'SET_BROWSER_TITLE' => $arParams['SET_BROWSER_TITLE'],
        'BROWSER_TITLE' => $arParams['BROWSER_TITLE'],
        'SET_META_KEYWORDS' => $arParams['SET_META_KEYWORDS'],
        'META_KEYWORDS' => $arParams['META_KEYWORDS'],
        'SET_META_DESCRIPTION' => $arParams['SET_META_DESCRIPTION'],
        'META_DESCRIPTION' => $arParams['META_DESCRIPTION'],
        'SET_LAST_MODIFIED' => 'N',
        'INCLUDE_IBLOCK_INTO_CHAIN' => $arParams['INCLUDE_IBLOCK_INTO_CHAIN'],
        'ADD_SECTIONS_CHAIN' => $arParams['ADD_SECTIONS_CHAIN'],
        'ADD_ELEMENT_CHAIN' => $arParams['ADD_ELEMENT_CHAIN'],
        'ACTIVE_DATE_FORMAT' => $arParams['DETAIL_ACTIVE_DATE_FORMAT'],
        'USE_PERMISSIONS' => $arParams['USE_PERMISSIONS'],
        'STRICT_SECTION_CHECK' => 'N',
        'DISPLAY_DATE' => 'N',
        'DISPLAY_NAME' => 'N',
        'DISPLAY_PICTURE' => 'N',
        'DISPLAY_PREVIEW_TEXT' => 'N',
        'USE_SHARE' => 'N',
        'PAGER_TEMPLATE' => '.default',
        'DISPLAY_TOP_PAGER' => 'N',
        'DISPLAY_BOTTOM_PAGER' => 'N',
        'PAGER_TITLE' => '',
        'PAGER_SHOW_ALL' => 'N',
        'PAGER_BASE_LINK_ENABLE' => 'N',
        'SET_STATUS_404' => $arParams['SET_STATUS_404'],
        'SHOW_404' => $arParams['SHOW_404'],
        'MESSAGE_404' => $arParams['MESSAGE_404'],

        'PROPERTY_PERSON_NAME' => $arParams['PROPERTY_PERSON_NAME'],
        'PROPERTY_PERSON_POSITION' => $arParams['PROPERTY_PERSON_POSITION'],
        'PROPERTY_SITE_URL' => $arParams['PROPERTY_SITE_URL'],
        'PROPERTY_DOCUMENT' => $arParams['PROPERTY_DOCUMENT'],
        'PROPERTY_SERVICES' => $arParams['PROPERTY_SERVICES'],
        'PROPERTY_CASES' => $arParams['PROPERTY_CASES'],
        'PROPERTY_VIDEO' => $arParams['PROPERTY_VIDEO'],
        'TITLE_SHOW' => $arParams['DETAIL_TITLE_SHOW'],
        'DOCUMENT_SHOW' => $arParams['DETAIL_DOCUMENT_SHOW'],
        'SERVICES_SHOW' => $arParams['DETAIL_SERVICES_SHOW'],
        'SERVICES_IBLOCK_TYPE' => $arParams['SERVICES_IBLOCK_TYPE'],
        'SERVICES_IBLOCK_ID' => $arParams['SERVICES_IBLOCK_ID'],
        'SERVICES_SORT_BY' => $arParams['SERVICES_SORT_BY'],
        'SERVICES_SORT_ORDER' => $arParams['SERVICES_SORT_ORDER'],
        'SERVICES_LINK_MODE' => $arParams['SERVICES_LINK_MODE'],
        'SERVICES_PROPERTY_LINK' => $arParams['SERVICES_PROPERTY_LINK'],
        'CASES_SHOW' => $arParams['DETAIL_CASES_SHOW'],
        'CASES_IBLOCK_TYPE' => $arParams['CASES_IBLOCK_TYPE'],
        'CASES_IBLOCK_ID' => $arParams['CASES_IBLOCK_ID'],
        'CASES_LINK_MODE' => $arParams['CASES_LINK_MODE'],
        'CASES_PROPERTY_LINK' => $arParams['CASES_PROPERTY_LINK'],
        'VIDEO_SHOW' => $arParams['DETAIL_VIDEO_SHOW'],
        'VIDEO_IBLOCK_TYPE' => $arParams['VIDEO_IBLOCK_TYPE'],
        'VIDEO_IBLOCK_ID' => $arParams['VIDEO_IBLOCK_ID'],
        'VIDEO_PROPERTY_LINK' => $arParams['VIDEO_PROPERTY_LINK']
    ),
    $component
) ?>
