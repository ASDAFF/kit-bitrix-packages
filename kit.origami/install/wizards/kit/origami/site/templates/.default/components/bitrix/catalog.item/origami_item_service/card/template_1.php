<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();
/**
 * Copyright (c) 27/8/2020 Created By/Edited By ASDAFF asdaff.asad@yandex.ru
 */

use \Bitrix\Main\Localization\Loc;
use Kit\Origami\Helper\Config;
use \Kit\Origami\Helper\Prop;

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

<div class="service-detail-item__image-wrapper" >
        <img
            <?=$strLazyLoad?>
            alt="<?=$imgAlt?>"
            id="<?=$itemIds['PICT']?>"
            title="<?=$imgTitle?>"
            data-entity="image-wrapper"
        >
    <?if($arResult['LAZY_LOAD']):?>
        <span class="loader-lazy"></span> <!--LOADER_LAZY-->
    <?endif;?>
</div>
<div class="service-detail-item__content">
    <a class="service-detail-item__title" href="<?=$item['DETAIL_PAGE_URL']?>"  title="<?=$productTitle?>">
        <span><?=$item['NAME']?></span>
    </a>
    <div class="service-detail-item__prices">
        <?if($arParams["FILL_ITEM_ALL_PRICES"] != "Y"):?>
            <span class="service-detail-item__prices-price" id="<?=$itemIds['PRICE']?>"><?=$price['PRINT_PRICE']?></span>
            <span class="service-detail-item__prices-price"><?='/' . $actualItem['ITEM_MEASURE']['TITLE']?></span>
            <?if($arParams['SHOW_OLD_PRICE'] === 'Y' && (isset($item['CHECK_DISCOUNT']) || $price['DISCOUNT'])):?>
                <span class="service-detail-item__prices-discount" id="<?=$itemIds['PRICE_OLD']?>"><?=$price['PRINT_BASE_PRICE']?></span>
            <?endif?>
        <?endif;?>
    </div>
    <?if($arParams['SHOW_OLD_PRICE'] === 'Y' && (isset($item['CHECK_DISCOUNT']) || $price['DISCOUNT'])):?>
        <div class="service-detail-item__economy">
            <span class="service-detail-item__economy-title"><?=Loc::getMessage('TO_SAVE')?></span>
            <span class="service-detail-item__economy-amount" id="<?=$itemIds['PRICE_SAVE']?>"><?=$price['PRINT_DISCOUNT']?></span>
        </div>
    <?endif;?>
    <div class="service-detail-item__content-hidden-wrapper">
        <div class="service-detail-item__content-hidden" data-entity="buttons-block">
            <div class="service-detail-item__content-hidden-form">
                <?php if($arParams['USE_PRODUCT_QUANTITY']): ?>
                    <div class="service-detail-item__content-hidden-counter" data-entity="quantity-block">
                        <button class="service-detail-item__content-hidden-counter-btn" type="button" id="<?=$itemIds['QUANTITY_DOWN']?>"> &minus;</button>
                        <input class="service-detail-item__content-hidden-counter-input" type="text"
                               id="<?=$itemIds['QUANTITY']?>" value="<?=$measureRatio?>">
                        <button class="service-detail-item__content-hidden-counter-btn" id="<?=$itemIds['QUANTITY_UP']?>" type="button"> +</button>
                    </div>
                <?endif;?>
                <span id="<?=$itemIds['BASKET_ACTIONS']?>"  id="<?=$itemIds['BASKET_ACTIONS']?>" <?=($actualItem['CAN_BUY'] ? '' : 'style="display: none;"')?>>
                    <button id="<?=$itemIds['BUY_LINK']?>" class="service-detail-item__content-hidden-button main_btn"><?=($arParams['ADD_TO_BASKET_ACTION'] === 'BUY' ? $arParams['MESS_BTN_BUY'] : $arParams['MESS_BTN_ADD_TO_BASKET'])?></button>
                </span>

            </div>

            <a href="<?=Config::get('BASKET_PAGE')?>" class="service-detail-item__content-hidden-form__at_basket" style="display: none">
                <?=Loc::getMessage('PRODUCT_IN_BASKET')?>
            </a>

        </div>
    </div>
</div>



