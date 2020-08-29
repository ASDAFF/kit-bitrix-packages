<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

global $analogProducts;

use Bitrix\Main\Localization\Loc;
use Sotbit\Origami\Helper\Config;
use \Sotbit\Origami\Helper\Prop;

$this->setFrameMode(true);

$templateLibrary = ['popup', 'fx'];
$currencyList = '';

if (!empty($arResult['CURRENCIES']))
{
    $templateLibrary[] = 'currency';
    $currencyList = CUtil::PhpToJSObject($arResult['CURRENCIES'], false, true, true);
}

$template = $this->__name;
if ($this->__name == '.default')
{
    $template = '';
} else if ($this->__name == 'origami_no_tabs') {
    $template = 'NO_TABS';
}

if(Config::get('OFFER_LANDING') == 'Y' && isset($arResult['JS_OFFERS']) && isset($arResult['JS_OFFERS']) && \SotbitOrigami::$checkOfferPage)
{
    foreach($arResult['OFFERS'] as $arOffer)
    {
        foreach($arResult['JS_OFFERS'] as &$jsOffer)
        {
            if($jsOffer['ID'] == $arOffer['ID'])
            {
                $jsOffer['URL'] = $arOffer['DETAIL_PAGE_URL'];
            }
        }
    }
}

$showFoundCheaper = false;
if ($arResult['OFFERS']) {
    foreach ($arResult['OFFERS'] as $offer) {
        if ($offer['ITEM_PRICES']) {
            $showFoundCheaper = true;
            break;
        }
    }
} else {
    if ($arResult['ITEM_PRICES']) {
        $showFoundCheaper = true;
    }
}

$templateData = [
    'TEMPLATE_THEME' => $arParams['TEMPLATE_THEME'],
    'TEMPLATE_LIBRARY' => $templateLibrary,
    'CURRENCIES' => $currencyList,
    'ITEM' => [
        'ID' => $arResult['ID'],
        'IBLOCK_ID' => $arResult['IBLOCK_ID'],
        'OFFERS_SELECTED' => $arResult['OFFERS_SELECTED'],
        'JS_OFFERS' => $arResult['JS_OFFERS'],
    ],
];

unset($currencyList, $templateLibrary);

$mainId = $this->GetEditAreaId($arResult['ID']);
$itemIds = [
    'ID' => $mainId,
    'DISCOUNT_PERCENT_ID' => $mainId . '_dsc_pict',
    'STICKER_ID' => $mainId . '_sticker',
    'BIG_SLIDER_ID' => $mainId . '_big_slider',
    'BIG_IMG_CONT_ID' => $mainId . '_bigimg_cont',
    'SLIDER_CONT_ID' => $mainId . '_slider_cont',
    'OLD_PRICE_ID' => $mainId . '_old_price',
    'PRICE_ID' => $mainId . '_price',
    'DISCOUNT_PRICE_ID' => $mainId . '_price_discount',
    'PRICE_TOTAL' => $mainId . '_price_total',
    'SLIDER_CONT_OF_ID' => $mainId . '_slider_cont_',
    'QUANTITY_ID' => $mainId . '_quantity',
    'QUANTITY_DOWN_ID' => $mainId . '_quant_down',
    'QUANTITY_UP_ID' => $mainId . '_quant_up',
    'QUANTITY_MEASURE' => $mainId . '_quant_measure',
    'QUANTITY_LIMIT' => $mainId . '_quant_limit',
    'BUY_LINK' => $mainId . '_buy_link',
    'ADD_BASKET_LINK' => $mainId . '_add_basket_link',
    'BASKET_ACTIONS_ID' => $mainId . '_basket_actions',
    'NOT_AVAILABLE_MESS' => $mainId . '_not_avail',
    'COMPARE_LINK' => $mainId . '_compare_link',
    'WISH_LINK' => $mainId . '_wish_link',
    'WISH_LINK_MODIFICATION' => $mainId . '_wish_link_modification',
    'TREE_ID' => $mainId . '_skudiv',
    'DISPLAY_PROP_DIV' => $mainId . '_sku_prop',
    'DISPLAY_MAIN_PROP_DIV' => $mainId . '_main_sku_prop',
    'OFFER_GROUP' => $mainId . '_set_group_',
    'BASKET_PROP_DIV' => $mainId . '_basket_prop',
    'SUBSCRIBE_LINK' => $mainId . '_subscribe',
    'TABS_ID' => $mainId . '_tabs',
    'TAB_CONTAINERS_ID' => $mainId . '_tab_containers',
    'SMALL_CARD_PANEL_ID' => $mainId . '_small_card_panel',
    'TABS_PANEL_ID' => $mainId . '_tabs_panel',
    'ALL_PRICES' => $mainId . '_all_prices',
    'MODIFICATION_ID' => $mainId . '_modification'
];
$obName
    =
$templateData['JS_OBJ'] = 'ob' . preg_replace('/[^a-zA-Z0-9_]/', 'x', $mainId);
$name = !empty($arResult['IPROPERTY_VALUES']['ELEMENT_PAGE_TITLE'])
    ? $arResult['IPROPERTY_VALUES']['ELEMENT_PAGE_TITLE']
    : $arResult['NAME'];
$title
    = !empty($arResult['IPROPERTY_VALUES']['ELEMENT_DETAIL_PICTURE_FILE_TITLE'])
    ? $arResult['IPROPERTY_VALUES']['ELEMENT_DETAIL_PICTURE_FILE_TITLE']
    : $arResult['NAME'];
$alt = !empty($arResult['IPROPERTY_VALUES']['ELEMENT_DETAIL_PICTURE_FILE_ALT'])
    ? $arResult['IPROPERTY_VALUES']['ELEMENT_DETAIL_PICTURE_FILE_ALT']
    : $arResult['NAME'];

$haveOffers = !empty($arResult['OFFERS']);
if ($haveOffers) {
    $actualItem = isset($arResult['OFFERS'][$arResult['OFFERS_SELECTED']])
        ? $arResult['OFFERS'][$arResult['OFFERS_SELECTED']]
        : reset($arResult['OFFERS']);
    $showSliderControls = false;

    $canNotBuyOffers = [];
    foreach ($arResult['OFFERS'] as $offer) {
        if (!$offer['CAN_BUY']) {
            $canNotBuyOffers[] = $offer;
        }
    }
    foreach ($arResult['OFFERS'] as $offer) {
        if ($offer['MORE_PHOTO_COUNT'] >= 1) {
            $showSliderControls = true;
            break;
        }
    }
} else {
    $actualItem = $arResult;
    $showSliderControls = $arResult['MORE_PHOTO_COUNT'] >= 1;
}
if ($arResult['VIDEO']) {
    $showSliderControls = true;
}
$skuProps = [];
$price = $actualItem['ITEM_PRICES'][$actualItem['ITEM_PRICE_SELECTED']];
$measureRatio
    = $actualItem['ITEM_MEASURE_RATIOS'][$actualItem['ITEM_MEASURE_RATIO_SELECTED']]['RATIO'];
$showDiscount = $price['PERCENT'] > 0;

$showDescription = !empty($arResult['PREVIEW_TEXT'])
    || !empty($arResult['DETAIL_TEXT']);
$showBuyBtn = in_array('BUY', $arParams['ADD_TO_BASKET_ACTION']);
$buyButtonClassName = in_array('BUY', $arParams['ADD_TO_BASKET_ACTION_PRIMARY'])
    ? 'btn-default' : 'btn-link';
$showAddBtn = in_array('ADD', $arParams['ADD_TO_BASKET_ACTION']);
$showButtonClassName = in_array('ADD',
    $arParams['ADD_TO_BASKET_ACTION_PRIMARY']) ? 'btn-default' : 'btn-link';

$showSubscribe = $arParams['PRODUCT_SUBSCRIPTION'] === 'Y'
    && ($arResult['CATALOG_SUBSCRIBE'] === 'Y' || $haveOffers);

$arParams['MESS_BTN_BUY'] = $arParams['MESS_BTN_BUY']
    ?: Loc::getMessage('CT_BCE_CATALOG_BUY');
$arParams['MESS_BTN_ADD_TO_BASKET'] = $arParams['MESS_BTN_ADD_TO_BASKET']
    ?: Loc::getMessage('CT_BCE_CATALOG_ADD');
$arParams['MESS_NOT_AVAILABLE'] = $arParams['MESS_NOT_AVAILABLE']
    ?: Loc::getMessage('CT_BCE_CATALOG_NOT_AVAILABLE');
$arParams['MESS_BTN_COMPARE'] = $arParams['MESS_BTN_COMPARE']
    ?: Loc::getMessage('CT_BCE_CATALOG_COMPARE');
$arParams['MESS_PRICE_RANGES_TITLE'] = $arParams['MESS_PRICE_RANGES_TITLE']
    ?: Loc::getMessage('CT_BCE_CATALOG_PRICE_RANGES_TITLE');
$arParams['MESS_DESCRIPTION_TAB'] = $arParams['MESS_DESCRIPTION_TAB']
    ?: Loc::getMessage('CT_BCE_CATALOG_DESCRIPTION_TAB');
$arParams['MESS_PROPERTIES_TAB'] = $arParams['MESS_PROPERTIES_TAB']
    ?: Loc::getMessage('CT_BCE_CATALOG_PROPERTIES_TAB');
$arParams['MESS_COMMENTS_TAB'] = $arParams['MESS_COMMENTS_TAB']
    ?: Loc::getMessage('CT_BCE_CATALOG_COMMENTS_TAB');
$arParams['MESS_SHOW_MAX_QUANTITY'] = $arParams['MESS_SHOW_MAX_QUANTITY']
    ?: Loc::getMessage('CT_BCE_CATALOG_SHOW_MAX_QUANTITY');
$arParams['MESS_RELATIVE_QUANTITY_MANY']
    = $arParams['MESS_RELATIVE_QUANTITY_MANY']
    ?: Loc::getMessage('CT_BCE_CATALOG_RELATIVE_QUANTITY_MANY');
$arParams['MESS_RELATIVE_QUANTITY_FEW']
    = $arParams['MESS_RELATIVE_QUANTITY_FEW']
    ?: Loc::getMessage('CT_BCE_CATALOG_RELATIVE_QUANTITY_FEW');
$arParams['MESS_RELATIVE_QUANTITY_NO'] = $arParams['MESS_RELATIVE_QUANTITY_NO']
    ?: Loc::getMessage('CT_BCE_CATALOG_RELATIVE_QUANTITY_NO');

$positionClassMap = [
    'left' => 'product-item-label-left',
    'center' => 'product-item-label-center',
    'right' => 'product-item-label-right',
    'bottom' => 'product-item-label-bottom',
    'middle' => 'product-item-label-middle',
    'top' => 'product-item-label-top',
];

$discountPositionClass = 'product-item-label-big';
if ($arParams['SHOW_DISCOUNT_PERCENT'] === 'Y'
    && !empty($arParams['DISCOUNT_PERCENT_POSITION'])
) {
    foreach (explode('-', $arParams['DISCOUNT_PERCENT_POSITION']) as $pos) {
        $discountPositionClass .= isset($positionClassMap[$pos]) ? ' '
            . $positionClassMap[$pos] : '';
    }
}

$labelPositionClass = 'product-item-label-big';
if (!empty($arParams['LABEL_PROP_POSITION'])) {
    foreach (explode('-', $arParams['LABEL_PROP_POSITION']) as $pos) {
        $labelPositionClass .= isset($positionClassMap[$pos]) ? ' '
            . $positionClassMap[$pos] : '';
    }
}

$stores = [];
$rs = \CCatalogStore::getList([], ['ID' => $arParams['STORES']],false,false,['*','UF_*']);
while($store = $rs->Fetch())
{
    $stores[] = $store;
}

$arCoordsArray = array();
foreach ($stores as $key => $store) {
    if ($store['GPS_N'])
        $arCoordsArray[$key][0] = $store['GPS_N'];
    if ($store['GPS_S'])
        $arCoordsArray[$key][1] = $store['GPS_S'];
}

if ($arParams['STORES_MAP'] == 'YANDEX') {
    $yandex_lat = $arCoordsArray[0][0];
    $yandex_lon = $arCoordsArray[0][1];
    $position['yandex_lat'] = $yandex_lat;
    $position['yandex_lon'] = $yandex_lon;
    $position['yandex_scale'] = 14;

    foreach ($arCoordsArray as $yandexCoord) {
        $position['PLACEMARKS'][] = array(
            'LON' => $yandexCoord[1],
            'LAT' => $yandexCoord[0]
        );
    }
} else {
    $google_lat = $arCoordsArray[0][0];
    $google_lon = $arCoordsArray[0][1];
    $position['google_lat'] = $google_lat;
    $position['google_lon'] = $google_lon;
    $position['google_scale'] = 14;
    foreach ($arCoordsArray as $googleCoord) {
        $position['PLACEMARKS'][] = array(
            'LON' => $googleCoord[1],
            'LAT' => $googleCoord[0]
        );
    }
}


$arPromotions =  CCatalogDiscount::GetDiscount($arResult['ID'], $arResult['IBLOCK_ID']);
$i = 1;
$dbProductDiscounts = array();
foreach ($arPromotions as $item) {
    $dbProductDiscounts[$i] = $item;
    $i++;
}

$blockID = randString(8);
?>
    <div id="<?= $itemIds['ID'] ?>" itemscope itemtype="http://schema.org/Product"
         class="elem_check_basket_<?= $arResult['ID'] ?>">
        <div class="main_info_detail_product row">
            <div class="col-xl-6 col-lg-12">
                <div class="product-detail-photo-block">
                    <div id="<?= $itemIds['BIG_SLIDER_ID'] ?>"
                         class="product-detail-photo-block_inner">
                        <div class="product-item-detail-slider-container">
							<span class="product-item-detail-slider-close"
                                  data-entity="close-popup"></span>
                            <div class="sticker_product" <?if($dbProductDiscounts):?> data-timer="timerID_<?=$blockID?>" <?endif;?>>
                                <?
                                if ($arParams['SHOW_DISCOUNT_PERCENT'] === 'Y')
                                {
                                    if ($haveOffers)
                                    {
                                        ?>
                                        <div class="sticker_product__discount fonts__small_comment" id="<?= $itemIds['DISCOUNT_PERCENT_ID'] ?>" style="display: none;">
                                            <span><?= -$price['PERCENT'] ?>%</span>
                                        </div>
                                        <?
                                    }else{
                                        if ($price['DISCOUNT'] > 0)
                                        {
                                            ?>
                                            <div class="sticker_product__discount fonts__small_comment" id="<?= $itemIds['DISCOUNT_PERCENT_ID'] ?>">
                                                <span><?= -$price['PERCENT'] ?>%</span>
                                            </div>
                                            <?
                                        }
                                    }
                                }
                                if ($arParams['LABEL_PROP'])
                                {
                                    foreach ($arParams['LABEL_PROP'] as $label)
                                    {
                                        if (Prop::checkPropListYes($arResult['PROPERTIES'][$label]))
                                        {
                                            ?>
                                            <div>
			                                    <span class="sticker_product__hit fonts__small_comment" style="<?= ($arResult['PROPERTIES'][$label]['HINT'])
                                                        ? 'background:'
                                                        . $arResult['PROPERTIES'][$label]['HINT']
                                                        . ';'
                                                        : '' ?>">
													<?= $arResult['PROPERTIES'][$label]['NAME'] ?>
												</span>
                                            </div>
                                            <?
                                        }
                                    }
                                }
                                ?>
							</div>
							<div class="product_card__block_icon">
                                <?if($haveOffers || $arResult['CAN_BUY']):?>
                                    <?if($arResult["SHOW_DELAY"] && Config::get('SKU_TYPE_' . $template) != "LIST_OF_MODIFICATIONS"):?>
                                        <span class="product_card__block_icon-heart" data-entity="wish" id="<?= $itemIds['WISH_LINK'] ?>"  <?if($haveOffers && $arResult['CAN_BUY']):?>style="display: none;"<?endif;?>>
                                    <svg width="16" height="16">
                                        <use xlink:href="/local/templates/kit_origami/assets/img/sprite.svg#icon_favourite"></use>
                                    </svg>
                                </span>
                                <?endif;?>
                                <?endif;?>
                                <?
                                if ($arResult["SHOW_COMPARE"]  && $arParams['DISPLAY_COMPARE'] && Config::get('SKU_TYPE_' . $template) != "LIST_OF_MODIFICATIONS")
                                {
                                    ?>
                                    <div class="" id="<?= $itemIds['COMPARE_LINK'] ?>">
										<span class="product_card__block_icon-bar" data-entity="compare-checkbox">
                                            <svg width="16" height="16">
                                                <use xlink:href="/local/templates/kit_origami/assets/img/sprite.svg#icon_compare"></use>
                                            </svg>
                                        </span>
                                    </div>
                                    <?
                                }
                                ?>
                            </div>
                            <?
                            if ($actualItem['MORE_PHOTO'][0]['MEDIUM']['ID'] != 'empty'):
                                ?>
                                <div class="product-tem-zoom" data-entity="zoom-container">
                                    <svg class="product-card_icon-zoom-big" width="28px" height="28px">
                                        <use xlink:href="/local/templates/kit_origami/assets/img/sprite.svg#icon_zoom_big"></use>
                                    </svg>
                                </div>
                            <?
                            endif;
                            ?>
                            <div class="product-item-detail-slider-block swiper-container" data-entity="images-slider-block">
                                <div class="product-item-detail-slider-images-container swiper-wrapper" data-entity="images-container">
                                    <?
                                    if (!empty($actualItem['MORE_PHOTO']))
                                    {
                                        foreach($actualItem['MORE_PHOTO'] as $key => $photo)
                                        {
                                            $photoMedium = ($photo['MEDIUM']) ? $photo['MEDIUM'] : $photo['ORIGINAL'];
                                            $photoBig = ($photo['BIG']) ? $photo['BIG'] : $photo['ORIGINAL'];
                                            ?>
                                            <div data-entity="image-wrapper" class="swiper-slide">
                                                <a
                                                    class="product-item-detail-slider-image zoomIt <?= ($key
                                                    == 0 ? ' active' : '') ?>"
                                                    data-entity="image"
                                                    data-id="<?= $photo['ORIGINAL']['ID'] ?>"
                                                    <?= (Config::get('SHOW_ZOOM_'
                                                            . $template) == 'Y')
                                                        ? 'href="' . $photoBig['SRC']
                                                        . '"' : '' ?>
                                                >
                                                    <img src="<?= $photoMedium['SRC'] ?>" alt="<?= $alt ?>" title="<?= $title ?>"<?= ($key == 0 ? ' itemprop="image"' : '') ?>>
                                                </a>
                                            </div>
                                            <?
                                            if ($key == 0 && $arResult['VIDEO'])
                                            {
                                                foreach ($arResult['VIDEO'] as $i => $video)
                                                {
                                                    ?>
                                                    <div class="detail-big-video swiper-slide" data-value="<?= $i ?>">
                                                        <?= $video ?>
                                                    </div>
                                                    <?
                                                }
                                            }
                                        }
                                    } ?>
                                </div>
                            </div>
                            <div class="pswp" tabindex="-1" role="dialog"
                                 aria-hidden="true">
                                <div class="pswp__bg"></div>
                                <div class="pswp__scroll-wrap">
                                    <div class="pswp__container">
                                        <div class="pswp__item"></div>
                                        <div class="pswp__item"></div>
                                        <div class="pswp__item"></div>
                                    </div>
                                    <div class="pswp__ui pswp__ui--hidden">
                                        <div class="pswp__top-bar">
                                            <div class="pswp__counter"></div>
                                            <button class="pswp__button pswp__button--close"
                                                    title="Close (Esc)"></button>
                                            <button class="pswp__button pswp__button--share"
                                                    title="Share"></button>
                                            <button class="pswp__button pswp__button--fs"
                                                    title="Toggle fullscreen"></button>
                                            <button class="pswp__button pswp__button--zoom"
                                                    title="Zoom in/out"></button>
                                            <div class="pswp__preloader">
                                                <div class="pswp__preloader__icn">
                                                    <div class="pswp__preloader__cut">
                                                        <div class="pswp__preloader__donut"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="pswp__share-modal pswp__share-modal--hidden pswp__single-tap">
                                            <div class="pswp__share-tooltip"></div>
                                        </div>
                                        <button class="pswp__button pswp__button--arrow--left"
                                                title="Previous (arrow left)">
                                        </button>
                                        <button class="pswp__button pswp__button--arrow--right"
                                                title="Next (arrow right)">
                                        </button>
                                        <div class="pswp__caption">
                                            <div class="pswp__caption__center"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <style>
                                .pswp__bg {
                                    background: #fff;
                                }
                            </style>
                        </div>
                        <?
                        if ($showSliderControls)
                        {
                            if ($haveOffers)
                            {
                                foreach ($arResult['OFFERS'] as $keyOffer => $offer)
                                {
                                    if (!isset($offer['MORE_PHOTO_COUNT']) || $offer['MORE_PHOTO_COUNT'] <= 0)
                                    {
                                        continue;
                                    }
                                    $strVisible = $arResult['OFFERS_SELECTED'] == $keyOffer ? '' : 'none';
                                    ?>
                                    <div class="product-item-detail-slider-controls-block swiper-container gallery-thumbs" id="<?= $itemIds['SLIDER_CONT_OF_ID'].$offer['ID'] ?>" style="display: <?= $strVisible ?>;">
                                        <div class="swiper-wrapper">
                                        <?
                                        foreach ($offer['MORE_PHOTO'] as $keyPhoto => $photo)
                                        {
                                            $photoSmall = ($photo['SMALL']) ? $photo['SMALL'] : $photo['ORIGINAL'];
                                            ?>
                                            <div class="product-item-detail-slider-controls-image swiper-slide<?= ($keyPhoto
                                            == 0 ? ' active' : '') ?>"
                                                 data-entity="slider-control"
                                                 data-value="<?= $offer['ID']
                                                 . '_'
                                                 . $photo['ORIGINAL']['ID'] ?>">
                                                <img src="<?= $photoSmall['SRC'] ?>" alt="<?= $alt ?>" title="<?= $title ?>">
                                            </div>
                                            <?
                                            if ($keyPhoto == 0 && $arResult['VIDEO'])
                                            {
                                                foreach ($arResult['VIDEO'] as $i => $video)
                                                {
                                                    ?>
                                                    <div class="product-item-detail-slider-controls-video swiper-slide" data-entity="video" data-value="<?= $i ?>">
                                                        <div class="video">video</div>
                                                    </div>
                                                    <?
                                                }
                                            }
                                        }
                                        ?>
                                        </div>
                                    </div>
                                    <?
                                }
                            }else{
                                ?>
                                <div class="product-item-detail-slider-controls-block swiper-container gallery-thumbs" id="<?= $itemIds['SLIDER_CONT_ID'] ?>">
                                    <div class="swiper-wrapper">
                                    <?
                                    if (!empty($actualItem['MORE_PHOTO']))
                                    {
                                        foreach ($actualItem['MORE_PHOTO'] as $key => $photo)
                                        {
                                            $photoSmall = ($photo['SMALL']) ? $photo['SMALL'] : $photo['ORIGINAL'];
                                            ?>
                                            <div class="product-item-detail-slider-controls-image swiper-slide<?= ($key
                                            == 0 ? ' active' : '') ?>"
                                                 data-entity="slider-control"
                                                 data-value="<?= $photo['ORIGINAL']['ID'] ?>">
                                                <img src="<?= $photoSmall['SRC'] ?>" title="<?= $title ?>" alt="<?= $alt ?>">
                                            </div>
                                            <?
                                        }
                                    }
                                    ?>
                                    </div>
                                </div>
                                <?
                            }
                        }
                        ?>
                    </div>
                    <?if (Config::get('SHOW_NAVIGATION_'.$template) == 'Y'):?>
                        <div class="product-detail__nav-link product-detail__nav-link--prev">
                            <a href="<?=$arResult['PREV_ITEM']['DETAIL_PAGE_URL']?>">
                                <svg class="product-detail__nav-link-icon" width="8" height="8">
                                    <use xlink:href="/local/templates/kit_origami/assets/img/sprite.svg#icon_arrow_left_small"></use>
                                </svg>
                                <?=Loc::getMessage('DETAIL_PREV_ITEM');?>
                            </a>
                        </div>
                        <div class="product-detail__nav-link product-detail__nav-link--next">
                            <a href="<?=$arResult['NEXT_ITEM']['DETAIL_PAGE_URL']?>" ><?=Loc::getMessage('DETAIL_NEXT_ITEM');?>
                                <svg class="product-detail__nav-link-icon" width="8" height="8">
                                    <use xlink:href="/local/templates/kit_origami/assets/img/sprite.svg#icon_arrow_right_small"></use>
                                </svg>
                            </a>
                        </div>
                    <?endif;?>

                </div>
            </div>
            <div class="col-xl-6 col-lg-12">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="product_detail_title_wrapper">
                            <?

                            if ($arParams['DISPLAY_NAME'] === 'Y')
                            {
                                ?>
                                <h1 class="product_detail_title fonts__middle_title js-product-name"><?=$name?></h1>
                            <?}

                            if ($arResult['BRAND'])
                            {
                                ?>
                                <div class="product_detail_brand">
                                    <?
                                    foreach($arResult['BRAND'] as $brand)
                                    {
                                        if($brand['SRC'])
                                        {
                                            ?>
                                            <a href="<?= $brand['URL'] ?>"
                                               class="product_detail_brand__link"
                                               title="<?= $brand['NAME'] ?>">
                                                <img src="<?= $brand['SRC'] ?>"
                                                     alt="<?= $brand['NAME'] ?>"
                                                     title="<?= $brand['NAME'] ?>">
                                            </a>
                                            <?
                                        }
                                    }
                                    ?>
                                </div>
                                <?
                            }
                            ?>
                        </div>
                    </div>
                </div>
                <div class="row product_detail_info">
                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                        <div class="product_detail_info_block">
                            <div class="product_detail_info_block__line">
                                <?if($arParams['SHOW_MAX_QUANTITY'] !== 'N')
                                {
                                    if($haveOffers)
                                    {
                                        ?>
                                        <?if(!$haveOffers || in_array(Config::get('SKU_TYPE_' . $template), ['ENUMERATION', 'COMBINED'])):?>
                                        <div class="product_detail_info_block__count fonts__middle_comment" id="<?= $itemIds['QUANTITY_LIMIT'] ?>">
                                            <span class="product_detail_info_block__title fonts__middle_comment">
                                                <?= $arParams['MESS_SHOW_MAX_QUANTITY'] ?>:
                                            </span>
                                            <span class="product_card__block__presence_product_value_many">
												<?
                                                if($arParams['SHOW_MAX_QUANTITY'] === 'M')
                                                {
                                                    if($actualItem['CATALOG_QUANTITY'] == 0)
                                                    {
                                                        ?>
                                                        <span class="product_card__block__presence_product_value_no">
                                                            <i class="icon-no-waiting"></i>
                                                            <?=$arParams['~MESS_RELATIVE_QUANTITY_NO'];?>
                                                        </span>
                                                        <?
                                                    }elseif((float)$actualItem['CATALOG_QUANTITY'] / $measureRatio >= $arParams['RELATIVE_QUANTITY_FACTOR'])
                                                    {
                                                        ?>
                                                        <span class="product_card__block__presence_product_value_many">
                                                           <svg class="product-card_icon-check" width="11px" height="12px">
                                                               <use xlink:href="/local/templates/kit_origami/assets/img/sprite.svg#icon_check_checkbox"></use>
                                                           </svg>
                                                            <?=$arParams['~MESS_RELATIVE_QUANTITY_MANY'];?>
                                                        </span>
                                                        <?
                                                    }else{
                                                        ?>
                                                        <span class="product_card__block__presence_product_value_sufficient">
                                                           <svg class="product-card_icon-check" width="11px" height="12px">
                                                                   <use xlink:href="/local/templates/kit_origami/assets/img/sprite.svg#icon_check_checkbox"></use>
                                                           </svg>
                                                            <?=$arParams['~MESS_RELATIVE_QUANTITY_FEW'];?>
                                                        </span>
                                                        <?
                                                    }
                                                }else{
                                                    if($actualItem['CATALOG_QUANTITY'] == 0 )
                                                    {
                                                        ?>
                                                        <span class="product_card__block__presence_product_value_no">
                                                            <i class="icon-no-waiting"></i>
                                                            <?echo $actualItem['CATALOG_QUANTITY'].' '.$actualItem['ITEM_MEASURE']['TITLE']; ?>
                                                        </span>
                                                        <?
                                                    }else{
                                                        ?>
                                                        <span class="product_card__block__presence_product_value_many">
                                                           <svg class="product-card_icon-check" width="11px" height="12px">
                                                                   <use xlink:href="/local/templates/kit_origami/assets/img/sprite.svg#icon_check_checkbox"></use>
                                                           </svg>
                                                            <?echo $actualItem['CATALOG_QUANTITY'].' '.$actualItem['ITEM_MEASURE']['TITLE']; ?>
                                                        </span>
                                                        <?
                                                    }
                                                }
                                                ?>
											</span>
                                        </div>
                                        <?endif;?>
                                        <?
                                    } else {
                                        if ($measureRatio && $actualItem['CATALOG_QUANTITY_TRACE'] === 'Y' && $actualItem['CATALOG_CAN_BUY_ZERO'] === 'N')
                                        {
                                            ?>
                                            <div class="product_detail_info_block__count fonts__middle_comment" id="<?= $itemIds['QUANTITY_LIMIT'] ?>">
                                                <span class="product_detail_info_block__title fonts__middle_comment">
                                                    <?= $arParams['MESS_SHOW_MAX_QUANTITY'] ?>:
                                                </span>
                                                <span class="" data-entity="quantity-limit-value">
                                                    <?
                                                    if($arParams['SHOW_MAX_QUANTITY'] === 'M')
                                                    {
                                                        if($actualItem['CATALOG_QUANTITY'] == 0)
                                                        {
                                                            ?>
                                                            <span class="product_card__block__presence_product_value_no">
                                                                <i class="icon-no-waiting"></i>
                                                                <?= $arParams['~MESS_RELATIVE_QUANTITY_NO']; ?>
                                                            </span>
                                                            <?
                                                        } elseif((float)$actualItem['CATALOG_QUANTITY'] / $measureRatio >= $arParams['RELATIVE_QUANTITY_FACTOR'])
                                                        {
                                                            ?>
                                                            <span class="product_card__block__presence_product_value_many">
                                                                <svg class="product-card_icon-check" width="11px" height="12px">
                                                                    <use xlink:href="/local/templates/kit_origami/assets/img/sprite.svg#icon_check_checkbox"></use>
                                                                </svg>
                                                                <?= $arParams['~MESS_RELATIVE_QUANTITY_MANY']; ?>
                                                            </span>
                                                            <?
                                                        } else {
                                                            ?>
                                                            <span class="product_card__block__presence_product_value_sufficient">
                                                                <svg class="product-card_icon-check" width="11px" height="12px">
                                                                    <use xlink:href="/local/templates/kit_origami/assets/img/sprite.svg#icon_check_checkbox"></use>
                                                                </svg>
                                                                <?= $arParams['~MESS_RELATIVE_QUANTITY_FEW']; ?>
                                                            </span>
                                                            <?
                                                        }
                                                    }else
                                                        {
                                                        if ($actualItem['CATALOG_QUANTITY'] == 0)
                                                        {
                                                            ?>
                                                            <span class="product_card__block__presence_product_value_no">
                                                                <i class="icon-no-waiting"></i>
                                                                <?echo $actualItem['CATALOG_QUANTITY'].' '.$actualItem['ITEM_MEASURE']['TITLE']; ?>
                                                            </span>
                                                            <?
                                                        } else {
                                                            ?>
                                                            <span class="product_card__block__presence_product_value_many">
                                                                <svg class="product-card_icon-check" width="11px" height="12px">
                                                                    <use xlink:href="/local/templates/kit_origami/assets/img/sprite.svg#icon_check_checkbox"></use>
                                                                </svg>
                                                                <?echo $actualItem['CATALOG_QUANTITY'].' '.$actualItem['ITEM_MEASURE']['TITLE']; ?>
                                                            </span>
                                                            <?
                                                        }
                                                    }
                                                    ?>
                                                </span>
                                            </div>
                                            <?
                                        }
                                    }
                                } ?>
                                <?if($arResult['PROPERTIES'][Config::get('ARTICUL')]['VALUE']) :
                                    $vendorCode = $arResult['PROPERTIES'][Config::get('ARTICUL')];
                                    ?>
                                    <div class="product_detail_info_block__article fonts__middle_comment">
                                        <span class="product_detail_info_block__title"><?= $vendorCode['NAME'] ?>:</span>
                                        <span><?= $vendorCode['VALUE'] ?></span>
                                    </div>
                                <?endif;?>
                            </div>
                            <?if(!$haveOffers || in_array(Config::get('SKU_TYPE_' . $template), ['ENUMERATION', 'COMBINED'])):?>
                            <div class="product-detail-price-and-cheaper">
                                <?php
                                if($arParams["FILL_ITEM_ALL_PRICES"] == "Y")
                                {
                                    if(!empty($arResult["ALL_PRICES_NAMES"]))
                                    {
                                        ?>
                                        <div class="product_card__other_prices" id="<?= $itemIds['ALL_PRICES'] ?>">
                                            <?
                                            foreach ($arResult["ALL_PRICES_NAMES"]as $id => $idName)
                                            {
                                                $allPrice = isset($actualItem['ITEM_ALL_PRICES'][$actualItem['ITEM_PRICE_SELECTED']]["PRICES"][$id]) ? $actualItem['ITEM_ALL_PRICES'][0]["PRICES"][$id] : array();
                                                ?>
                                                <div class="product_card__other_prices_row" data-id="<?=$id?>" <?if(empty($allPrice)):?>style="display:none"<?endif;?>>
                                                    <div class="product-detail-title-price fonts__middle_comment">
                                                        <?=$arResult['ALL_PRICES_NAMES'][$id]?>:
                                                    </div>
                                                    <span class="product_card__block__new_price_product fonts__middle_title" data-price-id="<?=$id?>"><?= $allPrice['PRINT_PRICE'] ?></span>
                                                    <?if($arParams['SHOW_OLD_PRICE'] === 'Y' && (isset($arResult['CHECK_DISCOUNT']) || $allPrice['RATIO_DISCOUNT'])):?>
                                                        <span class="product_card__block__old_price_product fonts__small_title js-product-old-price" data-oldprice-id="<?=$id?>" <?if(!$allPrice['RATIO_DISCOUNT']):?>style="display:none"<?endif;?>>
                                                            <?= $allPrice['PRINT_BASE_PRICE'] ?>
                                                        </span>
                                                    <?endif;?>
                                                    <?if($arParams['SHOW_OLD_PRICE'] === 'Y' && (isset($arResult['CHECK_DISCOUNT']) || $allPrice['RATIO_DISCOUNT'])):?>
                                                        <div class="product_card__block_saving" data-discount-id="<?=$id?>" <?if(!$allPrice['RATIO_DISCOUNT']):?>style="display:none"<?endif;?>>
                                                            <?= Loc::getMessage('DETAIL_SAVE') ?>
                                                            <span class="product_card__block_saving_title">
                                                                <?= $allPrice['PRINT_DISCOUNT'] ?>
                                                            </span>
                                                        </div>
                                                    <?endif;?>
                                                </div>
                                                <?
                                            }
                                            ?>
                                        </div>
                                        <?
                                    }
                                }
                                ?>
                                <?if($arParams["FILL_ITEM_ALL_PRICES"] != "Y"):?>
                                <div class="product-detail-info-block-price">
                                    <div class="product_card__block__old_new_price">
                                        <span class="product_card__block__new_price_product fonts__middle_title js-product-price" id="<?= $itemIds['PRICE_ID'] ?>">
                                            <?= $price['PRINT_PRICE'] ?>
                                        </span>
                                        <?
                                        if ($arParams['SHOW_OLD_PRICE'] === 'Y')
                                        {
                                            ?>
                                            <span class="product_card__block__old_price_product fonts__small_title js-product-old-price" id="<?= $itemIds['OLD_PRICE_ID'] ?>" style="display: <?= ($showDiscount ? '' : 'none') ?>;">
                                                <?= $price['PRINT_BASE_PRICE'] ?>
                                            </span>
                                        <?} ?>
                                    </div>
                                    <?
                                    if ($arParams['SHOW_OLD_PRICE'] === 'Y')
                                    {
                                        ?>
                                        <div class="product_card__block_saving" id="<?= $itemIds['DISCOUNT_PRICE_ID'] ?>" <?if(!$price['DISCOUNT']):?>style="display:none"<?endif;?>>
                                            <?= Loc::getMessage('DETAIL_SAVE') ?>
                                            <span class="product_card__block_saving_title">
                                                <?= $price['PRINT_DISCOUNT'] ?>
                                            </span>
                                        </div>
                                    <? } ?>
                                </div>
                                <?endif;?>
                                <? if (Config::get('SHOW_FOUND_CHEAPER_'. $template) == 'Y' && $showFoundCheaper): ?>
                                    <div class="product_detail_info_block_cheaper" onclick="foundCheaper('<?= SITE_DIR ?>', '<?= SITE_ID ?>', '<?= $arResult['NAME'] ?>', this)">
                                        <span class="far fa-money-bill-alt">
                                            <svg class="product_detail_info_block_cheaper-icon" width="14" height="11">
                                                <use xlink:href="/local/templates/kit_origami/assets/img/sprite.svg#icon_cheaper"></use>
                                            </svg>
                                        </span>
                                        <span class="fonts__small_comment product_detail_info_block_cheaper__title"><?= Loc::getMessage('DETAIL_FOUND_CHEAPER') ?></span>
                                    </div>
                                <? endif; ?>
                            </div>
                            <?endif;?>
                            <?if($haveOffers && in_array(Config::get('SKU_TYPE_' . $template), ['LIST_OF_MODIFICATIONS'])):?>
                            <div class="product-detail-price-and-cheaper-list" id="<?= $itemIds['PRICE_ID'] ?>">
                                <p class="product-detail-price-and-cheaper-list__title-price"><?=Loc::getMessage('DETAIL_PRICE');?></p>
                                <?foreach($arResult["ITEM_PRICE_DELTA"] as $arPrice):?>
                                <p class="product-detail-price-and-cheaper-list__price"><?=Loc::getMessage('DETAIL_PRICE_FROM');?> <?=$arPrice["PRINT_PRICE"]?></p>
                                <?endforeach;?>
                                <a href="#element_prices" class="product-detail-price-and-cheaper-list__price-other"><?=Loc::getMessage('DETAIL_PRICE_ALL');?>
                                    <svg class="product-detail-price-and-cheaper-list__price-other-icon" width="8" height="7">
                                        <use xlink:href="/local/templates/kit_origami/assets/img/sprite.svg#icon_dropdown_big"></use>
                                    </svg>
                                </a>
                            </div>
                            <?endif;?>
                            <? if (!in_array(Config::get('SKU_TYPE_' . $template), ['ENUMERATION', 'COMBINED']) && $haveOffers): ?>
                                <? if (Config::get('SHOW_FOUND_CHEAPER_'. $template) == 'Y'): ?>
                                    <div class="product_detail_info_block_cheaper" onclick="foundCheaper('<?= SITE_DIR ?>', '<?= SITE_ID ?>', '<?= $arResult['NAME'] ?>', this)">
                                        <span class="far fa-money-bill-alt">
                                             <svg class="product_detail_info_block_cheaper-icon" width="14" height="11">
                                                <use xlink:href="/local/templates/kit_origami/assets/img/sprite.svg#icon_cheaper"></use>
                                            </svg>
                                        </span>
                                        <span class="fonts__small_comment product_detail_info_block_cheaper__title"><?= Loc::getMessage('DETAIL_FOUND_CHEAPER') ?></span>
                                    </div>
                                <? endif; ?>
                                <? if (Config::get('SHOW_WANT_GIFT_' . $template)!= 'N' && (!$haveOffers || !in_array(Config::get('SKU_TYPE_' . $template), ['LIST_OF_MODIFICATIONS']))): ?>
                                    <?
                                    $currentUrl = (CMain::IsHTTPS()) ? "https://" : "http://";
                                    $currentUrl .= $_SERVER["HTTP_HOST"];
                                    $currentUrl .= $arResult['DETAIL_PAGE_URL'];

                                    ?>
                                    <div class="product_detail_info_block_gift" onclick="wantGift('<?= SITE_DIR ?>', '<?= SITE_ID ?>', '<?= $currentUrl ?>', '<?= $arResult['DETAIL_PICTURE']['SRC'] ?>', this)">
			                            <span>
                                            <svg class="product_detail_info_block_gift-icon" width="13" height="13">
                                                <use xlink:href="/local/templates/kit_origami/assets/img/sprite.svg#icon_gift"></use>
                                            </svg>
			                            </span>
                                        <span class="fonts__small_comment product_detail_info_block_gift__title"><?= Loc::getMessage('DETAIL_WANT_GIFT') ?></span>
                                    </div>
                                <? endif; ?>
                            <? endif; ?>
                            <?
                            if ($haveOffers && !empty($arResult['OFFERS_PROP']) && in_array(Config::get('SKU_TYPE_' . $template), ['ENUMERATION', 'COMBINED']))
                            {
                                ?>
                                <div class="product_detail_info_block__sku" id="<?= $itemIds['TREE_ID'] ?>">
                                    <?
                                    foreach ($arResult['SKU_PROPS'] as $code => $skuProperty)
                                    {
                                        if (!isset($arResult['OFFERS_PROP'][$skuProperty['CODE']]))
                                        {
                                            continue;
                                        }

                                        $propertyId = $skuProperty['ID'];
                                        $skuProps[] = [
                                            'ID' => $propertyId,
                                            'SHOW_MODE' => $skuProperty['SHOW_MODE'],
                                            'VALUES' => $skuProperty['VALUES'],
                                            'VALUES_COUNT' => $skuProperty['VALUES_COUNT'],
                                        ];

                                        if ($code == Config::get('COLOR'))
                                        {
                                            $type = Config::get('PROP_COLOR_TYPE_ELEMENT_');
                                            ?>
                                            <div class="product-detail-info-block-main-property"
                                                 data-entity="sku-line-block">
                                                <div class="product-detail-info-block-property-title fonts__middle_comment">
                                                    <?= Loc::getMessage('DETAIL_CHOOSE') ?>
                                                    <?= strtolower(htmlspecialcharsEx($skuProperty['NAME'])) ?>
                                                    :
                                                </div>
                                                <div class="block-property-color">
                                                    <?
                                                    foreach ($skuProperty['VALUES'] as &$value)
                                                    {
                                                        if (!$value['ID'])
                                                        {
                                                            continue;
                                                        }
                                                        $value['NAME'] = htmlspecialcharsbx($value['NAME']);
                                                        ?>

                                                        <?
                                                        if ($type != "color_square"):?>
                                                            <div class="
																	block-property-color-item
																	block-property
																	<?= $type ?>
																	<?= ($value['XML_ID'] == $actualItem['PROPERTIES'][$code]['VALUE']) ? 'active' : '' ?>
																	<?
                                                            if ($canNotBuyOffers)
                                                            {
                                                                foreach ($canNotBuyOffers as $notBuyOffer)
                                                                {
                                                                    if ($value['XML_ID'] == $notBuyOffer['PROPERTIES'][$code]['VALUE'])
                                                                    {
                                                                        echo 'notallowed';
                                                                        break;
                                                                    }
                                                                }
                                                            }
                                                            ?>
																"
                                                                 data-onevalue="<?= $value['ID'] ?>"
                                                                 data-treevalue="<?= $propertyId ?>_<?= $value['ID'] ?>"
                                                                 title="<?= $skuProperty['NAME'] ?>: <?= $value['NAME'] ?>">
                                                                <img
                                                                    src="<?= $value['PICT']['SRC'] ?>"
                                                                    alt="<?= $skuProperty['NAME'] ?>: <?= $value['NAME'] ?>"
                                                                    title="<?= $skuProperty['NAME'] ?>: <?= $value['NAME'] ?>"
                                                                >
                                                            </div>
                                                        <? else: ?>
                                                            <div class="
																	block-property-color-item
																	block-property
																	<?= $type ?>
																	<?= ($value['XML_ID'] == $actualItem['PROPERTIES'][$code]['VALUE']) ? 'active' : '' ?>
																	<?
                                                            if ($canNotBuyOffers)
                                                            {
                                                                foreach ($canNotBuyOffers as $notBuyOffer)
                                                                {
                                                                    if ($value['XML_ID'] == $notBuyOffer['PROPERTIES'][$code]['VALUE'])
                                                                    {
                                                                        echo 'notallowed';
                                                                        break;
                                                                    }
                                                                }
                                                            }
                                                            ?>
																"
                                                                 data-onevalue="<?= $value['ID'] ?>"
                                                                 data-treevalue="<?= $propertyId ?>_<?= $value['ID'] ?>"
                                                                 title="<?= $skuProperty['NAME'] ?>: <?= $value['NAME'] ?>">
                                                                <?= $value['NAME'] ?>
                                                            </div>
                                                        <? endif; ?>

                                                        <?
                                                    }
                                                    ?>
                                                </div>
                                            </div>
                                            <?
                                        }else{
                                            ?>
                                            <div class="product-detail-info-block-main-property"
                                                 data-entity="sku-line-block">
                                                <div class="product-detail-info-block-property-title fonts__middle_comment">
                                                    <?= Loc::getMessage('DETAIL_CHOOSE') ?> <?= strtolower(htmlspecialcharsEx($skuProperty['NAME'])) ?>
                                                    :
                                                </div>
                                                <div class="block-property-text">
                                                    <?
                                                    if (Config::get('PROP_DISPLAY_MODE_'. $template) != 'dropdown'):?>
                                                        <?
                                                        foreach ($skuProperty['VALUES'] as &$value)
                                                        {
                                                            if (!$value['ID'])
                                                            {
                                                                continue;
                                                            }

                                                            $value['NAME'] = htmlspecialcharsbx($value['NAME']);
                                                            ?>
                                                            <div class="block-property-text-item block-property
																<?= ($value['XML_ID'] == $actualItem['PROPERTIES'][$code]['VALUE']) ? 'active' : '' ?>
																	<?
                                                            if ($canNotBuyOffers)
                                                            {
                                                                foreach ($canNotBuyOffers as $notBuyOffer)
                                                                {
                                                                    if ($value['XML_ID'] == $notBuyOffer['PROPERTIES'][$code]['VALUE'])
                                                                    {
                                                                        echo 'notallowed';
                                                                        break;
                                                                    }
                                                                }
                                                            }
                                                            ?>"
                                                                 data-onevalue="<?= $value['ID'] ?>"
                                                                 data-treevalue="<?= $propertyId ?>_<?= $value['ID'] ?>"
                                                                 title="<?= $skuProperty['NAME'] ?>: <?= $value['NAME'] ?>">
                                                                <?= $value['NAME'] ?>
                                                            </div>
                                                            <?
                                                        }
                                                        ?>
                                                    <? else: ?>
                                                        <div class="dropdown-props-wrapper">
                                                            <div class="dropdown-btn angle-down"
                                                                 onclick="window.dropdownToggle(this)">
                                                                <?= $skuProperty['NAME'] ?>
                                                            </div>
                                                            <div class="dropdown-props">
                                                                <?
                                                                foreach($skuProperty['VALUES'] as &$value)
                                                                {
                                                                    if (!$value['ID'])
                                                                    {
                                                                        continue;
                                                                    }

                                                                    $value['NAME'] = htmlspecialcharsbx($value['NAME']);
                                                                    ?>
                                                                    <div class="prop-item block-property"
                                                                         data-onevalue="<?= $value['ID'] ?>"
                                                                         data-treevalue="<?= $propertyId ?>_<?= $value['ID'] ?>">
                                                                        <?= $value['NAME'] ?>
                                                                    </div>
                                                                    <?
                                                                }
                                                                ?>
															</div>
														</div>
														<style>
															.dropdown-props-wrapper {
																position: relative;
																max-width: 270px;
															}

															.dropdown-btn {
																height: 40px;
																display: flex;
																justify-content: space-between;
																align-items: center;
																border: 1px solid #ededed;
																padding: 0 15px;
																cursor: pointer;
															}

															.dropdown-btn.angle-down:after {
																content: '\f107';
																font-family: "Font Awesome 5 Pro";
															}

															.dropdown-btn.angle-up:after {
																content: '\f106';
																font-family: "Font Awesome 5 Pro";
															}

															.dropdown-props {
																display: none;
																position: absolute;
																background: #fff;
																border: 1px solid #ededed;
																box-shadow: 0 3px 3px rgba(0, 0, 0, 0.15);
																z-index: 1;
																width: 100%;
															}

															.prop-item {
                                                                position: relative;
                                                                display: flex;
                                                                align-items: center;
                                                                padding: 0 15px;
                                                                height: 40px;
                                                                cursor: pointer;
                                                            }

                                                            .prop-item::after {
                                                                content: "";
                                                                position: absolute;
                                                                height: 1px;
                                                                width: calc(100% - 30px);
                                                                bottom: -1px;
                                                                left: 15px;
                                                                background-color: #ededed;
                                                            }

															.prop-item:hover {
																background: #ededed;
                                                            }
															.prop-item:hover::after{
																width: 100%;
                                                            }


														</style>
                                                    <?endif; ?>
												</div>
											</div>
                                            <?
                                        }
                                    }
                                    ?>
                                </div>
                                <?
                            }
                            ?>
                        </div>
                    </div>
                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                        <div id="right_detail_card"
                             class="product_detail_info_block">
                            <div class="right_detail_card__stars">
                                <?
                                if ($arParams['USE_VOTE_RATING'] === 'Y')
                                {
                                    $APPLICATION->IncludeComponent(
                                        'bitrix:iblock.vote',
                                        'origami_stars',
                                        [
                                            'CUSTOM_SITE_ID' => isset($arParams['CUSTOM_SITE_ID'])
                                                ? $arParams['CUSTOM_SITE_ID']
                                                : null,
                                            'IBLOCK_TYPE' => $arParams['IBLOCK_TYPE'],
                                            'IBLOCK_ID' => $arParams['IBLOCK_ID'],
                                            'ELEMENT_ID' => $arResult['ID'],
                                            'ELEMENT_CODE' => '',
                                            'MAX_VOTE' => '5',
                                            'VOTE_NAMES' => [
                                                '1',
                                                '2',
                                                '3',
                                                '4',
                                                '5',
                                            ],
                                            'SET_STATUS_404' => 'N',
                                            'DISPLAY_AS_RATING' => $arParams['VOTE_DISPLAY_AS_RATING'],
                                            'CACHE_TYPE' => $arParams['CACHE_TYPE'],
                                            'CACHE_TIME' => $arParams['CACHE_TIME'],
                                        ],
                                        $component,
                                        ['HIDE_ICONS' => 'Y']
                                    );
                                }
                                ?>
                            </div>

                            <?
                            $addProps = unserialize(\Sotbit\Origami\Config\Option::get('DETAIL_ADD_PROPS_' . $template));
                            if ($addProps && !empty($arResult['DISPLAY_PROPERTIES']))
                            {
                                ?>
                                <div id="all_property"
                                     class="product-detail-info-block-basic-property">
                                    <div class="basic-property-title fonts__middle_text"><?= Loc::getMessage('DETAIL_PROPS') ?></div>
                                    <?
                                    foreach ($arResult['DISPLAY_PROPERTIES'] as $key => $property)
                                    {
                                        if (in_array($key, $addProps))
                                        {
                                            ?>
                                            <div class="product-detail-info-block-property fonts__middle_comment">
                                                <span class="property-title"><?= $property['NAME'] ?>:</span>
                                                <span class="property-value"><?= (
                                                    is_array($property['DISPLAY_VALUE'])
                                                        ? implode(' / ',
                                                        $property['DISPLAY_VALUE'])
                                                        : $property['DISPLAY_VALUE']
                                                    ) ?></span>
                                            </div>
                                            <?
                                        }
                                    }
                                    if (Config::get('ACTIVE_TAB_PROPERTIES_' . $template) == 'Y')
                                    {
                                        ?>
                                        <a class="block-basic-property-link fonts__middle_comment"
                                           href="#TAB_PROPERTIES"><?= Loc::getMessage('DETAIL_OTHER_PROPS') ?>
                                            <i class="icon-nav_button"></i></a>
                                        <?
                                    }
                                    ?>
                                </div>
                                <?
                            }
                            ?>
                        </div>
                        <script>
                            if (document.querySelector('.main_info_detail_product .right_detail_card__stars')) {
                                window.movingStarsRaiting();
                            }
                        </script>

					</div>
                    <?if(!$haveOffers || in_array(Config::get('SKU_TYPE_'.$template),['ENUMERATION','COMBINED'])):?>
					<div class="product_detail_info__buy">
						<?if($arResult["SHOW_BUY"]):?>
                        <div class="product_detail_info_block product_detail_info_block-buy">
							<div class="product-detail-info-block-basket">

                            <? if (Config::get('SHOW_CHECK_STOCK_'.$template) == 'Y'): ?>
                                <?if($haveOffers || (!$haveOffers && !$arResult['CAN_BUY'])):?>
                                    <div class="product_detail_info_block_gift-wrapper">
                                        <div class="product_detail_info_block_gift" id="product_check_stock"
                                            onclick="checkStock('<?= SITE_DIR ?>', '<?= SITE_ID ?>', '<?= $arResult['NAME'] ?>', this)"
                                            style="display: <?=($actualItem['CAN_BUY'])?'none':'inline-block'?>"
                                        >
                                            <span class="fonts__small_comment product_detail_info_block_gift__title product_detail_info--availability"><?= Loc::getMessage('DETAIL_CHECK_STOCK') ?></span>
                                        </div>
                                    </div>
                                    <? endif; ?>
                                <? endif; ?>

                                <?
                                if ($showSubscribe)
                                {
                                    $APPLICATION->IncludeComponent(
                                        'bitrix:catalog.product.subscribe',
                                        'origami_default',
                                        [
                                            'CUSTOM_SITE_ID' => isset($arParams['CUSTOM_SITE_ID'])
                                                ? $arParams['CUSTOM_SITE_ID']
                                                : null,
                                            'PRODUCT_ID' => $arResult['ID'],
                                            'BUTTON_ID' => $itemIds['SUBSCRIBE_LINK'],
                                            'BUTTON_CLASS' => 'btn main_btn  product-item-detail-buy-button',
                                            'DEFAULT_DISPLAY' => !$actualItem['CAN_BUY'],
                                            'MESS_BTN_SUBSCRIBE' => $arParams['~MESS_BTN_SUBSCRIBE'],
                                        ],
                                        $component,
                                        ['HIDE_ICONS' => 'Y']
                                    );
                                    ?>

                                    <div class="product-detail-info-block-message">
										<p>
											<?= Loc::getMessage('DETAIL_SUBSCRIBE_NOTICE') ?>
										</p>

                                    </div>
                                    <?
                                }
                                ?>
                                <div class="product-detail-info-block__buy"><?
                                    if ($arParams['USE_PRODUCT_QUANTITY'] && $price['MIN_QUANTITY'] > 0)
                                    {
                                        ?>
                                        <div class="product_card__block_buy_quantity"
                                            data-entity="quantity-block">
                                            <span class="product_card__block_buy_quantity__minus fonts__small_title"
                                                id="<?= $itemIds['QUANTITY_DOWN_ID'] ?>">&#8722;</span>
                                            <input class="product_card__block_buy_quantity__input fonts__small_text"
                                                type="number"
                                                id="<?= $itemIds['QUANTITY_ID'] ?>"
                                                placeholder=""
                                                value="<?= $price['MIN_QUANTITY'] ?>">
                                            <span class="product_card__block_buy_quantity__plus fonts__small_title"
                                                id="<?= $itemIds['QUANTITY_UP_ID'] ?>">+</span>
                                        </div>
                                    <? } ?>
                                    <div id="<?= $itemIds['BASKET_ACTIONS_ID'] ?>"
                                        class="detail-basket-wrapper"
                                        style="display:    <?= ($actualItem['CAN_BUY']
                                            ? '' : 'none') ?>;">
                                        <?
                                        if ($showAddBtn)
                                        {
                                            ?>
                                            <a class="main_btn sweep-to-right"
                                            id="<?= $itemIds['ADD_BASKET_LINK'] ?>"
                                            href="javascript:void(0);">
                                                <!-- <span class="icon-bbasket"></span> <?= Loc::getMessage('CT_BCE_CATALOG_ADD') ?> -->
                                                <svg width="24" height="24">
                                                    <use xlink:href="/local/templates/kit_origami/assets/img/sprite.svg#icon_cart"></use>
                                                </svg><?= Loc::getMessage('CT_BCE_CATALOG_ADD') ?>
                                            </a>
                                            <?
                                        }
                                        if ($showBuyBtn)
                                        {
                                            ?>
                                            <a class="main_btn sweep-to-right"
                                            id="<?= $itemIds['BUY_LINK'] ?>"
                                            href="javascript:void(0);">
                                                <span class="icon-bbasket"></span> <?= Loc::getMessage('CT_BCE_CATALOG_ADD') ?>
                                            </a>
                                            <?
                                        }
                                        ?>
                                    </div>
                                    <? if (Config::get('SHOW_BUY_OC_' . $template) == 'Y' && \Bitrix\Main\Loader::includeModule('kit.orderphone')): ?>
                                        <div class="product-detail-info-block-one-click-basket"
                                            id="modal_oc">
                                            <span class="one_click_btn fonts__middle_text"><?= Loc::getMessage('DETAIL_BUY_OC') ?></span>
                                            <svg class="product-detail-info-block-one-click__icon" width="20" height="20">
                                                <use xlink:href="/local/templates/kit_origami/assets/img/sprite.svg#icon_one_click"></use>
                                            </svg>
                                        </div>
                                    <? endif; ?>
                                </div>
                            </div>
                            <div class="product-detail-info-block-path-to-basket">
                                <a href="<?= Config::get('BASKET_PAGE') ?>"
                                   class="in_basket">
                                    <span></span><?= Loc::getMessage('DETAIL_PRODUCT_IN_BASKET') ?>
                                </a>
                            </div>
                            <span id="check_offer_basket_<?= $arResult['ID'] ?>"
                                  style="display: none;"></span>

                            <? if (Config::get('SHOW_WANT_GIFT_' . $template) != 'N'): ?>
                                <?
                                $currentUrl = (CMain::IsHTTPS()) ? "https://" : "http://";
                                $currentUrl .= $_SERVER["HTTP_HOST"];
                                $currentUrl .= $arResult['DETAIL_PAGE_URL'];

                                ?>
                                <div class="product_detail_info_block_gift product_detail_info_block_gift--gift"
                                     onclick="wantGift('<?= SITE_DIR ?>', '<?= SITE_ID ?>', '<?= $currentUrl ?>', '<?= $arResult['DETAIL_PICTURE']['SRC'] ?>',  this)">
                                    <span>
                                         <svg class="product_detail_info_block_gift-icon" width="13" height="13">
                                                <use xlink:href="/local/templates/kit_origami/assets/img/sprite.svg#icon_gift"></use>
                                        </svg>
                                    </span>
                                    <span class="fonts__small_comment product_detail_info_block_gift__title"><?=Loc::getMessage('DETAIL_WANT_GIFT')?></span>
                                </div>
                            <? endif; ?>
						</div>
                        <?endif;?>
                        <?if(Config::get('SHOW_PREVIEW_DELIVERY_' . $template) != 'N'): ?>
                        <?
                            $deliveryTab = 'N';
                            foreach ($arResult['TABS'] as $i => $tab)
                            {
                                if($tab['TYPE'] == 'DELIVERY')
                                {
                                    $deliveryTab = 'Y';
                                }
                            }
                        ?>
                        <div class="product_detail_info__delivery" id="product_detail_info__delivery"><?
                        $APPLICATION->IncludeComponent(
                            'kit:regions.delivery',
                            'origami_mini',
                            [
                                'ELEMENT_ID' => $actualItem['ID'],
                                'AJAX' => 'Y',
                                'LIMIT' => 2,
                                'START_AJAX' => 'Y',
                                'SHOW_DELIVERY_PAGE' => 'Y',
                                'SHOW_DELIVERY_TAB' => $deliveryTab
                            ]
                        );
                        $this->addExternalJS("/local/templates/.default/components/kit/regions.delivery/origami_mini/script.js");
                        ?>
                        </div>
                        <?endif;?>
					</div>
                    <?endif;?>
				</div>
				<div class="row">
                    <?
                    $APPLICATION->IncludeComponent(
                        'bitrix:main.include',
                        '',
                        [
                            'AREA_FILE_SHOW' => 'file',
                            'PATH' => SITE_DIR
                                . "include/kit_origami/share.php",
                            'AREA_FILE_RECURSIVE' => 'N',
                            'EDIT_MODE' => 'html',
                        ],
                        false,
                        ['HIDE_ICONS' => 'Y']
                    );
                    ?>
                </div>

                <?if (Config::get('SHOW_NAVIGATION_'.$template) == 'Y'):?>
                    <div class="product_detail__nav-items">
                        <a href="<?=$arResult['PREV_ITEM']['DETAIL_PAGE_URL']?>" class="product_detail__nav-prev">
                            <div class="product_detail__nav-prev-arrow">
                                <svg class="product_detail__nav-icon" width="18" height="18">
                                    <use xlink:href="/local/templates/kit_origami/assets/img/sprite.svg#icon_arrow_left_new"></use>
                                </svg>
                            </div>
                            <div class="product_detail__nav-content product_detail__nav-content--prev">
                                <?if($arResult['PREV_ITEM']['PREVIEW_PICTURE'] || $arResult['PREV_ITEM']['DETAIL_PICTURE']):?>
                                    <div class="product_detail__nav-img">
                                        <img src="<?=CFile::GetPath(($arResult['PREV_ITEM']['PREVIEW_PICTURE']) ? $arResult['PREV_ITEM']['PREVIEW_PICTURE'] : $arResult['PREV_ITEM']['DETAIL_PICTURE'])?>" alt="">
                                    </div>
                                <?endif;?>
                                <div class="product_detail__nav-description">
                                    <p class="product_detail__nav-title-slide"><?=Loc::getMessage('DETAIL_PREV_ITEM');?></p>
                                    <p class="product_detail__nav-item-name"><?=$arResult['PREV_ITEM']['NAME'];?></p>
                                </div>
                            </div>
                        </a>
                        <a href="<?=$arResult['NEXT_ITEM']['DETAIL_PAGE_URL']?>" class="product_detail__nav-next">
                            <div class="product_detail__nav-next-arrow">
                                <svg class="product_detail__nav-icon" width="18" height="18">
                                    <use xlink:href="/local/templates/kit_origami/assets/img/sprite.svg#icon_arrow_right_new"></use>
                                </svg>
                            </div>
                            <div class="product_detail__nav-content product_detail__nav-content--next">
                                <?if($arResult['NEXT_ITEM']['PREVIEW_PICTURE'] || $arResult['NEXT_ITEM']['DETAIL_PICTURE']):?>
                                    <div class="product_detail__nav-img">
                                        <img src="<?=CFile::GetPath(($arResult['NEXT_ITEM']['PREVIEW_PICTURE']) ? $arResult['NEXT_ITEM']['PREVIEW_PICTURE'] : $arResult['NEXT_ITEM']['DETAIL_PICTURE'])?>" alt="">
                                    </div>
                                <?endif;?>
                                <div class="product_detail__nav-description">
                                    <p class="product_detail__nav-title-slide"><?=Loc::getMessage('DETAIL_NEXT_ITEM');?></p>
                                    <p class="product_detail__nav-item-name"><?=$arResult['NEXT_ITEM']['NAME'];?></p>
                                </div>
                            </div>
                        </a>
                    </div>
                    <script>
                        let itemName = document.getElementsByClassName('product_detail__nav-item-name');
                        const COUNT_SIMBOLS = 35;
                        if (itemName.length > 0) {
                            for (let i = 0; itemName.length > i; i++) {
                                let text = null;
                                if (itemName[i].innerText.length >= COUNT_SIMBOLS) {
                                    text = itemName[i].innerText.slice(0, COUNT_SIMBOLS) + '...';
                                } else {
                                    text = itemName[i].innerText;
                                }
                                itemName[i].textContent = text;
                            }
                        }
                    </script>
                <?endif;?>



            </div>
            <div id="element_advantages"></div>
            <?
            //$APPLICATION->ShowViewContent('element_advantages');
            ?>
        </div>
        <div id="element_prices"></div>
        <?
        if ($arResult['TABS'])
        {
            ?>
            <div class="detailed-feat">
                <div class="detailed-feat__aside detailed-feat__aside--right">
                    <div class="detailed-feat__menu">
                        <ul class="detailed-feat__menu-list">
                            <?
                            foreach ($arResult['TABS'] as $i => $tab)
                            {
                                ?>
                                <li class="detailed-feat__menu-item ">
                                    <a href="#TAB_<?= $tab['TYPE'] ?>">
                                        <?= $tab['NAME'] ?>
                                    </a>
                                </li>
                                <?
                            }
                            ?>
                        </ul>
                    </div>
                </div>
                <script>




                </script>
                <div class="detailed-feat__wrapper">
                    <?
                    foreach ($arResult['TABS'] as $i => $tab)
                    {
                        switch ($tab['TYPE'])
                        {
                            case 'DESCRIPTION':
                                if ($showDescription)
                                {
                                    ?>
                                    <div class="detailed-feat__item">
                                        <div class="detailed-feat__item-title"
                                            id="TAB_<?= $tab['TYPE'] ?>">
                                            <p><?= $tab['NAME'] ?></p>
                                        </div>
                                        <div class="detailed-feat__item-description">
                                            <?
                                            if($arResult['PREVIEW_TEXT'] != '' && ($arParams['DISPLAY_PREVIEW_TEXT_MODE'] === 'S' || ($arParams['DISPLAY_PREVIEW_TEXT_MODE'] === 'E' && $arResult['DETAIL_TEXT'] == '')))
                                            {
                                                echo $arResult['PREVIEW_TEXT_TYPE'] === 'html' ? $arResult['PREVIEW_TEXT'] : '<p>' . $arResult['PREVIEW_TEXT'] . '</p>';
                                            }

                                            if ($arResult['DETAIL_TEXT'] != '')
                                            {
                                                echo $arResult['DETAIL_TEXT_TYPE'] === 'html' ? $arResult['DETAIL_TEXT'] : '<p>' . $arResult['DETAIL_TEXT'] . '</p>';
                                            }
                                            ?>
                                        </div>
                                    </div>
                                    <?
                                }
                                break;
                            case 'PROPERTIES':
                                ?>
                                <div class="detailed-feat__item">
                                    <div class="detailed-feat__item-title"
                                         id="TAB_<?= $tab['TYPE'] ?>">
                                        <p><?= $tab['NAME'] ?></p>
                                    </div>
                                    <div class="detailed-feat__item-content">
                                        <?
                                        if ($arResult['DISPLAY_PROPERTIES'])
                                        {
                                            if(Config::get('PROPERTY_GROUPER_' . $template) == "GRUPPER")
                                            {
                                                $APPLICATION->IncludeComponent(
                                                    "redsign:grupper.list",
                                                    "origami_default",
                                                    array(
                                                        "DISPLAY_PROPERTIES" => $arResult["DISPLAY_PROPERTIES"],
                                                        "CACHE_TIME" => 36000,
                                                    ),
                                                    false
                                                );
                                            }elseif(Config::get('PROPERTY_GROUPER_' . $template) == "WEBDEBUG")
                                            {
                                                $APPLICATION->IncludeComponent(
                                                    "webdebug:propsorter",
                                                    "",
                                                    array(
                                                        "IBLOCK_TYPE" => $arResult['IBLOCK_TYPE'],
                                                        "IBLOCK_ID" => $arResult['IBLOCK_ID'],
                                                        "PROPERTIES" => $arResult['DISPLAY_PROPERTIES'],
                                                        "EXCLUDE_PROPERTIES" => array(),
                                                        "WARNING_IF_EMPTY" => "N",
                                                        "WARNING_IF_EMPTY_TEXT" => "",
                                                        "NOGROUP_SHOW" => "Y",
                                                        "NOGROUP_NAME" => "",
                                                        "MULTIPLE_SEPARATOR" => ", "
                                                    ),
                                                    false,
                                                    array('HIDE_ICONS' => 'Y')
                                                );
                                            }else{
                                                ?>
                                                <ul class="detailed-tabs__options-list detailed-tabs-list">
                                                    <?
                                                    foreach ($arResult['DISPLAY_PROPERTIES'] as $property)
                                                    {
                                                        ?>
                                                        <li class="detailed-tabs-list__item">
                                                            <p class="detailed-tabs-list__item-name">
                                                                <?= $property['NAME'] ?>
                                                            </p>
                                                            <p class="detailed-tabs-list__item-value">
                                                                <?= (is_array($property['DISPLAY_VALUE']) ? implode(' / ', $property['DISPLAY_VALUE']) : $property['DISPLAY_VALUE']) ?>
                                                            </p>
                                                        </li>
                                                        <?
                                                    }
                                                    ?>
                                                </ul>
                                                <?
                                            }
                                        }

                                        ?>
                                    </div>
                                </div>
                                <?
                                break;
                            case 'DELIVERY':
                                ?>
                                <div class="detailed-feat__item">
                                    <div class="detailed-feat__item-title"
                                         id="TAB_<?= $tab['TYPE'] ?>">
                                        <p><?= $tab['NAME'] ?></p>
                                    </div>
                                    <div class="detailed-feat__item-content detailed-tabs__delivery" id="DELIVERY_CONTENT">
                                    <?
                                    $startAjax = 'N';
                                    if($i == 0)
                                        $startAjax = 'Y';

                                    $APPLICATION->IncludeComponent(
                                        'kit:regions.delivery',
                                        'origami_default',
                                        [
                                            'ELEMENT_ID' => $actualItem['ID'],
                                            'AJAX' => 'Y',
                                            'LIMIT' => 100,
                                            'START_AJAX' => $startAjax
                                        ]
                                    );
                                    $this->addExternalCss("/local/templates/.default/components/kit/regions.delivery/origami_default/style.css");
                                    $this->addExternalJS("/local/templates/.default/components/kit/regions.delivery/origami_default/script.js");
                                    ?>
                                        <script>
                                            RegionsDelivery.clickTab();
                                        </script>
                                    </div>
                                </div>
                                <?
                                break;
                            case 'COMMENTS':
                                ?>
                                <div class="detailed-feat__item">
                                    <div class="detailed-feat__item-title"
                                         id="TAB_<?= $tab['TYPE'] ?>">
                                        <p><?= $tab['NAME'] ?></p>
                                    </div>
                                    <div class="detailed-feat__item-content detailed-tabs__reviews">
                                        <?
                                        $componentCommentsParams = [
                                            'ELEMENT_ID' => $arResult['ID'],
                                            'ELEMENT_CODE' => '',
                                            'IBLOCK_ID' => $arParams['IBLOCK_ID'],
                                            'SHOW_DEACTIVATED' => $arParams['SHOW_DEACTIVATED'],
                                            'URL_TO_COMMENT' => '',
                                            'WIDTH' => '',
                                            'COMMENTS_COUNT' => '5',
                                            'BLOG_USE' => $arParams['BLOG_USE'],
                                            'FB_USE' => $arParams['FB_USE'],
                                            'FB_APP_ID' => $arParams['FB_APP_ID'],
                                            'VK_USE' => $arParams['VK_USE'],
                                            'VK_API_ID' => $arParams['VK_API_ID'],
                                            'CACHE_TYPE' => $arParams['CACHE_TYPE'],
                                            'CACHE_TIME' => $arParams['CACHE_TIME'],
                                            'CACHE_GROUPS' => $arParams['CACHE_GROUPS'],
                                            'BLOG_TITLE' => '',
                                            'BLOG_URL' => $arParams['BLOG_URL'],
                                            'PATH_TO_SMILE' => '',
                                            'EMAIL_NOTIFY' => $arParams['BLOG_EMAIL_NOTIFY'],
                                            'AJAX_POST' => 'Y',
                                            'SHOW_SPAM' => 'Y',
                                            'SHOW_RATING' => 'N',
                                            'FB_TITLE' => '',
                                            'FB_USER_ADMIN_ID' => '',
                                            'FB_COLORSCHEME' => 'light',
                                            'FB_ORDER_BY' => 'reverse_time',
                                            'VK_TITLE' => '',
                                            'TEMPLATE_THEME' => $arParams['~TEMPLATE_THEME'],
                                        ];
                                        if (isset($arParams["USER_CONSENT"]))
                                        {
                                            $componentCommentsParams["USER_CONSENT"] = $arParams["USER_CONSENT"];
                                        }
                                        if (isset($arParams["USER_CONSENT_ID"]))
                                        {
                                            $componentCommentsParams["USER_CONSENT_ID"] = $arParams["USER_CONSENT_ID"];
                                        }
                                        if (isset($arParams["USER_CONSENT_IS_CHECKED"]))
                                        {
                                            $componentCommentsParams["USER_CONSENT_IS_CHECKED"] = $arParams["USER_CONSENT_IS_CHECKED"];
                                        }
                                        if (isset($arParams["USER_CONSENT_IS_LOADED"]))
                                        {
                                            $componentCommentsParams["USER_CONSENT_IS_LOADED"] = $arParams["USER_CONSENT_IS_LOADED"];
                                        }
                                        $APPLICATION->IncludeComponent(
                                            'bitrix:catalog.comments',
                                            'origami_element_comments',
                                            $componentCommentsParams,
                                            $component,
                                            ['HIDE_ICONS' => 'Y']
                                        );
                                        ?>
                                    </div>
                                </div>
                                <?
                                break;
                            case 'VIDEO':
                                ?>
                                <div class="detailed-feat__item">
                                    <div class="detailed-feat__item-title"
                                               id="TAB_<?= $tab['TYPE'] ?>">
                                        <p><?= $tab['NAME'] ?></p>
                                    </div>
                                    <div class="detailed-feat__item-content detailed-tabs__video">
                                        <?
                                        if ($arResult['VIDEO_CONTENT'])
                                        {
                                            ?>
                                            <ul class="detailed-tabs__video-list">
                                                <?
                                                foreach ($arResult['VIDEO_CONTENT'] as $video)
                                                {
                                                    ?>
                                                    <li class="detailed-tabs__video-item">
                                                        <div class="detailed-tabs__video-item-video">
                                                            <?= $video ?>
                                                        </div>
                                                    </li>
                                                    <?
                                                }
                                                ?>
                                            </ul>
                                            <?

                                        }
                                        ?>
                                    </div>
                                </div>
                                <?
                                break;
                            case 'AVAILABLE':
                                ?>
                                <div class="detailed-feat__item">
                                    <div class="detailed-feat__item-title"
                                         id="TAB_<?= $tab['TYPE'] ?>">
                                        <p><?= $tab['NAME'] ?></p>
                                    </div>
                                    <div class="detailed-feat__item-content detailed-tabs__availability">
                                        <div class="detailed-tabs__availability-content" id="element_tab_available"></div>
                                            <?
//                                                                                    $APPLICATION->ShowViewContent('element_tab_available');
                                            ?>
                                        <div id="map-test" class="detailed-tabs__availability-map">
                                            <?
                                                if ($arParams['STORES_MAP'] == 'GOOGLE') {
                                                    $APPLICATION->IncludeComponent(
                                                        "bitrix:map.google.view",
                                                        ".default",
                                                        array(
                                                            "KEY" => "",
                                                            "INIT_MAP_TYPE" => "MAP",
                                                            "MAP_DATA" => serialize($position),
                                                            "MAP_WIDTH" => "100%",
                                                            "MAP_HEIGHT" => "400",
                                                            "CONTROLS" => array(
                                                                0 => "ZOOM",
                                                                1 => "MINIMAP",
                                                                2 => "TYPECONTROL",
                                                                3 => "SCALELINE",
                                                            ),
                                                            "OPTIONS" => array(
                                                                0 => "ENABLE_SCROLL_ZOOM",
                                                                1 => "ENABLE_DBLCLICK_ZOOM",
                                                                2 => "ENABLE_DRAGGING",
                                                            ),
                                                            "MAP_ID" => "availibly_" . rand(0, 5000),
                                                            "COMPONENT_TEMPLATE" => ".default",
                                                            "API_KEY" => ""
                                                        ),
                                                        false
                                                    );
                                                } else {

                                                    $APPLICATION->IncludeComponent(
                                                        "bitrix:map.yandex.view",
                                                        ".default",
                                                        array(
                                                            "KEY" => "",
                                                            "INIT_MAP_TYPE" => "MAP",
                                                            "MAP_DATA" => serialize($position),
                                                            "MAP_WIDTH" => "100%",
                                                            "MAP_HEIGHT" => "400",
                                                            "CONTROLS" => array(
                                                                0 => "ZOOM",
                                                                1 => "MINIMAP",
                                                                2 => "TYPECONTROL",
                                                                3 => "SCALELINE",
                                                            ),
                                                            "OPTIONS" => array(
                                                                0 => "ENABLE_SCROLL_ZOOM",
                                                                1 => "ENABLE_DBLCLICK_ZOOM",
                                                                2 => "ENABLE_DRAGGING",
                                                            ),
                                                            "MAP_ID" => "availibly_" . rand(0, 5000),
                                                            "COMPONENT_TEMPLATE" => ".default",
                                                            "API_KEY" => ""
                                                        ),
                                                        false
                                                    );
                                                }
                                            ?>
                                        </div>
                                    </div>
                                </div>
                                <?
                                break;
                            case 'DOCS':
                                ?>
                                <div class="detailed-feat__item">
                                    <div class="detailed-feat__item-title"
                                         id="TAB_<?= $tab['TYPE'] ?>">
                                        <p><?= $tab['NAME'] ?></p>
                                    </div>
                                    <div class="detailed-feat__item-content detailed-tabs__documents <?= ($i == 0) ? 'active' : '' ?> js-tabs-content">
                                        <?
                                        if ($arResult['DOCS'])
                                        {
                                            ?>
                                            <ul class="detailed-tabs__documents-list">
                                                <?
                                                foreach ($arResult['DOCS'] as $doc)
                                                {
                                                    ?>
                                                    <li class="detailed-tabs__documents-item">
                                                        <a class="detailed-tabs__documents-wrapper" download=""
                                                           href="<?= $doc['LINK'] ?>">
                                                            <div class="detailed-tabs__documents-img">
                                                                <img src="/local/templates/kit_origami/assets/img/tabs_detailed/document-default.png"
                                                                     alt="" title="">
                                                            </div>
                                                            <div class="detailed-tabs__documents-description">
                                                                <p class="detailed-tabs__documents-name"><?= $doc['NAME'] ?></p>
                                                                <p class="detailed-tabs__documents-size"><?= $doc['SIZE'] ?></p>
                                                            </div>
                                                        </a>
                                                    </li>
                                                    <?
                                                }
                                                ?>
                                            </ul>
                                            <?
                                        } ?>
                                    </div>
                                </div>
                                <?
                                break;
                        }
                    }
                    ?>
<!--                    <div class="detailed-feat__item">-->
<!--                        <div class="detailed-feat__item-title"-->
<!--                             id="TAB_--><?//= $tab['TYPE'] ?><!--">-->
<!--                            <p>������ � ������</p>-->
<!--                        </div>-->
<!--                        <div class="detailed-tabs__content detailed-tabs__articles js-tabs-content">-->
<!--                            <div class="detailed-tabs__articles-slider swiper-container">-->
<!--                                <div class="detailed-tabs__articles-slider-wrapper swiper-wrapper">-->
<!--                                    <div class="product-article swiper-slide">-->
<!--                                        <a href="#" class="product-article__wrapper">-->
<!--                                            <div class="product-article-item__img-wrapper">-->
<!--                                                <div class="product-article-item__note">-->
<!--                                                    <svg class="product-article-item__note-icon" width="11" height="11">-->
<!--                                                        <use xlink:href="/local/templates/kit_origami/assets/img/sprite.svg#icon_clock"></use>-->
<!--                                                    </svg>-->
<!--                                                    2 ������ �����-->
<!--                                                </div>-->
<!--                                                <div class="product-article-item__img">-->
<!--                                                    <img src="/local/templates/kit_origami/assets/img/item/1.png" alt="">-->
<!--                                                </div>-->
<!--                                            </div>-->
<!--                                            <div class="product-article-item__content">-->
<!--                                                <h3 class="product-article-item__title">-->
<!--                                                    ���������� �����������. ����� � ������ ��������� ��������.-->
<!--                                                </h3>-->
<!--                                                <p class="product-article-item__description">-->
<!--                                                    ����� ������������� ������ ����������� ������� ����� ������������ � �������������� �������������-->
<!--                                                </p>-->
<!--                                                <div class="product-article-item__btn-wrapper">-->
<!--                                                    <div class="product-article-item__btn">-->
<!--                                                        <svg class="product-article-item__btn-icon" width="16" height="16">-->
<!--                                                            <use xlink:href="/local/templates/kit_origami/assets/img/sprite.svg#icon_blog"></use>-->
<!--                                                        </svg>-->
<!--                                                        <span class="product-article-item__btn-text">����</span>-->
<!--                                                    </div>-->
<!--                                                </div>-->
<!--                                            </div>-->
<!--                                        </a>-->
<!--                                    </div>-->
<!--                                    <div class="product-article swiper-slide">-->
<!--                                        <a href="#" class="product-article__wrapper">-->
<!--                                            <div class="product-article-item__img-wrapper">-->
<!--                                                <div class="product-article-item__note">-->
<!--                                                    <svg class="product-article-item__note-icon" width="11" height="11">-->
<!--                                                        <use xlink:href="/local/templates/kit_origami/assets/img/sprite.svg#icon_clock"></use>-->
<!--                                                    </svg>-->
<!--                                                    2 ������ �����-->
<!--                                                </div>-->
<!--                                                <div class="product-article-item__img">-->
<!--                                                    <img src="/local/templates/kit_origami/assets/img/item/1.png" alt="">-->
<!--                                                </div>-->
<!--                                            </div>-->
<!--                                            <div class="product-article-item__content">-->
<!--                                                <h3 class="product-article-item__title">-->
<!--                                                    ����������� ������������� ��� ������������ ������������ ����������-->
<!--                                                </h3>-->
<!--                                                <p class="product-article-item__description">-->
<!--                                                    ����� ������������� ������ ����������� ������� ����� ������������ � ��������������-->
<!--                                                </p>-->
<!--                                                <div class="product-article-item__btn-wrapper">-->
<!--                                                    <div class="product-article-item__btn">-->
<!--                                                        <svg class="product-article-item__btn-icon" width="16" height="16">-->
<!--                                                            <use xlink:href="/local/templates/kit_origami/assets/img/sprite.svg#icon_vlog"></use>-->
<!--                                                        </svg>-->
<!--                                                        <span class="product-article-item__btn-text">����</span>-->
<!--                                                    </div>-->
<!--                                                </div>-->
<!--                                            </div>-->
<!--                                        </a>-->
<!--                                    </div>-->
<!--                                    <div class="product-article swiper-slide">-->
<!--                                        <a href="#" class="product-article__wrapper">-->
<!--                                            <div class="product-article-item__img-wrapper">-->
<!--                                                <div class="product-article-item__note">-->
<!--                                                    <svg class="product-article-item__note-icon" width="11" height="11">-->
<!--                                                        <use xlink:href="/local/templates/kit_origami/assets/img/sprite.svg#icon_clock"></use>-->
<!--                                                    </svg>-->
<!--                                                    2 ������ �����-->
<!--                                                </div>-->
<!--                                                <div class="product-article-item__img">-->
<!--                                                    <img src="/local/templates/kit_origami/assets/img/item/1.png" alt="">-->
<!--                                                </div>-->
<!--                                            </div>-->
<!--                                            <div class="product-article-item__content">-->
<!--                                                <h3 class="product-article-item__title">-->
<!--                                                    ������ �� ��� ������ ������ �Nike�-->
<!--                                                </h3>-->
<!--                                                <p class="product-article-item__description">-->
<!--                                                    ����� ������������� ������ ����������� ������� ����� ������������ � �������������� �������������-->
<!--                                                </p>-->
<!--                                                <div class="product-article-item__btn-wrapper">-->
<!--                                                    <div class="product-article-item__btn">-->
<!--                                                        <svg class="product-article-item__btn-icon" width="16" height="16">-->
<!--                                                            <use xlink:href="/local/templates/kit_origami/assets/img/sprite.svg#icon_stock"></use>-->
<!--                                                        </svg>-->
<!--                                                        <span class="product-article-item__btn-text">�����</span>-->
<!--                                                    </div>-->
<!--                                                </div>-->
<!--                                            </div>-->
<!--                                        </a>-->
<!--                                    </div>-->
<!--                                    <div class="product-article swiper-slide">-->
<!--                                        <a href="#" class="product-article__wrapper">-->
<!--                                            <div class="product-article-item__img-wrapper">-->
<!--                                                <div class="product-article-item__note">-->
<!--                                                    <svg class="product-article-item__note-icon" width="11" height="11">-->
<!--                                                        <use xlink:href="/local/templates/kit_origami/assets/img/sprite.svg#icon_clock"></use>-->
<!--                                                    </svg>-->
<!--                                                    2 ������ �����-->
<!--                                                </div>-->
<!--                                                <div class="product-article-item__img">-->
<!--                                                    <img src="/local/templates/kit_origami/assets/img/item/1.png" alt="">-->
<!--                                                </div>-->
<!--                                            </div>-->
<!--                                            <div class="product-article-item__content">-->
<!--                                                <h3 class="product-article-item__title">-->
<!--                                                    ������������� ����� Apple-->
<!--                                                </h3>-->
<!--                                                <p class="product-article-item__description">-->
<!--                                                    ����� ������������� ������ ����������� ������� ����� ������������ � �������������� �������������-->
<!--                                                </p>-->
<!--                                                <div class="product-article-item__btn-wrapper">-->
<!--                                                    <div class="product-article-item__btn">-->
<!--                                                        <svg class="product-article-item__btn-icon" width="22" height="22">-->
<!--                                                            <use xlink:href="/local/templates/kit_origami/assets/img/sprite.svg#icon_news"></use>-->
<!--                                                        </svg>-->
<!--                                                        <span class="product-article-item__btn-text">�������</span>-->
<!--                                                    </div>-->
<!--                                                </div>-->
<!--                                            </div>-->
<!--                                        </a>-->
<!--                                    </div>-->
<!--                                    <div class="product-article swiper-slide">-->
<!--                                        <a href="#" class="product-article__wrapper">-->
<!--                                            <div class="product-article-item__img-wrapper">-->
<!--                                                <div class="product-article-item__note">-->
<!--                                                    <svg class="product-article-item__note-icon" width="11" height="11">-->
<!--                                                        <use xlink:href="/local/templates/kit_origami/assets/img/sprite.svg#icon_clock"></use>-->
<!--                                                    </svg>-->
<!--                                                    2 ������ �����-->
<!--                                                </div>-->
<!--                                                <div class="product-article-item__img">-->
<!--                                                    <img src="/local/templates/kit_origami/assets/img/item/1.png" alt="">-->
<!--                                                </div>-->
<!--                                            </div>-->
<!--                                            <div class="product-article-item__content">-->
<!--                                                <h3 class="product-article-item__title">-->
<!--                                                    ���������� �����������. ����� � ������ ��������� ��������.-->
<!--                                                </h3>-->
<!--                                                <p class="product-article-item__description">-->
<!--                                                    ����� ������������� ������ ����������� ������� ����� ������������ � �������������� �������������-->
<!--                                                </p>-->
<!--                                                <div class="product-article-item__btn-wrapper">-->
<!--                                                    <div class="product-article-item__btn">-->
<!--                                                        <svg class="product-article-item__btn-icon" width="16" height="16">-->
<!--                                                            <use xlink:href="/local/templates/kit_origami/assets/img/sprite.svg#icon_blog"></use>-->
<!--                                                        </svg>-->
<!--                                                        <span class="product-article-item__btn-text">����</span>-->
<!--                                                    </div>-->
<!--                                                </div>-->
<!--                                            </div>-->
<!--                                        </a>-->
<!--                                    </div>-->
<!--                                    <div class="product-article swiper-slide">-->
<!--                                        <a href="#" class="product-article__wrapper">-->
<!--                                            <div class="product-article-item__img-wrapper">-->
<!--                                                <div class="product-article-item__note">-->
<!--                                                    <svg class="product-article-item__note-icon" width="11" height="11">-->
<!--                                                        <use xlink:href="/local/templates/kit_origami/assets/img/sprite.svg#icon_clock"></use>-->
<!--                                                    </svg>-->
<!--                                                    2 ������ �����-->
<!--                                                </div>-->
<!--                                                <div class="product-article-item__img">-->
<!--                                                    <img src="/local/templates/kit_origami/assets/img/item/1.png" alt="">-->
<!--                                                </div>-->
<!--                                            </div>-->
<!--                                            <div class="product-article-item__content">-->
<!--                                                <h3 class="product-article-item__title">-->
<!--                                                    ���������� �����������. ����� � ������ ��������� ��������.-->
<!--                                                </h3>-->
<!--                                                <p class="product-article-item__description">-->
<!--                                                    ����� ������������� ������ ����������� ������� ����� ������������ � �������������� �������������-->
<!--                                                </p>-->
<!--                                                <div class="product-article-item__btn-wrapper">-->
<!--                                                    <div class="product-article-item__btn">-->
<!--                                                        <svg class="product-article-item__btn-icon" width="16" height="16">-->
<!--                                                            <use xlink:href="/local/templates/kit_origami/assets/img/sprite.svg#icon_blog"></use>-->
<!--                                                        </svg>-->
<!--                                                        <span class="product-article-item__btn-text">����</span>-->
<!--                                                    </div>-->
<!--                                                </div>-->
<!--                                            </div>-->
<!--                                        </a>-->
<!--                                    </div>-->
<!--                                </div>-->
<!--                                <div class="detailed-tabs__articles-slider-btn-next">-->
<!--                                    <svg class="detailed-tabs__articles-slider-btn-icon" width="8" height="12">-->
<!--                                        <use xlink:href="/local/templates/kit_origami/assets/img/sprite.svg#icon_arrow_right_new"></use>-->
<!--                                    </svg>-->
<!--                                </div>-->
<!--                                <div class="detailed-tabs__articles-slider-btn-prev">-->
<!--                                    <svg class="detailed-tabs__articles-slider-btn-icon" width="8" height="12">-->
<!--                                        <use xlink:href="/local/templates/kit_origami/assets/img/sprite.svg#icon_arrow_right_new"></use>-->
<!--                                    </svg>-->
<!--                                </div>-->
<!--                            </div>-->
<!--                        </div>-->
<!--                        <script>-->
<!--                            let bigSlider = document.querySelector('.swiper-container');-->
<!--                            let slider = new Swiper(bigSlider, {-->
<!--                                slidesPerView: 4,-->
<!--                                spaceBetween: 30,-->
<!--                                observer: true,-->
<!--                                observeParents: true,-->
<!--                                slidesPerView: 'auto',-->
<!--                                navigation: {-->
<!--                                    nextEl: '.detailed-tabs__articles-slider-btn-next',-->
<!--                                    prevEl: '.detailed-tabs__articles-slider-btn-prev',-->
<!--                                },-->
<!--                                breakpoints: {-->
<!--                                    // when window width is >= 320px-->
<!--                                    320: {-->
<!--                                        slidesPerView: 1.5,-->
<!--                                        spaceBetween: 15-->
<!--                                    },-->
<!--                                    520: {-->
<!--                                        slidesPerView: 2.5,-->
<!--                                        spaceBetween: 20-->
<!--                                    },-->
<!--                                    768: {-->
<!--                                        slidesPerView: 3,-->
<!--                                        spaceBetween: 20-->
<!--                                    },-->
<!--                                    1024: {-->
<!--                                        slidesPerView: 3,-->
<!--                                        spaceBetween: 24-->
<!--                                    },-->
<!--                                }-->
<!--                            });-->
<!--                        </script>-->
<!--                    </div>-->
                </div>
            </div>
            <?
        }
        //        $APPLICATION->ShowViewContent('element_prices');
        ?>
    </div>


<?

// set of products
if ($haveOffers) {
    if ($arResult['OFFER_GROUP'])
    {
        foreach ($arResult['OFFER_GROUP_VALUES'] as $offerId)
        {
            ?>
            <span id="<?= $itemIds['OFFER_GROUP'] . $offerId ?>" style="display: none;">
                <?
                $APPLICATION->IncludeComponent(
                    'bitrix:catalog.set.constructor',
                    'origami_default',
                    [
                        'CUSTOM_SITE_ID' => isset($arParams['CUSTOM_SITE_ID'])
                            ? $arParams['CUSTOM_SITE_ID'] : null,
                        'IBLOCK_ID' => $arResult['OFFERS_IBLOCK'],
                        'ELEMENT_ID' => $offerId,
                        'PRICE_CODE' => $arParams['PRICE_CODE'],
                        'BASKET_URL' => $arParams['BASKET_URL'],
                        'OFFERS_CART_PROPERTIES' => $arParams['OFFERS_CART_PROPERTIES'],
                        'CACHE_TYPE' => $arParams['CACHE_TYPE'],
                        'CACHE_TIME' => $arParams['CACHE_TIME'],
                        'CACHE_GROUPS' => $arParams['CACHE_GROUPS'],
                        'TEMPLATE_THEME' => $arParams['~TEMPLATE_THEME'],
                        'CONVERT_CURRENCY' => $arParams['CONVERT_CURRENCY'],
                        'CURRENCY_ID' => $arParams['CURRENCY_ID'],
                    ],
                    $component,
                    ['HIDE_ICONS' => 'Y']
                );
                ?>
            </span>
            <?
        }
    }
} else {
    if ($arResult['MODULES']['catalog'] && $arResult['OFFER_GROUP'])
    {
        $APPLICATION->IncludeComponent(
            'bitrix:catalog.set.constructor',
            'origami_default',
            [
                'CUSTOM_SITE_ID' => isset($arParams['CUSTOM_SITE_ID'])
                    ? $arParams['CUSTOM_SITE_ID'] : null,
                'IBLOCK_ID' => $arParams['IBLOCK_ID'],
                'ELEMENT_ID' => $arResult['ID'],
                'PRICE_CODE' => $arParams['PRICE_CODE'],
                'BASKET_URL' => $arParams['BASKET_URL'],
                'CACHE_TYPE' => $arParams['CACHE_TYPE'],
                'CACHE_TIME' => $arParams['CACHE_TIME'],
                'CACHE_GROUPS' => $arParams['CACHE_GROUPS'],
                'TEMPLATE_THEME' => $arParams['~TEMPLATE_THEME'],
                'CONVERT_CURRENCY' => $arParams['CONVERT_CURRENCY'],
                'CURRENCY_ID' => $arParams['CURRENCY_ID'],
            ],
            $component,
            ['HIDE_ICONS' => 'Y']
        );
    }
}

if ($arResult['CATALOG'] && $arParams['USE_GIFTS_DETAIL'] == 'Y' && \Bitrix\Main\ModuleManager::isModuleInstalled('sale'))
{

    if (!$arResult['OFFERS_IBLOCK'])
    {
        $sku = \CCatalogSKU::GetInfoByProductIBlock($arParams['IBLOCK_ID']);
        if ($sku['IBLOCK_ID'])
        {
            $arResult['OFFERS_IBLOCK'] = $sku['IBLOCK_ID'];
        }
    }

    CBitrixComponent::includeComponentClass('bitrix:sale.products.gift');
    $APPLICATION->IncludeComponent(
        'bitrix:sale.products.gift',
        'origami_default',
        [
            'IBLOCK_ID' => $arParams['IBLOCK_ID'],
            'CUSTOM_SITE_ID' => isset($arParams['CUSTOM_SITE_ID'])
                ? $arParams['CUSTOM_SITE_ID'] : null,
            'PRODUCT_ID_VARIABLE' => $arParams['PRODUCT_ID_VARIABLE'],
            'ACTION_VARIABLE' => $arParams['ACTION_VARIABLE'],
            'PRODUCT_DISPLAY_MODE' => $arParams['PRODUCT_DISPLAY_MODE'],
            'PRODUCT_ROW_VARIANTS' => "",
            'PAGE_ELEMENT_COUNT' => 0,
            'DEFERRED_PRODUCT_ROW_VARIANTS' => \Bitrix\Main\Web\Json::encode(
                SaleProductsGiftComponent::predictRowVariants(
                    $arParams['GIFTS_DETAIL_PAGE_ELEMENT_COUNT'],
                    $arParams['GIFTS_DETAIL_PAGE_ELEMENT_COUNT']
                )
            ),
            'RELATIVE_QUANTITY_FACTOR' => $arParams['RELATIVE_QUANTITY_FACTOR'],
            'SHOW_MAX_QUANTITY' => $arParams['SHOW_MAX_QUANTITY'],
            'DEFERRED_PAGE_ELEMENT_COUNT' => $arParams['GIFTS_DETAIL_PAGE_ELEMENT_COUNT'],
            "DISPLAY_COMPARE" => ($arParams['DISPLAY_COMPARE']) ? 'Y' : 'N',
            'SHOW_DISCOUNT_PERCENT' => $arParams['GIFTS_SHOW_DISCOUNT_PERCENT'],
            'DISCOUNT_PERCENT_POSITION' => $arParams['DISCOUNT_PERCENT_POSITION'],
            'SHOW_OLD_PRICE' => $arParams['GIFTS_SHOW_OLD_PRICE'],
            'PRODUCT_DISPLAY_MODE' => $arParams['PRODUCT_DISPLAY_MODE'],
            'PRODUCT_BLOCKS_ORDER' => $arParams['GIFTS_PRODUCT_BLOCKS_ORDER'],
            'SHOW_SLIDER' => $arParams['GIFTS_SHOW_SLIDER'],
            'SLIDER_INTERVAL' => isset($arParams['GIFTS_SLIDER_INTERVAL'])
                ? $arParams['GIFTS_SLIDER_INTERVAL'] : '',
            'SLIDER_PROGRESS' => isset($arParams['GIFTS_SLIDER_PROGRESS'])
                ? $arParams['GIFTS_SLIDER_PROGRESS'] : '',

            'TEXT_LABEL_GIFT' => $arParams['GIFTS_DETAIL_TEXT_LABEL_GIFT'],

            'LABEL_PROP_' . $arParams['IBLOCK_ID'] => [],
            'LABEL_PROP_MOBILE_' . $arParams['IBLOCK_ID'] => [],
            'LABEL_PROP_POSITION' => $arParams['LABEL_PROP_POSITION'],

            'ADD_TO_BASKET_ACTION' => (isset($arParams['ADD_TO_BASKET_ACTION'])
                ? $arParams['ADD_TO_BASKET_ACTION'] : ''),
            'MESS_BTN_BUY' => $arParams['~GIFTS_MESS_BTN_BUY'],
            'MESS_BTN_ADD_TO_BASKET' => $arParams['~GIFTS_MESS_BTN_BUY'],
            'MESS_BTN_DETAIL' => $arParams['~MESS_BTN_DETAIL'],
            'MESS_BTN_SUBSCRIBE' => $arParams['~MESS_BTN_SUBSCRIBE'],
            'SHOW_PRODUCTS_' . $arParams['IBLOCK_ID'] => 'Y',
            'PROPERTY_CODE_' . $arParams['IBLOCK_ID'] => $arParams['LIST_PROPERTY_CODE'],
            'PROPERTY_CODE_MOBILE' . $arParams['IBLOCK_ID'] => $arParams['LIST_PROPERTY_CODE_MOBILE'],
            'PROPERTY_CODE_' . $arResult['OFFERS_IBLOCK'] => $arParams['OFFER_TREE_PROPS'],
            //'OFFER_TREE_PROPS' => $arParams['OFFER_TREE_PROPS'],
            'OFFER_TREE_PROPS_' . $arResult['OFFERS_IBLOCK'] => $arParams['OFFER_TREE_PROPS'],
            'CART_PROPERTIES_' . $arResult['OFFERS_IBLOCK'] => $arParams['OFFERS_CART_PROPERTIES'],
            'ADDITIONAL_PICT_PROP_' . $arParams['IBLOCK_ID'] => (isset($arParams['ADD_PICT_PROP']) ? $arParams['ADD_PICT_PROP'] : ''),
            'ADDITIONAL_PICT_PROP_' . $arResult['OFFERS_IBLOCK'] => (isset($arParams['OFFER_ADD_PICT_PROP']) ? $arParams['OFFER_ADD_PICT_PROP'] : ''),
            'HIDE_NOT_AVAILABLE' => 'Y',
            'HIDE_NOT_AVAILABLE_OFFERS' => 'Y',
            'PRODUCT_SUBSCRIPTION' => $arParams['PRODUCT_SUBSCRIPTION'],
            'TEMPLATE_THEME' => $arParams['TEMPLATE_THEME'],
            'PRICE_CODE' => $arParams['PRICE_CODE'],
            'SHOW_PRICE_COUNT' => $arParams['SHOW_PRICE_COUNT'],
            'PRICE_VAT_INCLUDE' => $arParams['PRICE_VAT_INCLUDE'],
            'CONVERT_CURRENCY' => $arParams['CONVERT_CURRENCY'],
            'BASKET_URL' => $arParams['BASKET_URL'],
            'ADD_PROPERTIES_TO_BASKET' => $arParams['ADD_PROPERTIES_TO_BASKET'],
            'PRODUCT_PROPS_VARIABLE' => $arParams['PRODUCT_PROPS_VARIABLE'],
            'PARTIAL_PRODUCT_PROPERTIES' => $arParams['PARTIAL_PRODUCT_PROPERTIES'],
            'USE_PRODUCT_QUANTITY' => 'N',
            'PRODUCT_QUANTITY_VARIABLE' => $arParams['PRODUCT_QUANTITY_VARIABLE'],
            'CACHE_GROUPS' => $arParams['CACHE_GROUPS'],
            'POTENTIAL_PRODUCT_TO_BUY' => [
                'ID' => isset($arResult['ID'])
                    ? $arResult['ID'] : null,
                'MODULE' => isset($arResult['MODULE'])
                    ? $arResult['MODULE'] : 'catalog',
                'PRODUCT_PROVIDER_CLASS' => isset($arResult['PRODUCT_PROVIDER_CLASS'])
                    ? $arResult['PRODUCT_PROVIDER_CLASS']
                    : 'CCatalogProductProvider',
                'QUANTITY' => isset($arResult['QUANTITY'])
                    ? $arResult['QUANTITY'] : null,
                'IBLOCK_ID' => isset($arResult['IBLOCK_ID'])
                    ? $arResult['IBLOCK_ID'] : null,

                'PRIMARY_OFFER_ID' => isset($arResult['OFFERS'][$arResult['OFFERS_SELECTED']]['ID'])
                    ? $arResult['OFFERS'][$arResult['OFFERS_SELECTED']]['ID']
                    : null,
                'SECTION' => [
                    'ID' => isset($arResult['SECTION']['ID'])
                        ? $arResult['SECTION']['ID'] : null,
                    'IBLOCK_ID' => isset($arResult['SECTION']['IBLOCK_ID'])
                        ? $arResult['SECTION']['IBLOCK_ID'] : null,
                    'LEFT_MARGIN' => isset($arResult['SECTION']['LEFT_MARGIN'])
                        ? $arResult['SECTION']['LEFT_MARGIN'] : null,
                    'RIGHT_MARGIN' => isset($arResult['SECTION']['RIGHT_MARGIN'])
                        ? $arResult['SECTION']['RIGHT_MARGIN'] : null,
                ],
            ],
            'USE_ENHANCED_ECOMMERCE' => $arParams['USE_ENHANCED_ECOMMERCE'],
            'DATA_LAYER_NAME' => $arParams['DATA_LAYER_NAME'],
            'BRAND_PROPERTY' => $arParams['BRAND_PROPERTY'],
        ],
        $component,
        ['HIDE_ICONS' => 'Y']
    );
    ?>

    <div data-entity="parent-container">
        <?
        if (!isset($arParams['GIFTS_MAIN_PRODUCT_DETAIL_HIDE_BLOCK_TITLE'])
            || $arParams['GIFTS_MAIN_PRODUCT_DETAIL_HIDE_BLOCK_TITLE'] !== 'Y'
        ) {
            ?>
            <div class="catalog-block-header" data-entity="header"
                 data-showed="false" style="display: none; opacity: 0;">
                <?= ($arParams['GIFTS_MAIN_PRODUCT_DETAIL_BLOCK_TITLE']
                    ?: Loc::getMessage('CT_BCE_CATALOG_GIFTS_MAIN_BLOCK_TITLE_DEFAULT')) ?>
            </div>
            <?
        }

        $APPLICATION->IncludeComponent(
            'bitrix:sale.gift.main.products',
            'origami_default',
            [
                'CUSTOM_SITE_ID' => isset($arParams['CUSTOM_SITE_ID'])
                    ? $arParams['CUSTOM_SITE_ID'] : null,
                'PAGE_ELEMENT_COUNT' => $arParams['GIFTS_MAIN_PRODUCT_DETAIL_PAGE_ELEMENT_COUNT'],
                'LINE_ELEMENT_COUNT' => $arParams['GIFTS_MAIN_PRODUCT_DETAIL_PAGE_ELEMENT_COUNT'],
                'HIDE_BLOCK_TITLE' => 'Y',
                'BLOCK_TITLE' => $arParams['GIFTS_MAIN_PRODUCT_DETAIL_BLOCK_TITLE'],

                'OFFERS_FIELD_CODE' => $arParams['OFFERS_FIELD_CODE'],
                'OFFERS_PROPERTY_CODE' => $arParams['OFFERS_PROPERTY_CODE'],

                'AJAX_MODE' => $arParams['AJAX_MODE'],
                'IBLOCK_TYPE' => $arParams['IBLOCK_TYPE'],
                'IBLOCK_ID' => $arParams['IBLOCK_ID'],

                'OFFER_TREE_PROPS' => $arParams['OFFER_TREE_PROPS'],
                'ELEMENT_SORT_FIELD' => 'ID',
                'ELEMENT_SORT_ORDER' => 'DESC',
                //'ELEMENT_SORT_FIELD2' => $arParams['ELEMENT_SORT_FIELD2'],
                //'ELEMENT_SORT_ORDER2' => $arParams['ELEMENT_SORT_ORDER2'],
                'FILTER_NAME' => 'searchFilter',
                'SECTION_URL' => $arParams['SECTION_URL'],
                'DETAIL_URL' => $arParams['DETAIL_URL'],
                'BASKET_URL' => $arParams['BASKET_URL'],
                'ACTION_VARIABLE' => $arParams['ACTION_VARIABLE'],
                'PRODUCT_ID_VARIABLE' => $arParams['PRODUCT_ID_VARIABLE'],
                'SECTION_ID_VARIABLE' => $arParams['SECTION_ID_VARIABLE'],

                'SKU_PROPS' => $arResult['SKU_PROPS'],

                'CACHE_TYPE' => $arParams['CACHE_TYPE'],
                'CACHE_TIME' => $arParams['CACHE_TIME'],

                'CACHE_GROUPS' => $arParams['CACHE_GROUPS'],
                'SET_TITLE' => $arParams['SET_TITLE'],
                'PROPERTY_CODE' => $arParams['PROPERTY_CODE'],
                'PRICE_CODE' => $arParams['PRICE_CODE'],
                'USE_PRICE_COUNT' => $arParams['USE_PRICE_COUNT'],
                'SHOW_PRICE_COUNT' => $arParams['SHOW_PRICE_COUNT'],

                'PRICE_VAT_INCLUDE' => $arParams['PRICE_VAT_INCLUDE'],
                'CONVERT_CURRENCY' => $arParams['CONVERT_CURRENCY'],
                'CURRENCY_ID' => $arParams['CURRENCY_ID'],
                'HIDE_NOT_AVAILABLE' => 'Y',
                'HIDE_NOT_AVAILABLE_OFFERS' => 'Y',
                'TEMPLATE_THEME' => (isset($arParams['TEMPLATE_THEME'])
                    ? $arParams['TEMPLATE_THEME'] : ''),
                'PRODUCT_BLOCKS_ORDER' => $arParams['GIFTS_PRODUCT_BLOCKS_ORDER'],

                'SHOW_SLIDER' => $arParams['GIFTS_SHOW_SLIDER'],
                'SLIDER_INTERVAL' => isset($arParams['GIFTS_SLIDER_INTERVAL'])
                    ? $arParams['GIFTS_SLIDER_INTERVAL'] : '',
                'SLIDER_PROGRESS' => isset($arParams['GIFTS_SLIDER_PROGRESS'])
                    ? $arParams['GIFTS_SLIDER_PROGRESS'] : '',

                'ADD_PICT_PROP' => (isset($arParams['ADD_PICT_PROP'])
                    ? $arParams['ADD_PICT_PROP'] : ''),
                'LABEL_PROP' => (isset($arParams['LABEL_PROP'])
                    ? $arParams['LABEL_PROP'] : ''),
                'LABEL_PROP_MOBILE' => (isset($arParams['LABEL_PROP_MOBILE'])
                    ? $arParams['LABEL_PROP_MOBILE'] : ''),
                'LABEL_PROP_POSITION' => (isset($arParams['LABEL_PROP_POSITION'])
                    ? $arParams['LABEL_PROP_POSITION'] : ''),
                'OFFER_ADD_PICT_PROP' => (isset($arParams['OFFER_ADD_PICT_PROP'])
                    ? $arParams['OFFER_ADD_PICT_PROP'] : ''),
                'OFFER_TREE_PROPS' => (isset($arParams['OFFER_TREE_PROPS'])
                    ? $arParams['OFFER_TREE_PROPS'] : ''),
                'SHOW_DISCOUNT_PERCENT' => (isset($arParams['SHOW_DISCOUNT_PERCENT'])
                    ? $arParams['SHOW_DISCOUNT_PERCENT'] : ''),
                'DISCOUNT_PERCENT_POSITION' => (isset($arParams['DISCOUNT_PERCENT_POSITION'])
                    ? $arParams['DISCOUNT_PERCENT_POSITION'] : ''),
                'SHOW_OLD_PRICE' => (isset($arParams['SHOW_OLD_PRICE'])
                    ? $arParams['SHOW_OLD_PRICE'] : ''),
                'MESS_BTN_BUY' => (isset($arParams['~MESS_BTN_BUY'])
                    ? $arParams['~MESS_BTN_BUY'] : ''),
                'MESS_BTN_ADD_TO_BASKET' => (isset($arParams['~MESS_BTN_ADD_TO_BASKET'])
                    ? $arParams['~MESS_BTN_ADD_TO_BASKET'] : ''),
                'MESS_BTN_DETAIL' => (isset($arParams['~MESS_BTN_DETAIL'])
                    ? $arParams['~MESS_BTN_DETAIL'] : ''),
                'MESS_NOT_AVAILABLE' => (isset($arParams['~MESS_NOT_AVAILABLE'])
                    ? $arParams['~MESS_NOT_AVAILABLE'] : ''),
                'ADD_TO_BASKET_ACTION' => (isset($arParams['ADD_TO_BASKET_ACTION'])
                    ? $arParams['ADD_TO_BASKET_ACTION'] : ''),
                'SHOW_CLOSE_POPUP' => (isset($arParams['SHOW_CLOSE_POPUP'])
                    ? $arParams['SHOW_CLOSE_POPUP'] : ''),
                'DISPLAY_COMPARE' => (isset($arParams['DISPLAY_COMPARE'])
                    ? $arParams['DISPLAY_COMPARE'] : ''),
                'COMPARE_PATH' => (isset($arParams['COMPARE_PATH'])
                    ? $arParams['COMPARE_PATH'] : ''),
            ]
            + [
                'OFFER_ID' => empty($arResult['OFFERS'][$arResult['OFFERS_SELECTED']]['ID'])
                    ? $arResult['ID']
                    : $arResult['OFFERS'][$arResult['OFFERS_SELECTED']]['ID'],
                'SECTION_ID' => $arResult['SECTION']['ID'],
                'ELEMENT_ID' => $arResult['ID'],

                'USE_ENHANCED_ECOMMERCE' => $arParams['USE_ENHANCED_ECOMMERCE'],
                'DATA_LAYER_NAME' => $arParams['DATA_LAYER_NAME'],
                'BRAND_PROPERTY' => $arParams['BRAND_PROPERTY'],
            ],
            $component,
            ['HIDE_ICONS' => 'Y']
        );
        ?>
    </div>
    <?
}

?>

    <meta itemprop="name" content="<?= $name ?>"/>
    <meta itemprop="category" content="<?= $arResult['CATEGORY_PATH'] ?>"/>
<?
if ($haveOffers)
{
    foreach ($arResult['JS_OFFERS'] as $offer)
    {
        $currentOffersList = [];

        if (!empty($offer['TREE']) && is_array($offer['TREE']))
        {
            foreach ($offer['TREE'] as $propName => $skuId)
            {
                $propId = (int)substr($propName, 5);

                foreach ($skuProps as $prop)
                {
                    if ($prop['ID'] == $propId)
                    {
                        foreach ($prop['VALUES'] as $propId => $propValue)
                        {
                            if ($propId == $skuId)
                            {
                                $currentOffersList[] = $propValue['NAME'];
                                break;
                            }
                        }
                    }
                }
            }
        }

        $offerPrice = $offer['ITEM_PRICES'][$offer['ITEM_PRICE_SELECTED']];
        ?>
        <span itemprop="offers" itemscope itemtype="http://schema.org/Offer">
		<meta itemprop="sku" content="<?= htmlspecialcharsbx(implode('/',
            $currentOffersList)) ?>"/>
		<meta itemprop="price" content="<?= $offerPrice['RATIO_PRICE'] ?>"/>
		<meta itemprop="priceCurrency"
              content="<?= $offerPrice['CURRENCY'] ?>"/>
		<link itemprop="availability"
              href="http://schema.org/<?= ($offer['CAN_BUY'] ? 'InStock'
                  : 'OutOfStock') ?>"/>
	</span>
        <?
    }

    unset($offerPrice, $currentOffersList);
} else {
    ?>
    <span itemprop="offers" itemscope itemtype="http://schema.org/Offer">
	<meta itemprop="price" content="<?= $price['RATIO_PRICE'] ?>"/>
	<meta itemprop="priceCurrency" content="<?= $price['CURRENCY'] ?>"/>
	<link itemprop="availability"
          href="http://schema.org/<?= ($actualItem['CAN_BUY'] ? 'InStock'
              : 'OutOfStock') ?>"/>
</span>
    <?
}

$allPrices = [];

if ($haveOffers && $arParams["FILL_ITEM_ALL_PRICES"] == "Y")
{
    foreach ($arResult['OFFERS'] as $offer)
    {
        $allPrices[$offer['ID']] = $offer['ITEM_ALL_PRICES'][$actualItem['ITEM_PRICE_SELECTED']]["PRICES"];
    }
} elseif($arParams["FILL_ITEM_ALL_PRICES"] == "Y")
{
    $allPrices[$item['ID']] = $arResult['ITEM_ALL_PRICES'][$actualItem['ITEM_PRICE_SELECTED']]["PRICES"];
}

if ($haveOffers)
{
    $offerIds = [];
    $offerCodes = [];

    $useRatio = $arParams['USE_RATIO_IN_RANGES'] === 'Y';

    foreach ($arResult['JS_OFFERS'] as $ind => &$jsOffer)
    {
        $offerIds[] = (int)$jsOffer['ID'];
        $offerCodes[] = $jsOffer['CODE'];

        $fullOffer = $arResult['OFFERS'][$ind];
        $measureName = $fullOffer['ITEM_MEASURE']['TITLE'];

        $strAllProps = '';
        $strMainProps = '';
        $strPriceRangesRatio = '';
        $strPriceRanges = '';

        if ($arResult['SHOW_OFFERS_PROPS'])
        {
            if (!empty($jsOffer['DISPLAY_PROPERTIES']))
            {
                foreach ($jsOffer['DISPLAY_PROPERTIES'] as $property)
                {
                    $current
                        = '<div class="product-detail-info-block-property fonts__middle_comment"><span class="property-title">'
                        . $property['NAME']
                        . ': </span><span class="property-value">' . (
                        is_array($property['VALUE'])
                            ? implode(' / ', $property['VALUE'])
                            : $property['VALUE']
                        ) . '</span></div>';
                    $strAllProps .= $current;

                    if (isset($arParams['MAIN_BLOCK_OFFERS_PROPERTY_CODE'][$property['CODE']]))
                    {
                        $strMainProps .= $current;
                    }
                }

                unset($current);
            }
        }

        if ($arParams['USE_PRICE_COUNT'] && count($jsOffer['ITEM_QUANTITY_RANGES']) > 1)
        {
            $strPriceRangesRatio = '(' . Loc::getMessage(
                    'CT_BCE_CATALOG_RATIO_PRICE',
                    [
                        '#RATIO#' => ($useRatio
                                ? $fullOffer['ITEM_MEASURE_RATIOS'][$fullOffer['ITEM_MEASURE_RATIO_SELECTED']]['RATIO']
                                : '1'
                            ) . ' ' . $measureName,
                    ]
                ) . ')';

            foreach ($jsOffer['ITEM_QUANTITY_RANGES'] as $range)
            {
                if ($range['HASH'] !== 'ZERO-INF')
                {
                    $itemPrice = false;

                    foreach ($jsOffer['ITEM_PRICES'] as $itemPrice)
                    {
                        if ($itemPrice['QUANTITY_HASH'] === $range['HASH'])
                        {
                            break;
                        }
                    }

                    if ($itemPrice)
                    {
                        $strPriceRanges .= '<dt>' . Loc::getMessage(
                                'CT_BCE_CATALOG_RANGE_FROM',
                                [
                                    '#FROM#' => $range['SORT_FROM'] . ' '
                                        . $measureName,
                                ]
                            ) . ' ';

                        if (is_infinite($range['SORT_TO'])) {
                            $strPriceRanges .= Loc::getMessage('CT_BCE_CATALOG_RANGE_MORE');
                        } else {
                            $strPriceRanges .= Loc::getMessage(
                                'CT_BCE_CATALOG_RANGE_TO',
                                ['#TO#' => $range['SORT_TO'] . ' ' . $measureName]
                            );
                        }

                        $strPriceRanges .= '</dt><dd>' . ($useRatio
                                ? $itemPrice['PRINT_RATIO_PRICE']
                                : $itemPrice['PRINT_PRICE']) . '</dd>';
                    }
                }
            }

            unset($range, $itemPrice);
        }

        $jsOffer['DISPLAY_PROPERTIES'] = $strAllProps;
        $jsOffer['DISPLAY_PROPERTIES_MAIN_BLOCK'] = $strMainProps;
        $jsOffer['PRICE_RANGES_RATIO_HTML'] = $strPriceRangesRatio;
        $jsOffer['PRICE_RANGES_HTML'] = $strPriceRanges;
    }

    $templateData['OFFER_IDS'] = $offerIds;
    $templateData['OFFER_CODES'] = $offerCodes;
    unset($jsOffer, $strAllProps, $strMainProps, $strPriceRanges, $strPriceRangesRatio, $useRatio);

    $jsParams = [
        'CONFIG' => [
            'THANKS' => Loc::getMessage('THANKS'),
            'SUCCESS_MESSAGE' => Loc::getMessage('SUCCESS_MESSAGE'),
            'FIXED_BLOCK_BUY' => true,  //<=== params: true/false;  what is he doing: lock purchase block
            'USE_CATALOG' => $arResult['CATALOG'],
            'SHOW_QUANTITY' => $arParams['USE_PRODUCT_QUANTITY'],
            'SHOW_PRICE' => true,
            'SHOW_DISCOUNT_PERCENT' => $arParams['SHOW_DISCOUNT_PERCENT']
                === 'Y',
            'SHOW_OLD_PRICE' => $arParams['SHOW_OLD_PRICE'] === 'Y',
            'USE_PRICE_COUNT' => $arParams['USE_PRICE_COUNT'],
            'DISPLAY_COMPARE' => $arParams['DISPLAY_COMPARE'],
            'SHOW_SKU_PROPS' => $arResult['SHOW_OFFERS_PROPS'],
            'OFFER_GROUP' => $arResult['OFFER_GROUP'],
            'MAIN_PICTURE_MODE' => $arParams['DETAIL_PICTURE_MODE'],
            'ADD_TO_BASKET_ACTION' => $arParams['ADD_TO_BASKET_ACTION'],
            'SHOW_CLOSE_POPUP' => $arParams['SHOW_CLOSE_POPUP'] === 'Y',
            'SHOW_MAX_QUANTITY' => $arParams['SHOW_MAX_QUANTITY'],
            'RELATIVE_QUANTITY_FACTOR' => $arParams['RELATIVE_QUANTITY_FACTOR'],
            'TEMPLATE_THEME' => $arParams['TEMPLATE_THEME'],
            'USE_STICKERS' => true,
            'USE_SUBSCRIBE' => $showSubscribe,
            'SHOW_SLIDER' => $arParams['SHOW_SLIDER'],
            'SLIDER_INTERVAL' => $arParams['SLIDER_INTERVAL'],
            'SKU_OFF' => Config::get('SKU_TYPE_' . $template) == "LIST_OF_MODIFICATIONS",
            'ALT' => $alt,
            'TITLE' => $title,
            'SITE_DIR' => SITE_DIR,
            'SITE_ID' => SITE_ID,
            'IBLOCK_ID' => $arParams['IBLOCK_ID'],
            'MAGNIFIER_ZOOM_PERCENT' => 200,
            'SHOW_ZOOM' => Config::get('SHOW_ZOOM_' . $template),
            'USE_ENHANCED_ECOMMERCE' => $arParams['USE_ENHANCED_ECOMMERCE'],
            'DATA_LAYER_NAME' => $arParams['DATA_LAYER_NAME'],
            'BRAND_PROPERTY' => !empty($arResult['DISPLAY_PROPERTIES'][$arParams['BRAND_PROPERTY']])
                ? $arResult['DISPLAY_PROPERTIES'][$arParams['BRAND_PROPERTY']]['DISPLAY_VALUE']
                : null,
        ],
        'PRODUCT_TYPE' => $arResult['CATALOG_TYPE'],
        'VISUAL' => $itemIds,
        'DEFAULT_PICTURE' => [
            'PREVIEW_PICTURE' => $arResult['DEFAULT_PICTURE'],
            'DETAIL_PICTURE' => $arResult['DEFAULT_PICTURE'],
        ],
        'MESS' => [
            'NO' => $arParams['~MESS_RELATIVE_QUANTITY_NO'],
        ],
        'PRODUCT' => [
            'ID' => $arResult['ID'],
            'ACTIVE' => $arResult['ACTIVE'],
            'NAME' => $arResult['~NAME'],
            'CATEGORY' => $arResult['CATEGORY_PATH'],
            'ALL_PRICES' => $allPrices,
            'VIDEOS' => $arResult['VIDEOS'],
        ],
        'BASKET' => [
            'QUANTITY' => $arParams['PRODUCT_QUANTITY_VARIABLE'],
            'BASKET_URL' => $arParams['BASKET_URL'],
            'BASKET_URL_AJAX' => SITE_DIR . 'include/ajax/buy.php',
            'SKU_PROPS' => $arResult['OFFERS_PROP_CODES'],
            'ADD_URL_TEMPLATE' => $arResult['~ADD_URL_TEMPLATE'],
            'BUY_URL_TEMPLATE' => $arResult['~BUY_URL_TEMPLATE'],
        ],
        'WISH' => [
            'WISHES' => [],
            'WISH_URL_TEMPLATE' => SITE_DIR . 'include/ajax/wish.php',
        ],
        'OFFERS' => $arResult['JS_OFFERS'],
        'OFFER_SELECTED' => $arResult['OFFERS_SELECTED'],
        'TREE_PROPS' => $skuProps,
    ];
} else {
    $emptyProductProperties = empty($arResult['PRODUCT_PROPERTIES']);
    if ($arParams['ADD_PROPERTIES_TO_BASKET'] === 'Y'
        && !$emptyProductProperties
    ) {
        ?>
        <div id="<?= $itemIds['BASKET_PROP_DIV'] ?>" style="display: none;">
            <?
            if (!empty($arResult['PRODUCT_PROPERTIES_FILL'])) {
                foreach (
                    $arResult['PRODUCT_PROPERTIES_FILL'] as $propId => $propInfo
                ) {
                    ?>
                    <input type="hidden"
                           name="<?= $arParams['PRODUCT_PROPS_VARIABLE'] ?>[<?= $propId ?>]"
                           value="<?= htmlspecialcharsbx($propInfo['ID']) ?>">
                    <?
                    unset($arResult['PRODUCT_PROPERTIES'][$propId]);
                }
            }

            $emptyProductProperties = empty($arResult['PRODUCT_PROPERTIES']);
            if (!$emptyProductProperties) {
                ?>
                <table>
                    <?
                    foreach (
                        $arResult['PRODUCT_PROPERTIES'] as $propId => $propInfo
                    ) {
                        ?>
                        <tr>
                            <td><?= $arResult['PROPERTIES'][$propId]['NAME'] ?></td>
                            <td>
                                <?
                                if (
                                    $arResult['PROPERTIES'][$propId]['PROPERTY_TYPE']
                                    === 'L'
                                    && $arResult['PROPERTIES'][$propId]['LIST_TYPE']
                                    === 'C'
                                ) {
                                    foreach (
                                        $propInfo['VALUES'] as $valueId =>
                                        $value
                                    ) {
                                        ?>
                                        <label>
                                            <input type="radio"
                                                   name="<?= $arParams['PRODUCT_PROPS_VARIABLE'] ?>[<?= $propId ?>]"
                                                   value="<?= $valueId ?>" <?= ($valueId
                                            == $propInfo['SELECTED']
                                                ? '"checked"' : '') ?>>
                                            <?= $value ?>
                                        </label>
                                        <br>
                                        <?
                                    }
                                } else {
                                    ?>
                                    <select name="<?= $arParams['PRODUCT_PROPS_VARIABLE'] ?>[<?= $propId ?>]">
                                        <?
                                        foreach (
                                            $propInfo['VALUES'] as $valueId =>
                                            $value
                                        ) {
                                            ?>
                                            <option value="<?= $valueId ?>" <?= ($valueId
                                            == $propInfo['SELECTED']
                                                ? '"selected"' : '') ?>>
                                                <?= $value ?>
                                            </option>
                                            <?
                                        }
                                        ?>
                                    </select>
                                    <?
                                }
                                ?>
                            </td>
                        </tr>
                        <?
                    }
                    ?>
                </table>
                <?
            }
            ?>
        </div>
        <?
    }

    $jsParams = [
        'CONFIG' => [
            'THANKS' => Loc::getMessage('THANKS'),
            'SUCCESS_MESSAGE' => Loc::getMessage('SUCCESS_MESSAGE'),
            'FIXED_BLOCK_BUY' => true,  //<=== params: true/false;  what is he doing: lock purchase block
            'USE_CATALOG' => $arResult['CATALOG'],
            'SHOW_QUANTITY' => $arParams['USE_PRODUCT_QUANTITY'],
            'SHOW_PRICE' => !empty($arResult['ITEM_PRICES']),
            'SHOW_DISCOUNT_PERCENT' => $arParams['SHOW_DISCOUNT_PERCENT']
                === 'Y',
            'SHOW_OLD_PRICE' => $arParams['SHOW_OLD_PRICE'] === 'Y',
            'USE_PRICE_COUNT' => $arParams['USE_PRICE_COUNT'],
            'DISPLAY_COMPARE' => $arParams['DISPLAY_COMPARE'],
            'MAIN_PICTURE_MODE' => $arParams['DETAIL_PICTURE_MODE'],
            'ADD_TO_BASKET_ACTION' => $arParams['ADD_TO_BASKET_ACTION'],
            'SHOW_CLOSE_POPUP' => $arParams['SHOW_CLOSE_POPUP'] === 'Y',
            'SHOW_MAX_QUANTITY' => $arParams['SHOW_MAX_QUANTITY'],
            'RELATIVE_QUANTITY_FACTOR' => $arParams['RELATIVE_QUANTITY_FACTOR'],
            'TEMPLATE_THEME' => $arParams['TEMPLATE_THEME'],
            'USE_STICKERS' => true,
            'USE_SUBSCRIBE' => $showSubscribe,
            'SHOW_SLIDER' => $arParams['SHOW_SLIDER'],
            'SLIDER_INTERVAL' => $arParams['SLIDER_INTERVAL'],
            'ALT' => $alt,
            'TITLE' => $title,
            'SITE_DIR' => SITE_DIR,
            'SITE_ID' => SITE_ID,
            'IBLOCK_ID' => $arParams['IBLOCK_ID'],
            'SHOW_ZOOM' => Config::get('SHOW_ZOOM_' . $template),
            'MAGNIFIER_ZOOM_PERCENT' => 200,
            'USE_ENHANCED_ECOMMERCE' => $arParams['USE_ENHANCED_ECOMMERCE'],
            'DATA_LAYER_NAME' => $arParams['DATA_LAYER_NAME'],
            'BRAND_PROPERTY' => !empty($arResult['DISPLAY_PROPERTIES'][$arParams['BRAND_PROPERTY']])
                ? $arResult['DISPLAY_PROPERTIES'][$arParams['BRAND_PROPERTY']]['DISPLAY_VALUE']
                : null,
        ],
        'VISUAL' => $itemIds,
        'ADD_PRODUCT_TO_BASKET_MODE' => (Config::get('SHOW_POPUP_ADD_BASKET') == 'Y') ? 'popup' : 'no-popup',
        'PRODUCT_TYPE' => $arResult['CATALOG_TYPE'],
        'MESS' => [
            'NO' => $arParams['~MESS_RELATIVE_QUANTITY_NO'],
        ],
        'PRODUCT' => [
            'ID' => $arResult['ID'],
            'ACTIVE' => $arResult['ACTIVE'],
            'PICT' => reset($arResult['MORE_PHOTO']),
            'NAME' => $arResult['~NAME'],
            'SUBSCRIPTION' => true,
            'ITEM_PRICE_MODE' => $arResult['ITEM_PRICE_MODE'],
            'ITEM_PRICES' => $arResult['ITEM_PRICES'],
            'ITEM_PRICE_SELECTED' => $arResult['ITEM_PRICE_SELECTED'],
            'ITEM_QUANTITY_RANGES' => $arResult['ITEM_QUANTITY_RANGES'],
            'ITEM_QUANTITY_RANGE_SELECTED' => $arResult['ITEM_QUANTITY_RANGE_SELECTED'],
            'ITEM_MEASURE_RATIOS' => $arResult['ITEM_MEASURE_RATIOS'],
            'ITEM_MEASURE_RATIO_SELECTED' => $arResult['ITEM_MEASURE_RATIO_SELECTED'],
            'SLIDER_COUNT' => $arResult['MORE_PHOTO_COUNT'],
            'SLIDER' => $arResult['MORE_PHOTO'],
            'CAN_BUY' => $arResult['CAN_BUY'],
            'CHECK_QUANTITY' => $arResult['CHECK_QUANTITY'],
            'QUANTITY_FLOAT' => is_float($arResult['ITEM_MEASURE_RATIOS'][$arResult['ITEM_MEASURE_RATIO_SELECTED']]['RATIO']),
            'MAX_QUANTITY' => $arResult['CATALOG_QUANTITY'],
            'STEP_QUANTITY' => $arResult['ITEM_MEASURE_RATIOS'][$arResult['ITEM_MEASURE_RATIO_SELECTED']]['RATIO'],
            'CATEGORY' => $arResult['CATEGORY_PATH'],
            'ALL_PRICES' => $allPrices,
            'VIDEOS' => $arResult['VIDEOS'],
        ],
        'BASKET' => [
            'ADD_PROPS' => $arParams['ADD_PROPERTIES_TO_BASKET'] === 'Y',
            'QUANTITY' => $arParams['PRODUCT_QUANTITY_VARIABLE'],
            'PROPS' => $arParams['PRODUCT_PROPS_VARIABLE'],
            'EMPTY_PROPS' => $emptyProductProperties,
            'BASKET_URL' => $arParams['BASKET_URL'],
            'BASKET_URL_AJAX' => SITE_DIR . 'include/ajax/buy.php',
            'ADD_URL_TEMPLATE' => $arResult['~ADD_URL_TEMPLATE'],
            'BUY_URL_TEMPLATE' => $arResult['~BUY_URL_TEMPLATE'],
        ],
        'WISH' => [
            'WISHES' => $arResult['WISHES'],
            'WISH_URL_TEMPLATE' => SITE_DIR . 'include/ajax/wish.php',
        ],
    ];
}

$jsParams['CONFIG']['DETAIL_IMAGE_RESOLUTION'] = $arParams['IMAGE_RESOLUTION']; //  <===== params: 16by9; 4by3; 1by1

if ($arParams['DISPLAY_COMPARE']) {
    $jsParams['COMPARE'] = [
        'COMPARE_URL_TEMPLATE' => $arResult['~COMPARE_URL_TEMPLATE'],
        'COMPARE_DELETE_URL_TEMPLATE' => $arResult['~COMPARE_DELETE_URL_TEMPLATE'],
        'COMPARE_PATH' => $arParams['COMPARE_PATH'],
    ];
}

// $jsParams['CONFIG']['BUY_BTN_FIXED'] = true;
?>
    <script>
        BX.message({
            ECONOMY_INFO_MESSAGE: '<?=GetMessageJS('CT_BCE_CATALOG_ECONOMY_INFO2')?>',
            TITLE_ERROR: '<?=GetMessageJS('CT_BCE_CATALOG_TITLE_ERROR')?>',
            TITLE_BASKET_PROPS: '<?=GetMessageJS('CT_BCE_CATALOG_TITLE_BASKET_PROPS')?>',
            BASKET_UNKNOWN_ERROR: '<?=GetMessageJS('CT_BCE_CATALOG_BASKET_UNKNOWN_ERROR')?>',
            BTN_SEND_PROPS: '<?=GetMessageJS('CT_BCE_CATALOG_BTN_SEND_PROPS')?>',
            BTN_MESSAGE_BASKET_REDIRECT: '<?=GetMessageJS('CT_BCE_CATALOG_BTN_MESSAGE_BASKET_REDIRECT')?>',
            BTN_MESSAGE_CLOSE: '<?=GetMessageJS('CT_BCE_CATALOG_BTN_MESSAGE_CLOSE')?>',
            BTN_MESSAGE_CLOSE_POPUP: '<?=GetMessageJS('CT_BCE_CATALOG_BTN_MESSAGE_CLOSE_POPUP')?>',
            TITLE_SUCCESSFUL: '<?=GetMessageJS('CT_BCE_CATALOG_ADD_TO_BASKET_OK')?>',
            COMPARE_MESSAGE_OK: '<?=GetMessageJS('CT_BCE_CATALOG_MESS_COMPARE_OK')?>',
            COMPARE_UNKNOWN_ERROR: '<?=GetMessageJS('CT_BCE_CATALOG_MESS_COMPARE_UNKNOWN_ERROR')?>',
            COMPARE_TITLE: '<?=GetMessageJS('CT_BCE_CATALOG_MESS_COMPARE_TITLE')?>',
            BTN_MESSAGE_COMPARE_REDIRECT: '<?=GetMessageJS('CT_BCE_CATALOG_BTN_MESSAGE_COMPARE_REDIRECT')?>',
            PRODUCT_GIFT_LABEL: '<?=GetMessageJS('CT_BCE_CATALOG_PRODUCT_GIFT_LABEL')?>',
            PRICE_TOTAL_PREFIX: '<?=GetMessageJS('CT_BCE_CATALOG_MESS_PRICE_TOTAL_PREFIX')?>',
            RELATIVE_QUANTITY_MANY: '<?=$arParams['~MESS_RELATIVE_QUANTITY_MANY']?>',
            RELATIVE_QUANTITY_FEW: '<?=$arParams['~MESS_RELATIVE_QUANTITY_FEW']?>',
            RELATIVE_QUANTITY_NO: '<?=$arParams['~MESS_RELATIVE_QUANTITY_NO']?>',
            SITE_ID: '<?=CUtil::JSEscape($component->getSiteId())?>',
            WISH_TO: '<?=GetMessageJS('WISH_TO')?>',
            WISH_IN: '<?=GetMessageJS('WISH_IN')?>',
        });
        var <?=$obName?> =
        new JCCatalogElement(<?=CUtil::PhpToJSObject($jsParams, false, true)?>);
        <? if (Config::get('SHOW_ZOOM_' . $template) == 'Y' && $actualItem['MORE_PHOTO'][0]['MEDIUM']['ID'] != 'empty'): ?>
        <? endif; ?>
    </script>

<?
if (Config::get('TIMER_PROMOTIONS') == 'Y') {
    if ($dbProductDiscounts) {
        $APPLICATION->IncludeComponent(
            "kit:origami.timer",
            "origami_default",
            array(
                "COMPONENT_TEMPLATE" => "origami_default",
                "ID" => $arResult["ID"],
                "BLOCK_ID" => $blockID,
                "ACTIVATE" => "Y",
                "TIMER_SIZE" => "lg",
                "TIMER_DATE_END" => $dbProductDiscounts[1]['ACTIVE_TO']
            ),
            $component
        );
    }
}
?>

<?
unset($actualItem, $itemIds, $jsParams);
?>
