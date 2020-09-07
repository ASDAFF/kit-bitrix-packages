<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;

/**
 * @var array $arParams
 */
?>
<script id="basket-total-template" type="text/html">
	<div class="basket-checkout-container" data-entity="basket-checkout-aligner">
        <?
        if ($arParams['HIDE_COUPON'] !== 'Y')
        {
            ?>
			<div class="basket-coupon-section">
				<div class="basket-coupon-block-field">
					<!-- <div class="basket-coupon-block-field-description">
                        <?=Loc::getMessage('SBB_COUPON_ENTER')?>:
					</div> -->
					<div class="form">
						<div class="form-group" style="position: relative;">
							<input type="text" class="form-control" id="" placeholder="<?=Loc::getMessage('SBB_COUPON_ENTER')?>" data-entity="basket-coupon-input">
							<span class="basket-coupon-block-coupon-btn-wrapper">
                                <svg class="basket-coupon-block-coupon-btn" width="8" height="12">
                                    <use xlink:href="/local/templates/kit_origami/assets/img/sprite.svg#icon_arrow_breadcrumbs"></use>
                                </svg>
                                <span class="basket-coupon-block-coupon-btn"></span>
                            </span>
						</div>
					</div>
				</div>
				<?
				if ($arParams['HIDE_COUPON'] !== 'Y')
				{
					?>
					<div class="basket-coupon-alert-section">
						<div class="basket-coupon-alert-inner">
							{{#COUPON_LIST}}
							<div class="basket-coupon-alert text-{{CLASS}}">
								<span class="basket-coupon-text">
									<strong>{{COUPON}}</strong> - <?=Loc::getMessage('SBB_COUPON')?> {{JS_CHECK_CODE}}
									{{#DISCOUNT_NAME}}({{{DISCOUNT_NAME}}}){{/DISCOUNT_NAME}}
								</span>
								<span class="close-link" data-entity="basket-coupon-delete" data-coupon="{{COUPON}}">
									<?=Loc::getMessage('SBB_DELETE')?>
								</span>
							</div>
							{{/COUPON_LIST}}
						</div>
					</div>
					<?
				}
				?>
			</div>
            <?
        }
        ?>




		<div class="basket-checkout-section">
			<div class="basket-checkout-section-inner">
				<div class="basket-checkout-block basket-checkout-block-total">
					<div class="basket-checkout-block-total-inner">
                        {{#DISCOUNT_PRICE_FORMATED}}
						<div class="basket-coupon-block-total-price-old">
                            <?=Loc::getMessage('SBB_TITLE_PRICE')?>: <span>{{{PRICE_WITHOUT_DISCOUNT_FORMATED}}}</span>
						</div>
                        {{/DISCOUNT_PRICE_FORMATED}}

                        <div class="basket-checkout-block-total-description">
							{{#WEIGHT_FORMATED}}
                            <?=Loc::getMessage('SBB_WEIGHT')?>: {{{WEIGHT_FORMATED}}}
							{{#SHOW_VAT}}<br>{{/SHOW_VAT}}
							{{/WEIGHT_FORMATED}}
							{{#SHOW_VAT}}
                            <?=Loc::getMessage('SBB_VAT')?>: {{{VAT_SUM_FORMATED}}}
							{{/SHOW_VAT}}
						</div>

						{{#DISCOUNT_PRICE_FORMATED}}
						<div class="basket-coupon-block-total-price-difference">
                            <?=Loc::getMessage('SBB_BASKET_ITEM_ECONOMY')?>
							<span style="white-space: nowrap;">{{{DISCOUNT_PRICE_FORMATED}}}</span>
						</div>
                        {{/DISCOUNT_PRICE_FORMATED}}

					</div>
				</div>

				<div class="basket-checkout-block basket-checkout-block-total-price">
					<div class="basket-checkout-block-total-price-inner">
                        <div class="basket-checkout-block-total-title"><?=Loc::getMessage('SBB_TOTAL')?>:</div>
						<div class="basket-coupon-block-total-price-current" data-entity="basket-total-price">
							{{{PRICE_FORMATED}}}
						</div>

					</div>
				</div>
				<div class="basket-checkout-block basket-checkout-block-btn">
                    <?if($arParams["SHOW_OC"] == "Y" && \Bitrix\Main\Loader::includeModule('kit.orderphone')):?>
                        <button class="basket-btn-oc" id="order_oc">
                            <?=Loc::getMessage('SBB_ORDER_OC')?>
                        </button>
                    <?endif;?>
					<button class="basket-btn-checkout{{#DISABLE_CHECKOUT}} disabled{{/DISABLE_CHECKOUT}}"
					        data-entity="basket-checkout-button">
                        <?=Loc::getMessage('SBB_ORDER')?>
					</button>
				</div>
			</div>
		</div>


	</div>
</script>
