<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();
use \Bitrix\Main\Localization\Loc;
use Sotbit\Origami\Helper\Config;

if(Config::get('QUICK_VIEW') == 'Y')
{
    $this->addExternalCss(CUtil::GetAdditionalFileURL("/local/templates/.default/components/bitrix/catalog.element/origami_preview/style.css"));
    $this->addExternalJS(CUtil::GetAdditionalFileURL("/local/templates/.default/components/bitrix/catalog.element/origami_preview/script.js"));
}

$this->setFrameMode(true);

if (!empty($arResult['NAV_RESULT']))
{
    $navParams =  array(
        'NavPageCount' => $arResult['NAV_RESULT']->NavPageCount,
        'NavPageNomer' => $arResult['NAV_RESULT']->NavPageNomer,
        'NavNum' => $arResult['NAV_RESULT']->NavNum
    );
}
else
{
    $navParams = array(
        'NavPageCount' => 1,
        'NavPageNomer' => 1,
        'NavNum' => $this->randString()
    );
}

$showTopPager = false;
$showBottomPager = false;
$showLazyLoad = false;

if ($arParams['PAGE_ELEMENT_COUNT'] > 0 && $navParams['NavPageCount'] > 1)
{
    $showTopPager = $arParams['DISPLAY_TOP_PAGER'];
    $showBottomPager = $arParams['DISPLAY_BOTTOM_PAGER'];
    $showLazyLoad = $arParams['LAZY_LOAD'] === 'Y' && $navParams['NavPageNomer'] != $navParams['NavPageCount'];
}

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
$arParams['~MESS_BTN_BUY'] = $arParams['~MESS_BTN_BUY'] ?: Loc::getMessage('CT_BCS_TPL_MESS_BTN_BUY');
$arParams['~MESS_BTN_DETAIL'] = $arParams['~MESS_BTN_DETAIL'] ?: Loc::getMessage('CT_BCS_TPL_MESS_BTN_DETAIL');
$arParams['~MESS_BTN_COMPARE'] = $arParams['~MESS_BTN_COMPARE'] ?: Loc::getMessage('CT_BCS_TPL_MESS_BTN_COMPARE');
$arParams['~MESS_BTN_SUBSCRIBE'] = $arParams['~MESS_BTN_SUBSCRIBE'] ?: Loc::getMessage('CT_BCS_TPL_MESS_BTN_SUBSCRIBE');
$arParams['~MESS_BTN_ADD_TO_BASKET'] = $arParams['~MESS_BTN_ADD_TO_BASKET'] ?: Loc::getMessage('CT_BCS_TPL_MESS_BTN_ADD_TO_BASKET');
$arParams['~MESS_NOT_AVAILABLE'] = $arParams['~MESS_NOT_AVAILABLE'] ?: Loc::getMessage('CT_BCS_TPL_MESS_PRODUCT_NOT_AVAILABLE');
$arParams['~MESS_SHOW_MAX_QUANTITY'] = $arParams['~MESS_SHOW_MAX_QUANTITY'] ?: Loc::getMessage('CT_BCS_CATALOG_SHOW_MAX_QUANTITY');
$arParams['~MESS_RELATIVE_QUANTITY_MANY'] = $arParams['~MESS_RELATIVE_QUANTITY_MANY'] ?: Loc::getMessage('CT_BCS_CATALOG_RELATIVE_QUANTITY_MANY');
$arParams['~MESS_RELATIVE_QUANTITY_FEW'] = $arParams['~MESS_RELATIVE_QUANTITY_FEW'] ?: Loc::getMessage('CT_BCS_CATALOG_RELATIVE_QUANTITY_FEW');
$arParams['~MESS_RELATIVE_QUANTITY_NO'] = $arParams['~MESS_RELATIVE_QUANTITY_FEW'] ?: Loc::getMessage('CT_BCS_CATALOG_RELATIVE_QUANTITY_NO');

$arParams['MESS_BTN_LAZY_LOAD'] = $arParams['MESS_BTN_LAZY_LOAD'] ?: Loc::getMessage('CT_BCS_CATALOG_MESS_BTN_LAZY_LOAD');


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
if(!isset($arParams["TEMPLATE_LIST_VIEW_DEFAULT"]))
{
    $arParams["TEMPLATE_LIST_VIEW_DEFAULT"] = Config::get("TEMPLATE_LIST_VIEW_DEFAULT");
}
if(!isset($arParams["SHOW_BUY_OC"]))
{
    $arParams["SHOW_BUY_OC"] = Config::get("SHOW_BUY_OC");
}

$arParams["HOVER_EFFECT"] = Config::getArray("HOVER_EFFECT");

$pagerTemplate = Config::get("PAGINATION");


$generalParams = array(
	'SHOW_DISCOUNT_PERCENT' => $arParams['SHOW_DISCOUNT_PERCENT'],
	'FILL_ITEM_ALL_PRICES' => $arParams['FILL_ITEM_ALL_PRICES'],
	'PRODUCT_DISPLAY_MODE' => $arParams['PRODUCT_DISPLAY_MODE'],
	'SHOW_MAX_QUANTITY' => $arParams['SHOW_MAX_QUANTITY'],
	'LABEL_PROP' => $arParams['LABEL_PROP'],
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
	'~BASKET_URL' => $arParams['~BASKET_URL'],
	'~ADD_URL_TEMPLATE' => $arResult['~ADD_URL_TEMPLATE'],
	'~BUY_URL_TEMPLATE' => $arResult['~BUY_URL_TEMPLATE'],
    '~COMPARE_URL_TEMPLATE' => str_replace('/bitrix/components/sotbit/crosssell.collection/ajax.php', '', $arResult['~COMPARE_URL_TEMPLATE']),
    '~COMPARE_DELETE_URL_TEMPLATE' => str_replace('/bitrix/components/sotbit/crosssell.collection/ajax.php', '', $arResult['~COMPARE_DELETE_URL_TEMPLATE']),
	'TEMPLATE_THEME' => $arParams['TEMPLATE_THEME'],
	'USE_ENHANCED_ECOMMERCE' => $arParams['USE_ENHANCED_ECOMMERCE'],
	'DATA_LAYER_NAME' => $arParams['DATA_LAYER_NAME'],
	'BRAND_PROPERTY' => $arParams['BRAND_PROPERTY'],
	'MESS_BTN_BUY' => $arParams['~MESS_BTN_BUY'],
	'MESS_BTN_DETAIL' => $arParams['~MESS_BTN_DETAIL'],
	'MESS_BTN_COMPARE' => $arParams['~MESS_BTN_COMPARE'],
	'MESS_BTN_SUBSCRIBE' => $arParams['~MESS_BTN_SUBSCRIBE'],
	'MESS_BTN_ADD_TO_BASKET' => $arParams['~MESS_BTN_ADD_TO_BASKET'],
	'MESS_NOT_AVAILABLE' => $arParams['~MESS_NOT_AVAILABLE'],
	'USE_VOTE_RATING' => $arParams['USE_VOTE_RATING'],
    'IBLOCK_ID' => $arParams['IBLOCK_ID'],
    'IBLOCK_TYPE' => $arParams['IBLOCK_TYPE'],
    'SHOW_BUY' =>  $arParams["SHOW_BUY"],
    'SHOW_DELAY' => $arParams["SHOW_DELAY"],
    'SHOW_COMPARE' => $arParams["SHOW_COMPARE"],
    'VARIANT_LIST_VIEW' => $arParams["VARIANT_LIST_VIEW"],
    'TEMPLATE_LIST_VIEW_DEFAULT' => $arParams["TEMPLATE_LIST_VIEW_DEFAULT"],
    'SHOW_BUY_OC' => $arParams["SHOW_BUY_OC"],
    'HOVER_EFFECT' => implode(" ", $arParams["HOVER_EFFECT"])
);

$obName = 'ob'.preg_replace('/[^a-zA-Z0-9_]/', 'x', $this->GetEditAreaId($navParams['NavNum']));
$containerName = 'container-'.$navParams['NavNum'];

?>

    <div class="block__product_cards block__product_cards-section <?=( $arParams["TEMPLATE_LIST_VIEW_DEFAULT"] !== 'list' && $arParams['MOBILE_VIEW_MINIMAL'] == 'Y' ? 'block__product_cards--mobile-min' : '' )?>" data-entity="<?=$containerName?>">
<!--    <div class="block__product_cards block__product_cards--mobile-min block__product_cards-section" data-entity="--><?//=$containerName?><!--">-->
		<?
		if($arResult['ITEMS'])
		{
			?><!-- items-container --><?
		    foreach($arResult['ITEMS'] as $item)
			{
                ?>
				<div
                    data-entity="items-row"
                    class="product_card__block_item <?
                        if($arParams["TEMPLATE_LIST_VIEW_DEFAULT"] == "list"):?>product_card__block_item--row <?=( $arParams['MOBILE_VIEW_MINIMAL'] == 'Y' ? "product_card__block_item--row-min" : "" )?><?else:?>product_card__block_item-<?=$arParams['LINE_ELEMENT_COUNT']?><?endif;
                    ?>"
                >
<!--				<div data-entity="items-row" class="product_card__block_item --><?//if($arParams["TEMPLATE_LIST_VIEW_DEFAULT"] == "list"):?><!--product_card__block_item--row product_card__block_item--row-min--><?//else:?><!--product_card__block_item---><?//=$arParams['LINE_ELEMENT_COUNT']?><!----><?//endif;?><!--">-->
					<?
					$uniqueId = $item['ID'].'_'.md5($this->randString().$component->getAction());
					$APPLICATION->IncludeComponent(
						'bitrix:catalog.item',
						'origami_item',
						array(
							'RESULT' => array(
								'ITEM' => $item,
								'TYPE' => strtoupper($arParams["TEMPLATE_LIST_VIEW_DEFAULT"]),
								'TEMPLATE' => $arParams["TEMPLATE_LIST_VIEW_DEFAULT"] == "list" ? "template_1" : $arParams["VARIANT_LIST_VIEW"],
								'AREA_ID' => $this->GetEditAreaId($uniqueId),
								'LAZY_LOAD' => (Config::get('LAZY_LOAD') == "Y"),
								'BIG_LABEL' => 'N',
								'BIG_DISCOUNT_PERCENT' => 'N',
								'BIG_BUTTONS' => 'N',
								'SCALABLE' => 'N',
							),
							'PARAMS' => $generalParams + array('SKU_PROPS' => $arResult['SKU_PROPS'][$item['IBLOCK_ID']]),
                            'MOBILE_VIEW_MINIMAL' => $arParams['MOBILE_VIEW_MINIMAL']
						),
						$component,
						array('HIDE_ICONS' => 'Y')
					);
					?>
				</div>
	    <?
			}?>
            <!-- items-container -->
            <?
		}
		else {
            $APPLICATION->IncludeComponent(
                "bitrix:main.include",
                "",
                array("AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR . "include/sotbit_origami/empty_filter/empty_filter.php")
            );
        }

		?>
	</div>

<?
if ($showBottomPager)
{
	?>
	<div data-pagination-num="<?=$navParams['NavNum']?>">
        <!-- pagination-container -->
		<?=$arResult['NAV_STRING']?>
        <!-- pagination-container -->
	</div>
	<?
}

$signer = new \Bitrix\Main\Security\Sign\Signer;
$signedTemplate = $signer->sign($templateName, 'catalog.section');
$signedParams = $signer->sign(base64_encode(serialize($arResult['ORIGINAL_PARAMETERS'])), 'catalog.section');
$containerName = 'container-'.$navParams['NavNum'];
?>
<script>
	BX.message({
		BTN_MESSAGE_BASKET_REDIRECT: '<?=GetMessageJS('CT_BCS_CATALOG_BTN_MESSAGE_BASKET_REDIRECT')?>',
		BASKET_URL: '<?=$arParams['BASKET_URL']?>',
		ADD_TO_BASKET_OK: '<?=GetMessageJS('ADD_TO_BASKET_OK')?>',
		TITLE_ERROR: '<?=GetMessageJS('CT_BCS_CATALOG_TITLE_ERROR')?>',
		TITLE_BASKET_PROPS: '<?=GetMessageJS('CT_BCS_CATALOG_TITLE_BASKET_PROPS')?>',
		TITLE_SUCCESSFUL: '<?=GetMessageJS('ADD_TO_BASKET_OK')?>',
		BASKET_UNKNOWN_ERROR: '<?=GetMessageJS('CT_BCS_CATALOG_BASKET_UNKNOWN_ERROR')?>',
		BTN_MESSAGE_SEND_PROPS: '<?=GetMessageJS('CT_BCS_CATALOG_BTN_MESSAGE_SEND_PROPS')?>',
		BTN_MESSAGE_CLOSE: '<?=GetMessageJS('CT_BCS_CATALOG_BTN_MESSAGE_CLOSE')?>',
		BTN_MESSAGE_CLOSE_POPUP: '<?=GetMessageJS('CT_BCS_CATALOG_BTN_MESSAGE_CLOSE_POPUP')?>',
		COMPARE_MESSAGE_OK: '<?=GetMessageJS('CT_BCS_CATALOG_MESS_COMPARE_OK')?>',
		COMPARE_UNKNOWN_ERROR: '<?=GetMessageJS('CT_BCS_CATALOG_MESS_COMPARE_UNKNOWN_ERROR')?>',
		COMPARE_TITLE: '<?=GetMessageJS('CT_BCS_CATALOG_MESS_COMPARE_TITLE')?>',
		PRICE_TOTAL_PREFIX: '<?=GetMessageJS('CT_BCS_CATALOG_PRICE_TOTAL_PREFIX')?>',
		RELATIVE_QUANTITY_MANY: '<?=CUtil::JSEscape($arParams['MESS_RELATIVE_QUANTITY_MANY'])?>',
		RELATIVE_QUANTITY_FEW: '<?=CUtil::JSEscape($arParams['MESS_RELATIVE_QUANTITY_FEW'])?>',
		RELATIVE_QUANTITY_NO: '<?=CUtil::JSEscape($arParams['MESS_RELATIVE_QUANTITY_NO'])?>',
		BTN_MESSAGE_COMPARE_REDIRECT: '<?=GetMessageJS('CT_BCS_CATALOG_BTN_MESSAGE_COMPARE_REDIRECT')?>',
		BTN_MESSAGE_LAZY_LOAD: '<?=CUtil::JSEscape($arParams['MESS_BTN_LAZY_LOAD'])?>',
		BTN_MESSAGE_LAZY_LOAD_WAITER: '<?=GetMessageJS('CT_BCS_CATALOG_BTN_MESSAGE_LAZY_LOAD_WAITER')?>',
		SITE_ID: '<?=CUtil::JSEscape($component->getSiteId())?>'
	});
	var <?=$obName?> = new JCCatalogSectionComponent({
		siteId: '<?=CUtil::JSEscape($component->getSiteId())?>',
		componentPath: '<?=CUtil::JSEscape($componentPath)?>',
		navParams: <?=CUtil::PhpToJSObject($navParams)?>,
		deferredLoad: false, // enable it for deferred load
		initiallyShowHeader: '<?=!empty($arResult['ITEM_ROWS'])?>',
		bigData: <?=CUtil::PhpToJSObject($arResult['BIG_DATA'])?>,
		lazyLoad: !!'<?=$showLazyLoad?>',
		loadOnScroll: !!'<?=($arParams['LOAD_ON_SCROLL'] === 'Y' || $pagerTemplate == "origami_auto")?>',
		template: '<?=CUtil::JSEscape($signedTemplate)?>',
		ajaxId: '<?=CUtil::JSEscape($arParams['AJAX_ID'])?>',
		parameters: '<?=CUtil::JSEscape($signedParams)?>',
		container: '<?=$containerName?>',
    });
</script>
<!-- component-end -->
