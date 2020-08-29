<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main;
use Bitrix\Main\Localization\Loc;

$documentRoot = Main\Application::getDocumentRoot();

if(!file_exists($_SERVER['DOCUMENT_ROOT'].'/bitrix/components/bitrix/sale.products.gift.basket/class.php'))
{
	$arParams['USE_GIFTS'] = 'N';
}



if (empty($arParams['TEMPLATE_THEME']))
{
	$arParams['TEMPLATE_THEME'] = Main\ModuleManager::isModuleInstalled('bitrix.eshop') ? 'site' : 'blue';
}

if ($arParams['TEMPLATE_THEME'] === 'site')
{
	$templateId = Main\Config\Option::get('main', 'wizard_template_id', 'eshop_bootstrap', $component->getSiteId());
	$templateId = preg_match('/^eshop_adapt/', $templateId) ? 'eshop_adapt' : $templateId;
	$arParams['TEMPLATE_THEME'] = Main\Config\Option::get('main', 'wizard_'.$templateId.'_theme_id', 'blue', $component->getSiteId());
}

if (!empty($arParams['TEMPLATE_THEME']))
{
	if (!is_file($documentRoot.'/bitrix/css/main/themes/'.$arParams['TEMPLATE_THEME'].'/style.css'))
	{
		$arParams['TEMPLATE_THEME'] = 'blue';
	}
}

if (!isset($arParams['DISPLAY_MODE']) || !in_array($arParams['DISPLAY_MODE'], array('extended', 'compact')))
{
	$arParams['DISPLAY_MODE'] = 'extended';
}

$arParams['USE_DYNAMIC_SCROLL'] = isset($arParams['USE_DYNAMIC_SCROLL']) && $arParams['USE_DYNAMIC_SCROLL'] === 'N' ? 'N' : 'Y';
$arParams['SHOW_FILTER'] = isset($arParams['SHOW_FILTER']) && $arParams['SHOW_FILTER'] === 'N' ? 'N' : 'Y';

$arParams['PRICE_DISPLAY_MODE'] = isset($arParams['PRICE_DISPLAY_MODE']) && $arParams['PRICE_DISPLAY_MODE'] === 'N' ? 'N' : 'Y';

if (!isset($arParams['TOTAL_BLOCK_DISPLAY']) || !is_array($arParams['TOTAL_BLOCK_DISPLAY']))
{
	$arParams['TOTAL_BLOCK_DISPLAY'] = array('top');
}

if (empty($arParams['PRODUCT_BLOCKS_ORDER']))
{
	$arParams['PRODUCT_BLOCKS_ORDER'] = 'props,sku,columns';
}

if (is_string($arParams['PRODUCT_BLOCKS_ORDER']))
{
	$arParams['PRODUCT_BLOCKS_ORDER'] = explode(',', $arParams['PRODUCT_BLOCKS_ORDER']);
}

$arParams['USE_PRICE_ANIMATION'] = isset($arParams['USE_PRICE_ANIMATION']) && $arParams['USE_PRICE_ANIMATION'] === 'N' ? 'N' : 'Y';
$arParams['EMPTY_BASKET_HINT_PATH'] = isset($arParams['EMPTY_BASKET_HINT_PATH']) ? (string)$arParams['EMPTY_BASKET_HINT_PATH'] : '/';
$arParams['USE_ENHANCED_ECOMMERCE'] = isset($arParams['USE_ENHANCED_ECOMMERCE']) && $arParams['USE_ENHANCED_ECOMMERCE'] === 'Y' ? 'Y' : 'N';
$arParams['DATA_LAYER_NAME'] = isset($arParams['DATA_LAYER_NAME']) ? trim($arParams['DATA_LAYER_NAME']) : 'dataLayer';
$arParams['BRAND_PROPERTY'] = isset($arParams['BRAND_PROPERTY']) ? trim($arParams['BRAND_PROPERTY']) : '';

if ($arParams['USE_GIFTS'] === 'Y')
{
	CBitrixComponent::includeComponentClass('bitrix:sale.products.gift.basket');

	$giftParameters = array(
		'SHOW_PRICE_COUNT' => 1,
		'PRODUCT_SUBSCRIPTION' => 'N',
		'PRODUCT_ID_VARIABLE' => 'id',
		'PARTIAL_PRODUCT_PROPERTIES' => 'N',
		'USE_PRODUCT_QUANTITY' => 'N',
		'ACTION_VARIABLE' => 'actionGift',
		'ADD_PROPERTIES_TO_BASKET' => 'Y',

		'BASKET_URL' => $APPLICATION->GetCurPage(),
		'APPLIED_DISCOUNT_LIST' => $arResult['APPLIED_DISCOUNT_LIST'],
		'FULL_DISCOUNT_LIST' => $arResult['FULL_DISCOUNT_LIST'],

		'TEMPLATE_THEME' => $arParams['TEMPLATE_THEME'],
		'PRICE_VAT_INCLUDE' => $arParams['PRICE_VAT_SHOW_VALUE'],
		'CACHE_GROUPS' => $arParams['CACHE_GROUPS'],

		'BLOCK_TITLE' => $arParams['GIFTS_BLOCK_TITLE'],
		'HIDE_BLOCK_TITLE' => $arParams['GIFTS_HIDE_BLOCK_TITLE'],
		'TEXT_LABEL_GIFT' => $arParams['GIFTS_TEXT_LABEL_GIFT'],

		'LABEL_PROP' => array(
            0 => 'KHIT',
            1 => 'NOVINKA'
        ),

        "OFFER_TREE_PROPS" => array(
            0 => "PROTSESSOR",
            1 => "OBEM_OPERATICHNOY_PAMYATI",
            2 => "OBEM_PAMYATI",
            3 => "RAZMER",
            4 => "CHASTOTA_PROTSESSORA",
            5 => "TIP_VIDEOKARTY",
            6 => "TSVET",
            7 => "KOLICHESTVO_YADER_PROTSESORA",
            8 => "OBEM_VIDEOPAMYATI",
            9 => "TSVET_1",
            10 => "USTANOVLENNAYA_OS",
            11 => "CML2_MANUFACTURER",
        ),
        "USE_COMPARE" => "Y",
        "PRODUCT_DISPLAY_MODE" => "Y",
        "DISPLAY_COMPARE" => "Y",
        "USE_VOTE_RATING" => "N",
		'COMPARE_PATH' => SITE_DIR.'catalog/',
		'DETAIL_URL' => isset($arParams['GIFTS_DETAIL_URL']) ? $arParams['GIFTS_DETAIL_URL'] : null,
		'PRODUCT_QUANTITY_VARIABLE' => $arParams['GIFTS_PRODUCT_QUANTITY_VARIABLE'],
		'PRODUCT_PROPS_VARIABLE' => $arParams['GIFTS_PRODUCT_PROPS_VARIABLE'],
		'SHOW_OLD_PRICE' => $arParams['GIFTS_SHOW_OLD_PRICE'],
		'SHOW_DISCOUNT_PERCENT' => $arParams['GIFTS_SHOW_DISCOUNT_PERCENT'],
		'DISCOUNT_PERCENT_POSITION' => $arParams['DISCOUNT_PERCENT_POSITION'],
		'MESS_BTN_BUY' => $arParams['GIFTS_MESS_BTN_BUY'],
		'MESS_BTN_DETAIL' => $arParams['GIFTS_MESS_BTN_DETAIL'],
		'CONVERT_CURRENCY' => $arParams['GIFTS_CONVERT_CURRENCY'],
		'HIDE_NOT_AVAILABLE' => $arParams['GIFTS_HIDE_NOT_AVAILABLE'],
		'PRODUCT_ROW_VARIANTS' => '',
		'PAGE_ELEMENT_COUNT' => 4,
		'DEFERRED_PRODUCT_ROW_VARIANTS' => \Bitrix\Main\Web\Json::encode(
			SaleProductsGiftBasketComponent::predictRowVariants(
				$arParams['GIFTS_PAGE_ELEMENT_COUNT'],
				$arParams['GIFTS_PAGE_ELEMENT_COUNT']
			)
		),
		'DEFERRED_PAGE_ELEMENT_COUNT' => $arParams['GIFTS_PAGE_ELEMENT_COUNT'],
		'ADD_TO_BASKET_ACTION' => 'BUY',
		'PRODUCT_DISPLAY_MODE' => 'Y',
		'PRODUCT_BLOCKS_ORDER' => isset($arParams['GIFTS_PRODUCT_BLOCKS_ORDER']) ? $arParams['GIFTS_PRODUCT_BLOCKS_ORDER'] : '',
		'SHOW_SLIDER' => isset($arParams['GIFTS_SHOW_SLIDER']) ? $arParams['GIFTS_SHOW_SLIDER'] : '',
		'SLIDER_INTERVAL' => isset($arParams['GIFTS_SLIDER_INTERVAL']) ? $arParams['GIFTS_SLIDER_INTERVAL'] : '',
		'SLIDER_PROGRESS' => isset($arParams['GIFTS_SLIDER_PROGRESS']) ? $arParams['GIFTS_SLIDER_PROGRESS'] : '',
		'LABEL_PROP_POSITION' => $arParams['LABEL_PROP_POSITION'],

		'USE_ENHANCED_ECOMMERCE' => $arParams['USE_ENHANCED_ECOMMERCE'],
		'DATA_LAYER_NAME' => $arParams['DATA_LAYER_NAME'],
		'BRAND_PROPERTY' => $arParams['BRAND_PROPERTY'],

	);
}

\CJSCore::Init(array('fx', 'popup', 'ajax'));

$this->addExternalCss('/bitrix/css/main/bootstrap.css');
$this->addExternalCss($templateFolder.'/themes/'.$arParams['TEMPLATE_THEME'].'/style.css');

$this->addExternalJs($templateFolder.'/js/mustache.js');
$this->addExternalJs($templateFolder.'/js/action-pool.js');
$this->addExternalJs($templateFolder.'/js/filter.js');
$this->addExternalJs($templateFolder.'/js/component.js');

$mobileColumns = isset($arParams['COLUMNS_LIST_MOBILE'])
	? $arParams['COLUMNS_LIST_MOBILE']
	: $arParams['COLUMNS_LIST'];
$mobileColumns = array_fill_keys($mobileColumns, true);

$jsTemplates = new Main\IO\Directory($documentRoot.$templateFolder.'/js-templates');
/** @var Main\IO\File $jsTemplate */
foreach ($jsTemplates->getChildren() as $jsTemplate)
{
	include($jsTemplate->getPath());
}

$displayModeClass = $arParams['DISPLAY_MODE'] === 'compact' ? ' basket-items-list-wrapper-compact' : '';

if (empty($arResult['ERROR_MESSAGE']))
{
	if ($arParams['USE_GIFTS'] === 'Y' && $arParams['GIFTS_PLACE'] === 'TOP')
	{
		$APPLICATION->IncludeComponent(
			'bitrix:sale.products.gift.basket',
			'origami_default',
			$giftParameters,
			$component
		);
	}

	if ($arResult['BASKET_ITEM_MAX_COUNT_EXCEEDED'])
	{
		?>
		<div id="basket-item-message">
			<?=Loc::getMessage('SBB_BASKET_ITEM_MAX_COUNT_EXCEEDED', array('#PATH#' => $arParams['PATH_TO_BASKET']))?>
		</div>
		<?
	}
	?>
	<div id="basket-root" class="bx-basket bx-step-opacity" style="opacity: 0;">
		<?
		if (
			$arParams['BASKET_WITH_ORDER_INTEGRATION'] !== 'Y'
			&& in_array('top', $arParams['TOTAL_BLOCK_DISPLAY'])
		)
		{
			?>
			<div class="basket-root-row">
				<div class="basket-root-content" data-entity="basket-total-block"></div>
			</div>
			<?
		}
		?>

		<div class="basket-root-row">
			<div class="basket-root-content">
				<div class="alert alert-warning alert-dismissable" id="basket-warning" style="display: none;">
					<span class="close" data-entity="basket-items-warning-notification-close">&times;</span>
					<div data-entity="basket-general-warnings"></div>
					<div data-entity="basket-item-warnings">
						<?=Loc::getMessage('SBB_BASKET_ITEM_WARNING')?>
					</div>
				</div>
			</div>
		</div>
		<div class="basket-root-row">
			<div class="basket-root-content">
				<div class="basket-items-list-wrapper basket-items-list-wrapper-height-fixed basket-items-list-wrapper-light<?=$displayModeClass?>"
					id="basket-items-list-wrapper">
					<div class="basket-items-list-header" data-entity="basket-items-list-header">

						<div class="basket-items-search-field" data-entity="basket-filter">
                            <? if($arParams['SHOW_FILTER'] == 'Y') :?>
							<div class="form has-feedback">
								<input type="text" class="form-control"
									placeholder="<?=Loc::getMessage('SBB_BASKET_FILTER')?>"
                                    data-entity="basket-filter-input">
                                    <svg class="form-control-feedback basket-clear" data-entity="basket-filter-clear-btn" width="10px" height="10px">
                                        <use xlink:href="/local/templates/kit_origami/assets/img/sprite.svg#icon_cancel"></use>
                                    </svg>
								<!-- <span class="form-control-feedback basket-clear" data-entity="basket-filter-clear-btn"></span> -->
							</div>
                            <? endif;?>
						</div>

						<div class="basket-items-list-header-filter basket-items-list-header-filter-visible-js">
							<span class="basket-items-list-header-filter-visibleItem basket-items-list-header-filter-visibleItem-js"><?=Loc::getMessage('SBB_IN_BASKET2')?></span>
						</div>
						<div class="basket-items-list-header-filter basket-items-list-header-filter-hide-js basket-items-list-header-filter-hide">
							<?if($arParams['EXCLUDE_DELAY'] != 'Y'):?>
								<a href="javascript:void(0)" class="basket-items-list-header-filter-item active"
									data-entity="basket-items-count" data-filter="all" style="display: none;"></a>
							<?endif;?>
                            <?if($arParams['EXCLUDE_DELAY'] == 'Y'):?>
							<a href="javascript:void(0)" class="basket-items-list-header-filter-item"
								data-entity="basket-items-count" data-filter="orderable" style="display: none;"></a>
                            <?endif;?>
							<a href="javascript:void(0)" class="basket-items-list-header-filter-item"
								data-entity="basket-items-count" data-filter="similar" style="display: none;"></a>
							<a href="javascript:void(0)" class="basket-items-list-header-filter-item"
								data-entity="basket-items-count" data-filter="warning" style="display: none;"></a>
							<a href="javascript:void(0)" class="basket-items-list-header-filter-item"
								data-entity="basket-items-count" data-filter="delayed" style="display: none;"></a>
							<a href="javascript:void(0)" class="basket-items-list-header-filter-item"
                                data-entity="basket-items-count" data-filter="not-available" style="display: none;"></a>
                        </div>
                        <script>
                            document.addEventListener('DOMContentLoaded', function () {
                                    if(window.location.hash === '#favorit') {
                                    const btnFavorits = document.querySelector('[data-filter="delayed"]');
                                    const eventClick = new Event ('click');
                                    btnFavorits.dispatchEvent(eventClick);
                                    console.log(window.location);

                                }
                            });
                        </script>
						<div class="basket-items__clear" onclick="clearBasket('<?=SITE_ID?>','<?=SITE_DIR?>');return false;">
                            <svg width="18" height="18">
                                <use xlink:href="/local/templates/kit_origami/assets/img/sprite.svg#icon_clear_cart_backet"></use>
                            </svg>
							<span class="basket-items__clear-title">
								<?=Loc::getMessage('SBB_CLEAR_BASKET')?>
							</span>
						</div>
					</div>
                    <div class="backet-big__titles">
                        <div class="backet-big__title-item backet-big__title-item--name"><?=Loc::getMessage('SBB_TITLE_NAME')?></div>
                        <?if ($usePriceInAdditionalColumn)
                            {
                                ?>
                                <div class="backet-big__title-item backet-big__title-item--price"><?=Loc::getMessage('SBB_TITLE_PRICE')?></div>
                                <?
                            }
                            ?>
                        <div class="backet-big__title-item backet-big__title-item--quantity"><?=Loc::getMessage('SBB_TITLE_AMOUNT')?></div>
                        <div class="backet-big__title-item backet-big__title-item--sum"><?=Loc::getMessage('SBB_TITLE_SUMM')?></div>
                        <div class="backet-big__title-item backet-big__title-item--close"></div>
                    </div>
					<div class="basket-items-list-container" id="basket-items-list-container">
						<div class="basket-items-list-overlay" id="basket-items-list-overlay" style="display: none;"></div>
						<div class="basket-items-list" id="basket-item-list">
							<div class="basket-search-not-found" id="basket-item-list-empty-result" style="display: none;">
								<div class="basket-search-not-found-icon"></div>
								<div class="basket-search-not-found-text">
									<?=Loc::getMessage('SBB_FILTER_EMPTY_RESULT')?>
								</div>
							</div>
							<table class="basket-items-list-table" id="basket-item-table"></table>
						</div>
					</div>
				</div>
			</div>
		</div>
		<?
		if (
			$arParams['BASKET_WITH_ORDER_INTEGRATION'] !== 'Y'
			&& in_array('bottom', $arParams['TOTAL_BLOCK_DISPLAY'])
		)
		{
			?>
			<div class="basket-root-row">
				<div class="basket-root-content" data-entity="basket-total-block"></div>
			</div>
			<?
		}
		?>
	</div>
	<?
	if (!empty($arResult['CURRENCIES']) && Main\Loader::includeModule('currency'))
	{
		CJSCore::Init('currency');

		?>
		<script>
			BX.Currency.setCurrencies(<?=CUtil::PhpToJSObject($arResult['CURRENCIES'], false, true, true)?>);
		</script>
		<?
	}

	$signer = new \Bitrix\Main\Security\Sign\Signer;
	$signedTemplate = $signer->sign($templateName, 'sale.basket.basket');
	$signedParams = $signer->sign(base64_encode(serialize($arParams)), 'sale.basket.basket');
	$messages = Loc::loadLanguageFile(__FILE__);

	?>
	<script>
		BX.message(<?=CUtil::PhpToJSObject($messages)?>);
		BX.Sale.BasketComponent.init({
			result: <?=CUtil::PhpToJSObject($arResult, false, false, true)?>,
			params: <?=CUtil::PhpToJSObject($arParams)?>,
			template: '<?=CUtil::JSEscape($signedTemplate)?>',
			signedParamsString: '<?=CUtil::JSEscape($signedParams)?>',
			siteId: '<?=CUtil::JSEscape($component->getSiteId())?>',
			ajaxUrl: '<?=CUtil::JSEscape($component->getPath().'/ajax.php')?>',
			templateFolder: '<?=CUtil::JSEscape($templateFolder)?>'
		});
		$(document).on('click','#order_oc',function(){
			$.ajax({
				url: '<?=SITE_DIR?>' + 'include/ajax/oc.php',
				type: 'POST',
				data:{'site_id':'<?=SITE_ID?>','basketData':'<?=base64_encode(serialize($arParams['OFFERS_PROPS']))?>'},
				success: function(html)
				{
					showModal(html);
				}
			});
		});
	</script>
	<?
	if ($arParams['USE_GIFTS'] === 'Y' && $arParams['GIFTS_PLACE'] === 'BOTTOM')
	{
		$APPLICATION->IncludeComponent(
			'bitrix:sale.products.gift.basket',
			'origami_default',
			$giftParameters,
			$component
		);
	}
}
elseif ($arResult['EMPTY_BASKET'])
{
	include(Main\Application::getDocumentRoot().$templateFolder.'/empty.php');
}
else
{
	ShowError($arResult['ERROR_MESSAGE']);
}
?>
