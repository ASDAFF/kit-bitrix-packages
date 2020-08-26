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
$crosssellId = $request->getPost('crosssellId');
$crosssellArray = $request->getPost('crosssellArray');
$currentCrosssell = $crosssellArray[$crosssellId];

if (!\Bitrix\Main\Loader::includeModule('sotbit.crosssell'))
    return;

\Bitrix\Main\Loader::includeModule('sotbit.origami');

global ${$params["FILTER_NAME"]};
${$params["FILTER_NAME"]} = $currentCrosssell['FILTER'];

$params["AJAX_MODE"] = "N";

//echo $includeJsCss;

$APPLICATION->IncludeComponent("bitrix:catalog.section",
    $params['SECTION_TEMPLATE'],
    array_merge($params, array("PAGE_ELEMENT_COUNT" => $currentCrosssell['PRODUCT_NUMBER'], "ELEMENT_SORT_FIELD" => $currentCrosssell["SORT_BY"], "ELEMENT_SORT_ORDER" => $currentCrosssell["SORT_ORDER"])),
    /*Array("CACHE_FILTER" => "Y",
        "CACHE_GROUPS" => "Y",
        "CACHE_TIME" => "36000000",
        "CACHE_TYPE" => "A",
        "DISPLAY_TOP_PAGER" => $params['DISPLAY_TOP_PAGER'],
        "DISPLAY_BOTTOM_PAGER" => $params['DISPLAY_BOTTOM_PAGER'],
        "SHOW_ALL_WO_SECTION" => "Y",
        "ELEMENT_ID" => array(),
        "ELEMENT_SORT_FIELD" => $arParams['ELEMENT_SORT_FIELD'],
        "ELEMENT_SORT_FIELD2" => $arParams['ELEMENT_SORT_FIELD2'],
        "ELEMENT_SORT_ORDER" => $arParams['ELEMENT_SORT_ORDER'],
        "ELEMENT_SORT_ORDER2" => $arParams['ELEMENT_SORT_ORDER2'],
        "FILTER_NAME" => 'arrFilter',
        "USE_FILTER" => 'Y',
        "IBLOCK_ID" => $params['IBLOCK_ID'],
        "INCLUDE_SUBSECTIONS" => "Y",
        "LABEL_PROP" => "-",
        "LINE_ELEMENT_COUNT" => $params['LINE_ELEMENT_COUNT'],
        "PAGE_ELEMENT_COUNT" => $currentCrosssell['PRODUCT_NUMBER'],
        "ADD_PROPERTIES_TO_BASKET" => $params['ADD_PROPERTIES_TO_BASKET'],
        "ADD_SECTIONS_CHAIN" => $params['ADD_SECTIONS_CHAIN'],
        "ADD_TO_BASKET_ACTION" => $params['ADD_TO_BASKET_ACTION'],
        "AJAX_MODE" => $params['AJAX_MODE'],
        "AJAX_OPTION_ADDITIONAL" => $params['AJAX_OPTION_ADDITIONAL'],
        "AJAX_OPTION_HISTORY" => $params['AJAX_OPTION_HISTORY'],
        "AJAX_OPTION_JUMP" => $params['AJAX_OPTION_JUMP'],
        "AJAX_OPTION_STYLE" => $params['AJAX_OPTION_STYLE'],
        "BACKGROUND_IMAGE" => $params['BACKGROUND_IMAGE'],
        "BASKET_URL" => $params['BASKET_URL'],
        "BROWSER_TITLE" => $params['BROWSER_TITLE'],
        "COMPATIBLE_MODE" => $params['COMPATIBLE_MODE'],
        "CONVERT_CURRENCY" => $params['CONVERT_CURRENCY'],
        "DETAIL_URL" => $params['DETAIL_URL'],
        "DISABLE_INIT_JS_IN_COMPONENT" => $params['DISABLE_INIT_JS_IN_COMPONENT'],
        "DISPLAY_COMPARE" => $params['DISPLAY_COMPARE'],
        "ENLARGE_PRODUCT" => $params['ENLARGE_PRODUCT'],
        "HIDE_NOT_AVAILABLE" => $params['HIDE_NOT_AVAILABLE'],
        "HIDE_NOT_AVAILABLE_OFFERS" => $params['HIDE_NOT_AVAILABLE_OFFERS'],
        "IBLOCK_TYPE" => $params['IBLOCK_TYPE'],
        "LAZY_LOAD" => $params['LAZY_LOAD'],
        "LOAD_ON_SCROLL" => $params['LOAD_ON_SCROLL'],
        "MESSAGE_404" => $params['MESSAGE_404'],
        "MESS_BTN_ADD_TO_BASKET" => $params['MESS_BTN_ADD_TO_BASKET'],
        "MESS_BTN_BUY" => $params['MESS_BTN_BUY'],
        "MESS_BTN_DETAIL" => $params['MESS_BTN_DETAIL'],
        "MESS_BTN_SUBSCRIBE" => $params['MESS_BTN_SUBSCRIBE'],
        "MESS_NOT_AVAILABLE" => $params['MESS_NOT_AVAILABLE'],
        "META_DESCRIPTION" => $params['META_DESCRIPTION'],
        "META_KEYWORDS" => $params['META_KEYWORDS'],
        "OFFERS_LIMIT" => $params['OFFERS_LIMIT'],
        "PAGER_BASE_LINK_ENABLE" => $params['PAGER_BASE_LINK_ENABLE'],
        "PAGER_DESC_NUMBERING" => $params['PAGER_DESC_NUMBERING'],
        "PAGER_DESC_NUMBERING_CACHE_TIME" => $params['PAGER_DESC_NUMBERING_CACHE_TIME'],
        "PAGER_SHOW_ALL" => $params['PAGER_SHOW_ALL'],
        "PAGER_SHOW_ALWAYS" => $params['PAGER_SHOW_ALWAYS'],
        "PAGER_TEMPLATE" => $params['PAGER_TEMPLATE'],
        "PAGER_TITLE" => $params['PAGER_TITLE'],
        "PARTIAL_PRODUCT_PROPERTIES" => $params['PARTIAL_PRODUCT_PROPERTIES'],
        "PRICE_CODE" => $params['PRICE_CODE'],
        "PRICE_VAT_INCLUDE" => $params['PRICE_VAT_INCLUDE'],
        "PRODUCT_BLOCKS_ORDER" => $params['PRODUCT_BLOCKS_ORDER'],
        "PRODUCT_ID_VARIABLE" => $params['PRODUCT_ID_VARIABLE'],
        "PRODUCT_PROPERTIES" => $params['PRODUCT_PROPERTIES'],
        "PRODUCT_PROPS_VARIABLE" => $params['PRODUCT_PROPS_VARIABLE'],
        "PRODUCT_QUANTITY_VARIABLE" => $params['PRODUCT_QUANTITY_VARIABLE'],
        "PRODUCT_ROW_VARIANTS" => $params['PRODUCT_ROW_VARIANTS'],
        "PRODUCT_SUBSCRIPTION" => $params['PRODUCT_SUBSCRIPTION'],
        "PROPERTY_CODE" => $params['PROPERTY_CODE'],
        "RCM_PROD_ID" => $params['RCM_PROD_ID'],
        "RCM_TYPE" => $params['RCM_TYPE'],
        "SECTION_ID" => array(),
        "SEF_MODE" => $params['SEF_MODE'],
        "SET_BROWSER_TITLE" => $params['SET_BROWSER_TITLE'],
        "SET_LAST_MODIFIED" => $params['PRODUCT_PROPS_VARIABLE'],
        "SET_META_DESCRIPTION" => $params['SET_META_DESCRIPTION'],
        "SET_META_KEYWORDS" => $params['SET_META_KEYWORDS'],
        "SET_STATUS_404" => $params['SET_STATUS_404'],
        "SET_TITLE" => $params['SET_TITLE'],
        "SHOW_404" => $params['SHOW_404'],
        "SHOW_CLOSE_POPUP" => $params['SHOW_CLOSE_POPUP'],
        "SHOW_DISCOUNT_PERCENT" => $params['SHOW_DISCOUNT_PERCENT'],
        "SHOW_FROM_SECTION" => $params['SHOW_FROM_SECTION'],
        "SHOW_MAX_QUANTITY" => $params['SHOW_MAX_QUANTITY'],
        "SHOW_OLD_PRICE" => $params['SHOW_OLD_PRICE'],
        "SHOW_PRICE_COUNT" => $params['SHOW_PRICE_COUNT'],
        "SHOW_SLIDER" => $params['SHOW_SLIDER'],
        "TEMPLATE_THEME" => $params['TEMPLATE_THEME'],
        "USE_ENHANCED_ECOMMERCE" => $params['USE_ENHANCED_ECOMMERCE'],
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
    ),*/
    false
);

die();