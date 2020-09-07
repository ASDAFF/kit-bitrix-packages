<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

global $APPLICATION;

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

$comparedIds = array();

$iblockId = ($arParams['IBLOCK_ID'])?$arParams['IBLOCK_ID']:$arParams['POTENTIAL_PRODUCT_TO_BUY'];

if (!empty($_SESSION[$arParams['COMPARE_NAME']][$iblockId])) {
	$comparedIds[] = $_SESSION[$arParams['COMPARE_NAME']][$iblockId]['ITEMS'];
}

$inBasket = array_values(array_unique($arIDs));

$Wish = new \Kit\Origami\Sale\Basket\Wish();
$wishes = $Wish->findWishes();

?>
	<script>
		var compares = '<?=str_replace("'",'"',CUtil::PhpToJSObject($comparedIds,false, true))?>';
		var wishes = '<?=str_replace("'",'"',CUtil::PhpToJSObject($wishes,false, true))?>';


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
				btnsBlock.find(".product_card__block_buy").hide();
				btnsBlock.find(".product_card__path_to_basket").show();
			});
		}
		//console.log(arrIdsForOffers);





	</script>
<?


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