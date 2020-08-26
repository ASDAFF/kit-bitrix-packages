<?
use Kit\Origami\Helper\Config;

if (! defined( 'B_PROLOG_INCLUDED' ) || B_PROLOG_INCLUDED !== true) die();

$this->IncludeLangFile( 'template.php' );
$cartId = $arParams['cartId'];
require (realpath( dirname( __FILE__ ) ) . '/top_template.php');

if ($arParams["SHOW_PRODUCTS"] == "Y" && $arResult['QNT'] > 0)
{
    ?>
	<div data-role="basket-item-list" class="bx-basket-item-list
	window-without-bg window_basket" style="display:none;">
		<div class='modal-block'>
			<div class='modal-block-inner'>

				<span class='close'></span>
				<div id="<?=$cartId?>products" class="bx-basket-item-list-container modal-content">
					<p class="bx-basket-item-list-container-title fonts__middle_title"><?=GetMessage("TSB1_CART")?></p>
					<div class="bx-basket-item-list-item basket-item-title">
						<div class="item-tovar">
							<div class="bx-basket-item-list-item-img wrap-img"></div>
							<span class="characteristics">
								<?=GetMessage("TSB1_PRODUCT_NAME")?>
							</span>
							<div class="bx-basket-item-list-item-price-block item-price">
								<div class="bx-basket-item-list-item-price-summ item-count">
									<?=GetMessage("TSB1_PRODUCT_ITEM")?>
								</div>
								<div class="bx-basket-item-list-item-price price">
									<?=GetMessage("TSB1_PRODUCT_PRICE")?>
								</div>
							</div>
							<div class="bx-basket-item-list-item-remove"></div>
						</div>
					</div>
					<div class="bx-basket-item-list-item-container">
						<?
						foreach ( $arResult["CATEGORIES"] as $category => $items )
						{
							if (empty( $items ))
							{
								continue;
							}
							foreach ($items as $v)
							{
								if ($v["CAN_BUY"] == "Y" && $v["DELAY"] == "N" && $v['SUBSCRIBE'] == "N")
								{
									?>
									<div class="bx-basket-item-list-item basket-item item-bg-1">
										<a href="<?=$v["DETAIL_PAGE_URL"]?>" class="item-tovar" title="<?=($arResult["NAMES"][$v['PRODUCT_ID']])?$arResult["NAMES"][$v['PRODUCT_ID']]:$v['NAME']?>">
											<div class="bx-basket-item-list-item-img wrap-img">
												<?if($arResult["PICTURE"][$v['PRODUCT_ID']]['src'])
												{?>
													<img
															width="<?=$arResult["PICTURE"][$v['PRODUCT_ID']]['width']?>"
															height="<?=$arResult["PICTURE"][$v['PRODUCT_ID']]['height']?>"
															class="img-responsive"
															src="<?=$arResult["PICTURE"][$v['PRODUCT_ID']]['src']?>"
															alt="<?=(isset($arResult["NAMES"][$v['PRODUCT_ID']]) && !empty($arResult["NAMES"][$v['PRODUCT_ID']]))?$arResult["NAMES"][$v['PRODUCT_ID']]:$v['NAME']?>"
															title="<?=(isset($arResult["NAMES"][$v['PRODUCT_ID']]) && !empty($arResult["NAMES"][$v['PRODUCT_ID']]))?$arResult["NAMES"][$v['PRODUCT_ID']]:$v['NAME']?>"
													/>
													<?
												}
												elseif(isset($v["PICTURE_SRC"]) && !empty($v["PICTURE_SRC"]))
												{?>
													<img width="<?=$arResult["IMG_WIDTH"]?>"
														 height="<?=$arResult["IMG_HEIGHT"]?>" class="img-responsive"
														 src="<?=$v["PICTURE_SRC"] ?>"
														 alt="<?=(isset($arResult["NAMES"][$v['PRODUCT_ID']]) && !empty($arResult["NAMES"][$v['PRODUCT_ID']]))?$arResult["NAMES"][$v['PRODUCT_ID']]:$v['NAME']?>"
														 title="<?=(isset($arResult["NAMES"][$v['PRODUCT_ID']]) && !empty($arResult["NAMES"][$v['PRODUCT_ID']]))?$arResult["NAMES"][$v['PRODUCT_ID']]:$v['NAME']?>"
													/>
													<?
												}
												else
												{?>
													<img width="<?=$arResult["IMG_WIDTH"]?>"
														 height="<?=$arResult["IMG_HEIGHT"]?>" class="img-responsive"
														 src="/upload/no_photo.jpg"
														 alt="<?=(isset($arResult["NAMES"][$v['PRODUCT_ID']]) && !empty($arResult["NAMES"][$v['PRODUCT_ID']]))?$arResult["NAMES"][$v['PRODUCT_ID']]:$v['NAME']?>"
														 title="<?=(isset($arResult["NAMES"][$v['PRODUCT_ID']]) && !empty($arResult["NAMES"][$v['PRODUCT_ID']]))?$arResult["NAMES"][$v['PRODUCT_ID']]:$v['NAME']?>"
													/>
													<?
												}
												?>
											</div>
											<span class="characteristics">
											<span class="item-name">
												<?=$arResult["BRANDS"][$v['PRODUCT_ID']]?>
											</span>
											<span class="item-second-name">
												<?=($arResult["NAMES"][$v['PRODUCT_ID']])?$arResult["NAMES"][$v['PRODUCT_ID']]:$v['NAME']?>
											</span>
												<?if(isset($arResult["PROPS"][$v['ID']]))
												{
													foreach($arResult["PROPS"][$v['ID']] as $prop)
													{?>
														<span class="item-size">
														<?=$prop["NAME"]?>:
														<b>
															<?=$prop["VALUE"]?>
														</b>
													</span>
														<?
													}
												}?>
										</span>
											<div class="bx-basket-item-list-item-price-block item-price">
												<div class="bx-basket-item-list-item-price-summ item-count">
													<?=intval($v["QUANTITY"])?>
												</div>
												<div class="bx-basket-item-list-item-price price">
													<strong>
														<?=$v["PRICE_FMT"]?>
													</strong>
												</div>
											</div>
										</a>
										<div class="bx-basket-item-list-item-remove delete" onclick="<?=$cartId?>.removeItemFromCart(<?=$v['ID']?>)"></div>

									</div>
									<?
								}
							}
						}
						?>
					</div>
					<div class="basket-total-container">
						<a class="basket-block__link
						basket-block__link_main_basket" href="<?=Config::get('BASKET_PAGE')?>">
							<span class="icon-bbasket"></span> <?=GetMessage('TSB1_GOTO_CART');?>
						</a>
						<?if ($arParams["PATH_TO_ORDER"] && $arResult["CATEGORIES"]["READY"])
						{?>
							<a href="<?=$arParams["PATH_TO_ORDER"]?>" class="basket-btn-order main_btn">
								<span class="icon-wallet"></span>
								<span class="basket-btn-order-inner">
									<?=GetMessage("TSB1_2ORDER")?>
								</span>
								<span class='basket-btn-order-l'></span>
								<span class='basket-btn-order-r'></span>
							</a>
						<?}?>
						<div class="bx-basket-item-list-item-price-summ basket-total">

							<div class="quantity">
										<div class="basket-total-name"><?=GetMessage("TSB1_TOTAL_NAME")?></div>
										<div class="basket-total-nds"><?=GetMessage("TSB1_TOTAL_NDS")?> <span>0 <?=GetMessage("TSB1_NDS_SUM")?></span> </div>
<!--								--><?//=GetMessage("TSB1_QUANTITY")?>
<!--								<b>-->
<!--									--><?//=$arResult['QNT']?>
<!--								</b>-->
							</div>
							<div class="total-price">
								<?=GetMessage("TSB1_BASKET_SUM")?>
								<strong><?=$arResult["TOTAL_PRICE"]?></strong>
								<?
									//var_dump($arResult);
								?>
							</div>
						</div>

					</div>

				</div>
			</div>
		</div>
	</div>
    <?
}