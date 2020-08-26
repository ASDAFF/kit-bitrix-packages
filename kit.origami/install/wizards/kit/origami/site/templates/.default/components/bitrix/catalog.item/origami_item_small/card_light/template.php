<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use \Bitrix\Main\Localization\Loc;
use Kit\Origami\Helper\Config;
?>

<div class="small-product-block-wrapper">
    <a class="small-product-block" href="<?=$item['DETAIL_PAGE_URL']?>" title="<?=$item['NAME']?>">
        <?
        $frame = $this->createFrame()->begin();
        if($arParams['SHOW_DISCOUNT_PERCENT'] === 'Y')
        {

            ?>
            <span class="small-product-block__sticker" id="<?=$itemIds['DSC_PERC']?>" <?if(!$price['PERCENT']):?>style="display:none"<?endif?>>
                <?

                ?>
                -<?=$price['PERCENT']?>%
                <?

                ?>
            </span>
            <?
        }
        $frame->end();?>
        <div class="small-product-block__img-block">
            <?
            if(!empty($item['PICT'])):?>
                <div class="small-product-block__img-wrapper">
                    <img
                        class="small-product-block__img <?if(arResult['LAZY_LOAD']):?>lazy<?endif;?>"
                        <?=$strLazyLoad?>
                        alt="<?=$imgAlt?>"
                        title="<?=$imgTitle?>"
                        width="<?=$item['PICT']['WIDTH'] > 0 ? $item['PICT']['WIDTH'] : ''?>"
                        height="<?=$item['PICT']['HEIGHT'] > 0 ? $item['PICT']['HEIGHT'] : ''?>"
                    >
                    <?if($arResult['LAZY_LOAD']):?>
                        <span class="loader-lazy loader-lazy--small"></span> <!--LOADER_LAZY-->
                    <?endif;?>
                </div>
            <?endif;?>
        </div>
        <div class="small-product-block__info">
            <p class="small-product-block__name-product"><?=$productTitle?></p>
            <div class="small-product-block__detail-wrapper">
                <?
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
                <span class="product-card-inner__quantity">
                    <svg class="product-card-inner__quantity-icon" width="10" height="10">
                        <use xlink:href="/local/templates/kit_origami/assets/img/sprite.svg#icon_check_availability_small"></use>
                    </svg>
                    <?php
                    echo $actualItem['CATALOG_QUANTITY'].' '.$actualItem['ITEM_MEASURE']['TITLE'];
                    ?>
                </span>
            </div>
            <?
            $frame = $this->createFrame()->begin();?>
            <div class="small-product-block__price-block">
                <div class="small-product-block__price-main">
                    <?=$price['PRINT_PRICE']?>
                </div>
                <?if($price['DISCOUNT'] > 0):?>
                    <div class="small-product-block__price-old">
                        <?=$price['PRINT_BASE_PRICE']?>
                    </div>
                <?endif;?>
            </div>
            <?
            $frame->end();
            ?>
        </div>
    </a>
</div>
