<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

use Bitrix\Main\Localization\Loc;
use Sotbit\Origami\Helper\Config;
use \Sotbit\Origami\Helper\Prop;

$this->setFrameMode(true);

global $analogProducts;

$templateLibrary = ['popup', 'fx'];
$currencyList = '';

if (!empty($arResult['CURRENCIES'])) {
    $templateLibrary[] = 'currency';
    $currencyList = CUtil::PhpToJSObject($arResult['CURRENCIES'], false, true,
        true);
}

$template = $this->__name;
if ($this->__name == '.default') {
    $template = '';
}

$templateData = [
    'TEMPLATE_THEME'   => $arParams['TEMPLATE_THEME'],
    'TEMPLATE_LIBRARY' => $templateLibrary,
    'CURRENCIES'       => $currencyList,
    'ITEM'             => [
        'ID'              => $arResult['ID'],
        'IBLOCK_ID'       => $arResult['IBLOCK_ID'],
        'OFFERS_SELECTED' => $arResult['OFFERS_SELECTED'],
        'JS_OFFERS'       => $arResult['JS_OFFERS'],
    ],
    'OFFERS_ID' => $arResult["OFFERS_ID"]
];
unset($currencyList, $templateLibrary);

$mainId = $this->GetEditAreaId($arResult['ID']);
$itemIds = [
    'ID'                    => $mainId,
    'DISCOUNT_PERCENT_ID'   => $mainId.'_dsc_pict',
    'STICKER_ID'            => $mainId.'_sticker',
    'BIG_SLIDER_ID'         => $mainId.'_big_slider',
    'BIG_IMG_CONT_ID'       => $mainId.'_bigimg_cont',
    'SLIDER_CONT_ID'        => $mainId.'_slider_cont',
    'OLD_PRICE_ID'          => $mainId.'_old_price',
    'PRICE_ID'              => $mainId.'_price',
    'DISCOUNT_PRICE_ID'     => $mainId.'_price_discount',
    'PRICE_TOTAL'           => $mainId.'_price_total',
    'SLIDER_CONT_OF_ID'     => $mainId.'_slider_cont_',
    'QUANTITY_ID'           => $mainId.'_quantity',
    'QUANTITY_DOWN_ID'      => $mainId.'_quant_down',
    'QUANTITY_UP_ID'        => $mainId.'_quant_up',
    'QUANTITY_MEASURE'      => $mainId.'_quant_measure',
    'QUANTITY_LIMIT'        => $mainId.'_quant_limit',
    'BUY_LINK'              => $mainId.'_buy_link',
    'ADD_BASKET_LINK'       => $mainId.'_add_basket_link',
    'BASKET_ACTIONS_ID'     => $mainId.'_basket_actions',
    'NOT_AVAILABLE_MESS'    => $mainId.'_not_avail',
    'COMPARE_LINK'          => $mainId.'_compare_link',
    'WISH_LINK'             => $mainId.'_wish_link',
    'WISH_LINK_MODIFICATION' => $mainId.'_wish_link_modification',
    'TREE_ID'               => $mainId.'_skudiv',
    'DISPLAY_PROP_DIV'      => $mainId.'_sku_prop',
    'DISPLAY_MAIN_PROP_DIV' => $mainId.'_main_sku_prop',
    'OFFER_GROUP'           => $mainId.'_set_group_',
    'BASKET_PROP_DIV'       => $mainId.'_basket_prop',
    'SUBSCRIBE_LINK'        => $mainId.'_subscribe',
    'TABS_ID'               => $mainId.'_tabs',
    'TAB_CONTAINERS_ID'     => $mainId.'_tab_containers',
    'SMALL_CARD_PANEL_ID'   => $mainId.'_small_card_panel',
    'TABS_PANEL_ID'         => $mainId.'_tabs_panel',
    'ALL_PRICES'            => $areaId.'_all_prices',
    'MODIFICATION_ID'       => $mainId.'_modification'
];
$obName
    =
$templateData['JS_OBJ'] = 'ob'.preg_replace('/[^a-zA-Z0-9_]/', 'x', $mainId);
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
        if(!$offer['CAN_BUY']){
            $canNotBuyOffers[] = $offer;
        }
    }
    foreach ($arResult['OFFERS'] as $offer) {
        if ($offer['MORE_PHOTO_COUNT'] > 1) {
            $showSliderControls = true;
            break;
        }
    }
} else {
    $actualItem = $arResult;
    $showSliderControls = $arResult['MORE_PHOTO_COUNT'] > 1;
}
if($arResult['VIDEO']){
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
    'left'   => 'product-item-label-left',
    'center' => 'product-item-label-center',
    'right'  => 'product-item-label-right',
    'bottom' => 'product-item-label-bottom',
    'middle' => 'product-item-label-middle',
    'top'    => 'product-item-label-top',
];

$discountPositionClass = 'product-item-label-big';
if ($arParams['SHOW_DISCOUNT_PERCENT'] === 'Y'
    && !empty($arParams['DISCOUNT_PERCENT_POSITION'])
) {
    foreach (explode('-', $arParams['DISCOUNT_PERCENT_POSITION']) as $pos) {
        $discountPositionClass .= isset($positionClassMap[$pos]) ? ' '
            .$positionClassMap[$pos] : '';
    }
}

$labelPositionClass = 'product-item-label-big';
if (!empty($arParams['LABEL_PROP_POSITION'])) {
    foreach (explode('-', $arParams['LABEL_PROP_POSITION']) as $pos) {
        $labelPositionClass .= isset($positionClassMap[$pos]) ? ' '
            .$positionClassMap[$pos] : '';
    }
}

?>
    <div class="quick-view">
        <div id="<?= $itemIds['ID'] ?>" class="elem_check_basket_<?= $arResult['ID'] ?>">
            <?
            if ($arParams['DISPLAY_NAME'] === 'Y') {
                ?>
                <h1 class="product_preview_title"><?= $name ?></h1>
                <?
            }
            ?>
            <div class="main_info_preview_product">
                <div class="product-preview-photo-block-wrapper">
                    <div class="product-preview-photo-block">
                        <div id="<?= $itemIds['BIG_SLIDER_ID'] ?>"
                            class="product-preview-photo-block_inner">
                            <div class="product-item-preview-slider-container">
                                <span class="product-item-preview-slider-close"
                                    data-entity="close-popup"></span>
                                <div class="sticker_product">
                                    <?
                                    if ($arParams['SHOW_DISCOUNT_PERCENT'] === 'Y') {
                                        if ($haveOffers) {
                                            ?>
                                            <div class="sticker_product__discount fonts__small_comment" id="<?= $itemIds['DISCOUNT_PERCENT_ID'] ?>" style="display: none;">
                                                <span><?= -$price['PERCENT'] ?>%</span>
                                            </div>
                                            <?
                                        } else {
                                            if ($price['DISCOUNT'] > 0) {
                                                ?>
                                                <div class="sticker_product__discount fonts__small_comment" id="<?= $itemIds['DISCOUNT_PERCENT_ID'] ?>">
                                                    <span><?= -$price['PERCENT'] ?>%</span>
                                                </div>
                                                <?
                                            }
                                        }
                                    }
                                    if ($arParams['LABEL_PROP']) {
                                        foreach ($arParams['LABEL_PROP'] as $label)
                                        {
                                            if (Prop::checkPropListYes($arResult['PROPERTIES'][$label])) {
                                                ?>
                                                <div>
                                                    <span class="sticker_product__hit fonts__small_comment" style="<?= ($arResult['PROPERTIES'][$label]['HINT'])
                                                                ? 'background:'
                                                                .$arResult['PROPERTIES'][$label]['HINT']
                                                                .';'
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
                                        <?if($arResult['SHOW_DELAY']):?>
                                            <span class="product_card__block_icon-heart" data-entity="wish" id="<?= $itemIds['WISH_LINK'] ?>">
                                        <svg width="16" height="16">
                                            <use xlink:href="/local/templates/sotbit_origami/assets/img/sprite.svg#icon_favourite"></use>
                                        </svg>
                                    </span>
                                    <?endif;?>
                                    <?
                                    if ($arResult['SHOW_COMPARE'] && $arParams['DISPLAY_COMPARE']) {
                                        ?>
                                        <div class="" id="<?= $itemIds['COMPARE_LINK'] ?>">
                                            <span class="product_card__block_icon-bar" data-entity="compare-checkbox">
                                                <svg width="16" height="16">
                                                    <use xlink:href="/local/templates/sotbit_origami/assets/img/sprite.svg#icon_compare"></use>
                                                </svg>
                                            </span>
                                        </div>
                                        <?
                                    }
                                    ?>
                                </div>

                                <?
                                if($actualItem['MORE_PHOTO'][0]['MEDIUM']['ID'] != 'empty'):
                                    ?>
                                    <div class="product-tem-zoom" data-entity="zoom-container">
                                        <svg class="product-card_icon-zoom-big" width="28px" height="28px">
                                            <use xlink:href="/local/templates/sotbit_origami/assets/img/sprite.svg#icon_zoom_big"></use>
                                        </svg>
                                    </div>
                                <?
                                endif;
                                ?>

                                <div class="product-item-preview-slider-block" data-entity="images-slider-block">
                                    <div class="product-item-preview-slider-images-container" data-entity="images-container">
                                        <?
                                        if (!empty($actualItem['MORE_PHOTO'])) {
                                            foreach (
                                                $actualItem['MORE_PHOTO'] as $key =>
                                                $photo
                                            ) {
                                                $photoMedium = ($photo['MEDIUM'])
                                                    ? $photo['MEDIUM']
                                                    : $photo['ORIGINAL'];
                                                $photoBig = ($photo['BIG'])
                                                    ? $photo['BIG']
                                                    : $photo['ORIGINAL'];
                                                ?>
                                                <div
                                                        class="product-item-preview-slider-image zoomIt <?= ($key
                                                        == 0 ? ' active' : '') ?>"
                                                        data-entity="image"
                                                        data-id="<?= $photo['ORIGINAL']['ID'] ?>"
                                                    <?/*= (Config::get('SHOW_ZOOM_'
                                                            .$template) == 'Y')
                                                        ? 'href="'.$photoBig['SRC']
                                                        .'"' : '' */?>
                                                >
                                                    <img src="<?= $photoMedium['SRC'] ?>"
                                                        alt="<?= $alt ?>"
                                                        title="<?= $title ?>"<?= ($key
                                                    == 0 ? ' itemprop="image"'
                                                        : '') ?>>
                                                </div>
                                                <?
                                                if($key == 0 && $arResult['VIDEO']){
                                                    foreach($arResult['VIDEO'] as $i=>$video){
                                                        ?>
                                                        <div class="preview-big-video" data-value="<?=$i?>">
                                                            <?=$video?>
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
                                        <div class="product-item-preview-slider-controls-block" id="<?= $itemIds['SLIDER_CONT_OF_ID'].$offer['ID'] ?>" style="display: <?= $strVisible ?>;">
                                            <?
                                            foreach ($offer['MORE_PHOTO'] as $keyPhoto => $photo)
                                            {
                                                $photoSmall = ($photo['SMALL']) ? $photo['SMALL'] : $photo['ORIGINAL'];
                                                ?>
                                                <div class="product-item-preview-slider-controls-image<?= ($keyPhoto
                                                == 0 ? ' active' : '') ?>"
                                                    data-entity="slider-control"
                                                    data-value="<?= $offer['ID']
                                                    .'_'
                                                    .$photo['ORIGINAL']['ID'] ?>">
                                                    <img src="<?= $photoSmall['SRC'] ?>"
                                                        alt="<?= $alt ?>" title="<?=$title?>">
                                                </div>
                                                <?
                                                if($keyPhoto == 0 && $arResult['VIDEO'])
                                                {
                                                    foreach($arResult['VIDEO'] as $i=>$video)
                                                    {
                                                        ?>
                                                        <div class="product-item-preview-slider-controls-video" data-entity="video" data-value="<?=$i?>">
                                                            <div class="video">video</div>
                                                        </div>
                                                        <?
                                                    }
                                                }
                                            }
                                            ?>
                                        </div>
                                        <?
                                    }
                                }else{
                                    ?>
                                    <div class="product-item-preview-slider-controls-block" id="<?= $itemIds['SLIDER_CONT_ID'] ?>">
                                        <?
                                        if (!empty($actualItem['MORE_PHOTO']))
                                        {
                                            foreach ($actualItem['MORE_PHOTO'] as $key => $photo)
                                            {
                                                $photoSmall = ($photo['SMALL']) ? $photo['SMALL'] : $photo['ORIGINAL'];
                                                ?>
                                                <div class="product-item-preview-slider-controls-image<?= ($ke
                                                == 0 ? ' active' : '') ?>"
                                                    data-entity="slider-control"
                                                    data-value="<?= $photo['ORIGINAL']['ID'] ?>">
                                                    <img src="<?= $photoSmall['SRC'] ?>" title="<?=$title?>" alt="<?=$alt?>">
                                                </div>
                                                <?
                                            }
                                        }
                                        ?>
                                    </div>
                                    <?
                                }
                            }
                            ?>
                        </div>

                    </div>
                    <script>

                    function name(params) {
                        checkedColor = document.querySelector('.main_info_preview_product .block-property-color-item.active');
                        checkedTextItem = document.querySelector('.main_info_preview_product .block-property-text-item.active');
                        checkedTextProps = document.querySelector('.main_info_preview_product .dropdown-props.active');

                        var startNumberProduct = $('.main_info_preview_product .block-property-color-item.active').index();

                        currentSlide = $('.product-item-preview-slider-images-container').slick({
                            slidesToShow: 1,
                            slidesToScroll: 1,
                            arrows: false,
                            fade: true,
                            infinite: true,
                            asNavFor: '.product-item-preview-slider-controls-block'
                        });
                        navSlider = $('.product-item-preview-slider-controls-block').slick({
                            slidesToShow: 6,
                            slidesToScroll: 3,
                            asNavFor: '.product-item-preview-slider-images-container',
                            centerMode: false,
                            variableWidth: true,
                            focusOnSelect: true,
                            edgeFriction: 1,
                            infinite: true,
                            prevArrow: '<button type="button" class="btn-slick-custom btn-slick-custom--prev">Prev</button>',
                            nextArrow: '<button type="button" class="btn-slick-custom btn-slick-custom--next">Prev</button>',
                            arrows: true
                        });
                    }


                    </script>

                </div>
                <div class="product_preview_description">
                    <div class="product_preview_description-content">

                    <div class="product_preview_info">
                        <div class="product_previev_info-content">
                        <div id="right_preview_card"
                                class="product_preview_info_block">
                                <div class="right_preview_card__stars">
                                    <?
                                    if ($arParams['USE_VOTE_RATING'] === 'Y') {
                                        $APPLICATION->IncludeComponent(
                                            'bitrix:iblock.vote',
                                            'origami_stars',
                                            [
                                                'CUSTOM_SITE_ID'    => isset($arParams['CUSTOM_SITE_ID'])
                                                    ? $arParams['CUSTOM_SITE_ID']
                                                    : null,
                                                'IBLOCK_TYPE'       => $arParams['IBLOCK_TYPE'],
                                                'IBLOCK_ID'         => $arParams['IBLOCK_ID'],
                                                'ELEMENT_ID'        => $arResult['ID'],
                                                'ELEMENT_CODE'      => '',
                                                'MAX_VOTE'          => '5',
                                                'VOTE_NAMES'        => [
                                                    '1',
                                                    '2',
                                                    '3',
                                                    '4',
                                                    '5',
                                                ],
                                                'SET_STATUS_404'    => 'N',
                                                'DISPLAY_AS_RATING' => $arParams['VOTE_DISPLAY_AS_RATING'],
                                                'CACHE_TYPE'        => $arParams['CACHE_TYPE'],
                                                'CACHE_TIME'        => $arParams['CACHE_TIME'],
                                            ],
                                            $component,
                                            ['HIDE_ICONS' => 'Y']
                                        );
                                    }
                                    ?>
                                </div>

                                <?
                                $addProps
                                    = unserialize(\Sotbit\Origami\Config\Option::get('DETAIL_ADD_PROPS_'
                                    .$template));
                                if ($addProps
                                    && !empty($arResult['DISPLAY_PROPERTIES'])
                                ) {
                                    ?>
                                    <div id="all_property"
                                        class="product-preview-info-block-basic-property">
                                        <div class="basic-property-title fonts__middle_text"><?= Loc::getMessage('DETAIL_PROPS') ?></div>
                                        <?
                                        foreach (
                                            $arResult['DISPLAY_PROPERTIES'] as $key
                                        => $property
                                        ) {
                                            if (in_array($key, $addProps)) {
                                                ?>
                                                <div class="product-preview-info-block-property fonts__middle_comment">
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
                                        if(Config::get('ACTIVE_TAB_PROPERTIES_'.$template) == 'Y'){
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

                                <? if ($arResult['PROPERTIES'][Config::get('ARTICUL')]['VALUE']):
                                    $vendorCode
                                        = $arResult['PROPERTIES'][Config::get('ARTICUL')];
                                    ?>
                                    <div class="product_preview_info_block__article fonts__middle_comment">
                                        <span class="product_preview_info_block__title"><?= $vendorCode['NAME'] ?>:</span>
                                        <span><?= $vendorCode['VALUE'] ?></span>
                                    </div>
                                <? endif; ?>
                            </div>
                            <div class="product_preview_info_block-description">
                                <div class="product_preview_info_block__line">
                                <? if ($arParams['SHOW_MAX_QUANTITY'] !== 'N')
                                {
                                    if ($haveOffers)
                                    {
                                        ?>
                                        <div class="product_preview_info_block__count fonts__middle_comment" id="<?= $itemIds['QUANTITY_LIMIT'] ?>">
                                            <span class="product_preview_info_block__title fonts__middle_comment">
                                                <?= $arParams['MESS_SHOW_MAX_QUANTITY'] ?>:
                                            </span>
                                            <span class="product_card__block__presence_product_value_many" data-entity="quantity-limit-value">
                                                <?
                                                if ($arParams['SHOW_MAX_QUANTITY'] === 'M')
                                                {
                                                    if ($actualItem['CATALOG_QUANTITY'] == 0)
                                                    {
                                                        ?>
                                                        <span class="product_card__block__presence_product_value_no">
                                                            <i class="icon-no-waiting"></i>
                                                            <?= $arParams['~MESS_RELATIVE_QUANTITY_NO']; ?>
                                                        </span>
                                                        <?
                                                    }elseif((float)$actualItem['CATALOG_QUANTITY'] / $measureRatio >= $arParams['RELATIVE_QUANTITY_FACTOR'])
                                                    {
                                                        ?>
                                                        <span class="product_card__block__presence_product_value_many">
                                                                  <svg class="product-card_icon-check" width="11px" height="12px"><use xlink:href="/local/templates/sotbit_origami/assets/img/sprite.svg#icon_check_checkbox"></use>
                                                                             </svg>
                                                            <?= $arParams['~MESS_RELATIVE_QUANTITY_MANY']; ?>
                                                        </span>
                                                        <?
                                                    }else{
                                                        ?>
                                                        <span class="product_card__block__presence_product_value_sufficient">
                                                            <i class="fas fa-check"></i>
                                                            <?= $arParams['~MESS_RELATIVE_QUANTITY_FEW']; ?>
                                                        </span>
                                                        <?
                                                    }
                                                }else{
                                                    if($actualItem['CATALOG_QUANTITY'] == 0)
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
                                                                  <svg class="product-card_icon-check" width="11px" height="12px"><use xlink:href="/local/templates/sotbit_origami/assets/img/sprite.svg#icon_check_checkbox"></use>
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
                                    }else{
                                        if($measureRatio && $actualItem['CATALOG_QUANTITY_TRACE'] === 'Y' && $actualItem['CATALOG_CAN_BUY_ZERO'] === 'N')
                                        {
                                            ?>
                                            <div class="product_preview_info_block__count fonts__middle_comment" id="<?=$itemIds['QUANTITY_LIMIT'] ?>">
                                                <span class="product_preview_info_block__title fonts__middle_comment">
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
                                                        }elseif((float)$actualItem['CATALOG_QUANTITY'] / $measureRatio >= $arParams['RELATIVE_QUANTITY_FACTOR'])
                                                        {
                                                            ?>
                                                            <span class="product_card__block__presence_product_value_many">
                                                                     <svg class="product-card_icon-check" width="11px" height="12px"><use xlink:href="/local/templates/sotbit_origami/assets/img/sprite.svg#icon_check_checkbox"></use>
                                                                             </svg>
                                                                <?= $arParams['~MESS_RELATIVE_QUANTITY_MANY']; ?>
                                                            </span>
                                                            <?
                                                        }else{
                                                            ?>
                                                            <span class="product_card__block__presence_product_value_sufficient">
                                                                <i class="fas fa-check"></i>
                                                                <?= $arParams['~MESS_RELATIVE_QUANTITY_FEW']; ?>
                                                            </span>
                                                            <?
                                                        }
                                                    }else{
                                                        if($actualItem['CATALOG_QUANTITY'] == 0)
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
                                                                      <svg class="product-card_icon-check" width="11px" height="12px"><use xlink:href="/local/templates/sotbit_origami/assets/img/sprite.svg#icon_check_checkbox"></use>
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

                                </div>

                                <div class="product-preview-price-and-cheaper">
                                    <?php
                                    if ($arParams["FILL_ITEM_ALL_PRICES"] == "Y")
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
                                    <div class="product-preview-info-block-price" >
                                        <div class="product_card__block__old_new_price">
                                            <div class="product-detail-title-price fonts__middle_comment">

                                            </div>
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
                                    <?if(Config::get('SHOW_FOUND_CHEAPER_'.$template) == 'Y' || true): ?>
                                        <div class="product_preview_info_block_cheaper"
                                            onclick="foundCheaper('<?= SITE_DIR ?>', '<?= SITE_ID ?>', '<?= $arResult['NAME'] ?>', this)">
                                            <span>
                                                <svg class="product_preview_info_block_cheaper-icon" width="14" height="11">
                                                    <use xlink:href="/local/templates/sotbit_origami/assets/img/sprite.svg#icon_cheaper"></use>
                                                </svg>
                                            </span>
                                            <span class="fonts__small_comment product_preview_info_block_cheaper__title"><?= Loc::getMessage('DETAIL_FOUND_CHEAPER') ?></span>
                                        </div>
                                    <?endif;?>
                                </div>
                                    <?if(Config::get('SHOW_FOUND_CHEAPER_'.$template) == 'Y'): ?>
                                        <div class="product_preview_info_block_cheaper"
                                            onclick="foundCheaper('<?= SITE_DIR ?>', '<?= SITE_ID ?>', '<?= $arResult['NAME'] ?>', this)">
                                            <span class="far fa-money-bill-alt"></span>
                                            <span class="fonts__small_comment product_preview_info_block_cheaper__title"><?= Loc::getMessage('DETAIL_FOUND_CHEAPER') ?></span>
                                        </div>
                                    <?endif;?>
                                <?
                                if ($haveOffers
                                    && !empty($arResult['OFFERS_PROP'])
                                ) {
                                    ?>
                                    <div id="<?= $itemIds['TREE_ID'] ?>" >
                                        <?
                                        foreach (
                                            $arResult['SKU_PROPS'] as $code =>
                                            $skuProperty
                                        ) {
                                            if (!isset($arResult['OFFERS_PROP'][$skuProperty['CODE']])) {
                                                continue;
                                            }

                                            $propertyId = $skuProperty['ID'];
                                            $skuProps[] = [
                                                'ID'           => $propertyId,
                                                'SHOW_MODE'    => $skuProperty['SHOW_MODE'],
                                                'VALUES'       => $skuProperty['VALUES'],
                                                'VALUES_COUNT' => $skuProperty['VALUES_COUNT'],
                                            ];

                                            if ($code == Config::get('COLOR')) {
                                                $type
                                                    = Config::get('PROP_COLOR_TYPE_ELEMENT_');
                                                ?>
                                                <div class="product-preview-info-block-main-property"
                                                    data-entity="sku-line-block">
                                                    <div class="product-preview-info-block-property-title fonts__middle_comment">

                                                        <?= strtolower(htmlspecialcharsEx($skuProperty['NAME'])) ?>
                                                        :
                                                    </div>
                                                    <div class="block-property-color">
                                                        <?
                                                        foreach (
                                                            $skuProperty['VALUES']
                                                            as &$value
                                                        ) {
                                                            if (!$value['ID']) {
                                                                continue;
                                                            }
                                                            $value['NAME']
                                                                = htmlspecialcharsbx($value['NAME']);
                                                            ?>

                                                            <?
                                                            if ($type
                                                                != "color_square"
                                                            ):?>
                                                                <div class="
                                                                        block-property-color-item
                                                                        block-property
                                                                        <?= $type ?>
                                                                        <?=($value['XML_ID'] == $actualItem['PROPERTIES'][$code]['VALUE'])?'active':''?>
                                                                        <?
                                                                if($canNotBuyOffers){
                                                                    foreach($canNotBuyOffers as $notBuyOffer){
                                                                        if($value['XML_ID'] == $notBuyOffer['PROPERTIES'][$code]['VALUE'])
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
                                                                    title="<?= $value['NAME'] ?>">
                                                                    <img
                                                                            src="<?= $value['PICT']['SRC'] ?>"
                                                                            alt="<?= $skuProperty['NAME'] ?>: <?= $value['NAME'] ?>"
                                                                            title="<?= $skuProperty['NAME'] ?>: <?= $value['NAME'] ?>"
                                                                    >
                                                                </div>
                                                            <? else:?>
                                                                <div class="
                                                                        block-property-color-item
                                                                        block-property
                                                                        <?= $type ?>
                                                                        <?=($value['XML_ID'] == $actualItem['PROPERTIES'][$code]['VALUE'])?'active':''?>
                                                                        <?
                                                                if($canNotBuyOffers){
                                                                    foreach($canNotBuyOffers as $notBuyOffer){
                                                                        if($value['XML_ID'] == $notBuyOffer['PROPERTIES'][$code]['VALUE'])
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
                                                                    title="<?= $value['NAME'] ?>">
                                                                    <?= $value['NAME'] ?>
                                                                </div>
                                                            <?endif; ?>

                                                            <?
                                                        }
                                                        ?>
                                                    </div>
                                                </div>
                                                <?
                                            } else {
                                                ?>
                                                <div class="product-preview-info-block-main-property"
                                                    data-entity="sku-line-block">
                                                    <div class="product-preview-info-block-property-title fonts__middle_comment">
                                                        <?= strtolower(htmlspecialcharsEx($skuProperty['NAME'])) ?>
                                                        :
                                                    </div>
                                                    <div class="block-property-text">
                                                        <?
                                                        if (Config::get('PROP_DISPLAY_MODE_'
                                                                .$template)
                                                            != 'dropdown'
                                                        ):?>
                                                            <?
                                                            foreach (
                                                                $skuProperty['VALUES']
                                                                as &$value
                                                            ) {
                                                                if (!$value['ID']) {
                                                                    continue;
                                                                }

                                                                $value['NAME']
                                                                    = htmlspecialcharsbx($value['NAME']);
                                                                ?>
                                                                <div class="block-property-text-item block-property
                                                                    <?=($value['XML_ID'] == $actualItem['PROPERTIES'][$code]['VALUE'])?'active':''?>
                                                                        <?
                                                                if($canNotBuyOffers){
                                                                    foreach($canNotBuyOffers as $notBuyOffer){
                                                                        if($value['XML_ID'] == $notBuyOffer['PROPERTIES'][$code]['VALUE'])
                                                                        {
                                                                            echo 'notallowed';
                                                                            break;
                                                                        }
                                                                    }
                                                                }
                                                                ?>"
                                                                    data-onevalue="<?= $value['ID'] ?>"
                                                                    data-treevalue="<?= $propertyId ?>_<?= $value['ID'] ?>">
                                                                    <?= $value['NAME'] ?>
                                                                </div>
                                                                <?
                                                            }
                                                            ?>
                                                        <? else:?>
                                                            <div class="dropdown-props-wrapper">
                                                                <div class="dropdown-btn angle-down"
                                                                    onclick="window.dropdownToggle(this)">
                                                                    <?= $skuProperty['NAME'] ?>
                                                                </div>
                                                                <div class="dropdown-props">
                                                                    <?
                                                                    foreach (
                                                                        $skuProperty['VALUES']
                                                                        as &$value
                                                                    ) {
                                                                        if (!$value['ID']) {
                                                                            continue;
                                                                        }

                                                                        $value['NAME']
                                                                            = htmlspecialcharsbx($value['NAME']);
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
                                                                    padding: 0 15px;
                                                                    cursor: pointer;
                                                                }

                                                                .prop-item.active {
                                                                    background: #ededed;
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
                        <div class="product_preview-btn-block">
                            <?if($arResult["SHOW_BUY"]):?>
                            <div class="product_preview_info_block">
                                <div class="product-preview-info-block-basket">



                                    <?
                                    if ($showSubscribe) {
                                        $APPLICATION->IncludeComponent(
                                            'bitrix:catalog.product.subscribe',
                                            'origami_default',
                                            [
                                                'CUSTOM_SITE_ID'     => isset($arParams['CUSTOM_SITE_ID'])
                                                    ? $arParams['CUSTOM_SITE_ID']
                                                    : null,
                                                'PRODUCT_ID'         => $arResult['ID'],
                                                'BUTTON_ID'          => $itemIds['SUBSCRIBE_LINK'],
                                                'BUTTON_CLASS'       => 'btn btn-default product-item-preview-buy-button',
                                                'DEFAULT_DISPLAY'    => !$actualItem['CAN_BUY'],
                                                'MESS_BTN_SUBSCRIBE' => $arParams['~MESS_BTN_SUBSCRIBE'],
                                            ],
                                            $component,
                                            ['HIDE_ICONS' => 'Y']
                                        );
                                        ?>
                                        <div class="product-preview-info-block-message">
                                            <?=Loc::getMessage('DETAIL_SUBSCRIBE_NOTICE')?>
                                        </div>
                                        <?
                                    }



                                    if (Config::get('SHOW_CHECK_STOCK_'.$template) == 'Y' || true): ?>
                                    <?if($haveOffers || (!$haveOffers && !$arResult['CAN_BUY'])):?>
                                        <div class="product_preview_info_block_gift-wrapper">
                                            <div class="product_preview_info_block_gift" id="product_check_stock"
                                                onclick="checkStock('<?= SITE_DIR ?>', '<?= SITE_ID ?>', '<?= $arResult['NAME'] ?>', this)"
                                                style="display: <?=($actualItem['CAN_BUY'])?'none':'flex'?>">
                                                <span class="fonts__small_comment product_preview_info_block_gift__title product_preview_info--availability"><?= Loc::getMessage('DETAIL_CHECK_STOCK') ?></span>
                                            </div>
                                        </div>
                                    <? endif; ?>
                                <? endif; ?>

                                <?


                                    if ($arParams['USE_PRODUCT_QUANTITY']
                                        && $price['MIN_QUANTITY'] > 0
                                    ) {
                                        ?>
                                        <div class="product_card__block_buy_quantity"
                                            data-entity="quantity-block">
                                            <span class="product_card__block_buy_quantity__minus fonts__small_title"
                                                id="<?= $itemIds['QUANTITY_DOWN_ID'] ?>">&dash;</span>
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
                                        class="preview-basket-wrapper"
                                        style="display:    <?= ($actualItem['CAN_BUY']
                                            ? '' : 'none') ?>;">
                                        <?
                                        if ($showAddBtn) {
                                            ?>
                                            <a class="main_btn sweep-to-right"
                                            id="<?= $itemIds['ADD_BASKET_LINK'] ?>"
                                            href="javascript:void(0);">
                                                <!-- <span class="icon-bbasket"></span> <?= Loc::getMessage('CT_BCE_CATALOG_ADD') ?> -->
                                                <svg width="24" height="24">
                                                    <use xlink:href="/local/templates/sotbit_origami/assets/img/sprite.svg#icon_cart"></use>
                                                </svg><?= Loc::getMessage('CT_BCE_CATALOG_ADD') ?>
                                            </a>
                                            <?
                                        }
                                        if ($showBuyBtn) {
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
                                    <? if (Config::get('SHOW_BUY_OC_'.$template) == 'Y' || true): //--temp for display ?>
                                        <div class="product-preview-info-block-one-click-basket"
                                            id="modal_oc">
                                            <span class="one_click_btn fonts__middle_text"><?= Loc::getMessage('DETAIL_BUY_OC') ?></span>
                                        </div>
                                    <? endif; ?>
                                </div>
                                <div class="product-preview-info-block-path-to-basket" >
                                    <a href="<?= Config::get('BASKET_PAGE') ?>"
                                    class="in_basket">
                                        <span></span><?= Loc::getMessage('DETAIL_PRODUCT_IN_BASKET') ?>
                                    </a>
                                </div>


                                <? if (Config::get('SHOW_WANT_GIFT_'.$template)
                                    != 'N'
                                ): ?>
                                    <?
                                    $currentUrl = (CMain::IsHTTPS())
                                        ? "https://" : "http://";
                                    $currentUrl .= $_SERVER["HTTP_HOST"];
                                    $currentUrl .= $arResult['DETAIL_PAGE_URL'];

                                    if ($arResult['ITEM_PRICES'][0]) {
                                        $priceProduct
                                            = $arResult['ITEM_PRICES'][0]['PRINT_PRICE'];
                                        if ($arResult['DISCOUNT'] > 0) {
                                            $oldPriceProduct
                                                = $arResult['ITEM_PRICES'][0]['PRINT_BASE_PRICE'];
                                        }
                                    }
                                    ?>
                                    <div class="product_preview_info_block_gift"
                                        onclick="wantGift('<?= SITE_DIR ?>', '<?= SITE_ID ?>', '<?= $currentUrl ?>', '<?= $arResult['DETAIL_PICTURE']['SRC'] ?>', this)">
                                        <span>
                                            <svg class="product_preview_info_block_gift-icon" width="13" height="13">
                                                    <use xlink:href="/local/templates/sotbit_origami/assets/img/sprite.svg#icon_gift"></use>
                                            </svg>
                                        </span>
                                        <span class="fonts__small_comment product_preview_info_block_gift__title"><?= Loc::getMessage('DETAIL_WANT_GIFT') ?></span>
                                    </div>
                                <? endif; ?>
                            </div>
                            <?endif;?>
                        </div>
                    </div>
                    <script>
                        var view = document.querySelector('.product_previev_info-content');
                        new PerfectScrollbar(view,{
                            wheelSpeed: 0.5,
                            wheelPropagation: true,
                            minScrollbarLength: 20
                        });
                    </script>
                </div>

            </div>
        </div>
    </div>

<?
$allPrices = [];

if ($haveOffers && $arParams["FILL_ITEM_ALL_PRICES"] == "Y")
{
    foreach ($arResult['OFFERS'] as $offer)
    {
        $allPrices[$offer['ID']] = $offer['ITEM_ALL_PRICES'][0]["PRICES"];
    }
} elseif($arParams["FILL_ITEM_ALL_PRICES"] == "Y")
{
    $allPrices[$item['ID']] = $arResult['ITEM_ALL_PRICES'][0]["PRICES"];
}

if ($haveOffers) {
    $offerIds = [];
    $offerCodes = [];

    $useRatio = $arParams['USE_RATIO_IN_RANGES'] === 'Y';

    foreach ($arResult['JS_OFFERS'] as $ind => &$jsOffer) {
        $offerIds[] = (int)$jsOffer['ID'];
        $offerCodes[] = $jsOffer['CODE'];

        $fullOffer = $arResult['OFFERS'][$ind];
        $measureName = $fullOffer['ITEM_MEASURE']['TITLE'];

        $strAllProps = '';
        $strMainProps = '';
        $strPriceRangesRatio = '';
        $strPriceRanges = '';

        if ($arResult['SHOW_OFFERS_PROPS']) {
            if (!empty($jsOffer['DISPLAY_PROPERTIES'])) {
                foreach ($jsOffer['DISPLAY_PROPERTIES'] as $property) {
                    $current
                        = '<div class="product-preview-info-block-property fonts__middle_comment"><span class="property-title">'
                        .$property['NAME']
                        .': </span><span class="property-value">'.(
                        is_array($property['VALUE'])
                            ? implode(' / ', $property['VALUE'])
                            : $property['VALUE']
                        ).'</span></div>';
                    $strAllProps .= $current;

                    if (isset($arParams['MAIN_BLOCK_OFFERS_PROPERTY_CODE'][$property['CODE']])) {
                        $strMainProps .= $current;
                    }
                }

                unset($current);
            }
        }

        if ($arParams['USE_PRICE_COUNT']
            && count($jsOffer['ITEM_QUANTITY_RANGES']) > 1
        ) {
            $strPriceRangesRatio = '('.Loc::getMessage(
                    'CT_BCE_CATALOG_RATIO_PRICE',
                    [
                        '#RATIO#' => ($useRatio
                                ? $fullOffer['ITEM_MEASURE_RATIOS'][$fullOffer['ITEM_MEASURE_RATIO_SELECTED']]['RATIO']
                                : '1'
                            ).' '.$measureName,
                    ]
                ).')';

            foreach ($jsOffer['ITEM_QUANTITY_RANGES'] as $range) {
                if ($range['HASH'] !== 'ZERO-INF') {
                    $itemPrice = false;

                    foreach ($jsOffer['ITEM_PRICES'] as $itemPrice) {
                        if ($itemPrice['QUANTITY_HASH'] === $range['HASH']) {
                            break;
                        }
                    }

                    if ($itemPrice) {
                        $strPriceRanges .= '<dt>'.Loc::getMessage(
                                'CT_BCE_CATALOG_RANGE_FROM',
                                [
                                    '#FROM#' => $range['SORT_FROM'].' '
                                        .$measureName,
                                ]
                            ).' ';

                        if (is_infinite($range['SORT_TO'])) {
                            $strPriceRanges .= Loc::getMessage('CT_BCE_CATALOG_RANGE_MORE');
                        } else {
                            $strPriceRanges .= Loc::getMessage(
                                'CT_BCE_CATALOG_RANGE_TO',
                                ['#TO#' => $range['SORT_TO'].' '.$measureName]
                            );
                        }

                        $strPriceRanges .= '</dt><dd>'.($useRatio
                                ? $itemPrice['PRINT_RATIO_PRICE']
                                : $itemPrice['PRINT_PRICE']).'</dd>';
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
        'CONFIG'          => [
            'USE_CATALOG'              => $arResult['CATALOG'],
            'SHOW_QUANTITY'            => $arParams['USE_PRODUCT_QUANTITY'],
            'SHOW_PRICE'               => true,
            'SHOW_DISCOUNT_PERCENT'    => $arParams['SHOW_DISCOUNT_PERCENT']
                === 'Y',
            'SHOW_OLD_PRICE'           => $arParams['SHOW_OLD_PRICE'] === 'Y',
            'USE_PRICE_COUNT'          => $arParams['USE_PRICE_COUNT'],
            'DISPLAY_COMPARE'          => $arParams['DISPLAY_COMPARE'],
            'SHOW_SKU_PROPS'           => $arResult['SHOW_OFFERS_PROPS'],
            'OFFER_GROUP'              => $arResult['OFFER_GROUP'],
            'MAIN_PICTURE_MODE'        => $arParams['DETAIL_PICTURE_MODE'],
            'ADD_TO_BASKET_ACTION'     => $arParams['ADD_TO_BASKET_ACTION'],
            'SHOW_CLOSE_POPUP'         => $arParams['SHOW_CLOSE_POPUP'] === 'Y',
            'SHOW_MAX_QUANTITY'        => $arParams['SHOW_MAX_QUANTITY'],
            'RELATIVE_QUANTITY_FACTOR' => $arParams['RELATIVE_QUANTITY_FACTOR'],
            'TEMPLATE_THEME'           => $arParams['TEMPLATE_THEME'],
            'USE_STICKERS'             => true,
            'USE_SUBSCRIBE'            => $showSubscribe,
            'SHOW_SLIDER'              => $arParams['SHOW_SLIDER'],
            'SLIDER_INTERVAL'          => $arParams['SLIDER_INTERVAL'],
            'ALT'                      => $alt,
            'TITLE'                    => $title,
            'SITE_DIR'                 => SITE_DIR,
            'SITE_ID'                  => SITE_ID,
            'IBLOCK_ID'                => $arParams['IBLOCK_ID'],
            'MAGNIFIER_ZOOM_PERCENT'   => 200,
            'SHOW_ZOOM'                => Config::get('SHOW_ZOOM_'.$template),
            'USE_ENHANCED_ECOMMERCE'   => $arParams['USE_ENHANCED_ECOMMERCE'],
            'DATA_LAYER_NAME'          => $arParams['DATA_LAYER_NAME'],
            'BRAND_PROPERTY'           => !empty($arResult['DISPLAY_PROPERTIES'][$arParams['BRAND_PROPERTY']])
                ? $arResult['DISPLAY_PROPERTIES'][$arParams['BRAND_PROPERTY']]['DISPLAY_VALUE']
                : null,
        ],
        'PRODUCT_TYPE'    => $arResult['CATALOG_TYPE'],
        'VISUAL'          => $itemIds,
        'DEFAULT_PICTURE' => [
            'PREVIEW_PICTURE' => $arResult['DEFAULT_PICTURE'],
            'DETAIL_PICTURE'  => $arResult['DEFAULT_PICTURE'],
        ],
        'MESS'            => [
            'NO' => $arParams['~MESS_RELATIVE_QUANTITY_NO'],
        ],
        'PRODUCT'         => [
            'ID'         => $arResult['ID'],
            'ACTIVE'     => $arResult['ACTIVE'],
            'NAME'       => $arResult['~NAME'],
            'CATEGORY'   => $arResult['CATEGORY_PATH'],
            'ALL_PRICES' => $allPrices,
            'VIDEOS'     => $arResult['VIDEOS'],
        ],
        'BASKET'          => [
            'QUANTITY'         => $arParams['PRODUCT_QUANTITY_VARIABLE'],
            'BASKET_URL' => $arParams['BASKET_URL'],
            'BASKET_URL_AJAX' => SITE_DIR.'include/ajax/buy.php',
            'SKU_PROPS'        => $arResult['OFFERS_PROP_CODES'],
            'ADD_URL_TEMPLATE' => $arResult['~ADD_URL_TEMPLATE'],
            'BUY_URL_TEMPLATE' => $arResult['~BUY_URL_TEMPLATE'],
        ],
        'WISH'            => [
            'WISHES'            => [],
            'WISH_URL_TEMPLATE' => SITE_DIR.'include/ajax/wish.php',
        ],
        'OFFERS'          => $arResult['JS_OFFERS'],
        'OFFER_SELECTED'  => $arResult['OFFERS_SELECTED'],
        'TREE_PROPS'      => $skuProps,
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
        'CONFIG'       => [
            'USE_CATALOG'              => $arResult['CATALOG'],
            'SHOW_QUANTITY'            => $arParams['USE_PRODUCT_QUANTITY'],
            'SHOW_PRICE'               => !empty($arResult['ITEM_PRICES']),
            'SHOW_DISCOUNT_PERCENT'    => $arParams['SHOW_DISCOUNT_PERCENT']
                === 'Y',
            'SHOW_OLD_PRICE'           => $arParams['SHOW_OLD_PRICE'] === 'Y',
            'USE_PRICE_COUNT'          => $arParams['USE_PRICE_COUNT'],
            'DISPLAY_COMPARE'          => $arParams['DISPLAY_COMPARE'],
            'MAIN_PICTURE_MODE'        => $arParams['DETAIL_PICTURE_MODE'],
            'ADD_TO_BASKET_ACTION'     => $arParams['ADD_TO_BASKET_ACTION'],
            'SHOW_CLOSE_POPUP'         => $arParams['SHOW_CLOSE_POPUP'] === 'Y',
            'SHOW_MAX_QUANTITY'        => $arParams['SHOW_MAX_QUANTITY'],
            'RELATIVE_QUANTITY_FACTOR' => $arParams['RELATIVE_QUANTITY_FACTOR'],
            'TEMPLATE_THEME'           => $arParams['TEMPLATE_THEME'],
            'USE_STICKERS'             => true,
            'USE_SUBSCRIBE'            => $showSubscribe,
            'SHOW_SLIDER'              => $arParams['SHOW_SLIDER'],
            'SLIDER_INTERVAL'          => $arParams['SLIDER_INTERVAL'],
            'ALT'                      => $alt,
            'TITLE'                    => $title,
            'SITE_DIR'                 => SITE_DIR,
            'SITE_ID'                  => SITE_ID,
            'IBLOCK_ID'                => $arParams['IBLOCK_ID'],
            'SHOW_ZOOM'                => Config::get('SHOW_ZOOM_'.$template),
            'MAGNIFIER_ZOOM_PERCENT'   => 200,
            'USE_ENHANCED_ECOMMERCE'   => $arParams['USE_ENHANCED_ECOMMERCE'],
            'DATA_LAYER_NAME'          => $arParams['DATA_LAYER_NAME'],
            'BRAND_PROPERTY'           => !empty($arResult['DISPLAY_PROPERTIES'][$arParams['BRAND_PROPERTY']])
                ? $arResult['DISPLAY_PROPERTIES'][$arParams['BRAND_PROPERTY']]['DISPLAY_VALUE']
                : null,
        ],
        'VISUAL'       => $itemIds,
        'ADD_PRODUCT_TO_BASKET_MODE' => (Config::get('SHOW_POPUP_ADD_BASKET') == 'Y') ? 'popup' : 'no-popup',
        'PRODUCT_TYPE' => $arResult['CATALOG_TYPE'],
        'MESS'         => [
            'NO' => $arParams['~MESS_RELATIVE_QUANTITY_NO'],
        ],
        'PRODUCT'      => [
            'ID'                           => $arResult['ID'],
            'ACTIVE'                       => $arResult['ACTIVE'],
            'PICT'                         => reset($arResult['MORE_PHOTO']),
            'NAME'                         => $arResult['~NAME'],
            'SUBSCRIPTION'                 => true,
            'ITEM_PRICE_MODE'              => $arResult['ITEM_PRICE_MODE'],
            'ITEM_PRICES'                  => $arResult['ITEM_PRICES'],
            'ITEM_PRICE_SELECTED'          => $arResult['ITEM_PRICE_SELECTED'],
            'ITEM_QUANTITY_RANGES'         => $arResult['ITEM_QUANTITY_RANGES'],
            'ITEM_QUANTITY_RANGE_SELECTED' => $arResult['ITEM_QUANTITY_RANGE_SELECTED'],
            'ITEM_MEASURE_RATIOS'          => $arResult['ITEM_MEASURE_RATIOS'],
            'ITEM_MEASURE_RATIO_SELECTED'  => $arResult['ITEM_MEASURE_RATIO_SELECTED'],
            'SLIDER_COUNT'                 => $arResult['MORE_PHOTO_COUNT'],
            'SLIDER'                       => $arResult['MORE_PHOTO'],
            'CAN_BUY'                      => $arResult['CAN_BUY'],
            'CHECK_QUANTITY'               => $arResult['CHECK_QUANTITY'],
            'QUANTITY_FLOAT'               => is_float($arResult['ITEM_MEASURE_RATIOS'][$arResult['ITEM_MEASURE_RATIO_SELECTED']]['RATIO']),
            'MAX_QUANTITY'                 => $arResult['CATALOG_QUANTITY'],
            'STEP_QUANTITY'                => $arResult['ITEM_MEASURE_RATIOS'][$arResult['ITEM_MEASURE_RATIO_SELECTED']]['RATIO'],
            'CATEGORY'                     => $arResult['CATEGORY_PATH'],
            'ALL_PRICES'                   => $allPrices,
            'VIDEOS'                       => $arResult['VIDEOS'],
        ],
        'BASKET'       => [
            'ADD_PROPS'        => $arParams['ADD_PROPERTIES_TO_BASKET'] === 'Y',
            'QUANTITY'         => $arParams['PRODUCT_QUANTITY_VARIABLE'],
            'PROPS'            => $arParams['PRODUCT_PROPS_VARIABLE'],
            'EMPTY_PROPS'      => $emptyProductProperties,
            'BASKET_URL' => $arParams['BASKET_URL'],
            'BASKET_URL_AJAX' => SITE_DIR.'include/ajax/buy.php',
            'ADD_URL_TEMPLATE' => $arResult['~ADD_URL_TEMPLATE'],
            'BUY_URL_TEMPLATE' => $arResult['~BUY_URL_TEMPLATE'],
        ],
        'WISH'         => [
            'WISHES'            => $arResult['WISHES'],
            'WISH_URL_TEMPLATE' => SITE_DIR.'include/ajax/wish.php',
        ],
    ];
    unset($emptyProductProperties);
}

if ($arParams['DISPLAY_COMPARE']) {
    $jsParams['COMPARE'] = [
        'COMPARE_URL_TEMPLATE'        => $arResult['~COMPARE_URL_TEMPLATE'],
        'COMPARE_DELETE_URL_TEMPLATE' => $arResult['~COMPARE_DELETE_URL_TEMPLATE'],
        'COMPARE_PATH'                => $arParams['COMPARE_PATH'],
    ];
}
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

        var <?=$obName?> = new JCCatalogElementView(<?=CUtil::PhpToJSObject($jsParams, false, true)?>);

	</script>
<?
unset($actualItem, $itemIds, $jsParams);
?>
