<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use \Bitrix\Main\Localization\Loc;
use Sotbit\Origami\Helper\Config;
use \Sotbit\Origami\Helper\Prop;

$labelProps = unserialize(Config::get('LABEL_PROPS'));
$arParams['LABEL_PROP'] = $labelProps;

if(!$arParams['LABEL_PROP'])
{
    $arParams['LABEL_PROP'] = [];
}

if ($haveOffers)
{
	$showDisplayProps = !empty($item['DISPLAY_PROPERTIES']);
	$showProductProps = $arParams['PRODUCT_DISPLAY_MODE'] === 'Y' && $item['OFFERS_PROPS_DISPLAY'];
	$showPropsBlock = $showDisplayProps || $showProductProps;
	$showSkuBlock = $arParams['PRODUCT_DISPLAY_MODE'] === 'Y' && !empty($item['OFFERS_PROP']);
}
else
{
	$showDisplayProps = !empty($item['DISPLAY_PROPERTIES']);
	$showProductProps = $arParams['ADD_PROPERTIES_TO_BASKET'] === 'Y' && !empty($item['PRODUCT_PROPERTIES']);
	$showPropsBlock = $showDisplayProps || $showProductProps;
	$showSkuBlock = false;
}

?>

<?
$arPromotions =  CCatalogDiscount::GetDiscount($item['ID'], $item['IBLOCK_ID']);
$i = 1;
$dbProductDiscounts = array();
foreach ($arPromotions as $itemDiscount) {
    $dbProductDiscounts[$i] = $itemDiscount;
    $i++;
}
$blockID = randString(8);
?>
<div class="product_card__inner product_card__inner--five <?=( $arParams['MOBILE_VIEW_MINIMAL'] == 'Y' ? "product_card__inner--five-min" : "" )?>">
    <div class="product_card__inner-wrapper">
        <div class="product-card-inner__stickers" <?if($dbProductDiscounts):?> data-timer="timerID_<?=$blockID?>" <?endif;?> >
            <?
            $frame = $this->createFrame()->begin();
            if($item['PROMOTION'])
            {
                ?>
                <span class="product-card-inner__sticker"><?=Loc::getMessage('PROMOTION')?></span>
                <?
            }
            if($arParams['SHOW_DISCOUNT_PERCENT'] === 'Y' && $price['PERCENT'])
            {
                ?>
                <span class="product-card-inner__sticker" id="<?=$itemIds['DSC_PERC']?>">-<?=$price['PERCENT']?>%</span>
                <?
            }
            $frame->end();
            if($item['PROPERTIES'] && $arParams['LABEL_PROP'])
            {
                foreach($arParams['LABEL_PROP'] as $label)
                {
                    if(Prop::checkPropListYes($item['PROPERTIES'][$label]))
                    {
                        $color = '#00b02a';
                        if($item['PROPERTIES'][$label]['HINT'])
                        {
                            $color = $item['PROPERTIES'][$label]['HINT'];
                        }
                        ?>
                        <span class="product-card-inner__sticker" style="background:<?=$color?>">
                            <?=$item['PROPERTIES'][$label]['NAME']?>
                        </span>
                        <?
                    }
                }
            }
            ?>
        </div>

        <div class="product-card-inner__icons">
            <?if($haveOffers || $actualItem['CAN_BUY']):?>
                <?if($arParams["SHOW_DELAY"] && (!$haveOffers || $arParams['PRODUCT_DISPLAY_MODE'] === 'Y')):?>
                    <span class="product-card-inner__icon" data-entity="wish" id="<?=$itemIds['WISH_LINK']?>" <?if($haveOffers && $actualItem['CAN_BUY']):?>style="display: none;"<?endif;?>>
                <svg width="16" height="16">
                    <use xlink:href="/local/templates/sotbit_origami/assets/img/sprite.svg#icon_favourite"></use>
                </svg>
            </span>
                <?endif;?>
            <?endif;?>
            <?
            if($arParams["SHOW_COMPARE"] && $arParams['DISPLAY_COMPARE'] && (!$haveOffers || $arParams['PRODUCT_DISPLAY_MODE'] === 'Y'))
            {
            ?>
                <span class="product-card-inner__icon" data-entity="compare-checkbox" id="<?=$itemIds['COMPARE_LINK']?>" >
                    <svg width="16" height="16">
                        <use xlink:href="/local/templates/sotbit_origami/assets/img/sprite.svg#icon_compare"></use>
                    </svg>
                </span>
                <?
            }
            ?>
        </div>
        <?
        if($morePhoto[0]['SRC'])
        {

            ?>
            <div class="product-card-inner__img-wrapper">
                <?if(Config::get('QUICK_VIEW') == 'Y'):?>
                    <span class="product-card-inner__img-view" onclick="quickView('<?=$item['DETAIL_PAGE_URL']?>');return false;">
                        <?=Loc::getMessage('QUICK_PREVIEW')?>
                    </span>
                <?endif;?>
                <a
                        href="<?=$item['DETAIL_PAGE_URL']?>"
                        onclick=""
                        class="product-card-inner__img-link <?=$arParams["HOVER_EFFECT"]?>"
                        data-entity="image-wrapper"
                        title="<?=$productTitle?>"
                >
                    <img
                            <?=$strLazyLoad?>
                            alt="<?=$imgAlt?>"
                            title="<?=$imgTitle?>"
                            id="<?=$itemIds['PICT']?>"
                    >
                    <?if($arResult['LAZY_LOAD']):?>
                        <span class="loader-lazy"></span> <!--LOADER_LAZY-->
                    <?endif;?>
                </a>
            </div>
            <?
        }
        ?>
        <div class="product-card-inner__title">
            <a href="<?=$item['DETAIL_PAGE_URL']?>" onclick="" title="<?=$productTitle?>" class="product-card-inner__title-link">
                <?=$item['NAME']?>
            </a>
        </div>
        <?
        if ($arParams['USE_VOTE_RATING'] === 'Y')
        {
        ?>
        <div class="product-card-inner__rating-block">
            <?php
                $APPLICATION->IncludeComponent(
                    'bitrix:iblock.vote',
                    'origami_stars',
                    [
                        'CUSTOM_SITE_ID' => null,
                        'IBLOCK_TYPE' => $arParams['IBLOCK_TYPE'],
                        'IBLOCK_ID' => $arParams['IBLOCK_ID'],
                        'ELEMENT_ID' => $item['ID'],
                        'ELEMENT_CODE' => '',
                        'MAX_VOTE' => '5',
                        'VOTE_NAMES' => ['1', '2', '3', '4', '5'],
                        'SET_STATUS_404' => 'N',
                        'DISPLAY_AS_RATING' => 'vote_avg',
                        'CACHE_TYPE' => $arParams['CACHE_TYPE'],
                        'CACHE_TIME' => $arParams['CACHE_TIME'],
                        'READ_ONLY' => 'Y'
                    ],
                    $component,
                    ['HIDE_ICONS' => 'Y']
                );
            ?>
        </div>
        <?
        }
        ?>
        <div class="product-card-inner__info">
            <?
            if ($arParams['SHOW_MAX_QUANTITY'] !== 'N')
            {
                if ($haveOffers)
                {
                    if ($arParams['PRODUCT_DISPLAY_MODE'] === 'Y')
                    {
                        ?>
                        <div
                            id="<?=$itemIds['QUANTITY_LIMIT']?>"
                            style="display: none;"
                            data-entity="quantity-limit-block">
                                <span data-entity="quantity-limit-value"></span>
                        </div>
                        <?
                    }
                }
                else
                {
                    if (
                        $measureRatio
                        && (float)$actualItem['CATALOG_QUANTITY'] > 0
                        && $actualItem['CATALOG_QUANTITY_TRACE'] === 'Y'
                        && $actualItem['CATALOG_CAN_BUY_ZERO'] === 'N'
                    )
                    {
                        if ($arParams['SHOW_MAX_QUANTITY'] === 'M')
                        {
                            if ((float)$actualItem['CATALOG_QUANTITY'] / $measureRatio >= $arParams['RELATIVE_QUANTITY_FACTOR'])
                            {
                                ?>
                                <span class="product-card-inner__quantity product-card-inner__quantity--lot">
                                    <?=$arParams['MESS_RELATIVE_QUANTITY_MANY']?>
                                </span>
                                <?
                            }
                            else
                            {
                                ?>
                                <span class="product-card-inner__quantity product-card-inner__quantity--few">
                                    <?=$arParams['MESS_RELATIVE_QUANTITY_FEW']?>
                                </span>
                                <?
                            }
                        }
                        else
                        {
                            if($actualItem['CATALOG_QUANTITY'] > 0){
                                ?>
                                <span class="product-card-inner__quantity product-card-inner__quantity--lot">
                                    <?php
                                    echo $actualItem['CATALOG_QUANTITY'].' '.$actualItem['ITEM_MEASURE']['TITLE'];
                                    ?>
                                </span>
                                <?
                            }
                            else{
                                ?>
                                <span class="product-card-inner__quantity product-card-inner__quantity--none">
                                    <?php
                                    echo $actualItem['CATALOG_QUANTITY'].' '.$actualItem['ITEM_MEASURE']['TITLE'];
                                    ?>
                                </span>
                                <?
                            }
                        }
                    }
                }
            }
            if($item['PROPERTIES'][Config::get('ARTICUL')]['VALUE']):
            ?>
                <p class="product-card-inner__vendor-code">
                    <?=$item['PROPERTIES'][Config::get('ARTICUL')]['NAME']?>:
                    <span class="product-card-inner__vendor-code-value">
                        <?=$item['PROPERTIES'][Config::get('ARTICUL')]['VALUE']?>
                    </span>
                </p>
            <?
            endif;
            ?>

        </div>

        <div class="product-card-inner__price">
            <?
            $frame = $this->createFrame()->begin();
            if($arParams["FILL_ITEM_ALL_PRICES"] != "Y"):?>
                <div>
                    <div class="product-card-inner__action-price">
                        <?
                        if($arParams['SHOW_OLD_PRICE'] === 'Y' && (isset($item['CHECK_DISCOUNT']) || $price['DISCOUNT']))
                        {
                            ?>
                            <p class="product-card-inner__old-price" id="<?=$itemIds['PRICE_OLD']?>">
                                <?=$price['PRINT_BASE_PRICE']?>
                            </p>
                            <?
                        }
                        ?>
                        <p class="product-card-inner__new-price" id="<?=$itemIds['PRICE']?>">
                            <?=$price['PRINT_PRICE']?>
                        </p>
                    </div>
                    <?if($arParams['SHOW_OLD_PRICE'] === 'Y' && (isset($item['CHECK_DISCOUNT']) || $price['DISCOUNT'])):?>
                        <div class="product-card-inner__saving" id="<?=$itemIds['PRICE_SAVE']?>">
                            <p class="product-card-inner__saving-title"><?=Loc::getMessage('TO_SAVE')?>
                                <span class="product-card-inner__saving-value">
                                <?=$price['PRINT_DISCOUNT']?>
                            </span>
                            </p>
                        </div>
                    <?endif;?>
                </div>
            <?elseif(isset($item['ALL_PRICES_NAMES'])):?>
                <div class="product-card-inner__option-price" id="<?=$itemIds['ALL_PRICES']?>" >
                    <?
                    {
                        foreach($item['ALL_PRICES_NAMES'] as $id => $idName)
                        {
                            $allPrice = isset($actualItem['ITEM_ALL_PRICES'][$actualItem['ITEM_PRICE_SELECTED']]["PRICES"][$id]) ? $actualItem['ITEM_ALL_PRICES'][$actualItem['ITEM_PRICE_SELECTED']]["PRICES"][$id] : array();
                            ?>
                            <div class="product-card-inner__option-price-retail" data-id="<?=$id?>" <?if(empty($allPrice)):?>style="display:none"<?endif;?>>
                                <p class="product-card-inner__option-price-title">
                                    <?=$item['ALL_PRICES_NAMES'][$id]?>:
                                </p>
                                <?
                                if($arParams['SHOW_OLD_PRICE'] === 'Y' && (isset($item['CHECK_DISCOUNT']) || $allPrice['RATIO_DISCOUNT']))
                                {
                                    ?>
                                    <p class="product-card-inner__old-price" data-oldprice-id="<?=$id?>" <?if(!$allPrice['RATIO_DISCOUNT']):?>style="display:none"<?endif;?>>
                                        <?=$price['PRINT_BASE_PRICE']?>
                                    </p>
                                    <?
                                }?>
                                <p class="product-card-inner__option-price-value" data-price-id="<?=$id?>">
                                    <?=$allPrice['PRINT_PRICE']?>
                                </p>
                                <?if($arParams['SHOW_OLD_PRICE'] === 'Y' && (isset($item['CHECK_DISCOUNT']) || $allPrice['RATIO_DISCOUNT'])):?>
                                    <div class="product-card-inner__saving" data-discount-id="<?=$id?>" <?if(!$allPrice['RATIO_DISCOUNT']):?>style="display:none"<?endif;?>>
                                        <p class="product-card-inner__saving-title"><?=Loc::getMessage('TO_SAVE')?>
                                            <span class="product-card-inner__saving-value">
                                    <?=$allPrice['PRINT_DISCOUNT']?>
                                </span>
                                        </p>
                                    </div>
                                <?endif;?>
                            </div>
                            <?
                        }
                    }
                    ?>
                </div>
            <?endif;
            $frame->end();
            ?>
        </div>

        <form method="post" class="product-card-inner__form">
            <div class="product-card-inner__buttons-block product-mode-N" data-entity="buttons-block">
                <div class="product-card-inner__buttom-more" title="<?=Loc::getMessage('ITEM_MORE')?> <?=$item["NAME"]?>">
                    <a class="product-card-inner__buttom-more-btn" href="<?=$item['DETAIL_PAGE_URL']?>">
                        <?=Loc::getMessage('ITEM_MORE')?>
                    </a>
                </div>
            </div>
        </form>
    </div>
    <?
    if (Config::get('TIMER_PROMOTIONS') == 'Y') {
        if ($dbProductDiscounts) {
            $APPLICATION->IncludeComponent(
                "sotbit:origami.timer",
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
    <script>
        if(timerFive) {
            clearTimeout(timerFive);
            var timerFive = setTimeout(window.calcHeight, 300);
        } else {
            var timerFive = setTimeout(window.calcHeight, 300);
        }
    </script>
</div>
