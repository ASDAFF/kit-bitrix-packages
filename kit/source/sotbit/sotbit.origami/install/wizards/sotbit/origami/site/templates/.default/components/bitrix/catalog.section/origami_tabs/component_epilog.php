<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

/**
 * @var array $arParams
 * @var array $templateData
 * @var string $templateFolder
 * @var CatalogSectionComponent $component
 */

$arIDs = array();
$basketRes = \Bitrix\Sale\Internals\BasketTable::getList(array(
    'filter' => array(
        'FUSER_ID' => \Bitrix\Sale\Fuser::getId(),
        'ORDER_ID' => null,
        'LID' => SITE_ID,
        'CAN_BUY' => 'Y',
        'DELAY' => 'N',
    )
));
while ($item = $basketRes->fetch()) {
    $arIDs[] = $item["PRODUCT_ID"];
}

$inBasket = array_values(array_unique($arIDs));
?>
	<script>
		var arrIds = <?=json_encode($inBasket)?>;
		var arrIdsForOffers = [];
		//console.log(arrIds);
		if(arrIds) {
			arrIds.forEach(function (item, i) {
				arrIds[i] = ".check_basket_" + item;
				arrIdsForOffers[i] = ".check_offer_basket_pr_" + item;
			});
			var selectStr = arrIds.join(",");
			var selectOffersStr = arrIdsForOffers.join(",");
			$(selectStr).each(function () {
				var btnsBlock = $(this);
				btnsBlock.find(".product-card-inner__buy").hide();
				btnsBlock.find(".product-card-inner__product-basket").show();
			});
		}
		//console.log(arrIdsForOffers);
	</script>

<?
$wish = new \Sotbit\Origami\Sale\Basket\Wish();
$wishes = array_values($wish->findWishes());
?>
	<script>
		var arrWishIds = <?=json_encode($wishes)?>;
		$("[class ^= check_basket_]").find(".product_card__block_icon .fa-heart").attr("title", BX.message('WISH_TO'));
		//console.log(arrWishIds);
		if(arrWishIds) {
			arrWishIds.forEach(function (item, i) {
				arrWishIds[i] = ".check_basket_" + item;
			});
			var selectWishStr = arrWishIds.join(",");
			$(selectWishStr).find(".product_card__block_icon .fa-heart").addClass("active").attr("title", BX.message('WISH_IN'));
		}
	</script>

<?


global $APPLICATION;

if (isset($templateData['TEMPLATE_THEME']))
{
	$APPLICATION->SetAdditionalCSS($templateFolder.'/themes/'.$templateData['TEMPLATE_THEME'].'/style.css');
	$APPLICATION->SetAdditionalCSS('/bitrix/css/main/themes/'.$templateData['TEMPLATE_THEME'].'/style.css', true);
}

if (!empty($templateData['TEMPLATE_LIBRARY']))
{
	$loadCurrency = false;
	if (!empty($templateData['CURRENCIES']))
	{
		$loadCurrency = \Bitrix\Main\Loader::includeModule('currency');
	}

	CJSCore::Init($templateData['TEMPLATE_LIBRARY']);

	if ($loadCurrency)
	{
		?>
		<script>
			BX.Currency.setCurrencies(<?=$templateData['CURRENCIES']?>);
		</script>
		<?
	}
}

//	lazy load and big data json answers
$request = \Bitrix\Main\Context::getCurrent()->getRequest();
if ($request->isAjaxRequest() && ($request->get('action') === 'showMore' || $request->get('action') === 'deferredLoad'))
{
	$content = ob_get_contents();
	ob_end_clean();

	list(, $itemsContainer) = explode('<!-- items-container -->', $content);
	list(, $paginationContainer) = explode('<!-- pagination-container -->', $content);

	if ($arParams['AJAX_MODE'] === 'Y')
	{
		$component->prepareLinks($paginationContainer);
	}

	$component::sendJsonAnswer(array(
		'items' => $itemsContainer,
		'pagination' => $paginationContainer
	));
}