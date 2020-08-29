<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();
$this->setFrameMode(true);

use \Bitrix\Main\Localization\Loc;
use Kit\Origami\Helper\Config;

if(Config::get('QUICK_VIEW') == 'Y')
{
    $this->addExternalCss("/local/templates/.default/components/bitrix/catalog.element/origami_preview/style.css");
    $this->addExternalJS("/local/templates/.default/components/bitrix/catalog.element/origami_preview/script.js");
}

$this->addExternalCss("/local/templates/.default/components/bitrix/catalog.item/origami_item/style.css");
$this->addExternalJS("/local/templates/.default/components/bitrix/catalog.item/origami_item/script.js");

$this->addExternalCss("/local/templates/.default/components/bitrix/iblock.vote/origami_stars/style.css");
$this->addExternalJS("/local/templates/.default/components/bitrix/iblock.vote/origami_stars/script.js");



$templateLibrary = array('popup');
$currencyList = '';

if (!empty($arResult['CURRENCIES']))
{
	$templateLibrary[] = 'currency';
	$currencyList = CUtil::PhpToJSObject($arResult['CURRENCIES'], false, true, true);
}

$templateData = array(
	'TEMPLATE_THEME' => $arParams['TEMPLATE_THEME'],
	'TEMPLATE_LIBRARY' => $templateLibrary,
	'CURRENCIES' => $currencyList
);
unset($currencyList, $templateLibrary);

$elementEdit = CIBlock::GetArrayByID($arParams['IBLOCK_ID'], 'ELEMENT_EDIT');
$elementDelete = CIBlock::GetArrayByID($arParams['IBLOCK_ID'], 'ELEMENT_DELETE');
$elementDeleteParams = array('CONFIRM' => GetMessage('CT_SPGB_TPL_ELEMENT_DELETE_CONFIRM'));

$positionClassMap = array(
	'left' => 'product-item-label-left',
	'center' => 'product-item-label-center',
	'right' => 'product-item-label-right',
	'bottom' => 'product-item-label-bottom',
	'middle' => 'product-item-label-middle',
	'top' => 'product-item-label-top'
);

$discountPositionClass = '';
if ($arParams['SHOW_DISCOUNT_PERCENT'] === 'Y' && !empty($arParams['DISCOUNT_PERCENT_POSITION']))
{
	foreach (explode('-', $arParams['DISCOUNT_PERCENT_POSITION']) as $pos)
	{
		$discountPositionClass .= isset($positionClassMap[$pos]) ? ' '.$positionClassMap[$pos] : '';
	}
}

$labelPositionClass = '';
if (!empty($arParams['LABEL_PROP_POSITION']))
{
	foreach (explode('-', $arParams['LABEL_PROP_POSITION']) as $pos)
	{
		$labelPositionClass .= isset($positionClassMap[$pos]) ? ' '.$positionClassMap[$pos] : '';
	}
}

$iblockId = '';
if($arResult['CATALOGS'])
{
    $iblockId = reset(array_keys($arResult['CATALOGS']));
}


$arParams['~MESS_BTN_BUY'] = $arParams['~MESS_BTN_BUY'] ?: Loc::getMessage('CT_SPGB_TPL_MESS_BTN_CHOOSE');
$arParams['~MESS_BTN_DETAIL'] = $arParams['~MESS_BTN_DETAIL'] ?: Loc::getMessage('CT_SPGB_TPL_MESS_BTN_DETAIL');
$arParams['~MESS_BTN_COMPARE'] = $arParams['~MESS_BTN_COMPARE'] ?: Loc::getMessage('CT_SPGB_TPL_MESS_BTN_COMPARE');
$arParams['~MESS_BTN_SUBSCRIBE'] = $arParams['~MESS_BTN_SUBSCRIBE'] ?: Loc::getMessage('CT_SPGB_TPL_MESS_BTN_SUBSCRIBE');
$arParams['~MESS_BTN_ADD_TO_BASKET'] = $arParams['~MESS_BTN_ADD_TO_BASKET'] ?: Loc::getMessage('CT_SPGB_TPL_MESS_BTN_CHOOSE');
$arParams['~MESS_NOT_AVAILABLE'] = $arParams['~MESS_NOT_AVAILABLE'] ?: Loc::getMessage('CT_SPGB_TPL_MESS_PRODUCT_NOT_AVAILABLE');
$arParams['~MESS_SHOW_MAX_QUANTITY'] = $arParams['~MESS_SHOW_MAX_QUANTITY'] ?: Loc::getMessage('CT_SPGB_CATALOG_SHOW_MAX_QUANTITY');
$arParams['~MESS_RELATIVE_QUANTITY_MANY'] = $arParams['~MESS_RELATIVE_QUANTITY_MANY'] ?: Loc::getMessage('CT_SPGB_CATALOG_RELATIVE_QUANTITY_MANY');
$arParams['~MESS_RELATIVE_QUANTITY_FEW'] = $arParams['~MESS_RELATIVE_QUANTITY_FEW'] ?: Loc::getMessage('CT_SPGB_CATALOG_RELATIVE_QUANTITY_FEW');

$arParams["HOVER_EFFECT"] = Config::getArray("HOVER_EFFECT");

if(!isset($arParams['ACTION_PRODUCTS']) || in_array("ADMIN", $arParams['ACTION_PRODUCTS']))
{
    $arParams["SHOW_BUY"] = Config::checkAction("BUY");
    $arParams["SHOW_DELAY"] = Config::checkAction("DELAY");
    $arParams["SHOW_COMPARE"] = Config::checkAction("COMPARE");
}else{

    $arParams["SHOW_BUY"] = 0;
    $arParams["SHOW_DELAY"] = 0;
    $arParams["SHOW_COMPARE"] = 0;
    foreach($arParams['ACTION_PRODUCTS'] as $val)
    {
        $arParams["SHOW_".$val] = 1;
    }
}
if(!isset($arParams['VARIANT_LIST_VIEW']) || $arParams['VARIANT_LIST_VIEW']=="ADMIN")
{
    $arParams["VARIANT_LIST_VIEW"] = Config::get("VARIANT_LIST_VIEW");
}

$generalParams = array(
	'SHOW_DISCOUNT_PERCENT' => $arParams['SHOW_DISCOUNT_PERCENT'],
	'PRODUCT_DISPLAY_MODE' => $arParams['PRODUCT_DISPLAY_MODE'],
	'SHOW_MAX_QUANTITY' => $arParams['SHOW_MAX_QUANTITY'],
	'RELATIVE_QUANTITY_FACTOR' => $arParams['RELATIVE_QUANTITY_FACTOR'],
	'MESS_SHOW_MAX_QUANTITY' => $arParams['~MESS_SHOW_MAX_QUANTITY'],
	'MESS_RELATIVE_QUANTITY_MANY' => $arParams['~MESS_RELATIVE_QUANTITY_MANY'],
	'MESS_RELATIVE_QUANTITY_FEW' => $arParams['~MESS_RELATIVE_QUANTITY_FEW'],
	'SHOW_OLD_PRICE' => $arParams['SHOW_OLD_PRICE'],
	'USE_PRODUCT_QUANTITY' => $arParams['USE_PRODUCT_QUANTITY'],
	'PRODUCT_QUANTITY_VARIABLE' => $arParams['PRODUCT_QUANTITY_VARIABLE'],
	'ADD_TO_BASKET_ACTION' => $arParams['ADD_TO_BASKET_ACTION'],
	'ADD_PROPERTIES_TO_BASKET' => $arParams['ADD_PROPERTIES_TO_BASKET'],
	'PRODUCT_PROPS_VARIABLE' => $arParams['PRODUCT_PROPS_VARIABLE'],
	'SHOW_CLOSE_POPUP' => $arParams['SHOW_CLOSE_POPUP'],
	'DISPLAY_COMPARE' => $arParams['DISPLAY_COMPARE'],
	'COMPARE_PATH' => $arParams['COMPARE_PATH'],
	'COMPARE_NAME' => $arParams['COMPARE_NAME'],
	'PRODUCT_SUBSCRIPTION' => $arParams['PRODUCT_SUBSCRIPTION'],
	'PRODUCT_BLOCKS_ORDER' => $arParams['PRODUCT_BLOCKS_ORDER'],
	'LABEL_POSITION_CLASS' => $labelPositionClass,
	'DISCOUNT_POSITION_CLASS' => $discountPositionClass,
	'SLIDER_INTERVAL' => $arParams['SLIDER_INTERVAL'],
	'SLIDER_PROGRESS' => $arParams['SLIDER_PROGRESS'],
	'~ADD_URL_TEMPLATE' => $arResult['~ADD_URL_TEMPLATE'],
	'~BUY_URL_TEMPLATE' => $arResult['~BUY_URL_TEMPLATE'],
	'~COMPARE_URL_TEMPLATE' => str_replace('actionGift','action',$arResult['~COMPARE_URL_TEMPLATE']),
	'~COMPARE_DELETE_URL_TEMPLATE' => $arResult['~COMPARE_DELETE_URL_TEMPLATE'],
	'TEMPLATE_THEME' => $arParams['TEMPLATE_THEME'],
	'USE_ENHANCED_ECOMMERCE' => $arParams['USE_ENHANCED_ECOMMERCE'],
	'DATA_LAYER_NAME' => $arParams['DATA_LAYER_NAME'],
	'MESS_BTN_BUY' => $arParams['~MESS_BTN_BUY'],
	'MESS_BTN_DETAIL' => $arParams['~MESS_BTN_DETAIL'],
	'MESS_BTN_COMPARE' => $arParams['~MESS_BTN_COMPARE'],
	'MESS_BTN_SUBSCRIBE' => $arParams['~MESS_BTN_SUBSCRIBE'],
	'MESS_BTN_ADD_TO_BASKET' => $arParams['~MESS_BTN_ADD_TO_BASKET'],
	'MESS_NOT_AVAILABLE' => $arParams['~MESS_NOT_AVAILABLE'],
	'LABEL_PROP' => $arParams['LABEL_PROP'],
	'USE_COMPARE' => $arParams['USE_COMPARE'],
	'PRODUCT_DISPLAY_MODE' => $arParams['PRODUCT_DISPLAY_MODE'],
	'USE_VOTE_RATING' => $arParams['USE_VOTE_RATING'],
	'IBLOCK_ID' => $iblockId,
    'VARIANT_LIST_VIEW' => $arParams['VARIANT_LIST_VIEW'],
    'SHOW_BUY' => $arParams["SHOW_BUY"],
    'SHOW_DELAY' => 0,
    'SHOW_COMPARE' => 0,
    'HOVER_EFFECT' => implode(" ", $arParams["HOVER_EFFECT"])
);

$obName = 'ob'.preg_replace('/[^a-zA-Z0-9_]/', 'x', $this->GetEditAreaId($this->randString()));
$containerName = 'sale-products-gift-container';
?>
<div data-entity="parent-container">
	<div class="recommended-products__row recommended-products__row--5 sale-products-gift bx-<?=$arParams['TEMPLATE_THEME']?>" data-entity="<?=$containerName?>">

        <?
		if (!empty($arResult['ITEMS']) && !empty($arResult['ITEM_ROWS']))
		{
			$areaIds = array();
			foreach ($arResult['ITEMS'] as &$item)
			{
				$uniqueId = $item['ID'].'_'.md5($this->randString().$component->getAction());
				$areaIds[$item['ID']] = $this->GetEditAreaId($uniqueId);
				$this->AddEditAction($uniqueId, $item['EDIT_LINK'], $elementEdit);
				$this->AddDeleteAction($uniqueId, $item['DELETE_LINK'], $elementDelete, $elementDeleteParams);

				// custom gift labels
				$item['LABEL_VALUE'] = $arParams['TEXT_LABEL_GIFT'] ?: Loc::getMessage('CT_SPGB_TPL_TEXT_LABEL_GIFT_DEFAULT');
				$item['LABEL_ARRAY_VALUE'] = array('gift' => $item['LABEL_VALUE']);
				$item['LABEL_PROP_MOBILE'] = array('gift' => true);
				$item['LABEL'] = !empty($item['LABEL_VALUE']);
			}
			unset($item);
			?>
			<!-- items-container -->
			<?
            $gifts = $arResult['ITEMS'];
            ?>


                <?foreach ($gifts as $key => $item):?>
                    <div class="product_card__block_item" data-entity="items-row">
                        <?
                        $APPLICATION->IncludeComponent(
                            'bitrix:catalog.item',
                            'origami_item',
                            array(
                                'RESULT' => array(
                                    'ITEM' => $item,
                                    'AREA_ID' => $areaIds[$item['ID']],
                                    'TYPE' => 'CARD',
                                    'TEMPLATE' => 'template_3',
                                    'BIG_LABEL' => 'N',
                                    'BIG_DISCOUNT_PERCENT' => 'N',
                                    'BIG_BUTTONS' => 'N',
                                    'SCALABLE' => 'N'
                                ),
                                'PARAMS' => $generalParams
                                    + array('SKU_PROPS' => $arResult['SKU_PROPS'][$item['IBLOCK_ID']]),
                            ),
                            $component,
                            array('HIDE_ICONS' => 'Y')
                        );
                        ?>
                    </div>
                <?endforeach;?>


            <?
			unset($generalParams, $rowItems);
			?>
			<!-- items-container -->
			<?
		}
		else
		{
			?>
			<p class="puzzle_block__title fonts__middle_title"
			   data-entity="header" style="display: none;" data-showed="false"
			><?=Loc::getMessage('CT_SPGB_TPL_TEXT_LABEL_GIFT_DEFAULT')?></p>
			<?
			// load css for bigData/deferred load
			$APPLICATION->IncludeComponent(
				'bitrix:catalog.item',
				'origami_item',
				array(),
				$component,
				array('HIDE_ICONS' => 'Y')
			);
		}
		$signer = new \Bitrix\Main\Security\Sign\Signer;
		$signedTemplate = $signer->sign($templateName, 'sale.products.gift.basket');
		$signedParams = $signer->sign(base64_encode(serialize($arResult['ORIGINAL_PARAMETERS'])), 'sale.products.gift.basket');
		?>

	</div>
</div>

<script>
	BX.message({
		BTN_MESSAGE_BASKET_REDIRECT: '<?=GetMessageJS('CT_SPGB_CATALOG_BTN_MESSAGE_BASKET_REDIRECT')?>',
		BASKET_URL: '<?=$arParams['BASKET_URL']?>',
		ADD_TO_BASKET_OK: '<?=GetMessageJS('ADD_TO_BASKET_OK')?>',
		TITLE_ERROR: '<?=GetMessageJS('CT_SPGB_CATALOG_TITLE_ERROR')?>',
		TITLE_BASKET_PROPS: '<?=GetMessageJS('CT_SPGB_CATALOG_TITLE_BASKET_PROPS')?>',
		TITLE_SUCCESSFUL: '<?=GetMessageJS('ADD_TO_BASKET_OK')?>',
		BASKET_UNKNOWN_ERROR: '<?=GetMessageJS('CT_SPGB_CATALOG_BASKET_UNKNOWN_ERROR')?>',
		BTN_MESSAGE_SEND_PROPS: '<?=GetMessageJS('CT_SPGB_CATALOG_BTN_MESSAGE_SEND_PROPS')?>',
		BTN_MESSAGE_CLOSE: '<?=GetMessageJS('CT_SPGB_CATALOG_BTN_MESSAGE_CLOSE')?>',
		BTN_MESSAGE_CLOSE_POPUP: '<?=GetMessageJS('CT_SPGB_CATALOG_BTN_MESSAGE_CLOSE_POPUP')?>',
		COMPARE_MESSAGE_OK: '<?=GetMessageJS('CT_SPGB_CATALOG_MESS_COMPARE_OK')?>',
		COMPARE_UNKNOWN_ERROR: '<?=GetMessageJS('CT_SPGB_CATALOG_MESS_COMPARE_UNKNOWN_ERROR')?>',
		COMPARE_TITLE: '<?=GetMessageJS('CT_SPGB_CATALOG_MESS_COMPARE_TITLE')?>',
		PRICE_TOTAL_PREFIX: '<?=GetMessageJS('CT_SPGB_CATALOG_PRICE_TOTAL_PREFIX')?>',
		RELATIVE_QUANTITY_MANY: '<?=CUtil::JSEscape($arParams['MESS_RELATIVE_QUANTITY_MANY'])?>',
		RELATIVE_QUANTITY_FEW: '<?=CUtil::JSEscape($arParams['MESS_RELATIVE_QUANTITY_FEW'])?>',
		BTN_MESSAGE_COMPARE_REDIRECT: '<?=GetMessageJS('CT_SPGB_CATALOG_BTN_MESSAGE_COMPARE_REDIRECT')?>',
		SITE_ID: '<?=CUtil::JSEscape($component->getSiteId())?>'
	});

	var <?=$obName?> = new JCSaleProductsGiftBasketComponent({
		siteId: '<?=CUtil::JSEscape($component->getSiteId())?>',
		componentPath: '<?=CUtil::JSEscape($componentPath)?>',
		deferredLoad: true,
		initiallyShowHeader: '<?=!empty($arResult['ITEM_ROWS'])?>',
		currentProductId: <?=CUtil::JSEscape((int)$arResult['POTENTIAL_PRODUCT_TO_BUY']['ID'])?>,
		template: '<?=CUtil::JSEscape($signedTemplate)?>',
		parameters: '<?=CUtil::JSEscape($signedParams)?>',
		container: '<?=$containerName?>'
	});
</script>
