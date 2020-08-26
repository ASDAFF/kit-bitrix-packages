<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)
	die();

use Bitrix\Main\Loader;

Loader::IncludeModule('kit.origami');
Loader::IncludeModule('fileman');

use \Kit\Origami\Config\Option;
$arIMAGE = CFile::MakeFileArray(WIZARD_SITE_DIR.'include/kit_origami/images/footer_bg.png');
$arIMAGE["MODULE_ID"] = "main";
$footerBg = CFile::SaveFile($arIMAGE, 'footer');


Option::set('VK','http://vk.com/kit',WIZARD_SITE_ID);
Option::set('INST','https://www.instagram.com/kit_insta/',WIZARD_SITE_ID);
Option::set('YOUTUBE','https://www.youtube.com/channel/UCljk41PuLLNRkcrxPOkj4Vg',WIZARD_SITE_ID);
Option::set('OK','https://ok.ru/feed',WIZARD_SITE_ID);
Option::set('FB','https://www.facebook.com/kit',WIZARD_SITE_ID);
Option::set('TELEGA','https://telegram.org/',WIZARD_SITE_ID);
Option::set('TW','https://twitter.com/kit',WIZARD_SITE_ID);
Option::set('GOOGLE','https://plus.google.com/?hl',WIZARD_SITE_ID);
Option::set('USE_REGIONS','Y',WIZARD_SITE_ID);
Option::set('REGION_TEMPLATE','origami_combine',WIZARD_SITE_ID);
Option::set('COLOR','TSVET',WIZARD_SITE_ID);
Option::set('SERVICES','SERVIVCES',WIZARD_SITE_ID);
Option::set('ACCESS_TOKEN','4028561690.1677ed0.b43f21bcd35b452c9b8cf7bce8142942',WIZARD_SITE_ID);
Option::set('ORDER_TEMPLATE','kit_order_make_classic',WIZARD_SITE_ID);
Option::set('CONTACTS','5',WIZARD_SITE_ID);
Option::set('FOOTER','3',WIZARD_SITE_ID);
Option::set('FOOTER_IMG', $footerBg,WIZARD_SITE_ID);
Option::set('FOOTER_CALL', 'Y',WIZARD_SITE_ID);
/******************Add footer image*************************/
Option::set('HEADER','4',WIZARD_SITE_ID);
Option::set('COLOR_BASE','#fb0040',WIZARD_SITE_ID);
Option::set('FONT_BASE','Ubuntu',WIZARD_SITE_ID);
Option::set('WIDTH','1344px',WIZARD_SITE_ID);
Option::set('PROMOTION_LIST_TEMPLATE','horizontal',WIZARD_SITE_ID);
Option::set('CONFIDENTIAL_PAGE',WIZARD_SITE_DIR.'help/confidentiality/',WIZARD_SITE_ID);
Option::set('FRONT_CHANGE','Y',WIZARD_SITE_ID);
Option::set('LOGO',WIZARD_SITE_DIR.'include/kit_origami/images/logo.png',WIZARD_SITE_ID);
Option::set('FONT_BASE_SIZE','14px',WIZARD_SITE_ID);
Option::set('HOVER_EFFECT','a:2:{i:0;s:12:"hover-square";i:1;s:10:"hover-zoom";}',WIZARD_SITE_ID);

Option::set('MENU_SIDE','left',WIZARD_SITE_ID);
Option::set('HEADER_CALL','Y',WIZARD_SITE_ID);
Option::set('BASKET_PAGE',WIZARD_SITE_DIR.'personal/cart/',WIZARD_SITE_ID);
Option::set('ORDER_PAGE',WIZARD_SITE_DIR.'personal/order/make/',WIZARD_SITE_ID);
Option::set('COMPARE_PAGE',WIZARD_SITE_DIR.'catalog/compare/',WIZARD_SITE_ID);
Option::set('PERSONAL_PAGE',WIZARD_SITE_DIR.'personal/',WIZARD_SITE_ID);
Option::set('PERSONAL_ORDER_PAGE',WIZARD_SITE_DIR.'personal/orders/',WIZARD_SITE_ID);
Option::set('PAYMENT_PAGE',WIZARD_SITE_DIR.'personal/order/payment/',WIZARD_SITE_ID);
Option::set('CAPTCHA','NO',WIZARD_SITE_ID);
Option::set('MASK','+7(999)999-99-99',WIZARD_SITE_ID);

Option::set('SECTION_ROOT_TEMPLATE','sections_4',WIZARD_SITE_ID);
Option::set('FILTER_TEMPLATE','VERTICAL',WIZARD_SITE_ID);
Option::set('FILTER_MODE','AJAX_MODE',WIZARD_SITE_ID);
Option::set('PROP_FILTER_MODE','link',WIZARD_SITE_ID);
Option::set('PROP_COLOR_MODE','color',WIZARD_SITE_ID);
Option::set('QUICK_VIEW','Y',WIZARD_SITE_ID);
Option::set('CNT_IN_ROW','4',WIZARD_SITE_ID);
Option::set('SHOW_BUY_BUTTON','Y',WIZARD_SITE_ID);
Option::set('PAGINATION','origami_both',WIZARD_SITE_ID);
Option::set('SHOW_RECOMMENDATION','Y',WIZARD_SITE_ID);
Option::set('SHOW_VIEWED','Y',WIZARD_SITE_ID);
Option::set('HIDE_NOT_AVAILABLE','L',WIZARD_SITE_ID);
Option::set('DETAIL_TEMPLATE','',WIZARD_SITE_ID);
Option::set('SHOW_COMPARE','Y',WIZARD_SITE_ID);
Option::set('RESIZE_WIDTH_SMALL','80',WIZARD_SITE_ID);
Option::set('RESIZE_HEIGHT_SMALL','80',WIZARD_SITE_ID);
Option::set('RESIZE_WIDTH_MEDIUM','180',WIZARD_SITE_ID);
Option::set('RESIZE_HEIGHT_MEDIUM','235',WIZARD_SITE_ID);

Option::set('OFFER_LANDING','Y',WIZARD_SITE_ID);
Option::set('OFFER_LANDING_404','404',WIZARD_SITE_ID);

Option::set('TIMER_PROMOTIONS', 'Y', WIZARD_SITE_ID);
Option::set('RESIZE_TYPE','1',WIZARD_SITE_ID);
Option::set('ACCESS_TOKEN', '4028561690.1677ed0.b43f21bcd35b452c9b8cf7bce8142942', WIZARD_SITE_ID);
Option::set('SECTION_DESCRIPTION','ABOVE',WIZARD_SITE_ID);
Option::set('SECTION_DESCRIPTION_TOP','SECTION_DESC',WIZARD_SITE_ID);
Option::set('SECTION_DESCRIPTION_BOTTOM','SECTION_DESC',WIZARD_SITE_ID);
Option::set('FAVICON',WIZARD_SITE_DIR.'favicon.ico',WIZARD_SITE_ID);
Option::set('ARTICUL','CML2_ARTICLE',WIZARD_SITE_ID);
Option::set('ARTICUL_OFFER','CML2_ARTICLE',WIZARD_SITE_ID);
Option::set('PROP_COLOR_TYPE_SECTION','color_image',WIZARD_SITE_ID);
Option::set('LABEL_PROPS','a:3:{i:0;s:4:"KHIT";i:1;s:7:"NOVINKA";i:2;s:11:"RASPRODAZHA";}',WIZARD_SITE_ID);
Option::set('LABEL_PROPS_MAIN','a:3:{i:0;s:4:"KHIT";i:1;s:7:"NOVINKA";i:2;s:11:"RASPRODAZHA";}',WIZARD_SITE_ID);
Option::set('DETAIL_ADD_PROPS_','a:5:{i:0;s:17:"CML2_MANUFACTURER";i:1;s:17:"GABARITY_SHKHVKHD";i:2;s:15:"GABARITY_SHXGXV";i:3;s:14:"RAZMERY_SHXVXT";i:4;s:3:"TIP";}',WIZARD_SITE_ID);
Option::set('SITE_BUILDER','Y',WIZARD_SITE_ID);
Option::set('SEO_LINK_MODE','MULTIPLE_LEVEL',WIZARD_SITE_ID);
Option::set('SHOW_STOCK_MODE','Y',WIZARD_SITE_ID);
Option::set('LEAD_CAPTURE_FORM','Y',WIZARD_SITE_ID);
Option::set('SKU_DISPLAY_TYPE','ENUMERATION',WIZARD_SITE_ID);
Option::set('DETAIL_PICTURE_DISPLAY_TYPE','popup',WIZARD_SITE_ID);
Option::set('DROPDOWN_SIDE_MENU_VIEW','SIDE',WIZARD_SITE_ID);
Option::set('RESIZE_TYPE_','1',WIZARD_SITE_ID);
Option::set('RESIZE_WIDTH_SMALL_','80',WIZARD_SITE_ID);
Option::set('RESIZE_HEIGHT_SMALL_','80',WIZARD_SITE_ID);
Option::set('RESIZE_WIDTH_MEDIUM_','400',WIZARD_SITE_ID);
Option::set('RESIZE_HEIGHT_MEDIUM_','400',WIZARD_SITE_ID);
Option::set('RESIZE_WIDTH_BIG_','2000',WIZARD_SITE_ID);
Option::set('RESIZE_HEIGHT_BIG_','2000',WIZARD_SITE_ID);
Option::set('SHOW_BUY_OC_','Y',WIZARD_SITE_ID);
Option::set('SHOW_PREVIEW_DELIVERY_','Y',WIZARD_SITE_ID);
Option::set('PROP_DISPLAY_MODE_','border',WIZARD_SITE_ID);
Option::set('PROP_FILTER_MODE_','link',WIZARD_SITE_ID);
Option::set('PROP_COLOR_TYPE_ELEMENT_','color_image',WIZARD_SITE_ID);
Option::set('SHOW_FOUND_CHEAPER_','Y',WIZARD_SITE_ID);
Option::set('SHOW_WANT_GIFT_','Y',WIZARD_SITE_ID);
Option::set('SHOW_CHECK_STOCK_','Y',WIZARD_SITE_ID);
Option::set('SHOW_ZOOM_','Y',WIZARD_SITE_ID);
Option::set('PROPERTY_GROUPER_','NO',WIZARD_SITE_ID);
Option::set('ACTIVE_TAB_DESCRIPTION_','Y',WIZARD_SITE_ID);
Option::set('ACTIVE_TAB_PROPERTIES_','Y',WIZARD_SITE_ID);
Option::set('ACTIVE_TAB_DELIVERY_','Y',WIZARD_SITE_ID);
Option::set('ACTIVE_TAB_AVAILABLE_','Y',WIZARD_SITE_ID);
Option::set('ACTIVE_TAB_COMMENTS_','Y',WIZARD_SITE_ID);
Option::set('ACTIVE_TAB_VIDEO_','Y',WIZARD_SITE_ID);
Option::set('TABS_','a:7:{i:0;s:11:"DESCRIPTION";i:1;s:10:"PROPERTIES";i:2;s:9:"AVAILABLE";i:3;s:8:"DELIVERY";i:4;s:8:"COMMENTS";i:5;s:5:"VIDEO";i:6;s:4:"DOCS";}',WIZARD_SITE_ID);
Option::set('SHOW_CROSSSELL_','Y',WIZARD_SITE_ID);
Option::set('ANALOG_PROP_','ANALOG_PRODUCTS',WIZARD_SITE_ID);
Option::set('SHOW_RECOMMENDATION_','Y',WIZARD_SITE_ID);
Option::set('SHOW_BUY_WITH_','Y',WIZARD_SITE_ID);
Option::set('SHOW_BESTSELLER_','Y',WIZARD_SITE_ID);
Option::set('SHOW_SECTION_POPULAR_','Y',WIZARD_SITE_ID);
Option::set('SHOW_VIEWED_','Y',WIZARD_SITE_ID);
Option::set('SHOW_PRICE_','Y',WIZARD_SITE_ID);
Option::set('PROP_VIDEO_','VIDEO',WIZARD_SITE_ID);
Option::set('PROP_TAB_VIDEO_','VIDEO_CONTENT',WIZARD_SITE_ID);
Option::set('IBLOCK_TYPE_BLOG','kit_origami_content',WIZARD_SITE_ID);
//Option::set('IBLOCK_ID_BLOG','1',WIZARD_SITE_ID);
Option::set('SHOW_TABS_BITRIX_BLOCKS_','Y',WIZARD_SITE_ID);
//
//FAQ
Option::set('IBLOCK_TYPE_FAQ','kit_origami_content',WIZARD_SITE_ID);
Option::set('IBLOCK_ID_FAQ','11',WIZARD_SITE_ID);

//**FAQ
//NO TABS
Option::set('TABS_NO_TABS','a:7:{i:0;s:11:"DESCRIPTION";i:1;s:10:"PROPERTIES";i:2;s:9:"AVAILABLE";i:3;s:8:"DELIVERY";i:4;s:8:"COMMENTS";i:5;s:5:"VIDEO";i:6;s:4:"DOCS";}',WIZARD_SITE_ID);
Option::set('PROP_TAB_VIDEO_NO_TABS','VIDEO_CONTENT',WIZARD_SITE_ID);
Option::set('PROP_TAB_DOCS_NO_TABS','DOCUMENTS',WIZARD_SITE_ID);
Option::set('DETAIL_ADD_PROPS_NO_TABS','a:5:{i:0;s:17:"CML2_MANUFACTURER";i:1;s:17:"GABARITY_SHKHVKHD";i:2;s:15:"GABARITY_SHXGXV";i:3;s:14:"RAZMERY_SHXVXT";i:4;s:3:"TIP";}',WIZARD_SITE_ID);
Option::set('RESIZE_TYPE_NO_TABS','1',WIZARD_SITE_ID);
Option::set('RESIZE_WIDTH_SMALL_NO_TABS','80',WIZARD_SITE_ID);
Option::set('RESIZE_HEIGHT_SMALL_NO_TABS','80',WIZARD_SITE_ID);
Option::set('RESIZE_WIDTH_MEDIUM_NO_TABS','400',WIZARD_SITE_ID);
Option::set('RESIZE_HEIGHT_MEDIUM_NO_TABS','400',WIZARD_SITE_ID);
Option::set('RESIZE_WIDTH_BIG_NO_TABS','2000',WIZARD_SITE_ID);
Option::set('RESIZE_HEIGHT_BIG_NO_TABS','2000',WIZARD_SITE_ID);
Option::set('SKU_TYPE_NO_TABS','ENUMERATION',WIZARD_SITE_ID);
Option::set('PROP_FILTER_MODE_NO_TABS','link',WIZARD_SITE_ID);
Option::set('PROPERTY_GROUPER_NO_TABS','NO',WIZARD_SITE_ID);
Option::set('PROP_VIDEO_NO_TABS','VIDEO',WIZARD_SITE_ID);
Option::set('SHOW_BUY_OC_NO_TABS','Y',WIZARD_SITE_ID);
Option::set('SHOW_PREVIEW_DELIVERY_NO_TABS','Y',WIZARD_SITE_ID);
Option::set('PROP_DISPLAY_MODE_NO_TABS','border',WIZARD_SITE_ID);
Option::set('PROP_COLOR_TYPE_ELEMENT_NO_TABS','color_image',WIZARD_SITE_ID);

Option::set('SHOW_FOUND_CHEAPER_NO_TABS','Y',WIZARD_SITE_ID);
Option::set('SHOW_WANT_GIFT_NO_TABS','Y',WIZARD_SITE_ID);
Option::set('SHOW_CHECK_STOCK_NO_TABS','Y',WIZARD_SITE_ID);
Option::set('SHOW_ZOOM_NO_TABS','Y',WIZARD_SITE_ID);
Option::set('SHOW_CROSSSELL_NO_TABS','Y',WIZARD_SITE_ID);
Option::set('SHOW_PRICE_NO_TABS','Y',WIZARD_SITE_ID);

Option::set('SHOW_TABS_BITRIX_BLOCKS_NO_TABS','N',WIZARD_SITE_ID);
Option::set('SHOW_RECOMMENDATION_NO_TABS','Y',WIZARD_SITE_ID);
Option::set('SHOW_BUY_WITH_NO_TABS','Y',WIZARD_SITE_ID);
Option::set('SHOW_BESTSELLER_NO_TABS','Y',WIZARD_SITE_ID);
Option::set('SHOW_VIEWED_NO_TABS','Y',WIZARD_SITE_ID);
//**NO TABS

Option::set('IBLOCK_TYPE_NEWS','kit_origami_content',WIZARD_SITE_ID);
//Option::set('IBLOCK_ID_NEWS','2',WIZARD_SITE_ID);
Option::set('IBLOCK_TYPE_BRANDS','kit_origami_content',WIZARD_SITE_ID);
//Option::set('IBLOCK_ID_BRANDS','5',WIZARD_SITE_ID);
Option::set('IBLOCK_TYPE','catalog',WIZARD_SITE_ID);
//Option::set('IBLOCK_ID','8',WIZARD_SITE_ID);
Option::set('IBLOCK_TYPE_PROMOTION','kit_origami_content',WIZARD_SITE_ID);
//Option::set('IBLOCK_ID_PROMOTION','10',WIZARD_SITE_ID);
Option::set('PERSON_TYPE','1',WIZARD_SITE_ID);
Option::set('DELIVERY','2',WIZARD_SITE_ID);
Option::set('PAYMENT','1',WIZARD_SITE_ID);
Option::set('PROP_NAME','1',WIZARD_SITE_ID);
Option::set('PROP_PHONE','3',WIZARD_SITE_ID);
Option::set('PROP_EMAIL','2',WIZARD_SITE_ID);
//Option::set('IBLOCK_ID_ADVANTAGES','7',WIZARD_SITE_ID);
Option::set('IBLOCK_TYPE_ADVANTAGES','kit_origami_content',WIZARD_SITE_ID);
//Option::set('IBLOCK_ID_BANNERS','4',WIZARD_SITE_ID);
Option::set('IBLOCK_TYPE_BANNERS','kit_origami_advertising',WIZARD_SITE_ID);
Option::set('SHOW_POPUP_ADD_BASKET','N',WIZARD_SITE_ID);
Option::set('SHOW_VOTE_RATING','Y',WIZARD_SITE_ID);
Option::set('IMAGE_FOR_OFFER','PRODUCT',WIZARD_SITE_ID);
Option::set('OFFER_NAME','{=product.Name} {=prop.Value}',WIZARD_SITE_ID);
Option::set('OFFER_NAME_DELIMETER',', ',WIZARD_SITE_ID);
Option::set('SHOW_PROPS_HOVER','N',WIZARD_SITE_ID);
Option::set('RESIZE_WIDTH_BIG','',WIZARD_SITE_ID);
Option::set('RESIZE_HEIGHT_BIG','',WIZARD_SITE_ID);
Option::set('NAME_TAB_SORT_FIELD_1_',GetMessage('MAIN_OPT_SORT_PRICE_UP'),WIZARD_SITE_ID);
Option::set('CODE_TAB_SORT_FIELD_1_','PROPERTY_MINIMUM_PRICE',WIZARD_SITE_ID);
Option::set('SORT_ORDER_TAB_SORT_FIELD_1_','asc',WIZARD_SITE_ID);
Option::set('DEFAULT_SORT_TAB_','SORT_FIELD_4',WIZARD_SITE_ID);
Option::set('NAME_TAB_SORT_FIELD_2_',GetMessage('MAIN_OPT_SORT_PRICE_DOWN'),WIZARD_SITE_ID);
Option::set('CODE_TAB_SORT_FIELD_2_','PROPERTY_MINIMUM_PRICE',WIZARD_SITE_ID);
Option::set('SORT_ORDER_TAB_SORT_FIELD_2_','desc',WIZARD_SITE_ID);
Option::set('NAME_TAB_SORT_FIELD_3_',GetMessage('MAIN_OPT_SORT_POPULAR_DOWN'),WIZARD_SITE_ID);
Option::set('CODE_TAB_SORT_FIELD_3_','PROPERTY_RATING',WIZARD_SITE_ID);
Option::set('SORT_ORDER_TAB_SORT_FIELD_3_','desc',WIZARD_SITE_ID);
Option::set('NAME_TAB_SORT_FIELD_4_',GetMessage('MAIN_OPT_SORT_POPULAR_UP'),WIZARD_SITE_ID);
Option::set('CODE_TAB_SORT_FIELD_4_','PROPERTY_RATING',WIZARD_SITE_ID);
Option::set('SORT_ORDER_TAB_SORT_FIELD_4_','asc',WIZARD_SITE_ID);
Option::set('NAME_TAB_SORT_FIELD_5_',GetMessage('MAIN_OPT_SORT_NEW_DOWN'),WIZARD_SITE_ID);
Option::set('CODE_TAB_SORT_FIELD_5_','CREATED_DATE',WIZARD_SITE_ID);
Option::set('SORT_ORDER_TAB_SORT_FIELD_5_','desc',WIZARD_SITE_ID);
Option::set('NAME_TAB_SORT_FIELD_6_',GetMessage('MAIN_OPT_SORT_NEW_UP'),WIZARD_SITE_ID);
Option::set('CODE_TAB_SORT_FIELD_6_','CREATED_DATE',WIZARD_SITE_ID);
Option::set('SORT_ORDER_TAB_SORT_FIELD_6_','asc',WIZARD_SITE_ID);
Option::set('NAME_TAB_SORT_FIELD_7_',GetMessage('MAIN_OPT_SORT_ALF_UP'),WIZARD_SITE_ID);
Option::set('CODE_TAB_SORT_FIELD_7_','NAME',WIZARD_SITE_ID);
Option::set('SORT_ORDER_TAB_SORT_FIELD_7_','asc',WIZARD_SITE_ID);
Option::set('NAME_TAB_SORT_FIELD_8_',GetMessage('MAIN_OPT_SORT_ALF_DOWN'),WIZARD_SITE_ID);
Option::set('CODE_TAB_SORT_FIELD_8_','NAME',WIZARD_SITE_ID);
Option::set('SORT_ORDER_TAB_SORT_FIELD_8_','desc',WIZARD_SITE_ID);
Option::set('NAME_TAB_SORT_FIELD_9_','',WIZARD_SITE_ID);
Option::set('CODE_TAB_SORT_FIELD_9_','ID',WIZARD_SITE_ID);
Option::set('SORT_ORDER_TAB_SORT_FIELD_9_','desc',WIZARD_SITE_ID);
Option::set('TAB_SORT_','a:9:{i:0;s:12:"SORT_FIELD_1";i:1;s:12:"SORT_FIELD_2";i:2;s:12:"SORT_FIELD_3";i:3;s:12:"SORT_FIELD_4";i:4;s:12:"SORT_FIELD_5";i:5;s:12:"SORT_FIELD_6";i:6;s:12:"SORT_FIELD_7";i:7;s:12:"SORT_FIELD_8";i:8;s:12:"SORT_FIELD_9";}',WIZARD_SITE_ID);
Option::set('COUNT_COUNT_TAB_0_','12',WIZARD_SITE_ID);
Option::set('DEFAULT_COUNT_TAB_','2',WIZARD_SITE_ID);
Option::set('COUNT_COUNT_TAB_1_','16',WIZARD_SITE_ID);
Option::set('COUNT_COUNT_TAB_2_','20',WIZARD_SITE_ID);
Option::set('COUNT_COUNT_TAB_3_','24',WIZARD_SITE_ID);
Option::set('COUNT_COUNT_TAB_4_','28',WIZARD_SITE_ID);
Option::set('TAB_COUNT_','N;',WIZARD_SITE_ID);
Option::set('NAME_TAB_DESCRIPTION_','',WIZARD_SITE_ID);
Option::set('NAME_TAB_PROPERTIES_','',WIZARD_SITE_ID);
Option::set('NAME_TAB_DELIVERY_','',WIZARD_SITE_ID);
Option::set('NAME_TAB_AVAILABLE_','',WIZARD_SITE_ID);
Option::set('NAME_TAB_COMMENTS_','',WIZARD_SITE_ID);
Option::set('NAME_TAB_VIDEO_','',WIZARD_SITE_ID);
Option::set('ACTIVE_TAB_DOCS_','',WIZARD_SITE_ID);
Option::set('NAME_TAB_DOCS_','',WIZARD_SITE_ID);
Option::set('PROP_TAB_DOCS_','DOCUMENTS',WIZARD_SITE_ID);
Option::set('SKU_TYPE_','COMBINED',WIZARD_SITE_ID);
Option::set('TAGS_POSITION','TOP',WIZARD_SITE_ID);
Option::set('SEO_DESCRIPTION','HIDE_IF_RULE_EXIST',WIZARD_SITE_ID);
Option::set('INLINE_CSS_CHECKBOX','N',WIZARD_SITE_ID);
Option::set('INLINE_CSS_ADMIN_CHECKBOX','N',WIZARD_SITE_ID);
Option::set('INLINE_CSS_REMOVE_KERNEL_CSS_JS','Y',WIZARD_SITE_ID);
Option::set('TOP_BANNER','N',WIZARD_SITE_ID);
Option::set('FIXED_HEADER','N',WIZARD_SITE_ID);
Option::set('SIDE_MENU_ON_THE_PRODUCT_PAGE','N',WIZARD_SITE_ID);
Option::set('INLINE_CSS_EXCLUDE_FILE','9999999999',WIZARD_SITE_ID);
Option::set('ACTION_PRODUCTS','a:3:{i:0;s:3:"BUY";i:1;s:5:"DELAY";i:2;s:7:"COMPARE";}',WIZARD_SITE_ID);
Option::set('VARIANT_LIST_VIEW','template_3',WIZARD_SITE_ID);
Option::set('MOBILE_VIEW_MINIMAL','Y',WIZARD_SITE_ID);
Option::set('TO_TOP','N',WIZARD_SITE_ID);
Option::set('INSERT_LOCATION_IN_ORDER','N',WIZARD_SITE_ID);
Option::set('TEMPLATE_LIST_VIEW','a:2:{i:0;s:4:"card";i:1;s:4:"list";}',WIZARD_SITE_ID);
Option::set('TEMPLATE_LIST_VIEW_DEFAULT','card',WIZARD_SITE_ID);
Option::set('BTN_TOP','Y',WIZARD_SITE_ID);
Option::set('LAZY_LOAD','Y',WIZARD_SITE_ID);
Option::set('HEADER_FIX_DESKTOP','Y',WIZARD_SITE_ID);
Option::set('HEADER_FIX_MOBILE','Y',WIZARD_SITE_ID);
Option::set('PICTURE_SIDE_SECTIONS','Y',WIZARD_SITE_ID);
Option::set('SHOW_BUY_OC','Y',WIZARD_SITE_ID);
Option::set('SHARE_HANDLERS', 'a:8:{i:0;s:8:"facebook";i:1;s:6:"mailru";i:2;s:2:"ok";i:3;s:8:"telegram";i:4;s:7:"twitter";i:5;s:5:"viber";i:6;s:2:"vk";i:7;s:8:"whatsapp";}', WIZARD_SITE_ID);

//Option::set('SHOW_BUY_OC', 'Y', WIZARD_SITE_ID);

$dir =  '/local/templates/kit_origami/theme/custom';
if(!is_dir($_SERVER['DOCUMENT_ROOT'] .$dir))
{
    mkdir($_SERVER['DOCUMENT_ROOT'] .$dir);
}
KitOrigami::genTheme(['COLOR_BASE' => '#fb0040','FONT_BASE' => 'Open Sans','WIDTH' => '1344px'],$dir);

COption::SetOptionString("sale", "SHOP_SITE_".WIZARD_SITE_ID, WIZARD_SITE_ID);
//COption::SetOptionString("main", "auth_components_template", "flat");
COption::SetOptionString("fileman", "propstypes", serialize(array("description"=>GetMessage("MAIN_OPT_DESCRIPTION"), "keywords"=>GetMessage("MAIN_OPT_KEYWORDS"), "title"=>GetMessage("MAIN_OPT_TITLE"), "keywords_inner"=>GetMessage("MAIN_OPT_KEYWORDS_INNER"))), false, $siteID);
COption::SetOptionInt("search", "suggest_save_days", 250);
COption::SetOptionString("search", "use_tf_cache", "Y");
COption::SetOptionString("search", "use_word_distance", "Y");
COption::SetOptionString("search", "use_social_rating", "Y");
COption::SetOptionString("iblock", "use_htmledit", "Y");

COption::SetOptionString("main", "captcha_registration", "N");
COption::SetOptionString("main", "optimize_css_files", "Y");
COption::SetOptionString("main", "optimize_js_files", "Y");
COption::SetOptionString("main", "use_minified_assets", "Y");
COption::SetOptionString("main", "move_js_to_body", "Y");
COption::SetOptionString("main", "compres_css_js_files", "Y");




$subscribes = unserialize(COption::GetOptionString('sale','subscribe_prod'));
if(is_array($subscribes))
{
	$subscribes[WIZARD_SITE_ID] = ['use' => 'Y','del_after' => 100];
	COption::SetOptionString("sale", "subscribe_prod", serialize($subscribes));
}
?>
