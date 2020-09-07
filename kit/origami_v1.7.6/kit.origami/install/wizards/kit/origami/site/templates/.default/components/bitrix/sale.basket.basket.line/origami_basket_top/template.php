<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
/**
 * @global string $componentPath
 * @global string $templateName
 * @var CBitrixComponentTemplate $this
 */
$cartStyle = 'bx-basket';
$cartId = "bx_basket".$this->randString();
$arParams['cartId'] = $cartId;

if ($arParams['POSITION_FIXED'] == 'Y')
{
	$cartStyle .= "-fixed {$arParams['POSITION_HORIZONTAL']} {$arParams['POSITION_VERTICAL']}";
	if ($arParams['SHOW_PRODUCTS'] == 'Y')
		$cartStyle .= ' bx-closed';
}
else
{
	$cartStyle .= ' bx-opener';
}

?>
<script>
var <?=$cartId?> = new BitrixSmallCart;
</script>
<div id="<?=$cartId?>" class="<?=$cartStyle?>" onmouseleave="<?=$cartId?>.toggleOpenCloseCart('close')"><?
	/** @var \Bitrix\Main\Page\FrameBuffered $frame */
	$frame = $this->createFrame($cartId, false)->begin();
		require(realpath(dirname(__FILE__)).'/ajax_template.php');
	$frame->beginStub();
		$arResult['COMPOSITE_STUB'] = 'Y';
		//require(realpath(dirname(__FILE__)).'/top_template.php');
		unset($arResult['COMPOSITE_STUB']);
	$frame->end();


?></div>
<div class="overlay-basket"></div>
<script type="text/javascript">
	<?=$cartId?>.siteId       = '<?=SITE_ID?>';
	<?=$cartId?>.cartId       = '<?=$cartId?>';
	<?=$cartId?>.ajaxPath     = <?="'".SITE_DIR."include/ajax/basket_ajax.php"."'"?>;
	<?=$cartId?>.templateName = '<?=$templateName?>';
	<?=$cartId?>.arParams     =  <?=CUtil::PhpToJSObject ($arParams)?>; // TODO \Bitrix\Main\Web\Json::encode
	<?=$cartId?>.closeMessage = '<?=GetMessage('TSB1_COLLAPSE')?>';
	<?=$cartId?>.openMessage  = '<?=GetMessage('TSB1_EXPAND')?>';
    <?=$cartId?>.qntBasket  = '<?=$arResult["NUM_PRODUCTS"]?>';
    <?=$cartId?>.qntDelay  = '<?=$arResult["NUM_PRODUCTS_DELAY"]?>';
    <?=$cartId?>.qntCompare  = '<?=$arResult["NUM_PRODUCTS_COMPARE"]?>';
	<?=$cartId?>.activate();
    var arBasketID = [];
    var arDelayID = [];
    var arCompareID = [];
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

</script>