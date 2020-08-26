<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
/** @var CBitrixComponentTemplate $this */
/** @var array $arParams */
/** @var array $arResult */

/** @global CDatabase $DB */

use \Bitrix\Main\Localization\Loc;
use Sotbit\Origami\Helper\Config;

$this->setFrameMode(true);

$arParams['SHOW_MAX_QUANTITY'] = 'Y';
$haveOffers = !empty($arResult['OFFERS']);

foreach ($arResult["ITEMS"] as $item) {
    $haveOffers[] = !empty($item['OFFERS']);
}

$templateData = array(
    'TEMPLATE_THEME' => $this->GetFolder() . '/themes/' . $arParams['TEMPLATE_THEME'] . '/style.css',
    'TEMPLATE_CLASS' => 'bx_' . $arParams['TEMPLATE_THEME']
);
$arSkuTemplate = array();
if (is_array($arResult['SKU_PROPS'])) {
    foreach ($arResult['SKU_PROPS'] as $iblockId => $skuProps) {
        $arSkuTemplate[$iblockId] = array();
        foreach ($skuProps as &$arProp) {
            ob_start();
            if ('TEXT' == $arProp['SHOW_MODE']) {
                if (5 < $arProp['VALUES_COUNT']) {
                    $strClass = 'bx_item_detail_size full';
                    $strWidth = ($arProp['VALUES_COUNT'] * 20) . '%';
                    $strOneWidth = (100 / $arProp['VALUES_COUNT']) . '%';
                    $strSlideStyle = '';
                } else {
                    $strClass = 'bx_item_detail_size';
                    $strWidth = '100%';
                    $strOneWidth = '20%';
                    $strSlideStyle = 'display: none;';
                }
                ?>
            <div class="<? echo $strClass; ?>" id="#ITEM#_prop_<? echo $arProp['ID']; ?>_cont">
                <span class="bx_item_section_name_gray"><? echo htmlspecialcharsex($arProp['NAME']); ?></span>
                <div class="bx_size_scroller_container">
                    <div class="bx_size">
                        <ul id="#ITEM#_prop_<? echo $arProp['ID']; ?>_list" style="width: <? echo $strWidth; ?>;"><?
                            foreach ($arProp['VALUES'] as $arOneValue) {
                                ?>
                                <li data-treevalue="<? echo $arProp['ID'] . '_' . $arOneValue['ID']; ?>"
                                    data-onevalue="<? echo $arOneValue['ID']; ?>"
                                    style="width: <? echo $strOneWidth; ?>;"><i></i><span
                                            class="cnt"><? echo htmlspecialcharsex($arOneValue['NAME']); ?></span></li>
                                <?
                            }
                            ?></ul>
                    </div>
                    <div class="bx_slide_left" id="#ITEM#_prop_<? echo $arProp['ID']; ?>_left"
                         data-treevalue="<? echo $arProp['ID']; ?>" style="<? echo $strSlideStyle; ?>"></div>
                    <div class="bx_slide_right" id="#ITEM#_prop_<? echo $arProp['ID']; ?>_right"
                         data-treevalue="<? echo $arProp['ID']; ?>" style="<? echo $strSlideStyle; ?>"></div>
                </div>
                </div><?
            } elseif ('PICT' == $arProp['SHOW_MODE']) {
                if (5 < $arProp['VALUES_COUNT']) {
                    $strClass = 'bx_item_detail_scu full';
                    $strWidth = ($arProp['VALUES_COUNT'] * 20) . '%';
                    $strOneWidth = (100 / $arProp['VALUES_COUNT']) . '%';
                    $strSlideStyle = '';
                } else {
                    $strClass = 'bx_item_detail_scu';
                    $strWidth = '100%';
                    $strOneWidth = '20%';
                    $strSlideStyle = 'display: none;';
                }
                ?>
            <div class="<? echo $strClass; ?>" id="#ITEM#_prop_<? echo $arProp['ID']; ?>_cont">
                <span class="bx_item_section_name_gray"><? echo htmlspecialcharsex($arProp['NAME']); ?></span>
                <div class="bx_scu_scroller_container">
                    <div class="bx_scu">
                        <ul id="#ITEM#_prop_<? echo $arProp['ID']; ?>_list" style="width: <? echo $strWidth; ?>;"><?
                            foreach ($arProp['VALUES'] as $arOneValue) {
                                ?>
                            <li data-treevalue="<? echo $arProp['ID'] . '_' . $arOneValue['ID'] ?>"
                                data-onevalue="<? echo $arOneValue['ID']; ?>"
                                style="width: <? echo $strOneWidth; ?>; padding-top: <? echo $strOneWidth; ?>;"><i
                                        title="<? echo htmlspecialcharsbx($arOneValue['NAME']); ?>"></i>
                                <span class="cnt"><span class="cnt_item"
                                                        style="background-image:url('<? echo $arOneValue['PICT']['SRC']; ?>');"
                                                        title="<? echo htmlspecialcharsbx($arOneValue['NAME']); ?>"></span></span>
                                </li><?
                            }
                            ?></ul>
                    </div>
                    <div class="bx_slide_left" id="#ITEM#_prop_<? echo $arProp['ID']; ?>_left"
                         data-treevalue="<? echo $arProp['ID']; ?>" style="<? echo $strSlideStyle; ?>"></div>
                    <div class="bx_slide_right" id="#ITEM#_prop_<? echo $arProp['ID']; ?>_right"
                         data-treevalue="<? echo $arProp['ID']; ?>" style="<? echo $strSlideStyle; ?>"></div>
                </div>
                </div><?
            }
            $arSkuTemplate[$iblockId][$arProp['CODE']] = ob_get_contents();
            ob_end_clean();
            unset($arProp);
        }
    }
}

?>
<div class="puzzle_block">
    <p class="puzzle_block__title fonts__middle_title"><? echo GetMessage('SB_HREF_TITLE') ?></p>
    <div class="tabs_block__product_card">
        <? if (!empty($arResult['ITEMS'])): ?>
            <?
            foreach ($arResult['ITEMS'] as $key => $arItem) { ?>
                <div class="product_card__block_item product_card__block_item-2">
                <?
                $strMainID = $this->GetEditAreaId($arItem['ID'] . $key);

                $arItemIDs = array(
                    'ID' => $strMainID,
                    'PICT' => $strMainID . '_pict',
                    'SECOND_PICT' => $strMainID . '_secondpict',
                    'MAIN_PROPS' => $strMainID . '_main_props',

                    'QUANTITY' => $strMainID . '_quantity',
                    'QUANTITY_DOWN' => $strMainID . '_quant_down',
                    'QUANTITY_UP' => $strMainID . '_quant_up',
                    'QUANTITY_MEASURE' => $strMainID . '_quant_measure',
                    'BUY_LINK' => $strMainID . '_buy_link',
                    'SUBSCRIBE_LINK' => $strMainID . '_subscribe',

                    'PRICE' => $strMainID . '_price',
                    'DSC_PERC' => $strMainID . '_dsc_perc',
                    'SECOND_DSC_PERC' => $strMainID . '_second_dsc_perc',

                    'PROP_DIV' => $strMainID . '_sku_tree',
                    'PROP' => $strMainID . '_prop_',
                    'DISPLAY_PROP_DIV' => $strMainID . '_sku_prop',
                    'BASKET_PROP_DIV' => $strMainID . '_basket_prop'
                );

                $strObName = 'ob' . preg_replace("/[^a-zA-Z0-9_]/", "x", $strMainID);

                $strTitle = (
                isset($arItem["IPROPERTY_VALUES"]["ELEMENT_PREVIEW_PICTURE_FILE_TITLE"]) && '' != isset($arItem["IPROPERTY_VALUES"]["ELEMENT_PREVIEW_PICTURE_FILE_TITLE"])
                    ? $arItem["IPROPERTY_VALUES"]["ELEMENT_PREVIEW_PICTURE_FILE_TITLE"]
                    : $arItem['NAME']
                );
                $showImgClass = $arParams['SHOW_IMAGE'] != "Y" ? "no-imgs" : "";

                ?>
                <div class="product_card__block_item_inner"
                     id="<? echo $strMainID; ?>">
                    <div class="product_card__block <? echo $showImgClass; ?>">
                        <?
                        if ('Y' == $arParams['SHOW_DISCOUNT_PERCENT']) {
                            ?>
                            <div class="sticker_product">
                                <?
                                if ($arItem['LABEL']) {
                                    $color = '#00b02a';
                                    ?>
                                    <div>
                                    <span class="sticker_product__hit fonts__small_comment"
                                          style="background:<?= $color ?>">
                    <? echo $arItem["PROPERTIES"]["KHIT"]["NAME"]; ?>
                </span>
                                    </div><?
                                }
                                ?>
                                <div>
                                    <span class="sticker_product__discount fonts__small_comment">-<? echo $arItem['MIN_PRICE']['DISCOUNT_DIFF_PERCENT']; ?>
                                        %</span>
                                </div>
                            </div>
                            <?
                        } ?>
                        <div class="product_card__block__photo">
                            <?
                            if (Config::get('QUICK_VIEW') == 'Y'):
                                ?>
                                <span class="product_card__block__quick_view" onclick="quickView
                                        ('<?= $arItem['DETAIL_PAGE_URL'] ?>');return false;">
                <?= Loc::getMessage('QUICK_PREVIEW') ?></span>
                            <? endif; ?>

                            <a id="<? echo $arItemIDs['PICT']; ?>" href="<? echo $arItem['DETAIL_PAGE_URL']; ?>"
                               onclick="" class="product_card__block__photo_link"
                               data-entity="image-wrapper"><? if ($arParams['SHOW_IMAGE'] == "Y") {
                                    ?> <img src="<? echo $arItem['PRODUCT_PREVIEW_SECOND']['SRC']; ?>"
                                            title="<? echo $strTitle; ?>" alt="<? echo $strTitle; ?>"/>

                                    <?
                                } ?>

                            </a>
                        </div>
                        <? if ($arParams['SHOW_NAME'] == "Y") { ?>
                            <div class="product_card__block__title_product">
                                <a onclick="" class="product_card__block__title_product_link fonts__middle_text"
                                   href="<?= $arItem['DETAIL_PAGE_URL'] ?>">
                                    <?= $arItem['NAME'] ?>
                                </a>
                            </div>
                            <?
                        } ?>
                        <?
                        if ($arParams['USE_VOTE_RATING'] === 'Y') {
                            $APPLICATION->IncludeComponent(
                                'bitrix:iblock.vote',
                                'stars',
                                [
                                    'CUSTOM_SITE_ID' => null,
                                    'IBLOCK_TYPE' => $arParams['IBLOCK_TYPE'],
                                    'IBLOCK_ID' => $arParams['IBLOCK_ID'],
                                    'ELEMENT_ID' => $arItem['ID'],
                                    'ELEMENT_CODE' => '',
                                    'MAX_VOTE' => '5',
                                    'VOTE_NAMES' => [
                                        '1',
                                        '2',
                                        '3',
                                        '4',
                                        '5'
                                    ],
                                    'SET_STATUS_404' => 'N',
                                    'DISPLAY_AS_RATING' => 'vote_avg',
                                    'CACHE_TYPE' => $arParams['CACHE_TYPE'],
                                    'CACHE_TIME' => $arParams['CACHE_TIME'],
                                    'READ_ONLY' => 'Y'
                                ],
                                $component,
                                ['HIDE_ICONS' => 'Y']
                            );
                        }
                        ?>
                        <?php

                        if ($arResult['PREVIEW_TEXT']) {
                            ?>
                            <div class="product_card__block__comment_product fonts__middle_comment">
                                <?= $arResult['PREVIEW_TEXT'] ?>
                            </div>
                            <?
                        }
                        ?>


                        <div class="product_card__block__article">
                            <?php

                            if ($arParams['SHOW_MAX_QUANTITY'] !== 'N') {
                                if (!$haveOffers) {
                                    if ($arParams['PRODUCT_DISPLAY_MODE'] === 'Y') {
                                        ?>
                                        <div class="product-item-info-container"
                                             id="<?= $arItemIDs['QUANTITY_LIMIT'] ?>"
                                             style="display: none;"
                                             data-entity="quantity-limit-block">
                                            <div class="product-item-info-container-title">
                                                <span class="product-item-quantity"
                                                      data-entity="quantity-limit-value"></span>
                                            </div>
                                        </div>
                                        <?
                                    }
                                } else {
                                    if (
                                        $measureRatio
                                        && (float)$actualItem['CATALOG_QUANTITY'] > 0
                                        && $actualItem['CATALOG_QUANTITY_TRACE'] === 'Y'
                                        && $actualItem['CATALOG_CAN_BUY_ZERO'] === 'N'
                                    ) {
                                        ?>
                                        <span class="product_card__block__presence_product_value_many">
                                           <svg class="product-card_icon-check" width="11px" height="12px">
                                                                                               <use xlink:href="/local/templates/sotbit_origami/assets/img/sprite.svg#icon_check_checkbox"></use>
                                                                                       </svg>

                                        <?
                                        if ($arParams['SHOW_MAX_QUANTITY'] === 'M') {
                                            if ((float)$actualItem['CATALOG_QUANTITY'] / $measureRatio >= $arParams['RELATIVE_QUANTITY_FACTOR']) {
                                                echo $arParams['MESS_RELATIVE_QUANTITY_MANY'];
                                            } else {
                                                echo $arParams['MESS_RELATIVE_QUANTITY_FEW'];
                                            }
                                        } else {
                                            echo $actualItem['CATALOG_QUANTITY'] . ' ' . $actualItem['ITEM_MEASURE']['TITLE'];
                                        }
                                        ?>
					</span>
                                        <?
                                    }
                                }
                            }
                            if ($arItem['PROPERTIES'][Config::get('ARTICUL')]['VALUE']) {
                                ?>
                                <span class="product_card__block__article_title">
				<?= $arItem['PROPERTIES'][Config::get('ARTICUL')]['NAME'] ?>
                                : <?= $arItem['PROPERTIES'][Config::get('ARTICUL')]['VALUE'] ?>
			</span>
                                <?
                            }
                            ?>
                        </div>

                        <?
                        echo '<pre>';
                        var_dump($arParams['SKU_PROPS']);
                        echo '</pre>';
                        ?>


                        <div class="product_card__block_price">
                            <div class="product_card__block__old_new_price">
                                <? if (!empty($arItem['MIN_PRICE'])) {
                                    if ($arParams['SHOW_OLD_PRICE'] == 'Y' && $arItem['MIN_PRICE']['DISCOUNT_VALUE'] < $arItem['MIN_PRICE']['VALUE']) {
                                        ?> <span class="product_card__block__old_price_product  fonts__middle_text"
                                                 id="<?= $itemIds['PRICE_OLD'] ?>"><? echo $arItem['MIN_PRICE']['PRINT_VALUE']; ?></span><?
                                    } ?>
                                    <span class="product_card__block__new_price_product fonts__main_text"
                                          id="<?= $itemIds['PRICE'] ?>">
					<?
                    echo $arItem['MIN_PRICE']['PRINT_DISCOUNT_VALUE']; ?>
</span>
                                    <?
                                    if (!empty($arItem['MIN_PRICE']['PRINT_DISCOUNT_DIFF']) && $arParams['SHOW_OLD_PRICE'] == 'Y'):?>
                                        <div class="product_card__block_saving">
                                            <?= Loc::getMessage('TO_SAVE') ?> <span
                                                    class="product_card__block_saving_title">
					<?= $arItem['MIN_PRICE']['PRINT_DISCOUNT_DIFF'] ?></span>
                                        </div>
                                    <? endif; ?>
                                    <?
                                }
                                ?>
                            </div>
                        </div>


                        <div class="product_card__block__hidden product-item-hidden" data-entity="buttons-block">
                            <?
                            if (Config::get('SHOW_PROPS_HOVER') == 'Y') {
                                if ($haveOffers) {
                                    ?>
                                    <div class="product_card__block__property">
                                        <div id="<?= $itemIds['PROP_DIV'] ?>">
                                            <?

                                            foreach ($arParams['SKU_PROPS'] as $code => $skuProperty) {

                                                $propertyId = $skuProperty['ID'];
                                                $skuProperty['NAME']
                                                    = htmlspecialcharsbx($skuProperty['NAME']);
                                                if (!isset($item['SKU_TREE_VALUES'][$propertyId])) {
                                                    continue;
                                                }

                                                if ($code == Config::get('COLOR')) {

                                                    $type = Config::get('PROP_COLOR_TYPE_SECTION');

                                                    ?>
                                                    <div class="product_card__block__color_product"
                                                         data-entity="sku-block">
                                                        <div class="product_card__block__color_product_title fonts__middle_comment">
                                                            <?= $skuProperty['NAME'] ?>
                                                        </div>
                                                        <div class="product_card__block__color_product_variant product_card__block__color_<?= $type ?>"
                                                             data-entity="sku-line-block">
                                                            <?
                                                            foreach ($skuProperty['VALUES'] as $value) {
                                                                if (!isset($item['SKU_TREE_VALUES'][$propertyId][$value['ID']])) {
                                                                    continue;
                                                                }
                                                                ?>
                                                                <div class="product_card__block__color_product_variant_item product_card__block_product_variant_item"
                                                                     data-treevalue="<?= $propertyId ?>_<?= $value['ID'] ?>"
                                                                     data-onevalue="<?= $value['ID'] ?>"
                                                                     title="<?= $skuProperty['NAME'] ?>: <?= $value['NAME'] ?>">
                                                                    <img src="<?= $value['PICT']['SRC'] ?>"
                                                                         alt="">
                                                                </div>
                                                                <?
                                                            }
                                                            ?>
                                                        </div>
                                                    </div>
                                                    <?
                                                } else {
                                                    ?>
                                                    <div class="product_card__block__property_product"
                                                         data-entity="sku-block">
                                                        <div class="product_card__block__property_product_title fonts__middle_comment">
                                                            <?= $skuProperty['NAME'] ?>
                                                        </div>
                                                        <div
                                                                class="product_card__block__property_product_variant"
                                                                data-entity="sku-line-block"
                                                        >
                                                            <?
                                                            foreach ($skuProperty['VALUES'] as $value) {
                                                                if (!isset($item['SKU_TREE_VALUES'][$propertyId][$value['ID']])) {
                                                                    continue;
                                                                }
                                                                $value['NAME']
                                                                    = htmlspecialcharsbx($value['NAME']); ?>
                                                                <div class="product_card__block__property_product_variant_item product_card__block_product_variant_item"
                                                                     data-treevalue="<?= $propertyId ?>_<?= $value['ID'] ?>"
                                                                     data-onevalue="<?= $value['ID'] ?>"
                                                                     title="<?= $skuProperty['NAME'] ?>: <?= $value['NAME'] ?>">
									<span class="product_card__block__property_product_variant_item_name
									fonts__middle_text">
										<?= $value['NAME'] ?>
									</span>
                                                                </div>
                                                                <?
                                                            }
                                                            ?>
                                                        </div>
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
                            <div class="product_card__block_quantity_price" id="<?= $itemIds['ALL_PRICES'] ?>">
                                <?
                                if (count($actualItem['ALL_PRICES']) > 1) {
                                    foreach ($actualItem['ALL_PRICES'] as $id => $price) {
                                        ?>
                                        <div class="product_card__block__all_price__wrapper"
                                             data-id="<?= $id ?>">
                                            <p class="product_card__block_quantity_price_title fonts__middle_comment">
                                                <?= $actualItem['ALL_PRICES_NAMES'][$id] ?>
                                            </p>
                                            <p class="product_card__block__new_price_product fonts__main_text"
                                               data-price-id="<?= $id ?>">
                                                <?= $price['PRINT_DISCOUNT_PRICE'] ?>
                                            </p>
                                        </div>
                                        <?
                                    }
                                }
                                ?>
                            </div>
                            <div class="product_card__block_buy">
                                <?
                                if (Config::get('SHOW_BUY_BUTTON') == 'Y') {
                                    if ($arParams['USE_PRODUCT_QUANTITY']) {
                                        ?>
                                        <div class="product_card__block_buy_quantity" data-entity="quantity-block">
                                            <span class="product_card__block_buy_quantity__minus fonts__small_title"
                                                  id="<?= $itemIds['QUANTITY_DOWN'] ?>">&dash;</span>
                                            <input class="product_card__block_buy_quantity__input fonts__small_text"
                                                   type="text"
                                                   placeholder="" id="<?= $itemIds['QUANTITY'] ?>"
                                                   value="<?= $measureRatio ?>">
                                            <span class="product_card__block_buy_quantity__plus fonts__small_title"
                                                  id="<?= $itemIds['QUANTITY_UP'] ?>">+</span>
                                        </div>
                                        <?
                                    }
                                    ?>
                                    <div data-entity="buttons-block">
                                        <div class="product-item-button-container">
                                            <?
                                            if ($showSubscribe) {
                                                $APPLICATION->IncludeComponent(
                                                    'bitrix:catalog.product.subscribe',
                                                    '',
                                                    array(
                                                        'PRODUCT_ID' => $item['ID'],
                                                        'BUTTON_ID' => $itemIds['SUBSCRIBE_LINK'],
                                                        'BUTTON_CLASS' => 'btn btn-default ' . $buttonSizeClass,
                                                        'DEFAULT_DISPLAY' => !$actualItem['CAN_BUY'],
                                                        'MESS_BTN_SUBSCRIBE' => $arParams['~MESS_BTN_SUBSCRIBE'],
                                                    ),
                                                    $component,
                                                    array('HIDE_ICONS' => 'Y')
                                                );
                                            }
                                            ?>
                                            <div id="<?= $itemIds['BASKET_ACTIONS'] ?>" <?= ($actualItem['CAN_BUY'] ? '' : 'style="display: none;"') ?>>
                                                <a class="main_btn sweep-to-right <?= $buttonSizeClass ?>"
                                                   id="<?= $itemIds['BUY_LINK'] ?>"
                                                   href="javascript:void(0)" rel="nofollow">
                                                    <?= ($arParams['ADD_TO_BASKET_ACTION'] === 'BUY' ? $arParams['MESS_BTN_BUY'] : $arParams['MESS_BTN_ADD_TO_BASKET']) ?>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <?
                                } else {
                                    ?>
                                    <a class="main_btn sweep-to-right"
                                       href="<?= $item['DETAIL_PAGE_URL'] ?>" rel="nofollow">
                                        <?= Loc::getMessage('ITEM_MORE') ?>
                                    </a>
                                    <?
                                } ?>
                            </div>
                            <div class="product_card__path_to_basket">
                                <a href="<?= Config::get('BASKET_PAGE') ?>" class="in_basket">
                                    <span></span><?= Loc::getMessage('PRODUCT_IN_BASKET') ?>
                                </a>
                            </div>
                            <span id="check_offer_basket_<?= $item['ID'] ?>" style="display: none;"></span>
                        </div>


                        <?
                        if (!isset($arItem['OFFERS']) || empty($arItem['OFFERS'])) // Simple Product
                        {
                            ?>
                            <?
                        if (isset($arItem['DISPLAY_PROPERTIES']) && !empty($arItem['DISPLAY_PROPERTIES'])) {
                            ?>
                            <div class="bx_catalog_item_articul">
                                <?
                                foreach ($arItem['DISPLAY_PROPERTIES'] as $arOneProp) {
                                    ?><br><? echo $arOneProp['NAME']; ?> <strong><?
                                    echo(
                                    is_array($arOneProp['DISPLAY_VALUE'])
                                        ? implode('/', $arOneProp['DISPLAY_VALUE'])
                                        : $arOneProp['DISPLAY_VALUE']
                                    ); ?></strong><?
                                }
                                ?>
                            </div>
                        <?
                        } ?>
                            <div class="bx_catalog_item_controls"><?
                                if ($arItem['CAN_BUY']) {
                                    if ('Y' == $arParams['USE_PRODUCT_QUANTITY']) {
                                        ?>
                                        <div class="bx_catalog_item_controls_blockone">
                                            <div style="display: inline-block;position: relative;">
                                                <a id="<? echo $arItemIDs['QUANTITY_DOWN']; ?>"
                                                   href="javascript:void(0)" class="bx_bt_button_type_2 bx_small"
                                                   rel="nofollow">-</a>
                                                <input type="text" class="bx_col_input"
                                                       id="<? echo $arItemIDs['QUANTITY']; ?>"
                                                       name="<? echo $arParams["PRODUCT_QUANTITY_VARIABLE"]; ?>"
                                                       value="<? echo $arItem['CATALOG_MEASURE_RATIO']; ?>">
                                                <a id="<? echo $arItemIDs['QUANTITY_UP']; ?>" href="javascript:void(0)"
                                                   class="bx_bt_button_type_2 bx_small" rel="nofollow">+</a>
                                                <span id="<? echo $arItemIDs['QUANTITY_MEASURE']; ?>"
                                                      class="bx_cnt_desc"><? echo $arItem['CATALOG_MEASURE_NAME']; ?></span>
                                            </div>
                                        </div>
                                        <?
                                    }
                                    ?>
                                    <div class="bx_catalog_item_controls_blocktwo">
                                        <a id="<? echo $arItemIDs['BUY_LINK']; ?>" class="bx_bt_button bx_medium"
                                           href="javascript:void(0)" rel="nofollow"><?
                                            echo('' != $arParams['MESS_BTN_BUY'] ? $arParams['MESS_BTN_BUY'] : GetMessage('CT_BCS_TPL_MESS_BTN_BUY'));
                                            ?></a>
                                    </div>
                                    <?
                                } else {
                                    ?>
                                    <div class="bx_catalog_item_controls_blockone">
                                    <a class="bx_medium bx_bt_button_type_2"
                                       href="<? echo $arItem['DETAIL_PAGE_URL']; ?>" rel="nofollow">
                                        <? echo('' != $arParams['MESS_BTN_DETAIL'] ? $arParams['MESS_BTN_DETAIL'] : GetMessage('SB_TPL_MESS_BTN_DETAIL')); ?>
                                    </a>
                                    </div><?
                                    if ('Y' == $arParams['PRODUCT_SUBSCRIPTION'] && 'Y' == $arItem['CATALOG_SUBSCRIPTION']) {
                                        ?>
                                        <div class="bx_catalog_item_controls_blocktwo">
                                        <a id="<? echo $arItemIDs['SUBSCRIBE_LINK']; ?>"
                                           class="bx_bt_button_type_2 bx_medium" href="javascript:void(0)"><?
                                            echo('' != $arParams['MESS_BTN_SUBSCRIBE'] ? $arParams['MESS_BTN_SUBSCRIBE'] : GetMessage('SB_TPL_MESS_BTN_SUBSCRIBE'));
                                            ?>
                                        </a>
                                        </div><?
                                    }
                                }
                                ?>
                                <div style="clear: both;"></div>
                            </div>

                        <?
                        $emptyProductProperties = empty($arItem['PRODUCT_PROPERTIES']);
                        if ('Y' == $arParams['ADD_PROPERTIES_TO_BASKET'] && !$emptyProductProperties)
                        {
                        ?>
                            <div id="<? echo $arItemIDs['BASKET_PROP_DIV']; ?>" style="display: none;">
                                <?
                                if (!empty($arItem['PRODUCT_PROPERTIES_FILL'])) {
                                    foreach ($arItem['PRODUCT_PROPERTIES_FILL'] as $propID => $propInfo) {
                                        ?><input type="hidden"
                                                 name="<? echo $arParams['PRODUCT_PROPS_VARIABLE']; ?>[<? echo $propID; ?>]"
                                                 value="<? echo htmlspecialcharsbx($propInfo['ID']); ?>"><?
                                        if (isset($arItem['PRODUCT_PROPERTIES'][$propID]))
                                            unset($arItem['PRODUCT_PROPERTIES'][$propID]);
                                    }
                                }
                                $emptyProductProperties = empty($arItem['PRODUCT_PROPERTIES']);

                                if (!$emptyProductProperties) {
                                    ?>
                                    <table>
                                        <?
                                        foreach ($arItem['PRODUCT_PROPERTIES'] as $propID => $propInfo) {
                                            ?>
                                            <tr>
                                                <td><? echo $arItem['PROPERTIES'][$propID]['NAME']; ?></td>
                                                <td>
                                                    <?
                                                    if (
                                                        'L' == $arItem['PROPERTIES'][$propID]['PROPERTY_TYPE']
                                                        && 'C' == $arItem['PROPERTIES'][$propID]['LIST_TYPE']
                                                    ) {
                                                        foreach ($propInfo['VALUES'] as $valueID => $value) {
                                                            ?><label><input type="radio"
                                                                            name="<? echo $arParams['PRODUCT_PROPS_VARIABLE']; ?>[<? echo $propID; ?>]"
                                                                            value="<? echo $valueID; ?>" <? echo($valueID == $propInfo['SELECTED'] ? '"checked"' : ''); ?>><? echo $value; ?>
                                                            </label><br><?
                                                        }
                                                    } else {
                                                        ?><select
                                                        name="<? echo $arParams['PRODUCT_PROPS_VARIABLE']; ?>[<? echo $propID; ?>]"><?
                                                        foreach ($propInfo['VALUES'] as $valueID => $value) {
                                                            ?>
                                                            <option
                                                            value="<? echo $valueID; ?>" <? echo($valueID == $propInfo['SELECTED'] ? '"selected"' : ''); ?>><? echo $value; ?></option><?
                                                        }
                                                        ?></select><?
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
                        $arJSParams = array(

                            'THANKS' => Loc::getMessage('THANKS'),
                            'SUCCESS_MESSAGE' => Loc::getMessage('SUCCESS_MESSAGE'),
                            'PRODUCT_TYPE' => $arItem['CATALOG_TYPE'],
                            'SHOW_QUANTITY' => $arParams['USE_PRODUCT_QUANTITY'],
                            'SHOW_ADD_BASKET_BTN' => false,
                            'SHOW_BUY_BTN' => true,
                            'SHOW_ABSENT' => true,
                            'PRODUCT' => array(
                                'ID' => $arItem['ID'],
                                'NAME' => $arItem['~NAME'],
                                'PICT' => ('Y' == $arItem['SECOND_PICT'] ? $arItem['PREVIEW_PICTURE_SECOND'] : $arItem['PREVIEW_PICTURE']),
                                'CAN_BUY' => $arItem["CAN_BUY"],
                                'SUBSCRIPTION' => ('Y' == $arItem['CATALOG_SUBSCRIPTION']),
                                'CHECK_QUANTITY' => $arItem['CHECK_QUANTITY'],
                                'MAX_QUANTITY' => $arItem['CATALOG_QUANTITY'],
                                'STEP_QUANTITY' => $arItem['CATALOG_MEASURE_RATIO'],
                                'QUANTITY_FLOAT' => is_double($arItem['CATALOG_MEASURE_RATIO']),
                                'ADD_URL' => $arItem['~ADD_URL'],
                                'SUBSCRIBE_URL' => $arItem['~SUBSCRIBE_URL']
                            ),
                            'BASKET' => array(
                                'ADD_PROPS' => ('Y' == $arParams['ADD_PROPERTIES_TO_BASKET']),
                                'QUANTITY' => $arParams['PRODUCT_QUANTITY_VARIABLE'],
                                'PROPS' => $arParams['PRODUCT_PROPS_VARIABLE'],
                                'EMPTY_PROPS' => $emptyProductProperties
                            ),
                            'VISUAL' => array(
                                'ID' => $arItemIDs['ID'],
                                'PICT_ID' => ('Y' == $arItem['SECOND_PICT'] ? $arItemIDs['SECOND_PICT'] : $arItemIDs['PICT']),
                                'QUANTITY_ID' => $arItemIDs['QUANTITY'],
                                'QUANTITY_UP_ID' => $arItemIDs['QUANTITY_UP'],
                                'QUANTITY_DOWN_ID' => $arItemIDs['QUANTITY_DOWN'],
                                'PRICE_ID' => $arItemIDs['PRICE'],
                                'BUY_ID' => $arItemIDs['BUY_LINK'],
                                'BASKET_PROP_DIV' => $arItemIDs['BASKET_PROP_DIV']
                            ),
                            'LAST_ELEMENT' => $arItem['LAST_ELEMENT']
                        );
                        ?>
                            <script type="text/javascript">
                                var <? echo $strObName; ?> =
                                new JCCatalogSectionBest(<? echo CUtil::PhpToJSObject($arJSParams, false, true); ?>);
                            </script><?
                        }
                        else // Wth Sku
                        {
                        ?>
                        <?
                        $boolShowOfferProps = !!$arItem['OFFERS_PROPS_DISPLAY'];
                        $boolShowProductProps = (isset($arItem['DISPLAY_PROPERTIES']) && !empty($arItem['DISPLAY_PROPERTIES']));
                        if ($boolShowProductProps || $boolShowOfferProps)
                        {
                        ?>


                            <div class="product_card__block__property_product" data-entity="sku-block">
                                <?
                                if ($boolShowProductProps) {

                                    foreach ($arItem['DISPLAY_PROPERTIES'] as $arOneProp) {
                                        ?>
                                        <div class="product_card__block__property_product_title fonts__middle_comment">
                                            <? echo $arOneProp['NAME'];

                                            //echo '<pre>';var_dump($arOneProp['DISPLAY_VALUE']);echo '</pre>';
                                            ?>
                                        </div>
                                        <div
                                                class="product_card__block__property_product_variant"
                                                data-entity="sku-line-block"
                                        >
                                            <? if (is_array($arOneProp['DISPLAY_VALUE'])) {
                                                foreach ($arOneProp['DISPLAY_VALUE'] as $value) { ?>
                                                    <div class="product_card__block__property_product_variant_item product_card__block_product_variant_item"
                                                         data-treevalue="<?= $propertyId ?>_<?= $arOneProp['ID'] ?>"
                                                         data-onevalue="<?= $arOneProp['ID'] ?>"
                                                         title="<?= $arOneProp['NAME'] ?>: <?= $value ?>">
									<span class="product_card__block__property_product_variant_item_name
									fonts__middle_text">
										<?= $value ?>
									</span>
                                                    </div>
                                                    <?
                                                }
                                            } else { ?>
                                                <div class="product_card__block__property_product_variant_item product_card__block_product_variant_item"
                                                     data-treevalue="<?= $propertyId ?>_<?= $arOneProp['ID'] ?>"
                                                     data-onevalue="<?= $arOneProp['ID'] ?>"
                                                     title="<?= $arOneProp['NAME'] ?>: <?= $value ?>">
									<span class="product_card__block__property_product_variant_item_name
									fonts__middle_text">
										<?
                                        echo $arOneProp['DISPLAY_VALUE']; ?>
                                            </span>
                                                </div>
                                            <? }
                                            ?>
                                        </div>
                                        <?
                                    }
                                }

                                ?>
                                <span id="<? echo $arItemIDs['DISPLAY_PROP_DIV']; ?>" style="display: none;"></span>
                            </div>
                        <?
                        } ?>
                            <div class="product_card__block_buy no_touch">
                                <?
                                if ($arParams['USE_PRODUCT_QUANTITY'] == 'Y') {
                                    ?>
                                    <div class="product_card__block_buy_quantity" data-entity="quantity-block">
                                    <span class="product_card__block_buy_quantity__minus fonts__small_title"
                                          id="<? echo $arItemIDs['QUANTITY_DOWN']; ?>">&dash;</span>
                                        <input class="product_card__block_buy_quantity__input fonts__small_text"
                                               type="text"
                                               placeholder="" id="<? echo $arItemIDs['QUANTITY']; ?>"
                                               value="<? echo $arItem['CATALOG_MEASURE_RATIO']; ?>">
                                        <span class="product_card__block_buy_quantity__plus fonts__small_title"
                                              id="<? echo $arItemIDs['QUANTITY_UP']; ?>">+</span>
                                    </div>
                                    <?
                                }
                                ?>
                                <div data-entity="buttons-block">
                                    <div class="product-item-button-container">
                                        <?
                                        if ($showSubscribe) {
                                            $APPLICATION->IncludeComponent(
                                                'bitrix:catalog.product.subscribe',
                                                '',
                                                array(
                                                    'PRODUCT_ID' => $item['ID'],
                                                    'BUTTON_ID' => $itemIds['SUBSCRIBE_LINK'],
                                                    'BUTTON_CLASS' => 'btn btn-default ' . $buttonSizeClass,
                                                    'DEFAULT_DISPLAY' => !$actualItem['CAN_BUY'],
                                                    'MESS_BTN_SUBSCRIBE' => $arParams['~MESS_BTN_SUBSCRIBE'],
                                                ),
                                                $component,
                                                array('HIDE_ICONS' => 'Y')
                                            );
                                        }
                                        ?>
                                        <div id="<?= $arItemIDs['BASKET_ACTIONS'] ?>">
                                            <a class="main_btn sweep-to-right <?= $buttonSizeClass ?>"
                                               id="<?= $arItemIDs['BUY_LINK'] ?>"
                                               href="javascript:void(0)" rel="nofollow">
                                                <?= ($arParams['MESS_BTN_BUY'] !== '' ? $arParams['MESS_BTN_BUY'] : GetMessage('CT_BCS_TPL_MESS_BTN_BUY')) ?>
                                            </a>
                                        </div>
                                    </div>
                                </div>


                            </div>

                            <div class="product_card__path_to_basket">
                                <a href="<?= Config::get('BASKET_PAGE') ?>" class="in_basket">
                                    <span></span><?= Loc::getMessage('PRODUCT_IN_BASKET') ?>
                                </a>
                            </div>

                        <?
                        if (!empty($arItem['OFFERS']) && isset($arSkuTemplate[$arItem['IBLOCK_ID']]))
                        {
                        $arSkuProps = array();
                        ?>
                            <div class="bx_catalog_item_scu" id="<? echo $arItemIDs['PROP_DIV']; ?>"><?
                                foreach ($arSkuTemplate[$arItem['IBLOCK_ID']] as $code => $strTemplate) {
                                    if (!isset($arItem['OFFERS_PROP'][$code]))
                                        continue;
                                    echo '<div>', str_replace('#ITEM#_prop_', $arItemIDs['PROP'], $strTemplate), '</div>';
                                }

                                if (isset($arResult['SKU_PROPS'][$arItem['IBLOCK_ID']])) {
                                    foreach ($arResult['SKU_PROPS'][$arItem['IBLOCK_ID']] as $arOneProp) {
                                        if (!isset($arItem['OFFERS_PROP'][$arOneProp['CODE']]))
                                            continue;
                                        $arSkuProps[] = array(
                                            'ID' => $arOneProp['ID'],
                                            'SHOW_MODE' => $arOneProp['SHOW_MODE'],
                                            'VALUES_COUNT' => $arOneProp['VALUES_COUNT']
                                        );
                                    }
                                }
                                foreach ($arItem['JS_OFFERS'] as &$arOneJs) {
                                    if (0 < $arOneJs['PRICE']['DISCOUNT_DIFF_PERCENT'])
                                        $arOneJs['PRICE']['DISCOUNT_DIFF_PERCENT'] = '-' . $arOneJs['PRICE']['DISCOUNT_DIFF_PERCENT'] . '%';
                                }

                                ?></div><?
                        if ($arItem['OFFERS_PROPS_DISPLAY']) {
                            foreach ($arItem['JS_OFFERS'] as $keyOffer => $arJSOffer) {
                                $strProps = '';
                                if (!empty($arJSOffer['DISPLAY_PROPERTIES'])) {
                                    foreach ($arJSOffer['DISPLAY_PROPERTIES'] as $arOneProp) {
                                        $strProps .= '<br>' . $arOneProp['NAME'] . ' <strong>' . (
                                            is_array($arOneProp['VALUE'])
                                                ? implode(' / ', $arOneProp['VALUE'])
                                                : $arOneProp['VALUE']
                                            ) . '</strong>';
                                    }
                                }
                                $arItem['JS_OFFERS'][$keyOffer]['DISPLAY_PROPERTIES'] = $strProps;
                            }
                        }
                        $arJSParams = array(
                            'THANKS' => Loc::getMessage('THANKS'),
                            'SUCCESS_MESSAGE' => Loc::getMessage('SUCCESS_MESSAGE'),
                            'PRODUCT_TYPE' => $arItem['CATALOG_TYPE'],
                            'SHOW_QUANTITY' => $arParams['USE_PRODUCT_QUANTITY'],
                            'SHOW_ADD_BASKET_BTN' => false,
                            'SHOW_BUY_BTN' => true,
                            'SHOW_ABSENT' => true,
                            'SHOW_SKU_PROPS' => $arItem['OFFERS_PROPS_DISPLAY'],
                            'SECOND_PICT' => ($arParams['SHOW_IMAGE'] == "Y" ? $arItem['SECOND_PICT'] : false),
                            'SHOW_OLD_PRICE' => ('Y' == $arParams['SHOW_OLD_PRICE']),
                            'SHOW_DISCOUNT_PERCENT' => ('Y' == $arParams['SHOW_DISCOUNT_PERCENT']),
                            'DEFAULT_PICTURE' => array(
                                'PICTURE' => $arItem['PRODUCT_PREVIEW'],
                                'PICTURE_SECOND' => $arItem['PRODUCT_PREVIEW_SECOND']
                            ),
                            'VISUAL' => array(
                                'ID' => $arItemIDs['ID'],
                                'PICT_ID' => $arItemIDs['PICT'],
                                'SECOND_PICT_ID' => $arItemIDs['SECOND_PICT'],
                                'QUANTITY_ID' => $arItemIDs['QUANTITY'],
                                'QUANTITY_UP_ID' => $arItemIDs['QUANTITY_UP'],
                                'QUANTITY_DOWN_ID' => $arItemIDs['QUANTITY_DOWN'],
                                'QUANTITY_MEASURE' => $arItemIDs['QUANTITY_MEASURE'],
                                'PRICE_ID' => $arItemIDs['PRICE'],
                                'TREE_ID' => $arItemIDs['PROP_DIV'],
                                'TREE_ITEM_ID' => $arItemIDs['PROP'],
                                'BUY_ID' => $arItemIDs['BUY_LINK'],
                                'ADD_BASKET_ID' => $arItemIDs['ADD_BASKET_ID'],
                                'DSC_PERC' => $arItemIDs['DSC_PERC'],
                                'SECOND_DSC_PERC' => $arItemIDs['SECOND_DSC_PERC'],
                                'DISPLAY_PROP_DIV' => $arItemIDs['DISPLAY_PROP_DIV'],
                            ),
                            'BASKET' => array(
                                'QUANTITY' => $arParams['PRODUCT_QUANTITY_VARIABLE'],
                                'PROPS' => $arParams['PRODUCT_PROPS_VARIABLE']
                            ),
                            'PRODUCT' => array(
                                'ID' => $arItem['ID'],
                                'NAME' => $arItem['~NAME']
                            ),
                            'OFFERS' => $arItem['JS_OFFERS'],
                            'OFFER_SELECTED' => $arItem['OFFERS_SELECTED'],
                            'TREE_PROPS' => $arSkuProps,
                            'LAST_ELEMENT' => $arItem['LAST_ELEMENT']
                        );
                        ?>
                            <script type="text/javascript">
                                var <? echo $strObName; ?> =
                                new JCCatalogSectionBest(<? echo CUtil::PhpToJSObject($arJSParams, false, true); ?>);
                            </script>
                            <?
                        }
                        }
                        ?></div>
                </div></div><?
            }
            ?>
            <div style="clear: both;"></div>


        <? else: ?>
            <div class="bx-nothing"><?= GetMessage("SB_NO_PRODUCTS"); ?></div>
        <? endif ?>
    </div>
</div>

<script type="text/javascript">
    BX.message({
        MESS_BTN_BUY: '<? echo('' != $arParams['MESS_BTN_BUY'] ? CUtil::JSEscape($arParams['MESS_BTN_BUY']) : GetMessageJS('SB_TPL_MESS_BTN_BUY')); ?>',
        MESS_BTN_ADD_TO_BASKET: '<? echo('' != $arParams['MESS_BTN_ADD_TO_BASKET'] ? CUtil::JSEscape($arParams['MESS_BTN_ADD_TO_BASKET']) : GetMessageJS('SB_TPL_MESS_BTN_ADD_TO_BASKET')); ?>',
        MESS_BTN_DETAIL: '<? echo('' != $arParams['MESS_BTN_DETAIL'] ? CUtil::JSEscape($arParams['MESS_BTN_DETAIL']) : GetMessageJS('SB_TPL_MESS_BTN_DETAIL')); ?>',
        MESS_NOT_AVAILABLE: '<? echo('' != $arParams['MESS_BTN_DETAIL'] ? CUtil::JSEscape($arParams['MESS_BTN_DETAIL']) : GetMessageJS('SB_TPL_MESS_BTN_DETAIL')); ?>',
        BTN_MESSAGE_BASKET_REDIRECT: '<? echo GetMessageJS('SB_CATALOG_BTN_MESSAGE_BASKET_REDIRECT'); ?>',
        BASKET_URL: '<? echo $arParams["BASKET_URL"]; ?>',
        ADD_TO_BASKET_OK: '<? echo GetMessageJS('SB_ADD_TO_BASKET_OK'); ?>',
        TITLE_ERROR: '<? echo GetMessageJS('SB_CATALOG_TITLE_ERROR') ?>',
        TITLE_BASKET_PROPS: '<? echo GetMessageJS('SB_CATALOG_TITLE_BASKET_PROPS') ?>',
        TITLE_SUCCESSFUL: '<? echo GetMessageJS('SB_ADD_TO_BASKET_OK'); ?>',
        BASKET_UNKNOWN_ERROR: '<? echo GetMessageJS('SB_CATALOG_BASKET_UNKNOWN_ERROR') ?>',
        BTN_MESSAGE_SEND_PROPS: '<? echo GetMessageJS('SB_CATALOG_BTN_MESSAGE_SEND_PROPS'); ?>',
        BTN_MESSAGE_CLOSE: '<? echo GetMessageJS('SB_CATALOG_BTN_MESSAGE_CLOSE') ?>'
    });
</script>
