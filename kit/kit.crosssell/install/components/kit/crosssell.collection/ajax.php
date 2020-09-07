<?
define('STOP_STATISTICS', true);
define('NOT_CHECK_PERMISSIONS', true);

$siteId = isset($_REQUEST['siteId']) && is_string($_REQUEST['siteId']) ? $_REQUEST['siteId'] : '';
$siteId = substr(preg_replace('/[^a-z0-9_]/i', '', $siteId), 0, 2);
if (!empty($siteId) && is_string($siteId))
{
    define('SITE_ID', $siteId);
}

require_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_before.php');

$request = \Bitrix\Main\Application::getInstance()->getContext()->getRequest();
$request->addFilter(new \Bitrix\Main\Web\PostDecodeFilter);
$params = $request->getPost('params');
$collectionId = $request->getPost('collectionId');

if (!\Bitrix\Main\Loader::includeModule('kit.crosssell'))
    return;

$APPLICATION->IncludeComponent(
    "kit:crosssell.collection.list",
    $params["COLLECTION_LIST_TEMPLATE"],
    array_merge($params, array("COLLECTION_ID" => $collectionId)),
    /*Array(
        "ACTION_VARIABLE" => $params['ACTION_VARIABLE'],
        "ADD_PROPERTIES_TO_BASKET" => $params['ADD_PROPERTIES_TO_BASKET'],
        "ADD_SECTIONS_CHAIN" => $params['ADD_SECTIONS_CHAIN'],
        "AJAX_MODE" => $params['AJAX_MODE'],
        "AJAX_OPTION_ADDITIONAL" => $params['AJAX_OPTION_ADDITIONAL'],
        "AJAX_OPTION_HISTORY" => $params['AJAX_OPTION_HISTORY'],
        "AJAX_OPTION_JUMP" => $params['AJAX_OPTION_JUMP'],
        "AJAX_OPTION_STYLE" => $params['AJAX_OPTION_STYLE'],
        "BACKGROUND_IMAGE" => $params['BACKGROUND_IMAGE'],
        "BASKET_URL" => $params['BASKET_URL'],
        "BROWSER_TITLE" => $params['BROWSER_TITLE'],
        "CACHE_FILTER" => $params['CACHE_FILTER'],
        "CACHE_GROUPS" => $params['CACHE_GROUPS'],
        "CACHE_TIME" => $params['CACHE_TIME'],
        "CACHE_TYPE" => $params['CACHE_TYPE'],
        "COLLECTION_ID" => $collectionId,
        "COMPATIBLE_MODE" => $params['COMPATIBLE_MODE'],
        "CONVERT_CURRENCY" => $params['CONVERT_CURRENCY'],
        "DETAIL_URL" => $params['DETAIL_URL'],
        "DISABLE_INIT_JS_IN_COMPONENT" => $params['DISABLE_INIT_JS_IN_COMPONENT'],
        "DISPLAY_BOTTOM_PAGER" => $params['DISPLAY_BOTTOM_PAGER'],
        "DISPLAY_COMPARE" => $params['DISPLAY_COMPARE'],
        "DISPLAY_TOP_PAGER" => $params['DISPLAY_TOP_PAGER'],
        "ELEMENT_SORT_FIELD" => $params['ELEMENT_SORT_FIELD'],
        "ELEMENT_SORT_FIELD2" => $params['ELEMENT_SORT_FIELD2'],
        "ELEMENT_SORT_ORDER" => $params['ELEMENT_SORT_ORDER'],
        "ELEMENT_SORT_ORDER2" => $params['ELEMENT_SORT_ORDER2'],
        "FILTER_NAME" => $params['FILTER_NAME'],
        "FROM_COMPLEX" => $params['FROM_COMPLEX'],
        "HIDE_NOT_AVAILABLE" => $params['HIDE_NOT_AVAILABLE'],
        "HIDE_NOT_AVAILABLE_OFFERS" => $params['HIDE_NOT_AVAILABLE_OFFERS'],
        "IBLOCK_ID" => $params['IBLOCK_ID'],
        "IBLOCK_TYPE" => $params['IBLOCK_TYPE'],
        "INCLUDE_SUBSECTIONS" => $params['INCLUDE_SUBSECTIONS'],
        "LINE_ELEMENT_COUNT" => $params['LINE_ELEMENT_COUNT'],
        "MESSAGE_404" => $params['MESSAGE_404'],
        "META_DESCRIPTION" => $params['META_DESCRIPTION'],
        "META_KEYWORDS" => $params['META_KEYWORDS'],
        "OFFERS_CART_PROPERTIES" => $params['OFFERS_CART_PROPERTIES'],
        "OFFERS_FIELD_CODE" => $params['OFFERS_FIELD_CODE'],
        "OFFERS_LIMIT" => $params['OFFERS_LIMIT'],
        "OFFERS_PROPERTY_CODE" => $params['OFFERS_PROPERTY_CODE'],
        "OFFERS_SORT_FIELD" => $params['OFFERS_SORT_FIELD'],
        "OFFERS_SORT_FIELD2" => $params['OFFERS_SORT_FIELD2'],
        "OFFERS_SORT_ORDER" => $params['OFFERS_SORT_ORDER'],
        "OFFERS_SORT_ORDER2" => $params['OFFERS_SORT_ORDER2'],
        "PAGER_BASE_LINK_ENABLE" => $params['PAGER_BASE_LINK_ENABLE'],
        "PAGER_DESC_NUMBERING" => $params['PAGER_DESC_NUMBERING'],
        "PAGER_DESC_NUMBERING_CACHE_TIME" => $params['PAGER_DESC_NUMBERING_CACHE_TIME'],
        "PAGER_SHOW_ALL" => $params['PAGER_SHOW_ALL'],
        "PAGER_SHOW_ALWAYS" => $params['PAGER_SHOW_ALWAYS'],
        "PAGER_TEMPLATE" => $params['PAGER_TEMPLATE'],
        "PAGER_TITLE" => $params['PAGER_TITLE'],
        "PARTIAL_PRODUCT_PROPERTIES" => $params['PARTIAL_PRODUCT_PROPERTIES'],
        "PRICE_CODE" => $priceCode,
        "PRICE_VAT_INCLUDE" => $params['PRICE_VAT_INCLUDE'],
        "PRODUCT_ID_VARIABLE" => $params['PRODUCT_ID_VARIABLE'],
        "PRODUCT_PER_TAB" => $params['PRODUCT_PER_TAB'],
        "PRODUCT_PROPERTIES" => $params['PRODUCT_PROPERTIES'],
        "PRODUCT_PROPS_VARIABLE" => $params['PRODUCT_PROPS_VARIABLE'],
        "PRODUCT_QUANTITY_VARIABLE" => $params['PRODUCT_QUANTITY_VARIABLE'],
        "SECTION_ID" => $params['SECTION_ID'],
        "SECTION_TEMPLATE" => $params['SECTION_TEMPLATE'],
        "SECTION_URL" => $params['SECTION_URL'],
        "SEF_MODE" => $params['SEF_MODE'],
        "SET_BROWSER_TITLE" => $params['SET_BROWSER_TITLE'],
        "SET_LAST_MODIFIED" => $params['SET_LAST_MODIFIED'],
        "SET_META_DESCRIPTION" => $params['SET_META_DESCRIPTION'],
        "SET_META_KEYWORDS" => $params['SET_META_KEYWORDS'],
        "SET_STATUS_404" => $params['SET_STATUS_404'],
        "SET_TITLE" => $params['SET_TITLE'],
        "SHOW_404" => $params['SHOW_404'],
        "SHOW_PRICE_COUNT" => $params['SHOW_PRICE_COUNT'],
        "SHOW_SLIDER" => $params['SHOW_SLIDER'],
        "USE_MAIN_ELEMENT_SECTION" => $params['USE_MAIN_ELEMENT_SECTION'],
        "USE_PRICE_COUNT" => $params['USE_PRICE_COUNT'],
        "USE_PRODUCT_QUANTITY" => $params['USE_PRODUCT_QUANTITY'],

        'OFFER_TREE_PROPS' => $params['OFFER_TREE_PROPS'],
        "PRODUCT_DISPLAY_MODE" => $params['PRODUCT_DISPLAY_MODE'],
        'ADD_PICT_PROP' => $params['ADD_PICT_PROP'],
        'OFFER_ADD_PICT_PROP' => $params['OFFER_ADD_PICT_PROP'],
        'USE_VOTE_RATING' => $params['USE_VOTE_RATING'],
        'COMPARE_PATH' => $params['COMPARE_PATH'],
        'COMPARE_NAME' => $params['COMPARE_PATH'],
        'USE_COMPARE_LIST' => $params['USE_COMPARE_LIST'],
    )*/
    false
);
die();