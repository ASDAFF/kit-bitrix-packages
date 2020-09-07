<?
use Kit\Origami\Helper\Config;
\Bitrix\Main\Loader::includeModule('kit.origami');
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();
/**
 * @global array $arParams
 * @global CUser $USER
 * @global CMain $APPLICATION
 * @global string $cartId
 */
if (!$arResult["DISABLE_USE_BASKET"])
{
	?>
    <?if($arResult["SHOW_COMPARE"]):
	    ?>
		<a class="header-two__basket-compare <?=($arResult["NUM_PRODUCTS_COMPARE"] > 0)?'active':''?>" <?if($arResult["NUM_PRODUCTS_COMPARE"] != 0):?> href="<?=Config::get('COMPARE_PAGE')?>" <?endif;?>>
			<svg class="basket-compare-icon" width="18" height="18">
				<use xlink:href="/local/templates/kit_origami/assets/img/sprite.svg#icon_compare"></use>
			</svg>
			<span class="basket-item-count" id="compare-count"><?=$arResult["NUM_PRODUCTS_COMPARE"]?></span>
		</a>
	<?endif;
    if($arResult["SHOW_DELAY"]):
	?>
	<a class="header-two__basket-favorites <?=($arResult["NUM_PRODUCTS_DELAY"] > 0)?'active':''?>" href="<?=$arParams['PATH_TO_BASKET'].'#favorit'?>" <?if($arResult["NUM_PRODUCTS_DELAY"]>0):?>onmouseenter="<?=$cartId?>.toggleOpenCloseCart('open')"<?endif?>>
		<svg class="basket-favorites-icon" width="18" height="18">
			<use xlink:href="/local/templates/kit_origami/assets/img/sprite.svg#icon_favourite"></use>
		</svg>
		<span class="basket-item-count" id="favorites-count"><?=$arResult["NUM_PRODUCTS_DELAY"]?></span>
	</a>
    <?endif;?>
    <?if($arResult["SHOW_BASKET"]):?>
	<a class="header-two__basket-buy <?=($arResult['NUM_PRODUCTS'] > 0)?'active':''?>" href="<?=$arParams['PATH_TO_BASKET']?>" <?if($arResult["NUM_PRODUCTS"]>0):?>onmouseenter="<?=$cartId?>.toggleOpenCloseCart('open')"<?endif;?>>
		<svg class="basket-buy-icon" width="18" height="18">
			<use xlink:href="/local/templates/kit_origami/assets/img/sprite.svg#icon_cart"></use>
		</svg>
        <?
        if (!$compositeStub)
        {
            if ($arParams['SHOW_NUM_PRODUCTS'] == 'Y' && ($arResult['NUM_PRODUCTS'] > 0 || $arParams['SHOW_EMPTY_VALUES'] == 'Y'))
            {
                ?>
				<span class="basket-item-count" id="basket-count"><?=$arResult['NUM_PRODUCTS']?></span>
                <?
            }
        }
        ?>
	</a>
    <?endif;?>
	<?
}
?>


