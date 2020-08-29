<?
use Sotbit\Origami\Helper\Config;

if (! defined( 'B_PROLOG_INCLUDED' ) || B_PROLOG_INCLUDED !== true) die();

$this->IncludeLangFile( 'template.php' );
$cartId = $arParams['cartId'];

switch (\Sotbit\Origami\Helper\Config::get('HEADER'))
{
    case 1:
        require (realpath( dirname( __FILE__ ) ) . '/top_template_1.php');
        break;
    case 2:
        require (realpath( dirname( __FILE__ ) ) . '/top_template_2.php');
        break;
    default:
        require (realpath( dirname( __FILE__ ) ) . '/top_template_2.php');
        break;
}

//if ($arParams["SHOW_PRODUCTS"] == "Y" &&$arResult["NUM_PRODUCTS_ALL"])
{
    ?>
    <div data-role="basket-item-list" class="bx-basket-item-list
	window-without-bg window_basket" style="display:none;">
        <div class='modal-block'>
            <div class='modal-block-inner'>
                <div id="<?=$cartId?>products" class="bx-basket-item-list-container open-basket-origami-container modal-content ">
                    <!--  ///////// NEW  PAGE-PROOFS ///////////////-->
                    <div class="open-basket-origami">
                        <h3 class="open-basket-origami__title"><?=GetMessage("ORIGAMI_BASKET_BASKET")?></h3>
                        <div class="open-basket-origami__close" onclick="<?=$cartId?>.removeList()">
                            <svg class="open-basket-origami__close-svg" width="13px" height="16px" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                                 viewBox="0 0 59 59" style="enable-background:new 0 0 59 59;" xml:space="preserve">
                                        <g>
                                            <path d="M29.5,51c0.552,0,1-0.447,1-1V17c0-0.553-0.448-1-1-1s-1,0.447-1,1v33C28.5,50.553,28.948,51,29.5,51z"/>
                                            <path d="M19.5,51c0.552,0,1-0.447,1-1V17c0-0.553-0.448-1-1-1s-1,0.447-1,1v33C18.5,50.553,18.948,51,19.5,51z"/>
                                            <path d="M39.5,51c0.552,0,1-0.447,1-1V17c0-0.553-0.448-1-1-1s-1,0.447-1,1v33C38.5,50.553,38.948,51,39.5,51z"/>
                                            <path d="M52.5,6H38.456c-0.11-1.25-0.495-3.358-1.813-4.711C35.809,0.434,34.751,0,33.499,0H23.5c-1.252,0-2.31,0.434-3.144,1.289
                                                C19.038,2.642,18.653,4.75,18.543,6H6.5c-0.552,0-1,0.447-1,1s0.448,1,1,1h2.041l1.915,46.021C10.493,55.743,11.565,59,15.364,59
                                                h28.272c3.799,0,4.871-3.257,4.907-4.958L50.459,8H52.5c0.552,0,1-0.447,1-1S53.052,6,52.5,6z M21.792,2.681
                                                C22.24,2.223,22.799,2,23.5,2h9.999c0.701,0,1.26,0.223,1.708,0.681c0.805,0.823,1.128,2.271,1.24,3.319H20.553
                                                C20.665,4.952,20.988,3.504,21.792,2.681z M46.544,53.979C46.538,54.288,46.4,57,43.636,57H15.364
                                                c-2.734,0-2.898-2.717-2.909-3.042L10.542,8h37.915L46.544,53.979z"/>
                                        </g>
                                        </svg>
                            <?=GetMessage("ORIGAMI_BASKET_EMPTY_LIST")?>
                        </div>
                        <div class="open-basket-origami__tabs">
                            <?if($arResult["SHOW_BASKET"]):?>
                            <input type="radio" name="tabs" id="open-basket-origami__tab_basket" <?if($arParams["TAB_ACTIVE"]=="BUY"):?>checked<?endif;?>>
                            <label for="open-basket-origami__tab_basket"><?=GetMessage("ORIGAMI_BASKET_PRODUCTS")?> (<?=$arResult["NUM_PRODUCTS"]?>)</label>
                            <?endif;?>
                            <?if($arResult["SHOW_BASKET"]):?>
                            <div class="open-basket-origami__tab">
                                <?if($arResult["NUM_PRODUCTS"]==0):?>
                                    <div class="open-basket-origami__tab-not-products">
                                        <div class="open-basket-origami__tab-not-products-logo">
                                            <svg width="93" height="93">
                                                <use xlink:href="/local/templates/kit_origami/assets/img/sprite.svg#icon_cart_big"></use>
                                            </svg>
                                        </div>
                                        <div class="open-basket-origami__tab-not-products-content">
                                            <p class="open-basket-origami__tab-not-products-title"><?=GetMessage("ORIGAMI_BASKET_YOUR_EMPTY")?></p>
                                            <p class="open-basket-origami__tab-not-products-descripion"><?=GetMessage("ORIGAMI_BASKET_YOUR_EMPTY_DESC")?></p>
                                            <div class="open-basket-origami__tab-not-products-btns">
                                                <a href="<?=SITE_DIR?>catalog/" class="open-basket-origami__tab-not-products-cat"><?=GetMessage("ORIGAMI_BASKET_TO_CATALOG")?></a>
                                                <a href="<?=SITE_DIR?>" class="open-basket-origami__tab-not-products-main"><?=GetMessage("ORIGAMI_BASKET_TO_MAIN")?></a>
                                            </div>
                                        </div>
                                    </div>
                                <?else:?>
                                    <div class="open-basket-product-block-title">
                                        <div class="open-basket-product-block-name open-basket-product-block-name--name"><?=GetMessage("ORIGAMI_BASKET_HEADER_NAME")?></div>
                                        <div class="open-basket-product-block-name open-basket-product-block-name--price"><?=GetMessage("ORIGAMI_BASKET_HEADER_PRICE")?></div>
                                        <div class="open-basket-product-block-name open-basket-product-block-name--quantity"><?=GetMessage("ORIGAMI_BASKET_HEADER_QNT")?></div>
                                        <div class="open-basket-product-block-name open-basket-product-block-name--summ"><?=GetMessage("ORIGAMI_BASKET_HEADER_SUMM")?></div>
                                    </div>
                                    <div class="open-basket-product-block origami_main_scroll">

                                        <?if(isset($arResult["CATEGORIES"]["READY"]))foreach($arResult["CATEGORIES"]["READY"] as $arCart):?>
                                            <div class="open-basket-product-block__one">
                                                <svg class="open-basket-product-block__one-close" onclick="<?=$cartId?>.removeItemFromCart(<?=$arCart['ID']?>)" width="11px" version="1.1" xmlns="http://www.w3.org/2000/svg" height="11px" viewBox="0 0 64 64" xmlns:xlink="http://www.w3.org/1999/xlink" enable-background="new 0 0 64 64">
                                                    <g>
                                                        <path d="M28.941,31.786L0.613,60.114c-0.787,0.787-0.787,2.062,0,2.849c0.393,0.394,0.909,0.59,1.424,0.59   c0.516,0,1.031-0.196,1.424-0.59l28.541-28.541l28.541,28.541c0.394,0.394,0.909,0.59,1.424,0.59c0.515,0,1.031-0.196,1.424-0.59   c0.787-0.787,0.787-2.062,0-2.849L35.064,31.786L63.41,3.438c0.787-0.787,0.787-2.062,0-2.849c-0.787-0.786-2.062-0.786-2.848,0   L32.003,29.15L3.441,0.59c-0.787-0.786-2.061-0.786-2.848,0c-0.787,0.787-0.787,2.062,0,2.849L28.941,31.786z"/>
                                                    </g>
                                                </svg>
                                                <div class="open-basket-product-block__item open-basket-product__picture">
                                                    <?if($arCart["DETAIL_PAGE_URL"]):?>
                                                        <a href="<?=$arCart["DETAIL_PAGE_URL"]?>">
                                                            <img src="<?=( empty($arCart["PICTURE_SRC"]) ? "/upload/kit.origami/no_photo_small.svg" : $arCart["PICTURE_SRC"] )?>" alt="<?=$arCart["NAME"]?>" title="<?=$arCart["NAME"]?>">
                                                        </a>
                                                    <?else:?>
                                                        <img src="<?=( empty($arCart["PICTURE_SRC"]) ? "/upload/kit.origami/no_photo_small.svg" : $arCart["PICTURE_SRC"] )?>" alt="<?=$arCart["NAME"]?>" title="<?=$arCart["NAME"]?>">
                                                    <?endif;?>

                                                </div>
                                                <div class="open-basket-product-block__item open-basket-product__info">
                                                    <?if($arCart["DETAIL_PAGE_URL"]):?>
                                                        <a href="<?=$arCart["DETAIL_PAGE_URL"]?>" class="open-basket-product__name">
                                                            <?=$arCart["NAME"]?>
                                                        </a>
                                                    <?else:?>
                                                        <span class="open-basket-product__name">
                                                    <?=$arCart["NAME"]?>
                                                </span>
                                                    <?endif;?>
                                                    <?if(isset($arResult["PROPS"][$arCart["ID"]])):?>
                                                        <div class="open-basket-product__property">
                                                            <?foreach($arResult["PROPS"][$arCart["ID"]] as $arProp):?>
                                                                <div class="open-basket-product__property-item">
                                                                    <span class="open-basket-product__property-name"><?=$arProp["NAME"]?>:</span>
                                                                    <span class="open-basket-product__property-value"><?=$arProp["VALUE"]?></span>
                                                                </div>
                                                            <?endforeach?>
                                                        </div>
                                                    <?endif;?>
                                                </div>
                                                <div class="open-basket-product-block__item open-basket-product__price">
                                                    <div class="open-basket-product__price-one">
                                                        <div class="open-basket-product__price-one-name"><?=$arCart["NOTES"]?>:</div>
                                                        <div class="open-basket-product__price-one-value"><?=$arCart["PRICE_FORMATED"]?></div>
                                                        <?if(isset($arCart["ECONOM_FORMAT"])):?>
                                                            <div class="open-basket-product__price-one-old-value"><?=$arCart["FULL_PRICE"]?></div>
                                                            <div class="open-basket-product__price-one-quantity"><?=GetMessage("ORIGAMI_BASKET_PRICE_TO")?> <?=$arCart['RATIO']?> <?=$arCart["MEASURE_NAME"]?></div>
                                                            <!-- <div class="open-basket-product__price-one-saving">
                                                            <div class="price-one-saving__name"><?=GetMessage("ORIGAMI_BASKET_ECONOM")?></div>
                                                            <div class="price-one-saving__value"><?=$arCart["ECONOM_FORMAT"]?></div>
                                                        </div> -->
                                                        <?endif;?>
                                                    </div>
                                                </div>
                                                <div class="open-basket-product-block__item open-basket-product__number">
                                                    <div class="open-basket-product__quantity">
                                                        <div class="open-basket-product__quantity-minus" onclick="<?=$cartId?>.refreshMinusCart(<?=$arCart['ID']?>, <?=$arCart['RATIO']?>, <?=$arCart['PRODUCT_ID']?>, this)">
                                                            <i class="far fa-minus"></i>
                                                        </div>
                                                        <input class="open-basket-product__quantity-value" type="text" value="<?=$arCart["QUANTITY"]?>" data-available="<?=$arCart["AVAILABLE_QUANTITY"]?>" onchange="<?if($arCart['QUANTITY_TRACE'] == 'Y'):?> ( parseInt(this.getAttribute('data-available')) <= parseInt(this.value)  ) ? this.value = this.getAttribute('data-available') : ''; <?endif;?> <?=$cartId?>.refreshInputCart(<?=$arCart['ID']?>, <?=$arCart['RATIO']?>, <?=$arCart['PRODUCT_ID']?>, this)">
                                                        <div class="open-basket-product__quantity-plus"
                                                             onclick="
                                                             <?if($arCart['QUANTITY_TRACE'] == 'Y'):?>
                                                                 ( parseInt(this.previousElementSibling.getAttribute('data-available')) <= parseInt(this.previousElementSibling.value)  ) ?
                                                                 this.previousElementSibling.value = parseInt(this.previousElementSibling.getAttribute('data-available')) :
                                                                 <?= $cartId?>.refreshPlusCart(<?= $arCart['ID'] ?>, <?= $arCart['RATIO'] ?>, <?= $arCart['PRODUCT_ID'] ?>, this)
                                                            <?else:?>
                                                                <?= $cartId?>.refreshPlusCart(<?= $arCart['ID'] ?>, <?= $arCart['RATIO'] ?>, <?= $arCart['PRODUCT_ID'] ?>, this)
                                                            <?endif;?>
                                                            ">
                                                            <i class="far fa-plus"></i>
                                                        </div>
                                                    </div>
                                                    <p class="open-basket-product__number-item"><?=$arCart["MEASURE_NAME"]?></p>
                                                </div>
                                                <div class="open-basket-product-block__item open-basket-product__total">
                                                    <div class="open-basket-product__total-block">
                                                        <div class="open-basket-product__total-price">
                                                            <div class="open-basket-product__total-name"><?=GetMessage("ORIGAMI_BASKET_SUMM")?></div>
                                                            <div class="open-basket-product__total-value"><?=$arCart["SUM"]?></div>
                                                            <?if(isset($arCart["ECONOM_FORMAT"])):?>
                                                                <div class="open-basket-product__total-eco"><?=$arCart["OLD_PRICE_FORMAT"]?></div>
                                                                <div class="open-basket-product__price-one-saving">
                                                                    <div class="price-one-saving__name"><?=GetMessage("ORIGAMI_BASKET_ECONOM")?>:</div>
                                                                    <div class="price-one-saving__value"><?=$arCart["ECONOM_SUM_FORMAT"]?></div>
                                                                </div>
                                                            <?endif;?>
                                                        </div>
                                                        <?if($arResult["SHOW_DELAY"]):?>
                                                        <div class="open-basket-product__wish" title="<?=GetMessage("ORIGAMI_BASKET_DELAY")?>" onclick="<?=$cartId?>.refreshDelay(<?=$arCart['ID']?>)">
                                                            <svg class="open-basket-product__wish-svg" height="15px" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M458.4 64.3C400.6 15.7 311.3 23 256 79.3 200.7 23 111.4 15.6 53.6 64.3-21.6 127.6-10.6 230.8 43 285.5l175.4 178.7c10 10.2 23.4 15.9 37.6 15.9 14.3 0 27.6-5.6 37.6-15.8L469 285.6c53.5-54.7 64.7-157.9-10.6-221.3zm-23.6 187.5L259.4 430.5c-2.4 2.4-4.4 2.4-6.8 0L77.2 251.8c-36.5-37.2-43.9-107.6 7.3-150.7 38.9-32.7 98.9-27.8 136.5 10.5l35 35.7 35-35.7c37.8-38.5 97.8-43.2 136.5-10.6 51.1 43.1 43.5 113.9 7.3 150.8z"/></svg>
                                                        </div>
                                                        <?endif;?>
                                                    </div>
                                                </div>

                                            </div>
                                        <?endforeach;?>

                                    </div>
                                <?endif;?>
                            </div>
                            <?endif;?>
                            <?if($arResult["SHOW_DELAY"]):?>
                            <input type="radio" name="tabs" id="open-basket-origami__tab_wish" <?if($arParams["TAB_ACTIVE"]=="DELAY"):?>checked<?endif;?>>
                            <label for="open-basket-origami__tab_wish"><?=GetMessage("ORIGAMI_BASKET_WISHLIST")?> (<?=$arResult["NUM_PRODUCTS_DELAY"]?>)</label>
                            <?endif;?>
                            <?if($arResult["SHOW_DELAY"]):?>
                            <div class="open-basket-origami__tab">
                                <?if($arResult["NUM_PRODUCTS_DELAY"]==0):?>
                                    <div class="open-basket-origami__tab-not-products">
                                        <div class="open-basket-origami__tab-not-products-logo">
                                            <svg width="93" height="93">
                                                <use xlink:href="/local/templates/kit_origami/assets/img/sprite.svg#icon_cart_big"></use>
                                            </svg>
                                        </div>
                                        <div class="open-basket-origami__tab-not-products-content">
                                            <p class="open-basket-origami__tab-not-products-title"><?=GetMessage("ORIGAMI_DELAY_YOUR_EMPTY")?></p>
                                            <p class="open-basket-origami__tab-not-products-descripion"><?=GetMessage("ORIGAMI_DELAY_YOUR_EMPTY_DESC")?></p>
                                            <div class="open-basket-origami__tab-not-products-btns">
                                                <a href="<?=SITE_DIR?>catalog/" class="open-basket-origami__tab-not-products-cat"><?=GetMessage("ORIGAMI_BASKET_TO_CATALOG")?></a>
                                                <a href="<?=SITE_DIR?>" class="open-basket-origami__tab-not-products-main"><?=GetMessage("ORIGAMI_BASKET_TO_MAIN")?></a>
                                            </div>
                                        </div>
                                    </div>
                                <?else:?>
                                    <div class="open-basket-product-block-title">
                                        <div class="open-basket-product-block-name open-basket-product-block-name--name"><?=GetMessage("ORIGAMI_BASKET_HEADER_NAME")?></div>
                                        <div class="open-basket-product-block-name open-basket-product-block-name--price"><?=GetMessage("ORIGAMI_BASKET_HEADER_PRICE")?></div>
                                        <div class="open-basket-product-block-name open-basket-product-block-name--quantity"><?=GetMessage("ORIGAMI_BASKET_HEADER_QNT")?></div>
                                        <div class="open-basket-product-block-name open-basket-product-block-name--summ"><?=GetMessage("ORIGAMI_BASKET_HEADER_SUMM")?></div>
                                    </div>
                                <div class="open-basket-product-block origami_main_scroll">
                                    <?if(isset($arResult["CATEGORIES"]["DELAY"]))foreach($arResult["CATEGORIES"]["DELAY"] as $arDelay):?>
                                        <div class="open-basket-product-block__one">
                                            <svg class="open-basket-product-block__one-close" onclick="<?=$cartId?>.removeItemFromCart(<?=$arDelay['ID']?>)" width="11px" version="1.1" xmlns="http://www.w3.org/2000/svg" height="11px" viewBox="0 0 64 64" xmlns:xlink="http://www.w3.org/1999/xlink" enable-background="new 0 0 64 64">
                                                <g>
                                                    <path d="M28.941,31.786L0.613,60.114c-0.787,0.787-0.787,2.062,0,2.849c0.393,0.394,0.909,0.59,1.424,0.59   c0.516,0,1.031-0.196,1.424-0.59l28.541-28.541l28.541,28.541c0.394,0.394,0.909,0.59,1.424,0.59c0.515,0,1.031-0.196,1.424-0.59   c0.787-0.787,0.787-2.062,0-2.849L35.064,31.786L63.41,3.438c0.787-0.787,0.787-2.062,0-2.849c-0.787-0.786-2.062-0.786-2.848,0   L32.003,29.15L3.441,0.59c-0.787-0.786-2.061-0.786-2.848,0c-0.787,0.787-0.787,2.062,0,2.849L28.941,31.786z"/>
                                                </g>
                                            </svg>
                                            <div class="open-basket-product-block__item open-basket-product__picture">
                                                <a href="<?=$arDelay["DETAIL_PAGE_URL"]?>">
                                                    <img src="<?=( empty($arDelay["PICTURE_SRC"]) ? "/upload/kit.origami/no_photo_small.svg" : $arDelay["PICTURE_SRC"] )?>" alt="<?=$arDelay["NAME"]?>" title="<?=$arDelay["NAME"]?>">
                                                </a>
                                            </div>
                                            <div class="open-basket-product-block__item open-basket-product__info">
                                                <a href="<?=$arDelay["DETAIL_PAGE_URL"]?>" class="open-basket-product__name">
                                                    <?=$arDelay["NAME"]?>
                                                </a>
                                                <?if(isset($arResult["PROPS"][$arDelay["ID"]])):?>
                                                    <div class="open-basket-product__property">
                                                        <?foreach($arResult["PROPS"][$arDelay["ID"]] as $arProp):?>
                                                            <div class="open-basket-product__property-item">
                                                                <span class="open-basket-product__property-name"><?=$arProp["NAME"]?>:</span>
                                                                <span class="open-basket-product__property-value"><?=$arProp["VALUE"]?></span>
                                                            </div>
                                                        <?endforeach?>
                                                    </div>
                                                <?endif;?>
                                            </div>
                                            <div class="open-basket-product-block__item open-basket-product__price">
                                                <div class="open-basket-product__price-one">
                                                    <div class="open-basket-product__price-one-name"><?=$arDelay["NOTES"]?>:</div>
                                                    <div class="open-basket-product__price-one-value"><?=$arDelay["PRICE_FORMATED"]?></div>
                                                    <?if(isset($arDelay["ECONOM_FORMAT"])):?>
                                                        <div class="open-basket-product__price-one-old-value"><?=$arDelay["FULL_PRICE"]?></div>
                                                        <div class="open-basket-product__price-one-quantity"><?=GetMessage("ORIGAMI_BASKET_PRICE_TO")?> <?=$arDelay['RATIO']?> <?=$arDelay["MEASURE_NAME"]?></div>
                                                        <!-- <div class="open-basket-product__price-one-saving">
                                                            <div class="price-one-saving__name"><?=GetMessage("ORIGAMI_BASKET_ECONOM")?></div>
                                                            <div class="price-one-saving__value"><?=$arDelay["ECONOM_FORMAT"]?></div>
                                                        </div> -->
                                                    <?endif;?>
                                                </div>
                                            </div>
                                            <div class="open-basket-product-block__item open-basket-product__number open-basket-product__number--wish">
                                                <div class="open-basket-product__quantity open-basket-product__quantity--wish">
                                                    <span><?=$arDelay["QUANTITY"]?> <?=$arDelay["MEASURE_NAME"]?></span>
                                                </div>
                                            </div>
                                            <div class="open-basket-product-block__item open-basket-product__total">
                                                <div class="open-basket-product__total-block open-basket-product__total-block--wish">
                                                    <div class="open-basket-product__total-price">
                                                        <div class="open-basket-product__total-name"><?=GetMessage("ORIGAMI_BASKET_SUMM")?></div>
                                                        <div class="open-basket-product__total-value"><?=$arDelay["SUM"]?></div>
                                                        <?if(isset($arDelay["ECONOM_FORMAT"])):?>
                                                            <div class="open-basket-product__total-eco"><?=$arDelay["OLD_PRICE_FORMAT"]?></div>
                                                            <div class="open-basket-product__price-one-saving">
                                                                <div class="price-one-saving__name"><?=GetMessage("ORIGAMI_BASKET_ECONOM")?>:</div>
                                                                <div class="price-one-saving__value"><?=$arDelay["ECONOM_SUM_FORMAT"]?></div>
                                                            </div>
                                                        <?endif;?>
                                                    </div>
                                                    <?if($arResult["SHOW_BASKET"]):?>
                                                    <div class="open-basket-product__wish basket" title="<?=GetMessage("ORIGAMI_BASKET_CART")?>"  onclick="<?=$cartId?>.refreshBuy(<?=$arDelay['ID']?>)">
                                                        <svg class="open-basket-product__wish-svg" width="19"  viewBox="0 0 29 25"  xmlns="http://www.w3.org/2000/svg">
                                                            <path d="M4.40819 22.8193C4.49344 23.6988 5.20721 24.3625 6.06853 24.3625H23.4707C24.332 24.3625 25.0466 23.6988 25.1336 22.8195V22.8194L26.2743 11.0793C26.2743 11.0793 26.2743 11.0793 26.2743 11.0792C26.2882 10.9359 26.2425 10.7914 26.1474 10.6844C26.0525 10.577 25.9179 10.5155 25.777 10.5155H3.76396C3.62214 10.5155 3.4883 10.5771 3.39357 10.6844C3.29845 10.7914 3.25267 10.935 3.26662 11.0792V11.0793L4.40819 22.8193ZM4.40819 22.8193L4.49475 22.8105L4.40819 22.8194V22.8193ZM5.40121 22.7179L4.31455 11.5415H25.2248L24.1381 22.7179C24.1037 23.0717 23.8171 23.3375 23.4707 23.3375H6.06771C5.72148 23.3375 5.43554 23.0718 5.40121 22.7179Z"  stroke-width="0.15"/>
                                                            <path d="M12.2623 1.22172L12.2626 1.22145C12.4745 1.04072 12.7891 1.06747 12.9656 1.2853C13.1433 1.50181 13.1152 1.82561 12.9035 2.007L12.9036 2.00694L12.8477 1.93854L12.9035 2.00707L7.75177 6.4426H7.75175C7.65901 6.52243 7.54508 6.56251 7.43191 6.56251C7.28939 6.56251 7.14724 6.50007 7.04907 6.37875L12.2623 1.22172ZM12.2623 1.22172L7.11055 5.65724C7.11054 5.65725 7.11052 5.65726 7.11051 5.65727L12.2623 1.22172Z"  stroke-width="0.15"/>
                                                            <path d="M21.7875 6.44239L21.7878 6.44269C21.8812 6.52218 21.9949 6.56231 22.1082 6.56231C22.2508 6.56231 22.3927 6.49982 22.4916 6.37889L22.4917 6.37873C22.6685 6.16144 22.6404 5.83874 22.4299 5.65648L22.4297 5.65631L17.2769 1.22066L17.2768 1.22053C17.065 1.03902 16.7516 1.06808 16.5732 1.28379L16.5728 1.28432C16.396 1.50161 16.4241 1.82431 16.6347 2.00657L16.6348 2.00674L21.7875 6.44239ZM22.3738 5.72472C22.5478 5.87539 22.571 6.14205 22.4249 6.3215C22.3432 6.42139 22.2261 6.47303 22.1082 6.47303C22.015 6.47303 21.9209 6.44001 21.8434 6.37399L22.3738 5.72472Z"  stroke-width="0.15"/>
                                                            <path d="M2.58283 11.5414H26.9565C27.7899 11.5414 28.4664 10.8462 28.4664 9.99192V7.28482C28.4664 6.43049 27.789 5.73532 26.9565 5.73532H2.58283C1.75037 5.73532 1.073 6.43049 1.073 7.28482V9.99192C1.073 10.8462 1.75037 11.5414 2.58283 11.5414ZM2.07183 7.28398C2.07183 6.99468 2.30094 6.75955 2.58283 6.75955H26.9565C27.2384 6.75955 27.4675 6.99468 27.4675 7.28398V9.99107C27.4675 10.2803 27.2376 10.5155 26.9565 10.5155H2.58283C2.30094 10.5155 2.07183 10.2804 2.07183 9.99107V7.28398Z"  stroke-width="0.15"/>
                                                            <path d="M8.29443 20.3912C8.29443 20.674 8.51815 20.9037 8.79385 20.9037C9.07038 20.9037 9.29326 20.6749 9.29326 20.3912V14.254C9.29326 13.9711 9.06954 13.7414 8.79385 13.7414C8.51815 13.7414 8.29443 13.9711 8.29443 14.254V20.3912Z"  stroke-width="0.15"/>
                                                            <path d="M12.2783 20.3912C12.2783 20.674 12.5021 20.9037 12.7778 20.9037C13.0544 20.9037 13.2772 20.6749 13.2772 20.3912V14.254C13.2772 13.9711 13.0534 13.7414 12.7778 13.7414C12.5021 13.7414 12.2783 13.9711 12.2783 14.254V20.3912Z"  stroke-width="0.15"/>
                                                            <path d="M16.2622 20.3912C16.2622 20.674 16.4859 20.9037 16.7616 20.9037C17.0372 20.9037 17.261 20.675 17.261 20.3912V14.254C17.261 13.9711 17.0373 13.7414 16.7616 13.7414C16.4859 13.7414 16.2622 13.9711 16.2622 14.254V20.3912Z"  stroke-width="0.15"/>
                                                            <path d="M20.2461 20.3912C20.2461 20.674 20.4697 20.9037 20.7455 20.9037C21.0211 20.9037 21.2449 20.675 21.2449 20.3912V14.254C21.2449 13.9711 21.0212 13.7414 20.7455 13.7414C20.4697 13.7414 20.2461 13.9711 20.2461 14.254V20.3912Z"  stroke-width="0.15"/>
                                                        </svg>
                                                    </div>
                                                    <?endif;?>
                                                </div>
                                            </div>
                                        </div>
                                    <?endforeach;?>
                                </div>
                                <?endif;?>
                            </div>
                            <?endif;?>
                        </div>
                        <?if($arResult["NUM_PRODUCTS"]>0 && $arResult["SHOW_BASKET"]):?>
                            <div class="open-basket-product-btn">
                                <a href="#" class="open-basket-product-btn__more-buy">
                                    <svg  width="10" height="10">
                                        <use xlink:href="/local/templates/kit_origami/assets/img/sprite.svg#icon_dropdown_right"></use>
                                    </svg>
                                    <?=GetMessage('ORIGAMI_BASKET_RETURN_BUY')?></a>
                                <a href="<?=$arParams["PATH_TO_BASKET"]?>" class="open-basket-product-btn__basket">
                                    <svg class="open-basket-product-btn__basket-svg" width="29"  viewBox="0 0 29 25"  xmlns="http://www.w3.org/2000/svg">
                                        <path d="M4.40819 22.8193C4.49344 23.6988 5.20721 24.3625 6.06853 24.3625H23.4707C24.332 24.3625 25.0466 23.6988 25.1336 22.8195V22.8194L26.2743 11.0793C26.2743 11.0793 26.2743 11.0793 26.2743 11.0792C26.2882 10.9359 26.2425 10.7914 26.1474 10.6844C26.0525 10.577 25.9179 10.5155 25.777 10.5155H3.76396C3.62214 10.5155 3.4883 10.5771 3.39357 10.6844C3.29845 10.7914 3.25267 10.935 3.26662 11.0792V11.0793L4.40819 22.8193ZM4.40819 22.8193L4.49475 22.8105L4.40819 22.8194V22.8193ZM5.40121 22.7179L4.31455 11.5415H25.2248L24.1381 22.7179C24.1037 23.0717 23.8171 23.3375 23.4707 23.3375H6.06771C5.72148 23.3375 5.43554 23.0718 5.40121 22.7179Z"  stroke-width="0.15"/>
                                        <path d="M12.2623 1.22172L12.2626 1.22145C12.4745 1.04072 12.7891 1.06747 12.9656 1.2853C13.1433 1.50181 13.1152 1.82561 12.9035 2.007L12.9036 2.00694L12.8477 1.93854L12.9035 2.00707L7.75177 6.4426H7.75175C7.65901 6.52243 7.54508 6.56251 7.43191 6.56251C7.28939 6.56251 7.14724 6.50007 7.04907 6.37875L12.2623 1.22172ZM12.2623 1.22172L7.11055 5.65724C7.11054 5.65725 7.11052 5.65726 7.11051 5.65727L12.2623 1.22172Z"  stroke-width="0.15"/>
                                        <path d="M21.7875 6.44239L21.7878 6.44269C21.8812 6.52218 21.9949 6.56231 22.1082 6.56231C22.2508 6.56231 22.3927 6.49982 22.4916 6.37889L22.4917 6.37873C22.6685 6.16144 22.6404 5.83874 22.4299 5.65648L22.4297 5.65631L17.2769 1.22066L17.2768 1.22053C17.065 1.03902 16.7516 1.06808 16.5732 1.28379L16.5728 1.28432C16.396 1.50161 16.4241 1.82431 16.6347 2.00657L16.6348 2.00674L21.7875 6.44239ZM22.3738 5.72472C22.5478 5.87539 22.571 6.14205 22.4249 6.3215C22.3432 6.42139 22.2261 6.47303 22.1082 6.47303C22.015 6.47303 21.9209 6.44001 21.8434 6.37399L22.3738 5.72472Z"  stroke-width="0.15"/>
                                        <path d="M2.58283 11.5414H26.9565C27.7899 11.5414 28.4664 10.8462 28.4664 9.99192V7.28482C28.4664 6.43049 27.789 5.73532 26.9565 5.73532H2.58283C1.75037 5.73532 1.073 6.43049 1.073 7.28482V9.99192C1.073 10.8462 1.75037 11.5414 2.58283 11.5414ZM2.07183 7.28398C2.07183 6.99468 2.30094 6.75955 2.58283 6.75955H26.9565C27.2384 6.75955 27.4675 6.99468 27.4675 7.28398V9.99107C27.4675 10.2803 27.2376 10.5155 26.9565 10.5155H2.58283C2.30094 10.5155 2.07183 10.2804 2.07183 9.99107V7.28398Z"  stroke-width="0.15"/>
                                        <path d="M8.29443 20.3912C8.29443 20.674 8.51815 20.9037 8.79385 20.9037C9.07038 20.9037 9.29326 20.6749 9.29326 20.3912V14.254C9.29326 13.9711 9.06954 13.7414 8.79385 13.7414C8.51815 13.7414 8.29443 13.9711 8.29443 14.254V20.3912Z"  stroke-width="0.15"/>
                                        <path d="M12.2783 20.3912C12.2783 20.674 12.5021 20.9037 12.7778 20.9037C13.0544 20.9037 13.2772 20.6749 13.2772 20.3912V14.254C13.2772 13.9711 13.0534 13.7414 12.7778 13.7414C12.5021 13.7414 12.2783 13.9711 12.2783 14.254V20.3912Z"  stroke-width="0.15"/>
                                        <path d="M16.2622 20.3912C16.2622 20.674 16.4859 20.9037 16.7616 20.9037C17.0372 20.9037 17.261 20.675 17.261 20.3912V14.254C17.261 13.9711 17.0373 13.7414 16.7616 13.7414C16.4859 13.7414 16.2622 13.9711 16.2622 14.254V20.3912Z"  stroke-width="0.15"/>
                                        <path d="M20.2461 20.3912C20.2461 20.674 20.4697 20.9037 20.7455 20.9037C21.0211 20.9037 21.2449 20.675 21.2449 20.3912V14.254C21.2449 13.9711 21.0212 13.7414 20.7455 13.7414C20.4697 13.7414 20.2461 13.9711 20.2461 14.254V20.3912Z"  stroke-width="0.15"/>
                                    </svg>
                                    <?=GetMessage("ORIGAMI_BASKET_TO_CART")?>
                                </a>
                                <?if($arResult["NUM_PRODUCTS"]>0):?>
                                    <a href="<?=$arParams["PATH_TO_ORDER"]?>" class="open-basket-product-btn__order main_btn">
                                        <svg class="open-basket-product-btn__order-svg" width="23" height="23" viewBox="0 0 23 23" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M21.6471 20.9156V17.1504H22.0387C22.2087 17.1504 22.359 17.0931 22.4671 16.9843C22.575 16.8756 22.6309 16.7255 22.6309 16.5582V10.4713C22.6309 10.1124 22.3342 9.87461 22.0387 9.87461H21.6021V6.2082C21.6021 5.25206 20.8477 4.49297 19.9318 4.49297H19.6346V2.08438C19.6346 1.13596 18.9324 0.369141 18.0586 0.369141H18.0586H18.0586H18.0585H18.0585H18.0585H18.0585H18.0584H18.0584H18.0584H18.0584H18.0584H18.0583H18.0583H18.0583H18.0583H18.0583H18.0582H18.0582H18.0582H18.0581H18.0581H18.0581H18.058H18.058H18.058H18.0579H18.0579H18.0579H18.0578H18.0578H18.0578H18.0577H18.0577H18.0576H18.0576H18.0575H18.0575H18.0574H18.0574H18.0573H18.0573H18.0572H18.0572H18.0571H18.0571H18.057H18.057H18.0569H18.0569H18.0568H18.0567H18.0567H18.0566H18.0566H18.0565H18.0564H18.0564H18.0563H18.0562H18.0562H18.0561H18.056H18.056H18.0559H18.0558H18.0558H18.0557H18.0556H18.0555H18.0555H18.0554H18.0553H18.0552H18.0551H18.0551H18.055H18.0549H18.0548H18.0547H18.0546H18.0546H18.0545H18.0544H18.0543H18.0542H18.0541H18.054H18.0539H18.0538H18.0538H18.0537H18.0536H18.0535H18.0534H18.0533H18.0532H18.0531H18.053H18.0529H18.0528H18.0527H18.0526H18.0525H18.0524H18.0523H18.0521H18.052H18.0519H18.0518H18.0517H18.0516H18.0515H18.0514H18.0513H18.0511H18.051H18.0509H18.0508H18.0507H18.0506H18.0504H18.0503H18.0502H18.0501H18.05H18.0498H18.0497H18.0496H18.0495H18.0493H18.0492H18.0491H18.049H18.0488H18.0487H18.0486H18.0484H18.0483H18.0482H18.048H18.0479H18.0478H18.0476H18.0475H18.0474H18.0472H18.0471H18.0469H18.0468H18.0467H18.0465H18.0464H18.0462H18.0461H18.0459H18.0458H18.0457H18.0455H18.0454H18.0452H18.0451H18.0449H18.0448H18.0446H18.0445H18.0443H18.0442H18.044H18.0438H18.0437H18.0435H18.0434H18.0432H18.0431H18.0429H18.0427H18.0426H18.0424H18.0423H18.0421H18.0419H18.0418H18.0416H18.0414H18.0413H18.0411H18.0409H18.0408H18.0406H18.0404H18.0403H18.0401H18.0399H18.0398H18.0396H18.0394H18.0392H18.0391H18.0389H18.0387H18.0385H18.0384H18.0382H18.038H18.0378H18.0376H18.0375H18.0373H18.0371H18.0369H18.0367H18.0366H18.0364H18.0362H18.036H18.0358H18.0356H18.0354H18.0353H18.0351H18.0349H18.0347H18.0345H18.0343H18.0341H18.0339H18.0337H18.0335H18.0333H18.0331H18.033H18.0328H18.0326H18.0324H18.0322H18.032H18.0318H18.0316H18.0314H18.0312H18.031H18.0308H18.0306H18.0304H18.0302H18.03H18.0298H18.0295H18.0293H18.0291H18.0289H18.0287H18.0285H18.0283H18.0281H18.0279H18.0277H18.0275H18.0273H18.027H18.0268H18.0266H18.0264H18.0262H18.026H18.0258H18.0256H18.0253H18.0251H18.0249H18.0247H18.0245H18.0242H18.024H18.0238H18.0236H18.0234H18.0231H18.0229H18.0227H18.0225H18.0223H18.022H18.0218H18.0216H18.0214H18.0211H18.0209H18.0207H18.0205H18.0202H18.02H18.0198H18.0195H18.0193H18.0191H18.0189H18.0186H18.0184H18.0182H18.0179H18.0177H18.0175H18.0172H18.017H18.0168H18.0165H18.0163H18.0161H18.0158H18.0156H18.0153H18.0151H18.0149H18.0146H18.0144H18.0142H18.0139H18.0137H18.0134H18.0132H18.013H18.0127H18.0125H18.0122H18.012H18.0117H18.0115H18.0112H18.011H18.0108H18.0105H18.0103H18.01H18.0098H18.0095H18.0093H18.009H18.0088H18.0085H18.0083H18.008H18.0078H18.0075H18.0073H18.007H18.0068H18.0065H18.0063H18.006H18.0058H18.0055H18.0053H18.005H18.0048H18.0045H18.0042H18.004H18.0037H18.0035H18.0032H18.003H18.0027H18.0024H18.0022H18.0019H18.0017H18.0014H18.0011H18.0009H18.0006H18.0004H18.0001H17.9998H17.9996H17.9993H17.9991H17.9988H17.9985H17.9983H17.998H17.9977H17.9975H17.9972H17.9969H17.9967H17.9964H17.9962H17.9959H17.9956H17.9953H17.9951H17.9948H17.9945H17.9943H17.994H17.9937H17.9935H17.9932H17.9929H17.9927H17.9924H17.9921H17.9919H17.9916H17.9913H17.991H17.9908H17.9905H17.9902H17.99H17.9897H17.9894H17.9891H17.9889H17.9886H17.9883H17.988H17.9878H17.9875H17.9872H17.9869H17.9867H17.9864H17.9861H17.9858H17.9856H17.9853H17.985H17.9847H17.9844H17.9842H17.9839H17.9836H17.9833H17.9831H17.9828H17.9825H17.9822H17.9819H17.9817H17.9814H17.9811H17.9808H17.9805H17.9803H17.98H17.9797H17.9794H17.9791H17.9788H17.9786H17.9783H17.978H17.9777H17.9774H17.9772H17.9769H17.9766H17.9763H17.976H17.9757H17.9755H17.9752H17.9749H17.9746H17.9743H17.974H17.9737H17.9735H17.9732H17.9729H17.9726H17.9723H17.972H17.9717H17.9715H17.9712H17.9709H17.9706H17.9703H17.97H17.9697H17.9694H17.9692H17.9689H17.9686H17.9683H17.968H17.9677H17.9674H17.9671H17.9669H17.9666H17.9663H17.966H17.9657H17.9654H17.9651H17.9648H17.9645H17.9643H17.9484L17.9331 0.373079L1.92029 4.49079C1.91523 4.49174 1.91102 4.4928 1.90815 4.49358L1.90566 4.49432C1.05433 4.56813 0.369141 5.29919 0.369141 6.2082V20.9156C0.369141 21.8718 1.12356 22.6309 2.03945 22.6309H19.9318C20.8903 22.6309 21.6471 21.8741 21.6471 20.9156ZM6.67173 4.48848L18.1495 1.51246C18.3054 1.55954 18.4906 1.77201 18.4906 2.08438V4.48848H6.67173ZM20.4627 20.9156C20.4627 21.2304 20.2251 21.4914 19.9318 21.4914H2.03945C1.74614 21.4914 1.50859 21.2304 1.50859 20.9156V6.2082C1.50859 5.89339 1.74614 5.63242 2.03945 5.63242H19.8824C20.1757 5.63242 20.4133 5.89339 20.4133 6.2082V9.87461H15.9023C13.8657 9.87461 12.2645 11.4759 12.2645 13.5125C12.2645 15.5502 13.9117 17.1504 15.9023 17.1504H20.4627V20.9156ZM21.442 16.0109H15.9023C14.5655 16.0109 13.4533 14.8946 13.4533 13.5619C13.4533 12.2294 14.5698 11.1129 15.9023 11.1129H21.442V16.0109Z" stroke-width="0.25"/>
                                            <path d="M15.2607 13.6068C15.2607 13.9673 15.5591 14.199 15.8529 14.199H16.2752C16.5699 14.199 16.8674 13.9621 16.8674 13.6068C16.8674 13.2463 16.5691 13.0146 16.2752 13.0146H15.8574C15.6875 13.0146 15.5364 13.0718 15.4274 13.1801C15.3183 13.2885 15.2607 13.4386 15.2607 13.6068Z" stroke-width="0.25"/>
                                        </svg>
                                        <?=GetMessage("ORIGAMI_BASKET_TO_ORDER")?>
                                    </a>
                                    <?
                                    $props = ($arResult['PROPS'])?array_keys(reset($arResult['PROPS'])):[];
                                    ?>
                                    <? if(\Bitrix\Main\Loader::includeModule('kit.orderphone')): ?>
                                        <button
                                            class="basket-line-btn-oc"
                                            id="order_oc_top"
                                            data-site_id="<?=SITE_ID?>"
                                            data-site_dir="<?=SITE_DIR?>"
                                            data-props="<?=base64_encode(serialize($props))?>">
                                            <?=GetMessage('ORIGAMI_BASKET_OC')?>
                                        </button>
                                    <? endif;?>
                                <?endif;?>


                                <?if($arResult["SHOW_BASKET"]):?>
                                <div class="open-basket-product-btn__total-price">
                                    <div class="open-basket-product-btn__total-price-wrapper">
                                        <div class="open-basket-product-btn__total-price-n-block">
                                            <div class="open-basket-product-btn__total-price-n-name">
                                                <?=GetMessage("ORIGAMI_BASKET_ITOGO")?>
                                            </div>
                                        </div>
                                        <div class="open-basket-product-btn__total-price-value">
                                            <?=$arResult["TOTAL_PRICE_VALUE"]?> <span class="total-price-value__currency"><?=$arResult["CURRENCY_FORMAT_STRING"]?></span>
                                        </div>
                                    </div>
                                    <?if($arResult["ECONOM_ITOGO"]>0):?>
                                        <div class="open-basket-product-btn__total-eco-wrapper">
                                            <div class="open-basket-product-btn__total-eco-title">
                                                <?=GetMessage("ORIGAMI_BASKET_ECONOM")?>
                                            </div>
                                            <div class="open-basket-product-btn__total-eco-summ">
                                                <?=$arResult["ECONOM_ITOGO_FORMAT"]?>
                                            </div>
                                            <div class="open-basket-product-btn__total-eco-old-price">
                                                <?=$arResult["OLD_PRICE_ITOGO_FORMAT"]?>
                                            </div>
                                        </div>
                                    <?endif;?>

                                </div>
                                <?endif;?>
                            </div>
                        <?endif;?>
                    </div>
                    <!--  ///////// END NEW  PAGE-PROOFS ///////////////-->
                </div>
            </div>
        </div>
    </div>

    <script>
        //not work in ready
        $('#order_oc_top').on('click',function(){
            let siteId = $(this).data('site_id');
            let siteDir = $(this).data('site_dir');
            let props = $(this).data('props');
            $.ajax({
                url: siteDir + 'include/ajax/oc.php',
                type: 'POST',
                data:{'site_id':siteId,'basketData':props},
                success: function(html)
                {
                    showModal(html);
                }
            });
        });

        <?if(isset($arResult["arBasketID"])):?>
        arBasketID = <?=json_encode($arResult["arBasketID"])?>;
        <?else:?>
        arBasketID = [];
        <?endif;?>
        <?if(isset($arResult["arDelayID"])):?>
        arDelayID = <?=json_encode($arResult["arDelayID"])?>;
        <?else:?>
        arDelayID = [];
        <?endif;?>
        <?if(isset($arResult["arCompareID"])):?>
        arCompareID = <?=json_encode($arResult["arCompareID"])?>;
        <?else:?>
        arCompareID = [];
        <?endif;?>
        BX.onCustomEvent('OnBasketChangeAfter');
    </script>

    <?
}
require(realpath(dirname(__FILE__)).'/template_mobile_top.php');
