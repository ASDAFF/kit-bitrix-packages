<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use \Bitrix\Main\Localization\Loc;
use Kit\Origami\Helper\Config;
use \Kit\Origami\Helper\Prop;
use Bitrix\Main\Page\Asset;

//Asset::getInstance()->addCss(SITE_DIR . "local/templates/.default/components/bitrix/catalog.item/origami_item/column/style.css");

$labelProps = unserialize(Config::get('LABEL_PROPS'));
$arParams['LABEL_PROP'] = $labelProps;

if (!$arParams['LABEL_PROP']) {
    $arParams['LABEL_PROP'] = [];
}

if ($haveOffers) {
    $showDisplayProps = !empty($item['DISPLAY_PROPERTIES']);
    $showProductProps = $arParams['PRODUCT_DISPLAY_MODE'] === 'Y' && $item['OFFERS_PROPS_DISPLAY'];
    $showPropsBlock = $showDisplayProps || $showProductProps;
    $showSkuBlock = $arParams['PRODUCT_DISPLAY_MODE'] === 'Y' && !empty($item['OFFERS_PROP']);
} else {
    $showDisplayProps = !empty($item['DISPLAY_PROPERTIES']);
    $showProductProps = $arParams['ADD_PROPERTIES_TO_BASKET'] === 'Y' && !empty($item['PRODUCT_PROPERTIES']);
    $showPropsBlock = $showDisplayProps || $showProductProps;
    $showSkuBlock = false;
}

?>
<?
$arPromotions = CCatalogDiscount::GetDiscount($item['ID'], $item['IBLOCK_ID']);
$i = 1;
$dbProductDiscounts = array();
foreach ($arPromotions as $itemDiscount) {
    $dbProductDiscounts[$i] = $itemDiscount;
    $i++;
}
$blockID = randString(8);
?>
<div class="product-card-column">
    <? if ($morePhoto[0]['SRC']) { ?>
        <div class="product-card-column__image-wrapper"
             onclick="quickView('<?=$item['DETAIL_PAGE_URL']?>'); return false;"
        >
            <a
                href="<?= $item['DETAIL_PAGE_URL'] ?>"
                class="product-card-column__image-wrapper-link <?= $arParams["HOVER_EFFECT"] ?>"
                data-entity="image-wrapper"
                title="<?= $productTitle ?>">

                <img
                    <?= $strLazyLoad ?>
                    alt="<?= $imgAlt ?>"
                    id="<?= $itemIds['PICT'] ?>"
                    title="<?= $imgTitle ?>">
                <? if ($arResult['LAZY_LOAD']): ?>
                    <span class="loader-lazy"></span> <!--LOADER_LAZY-->
                <? endif; ?>
            </a>
        </div>
        <?
    }
    ?>

    <div class="product-card-column__data-wrapper-outer">
        <div>
            <a class="product-card-column__title" href="<?= $item['DETAIL_PAGE_URL'] ?>" onclick=""
               title="<?= $productTitle ?>">
                <?= $item['NAME'] ?>
            </a>
            <div class="product-card-column__data-btns">
                <?
                if ($arParams['SHOW_MAX_QUANTITY'] !== 'N') {
                    if ($haveOffers) {
                        if ($arParams['PRODUCT_DISPLAY_MODE'] === 'Y') {
                            ?>
                            <div id="<?= $itemIds['QUANTITY_LIMIT'] ?>" style="display: none;"
                                 data-entity="quantity-limit-block">
                                <span data-entity="quantity-limit-value"></span>
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
                            if ($arParams['SHOW_MAX_QUANTITY'] === 'M') {
                                if ((float)$actualItem['CATALOG_QUANTITY'] / $measureRatio >= $arParams['RELATIVE_QUANTITY_FACTOR']) {
                                    ?>
                                    <span class="product-card-inner__quantity product-card-inner__quantity--lot">
                                            <?= $arParams['MESS_RELATIVE_QUANTITY_MANY'] ?>
                                        </span>
                                    <?
                                } else {
                                    ?>
                                    <span class="product-card-inner__quantity product-card-inner__quantity--few">
                                            <?= $arParams['MESS_RELATIVE_QUANTITY_FEW'] ?>
                                        </span>
                                    <?
                                }
                            } else {
                                if ($actualItem['CATALOG_QUANTITY'] > 0) {
                                    ?>
                                    <span class="product-card-inner__quantity product-card-inner__quantity--lot">
                                            <?php
                                            echo $actualItem['CATALOG_QUANTITY'] . ' ' . $actualItem['ITEM_MEASURE']['TITLE'];
                                            ?>
                                        </span>
                                    <?
                                } else {
                                    ?>
                                    <span class="product-card-inner__quantity product-card-inner__quantity--none">
                                            <?php
                                            echo $actualItem['CATALOG_QUANTITY'] . ' ' . $actualItem['ITEM_MEASURE']['TITLE'];
                                            ?>
                                        </span>
                                    <?
                                }
                            }
                        }
                    }
                }
                ?>

                <div class="product-card-column__data-btns-favourite-compare">
                    <? if ($haveOffers || $actualItem['CAN_BUY']): ?>
                        <? if ($arParams["SHOW_DELAY"] && (!$haveOffers || $arParams['PRODUCT_DISPLAY_MODE'] === 'Y')): ?>
                            <span data-entity="wish" id="<?= $itemIds['WISH_LINK'] ?>"
                                  <? if ($haveOffers && $actualItem['CAN_BUY']): ?>style="display: none;"<? endif; ?>>
                                <svg width="16" height="16">
                                    <use
                                        xlink:href="/local/templates/kit_origami/assets/img/sprite.svg#icon_favourite"></use>
                                </svg>
                                </span>
                        <? endif; ?>
                    <? endif; ?>
                    <?
                    if ($arParams["SHOW_COMPARE"] && $arParams['DISPLAY_COMPARE'] && (!$haveOffers || $arParams['PRODUCT_DISPLAY_MODE'] === 'Y')) {
                        ?>
                        <span data-entity="compare-checkbox"
                              id="<?= $itemIds['COMPARE_LINK'] ?>">
                        <svg width="16" height="16">
                            <use xlink:href="/local/templates/kit_origami/assets/img/sprite.svg#icon_compare"></use>
                        </svg>
                    </span>
                        <?
                    }
                    ?>
                </div>

            </div>
        </div>
        <!-- PRICE -->
        <? $frame = $this->createFrame()->begin();
        if (!$haveOffers):?>
            <?if ($arParams["FILL_ITEM_ALL_PRICES"] != "Y"):?>
            <div>
                <div class="product-card-column__price">
                    <? if ($arParams['SHOW_OLD_PRICE'] === 'Y' && (isset($item['CHECK_DISCOUNT']) || $price['DISCOUNT'])) { ?>
                        <p class="product-card-inner__old-price" id="<?= $itemIds['PRICE_OLD'] ?>">
                            <?= $price['PRINT_BASE_PRICE'] ?>
                        </p>
                    <? } ?>
                    <div id="<?= $itemIds['PRICE'] ?>">
                        <?= $price['PRINT_PRICE'] ?>
                    </div>
                </div>
                <? if ($arParams['SHOW_OLD_PRICE'] === 'Y' && (isset($item['CHECK_DISCOUNT']) || $price['DISCOUNT'])): ?>
                    <div class="product-card-inner__saving" id="<?= $itemIds['PRICE_SAVE'] ?>">
                        <p class="product-card-inner__saving-title"><?= Loc::getMessage('TO_SAVE') ?>
                            <span class="product-card-inner__saving-value">
                                <?= $price['PRINT_DISCOUNT'] ?>
                            </span>
                        </p>
                    </div>
                <? endif; ?>
            </div>
        <? elseif (isset($item['ALL_PRICES_NAMES'])): ?>
            <div class="product-card-inner__option-price" id="<?= $itemIds['ALL_PRICES'] ?>">
                <?
                {
                    foreach ($item['ALL_PRICES_NAMES'] as $id => $idName) {
                        $allPrice = isset($actualItem['ITEM_ALL_PRICES'][$actualItem['ITEM_PRICE_SELECTED']]["PRICES"][$id]) ? $actualItem['ITEM_ALL_PRICES'][$actualItem['ITEM_PRICE_SELECTED']]["PRICES"][$id] : array();
                        ?>
                        <div class="product-card-inner__option-price-retail" data-id="<?= $id ?>"
                             <? if (empty($allPrice)): ?>style="display:none"<?endif;
                        ?>>
                            <p class="product-card-inner__option-price-title">
                                <?= $item['ALL_PRICES_NAMES'][$id] ?>:
                            </p>
                            <?
                            if ($arParams['SHOW_OLD_PRICE'] === 'Y' && (isset($item['CHECK_DISCOUNT']) || $allPrice['RATIO_DISCOUNT'])) {
                                ?>
                                <p class="product-card-inner__old-price" data-oldprice-id="<?= $id ?>"
                                   <? if (!$allPrice['RATIO_DISCOUNT']): ?>style="display:none"<?endif;
                                ?>>
                                    <?= $price['PRINT_BASE_PRICE'] ?>
                                </p>
                                <?
                            } ?>
                            <p class="product-card-inner__option-price-value" data-price-id="<?= $id ?>">
                                <?= $allPrice['PRINT_PRICE'] ?>
                            </p>
                            <? if ($arParams['SHOW_OLD_PRICE'] === 'Y' && (isset($item['CHECK_DISCOUNT']) || $allPrice['RATIO_DISCOUNT'])): ?>
                                <div class="product-card-inner__saving" data-discount-id="<?= $id ?>"
                                     <? if (!$allPrice['RATIO_DISCOUNT']): ?>style="display:none"<?endif;
                                ?>>
                                    <p class="product-card-inner__saving-title"><?= Loc::getMessage('TO_SAVE') ?>
                                        <span class="product-card-inner__saving-value">
                                    <?= $allPrice['PRINT_DISCOUNT'] ?>
                                </span>
                                    </p>
                                </div>
                            <? endif; ?>
                        </div>
                        <?
                    }
                }
                ?>
            </div>
        <?endif;
        else:
            $min = ($item['OFFERS'][0]['ITEM_PRICES']) ? $item['OFFERS'][0]['ITEM_PRICES'][0]['PRICE'] : $item['OFFERS'][1]['ITEM_PRICES'][0]['PRICE'];
            $minTitle = ($item['OFFERS'][0]['ITEM_PRICES']) ? $item['OFFERS'][0]['ITEM_PRICES'][0]['PRINT_BASE_PRICE'] : $item['OFFERS'][1]['ITEM_PRICES'][0]['PRINT_BASE_PRICE'];
            foreach ($item['OFFERS'] as $offer) {
                if ($offer['ITEM_PRICES'] && $offer['ITEM_PRICES'][0]['PRICE'] < $min) {
                    $min = $offer['ITEM_PRICES'][0]['PRICE'];
                    $minTitle = $offer['ITEM_PRICES'][0]['PRINT_BASE_PRICE'];
                }
            }
        ?>
            <div>
                <div class="product-card-column__price">
                    <div id="<?= $itemIds['PRICE_OLD'] ?>">
                        <?= Loc::getMessage('FROM_PRICE'). ' ' . $minTitle?>
                    </div>
                </div>
            </div>
        <?endif;
        $frame->end(); ?>

        <!-- PRICE -->
    </div>

    <?if(!$haveOffers):?>
        <div class="product-card-column__buttons">
            <form method="post" class="product-card-inner__form" id="check_offer_basket_<?= $item['ID'] ?>">
                <? if ($arParams["SHOW_BUY"] && (!$haveOffers || $arParams['PRODUCT_DISPLAY_MODE'] === 'Y')): ?>
                    <div data-entity="buttons-block"
                         class="<?= ($haveOffers ? " offer" : "") ?>">
                        <div class="product-card-inner__buy">
                            <?php
                            if ($arParams['USE_PRODUCT_QUANTITY']) {
                                ?>
                                <div class="product-card-inner__counter product-counter__amount"
                                     data-entity="quantity-block">
                                    <button class="product-card-inner__counter-btn product-counter__button--less"
                                            type="button" id="<?= $itemIds['QUANTITY_DOWN'] ?>">
                                        &minus;
                                    </button>
                                    <input type="text" id="<?= $itemIds['QUANTITY'] ?>" value="<?= $measureRatio ?>">
                                    <button class="product-card-inner__counter-btn product-counter__button--more"
                                            id="<?= $itemIds['QUANTITY_UP'] ?>" type="button">
                                        +
                                    </button>
                                </div>
                                <?
                            }
                            ?>
                            <div id="<?= $itemIds['BASKET_ACTIONS'] ?>"
                                <?= ($actualItem['CAN_BUY'] ? '' : 'style="display: none;"') ?>>
                                <button id="<?= $itemIds['BUY_LINK'] ?>" class="product-card-inner__in-basket"
                                        type="button">
                                <span>
                                    <?= ($arParams['ADD_TO_BASKET_ACTION'] === 'BUY' ? $arParams['MESS_BTN_BUY'] : $arParams['MESS_BTN_ADD_TO_BASKET']) ?>
                                </span>
                                    <svg class="icon_cart_medium" width="20" height="16">
                                        <use
                                            xlink:href="/local/templates/kit_origami/assets/img/sprite.svg#icon_cart_medium"></use>
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <? if ($actualItem['CAN_BUY'] == 'Y'): ?>
                            <div class="product-card-inner__product-basket" style="display: none">
                                <a class="product-card-inner__product-basket-btn" href="<?= Config::get('BASKET_PAGE') ?>">
                                <span>
                                    <?= Loc::getMessage('PRODUCT_IN_BASKET') ?>
                                </span>
                                </a>
                            </div>
                        <? endif ?>

                        <?
                        if ($showSubscribe)
                        {
                            $APPLICATION->IncludeComponent(
                                'bitrix:catalog.product.subscribe',
                                'origami_recommend',
                                array(
                                    'PRODUCT_ID' => $item['ID'],
                                    'BUTTON_ID' => $itemIds['SUBSCRIBE_LINK'],
                                    'BUTTON_CLASS' => 'product-card-inner__subscribe',
                                    'DEFAULT_DISPLAY' => !$actualItem['CAN_BUY'],
                                    'MESS_BTN_SUBSCRIBE' => $arParams['~MESS_BTN_SUBSCRIBE'],
                                ),
                                $component,
                                array('HIDE_ICONS' => 'Y')
                            );
                        }
                        ?>

                    </div>
                <? else: ?>
                    <div class="product-card-inner__buttons-block product-mode-N" data-entity="buttons-block">
                        <div class="product-card-inner__buttom-more"
                             title="<?= Loc::getMessage('ITEM_MORE') ?> <?= $item["NAME"] ?>">
                            <a class="product-card-inner__buttom-more-btn" href="<?= $item['DETAIL_PAGE_URL'] ?>">
                                <?= Loc::getMessage('ITEM_MORE') ?>
                            </a>
                        </div>
                    </div>
                <? endif; ?>
            </form>
        </div>
    <?else:?>
        <div class="product-card-column__buttons">
                <a href="<?= $item['DETAIL_PAGE_URL'] ?>" class="product-card-inner__in-basket" type="button" style="align-items: inherit; display: flex;">
                    <span style="color: #fff;">
                        <?=Loc::getMessage('GET_MODIFICATION')?>
                    </span>
                    <svg class="icon_cart_medium" width="16" height="16">
                        <use
                            xlink:href="/local/templates/kit_origami/assets/img/sprite.svg#icon_arrow_right_medium"></use>
                    </svg>
                </a>
        </div>
    <?endif;?>
</div>
<?
if (Config::get('TIMER_PROMOTIONS') == 'Y') {
    if ($dbProductDiscounts) {
        $APPLICATION->IncludeComponent(
            "kit:origami.timer",
            "origami_default",
            array(
                "COMPONENT_TEMPLATE" => "origami_default",
                "ID" => $item["ID"],
                "BLOCK_ID" => $blockID,
                "ACTIVATE" => "Y",
                "TIMER_SIZE" => "md",
                "MOBILE_DESTROY" => $arParams['MOBILE_VIEW_MINIMAL'],
                "TIMER_DATE_END" => $dbProductDiscounts[1]['ACTIVE_TO']
            ),
            $component
        );
    }
}
?>

