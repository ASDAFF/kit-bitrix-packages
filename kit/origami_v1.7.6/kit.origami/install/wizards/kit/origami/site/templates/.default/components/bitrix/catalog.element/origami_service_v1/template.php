<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}
/**
 * Copyright (c) 27/8/2020 Created By/Edited By ASDAFF asdaff.asad@yandex.ru
 */

global $analogProducts;

use Bitrix\Main\Localization\Loc;
use Kit\Origami\Helper\Config;
use \Kit\Origami\Helper\Prop;

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
    //'STICKER_ID' => $mainId . '_sticker',
    //'BIG_SLIDER_ID' => $mainId . '_big_slider',
    //'BIG_IMG_CONT_ID' => $mainId . '_bigimg_cont',
    //'SLIDER_CONT_ID' => $mainId . '_slider_cont',
    'OLD_PRICE_ID' => $mainId . '_old_price',
    'PRICE_ID' => $mainId . '_price',
    'DISCOUNT_PRICE_ID' => $mainId . '_price_discount',
    'PRICE_TOTAL' => $mainId . '_price_total',
    //'SLIDER_CONT_OF_ID' => $mainId . '_slider_cont_',
    'QUANTITY_ID' => $mainId . '_quantity',
    'QUANTITY_DOWN_ID' => $mainId . '_quant_down',
    'QUANTITY_UP_ID' => $mainId . '_quant_up',
    'QUANTITY_MEASURE' => $mainId . '_quant_measure',
    'QUANTITY_LIMIT' => $mainId . '_quant_limit',
    'BUY_LINK' => $mainId . '_buy_link',
    'ADD_BASKET_LINK' => $mainId . '_add_basket_link',
    'BASKET_ACTIONS_ID' => $mainId . '_basket_actions',
    'NOT_AVAILABLE_MESS' => $mainId . '_not_avail',
    //'COMPARE_LINK' => $mainId . '_compare_link',
    //'WISH_LINK' => $mainId . '_wish_link',
    //'WISH_LINK_MODIFICATION' => $mainId . '_wish_link_modification',
    'TREE_ID' => $mainId . '_skudiv',
    'DISPLAY_PROP_DIV' => $mainId . '_sku_prop',
    'DISPLAY_MAIN_PROP_DIV' => $mainId . '_main_sku_prop',
    //'OFFER_GROUP' => $mainId . '_set_group_',
    'BASKET_PROP_DIV' => $mainId . '_basket_prop',
    //'SUBSCRIBE_LINK' => $mainId . '_subscribe',
    //'TABS_ID' => $mainId . '_tabs',
    //'TAB_CONTAINERS_ID' => $mainId . '_tab_containers',
    //'SMALL_CARD_PANEL_ID' => $mainId . '_small_card_panel',
    //'TABS_PANEL_ID' => $mainId . '_tabs_panel',
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
?>

<div class="service-order elem_check_basket_<?= $arResult['ID'] ?>" id="<?= $itemIds['ID'] ?>" itemscope itemtype="http://schema.org/Product">
    <div class="service-order__banner" style='background-image: linear-gradient(90deg, #FFFFFF 0%, rgba(255, 255, 255, 0.95) 100%), linear-gradient(90deg, rgba(255, 255, 255, 0.95) 0%, rgba(255, 255, 255, 0) 100%), url("<?=CFile::GetPath($arResult["DETAIL_PICTURE"]["ID"])?>")'>
        <div class="service-order__banner-content">
            <div class="service-order__banner-content-mobile-banner">
                <img src="<?=CFile::GetPath($arResult['DETAIL_PICTURE']['ID'])?>" alt="<?=$arResult['NAME']?>">
            </div>
            <div class="service-order__banner-content-padding-mobile">
                <?if(isset($arResult['NAME']) && $arParams['DISPLAY_NAME'] === 'Y'):?>
                    <div class="service-order__banner-title">
                        <span><?=$arResult['NAME']?></span>
                    </div>
                <?endif;?>
                <?if(isset($arResult['PREVIEW_TEXT'])):?>
                    <div class="service-order__banner-description">
                        <span><?=$arResult['PREVIEW_TEXT']?></span>
                    </div>
                <?endif;?>
                <div class="service-order__banner-price">
                    <div class="service-order__banner-price-title">
                        <span><?=Loc::getMessage('SERVICES_PRICE')?></span>
                    </div>
                    <div class="service-order__banner-price-wrapper">
                        <span class="service-order__banner-amount"><?=$arResult['ITEM_PRICES'][0]['PRINT_PRICE']?><?= ' /' . $arResult['ITEM_MEASURE']['TITLE']?></span>
                            <span class="service-order__banner-discount" id="<?=$itemIds['PRICE_ID'];?>" style="<?=(isset($arResult['ITEM_PRICES'][0]['RATIO_DISCOUNT']) && $arResult['ITEM_PRICES'][0]['DISCOUNT'] != 0) ? '' : 'display: none;'; ?>"><?=$arResult['ITEM_PRICES'][0]['PRINT_BASE_PRICE']?></span>
                    </div>
                </div>
                <div class="service-order__banner-economy" style="<?=(isset($arResult['ITEM_PRICES'][0]['RATIO_DISCOUNT']) && $arResult['ITEM_PRICES'][0]['DISCOUNT'] != 0) ? '' : 'display: none;'; ?>">
                    <span class="service-order__banner-economy-title"><?=Loc::getMessage('SERVICES_ECONOMY')?></span>
                    <span class="service-order__banner-economy-amount" id="<?=$itemIds['DISCOUNT_PRICE_ID'];?>"><?=$arResult['ITEM_PRICES'][0]['PRINT_DISCOUNT']?></span>
                </div>

                <div class="service-order__banner-buttons">
                    <div class="product-detail-info-block-basket">


                        <div class="service-detail-item__content-hidden-counter" data-entity="quantity-block">
                            <button class="service-detail-item__content-hidden-counter-btn" id="<?= $itemIds['QUANTITY_DOWN_ID'] ?>"> -</button>
                            <input class="service-detail-item__content-hidden-counter-input"
                                   type="number"
                                   placeholder=""
                                   value="<?= $price['MIN_QUANTITY'] ?>"
                                   id="<?= $itemIds['QUANTITY_ID'] ?>">
                            <button class="service-detail-item__content-hidden-counter-btn" id="<?= $itemIds['QUANTITY_UP_ID'] ?>"> +</button>
                        </div>

                        <div class="service-order__banner-buttons-add-wrapper" id="<?=$itemIds['BASKET_ACTIONS_ID']; ?>">
                            <a class="service-order__banner-buttons-add main_btn"  id="<?=$itemIds['ADD_BASKET_LINK']; ?>" href="javascript:void(0)" rel="nofollow" style="color: #fff;">
                                 <span>
                                     <?=Loc::getMessage('CT_BCE_CATALOG_ADD');?>
                                 </span>
                            </a>
                        </div>

<!--                        --><?//if(\Bitrix\Main\Loader::includeModule('kit.orderphone')):?>
                            <button class="service-order__banner-buttons-byu-in-click main-color_btn-transparent" id="modal_oc">
                                <span><?=Loc::getMessage('SERVICES_BUY_ONE_CLICK')?></span>
                                <svg class="service-order__banner-buttons-byu-in-click-icon" width="20" height="20">
                                    <use
                                        xlink:href="/local/templates/kit_origami/assets/img/sprite.svg#icon_one_click"></use>
                                </svg>
                            </button>
<!--                        --><?//endif;?>
                    </div>

                    <div class="product-detail-info-block-path-to-basket" style="display: none;">
                        <a href="<?= Config::get('BASKET_PAGE') ?>"
                           class="in_basket main_btn-white">
                            <span></span><?= Loc::getMessage('DETAIL_PRODUCT_IN_BASKET') ?>
                        </a>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <div class="service-order__detail">
        <?if(isset($arResult['DETAIL_TEXT'])):?>
            <div class="service-order__detail-description">
                <div class="service-order__detail-description-title">
                    <span><?=Loc::getMessage('SERVICES_ABOUT_SERVICE')?></span>
                </div>

                <div class="service-order__detail-description-text-wrapper">
                    <div class="service-order__detail-description-text">
                    <span>
                        <?=$arResult['DETAIL_TEXT']?>
                    </span>
                    </div>
                </div>

                <div class="service-order__detail-description-show-more main-color">
                    <span><?=Loc::getMessage('SERVICES_VIEW_ALL')?></span>
                </div>
            </div>
        <?endif;?>
        <?if(isset($arResult['PROPERTIES'])):?>
            <div>
                <div class="service-order__detail-params">
                    <?foreach ($arResult['PROPERTIES'] as $key => $item):?>
                        <?if(strpos($key, 'SERVICE_PROP') !== false && $item['VALUE'] !== ""):?>
                            <div class="service-order__detail-params-item">
                                <div class="service-order__detail-params-title">
                                    <span><?=$item['NAME']?></span>
                                </div>
                                <div class="service-order__detail-params-data">
                                    <span><?=$item['VALUE']?></span>
                                </div>
                            </div>
                        <?endif;?>
                    <?endforeach;?>
                </div>
            </div>
        <?endif;?>
    </div>
</div>



<?
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

?>

    <meta itemprop="name" content="<?= $name ?>"/>
    <meta itemprop="category" content="<?= $arResult['CATEGORY_PATH'] ?>"/>
    <span itemprop="offers" itemscope itemtype="http://schema.org/Offer">
	<meta itemprop="price" content="<?= $price['RATIO_PRICE'] ?>"/>
	<meta itemprop="priceCurrency" content="<?= $price['CURRENCY'] ?>"/>
	<link itemprop="availability"
          href="http://schema.org/<?= ($actualItem['CAN_BUY'] ? 'InStock'
              : 'OutOfStock') ?>"/>
</span>

<?
$allPrices = [];
$allPrices[$item['ID']] = $arResult['ITEM_ALL_PRICES'][$actualItem['ITEM_PRICE_SELECTED']]["PRICES"];

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


$jsParams['CONFIG']['DETAIL_IMAGE_RESOLUTION'] = $arParams['IMAGE_RESOLUTION']; //  <===== params: 16by9; 4by3; 1by1


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
        var <?=$obName?> = new JCCatalogElement(<?=CUtil::PhpToJSObject($jsParams, false, true)?>);
        <? if (Config::get('SHOW_ZOOM_' . $template) == 'Y' && $actualItem['MORE_PHOTO'][0]['MEDIUM']['ID'] != 'empty'): ?>
        <? endif; ?>
    </script>
<?
unset($actualItem, $itemIds, $jsParams);
?>
