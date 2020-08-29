<?php

use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use Sotbit\Origami\Config;
use Sotbit\Origami\Helper;

require_once($_SERVER["DOCUMENT_ROOT"]
    ."/bitrix/modules/main/include/prolog_admin_before.php");
require_once($_SERVER['DOCUMENT_ROOT']
    .'/bitrix/modules/main/include/prolog_admin.php');
Loc::loadMessages(__FILE__);


CJSCore::Init(array("jquery"));
$seoModule = \CModule::IncludeModule("sotbit.seometa");

if ($APPLICATION->GetGroupRight("main") < "R") {
    $APPLICATION->AuthForm(Loc::getMessage("ACCESS_DENIED"));
}
$moduleLoaded = false;
try {
    $moduleLoaded = Loader::includeModule('sotbit.origami');
} catch (\Bitrix\Main\LoaderException $e) {
    echo $e->getMessage();
}

$moduleSeo = Loader::includeModule('sotbit.seometa');

$frontChange = new Config\Widgets\CheckBox('FRONT_CHANGE');

$color = new Config\Widgets\SelectAdd('COLOR_BASE');
$color->setValues(Helper\Config::getColors());

$logo = new Config\Widgets\Str('LOGO');

$colorBackground = new Config\Widgets\SelectAdd('COLOR_BACKGROUND');
$colorBackground->setValues(Helper\Config::getColors());


$favicon = new Config\Widgets\Str('FAVICON');

$width = new Config\Widgets\Select('WIDTH');
$width->setValues(Helper\Config::getWidths());

$fontBase = new Config\Widgets\SelectAdd('FONT_BASE');
$fontBase->setValues(Helper\Config::getFonts());

$fontBaseSize = new Config\Widgets\SelectAdd('FONT_BASE_SIZE');
$fontBaseSize->setValues(Helper\Config::getFontsSizes());

$imgHover = new Config\Widgets\Select('IMG_HOVER');
$imgHover->setValues(Helper\Config::getImgHover());

$menuSide = new Config\Widgets\Select('MENU_SIDE');
$menuSide->setValues(Helper\Config::getMenuSide());

$sliderButtonsType = new Config\Widgets\Select('SLIDER_BUTTONS');
$sliderButtonsType->setValues(Helper\Config::getSliderButtons());

$back = new Config\Widgets\Select('BACK');
$back->setValues(Helper\Config::getBack());
$basket = new Config\Widgets\Select('BASKET');
$basket->setValues(Helper\Config::getBasket());
$innerMenu = new Config\Widgets\Select('INNER_MENU');
$innerMenu->setValues(Helper\Config::getInnerMenu());

$hoverEffect = new Config\Widgets\Select('HOVER_EFFECT');
$hoverEffect->setValues(Helper\Config::getHoverEffect());
$hoverEffect->setMultiple(true);

$up = new Config\Widgets\Select('UP');
$up->setValues(Helper\Config::getUp());
$upPosition = new Config\Widgets\Select('UP_POSITION');
$upPosition->setValues(Helper\Config::getUpPosition());

$header = new Config\Widgets\Select('HEADER');
$header->setValues(Helper\Config::getHeaders());
/*$headerFix = new Config\Widgets\Select('HEADER_FIX');
$headerFix->setValues(Helper\Config::getHeaderFix());
$headerFixType = new Config\Widgets\Select('HEADER_FIX_TYPE');
$headerFixType->setValues(Helper\Config::getHeaderFixType());*/


/*Youtube header settings*/

$headerBgColor = new Config\Widgets\Select('HEADER_BG_COLOR');
$headerBgColor->setValues(Helper\Config::getHeaderBgColors());
$headerBgColor->setCurrentValue('header-three--black');


/*--End Youtube settings--*/

$headerFixDestkop = new Config\Widgets\CheckBox('HEADER_FIX_DESKTOP');
$headerFixMobile = new Config\Widgets\CheckBox('HEADER_FIX_MOBILE');
$headerCall = new Config\Widgets\CheckBox('HEADER_CALL');
$footer = new Config\Widgets\Select('FOOTER');
$footerImg = new Config\Widgets\File('FOOTER_IMG');
$footer->setValues(Helper\Config::getFooters());
$footerImg->setValues();
$footerCall = new Config\Widgets\CheckBox('FOOTER_CALL');


$pBasket = new Config\Widgets\Str('BASKET_PAGE');
$pmOrder = new Config\Widgets\Str('ORDER_PAGE');
$pCompare = new Config\Widgets\Str('COMPARE_PAGE');
$pPersonal = new Config\Widgets\Str('PERSONAL_PAGE');
$pOrder = new Config\Widgets\Str('PERSONAL_ORDER_PAGE');
$pPayment = new Config\Widgets\Str('PAYMENT_PAGE');



$contacts = new Config\Widgets\Select('CONTACTS');
$contacts->setValues(Helper\Config::getContacts());

$iTypeContact = new Config\Widgets\Select('IBLOCK_TYPE_SHOP', ['refresh' => 'Y']);
$iTypeContact->setValues(Helper\Config::getIblockTypes());
$iIdContact = new Config\Widgets\IblockId('IBLOCK_ID_SHOP');
$iIdContact->setIblockTypeCode('IBLOCK_TYPE_SHOP');



$vk = new Config\Widgets\Str('VK');
$fb = new Config\Widgets\Str('FB');
$inst = new Config\Widgets\Str('INST');
$youtube = new Config\Widgets\Str('YOUTUBE');
$ok = new Config\Widgets\Str('OK');
$telega = new Config\Widgets\Str('TELEGA');
$tw = new Config\Widgets\Str('TW');
$google = new Config\Widgets\Str('GOOGLE');
$pt = new Config\Widgets\Select('PERSON_TYPE', ['refresh' => 'Y']);
$pt->setValues(Helper\Config::getPaymentTypes());
$delivery = new Config\Widgets\Select('DELIVERY');
$delivery->setValues(Helper\Config::getDeliveries());
$payment = new Config\Widgets\Select('PAYMENT');
$payment->setValues(Helper\Config::getPayments());
$propName = new Config\Widgets\Select('PROP_NAME');
$propName->setValues(Helper\Config::getProps());
$propPhone = new Config\Widgets\Select('PROP_PHONE');
$propPhone->setValues(Helper\Config::getProps());
$propEmail = new Config\Widgets\Select('PROP_EMAIL');
$propEmail->setValues(Helper\Config::getProps());

/* New settings One click*/
$sopSelectUser = new Config\Widgets\Select('SOP_SELECT_USER', ['refresh' => 'Y']);
$sopSelectUser->setValues(['new' => Loc::getMessage('SOP_SELECT_USER_NEW'), 'one' => Loc::getMessage('SOP_SELECT_USER_ONE')]);
$sopOneUserId = new Config\Widgets\Number('SOP_ONE_USER_ID');
$sopUserGroup = new Config\Widgets\Select('SOP_USER_GROUP');
$sopUserGroup->setValues(Helper\Config::getUserGroups());
$sopStatusOrder = new Config\Widgets\Select('SOP_STATUS_ORDER');
$sopStatusOrder->setValues(Helper\Config::getStatusOrder());
$sopDisplayedFields = new Config\Widgets\Select('SOP_DISPLAYED_FIELDS');
$sopDisplayedFields->setValues(Helper\Config::getOCDisplayedRequiredFields());
$sopDisplayedFields->setMultiple(true);
$sopRequiredFields = new Config\Widgets\Select('SOP_REQUIRED_FIELDS');
$sopRequiredFields->setValues(Helper\Config::getOCDisplayedRequiredFields());
$sopRequiredFields->setMultiple(true);
$sopRequiredFields->setCurrentValue(serialize(['PHONE', 'EMAIL']));
$sopRequiredFields->setSettings(['NOTE' => Loc::getMessage('SOP_REQUIRED_EXPLANATION')]);
$sopCommentTemplate = new Config\Widgets\Textarea('SOP_COMMENT_TEMPLATE', Loc::getMessage('SOP_COMMENT_TEMPLATE_DEFAULT'));
$sopCommentTemplate->setSettings(['NOTE' => Loc::getMessage('SOP_COMMENT_EXPLANATION')]);
$sopLoginMask = new Config\Widgets\Textarea('SOP_LOGIN_MASK', Loc::getMessage('SOP_LOGIN_MASK_DEFAULT'));
$sopEmailMask = new Config\Widgets\Textarea('SOP_EMAIL_MASK', Loc::getMessage('SOP_EMAIL_MASK_DEFAULT'));
$sopEmailMask->setSettings(['NOTE' => Loc::getMessage('SOP_EMAIL_MASK_EXPLANATION')]);
$sopSmsConfirm = new Config\Widgets\CheckBox('SOP_SMS_CONFIRM', ['refresh' => 'Y']);
$sopSmsRepeatedTime = new Config\Widgets\Number('SOP_SMS_REPEATED_TIME');
if (\Sotbit\Origami\Helper\Config::get('SOP_SMS_CONFIRM') == 'N')
    $sopSmsRepeatedTime->setDisable();
if (\Sotbit\Origami\Helper\Config::get('SOP_SELECT_USER') != 'one')
    $sopOneUserId->setDisable();
$sopSmsRepeatedTime->setSettings(['NOTE' => Loc::getMessage('SOP_EXPLANATION')]);

$captcha = new Config\Widgets\Select('CAPTCHA');
$captcha->setValues(Helper\Config::getCaptcha());
$captcha->setDisable(['RECAPTCHA','HIDE']);
$mask = new Config\Widgets\Str('MASK');

$typeMask = new Config\Widgets\Select('TYPE_MASK_VIEW');
$typeMask->setValues(Helper\Config::getMasksTypes());
$typeMask->setSettings(['NOTE' => Loc::getMessage('TYPE_MASKS_TITLE')]);

$sectionRootTemplate = new Config\Widgets\Select('SECTION_ROOT_TEMPLATE');
$sectionRootTemplate->setValues(Helper\Config::getSectionRootTemplate());

$filterTemplate = new Config\Widgets\Select('FILTER_TEMPLATE');
$filterTemplate->setValues(Helper\Config::getFilterTemplate());

$templateListView = new Config\Widgets\Select('TEMPLATE_LIST_VIEW');
$templateListView->setValues(Helper\Config::getTemplateListView());
$templateListView->setMultiple(true);

$templateListViewDefault = new Config\Widgets\Select('TEMPLATE_LIST_VIEW_DEFAULT');
$templateListViewDefault->setValues(Helper\Config::getTemplateListView());


$variantListView = new Config\Widgets\Select('VARIANT_LIST_VIEW');
$variantListView->setValues(Helper\Config::getVariantListView());

$mobileViewMin = new Config\Widgets\CheckBox('MOBILE_VIEW_MINIMAL');

$filterMode = new Config\Widgets\Select('FILTER_MODE');
$filterMode->setValues(Helper\Config::getFilterMode());

$propSeoLinkMode = new Config\Widgets\Select('SEO_LINK_MODE');
//$propSeoLinkMode = new Config\Widgets\Select('SEO_LINK_MODEEFAULT');
$propSeoLinkMode->setValues(Helper\Config::getSeoMode());
if(!$seoModule)
    $filterMode->setDisable(['SEOMETA_MODE']);

$quickView = new Config\Widgets\CheckBox('QUICK_VIEW');
$showBuyOCList = new Config\Widgets\CheckBox('SHOW_BUY_OC');

$cntInRow = new Config\Widgets\Select('CNT_IN_ROW');
$cntInRow->setValues(Helper\Config::getProductInRow());

/*
$showBuyButton = new Config\Widgets\CheckBox('SHOW_BUY_BUTTON');
$showPropsHover = new Config\Widgets\CheckBox('SHOW_PROPS_HOVER');*/

$pagination = new Config\Widgets\Select('PAGINATION');
$pagination->setValues(Helper\Config::getPagination());


$seometaTags = new Config\Widgets\CheckBox('SEOMETA_TAGS');
$productInRow = new Config\Widgets\Select('PRODUCT_IN_ROW');
$productInRow->setValues(Helper\Config::getProductInRow());

$hideNotAvailable = new Config\Widgets\Select('HIDE_NOT_AVAILABLE');
$hideNotAvailable->setValues(Helper\Config::getHideNotAvailable());




$detailTemplate = new Config\Widgets\Select('DETAIL_TEMPLATE');
$detailTemplate->setValues(Helper\Config::getDetailTemplates());


//$replaceQnt = new Config\Widgets\CheckBox('REPLACE_QNT');

$iblock = Config\Option::get('IBLOCK_ID', $site);
$oIblock = CCatalogSku::GetInfoByIBlock($iblock)['IBLOCK_ID'];

$iType = new Config\Widgets\Select('IBLOCK_TYPE', ['refresh' => 'Y']);
$iType->setValues(Helper\Config::getIblockTypes());
$iId = new Config\Widgets\IblockId('IBLOCK_ID');
$iId->setIblockTypeCode('IBLOCK_TYPE');

$actionProducts = new Config\Widgets\Select('ACTION_PRODUCTS');
$actionProducts->setValues(Helper\Config::getActionProducts());
$actionProducts->setMultiple(true);



//$showCompare = new Config\Widgets\CheckBox('SHOW_COMPARE');
$detailPropCnt = new Config\Widgets\Number('DETAIL_PROP_CNT');

$showStockMode = new Config\Widgets\Select('SHOW_STOCK_MODE');
$showStockMode->setValues(Helper\Config::getShowStockMode());

$artProp = new Config\Widgets\Select('ARTICUL');
$artProp->setCanEmpty(true);
$artProp->setValues(Helper\Config::getIblockProps(Config\Option::get('IBLOCK_ID', $site)));

$artPropOffer = new Config\Widgets\Select('ARTICUL_OFFER');
$artPropOffer->setCanEmpty(true);
$artPropOffer->setValues(Helper\Config::getIblockProps($oIblock));

$colorProp = new Config\Widgets\Select('COLOR');
$colorProp->setCanEmpty(true);
$colorProp->setValues(Helper\Config::getIblockProps($oIblock));

$labelProps = new Config\Widgets\Select('LABEL_PROPS');
$labelProps->setValues(Helper\Config::getIblockProps(Config\Option::get('IBLOCK_ID', $site)));
$labelProps->setMultiple(true);

$labelPropsMain = new Config\Widgets\Select('LABEL_PROPS_MAIN');
$labelPropsMain->setValues(Helper\Config::getIblockPropsList(Config\Option::get('IBLOCK_ID', $site)));
$labelPropsMain->setMultiple(true);

$servicesProp = new Config\Widgets\Select('SERVICES');
$servicesProp->setCanEmpty(true);
$servicesProp->setValues(Helper\Config::getIblockProps(Config\Option::get('IBLOCK_ID', $site)));

$pictureSideSections = new Config\Widgets\CheckBox('PICTURE_SIDE_SECTIONS');
$popupAddBasket = new Config\Widgets\CheckBox('SHOW_POPUP_ADD_BASKET');
$showVoteRating = new Config\Widgets\CheckBox('SHOW_VOTE_RATING');



$files = new Config\Widgets\Multi('FILES');

$useRegions = new Config\Widgets\CheckBox('USE_REGIONS');

$regionTemplate = new Config\Widgets\Select('REGION_TEMPLATE');
$regionTemplate->setValues(Helper\Config::getRegionTemplate());

$toTop = new Config\Widgets\CheckBox('BTN_TOP');

$lazyLoad = new Config\Widgets\CheckBox('LAZY_LOAD');
$timerPromotions = new Config\Widgets\CheckBox('TIMER_PROMOTIONS');

$ieStub = new Config\Widgets\CheckBox('IE_STUB');

$siteBuilder = new Config\Widgets\CheckBox('SITE_BUILDER');

$confidentialPage = new Config\Widgets\Str('CONFIDENTIAL_PAGE');

$imgForOffer = new Config\Widgets\Select('IMAGE_FOR_OFFER');
$imgForOffer->setValues(Helper\Config::getImgOffer());

$basketType = new Config\Widgets\Select('BASKET_TYPE');
$basketType->setValues(Helper\Config::getBasketTypes());

$orderTemplate = new Config\Widgets\Select('ORDER_TEMPLATE');
$orderTemplate->setValues(Helper\Config::getOrderTemplates());

$insertLocationInOrder = new Config\Widgets\CheckBox('INSERT_LOCATION_IN_ORDER');



$showMaskOrderProp = new Config\Widgets\Select('ORDER_PROP_MASK');
$showMaskOrderProp->setValues(Helper\Config::getProps());
$showMaskOrderProp->setMultiple(true);




$iTypePr = new Config\Widgets\Select('IBLOCK_TYPE_PROMOTION', ['refresh' => 'Y']);
$iTypePr->setValues(Helper\Config::getIblockTypes());
$iIdPr = new Config\Widgets\IblockId('IBLOCK_ID_PROMOTION');
$iIdPr->setIblockTypeCode('IBLOCK_TYPE_PROMOTION');
$promotionListTemplate = new Config\Widgets\Select('PROMOTION_LIST_TEMPLATE');
$promotionListTemplate->setValues(Helper\Config::getPromotionListTemplates());

$iTypeBl = new Config\Widgets\Select('IBLOCK_TYPE_BLOG', ['refresh' => 'Y']);
$iTypeBl->setValues(Helper\Config::getIblockTypes());
$iIdBl = new Config\Widgets\IblockId('IBLOCK_ID_BLOG');
$iIdBl->setIblockTypeCode('IBLOCK_TYPE_BLOG');

$iTypeBlTemplate = new Config\Widgets\Select('IBLOCK_TEMPLATE_BLOG');
$iTypeBlTemplate->setValues(Helper\Config::getIblockBlogTemplates());

$iTypeNews = new Config\Widgets\Select('IBLOCK_TYPE_NEWS', ['refresh' => 'Y']);
$iTypeNews->setValues(Helper\Config::getIblockTypes());
$iIdNews = new Config\Widgets\IblockId('IBLOCK_ID_NEWS');
$iIdNews->setIblockTypeCode('IBLOCK_TYPE_NEWS');
$iTypeNewsTemplate = new Config\Widgets\Select('IBLOCK_TEMPLATE_NEWS');
$iTypeNewsTemplate->setValues(Helper\Config::getIblockNewsTemplates());

$iTypeBrands = new Config\Widgets\Select('IBLOCK_TYPE_BRANDS', ['refresh' => 'Y']);
$iTypeBrands->setValues(Helper\Config::getIblockTypes());
$iIdBrands = new Config\Widgets\IblockId('IBLOCK_ID_BRANDS');
$iIdBrands->setIblockTypeCode('IBLOCK_TYPE_BRANDS');

$iTypeBanners = new Config\Widgets\Select('IBLOCK_TYPE_BANNERS', ['refresh' => 'Y']);
$iTypeBanners->setValues(Helper\Config::getIblockTypes());
$iIdBanners = new Config\Widgets\IblockId('IBLOCK_ID_BANNERS');
$iIdBanners->setIblockTypeCode('IBLOCK_TYPE_BANNERS');

$iTypeAdvantages = new Config\Widgets\Select('IBLOCK_TYPE_ADVANTAGES', ['refresh' => 'Y']);
$iTypeAdvantages->setValues(Helper\Config::getIblockTypes());
$iIdAdvantages = new Config\Widgets\IblockId('IBLOCK_ID_ADVANTAGES');
$iIdAdvantages->setIblockTypeCode('IBLOCK_TYPE_ADVANTAGES');

$iTypeFaq = new Config\Widgets\Select('IBLOCK_TYPE_FAQ', ['refresh' => 'Y']);
$iTypeFaq->setValues(Helper\Config::getIblockTypes());
$iIdFaq = new Config\Widgets\IblockId('IBLOCK_ID_FAQ');
$iIdFaq->setIblockTypeCode('IBLOCK_TYPE_FAQ');
$iTypeVlog = new Config\Widgets\Select('IBLOCK_TYPE_VLOG', ['refresh' => 'Y']);
$iTypeVlog->setValues(Helper\Config::getIblockTypes());
$iIdVlog= new Config\Widgets\IblockId('IBLOCK_ID_VLOG');
$iIdVlog->setIblockTypeCode('IBLOCK_TYPE_VLOG');

$sectionDescription = new Config\Widgets\Select('SECTION_DESCRIPTION');
$sectionDescription->setValues(Helper\Config::getSectionDescription());
$sectionDescriptionTop = new Config\Widgets\Select('SECTION_DESCRIPTION_TOP');
$sectionDescriptionTop->setValues(Helper\Config::getSectionDescriptionPosition());
$sectionDescriptionBottom = new Config\Widgets\Select('SECTION_DESCRIPTION_BOTTOM');
$sectionDescriptionBottom->setValues(Helper\Config::getSectionDescriptionPosition());

$offerLanding = new Config\Widgets\CheckBox('OFFER_LANDING');
$offerLandingSeo = new Config\Widgets\CheckBox('OFFER_LANDING_SEO');
$offerLanding404 = new Config\Widgets\Select('OFFER_LANDING_404');
$offerLanding404->setValues(Helper\Config::getOfferLanding404());


$tagsPosition = new Config\Widgets\Select('TAGS_POSITION');
$tagsPosition->setValues(Helper\Config::getTagsPosition());
$seoDescription = new Config\Widgets\Select('SEO_DESCRIPTION');
$seoDescription->setValues(Helper\Config::getSeoDescription());

//$descriptionTop = new Config\Widgets\CheckBox('DESCRIPTION_TOP');
//$positionTopDescription = new Config\Widgets\Select('DESCRIPTION_TOP_POSITION');
//$positionTopDescription->setValues(Helper\Config::getDescriptionPosition());
//$descriptionBottom = new Config\Widgets\CheckBox('DESCRIPTION_BOTTOM');
//$positionBottomDescription = new Config\Widgets\Select('DESCRIPTION_BOTTOM_POSITION');
//$positionBottomDescription->setValues(Helper\Config::getDescriptionPosition());
//$descriptionAdditional = new Config\Widgets\CheckBox('DESCRIPTION_ADDITIONAL');
//$topTagPosition = new Config\Widgets\CheckBox('TAG_POSITION_TOP');
//$temlateTagTop = new Config\Widgets\Select('TAG_TOP_TEMPLATE');
//$temlateTagTop->setValues(Helper\Config::getTagTemlate());
//$bottomTagPosition = new Config\Widgets\CheckBox('TAG_POSITION_BOTTOM');
//$temlateTagBottom = new Config\Widgets\Select('TAG_BOTTOM_TEMPLATE');
//$temlateTagBottom->setValues(Helper\Config::getTagTemlate());



$resizeType = new Config\Widgets\Select('RESIZE_TYPE');
$resizeType->setValues(Helper\Config::getResizeTypes());
$resizeWidthSmall = new Config\Widgets\Str('RESIZE_WIDTH_SMALL');
$resizeHeightSmall = new Config\Widgets\Str('RESIZE_HEIGHT_SMALL');
$resizeWidthMedium = new Config\Widgets\Str('RESIZE_WIDTH_MEDIUM');
$resizeHeightMedium = new Config\Widgets\Str('RESIZE_HEIGHT_MEDIUM');
//$resizeWidthBig = new Config\Widgets\Str('RESIZE_WIDTH_BIG');
//$resizeHeightBig = new Config\Widgets\Str('RESIZE_HEIGHT_BIG');


$propColorTypeSection = new Config\Widgets\Select('PROP_COLOR_TYPE_SECTION');
$propColorTypeSection->setValues(Helper\Config::getPropColorMode());


$showQuantityForGroups = new Config\Widgets\Select('SHOW_QUANTITY_FOR_GROUPS');
$showQuantityForGroups->setValues(Helper\Config::getUserGroups());
$showQuantityForGroups->setMultiple(true);
$showQuantityCountForGroups = new Config\Widgets\Select('SHOW_QUANTITY_COUNT_FOR_GROUPS');
$showQuantityCountForGroups->setValues(Helper\Config::getUserGroups());
$showQuantityCountForGroups->setMultiple(true);
$replaceWithWord = new Config\Widgets\CheckBox('REPLACE_WITH_WORD');
$minAmount = new Config\Widgets\Str('MIN_AMOUNT');
$maxAmount = new Config\Widgets\Str('MAX_AMOUNT');

$offerName = new Config\Widgets\Seo('OFFER_NAME');
$offerName->setIblock(Config\Option::get('IBLOCK_ID', $site));
$offerNameDelimeter = new Config\Widgets\Str('OFFER_NAME_DELIMETER');
$offerNameDelimeter->setSettings(['NOTE' => Loc::getMessage('OFFER_NAME_DELIMETER_HELP')]);
$Group = new Config\Group('MAIN_SETTINGS');
$Group->getWidgets()->addItem($siteBuilder);
$Group->getWidgets()->addItem($frontChange);
$Group->getWidgets()->addItem($color);
$Group->getWidgets()->addItem($logo);
//$Group->getWidgets()->addItem($colorBackground);

$Group->getWidgets()->addItem($favicon);

$Group->getWidgets()->addItem($width);
$Group->getWidgets()->addItem($fontBase);
$Group->getWidgets()->addItem($fontBaseSize);
$Group->getWidgets()->addItem($hoverEffect);
$Group->getWidgets()->addItem($menuSide);
$Group->getWidgets()->addItem($sliderButtonsType);

$Group->getWidgets()->addItem($toTop);
$Group->getWidgets()->addItem($lazyLoad);
$Group->getWidgets()->addItem($timerPromotions);
$Group->getWidgets()->addItem($ieStub);

$GroupReg = new Config\Group('REGIONS');
$GroupReg->getWidgets()->addItem($useRegions);
$GroupReg->getWidgets()->addItem($regionTemplate);
//$Group->getWidgets()->addItem($files);
$GroupH = new Config\Group('HEADER');
$GroupH->getWidgets()->addItem($header);
$GroupH->getWidgets()->addItem($headerFixDestkop);
$GroupH->getWidgets()->addItem($headerFixMobile);
$GroupH->getWidgets()->addItem($headerCall);
$GroupH->getWidgets()->addItem($headerBgColor);
$GroupF = new Config\Group('FOOTER');
$GroupF->getWidgets()->addItem($footer);
$GroupF->getWidgets()->addItem($footerCall);
$GroupF->getWidgets()->addItem($footerImg);
$GroupP = new Config\Group('PAGES');
$GroupP->getWidgets()->addItem($pBasket);
$GroupP->getWidgets()->addItem($pmOrder);
$GroupP->getWidgets()->addItem($pCompare);
$GroupP->getWidgets()->addItem($pPersonal);
$GroupP->getWidgets()->addItem($pOrder);
$GroupP->getWidgets()->addItem($pPayment);
$GroupP->getWidgets()->addItem($confidentialPage);
$GroupC = new Config\Group('CONTACTS');
$GroupC->getWidgets()->addItem($iTypeContact);
$GroupC->getWidgets()->addItem($iIdContact);
$GroupC->getWidgets()->addItem($contacts);

$GroupShare = new Config\Group('SHARE');
$shareHandlers = new Config\Widgets\Select('SHARE_HANDLERS');
$shareHandlers->setValues(Helper\Config::getShareHandlers());
$shareHandlers->setMultiple(true);

$instagramToken = new Config\Widgets\Str('ACCESS_TOKEN');
$instagramToken->setValues('4028561690.1677ed0.b43f21bcd35b452c9b8cf7bce8142942');

$GroupShare->getWidgets()->addItem($shareHandlers);
$GroupShare->getWidgets()->addItem($instagramToken);

$GroupSoc = new Config\Group('SOC');
$GroupSoc->getWidgets()->addItem($vk);
$GroupSoc->getWidgets()->addItem($fb);
$GroupSoc->getWidgets()->addItem($inst);
$GroupSoc->getWidgets()->addItem($youtube);
$GroupSoc->getWidgets()->addItem($ok);
$GroupSoc->getWidgets()->addItem($telega);
$GroupSoc->getWidgets()->addItem($tw);
$GroupSoc->getWidgets()->addItem($google);
$GroupOC = new Config\Group('ONE_CLICK');
$GroupOC->getWidgets()->addItem($pt);
$GroupOC->getWidgets()->addItem($delivery);
$GroupOC->getWidgets()->addItem($payment);
$GroupOC->getWidgets()->addItem($propName);
$GroupOC->getWidgets()->addItem($propPhone);
$GroupOC->getWidgets()->addItem($propEmail);
$GroupOC->getWidgets()->addItem($sopSelectUser);
$GroupOC->getWidgets()->addItem($sopOneUserId);
$GroupOC->getWidgets()->addItem($sopUserGroup);
$GroupOC->getWidgets()->addItem($sopStatusOrder);
$GroupOC->getWidgets()->addItem($sopDisplayedFields);
$GroupOC->getWidgets()->addItem($sopRequiredFields);
$GroupOC->getWidgets()->addItem($sopCommentTemplate);
$GroupOC->getWidgets()->addItem($sopLoginMask);
$GroupOC->getWidgets()->addItem($sopEmailMask);
$GroupOC->getWidgets()->addItem($sopSmsConfirm);
$GroupOC->getWidgets()->addItem($sopSmsRepeatedTime);
$GroupValid = new Config\Group('VALID');
$GroupValid->getWidgets()->addItem($captcha);
$GroupValid->getWidgets()->addItem($typeMask);
$GroupValid->getWidgets()->addItem($mask);

$GroupSection = new Config\Group('SECTION');
$GroupSection->getWidgets()->addItem($sectionRootTemplate);
$GroupSection->getWidgets()->addItem($filterTemplate);
$GroupSection->getWidgets()->addItem($templateListView);
$GroupSection->getWidgets()->addItem($templateListViewDefault);
$GroupSection->getWidgets()->addItem($variantListView);
$GroupSection->getWidgets()->addItem($mobileViewMin);
$GroupSection->getWidgets()->addItem($filterMode);

$GroupSection->getWidgets()->addItem($propColorTypeSection);
$GroupSection->getWidgets()->addItem($quickView);
$GroupSection->getWidgets()->addItem($showBuyOCList);

$GroupSection->getWidgets()->addItem($sectionDescription);
$GroupSection->getWidgets()->addItem($sectionDescriptionTop);
$GroupSection->getWidgets()->addItem($sectionDescriptionBottom);

$GroupSection->getWidgets()->addItem($cntInRow);
/*
$GroupSection->getWidgets()->addItem($showBuyButton);
$GroupSection->getWidgets()->addItem($showPropsHover);*/
$GroupSection->getWidgets()->addItem($pagination);
$GroupSection->getWidgets()->addItem($hideNotAvailable);
$GroupSection->getWidgets()->addItem($propSeoLinkMode);

$GroupSection->getWidgets()->addItem($resizeType);
$GroupSection->getWidgets()->addItem($resizeWidthSmall);
$GroupSection->getWidgets()->addItem($resizeHeightSmall);
$GroupSection->getWidgets()->addItem($resizeWidthMedium);
$GroupSection->getWidgets()->addItem($resizeHeightMedium);
//$GroupSection->getWidgets()->addItem($resizeWidthBig);
//$GroupSection->getWidgets()->addItem($resizeHeightBig);

$GroupSort = new Config\Group('SORT');
$tabSort = new Config\Widgets\TabSort('TAB_SORT_');
$tabSort->setValues(array(
    "SORT_FIELD_1",
    "SORT_FIELD_2",
    "SORT_FIELD_3",
    "SORT_FIELD_4",
    "SORT_FIELD_5",
    "SORT_FIELD_6",
    "SORT_FIELD_7",
    "SORT_FIELD_8",
    "SORT_FIELD_9",
));
$tabSort->setSite($site);
$GroupSort->getWidgets()->addItem($tabSort);

$tabCount = new Config\Widgets\TabCount('TAB_COUNT_');
$tabCount->setSite($site);
$GroupSort->getWidgets()->addItem($tabCount);

$GroupQuantityOptions = new Config\Group('QUANTITY_OPTIONS');
$GroupQuantityOptions->getWidgets()->addItem($showQuantityForGroups);
$GroupQuantityOptions->getWidgets()->addItem($showQuantityCountForGroups);
$GroupQuantityOptions->getWidgets()->addItem($replaceWithWord);
$GroupQuantityOptions->getWidgets()->addItem($minAmount);
$GroupQuantityOptions->getWidgets()->addItem($maxAmount);

$GroupMainCatalogServices = new Config\Group('MAIN_CATALOG_SERVICES');
$iTypeBlockServices = new Config\Widgets\Select('IBLOCK_TYPE_SERVICES', ['refresh' => 'Y']);
$iTypeBlockServices->setValues(Helper\Config::getIblockTypes());
$iIdBlockServices = new Config\Widgets\IblockId('IBLOCK_ID_SERVICES');
$iIdBlockServices->setIblockTypeCode('IBLOCK_TYPE_SERVICES');

$sectionRootTemplateSections = new Config\Widgets\Select('SECTION_ROOT_TEMPLATE_SERVICES');
$sectionRootTemplateSections->setValues(Helper\Config::getSectionRootTemplateServices());

$GroupMainCatalogServices->getWidgets()->addItem($iTypeBlockServices);
$GroupMainCatalogServices->getWidgets()->addItem($iIdBlockServices);
$GroupMainCatalogServices->getWidgets()->addItem($sectionRootTemplateSections);



$GroupMainCatalog = new Config\Group('MAIN_CATALOG');
$GroupMainCatalog->getWidgets()->addItem($iType);
$GroupMainCatalog->getWidgets()->addItem($iId);
$GroupMainCatalog->getWidgets()->addItem($actionProducts);
//$GroupMainCatalog->getWidgets()->addItem($showCompare);
$GroupMainCatalog->getWidgets()->addItem($showStockMode);
$GroupMainCatalog->getWidgets()->addItem($artProp);
$GroupMainCatalog->getWidgets()->addItem($artPropOffer);
$GroupMainCatalog->getWidgets()->addItem($colorProp);
$GroupMainCatalog->getWidgets()->addItem($labelProps);
$GroupMainCatalog->getWidgets()->addItem($labelPropsMain);
$GroupMainCatalog->getWidgets()->addItem($pictureSideSections);
$GroupMainCatalog->getWidgets()->addItem($popupAddBasket);
$GroupMainCatalog->getWidgets()->addItem($showVoteRating);
$GroupMainCatalog->getWidgets()->addItem($imgForOffer);
$GroupMainCatalog->getWidgets()->addItem($offerName);
$GroupMainCatalog->getWidgets()->addItem($offerNameDelimeter);

$GroupMainElement = new Config\Group('ELEMENT_MAIN');
$GroupMainElement->getWidgets()->addItem($detailTemplate);

$Tab3 = new Config\Tab('CATALOG_DETAIL');
$Tab3->getGroups()->addItem($GroupMainElement);
$templates = Helper\Config::getDetailTemplates();
$Tab7 = new Config\Tab('CATALOG_SERVICES');
$Tab7->getGroups()->addItem($GroupMainCatalogServices);

foreach ($templates as $key=>$template) {
    $GroupMainElementTemplate = new Config\Group('ELEMENT_MAIN_'.$key);
    $GroupBitrixProductBlocks = new Config\Group('BITRIX_PRODUCT_BLOCK_'.$key);

    $sections = new Config\Widgets\Sections('SECTIONS_'.$key);
    $sections->setMultiple(true);
    $sections->setCanEmpty(true);
    $GroupMainElementTemplate->getWidgets()->addItem($sections);

    $tabs = new Config\Widgets\Tabs('TABS_'.$key);
    $tabs->setValues(Helper\Config::getTabs());
    $tabs->setTemplate($key);
    $tabs->setSite($site);
    $GroupMainElementTemplate->getWidgets()->addItem($tabs);

    $propTabVideo = new Config\Widgets\Select('PROP_TAB_VIDEO_'.$key);
    $propTabVideo->setCanEmpty(true);
    $propTabVideo->setValues(Helper\Config::getIblockPropsStrMulti(Config\Option::get('IBLOCK_ID', $site)));
    $GroupMainElementTemplate->getWidgets()->addItem($propTabVideo);

    $propTabDoc = new Config\Widgets\Select('PROP_TAB_DOCS_'.$key);
    $propTabDoc->setCanEmpty(true);
    $propTabDoc->setValues(Helper\Config::getIblockPropsStrMulti(Config\Option::get('IBLOCK_ID', $site)));
    $GroupMainElementTemplate->getWidgets()->addItem($propTabDoc);



    $addProps = new Config\Widgets\Select('DETAIL_ADD_PROPS_'.$key);
    $addProps->setMultiple('Y');
    $addProps->setValues(Helper\Config::getIblockProps(Config\Option::get('IBLOCK_ID', $site)));
    $GroupMainElementTemplate->getWidgets()->addItem($addProps);

    $resizeType = new Config\Widgets\Select('RESIZE_TYPE_'.$key);
    $resizeType->setValues(Helper\Config::getResizeTypes());
    $resizeWidthSmall = new Config\Widgets\Str('RESIZE_WIDTH_SMALL_'.$key);
    $resizeHeightSmall = new Config\Widgets\Str('RESIZE_HEIGHT_SMALL_'.$key);
    $resizeWidthMedium = new Config\Widgets\Str('RESIZE_WIDTH_MEDIUM_'.$key);
    $resizeHeightMedium = new Config\Widgets\Str('RESIZE_HEIGHT_MEDIUM_'.$key);
    $resizeWidthBig = new Config\Widgets\Str('RESIZE_WIDTH_BIG_'.$key);
    $resizeHeightBig = new Config\Widgets\Str('RESIZE_HEIGHT_BIG_'.$key);

    $GroupMainElementTemplate->getWidgets()->addItem($resizeType);
    $GroupMainElementTemplate->getWidgets()->addItem($resizeWidthSmall);
    $GroupMainElementTemplate->getWidgets()->addItem($resizeHeightSmall);
    $GroupMainElementTemplate->getWidgets()->addItem($resizeWidthMedium);
    $GroupMainElementTemplate->getWidgets()->addItem($resizeHeightMedium);
    $GroupMainElementTemplate->getWidgets()->addItem($resizeWidthBig);
    $GroupMainElementTemplate->getWidgets()->addItem($resizeHeightBig);


    $showBuyOC = new Config\Widgets\CheckBox('SHOW_BUY_OC_'.$key);
    $showPreviewDelivery = new Config\Widgets\CheckBox('SHOW_PREVIEW_DELIVERY_'.$key);
    $propDisplayMode = new Config\Widgets\Select('PROP_DISPLAY_MODE_'.$key);
    $propDisplayMode->setValues(Helper\Config::getPropDisplayMode());
    $propColorTypeElement = new Config\Widgets\Select('PROP_COLOR_TYPE_ELEMENT_'.$key);
    $propColorTypeElement->setValues(Helper\Config::getPropColorMode());
    $showFoundCheaper = new Config\Widgets\CheckBox('SHOW_FOUND_CHEAPER_'.$key);
    $showWantGift = new Config\Widgets\CheckBox('SHOW_WANT_GIFT_'.$key);
    $showCheckStock = new Config\Widgets\CheckBox('SHOW_CHECK_STOCK_'.$key);
    $showZoom = new Config\Widgets\CheckBox('SHOW_ZOOM_'.$key);
    $propertyGrouper = new Config\Widgets\Select('PROPERTY_GROUPER_'.$key);
    $propertyGrouper->setValues(Helper\Config::getPropertyGrouperType());
    if(!Loader::includeModule('redsign.grupper'))
    {
        $propertyGrouper->setDisable(['GRUPPER']);
        $propertyGrouper->changeName('GRUPPER', Loc::getMessage('sotbit.origami_NOT_INSTALLED', array('#MODULE_NAME#' => 'redsign.grupper')));
    }
    if(!Loader::includeModule('webdebug.utilities'))
    {
        $propertyGrouper->setDisable(['WEBDEBUG']);
        $propertyGrouper->changeName('WEBDEBUG', Loc::getMessage('sotbit.origami_NOT_INSTALLED', array('#MODULE_NAME#' => 'webdebug.utilities')));
    }

    $propFilterMode = new Config\Widgets\Select('PROP_FILTER_MODE_'.$key);
    $propFilterMode->setValues(Helper\Config::getPropFilterMode());


    $propVideo = new Config\Widgets\Select('PROP_VIDEO_'.$key);
    $propVideo->setCanEmpty(true);
    $propVideo->setValues(Helper\Config::getIblockPropsStrMulti(Config\Option::get('IBLOCK_ID', $site)));

    $showCrosssell = new Config\Widgets\CheckBox('SHOW_CROSSSELL_'.$key);

//    $showAnalog = new Config\Widgets\CheckBox('SHOW_ANALOG_'.$key);
//
//    $showAnalogProp = new Config\Widgets\Select('ANALOG_PROP_'.$key);
//    $showAnalogProp->setCanEmpty(true);
//    $showAnalogProp->setValues(Helper\Config::getIblockPropsIElement(Config\Option::get('IBLOCK_ID', $site)));

    $sku = new Config\Widgets\Select('SKU_TYPE_'.$key);
    $sku->setValues(Helper\Config::getSkuDisplayTypes());

    $showRecomendation = new Config\Widgets\CheckBox('SHOW_RECOMMENDATION_'.$key);
    $showBuyWith = new Config\Widgets\CheckBox('SHOW_BUY_WITH_'.$key);
    $showBestseller = new Config\Widgets\CheckBox('SHOW_BESTSELLER_'.$key);
    $showSectionPopular = new Config\Widgets\CheckBox('SHOW_SECTION_POPULAR_'.$key);
    $showViewed = new Config\Widgets\CheckBox('SHOW_VIEWED_'.$key);
    $showTabsDetail = new Config\Widgets\CheckBox('SHOW_TABS_BITRIX_BLOCKS_'.$key);
    $showPrice = new Config\Widgets\CheckBox('SHOW_PRICE_'.$key);
    $showNavigationBlock = new Config\Widgets\CheckBox('SHOW_NAVIGATION_'.$key);






    $GroupMainElementTemplate->getWidgets()->addItem($sku);
    $GroupMainElementTemplate->getWidgets()->addItem($propFilterMode);
    $GroupMainElementTemplate->getWidgets()->addItem($propertyGrouper);
    $GroupMainElementTemplate->getWidgets()->addItem($propVideo);
    $GroupMainElementTemplate->getWidgets()->addItem($showBuyOC);
    $GroupMainElementTemplate->getWidgets()->addItem($showPreviewDelivery);
    $GroupMainElementTemplate->getWidgets()->addItem($propDisplayMode);
    $GroupMainElementTemplate->getWidgets()->addItem($propColorTypeElement);
    $GroupMainElementTemplate->getWidgets()->addItem($showFoundCheaper);
    $GroupMainElementTemplate->getWidgets()->addItem($showWantGift);
    $GroupMainElementTemplate->getWidgets()->addItem($showCheckStock);
    $GroupMainElementTemplate->getWidgets()->addItem($showZoom);
    $GroupMainElementTemplate->getWidgets()->addItem($showCrosssell);
//    $GroupMainElementTemplate->getWidgets()->addItem($showAnalog);
//    $GroupMainElementTemplate->getWidgets()->addItem($showAnalogProp);
    $GroupBitrixProductBlocks->getWidgets()->addItem($showTabsDetail);
    $GroupBitrixProductBlocks->getWidgets()->addItem($showRecomendation);
    $GroupBitrixProductBlocks->getWidgets()->addItem($showBuyWith);
    $GroupBitrixProductBlocks->getWidgets()->addItem($showBestseller);
    //$GroupMainElementTemplate->getWidgets()->addItem($showSectionPopular);
    $GroupBitrixProductBlocks->getWidgets()->addItem($showViewed);
    $GroupMainElementTemplate->getWidgets()->addItem($showPrice);
    $GroupMainElementTemplate->getWidgets()->addItem($showNavigationBlock);






    /*
        $tabs = Helper\Config::getTabs();
        foreach($tabs as $tab){
            $nameTab = new Config\Widgets\Str('NAME_TAB_'.$tab.'_');
            $GroupMainElementTemplate->getWidgets()->addItem($nameTab);
        }
        foreach($tabs as $tab){
            $activeTab = new Config\Widgets\CheckBox('ACTIVE_TAB_'.$tab.'_');
            $GroupMainElementTemplate->getWidgets()->addItem($activeTab);
        }
    */
    $Tab3->getGroups()->addItem($GroupMainElementTemplate);
    $Tab3->getGroups()->addItem($GroupBitrixProductBlocks);

}



$GroupMainOrder = new Config\Group('ORDER_MAIN');
$GroupMainOrder->getWidgets()->addItem($basketType);
$GroupMainOrder->getWidgets()->addItem($orderTemplate);
$GroupMainOrder->getWidgets()->addItem($insertLocationInOrder);
//$GroupMainOrder->getWidgets()->addItem($showMaskOrderProp);

$GroupMainPromotion = new Config\Group('PROMOTIONS_MAIN');
$GroupMainPromotion->getWidgets()->addItem($iTypePr);
$GroupMainPromotion->getWidgets()->addItem($iIdPr);
$GroupMainPromotion->getWidgets()->addItem($promotionListTemplate);

$GroupMainBlog = new Config\Group('BLOG_MAIN');
$GroupMainBlog->getWidgets()->addItem($iTypeBl);
$GroupMainBlog->getWidgets()->addItem($iIdBl);
$GroupMainBlog->getWidgets()->addItem($iTypeBlTemplate);

$GroupMainNews = new Config\Group('NEWS_MAIN');
$GroupMainNews->getWidgets()->addItem($iTypeNews);
$GroupMainNews->getWidgets()->addItem($iIdNews);
$GroupMainNews->getWidgets()->addItem($iTypeNewsTemplate);

$GroupMainBrands = new Config\Group('BRANDS_MAIN');
$GroupMainBrands->getWidgets()->addItem($iTypeBrands);
$GroupMainBrands->getWidgets()->addItem($iIdBrands);

$GroupMainBanners = new Config\Group('BANNERS_MAIN');
$GroupMainBanners->getWidgets()->addItem($iTypeBanners);
$GroupMainBanners->getWidgets()->addItem($iIdBanners);

$GroupMainAdvantages = new Config\Group('ADVANTAGES_MAIN');
$GroupMainAdvantages->getWidgets()->addItem($iTypeAdvantages);
$GroupMainAdvantages->getWidgets()->addItem($iIdAdvantages);

$GroupMainFaq = new Config\Group('FAQ_MAIN');
$GroupMainFaq->getWidgets()->addItem($iTypeFaq);
$GroupMainFaq->getWidgets()->addItem($iIdFaq);
$GroupMainVlog = new Config\Group('VLOG_MAIN');
$GroupMainVlog->getWidgets()->addItem($iTypeVlog);
$GroupMainVlog->getWidgets()->addItem($iIdVlog);

$GroupOffer = new Config\Group('OFFERS');
$GroupOffer->getWidgets()->addItem($offerLanding);
$GroupOffer->getWidgets()->addItem($offerLandingSeo);
$GroupOffer->getWidgets()->addItem($offerLanding404);

$GroupSeoTag = new Config\Group('SEO_TAG_SETTING');
$GroupSeoTag->getWidgets()->addItem($tagsPosition);
$GroupSeoTag->getWidgets()->addItem($seoDescription);

//$GroupSeoTag->getWidgets()->addItem($descriptionTop);
//$GroupSeoTag->getWidgets()->addItem($positionTopDescription);
//$GroupSeoTag->getWidgets()->addItem($descriptionBottom);
//$GroupSeoTag->getWidgets()->addItem($positionBottomDescription);
//$GroupSeoTag->getWidgets()->addItem($descriptionAdditional);
//$GroupSeoTag->getWidgets()->addItem($topTagPosition);
//$GroupSeoTag->getWidgets()->addItem($temlateTagTop);
//$GroupSeoTag->getWidgets()->addItem($bottomTagPosition);
//$GroupSeoTag->getWidgets()->addItem($temlateTagBottom);


$Options = new Config\Admin($site);

$Tab = new Config\Tab('MAIN');
$Tab->getGroups()->addItem($Group);
$Tab->getGroups()->addItem($GroupReg);
$Tab->getGroups()->addItem($GroupH);
$Tab->getGroups()->addItem($GroupF);
$Tab->getGroups()->addItem($GroupP);
$Tab->getGroups()->addItem($GroupOC);



$Tab->getGroups()->addItem($GroupValid);
$Tab2 = new Config\Tab('CATALOG');
$Tab2->getGroups()->addItem($GroupMainCatalog);
$Tab2->getGroups()->addItem($GroupSection);
$Tab2->getGroups()->addItem($GroupOffer);
if (Loader::includeModule('sotbit.seometa')) {
    $Tab2->getGroups()->addItem($GroupSeoTag);
}
$Tab2->getGroups()->addItem($GroupSort);

$Tab4 = new Config\Tab('ORDER');
$Tab4->getGroups()->addItem($GroupMainOrder);

$Tab5 = new Config\Tab('SECTIONS');
$Tab5->getGroups()->addItem($GroupC);
$Tab5->getGroups()->addItem($GroupMainPromotion);
$Tab5->getGroups()->addItem($GroupMainBlog);
$Tab5->getGroups()->addItem($GroupMainNews);
$Tab5->getGroups()->addItem($GroupMainBrands);
$Tab5->getGroups()->addItem($GroupMainBanners);
$Tab5->getGroups()->addItem($GroupMainAdvantages);
$Tab5->getGroups()->addItem($GroupMainFaq);
$Tab5->getGroups()->addItem($GroupMainVlog);

$Tab6 = new Config\Tab('SOC');
$Tab6->getGroups()->addItem($GroupSoc);
$Tab6->getGroups()->addItem($GroupShare);

//$Tab7 = new Config\Tab('SEO_TAG');
//$Tab7->getGroups()->addItem($GroupSeoTag);

$Options->getTabs()->addItem($Tab);
$Options->getTabs()->addItem($Tab2);
$Options->getTabs()->addItem($Tab7);
$Options->getTabs()->addItem($Tab3);
$Options->getTabs()->addItem($Tab4);
$Options->getTabs()->addItem($Tab5);
$Options->getTabs()->addItem($Tab6);
//if (Loader::includeModule('sotbit.seometa')){
//    $Options->getTabs()->addItem($Tab7);
//}

$Options->show();

require(
    $_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_admin.php"
);
?>
