<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die() ?>
<?php

use Bitrix\Main\Localization\Loc;

/**
 * @var array $arParams
 * @var array $arResult
 * @var array $arVisual
 * @var string $sTemplate
 * @var IntecBasketLiteComponent $component
 * @var CBitrixComponentTemplate $this
 */

?>
<div class="sale-basket-small-content">
    <div class="sale-basket-small-switches" data-role="switches">
        <?php if ($arResult['BASKET']['SHOW']) { ?>
            <div class="sale-basket-small-switch intec-cl-background-dark" data-role="switch" data-tab="basket">
                <div class="sale-basket-small-switch-icon">
                    <div class="intec-aligner"></div>
                    <i class="glyph-icon-cart"></i>
                </div>
                <?php if ($arResult['BASKET']['COUNT'] > 0) { ?>
                    <div class="sale-basket-small-switch-count">
                        <?= $arResult['BASKET']['COUNT'] ?>
                    </div>
                <?php } ?>
            </div>
        <?php } ?>
        <?php if ($arResult['DELAYED']['SHOW']) { ?>
            <div class="sale-basket-small-switch intec-cl-background-dark" data-tab="delayed">
                <div class="sale-basket-small-switch-icon">
                    <div class="intec-aligner"></div>
                    <i class="glyph-icon-heart"></i>
                </div>
                <?php if ($arResult['DELAYED']['COUNT'] > 0) { ?>
                    <div class="sale-basket-small-switch-count">
                        <?= $arResult['DELAYED']['COUNT'] ?>
                    </div>
                <?php } ?>
            </div>
        <?php } ?>
        <?php if ($arResult['FORM']['SHOW']) { ?>
            <div class="sale-basket-small-switch intec-cl-background-dark" data-tab="form">
                <div class="sale-basket-small-switch-icon">
                    <div class="intec-aligner"></div>
                    <i class="glyph-icon-phone"></i>
                </div>
            </div>
        <?php } ?>
        <?php if ($arResult['PERSONAL']['SHOW']) { ?>
            <a rel="nofollow" href="<?= $arResult['URL']['PERSONAL'] ?>" class="sale-basket-small-switch intec-cl-background-dark">
                <div class="sale-basket-small-switch-icon">
                    <div class="intec-aligner"></div>
                    <i class="glyph-icon-lock"></i>
                </div>
            </a>
        <?php } ?>
        <?php if ($arResult['COMPARE']['SHOW']) { ?>
            <a rel="nofollow" href="<?= $arResult['URL']['COMPARE'] ?>" class="sale-basket-small-switch intec-cl-background-dark">
                <div class="sale-basket-small-switch-icon">
                    <div class="intec-aligner"></div>
                    <i class="glyph-icon-compare"></i>
                </div>
            </a>
        <?php } ?>
    </div>
    <div class="sale-basket-small-tabs" data-role="tabs" >
        <?php if ($arResult['BASKET']['SHOW']) { ?>
            <div class="sale-basket-small-tab sale-basket-small-tab-products" data-role="tab" data-tab="basket" data-active="false">
                <div class="sale-basket-small-tab-wrapper">
                    <div class="sale-basket-small-tab-header intec-grid intec-grid-nowrap intec-grid-a-v-center">
                        <div class="sale-basket-small-tab-title intec-grid-item-auto">
                            <?= Loc::getMessage('C_SALE_BASKET_SMALL_TEMPLATE_1_BASKET_TITLE') ?>
                        </div>
                        <div class="intec-grid-item"></div>
                        <div class="sale-basket-small-tab-buttons intec-grid-item-auto">
                            <?php if ($arResult['BASKET']['COUNT'] > 0) { ?>
                                <div data-role="button" data-action="basket.clear" class="intec-button intec-button-cl-default intec-button-md intec-button-transparent intec-button-w-icon">
                                    <i class="intec-button-icon fal fa-times"></i>
                                    <span class="intec-button-text">
                                        <?= Loc::getMessage('C_SALE_BASKET_SMALL_TEMPLATE_1_BUTTONS_CLEAR') ?>
                                    </span>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                    <?php $arItems = &$arResult['BASKET']['ITEMS'] ?>
                    <?php if (!empty($arItems)) { ?>
                        <div class="sale-basket-small-tab-content">
                            <?php include(__DIR__.'/content/products.php') ?>
                            <div class="sale-basket-small-products-total">
                                <div class="sale-basket-small-products-total-title">
                                    <?= Loc::getMessage('C_SALE_BASKET_SMALL_TEMPLATE_1_TOTAL') ?>:
                                </div>
                                <div class="sale-basket-small-products-total-value">
                                    <?= $arResult['BASKET']['SUM']['DISCOUNT']['DISPLAY'] ?>
                                </div>
                                <?php if ($arResult['BASKET']['SUM']['ECONOMY']['VALUE'] > 0) { ?>
                                    <div class="sale-basket-small-products-total-economy">
                                        <?= Loc::getMessage('C_SALE_BASKET_SMALL_TEMPLATE_1_ECONOMY'); ?>
                                        <?= $arResult['BASKET']['SUM']['ECONOMY']['DISPLAY']; ?>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                        <div class="sale-basket-small-tab-footer">
                            <div class="sale-basket-small-tab-buttons intec-grid intec-grid-nowrap intec-grid-a-v-center intec-grid-i-h-5">
                                <?php if (true) { ?>
                                    <div class="intec-grid-item-auto">
                                        <div data-role="button" data-action="close" class="intec-button intec-button-cl-common intec-button-md">
                                            <?= Loc::getMessage('C_SALE_BASKET_SMALL_TEMPLATE_1_BUTTONS_SHOPPING') ?>
                                        </div>
                                    </div>
                                <?php } ?>
                                <div class="intec-grid-item"></div>
                                <?php if (!empty($arResult['URL']['BASKET'])) { ?>
                                    <div class="intec-grid-item-auto">
                                        <a href="<?= $arResult['URL']['BASKET'] ?>" class="intec-button intec-button-cl-common intec-button-md intec-button-transparent">
                                            <?= Loc::getMessage('C_SALE_BASKET_SMALL_TEMPLATE_1_BUTTONS_BASKET') ?>
                                        </a>
                                    </div>
                                <?php } ?>
                                <?php if (!empty($arResult['URL']['ORDER'])) { ?>
                                    <div class="intec-grid-item-auto">
                                        <a href="<?= $arResult['URL']['ORDER'] ?>" class="intec-button intec-button-cl-common intec-button-md">
                                            <?= Loc::getMessage('C_SALE_BASKET_SMALL_TEMPLATE_1_BUTTONS_ORDER') ?>
                                        </a>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    <?php } else { ?>
                        <div class="sale-basket-small-tab-content">
                            <div class="sale-basket-small-products-empty">
                                <div class="sale-basket-small-products-icon">
                                    <img src="<?= $this->GetFolder().'/images/basket.empty.png' ?>" alt="" />
                                </div>
                                <div class="sale-basket-small-products-title">
                                    <?= Loc::getMessage('C_SALE_BASKET_SMALL_TEMPLATE_1_BASKET_EMPTY_TITLE') ?>
                                </div>
                                <div class="sale-basket-small-products-description">
                                    <?= Loc::getMessage('C_SALE_BASKET_SMALL_TEMPLATE_1_BASKET_EMPTY_DESCRIPTION') ?>
                                </div>
                                <?php if (!empty($arResult['URL']['CATALOG'])) { ?>
                                    <div class="sale-basket-small-products-button">
                                        <a href="<?= $arResult['URL']['CATALOG'] ?>" class="intec-button intec-button-cl-common intec-button-lg">
                                            <?= Loc::getMessage('C_SALE_BASKET_SMALL_TEMPLATE_1_BUTTONS_CATALOG') ?>
                                        </a>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>
        <?php } ?>
        <?php if ($arResult['DELAYED']['SHOW']) { ?>
            <div class="sale-basket-small-tab sale-basket-small-tab-products" data-role="tab" data-tab="delayed" data-active="false">
                <div class="sale-basket-small-tab-wrapper">
                    <div class="sale-basket-small-tab-header intec-grid intec-grid-nowrap intec-grid-a-v-center">
                        <div class="sale-basket-small-tab-title intec-grid-item-auto">
                            <?= Loc::getMessage('C_SALE_BASKET_SMALL_TEMPLATE_1_DELAYED_TITLE') ?>
                        </div>
                        <div class="intec-grid-item"></div>
                        <div class="sale-basket-small-tab-buttons intec-grid-item-auto">
                            <?php if ($arResult['DELAYED']['COUNT'] > 0) { ?>
                                <div data-role="button" data-action="delayed.clear" class="intec-button intec-button-cl-default intec-button-md intec-button-transparent intec-button-w-icon">
                                    <i class="intec-button-icon fal fa-times"></i>
                                    <span class="intec-button-text">
                                        <?= Loc::getMessage('C_SALE_BASKET_SMALL_TEMPLATE_1_BUTTONS_CLEAR') ?>
                                    </span>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                    <?php $arItems = &$arResult['DELAYED']['ITEMS'] ?>
                    <?php if (!empty($arItems)) { ?>
                        <div class="sale-basket-small-tab-content">
                            <?php include(__DIR__.'/content/products.php') ?>
                        </div>
                        <div class="sale-basket-small-tab-footer">
                            <div class="sale-basket-small-tab-buttons intec-grid intec-grid-nowrap intec-grid-a-v-center intec-grid-i-h-5">
                                <?php if (true) { ?>
                                    <div class="intec-grid-item-auto">
                                        <div data-role="button" data-action="close" class="intec-button intec-button-cl-common intec-button-md">
                                            <?= Loc::getMessage('C_SALE_BASKET_SMALL_TEMPLATE_1_BUTTONS_SHOPPING') ?>
                                        </div>
                                    </div>
                                <?php } ?>
                                <div class="intec-grid-item"></div>
                                <?php if (!empty($arResult['URL']['DELAYED'])) { ?>
                                    <div class="intec-grid-item-auto">
                                        <a href="<?= $arResult['URL']['DELAYED'] ?>" class="intec-button intec-button-cl-common intec-button-md intec-button-transparent">
                                            <?= Loc::getMessage('C_SALE_BASKET_SMALL_TEMPLATE_1_BUTTONS_BASKET') ?>
                                        </a>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    <?php } else { ?>
                        <div class="sale-basket-small-tab-content">
                            <div class="sale-basket-small-products-empty">
                                <div class="sale-basket-small-products-icon">
                                    <img src="<?= $this->GetFolder().'/images/delayed.empty.png' ?>" alt="" />
                                </div>
                                <div class="sale-basket-small-products-title">
                                    <?= Loc::getMessage('C_SALE_BASKET_SMALL_TEMPLATE_1_DELAYED_EMPTY_TITLE') ?>
                                </div>
                                <div class="sale-basket-small-products-description">
                                    <?= Loc::getMessage('C_SALE_BASKET_SMALL_TEMPLATE_1_DELAYED_EMPTY_DESCRIPTION') ?>
                                </div>
                                <?php if (!empty($arResult['URL']['CATALOG'])) { ?>
                                    <div class="sale-basket-small-products-button">
                                        <a href="<?= $arResult['URL']['CATALOG'] ?>" class="intec-button intec-button-cl-common intec-button-lg">
                                            <?= Loc::getMessage('C_SALE_BASKET_SMALL_TEMPLATE_1_BUTTONS_CATALOG') ?>
                                        </a>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>
        <?php } ?>
        <?php if ($arResult['FORM']['SHOW']) { ?>
            <div class="sale-basket-small-tab sale-basket-small-tab-form" data-tab="form" data-active="false">
                <div class="sale-basket-small-tab-wrapper">
                    <?php if (!empty($arResult['FORM']['TITLE'])) { ?>
                        <div class="sale-basket-small-tab-header">
                            <div class="sale-basket-small-tab-title">
                                <?= $arResult['FORM']['TITLE'] ?>
                            </div>
                        </div>
                    <?php } ?>
                    <div data-role="area" data-area="form" class="sale-basket-small-tab-content"></div>
                </div>
            </div>
        <?php } ?>
    </div>
</div>