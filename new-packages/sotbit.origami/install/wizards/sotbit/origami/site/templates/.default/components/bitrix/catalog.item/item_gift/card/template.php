<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use \Bitrix\Main\Localization\Loc;
use Sotbit\Origami\Helper\Config;
use \Sotbit\Origami\Helper\Prop;

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

if ($arParams['ADD_PROPERTIES_TO_BASKET'] === 'Y' && !empty($item['PRODUCT_PROPERTIES']))
{
	?>

	<div id="<?=$itemIds['BASKET_PROP_DIV']?>" style="display: none;">
		<?
		if (!empty($item['PRODUCT_PROPERTIES_FILL']))
		{
			foreach ($item['PRODUCT_PROPERTIES_FILL'] as $propID => $propInfo)
			{
				?>
				<input type="hidden" name="<?=$arParams['PRODUCT_PROPS_VARIABLE']?>[<?=$propID?>]"
				       value="<?=htmlspecialcharsbx($propInfo['ID'])?>">
				<?
				unset($item['PRODUCT_PROPERTIES'][$propID]);
			}
		}

		if (!empty($item['PRODUCT_PROPERTIES']))
		{
			?>
			<table>
				<?
				foreach ($item['PRODUCT_PROPERTIES'] as $propID => $propInfo)
				{
					?>
					<tr>
						<td><?=$item['PROPERTIES'][$propID]['NAME']?></td>
						<td>
							<?
							if (
								$item['PROPERTIES'][$propID]['PROPERTY_TYPE'] === 'L'
								&& $item['PROPERTIES'][$propID]['LIST_TYPE'] === 'C'
							)
							{
								foreach ($propInfo['VALUES'] as $valueID => $value)
								{
									?>
									<label>
										<? $checked = $valueID === $propInfo['SELECTED'] ? 'checked' : ''; ?>
										<input type="radio" name="<?=$arParams['PRODUCT_PROPS_VARIABLE']?>[<?=$propID?>]"
										       value="<?=$valueID?>" <?=$checked?>>
										<?=$value?>
									</label>
									<br />
									<?
								}
							}
							else
							{
								?>
								<select name="<?=$arParams['PRODUCT_PROPS_VARIABLE']?>[<?=$propID?>]">
									<?
									foreach ($propInfo['VALUES'] as $valueID => $value)
									{
										$selected = $valueID === $propInfo['SELECTED'] ? 'selected' : '';
										?>
										<option value="<?=$valueID?>" <?=$selected?>>
											<?=$value?>
										</option>
										<?
									}
									?>
								</select>
								<?
							}
							?>
						</td>
					</tr>
					<?
				}
				?>
			</table>
			<?
		}
		?>
	</div>
	<?
}
?>

<div class="product_card__block  product-item-gift__block">
	<div class="sticker_product">
		<?if($item['PROMOTION']) { ?>
			<div>
		        <span class="sticker_product__sale fonts__small_comment">
			        <?= Loc::getMessage('PROMOTION') ?>
		        </span>
			</div>
            <?
        }
		if($price['PERCENT'] > 0){?>
	        <div>
	            <span class="sticker_product__discount fonts__small_comment">-<?=$price['PERCENT']?>%</span>
	        </div>
		<?}

        if($item['PROPERTIES'] && $arParams['LABEL_PROP'])
        {
            foreach($arParams['LABEL_PROP'] as $label){
                if(Prop::checkPropListYes($item['PROPERTIES'][$label])){
                    $color = '#00b02a';
                    if($item['PROPERTIES'][$label]['HINT']){
                        $color = $item['PROPERTIES'][$label]['HINT'];
                    }
                    ?>

					<div>
                        <span
		                        class="sticker_product__hit fonts__small_comment"
		                        style="background:<?=$color?>">
                            <?=$item['PROPERTIES'][$label]['NAME']?>
                        </span>
					</div>
                    <?
                }
            }
        }
		?>

	</div>
	<div class="product_card__block_icon">
		<span class="fal fa-heart" data-entity="wish" id="<?=$itemIds['WISH_LINK']?>"></span>
		<?
		if($arParams['DISPLAY_COMPARE'] == 'Y'){
		?>
		<span class="fal fa-chart-bar" data-entity="compare-checkbox" id="<?=$itemIds['COMPARE_LINK']?>"></span>
		<?}?>
	</div>
	<?
	if($morePhoto[0]['SRC'])
	{
		?>
		<div class="product_card__block__photo">
			<?if(Config::get('QUICK_VIEW') == 'Y'):?>
                <span class="product_card__block__quick_view" onclick="quickView('<?=$item['DETAIL_PAGE_URL']?>');return false;">
                    <?=Loc::getMessage('QUICK_PREVIEW')?>
                </span>
			<?endif;?>
			<a href="<?=$item['DETAIL_PAGE_URL']?>" onclick="" class="product_card__block__photo_link"
			   data-entity="image-wrapper" id="<?=$itemIds['PICT']?>">
				<img src="<?=$morePhoto[0]['SRC']?>" alt="<?=$imgAlt?>" title="<?=$imgTitle?>">
			</a>
		</div>
		<?
	}
	?>
	<div class="product_card__block__title_product">
		<a onclick="" class="product_card__block__title_product_link fonts__middle_text"
		   href="<?=$item['DETAIL_PAGE_URL']?>">
			<?=$productTitle?>
		</a>
	</div>
	<?if ($arParams['USE_VOTE_RATING'] === 'Y')
    {
        $APPLICATION->IncludeComponent(
            'bitrix:iblock.vote',
            'stars',
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
    }?>
	<?php
	if($arResult['PREVIEW_TEXT'])
	{
		?>
		<div class="product_card__block__comment_product fonts__middle_comment">
            <?=$arResult['PREVIEW_TEXT']?>
		</div>
		<?
	}
	?>
	<div class="product_card__block__article">
		<?php

        if ($arParams['SHOW_MAX_QUANTITY'] !== 'N')
        {
            if ($haveOffers)
            {
                if ($arParams['PRODUCT_DISPLAY_MODE'] === 'Y')
                {
                    ?>
					<div class="product-item-info-container"
					     id="<?=$itemIds['QUANTITY_LIMIT']?>"
					     data-entity="quantity-limit-block">
						<div class="product-item-info-container-title">
							<span class="product-item-quantity" data-entity="quantity-limit-value"></span>
						</div>
					</div>
                    <?
                }
            }
            else
            {
                if (
                    $measureRatio
                    && (float)$actualItem['CATALOG_QUANTITY'] > 0
                    && $actualItem['CATALOG_QUANTITY_TRACE'] === 'Y'
                    && $actualItem['CATALOG_CAN_BUY_ZERO'] === 'N'
                )
                {
                    ?>
					<span class="product_card__block__presence_product_value_many">
                        <i class="fas fa-check"></i>
						<?
                        if ($arParams['SHOW_MAX_QUANTITY'] === 'M')
                        {
                            if ((float)$actualItem['CATALOG_QUANTITY'] / $measureRatio >= $arParams['RELATIVE_QUANTITY_FACTOR'])
                            {
                                echo $arParams['MESS_RELATIVE_QUANTITY_MANY'];
                            }
                            else
                            {
                                echo $arParams['MESS_RELATIVE_QUANTITY_FEW'];
                            }
                        }
                        else
                        {
                            echo $actualItem['CATALOG_QUANTITY'].' '.$actualItem['ITEM_MEASURE']['TITLE'];
                        }
                        ?>
					</span>
                    <?
                }
            }
        }
		if($item['PROPERTIES'][Config::get('ARTICUL')]['VALUE'])
		{
			?>
			<span class="product_card__block__article_title">
				<?=$item['PROPERTIES'][Config::get('ARTICUL')]['NAME']?>: <?=$item['PROPERTIES'][Config::get('ARTICUL')]['VALUE']?>
			</span>
			<?
		}
		?>
	</div>
	<?
    if ($haveOffers) {
        ?>
		<div class="product_card__block__property" style="display: none;">
			<div id="<?= $itemIds['PROP_DIV'] ?>">
                <?

                if($arParams['SKU_PROPS'])
                foreach ($arParams['SKU_PROPS'] as $code => $skuProperty) {
                    $propertyId = $skuProperty['ID'];

                    $skuProperty['NAME'] = htmlspecialcharsbx($skuProperty['NAME']);
                    if (!isset($item['SKU_TREE_VALUES'][$propertyId])) {
                        continue;
                    }

                    if ($code == Config::get('COLOR')) {

                        ?>
						<div class="product_card__block__color_product"
						     data-entity="sku-block">
							<div class="product_card__block__color_product_title fonts__middle_comment">
                                <?= $skuProperty['NAME'] ?>
							</div>
							<div class="product_card__block__color_product_variant"
							     data-entity="sku-line-block">
                                <?
                                foreach ($skuProperty['VALUES'] as $value) {
                                    if (!isset($item['SKU_TREE_VALUES'][$propertyId][$value['ID']])) {
                                        continue;
                                    }
                                    ?>
									<div class="product_card__block__color_product_variant_item product_card__block_product_variant_item"
									     data-treevalue="<?=$propertyId ?>_<?=$value['ID'] ?>"
									     data-onevalue="<?= $value['ID'] ?>" title="<?=$value['NAME']?>">
										<img src="<?=$value['PICT']['SRC']?>" alt="<?=$skuProperty['NAME']?>: <?=$value['NAME']?>" title="<?=$skuProperty['NAME']?>: <?=$value['NAME']?>">
									</div>
                                    <?
                                }
                                ?>
							</div>
						</div>
                        <?
                    } else {
                        ?>
						<div class="product_card__block__property_product"
						     data-entity="sku-block">
							<div class="product_card__block__property_product_title fonts__middle_comment">
                                <?= $skuProperty['NAME'] ?>
							</div>
							<div
									class="product_card__block__property_product_variant"
									data-entity="sku-line-block"
							>
                                <?
                                foreach ($skuProperty['VALUES'] as $value) {
                                    if (!isset($item['SKU_TREE_VALUES'][$propertyId][$value['ID']])) {
                                        continue;
                                    }

                                    $value['NAME']
                                        = htmlspecialcharsbx($value['NAME']); ?>
									<div class="product_card__block__property_product_variant_item product_card__block_product_variant_item"
									     data-treevalue="<?= $propertyId ?>_<?= $value['ID'] ?>"
									     data-onevalue="<?= $value['ID'] ?>"
									     title="<?=$skuProperty['NAME']?>: <?=$value['NAME']?>">
									<span class="product_card__block__property_product_variant_item_name
									fonts__middle_text">
										<?= $value['NAME'] ?>
									</span>
									</div>
                                    <?
                                }
                                ?>
							</div>
						</div>
                        <?
                    }
                }
                ?>
			</div>
		</div>
        <?
    }
	?>
	<div class="product_card__block_price">
		<div class="product_card__block__old_new_price">
			<?
			if($price['RATIO_DISCOUNT'] && $arParams['SHOW_OLD_PRICE'] === 'Y')
			{
				?>
				<span class="product_card__block__old_price_product  fonts__middle_text" id="<?=$itemIds['PRICE_OLD']?>">
					<?=$price['PRINT_BASE_PRICE']?>
				</span>
				<?
			}?>
			<span class="product_card__block__new_price_product fonts__main_text" id="<?=$itemIds['PRICE']?>">
				<?=$price['PRINT_PRICE']?>
			</span>
		</div>
		<?if($price['RATIO_DISCOUNT'] && $arParams['SHOW_OLD_PRICE'] === 'Y'):?>
			<div class="product_card__block_saving">
				<?=Loc::getMessage('TO_SAVE')?> <span class="product_card__block_saving_title">
					<?=$price['PRINT_DISCOUNT']?></span>
			</div>
		<?endif;?>
	</div>
    <div class="js_porduct_item_button_block product_item_button_block">
        <div data-entity="buttons-block">
            <div class="product_card__block_buy js_btn_buy">
                <?
                if(Config::get('SHOW_BUY_BUTTON') == 'Y')
                {
                    if($arParams['USE_PRODUCT_QUANTITY'])
                    {
                        ?>
                        <div class="product_card__block_buy_quantity" data-entity="quantity-block">
                            <span class="product_card__block_buy_quantity__minus fonts__small_title" id="<?=$itemIds['QUANTITY_DOWN']?>">&dash;</span>
                            <input class="product_card__block_buy_quantity__input fonts__small_text" type="text"
                                   placeholder="" id="<?=$itemIds['QUANTITY']?>" value="<?=$measureRatio?>">
                            <span class="product_card__block_buy_quantity__plus fonts__small_title" id="<?=$itemIds['QUANTITY_UP']?>">+</span>
                        </div>
                        <?
                    }
                    ?>
                    <div data-entity="buttons-block">
                        <div class="product-item-button-container">
                            <?
                            if ($showSubscribe)
                            {
                                $APPLICATION->IncludeComponent(
                                    'bitrix:catalog.product.subscribe',
                                    '',
                                    array(
                                        'PRODUCT_ID' => $item['ID'],
                                        'BUTTON_ID' => $itemIds['SUBSCRIBE_LINK'],
                                        'BUTTON_CLASS' => 'btn btn-default '.$buttonSizeClass,
                                        'DEFAULT_DISPLAY' => !$actualItem['CAN_BUY'],
                                        'MESS_BTN_SUBSCRIBE' => $arParams['~MESS_BTN_SUBSCRIBE'],
                                    ),
                                    $component,
                                    array('HIDE_ICONS' => 'Y')
                                );
                            }
                            ?>
                            <div id="<?=$itemIds['BASKET_ACTIONS']?>" <?=($actualItem['CAN_BUY'] ? '' : 'style="display: none;"')?>>
                                <a class="main_btn sweep-to-right <?=$buttonSizeClass?>" id="<?=$itemIds['BUY_LINK']?>"
                                   href="javascript:void(0)" rel="nofollow">
                                    <?=($arParams['ADD_TO_BASKET_ACTION'] === 'BUY' ? $arParams['MESS_BTN_BUY'] : $arParams['MESS_BTN_ADD_TO_BASKET'])?>
                                </a>
                            </div>
                        </div>
                    </div>
                <?}
                else
                {
                    ?>
                    <a class="main_btn sweep-to-right"
                       href="<?=$item['DETAIL_PAGE_URL']?>" rel="nofollow">
                        <?=Loc::getMessage('ITEM_MORE')?>
                    </a>
                    <?
                }?>
            </div>
            <div class="product_card__path_to_basket js_btn_to_basket">
                <a href="<?=Config::get('BASKET_PAGE')?>" class="in_basket">
                    <span></span><?=Loc::getMessage('PRODUCT_IN_BASKET')?>
                </a>
            </div>
        </div>
        <span id="check_offer_basket_<?=$item['ID']?>" style="display: none;"></span>
    </div>

</div>
