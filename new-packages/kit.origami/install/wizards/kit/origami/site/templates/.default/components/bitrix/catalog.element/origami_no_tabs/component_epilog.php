<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Loader;
use Sotbit\Origami\Helper\Config;
use Bitrix\Main\Localization\Loc;
use Sotbit\Origami\Config\Option;
use Bitrix\Main\Page\Asset;

Asset::getInstance()->addJs(SITE_DIR . "local/templates/sotbit_origami/assets/plugin/swiper5.2.0/js/swiper.js");
Asset::getInstance()->addJs(SITE_DIR . "local/templates/sotbit_origami/assets/js/custom-slider.js");
Asset::getInstance()->addCss(SITE_DIR . "local/templates/sotbit_origami/assets/plugin/swiper5.2.0/css/swiper.min.css");

Loc::loadMessages(__FILE__);

\Bitrix\Main\Page\Asset::getInstance()->addCss(SITE_TEMPLATE_PATH.'/assets/fonts/share/share.css');

/**
 * @var array $templateData
 * @var array $arParams
 * @var string $templateFolder
 * @global CMain $APPLICATION
 */

global $analogProducts;

\SotbitOrigami::checkOfferPage($arResult, $arParams);
\SotbitOrigami::getOffersSelected($arResult, $arParams);
\SotbitOrigami::checkOfferLanding($arResult, $arParams);


// get products in the current user's basket
if( \Bitrix\Main\Loader::includeModule('sotbit.schemaorg') && strpos($APPLICATION->GetCurPage(), "bitrix") === false )
{
    Sotbit\Schemaorg\EventHandlers::makeContent($APPLICATION->GetCurPage(false), 'Product');
    $data = SchemaMain::getData();
    if(!empty($data))
    {
        foreach ($data as $k => &$dat)
        {
            if ($dat['@type'] == 'Product')
            {
                $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";

                if (!empty($arResult['NAME']))
                    $dat['name'] = $arResult['NAME'];
                else {
                    $dat = [];
                    continue;
                }

                if (!empty($arResult['PREVIEW_TEXT']))
                    $dat['description'] = $arResult['PREVIEW_TEXT'];
                else if (!empty($arResult['DETAIL_TEXT']))
                    $dat['description'] = $arResult['DETAIL_TEXT'];

                (!empty($arResult['SKU']) ? $dat['sku'] = $arResult['SKU'] : "");

                if (!empty($arResult['PREVIEW_PICTURE']['SRC']))
                    $dat['image'] = $protocol . $_SERVER['SERVER_NAME'] . $arResult['PREVIEW_PICTURE']['SRC'];
                else if (!empty($arResult['DETAIL_PICTURE']['SRC']))
                    $dat['image'] = $protocol . $_SERVER['SERVER_NAME'] . $arResult['DETAIL_PICTURE']['SRC'];

                if (!empty($arResult['PROPERTIES']['rating']) && !empty($arResult['PROPERTIES']['rating']['VALUE'])) {
                    if (!isset($dat['aggregateRating']))
                        $dat['aggregateRating']['@type'] = 'AggregateRating';

                    (!empty($arResult['PROPERTIES']['rating']['NAME']) ? $dat['aggregateRating']['name'] = $arResult['PROPERTIES']['rating']['NAME'] : "");
                    (!empty($arResult['PROPERTIES']['rating']['VALUE']) ? $dat['aggregateRating']['ratingValue'] = $arResult['PROPERTIES']['rating']['VALUE'] : "");
                    (!empty($arResult['PROPERTIES']['vote_count']['VALUE']) ? $dat['aggregateRating']['ratingCount'] = $arResult['PROPERTIES']['vote_count']['VALUE'] : "");
                }

                if (!empty($arResult['OFFERS']) && !empty($OFFER['ITEM_PRICES'][0]['PRICE']))
                {
                    if (!isset($dat['offers']))
                        $dat['offers'][0]['@type'] = "Offers";

                    foreach ($arResult['OFFERS'] as $keyOf => $OFFER)
                    {
                        (!empty($OFFER['NAME']) ? $dat['offers'][$keyOf]['name'] = $OFFER['NAME'] : "");
                        ($OFFER['PRODUCT']['AVAILABLE'] == 'Y' ? $dat['offers'][$keyOf]['availability'] = "InStock" : "");
                        (!empty($OFFER['ITEM_PRICES'][0]['PRICE']) ? $dat['offers'][$keyOf]['price'] = $OFFER['ITEM_PRICES'][0]['PRICE'] : "");
                        (!empty($OFFER['ITEM_PRICES'][0]['CURRENCY']) ? $dat['offers'][$keyOf]['priceCurrency'] = $OFFER['ITEM_PRICES'][0]['CURRENCY'] : "");
                        (!empty($arResult['DETAIL_PAGE_URL']) ? $dat['offers'][$keyOf]['url'] = $protocol . $_SERVER['SERVER_NAME'] . $arResult['DETAIL_PAGE_URL'] : "");
                    }
                }

                if (is_array($arResult['BRAND']) && !empty($arResult['BRAND']))
                {
                    if (!isset($dat['brand']))
                        $dat['brand']['@type'] = "Brand";

                    foreach ($arResult['BRAND'] as $key => $brand)
                    {
                        (!empty($brand['NAME']) ? $dat['brand']['name'] = $brand['NAME'] : "");
                        (!empty($brand['SRC']) ? $dat['brand']['image'] = $protocol . $_SERVER['SERVER_NAME'] . $brand['SRC'] : "");
                        (!empty($brand['URL']) ? $dat['brand']['url'] = $protocol . $_SERVER['SERVER_NAME'] . $brand['URL'] : "");
                        break;
                    }
                }

                SchemaMain::setData($data);
            }
        }
    }
}

if(
    Option::get('SHOW_ANALOG_'.$template) == 'Y' &&
    $arResult['PROPERTIES'][Option::get('ANALOG_PROP_'.$template)]['VALUE']
){
    $analogProducts = $arResult['PROPERTIES'][Option::get('ANALOG_PROP_'.$template)]['VALUE'];
}

// get products in the current user's basket

$template = $this->__template->__name;
if ($template == '.default') {
    $template = '';
} else if ($template == 'origami_no_tabs') {
    $template = 'NO_TABS';
}

$showAddBtn = in_array('ADD', $arParams['ADD_TO_BASKET_ACTION']);
$showBuyBtn = in_array('BUY', $arParams['ADD_TO_BASKET_ACTION']);

if(Config::get('SHOW_PRICE_'.$template) == 'Y' && in_array(Config::get('SKU_TYPE_'.$template),['LIST_OF_MODIFICATIONS','COMBINED']))
{
	$arResult['STORES'] = [];

	if($arParams['STORES'] && $arResult['OFFERS'])
	{
	    $idOffers = [];
	    foreach($arResult['OFFERS'] as $i => $offer)
	    {
	        $idOffers[$offer['ID']] = $i;
	    }

	    $stores = [];
	    $rs = \CCatalogStore::getList([], ['ID' => $arParams['STORES']],false,false,['*','UF_*']);
	    while($store = $rs->Fetch())
        {
	        if($store['UF_EMAIL']){
	            $store['UF_EMAIL'] = unserialize($store['UF_EMAIL']);
	        }
	        if($store['UF_PHONE']){
	            $store['UF_PHONE'] = unserialize($store['UF_PHONE']);
	        }
	        if($store['UF_WORKTIME']){
	            $store['UF_WORKTIME'] = unserialize($store['UF_WORKTIME']);
	        }
	        if($store['IMAGE_ID'] > 0){
	            $store['IMAGE'] = \CFile::ResizeImageGet(
	                $store['IMAGE_ID'],
	                [
	                    'width'  => 65,
	                    'height' => 50,
	                ],
	                BX_RESIZE_IMAGE_PROPORTIONAL
	            );
	        }
	        $stores[$store['ID']] = $store;
	    }
	    $rs = CCatalogStoreProduct::GetList(Array(),['PRODUCT_ID' => array_keys($idOffers),'STORE_ID' => $arParams['STORES']],false,false,Array());
	    while($qnt = $rs->Fetch())
        {
	        if($qnt['AMOUNT'] == 0)
	        {
	            continue;
	        }
	        $arResult['STORES'][$qnt['PRODUCT_ID']][$qnt['STORE_ID']] = $stores[$qnt['STORE_ID']];

	        if($arParams['SHOW_MAX_QUANTITY'] == 'M')
	        {
	            $offer = $arResult['OFFERS'][$idOffers[$qnt['PRODUCT_ID']]];

				if($qnt['AMOUNT'] == 0){

				}
				elseif($qnt['AMOUNT']/reset($offer['ITEM_MEASURE_RATIOS'])['RATIO']<$arParams['RELATIVE_QUANTITY_FACTOR'])
				{
	                $qnt['AMOUNT'] = '<div class="product_card__block__presence_product_value_sufficient">'.
	                    Loc::getMessage('DETAIL_MODIFICATION_AMOUNT').':
						<span><i class="fas fa-check"></i> '.\Bitrix\Main\Localization\Loc::getMessage('CT_BCE_CATALOG_RELATIVE_QUANTITY_FEW').'</span>
					</div>';
				}
				else{
	                $qnt['AMOUNT'] = '<div class="product_card__block__presence_product_value_many">'.
						Loc::getMessage('DETAIL_MODIFICATION_AMOUNT').':
						<span><svg class="product-card_icon-check" width="11px" height="12px"><use xlink:href="/local/templates/sotbit_origami/assets/img/sprite.svg#icon_check_checkbox"></use>
						</svg> '.\Bitrix\Main\Localization\Loc::getMessage('CT_BCE_CATALOG_RELATIVE_QUANTITY_MANY').'</span>
					</div>';
				}
	        }
	        else{
	            if($qnt['AMOUNT'] == 0){

	            }
	            else{
	                $qnt['AMOUNT'] = '<div class="product_card__block__presence_product_value_many">'.
	                    Loc::getMessage('DETAIL_MODIFICATION_AMOUNT').':
						<span><svg class="product-card_icon-check" width="11px" height="12px"><use xlink:href="/local/templates/sotbit_origami/assets/img/sprite.svg#icon_check_checkbox"></use>
                                                                             </svg> '.$qnt['AMOUNT'].' '.$offer['ITEM_MEASURE']['TITLE'].'</span>
					</div>';
	            }
	        }
	        $arResult['STORES'][$qnt['PRODUCT_ID']][$qnt['STORE_ID']]['AMOUNT'] = $qnt['AMOUNT'];
	    }
	}
}
//$this->__template->SetViewTarget("element_prices");

?>
	<div id="element_prices_content" style="display:none;">
        <?php
        if(Config::get('SHOW_PRICE_'.$template) == 'Y' && in_array(Config::get('SKU_TYPE_'.$template),['LIST_OF_MODIFICATIONS','COMBINED'])){
	if($arResult['OFFERS']){
	    ?>
		<div class="row" id="<?=$itemIds['MODIFICATION_ID']?>">
		<div class="element_prices__wrapper">
			<p class="element_prices__wrapper-title puzzle_block__title fonts__middle_title">
		        <?=Loc::getMessage('DETAIL_MODIFICATION_TITLE')?>
			</p>
		<div class="main_info_detail_product-presence">
			<div class="product-presence__offer product-presence__offer-title">
				<div class="product-presence__img_and_buttom"></div>
				<div class="product-presence__offer-property">
					<div class="product-presence__name-block">
						<div class="product-presence__name">
		                    <?=Loc::getMessage('DETAIL_MODIFICATION_NAME')?>
						</div>
					</div>
		            <?
		            if($arResult['SKU_PROPS']){
		                ?>
						<div class="product-presence__property-block origami_main_scroll">
							<div class="product-presence__property-wrapper">
		                        <?
		                        foreach ($arResult['SKU_PROPS'] as $code =>$skuProperty) {
		                            ?>
									<div class="product-presence__property">
										<div><?=$skuProperty['NAME']?></div>
									</div>
		                            <?
		                        }
		                        ?>
							</div>
						</div>
		                <?
		            }
		            ?>
					<div class="product-presence__price">
		                <?=Loc::getMessage('DETAIL_MODIFICATION_PRICE')?>
                    </div>
                    <div class="product-presence__action">
                        <?if($arResult["SHOW_DELAY"]  || $arResult["SHOW_COMPARE"]):?>
                            <div class="product-presence__icons">
                                <div class="visually-hidden"><?=Loc::getMessage('DETAIL_MODIFICATION_WISH')?></div>
                            </div>
                        <?endif;?>
                        <div class="product-presence__basket">
                            <div class="product-detail-info-block-basket visually-hidden">
                                <?=Loc::getMessage('DETAIL_MODIFICATION_BASKET')?>
                            </div>
                        </div>
                    </div>
				</div>
			</div>
			<?
            $Offer = new \Sotbit\Origami\Helper\Offer(SITE_ID);
			foreach ($arResult['OFFERS'] as $offer){
			    ?>
				<div class="product-presence" data-id="<?=$offer['ID']?>">
					<div class="product-presence__offer">
						<div class="product-presence__img_and_buttom">
							<?
			                if($arResult['STORES'][$offer['ID']]){
			                    ?>
				                <div class="product-presence__buttom">
                                    <svg class="product-presence__buttom-icon" width="14" height="12">
                                        <use xlink:href="/local/templates/sotbit_origami/assets/img/sprite.svg#icon_dropdown_big"></use>
                                    </svg>
                                </div>
				                <?
			                }
							?>
							<div class="product-presence__img">
			                    <?
			                    $photo = reset($offer['MORE_PHOTO']);
			                    $photoSmall = ($photo['SMALL']) ? $photo['SMALL'] : $photo['ORIGINAL'];
			                    ?>
								<img src="<?=$photoSmall['SRC']?>" alt="<?=$offer['NAME']?>" title="<?=$offer['NAME']?>">
                            </div>
						</div>
						<div class="product-presence__offer-property">
							<div class="product-presence__name-block">
								<div class="product-presence__name">
			                        <?
			                        $tmpResult = $arResult;

                                    $tmpResult['OFFERS'] = [$offer];
			                        $name = $Offer->changeText($tmpResult,$arParams['OFFER_TREE_PROPS']);
			                        echo $name;
			                        //$offer['NAME']?>
								</div>
								<?
								$qnt = '';
			                    if($arParams['SHOW_MAX_QUANTITY'] == 'M'){
			                        if($offer['CATALOG_QUANTITY'] == 0){
			                            $qnt = '<span class="product_card__block__presence_product_value_no"><i class="icon-no-waiting"></i> '.\Bitrix\Main\Localization\Loc::getMessage('CT_BCE_CATALOG_RELATIVE_QUANTITY_NO').'</span>';
			                        }
			                        elseif($offer['CATALOG_QUANTITY']/reset($offer['ITEM_MEASURE_RATIOS'])['RATIO']<$arParams['RELATIVE_QUANTITY_FACTOR']){
			                            $qnt = '<span class="product_card__block__presence_product_value_sufficient"><i class="fas fa-check"></i> '.\Bitrix\Main\Localization\Loc::getMessage('CT_BCE_CATALOG_RELATIVE_QUANTITY_FEW').'</span>';
			                        }
			                        else{
			                            $qnt = '<span class="product_card__block__presence_product_value_many"><svg class="product-card_icon-check" width="11px" height="12px"><use xlink:href="/local/templates/sotbit_origami/assets/img/sprite.svg#icon_check_checkbox"></use>
                                                                             </svg> '.\Bitrix\Main\Localization\Loc::getMessage('CT_BCE_CATALOG_RELATIVE_QUANTITY_MANY').'</span>';
			                        }
			                    }
			                    else{
			                        if($offer['CATALOG_QUANTITY'] == 0){
			                            $qnt= '<span class="product_card__block__presence_product_value_no"><i class="icon-no-waiting"></i> '.$offer['CATALOG_QUANTITY'].' '.$offer['ITEM_MEASURE']['TITLE'].'</span>';
			                        }
			                        else{
			                            $qnt= '<span class="product_card__block__presence_product_value_many"><svg class="product-card_icon-check" width="11px" height="12px"><use xlink:href="/local/templates/sotbit_origami/assets/img/sprite.svg#icon_check_checkbox"></use>
                                                                             </svg> '.$offer['CATALOG_QUANTITY'].' '.$offer['ITEM_MEASURE']['TITLE'].'</span>';
			                        }
			                    }
			                    echo $qnt;
								?>
			                    <?
			                    if($offer['PROPERTIES'][Config::get('ARTICUL_OFFER')]['VALUE']){

				                    $vendorCode = $offer['PROPERTIES'][Config::get('ARTICUL_OFFER')];
				                    ?>
									<div class="product_detail_info_block__article">
										<span class="product_detail_info_block__title"><?=$vendorCode['NAME']?>:</span>
										<span><?=$vendorCode['VALUE']?></span>
									</div>
									<?
			                    }
			                    elseif ($arResult['PROPERTIES'][Config::get('ARTICUL')]['VALUE']){
			                        $vendorCode = $arResult['PROPERTIES'][Config::get('ARTICUL')];
			                        ?>
									<div class="product_detail_info_block__article">
										<span class="product_detail_info_block__title"><?=$vendorCode['NAME']?>:</span>
										<span><?=$vendorCode['VALUE']?></span>
									</div>
			                    <?}?>
							</div>
			                <?
			                if($arResult['SKU_PROPS']){
			                    ?>
								<div class="product-presence__property-block origami_main_scroll">
                                    <div class="product-presence__property-btn"><?=Loc::getMessage('CT_BCE_CATALOG_PROPERTIES')?>
                                        <svg class="site-navigation__item-icon" width="7" height="7">
                                            <use xlink:href="/local/templates/sotbit_origami/assets/img/sprite.svg#icon_dropdown_big"></use>
                                        </svg>
                                    </div>
									<div class="product-presence__property-wrapper">
			                            <?
			                            foreach ($arResult['SKU_PROPS'] as $code =>$skuProperty) {
                                            ?>
											<div class="product-presence__property">
                                                <div class="product-presence__property-name-mobile"><?=$skuProperty['NAME']?></div>
												<div><?=($offer['PROPERTIES'][$code]['DISPLAY_VALUE'])?$offer['PROPERTIES'][$code]['DISPLAY_VALUE']:$offer['PROPERTIES'][$code]['VALUE']?></div>
											</div>
			                                <?
			                            }
			                            ?>
									</div>
								</div>
			                    <?
			                }
			                ?>
							<div class="product-presence__price">
								<div class="product-detail-price-and-cheaper">
			                        <?php
			                        if ($arParams["FILL_ITEM_ALL_PRICES"] == "Y")
			                        {
			                            ?>
										<div class="product_card__other_prices">
			                                <?
			                                foreach ($offer['ITEM_ALL_PRICES'][$offer["ITEM_PRICE_SELECTED"]]["PRICES"] as $id => $customPrice)
			                                {
			                                    ?>
												<div class="product_card__other_prices_row">
													<div class="product-detail-title-price fonts__middle_comment">
			                                            <?= $arResult['ALL_PRICES_NAMES'][$id] ?>
													</div>
													<div class="product_card__block__new_price_product">
			                                            <?= $customPrice['PRINT_PRICE'] ?>
													</div>
                                                    <?
                                                    if ($arParams['SHOW_OLD_PRICE'] === 'Y' && $customPrice["DISCOUNT"])
                                                    {
                                                        ?>
                                                        <div class="product_card__block__old_price_product">
                                                            <?=$customPrice['PRINT_BASE_PRICE'] ?>
                                                        </div>
                                                    <?}
                                                    if ($arParams['SHOW_OLD_PRICE'] === 'Y' && $customPrice["DISCOUNT"] > 0)
                                                    {
                                                        ?>
                                                        <div class="product_card__block_saving">
                                                            <div><?= Loc::getMessage('DETAIL_SAVE') ?></div>
                                                            <span class="product_card__block_saving_title">
				                                            <?=$customPrice['PRINT_DISCOUNT'] ?>
			                                            </span>
                                                        </div>
                                                    <? }
                                                    ?>
												</div>
			                                    <?
			                                }
			                                ?>
										</div>
			                            <?
			                        }
									elseif($offer['ITEM_PRICES']){
			                            ?>
										<div class="product_card__other_prices">
											<div class="product_card__other_prices_row">
												<div class="product-detail-title-price fonts__middle_comment">

												</div>
												<div class="product_card__block__new_price_product">
			                                        <?= $offer['ITEM_PRICES'][$offer["ITEM_PRICE_SELECTED"]]['PRINT_PRICE'] ?>
												</div>
			                                    <?php
			                                    if ($arParams['SHOW_OLD_PRICE'] === 'Y')
			                                    {
			                                        ?>
													<div class="product_card__block__old_price_product"
													     style="display: <?= ($showDiscount ? '' : 'none') ?>;">
			                                            <?= $offer['ITEM_PRICES'][$offer["ITEM_PRICE_SELECTED"]]['PRINT_BASE_PRICE'] ?>
													</div>
			                                    <? }
			                                    if ($arParams['SHOW_OLD_PRICE'] === 'Y' && $offer['ITEM_PRICES'][0]['DISCOUNT'] > 0)
			                                    {
			                                        ?>
													<div class="product_card__block_saving">
														<div><?= Loc::getMessage('DETAIL_SAVE') ?></div>
														<span class="product_card__block_saving_title">
				                                            <?= $offer['ITEM_PRICES'][$offer["ITEM_PRICE_SELECTED"]]['PRINT_DISCOUNT'] ?>
			                                            </span>
													</div>
			                                    <? }
			                                    ?>
											</div>
										</div>
			                            <?
			                        }?>
								</div>
                            </div>
                            <div class="product-presence__action">
                                <?if($arResult["SHOW_DELAY"]  || $arResult["SHOW_COMPARE"]):?>
                                    <div class="product-presence__icons">
                                        <?
                                        if ($arResult["SHOW_COMPARE"] ) {
                                            ?>
                                            <span class="product-presence__icons-bar" data-entity="compare-checkbox-modification">
                                                <svg width="16" height="16">
                                                    <use xlink:href="/local/templates/sotbit_origami/assets/img/sprite.svg#icon_compare"></use>
                                                </svg>

                                            </span>
                                            <?
                                        }
                                        ?>
                                        <?if($arResult["SHOW_DELAY"] && $offer['CAN_BUY'] == 'Y'):?>
                                            <span class="product-presence__icons-heart"
                                            data-entity="wish_modification">
                                                <svg width="16" height="16">
                                                    <use xlink:href="/local/templates/sotbit_origami/assets/img/sprite.svg#icon_favourite"></use>
                                                </svg>
                                            </span>
                                        <?endif;?>
                                    </div>
                                <?endif;?>
                                <?if($arResult["SHOW_BUY"]):?>
                                <div class="product-presence__basket">
                                    <div class="product-detail-info-block-basket">
                                        <?
                                        if ($arParams['USE_PRODUCT_QUANTITY']
                                            && ($offer['ITEM_PRICES'][0]['MIN_QUANTITY'] > 0 && $offer['CAN_BUY'] )
                                        ) {
                                            ?>
                                            <div class="product_card__block_buy_quantity product-detail-info-block-basket-item">
                                                <span class="product_card__block_buy_quantity__minus fonts__small_title" data-entity="modification-minus">&#8722;</span>
                                                <input class="product_card__block_buy_quantity__input"
                                                    type="number"
                                                    placeholder=""
                                                    min="<?= $offer['ITEM_PRICES'][0]['MIN_QUANTITY'] ?>"
                                                    step="<?= $offer['ITEM_PRICES'][0]['MIN_QUANTITY'] ?>"
                                                    data-ratio="<?= $offer['ITEM_PRICES'][0]['MIN_QUANTITY'] ?>"
                                                    value="<?= $offer['ITEM_PRICES'][0]['MIN_QUANTITY'] ?>">
                                                <span class="product_card__block_buy_quantity__plus fonts__small_title" data-entity="modification-plus">+</span>
                                            </div>
                                        <? }
                                        if($offer['CAN_BUY'] == 'Y'){
                                            ?>
                                            <div class="detail-basket-wrapper product-detail-info-block-basket-item">
                                                <?
                                                if ($showAddBtn) {
                                                    ?>
                                                    <a class="main_btn sweep-to-right" data-entity="modification-addbasket"
                                                    href="javascript:void(0);"><?= Loc::getMessage('CT_BCE_CATALOG_ADD') ?>
                                                    </a>
                                                    <?
                                                }
                                                if ($showBuyBtn) {
                                                    ?>
                                                    <a class="main_btn sweep-to-right" data-entity="modification-buybasket"
                                                    href="javascript:void(0);"> <?= Loc::getMessage('CT_BCE_CATALOG_ADD') ?>
                                                    </a>
                                                    <?
                                                }
                                                ?>
                                            </div>
                                            <?
                                        }
                                        else{

                                            $APPLICATION->IncludeComponent(
                                                'bitrix:catalog.product.subscribe',
                                                'origami_default',
                                                [
                                                    'CUSTOM_SITE_ID'     => isset($arParams['CUSTOM_SITE_ID'])
                                                        ? $arParams['CUSTOM_SITE_ID']
                                                        : null,
                                                    'PRODUCT_ID'         => $offer['ID'],
                                                    'BUTTON_ID'          => "subscribe_".$offer['ID'],
                                                    'BUTTON_CLASS'       => 'btn main_btn product-item-detail-buy-button',
                                                    'DEFAULT_DISPLAY'    => !$offer['CAN_BUY'],
                                                    'MESS_BTN_SUBSCRIBE' => $arParams['~MESS_BTN_SUBSCRIBE'],
                                                ],
                                                $component,
                                                ['HIDE_ICONS' => 'Y']
                                            );
                                        }
                                        ?>

                                        <? if ($offer['CAN_BUY'] && Config::get('SHOW_BUY_OC_'.$template) == 'Y' && \Bitrix\Main\Loader::includeModule('sotbit.orderphone')): ?>
                                                <div
                                                    class="product-detail-info-block-one-click-basket product-detail-info-block-basket-item"
                                                    data-entity="modification-buyoc">
                                                    <div class="one_click_btn"><?= Loc::getMessage('DETAIL_BUY_OC') ?></div>
                                                </div>
                                        <? endif; ?>
                                    </div>
                                    <div class="product-detail-info-block-path-to-basket">
                                        <a href="<?= Config::get('BASKET_PAGE') ?>"
                                        class="in_basket">
                                            <span></span><?= Loc::getMessage('DETAIL_PRODUCT_IN_BASKET') ?>
                                        </a>
                                    </div>
                                </div>
                                <?endif;?>
                            </div>
						</div>
					</div>
			        <?
			        if($arResult['STORES'][$offer['ID']]){
			            ?>
						<div class="product-presence__address">
			                <?
			                foreach($arResult['STORES'][$offer['ID']] as $store){
			                    ?>
								<div class="product-presence__address-item">
									<?
									if($store['IMAGE']){
										?>
										<div class="product-presence__address-img">
											<img src="<?=$store['IMAGE']['src']?>" alt="">
										</div>
										<?
									}
									?>

									<div class="product-presence__address-rest">
										<div class="product-presence__address-name-block">
											<div class="product-presence__address-name">
												<?=$store['TITLE']?>
											</div>
			                                <?=$store['AMOUNT']?>

										</div>
										<?
										if($store['UF_WORKTIME'] && is_array($store['UF_WORKTIME'])){
											?>
											<div class="product-presence__address-time">
											<?
											foreach($store['UF_WORKTIME'] as $time){
												?>
												<div><?=$time?></div>
												<?
											}
											?>
											</div>
											<?
										}
										if($store['UF_METRO']){
											?>
											<div class="product-presence__address-metro">
                                            <svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                                                    width="12px" height="12px" viewBox="0 0 95.44 95.441" style="enable-background:new 0 0 95.44 95.441;"
                                                    xml:space="preserve">
                                                <g>
                                                    <g>
                                                        <path d="M47.721,11.712C21.366,11.712,0,33.281,0,59.888c0,14.427,6.226,23.836,6.226,23.836l82.97,0.004
                                                            c0,0,6.244-10.283,6.244-23.841C95.44,33.281,74.074,11.712,47.721,11.712z M85.696,77.859L9.897,77.826
                                                            c0,0-4.039-7.649-4.039-18.09c0-23.32,18.814-42.226,42.023-42.226c23.208,0,42.023,18.905,42.023,42.226
                                                            C89.905,70.295,85.696,77.859,85.696,77.859z"/>
                                                        <polygon points="61.207,24.505 60.608,24.504 47.879,49.447 34.692,24.291 16.637,69.736 11.971,69.736 11.971,73.386
                                                            37.533,73.386 37.533,69.736 32.461,69.736 37.533,55.533 47.879,73.386 57.82,55.533 62.891,69.736 57.82,69.736 57.82,73.386
                                                            83.178,73.386 83.178,69.736 78.785,69.736 		"/>
                                                    </g>
                                                </g>

                                                </svg>
                                                 <?=$store['UF_METRO']?>
											</div>
											<?
										}
			                            if($store['ADDRESS']){
			                                ?>
				                            <div class="product-presence__address-city">
					                            <?=$store['ADDRESS']?>
				                            </div>
				                            <?
			                            }
			                            if($store['UF_EMAIL'] && is_array($store['UF_EMAIL'])){
			                                ?>
				                            <div class="product-presence__address-email">
					                            <?
					                            foreach($store['UF_EMAIL'] as $email){
					                                ?>
						                            <a class="product-presence__address-email" href="mailto:<?=$email?>">
							                            <?=$email?>
						                            </a>
						                            <?
					                            }
					                            ?>
				                            </div>
				                            <?
			                            }
			                            if($store['UF_PHONE'] && is_array($store['UF_PHONE'])){
			                                ?>
				                            <div class="product-presence__address-phone">
					                            <?
					                            foreach($store['UF_PHONE'] as $phone){
					                                ?>
						                            <a class="product-presence__address-link" href="tel:<?=\SotbitOrigami::showDigitalPhone($phone)?>"><?=$phone?></a>
						                            <?
					                            }
					                            ?>
				                            </div>
				                            <?
			                            }
										?>
									</div>
								</div>
			                    <?
			                }
			                ?>
						</div>
			            <?
			        }
			        ?>
				</div>
			    <?
				}
				?>
		 	</div>
		</div>
	</div>
<?
	}
        }
?>
	</div>
	<div id="element_tab_available_content" style="display:none;">
        <?php
        //$this->__template->SetViewTarget("element_tab_available");


if(Config::get('ACTIVE_TAB_AVAILABLE_'.$template) == 'Y'){         //<===HARD

    $stores = [];
    $rs = \CCatalogStore::getList([], ['ID' => $arParams['STORES']],false,false,['*','UF_*']);
    while($store = $rs->Fetch()){
        if($store['UF_EMAIL']){
            $store['UF_EMAIL'] = unserialize($store['UF_EMAIL']);
        }
        if($store['UF_PHONE']){
            $store['UF_PHONE'] = unserialize($store['UF_PHONE']);
        }
        if($store['UF_WORKTIME']){
            $store['UF_WORKTIME'] = unserialize($store['UF_WORKTIME']);
        }
        if($store['IMAGE_ID'] > 0){
            $store['IMAGE'] = \CFile::ResizeImageGet(
                $store['IMAGE_ID'],
                [
                    'width'  => 65,
                    'height' => 50,
                ],
                BX_RESIZE_IMAGE_PROPORTIONAL
            );
        }
        $stores[$store['ID']] = $store;
    }
    if(CCatalogSKU::IsExistOffers($arResult['ID'], $arResult['IBLOCK_ID'])){
        $arItemsSKU = CCatalogSKU::getOffersList([$arResult['ID']], $arResult['IBLOCK_ID'], array(), array());
        foreach ($arItemsSKU[$arResult['ID']] as $item) {
            $dbItem = CCatalogStoreProduct::GetList(Array(),['PRODUCT_ID' => $item,'STORE_ID' => $arParams['STORES']],false,false,Array());
            while($el = $dbItem->Fetch())
            {
                $arItems[] = $el;
            }
        }
    }else{
        $rs = CCatalogStoreProduct::GetList(Array(),['PRODUCT_ID' => $arResult['ID'],'STORE_ID' => $arParams['STORES']],false,false,Array());
        while($qnt = $rs->Fetch()){
            $arItems[] = $qnt;
        }
    }

    foreach ($arItems as $qnt) {
    	$store = $stores[$qnt['STORE_ID']];
    	if(!$qnt['AMOUNT']){
    		continue;
        }
		?>
        <div class="availability-item" data-id="<?=$qnt['PRODUCT_ID']?>">
            <?if($store['IMAGE']['src']):?>
                <div class="availability-item__img-wrapper">
                    <img src="<?=$store['IMAGE']['src']?>" alt="<?=$store['IMAGE']['src']?>" title="<?=$store['IMAGE']['src']?>">
                </div>
            <?endif;?>
            <div class="availability-item__content-tile-wrapper availability-item__content-tile-wrapper--tablet">
                    <p class="availability-item__content-title"><?=($store['ADDRESS']) ? $store['ADDRESS'] : '-';?></p>
                    <a href="#map-test" class="availability-item__content-link-map">
                        <svg class="availability-item__content-link-icon" width="18" height="18">
                            <use xlink:href="/local/templates/sotbit_origami/assets/img/sprite.svg#icon_location_small"></use>
                        </svg>
                        <?=Loc::getMessage('DETAIL_AVAILABILITY_ON_MAP');?>
                    </a>
            </div>
            <div class="availability-item__content">
                <div class="availability-item__content-tile-wrapper availability-item__content-tile-wrapper--desk">

                        <p class="availability-item__content-title"><?=($store['ADDRESS']) ? $store['ADDRESS'] : '-';?></p>
                        <a href="#map-test" class="availability-item__content-link-map">
                            <svg class="availability-item__content-link-icon" width="18" height="18">
                                <use xlink:href="/local/templates/sotbit_origami/assets/img/sprite.svg#icon_location_small"></use>
                            </svg>
                            <?=Loc::getMessage('DETAIL_AVAILABILITY_ON_MAP');?>
                        </a>
                </div>
                <div class="availability-item__content-info">
                    <?
                    if($arParams['SHOW_MAX_QUANTITY'] == 'M'){
                        if($qnt['AMOUNT'] == 0){
                            $qnt['AMOUNT'] = '
                            <div class="availability-item__amount availability-item__amount--lot">
                                <p class="availability-item__title">'.Loc::getMessage('DETAIL_MODIFICATION_AMOUNT').'</p>
                                <p class="availability-item__amount-content"> - </p>
                            </div>';
                        }
                        elseif($qnt['AMOUNT']/reset($arResult['ITEM_MEASURE_RATIOS'])['RATIO']<$arParams['RELATIVE_QUANTITY_FACTOR'])
                        {
                            $qnt['AMOUNT'] = '
                        <div class="availability-item__amount availability-item__amount--few">
                            <p class="availability-item__title">'.Loc::getMessage('DETAIL_MODIFICATION_AMOUNT').'</p>
                            <p class="availability-item__amount-content">
                            <svg class="availability-item__amount-content-icon" width="10" height="10">
                                <use xlink:href="/local/templates/sotbit_origami/assets/img/sprite.svg#icon_not_available"></use>
                            </svg>'.\Bitrix\Main\Localization\Loc::getMessage('CT_BCE_CATALOG_RELATIVE_QUANTITY_FEW').'</p>
                        </div>';
                        }
                        else{
                            $qnt['AMOUNT'] = '
                    <div class="availability-item__amount availability-item__amount--lot">
                        <p class="availability-item__title">'.Loc::getMessage('DETAIL_MODIFICATION_AMOUNT').'</p>
                        <p class="availability-item__amount-content">
                           <svg class="availability-item__amount-content-icon" width="10" height="10">
                                <use xlink:href="/local/templates/sotbit_origami/assets/img/sprite.svg#icon_check_availability_small"></use>
                            </svg>'.\Bitrix\Main\Localization\Loc::getMessage('CT_BCE_CATALOG_RELATIVE_QUANTITY_MANY').'
                           </p>
                    </div>';
                        }
                    }
                    else{
                        if($qnt['AMOUNT'] == 0){

                        }
                        else{
                            $qnt['AMOUNT'] = '
                        <div class="availability-item__amount availability-item__amount--lot">
                            <p class="availability-item__title">'.Loc::getMessage('DETAIL_MODIFICATION_AMOUNT').'</p>

                            <p class="availability-item__amount-content">'.$qnt['AMOUNT'].' '.$arResult['ITEM_MEASURE']['TITLE'].'</p>
                        </div>';
                        }
                    }
                    ?>
                    <?=$qnt['AMOUNT']?>

                    <div class="availability-item__metro">
                        <p class="availability-item__title"><?=Loc::getMessage('DETAIL_AVAILABILITY_METRO')?></p>
                        <div class="availability-item__metro">
                            <p class="detailed-tabs__availability-metro-name"><?=($store['UF_METRO']) ? $store['UF_METRO'] : '-';?></p>
                        </div>
                    </div>

                    <div class="availability-item__contact">
                        <p class="availability-item__title"><?=Loc::getMessage('DETAIL_AVAILABILITY_CONTACT_INFO')?></p>
                        <div class="vailability-item__contact-phone">
                            <?
                            if($store['UF_PHONE']) {
                                foreach($store['UF_PHONE'] as $phone){
                                    ?>
                                    <p class="vailability-item__contact-phone-item"><?=$phone?></p>
                                    <?
                                }
                            } else {
                            ?>
                                <p class="vailability-item__contact-phone-item">-</p>
                            <?}?>
                        </div>
                        <?
                        if($store['UF_EMAIL']){
                            ?>
                            <div class="availability-item__email">
                                <?foreach($store['UF_EMAIL'] as $email){
                                    ?>
                                    <a href="mailto:<?=$email?>" class="availability-item__email-name main-color-txt"><?=$email?></a>
                                    <?
                                }?>
                            </div>
                            <?
                        } else {?>
                            <a href="javascript:void(0)" class="availability-item__email-name main-color-txt">-</a>
                        <?}?>
                    </div>

                    <div class="availability-item__time">
                        <p class="availability-item__title"><?=Loc::getMessage('DETAIL_AVAILABILITY_OPERATING_MODE')?></p>
                        <?
                        if($store['UF_WORKTIME'] && is_array($store['UF_WORKTIME'])){
                            ?>
                            <div class="detailed-tabs__availability-time">
                                <?
                                foreach($store['UF_WORKTIME'] as $time){
                                    ?>
                                    <p class="detailed-tabs__availability-time-item"><?=$time?></p>
                                    <?
                                }
                                ?>
                            </div>
                            <?
                        } else {?>
                        <div class="detailed-tabs__availability-time">
                            <p class="detailed-tabs__availability-time-item">-</p>
                        </div>
                        <?}?>
                    </div>
                    <div class="availability-item__btn-more">
                        <span class="availability-item__btn-more-open"><?=Loc::getMessage('DETAIL_AVAILABILITY_BTN_MORE')?></span>
                        <span class="availability-item__btn-more-close"><?=Loc::getMessage('DETAIL_AVAILABILITY_BTN_LESS')?></span>
                        <svg class="availability-item__btn-more-icon" width="10" height="6">
                            <use xlink:href="/local/templates/sotbit_origami/assets/img/sprite.svg#icon_dropdown_up_small"></use>
                        </svg>
                    </div>
                </div>
            </div>
        </div>
	    <?
    }
}
?>
	</div>
    <script>
        function toggleAvailability() {
            const listItems = Array.prototype.slice.call(document.querySelectorAll('.availability-item__content'));
            if (listItems.length === 0) {
                return;
            }
            listItems.forEach(function (item) {
                const infoBlock = item.querySelector('.availability-item__content-info');
                const btnShow = item.querySelector('.availability-item__btn-more');
                let heightStart = infoBlock.offsetHeight;
                let heightVisible = item.querySelector('.availability-item__amount').offsetHeight;
                if (heightStart !== heightVisible) {
                    infoBlock.classList.add('show-btn');
                    infoBlock.style.height = heightVisible + 'px';
                }

                function handlerClick (evt) {
                    if (!infoBlock.classList.contains('open')) {
                        infoBlock.classList.add('open');
                        infoBlock.style.height = heightStart + 'px';
                    } else {
                        infoBlock.classList.remove('open');
                        infoBlock.style.height = heightVisible + 'px';
                    }
                }

                btnShow.addEventListener('click', handlerClick);

                window.addEventListener('resize', function () {
                    if (window.innerWidth > 768) {
                        infoBlock.classList.remove('show-btn');
                        infoBlock.style.height = 'auto';
                    }
                    if (window.innerWidth <= 768) {
                        infoBlock.style.height = 'auto';
                        heightStart = infoBlock.offsetHeight;
                        heightVisible = item.querySelector('.availability-item__amount').offsetHeight;
                        if (heightStart !== heightVisible) {
                            infoBlock.classList.add('show-btn');
                            infoBlock.style.height = heightVisible + 'px';
                        }
                    }
                });
            });
        };



        document.addEventListener('DOMContentLoaded', function () {
            let btn = $(".availability-item__content-link-map");
            if(btn) {
                btn.on("click", function (event) {
                    event.preventDefault();
                    let id  = $(this).attr('href'),
                        top = $(id).offset().top;

                    let panelBX = document.querySelector('#bx-panel');
                    if(panelBX) {
                        let heightPanelBx = panelBX.offsetHeight;
                        top -=  heightPanelBx;
                    } else {
                        top -= 70;
                    }
                    $('body,html').animate({scrollTop: top}, 1000);
                });
            }
        });
    </script>
<?php

global $APPLICATION;

if (!empty($templateData['TEMPLATE_LIBRARY']))
{
	$loadCurrency = false;

	if (!empty($templateData['CURRENCIES']))
	{
		$loadCurrency = Loader::includeModule('currency');
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

if (isset($templateData['JS_OBJ']))
{
	?>
	<script>
		BX.ready(BX.defer(function(){
			if (!!window.<?=$templateData['JS_OBJ']?>)
			{
				window.<?=$templateData['JS_OBJ']?>.allowViewedCount(true);
			}
		}));
	</script>
	<?
	// select target offer
	$request = Bitrix\Main\Application::getInstance()->getContext()->getRequest();
	$offerNum = false;
	$offerId = (int)$this->request->get('OFFER_ID');
	$offerCode = $this->request->get('OFFER_CODE');

	if ($offerId > 0 && !empty($templateData['OFFER_IDS']) && is_array($templateData['OFFER_IDS']))
	{
		$offerNum = array_search($offerId, $templateData['OFFER_IDS']);
	}
	elseif (!empty($offerCode) && !empty($templateData['OFFER_CODES']) && is_array($templateData['OFFER_CODES']))
	{
		$offerNum = array_search($offerCode, $templateData['OFFER_CODES']);
	}

	if (!empty($offerNum))
	{
		?>
		<script>
			BX.ready(function(){
				if (!!window.<?=$templateData['JS_OBJ']?>)
				{
					window.<?=$templateData['JS_OBJ']?>.setOffer(<?=$offerNum?>);
				}
			});
		</script>
		<?
	}
}

global $filterAdvantages;
$filterAdvantages = ['PROPERTY_SECTION' => $arResult['ADVANTAGES_SECTIONS']];
$useRegion = (Config::get('USE_REGIONS') == 'Y') ? true : false;
if ($useRegion && $_SESSION['SOTBIT_REGIONS']['ID']) {
    $filterAdvantages['PROPERTY_REGIONS'] = [
        false,
        $_SESSION['SOTBIT_REGIONS']['ID'],
    ];
}
//$this->__template->SetViewTarget("element_advantages");
?>
	<div id="element_advantages_content" style="display:none;">
        <?php
$APPLICATION->IncludeComponent(
    "bitrix:news.list",
    "origami_advantages_simple",
    [
        "ACTIVE_DATE_FORMAT"              => "d.m.Y",
        "ADD_SECTIONS_CHAIN"              => "N",
        "AJAX_MODE"                       => "N",
        "AJAX_OPTION_ADDITIONAL"          => "",
        "AJAX_OPTION_HISTORY"             => "N",
        "AJAX_OPTION_JUMP"                => "N",
        "AJAX_OPTION_STYLE"               => "Y",
        "CACHE_FILTER"                    => "N",
        "CACHE_GROUPS"                    => "Y",
        "CACHE_TIME"                      => "36000000",
        "CACHE_TYPE"                      => "A",
        "CHECK_DATES"                     => "Y",
        "COMPOSITE_FRAME_MODE"            => "A",
        "COMPOSITE_FRAME_TYPE"            => "AUTO",
        "DETAIL_URL"                      => "",
        "DISPLAY_BOTTOM_PAGER"            => "Y",
        "DISPLAY_DATE"                    => "Y",
        "DISPLAY_NAME"                    => "Y",
        "DISPLAY_PICTURE"                 => "Y",
        "DISPLAY_PREVIEW_TEXT"            => "Y",
        "DISPLAY_TOP_PAGER"               => "N",
        "FIELD_CODE"                      => [
            0 => "NAME",
            1 => "PREVIEW_PICTURE",
        ],
        "FILTER_NAME"                     => "filterAdvantages",
        "HIDE_LINK_WHEN_NO_DETAIL"        => "N",
        "IBLOCK_ID"                       => Config::get('IBLOCK_ID_ADVANTAGES'),
        "IBLOCK_TYPE"                     => Config::get('IBLOCK_TYPE_ADVANTAGES'),
        "INCLUDE_IBLOCK_INTO_CHAIN"       => "N",
        "INCLUDE_SUBSECTIONS"             => "Y",
        "MESSAGE_404"                     => "",
        "NEWS_COUNT"                      => "4",
        "PAGER_BASE_LINK_ENABLE"          => "N",
        "PAGER_DESC_NUMBERING"            => "N",
        "PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
        "PAGER_SHOW_ALL"                  => "N",
        "PAGER_SHOW_ALWAYS"               => "N",
        "PAGER_TEMPLATE"                  => ".default",
        "PAGER_TITLE"                     => "Преимущества",
        "PARENT_SECTION"                  => "",
        "PARENT_SECTION_CODE"             => "",
        "PREVIEW_TRUNCATE_LEN"            => "",
        "PROPERTY_CODE"                   => [
            0 => "URL",
            1 => "",
        ],
        "SET_BROWSER_TITLE"               => "Y",
        "SET_LAST_MODIFIED"               => "N",
        "SET_META_DESCRIPTION"            => "Y",
        "SET_META_KEYWORDS"               => "Y",
        "SET_STATUS_404"                  => "N",
        "SET_TITLE"                       => "N",
        "SHOW_404"                        => "N",
        "SORT_BY1"                        => "ACTIVE_FROM",
        "SORT_BY2"                        => "SORT",
        "SORT_ORDER1"                     => "DESC",
        "SORT_ORDER2"                     => "ASC",
        "STRICT_SECTION_CHECK"            => "N",
        "COMPONENT_TEMPLATE"              => "sotbit_advantages_simple",
    ],
    false
);
?>
	</div>
	<script>
		$('#element_advantages').html($('#element_advantages_content').html());
        $('#element_advantages_content').remove();
        $('#element_tab_delivery').html($('#element_tab_delivery_content').html());
        $('#element_tab_delivery_content').remove();
        if($('#element_tab_available_content').html().trim() == 0){
            $('#TAB_AVAILABLE').remove();
            $('.detailed-tabs__availability').remove();
        }
        else{
            $('#element_tab_available').html($('#element_tab_available_content').html());
        }
        $('#element_tab_available_content').remove();
        $('#element_prices').html($('#element_prices_content').html());
        $('#element_prices_content').remove();

        let itemsTabs = Array.prototype.slice.call(document.querySelectorAll('.detailed-feat__menu-item'));
        if (itemsTabs) {
            itemsTabs.forEach(function (item) {
                let itemID = item.children[0].getAttribute('href');
                if (!document.querySelector(itemID)) {
                    item.parentElement.removeChild(item);
                }
            });
        }

        let weel = new WheelOfFortune('.detailed-feat__menu', {
            classBtnOpen: 'detail-menu-open'
        });
        let fixedBlock = new FixedBlock('.detailed-feat__menu', {
            marginBottom: 100,
            marginTop: 40
        });
        let isShow = true;
        if (window.innerWidth > 1024 && isShow) {
            weel.destroy();
            fixedBlock.init();
            isShow = false;
        }
        window.addEventListener('resize', function () {
            if (window.innerWidth < 1024 && !isShow) {
                fixedBlock.destroy();
                weel.init();
                isShow = true;
            }
            if (window.innerWidth > 1024 && isShow) {
                weel.destroy();
                fixedBlock.init();
                isShow = false;
            }
        });

         function scrollTo (item) {
            var btnScroll = $(item);
            if(btnScroll) {
                function scroll(event) {
                    event.preventDefault();
                    let id  = $(event.target).attr('href'),
                        top = $(id).offset().top;

                    let panelBX = document.querySelector('#bx-panel');
                    let headerTwo = document.querySelector('.header-two');
                    let headerOne = document.querySelector('#main-header');
                    let heightPanelBX = panelBX ? panelBX.offsetHeight : 0;
                    let heightHeaderTwo = headerTwo ? headerTwo.offsetHeight : 0;
                    let heightHeaderOne = headerOne ? headerOne.offsetHeight : 0;
                    top -= heightPanelBX + heightHeaderTwo + heightHeaderOne;
                    $('body,html').animate({scrollTop: top}, 1000);
                }

                let isMove = false;
                let startX;
                let startY;
                function handlerTouchStart (evt) {
                    window.addEventListener('touchmove', isTouchMove);
                    isMove = false;
                    startX = evt.changedTouches[0].clientX;
                    startY = evt.changedTouches[0].clientY;
                }

                function handlerTouchEnd (evt) {
                    window.removeEventListener('touchmove', isTouchMove);


                    let endX = evt.changedTouches[0].clientX;
                    let endY = evt.changedTouches[0].clientY;
                    let diffX = endX - startX;
                    let diffY = endY - startY;

                    if ((-5 < diffX && diffX < 5) || (-5 < diffY && diffY < 5)) {
                        isMove = false;
                    } else {
                        isMove = true;
                    }

                    if (!isMove) {
                        scroll(evt);
                    }
                }

                function isTouchMove (evt) {
                    isMove = true;
                }

                $(document).on("click", '.detailed-feat__menu-item a', scroll);
                $(document).on("touchstart", '.detailed-feat__menu-item a', handlerTouchStart);
                $(document).on("touchend", '.detailed-feat__menu-item a', handlerTouchEnd);

            }
        }
        scrollTo('.detailed-feat__menu-item a');

        if(toggleAvailability) {
            toggleAvailability();
        }

	</script>
<?
if(Bitrix\Main\Loader::includeModule('sotbit.opengraph')) {
    OpengraphMain::setImageMeta('og:image', $templateData["ITEM"]["JS_OFFERS"][0]["DETAIL_PICTURE"]["SRC"]);
    OpengraphMain::setImageMeta('twitter:image', $templateData["ITEM"]["JS_OFFERS"][0]["DETAIL_PICTURE"]["SRC"]);
    OpengraphMain::setMeta('og:type', 'product');
    OpengraphMain::setMeta('og:title', $arResult["NAME"]);
    OpengraphMain::setMeta('og:description', $arResult["META_TAGS"]["DESCRIPTION"]);
    OpengraphMain::setMeta('twitter:title', $arResult["NAME"]);
    OpengraphMain::setMeta('twitter:description', $arResult["META_TAGS"]["DESCRIPTION"]);
}
