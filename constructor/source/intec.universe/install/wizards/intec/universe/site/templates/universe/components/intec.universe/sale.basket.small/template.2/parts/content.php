<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die() ?>
<?php

use Bitrix\Main\Localization\Loc;
/**
 * @var $APPLICATION
 * @var array $arParams
 * @var array $arResult
 * @var array $arVisual
 * @var IntecBasketLiteComponent $component
 * @var CBitrixComponentTemplate $this
 */

$arTitlesCount = array(
    Loc::getMessage('C_SALE_BASKET_SMALL_TEMPLATE_2_DECLINE_1'),
    Loc::getMessage('C_SALE_BASKET_SMALL_TEMPLATE_2_DECLINE_2'),
    Loc::getMessage('C_SALE_BASKET_SMALL_TEMPLATE_2_DECLINE_3')
);
$fDeclOfNum = function ($fNumber, $arTitles) {
    $arCasesTitle = array (2, 0, 1, 1, 1, 2);
    return $fNumber." ".$arTitles[
        ($fNumber%100 > 4 && $fNumber %100 < 20) ? 2 : $arCasesTitle[ min($fNumber%10, 5) ]
    ];
};
$sCountBasket = $fDeclOfNum($arResult['BASKET']['COUNT'], $arTitlesCount);
$sCountDelayed = $fDeclOfNum($arResult['DELAYED']['COUNT'], $arTitlesCount);
?>

<div class="sale-basket-small-content">
    <div class="sale-basket-small-switches" data-role="switches">
        <?php if ($arResult['BASKET']['SHOW']) { ?>
            <div class="sale-basket-small-switch intec-cl-text-hover intec-cl-text-active"
                 data-role="switch"
                 data-tab="basket"
                 data-active="false">
                <div class="sale-basket-small-switch-wrapper">
                    <i class="icon-basket glyph-icon-cart"></i>
                    <?php if ($arResult['BASKET']['COUNT'] > 0) { ?>
                    <span class="sale-basket-small-switch-count intec-cl-background-dark">
                        <?= $arResult['BASKET']['COUNT'] ?>
                    </span>
                    <?php } ?>
                </div>
            </div>
        <?php } ?>
        <?php if ($arResult['DELAYED']['SHOW']) { ?>
            <div class="sale-basket-small-switch intec-cl-text-hover intec-cl-text-active"
                 data-role="switch"
                 data-tab="delayed"
                 data-active="false">
                <div class="sale-basket-small-switch-wrapper">
                    <i class="icon-heart glyph-icon-heart"></i>
                    <?php if ($arResult['DELAYED']['COUNT'] > 0) { ?>
                        <span class="sale-basket-small-switch-count intec-cl-background-dark">
                            <?= $arResult['DELAYED']['COUNT'] ?>
                        </span>
                    <?php } ?>
                </div>
            </div>
        <?php } ?>
        <?php if ($arResult['FORM']['SHOW']) { ?>
            <div class="sale-basket-small-switch intec-cl-text-hover intec-cl-text-active"
                 data-role="switch"
                 data-tab="form"
                 data-active="false">
                <i class="icon-phone glyph-icon-phone"></i>
            </div>
        <?php } ?>
        <?php if ($arResult['PERSONAL']['SHOW']) { ?>
            <?php if ($arResult['PERSONAL']['AUTHORIZED']) { ?>
                <a rel="nofollow" href="<?= $arResult['URL']['PERSONAL'] ?>" class="sale-basket-small-switch intec-cl-text-hover intec-cl-text-active">
                    <i class="icon-login glyph-icon-lock"></i>
                </a>
            <?php } else { ?>
                <div class="sale-basket-small-switch intec-cl-text-hover intec-cl-text-active"
                     data-role="switch"
                     data-tab="personal"
                     data-active="false">
                    <i class="icon-login glyph-icon-lock"></i>
                </div>
            <?php } ?>
        <?php } ?>
        <?php if (!empty($arResult['URL']['COMPARE']) && $arResult['COMPARE']['SHOW']) { ?>
            <a rel="nofollow" href="<?= $arResult['URL']['COMPARE'] ?>" class="sale-basket-small-switch intec-cl-text-hover intec-cl-text-active">
                <div class="sale-basket-small-switch-wrapper">
                    <i class="icon-compare glyph-icon-compare"></i>
                    <?php if ($arResult['COMPARE']['COUNT'] > 0) { ?>
                        <span class="sale-basket-small-switch-count intec-cl-background-dark">
                            <?= $arResult['COMPARE']['COUNT'] ?>
                        </span>
                    <?php } ?>
                </div>
            </a>
        <?php } ?>
    </div>

    <div class="sale-basket-small-overlay" data-role="overlay"></div>

    <div class="sale-basket-small-tabs sale-basket-small-popup" data-role="tabs">
        <?php if ($arResult['BASKET']['SHOW']) { ?>
            <div class="sale-basket-small-tab sale-basket-small-tab-basket" data-role="tab" data-tab="basket" data-active="false">
                <?php if ($arResult['BASKET']['COUNT'] > 0) { ?>
                    <div class="sale-basket-small-tab-wrapper">
                        <div class="sale-basket-small-header">
                            <div class="intec-grid intec-grid-nowrap intec-grid-a-v-center">
                                <div class="sale-basket-small-header-text intec-grid-item">
                                    <?= Loc::getMessage('C_SALE_BASKET_SMALL_TEMPLATE_2_BASKET_TITLE')?>
                                    <span class="sale-basket-small-header-count">
                                        <?= $sCountBasket ?>
                                    </span>
                                </div>
                                <div class="intec-grid-item-auto">
                                    <div data-role="button" data-action="basket.clear" class="sale-basket-small-header-clear intec-button intec-cl-background-hover intec-cl-border-hover intec-button-md intec-button-w-icon">
                                        <i class="fal fa-times"></i>
                                        <span class="intec-button-text">
                                            <?= Loc::getMessage('C_SALE_BASKET_SMALL_TEMPLATE_2_CLEAR')?>
                                        </span>
                                    </div>
                                </div>
                                <div class="sale-basket-small-header-btn-close-wrap intec-grid-item-auto">
                                    <div class="sale-basket-small-header-btn-close" data-role="button" data-action="close">
                                        <i class="fal fa-times intec-cl-text-hover"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="sale-basket-small-body">
                            <?php
                            $arItems = $arResult['BASKET']['ITEMS'];
                            include(__DIR__.'/content/products.php');
                            ?>
                        </div>
                        <div class="sale-basket-small-footer-wrap">
                            <div class="sale-basket-small-footer">
                                <div class="intec-grid intec-grid-nowrap intec-grid-a-v-center">
                                    <div class="sale-basket-small-footer-sum intec-grid-item">
                                        <div class="sale-basket-small-footer-sum-title">
                                            <?= Loc::getMessage('C_SALE_BASKET_SMALL_TEMPLATE_2_BASKET_SUM_TITLE')?>
                                        </div>
                                        <div class="sale-basket-small-footer-sum-wrapper">
                                            <span class="sale-basket-small-footer-new-sum">
                                                <?= $arResult['BASKET']['SUM']['DISCOUNT']['DISPLAY'] ?>
                                            </span>
                                            <?php if ($arResult['BASKET']['SUM']['DISCOUNT']['VALUE'] != $arResult['BASKET']['SUM']['BASE']['VALUE']) { ?>
                                                <span class="sale-basket-small-footer-old-sum">
                                                    <?= $arResult['BASKET']['SUM']['BASE']['DISPLAY'] ?>
                                                </span>
                                            <?php } ?>
                                        </div>
                                        <div class="sale-basket-small-footer-sum-economy">
                                            <?php if ($arResult['BASKET']['SUM']['ECONOMY']['VALUE'] > 0) { ?>
                                                <?= Loc::getMessage('C_SALE_BASKET_SMALL_TEMPLATE_2_ECONOMY'); ?>
                                                <?= $arResult['BASKET']['SUM']['ECONOMY']['DISPLAY']; ?>
                                            <?php } ?>
                                        </div>
                                    </div>
                                    <div class="sale-basket-small-footer-buttons intec-grid-item-auto">
                                        <?php if ($arResult['URL']['ORDER']) { ?>
                                            <a href="<?= $arResult['URL']['ORDER'] ?>"
                                               class="sale-basket-small-footer-order-button intec-button intec-button-cl-common intec-button-md">
                                                <?= Loc::getMessage('C_SALE_BASKET_SMALL_TEMPLATE_2_CREATE_ORDER') ?>
                                            </a>
                                        <?php } ?>
                                        <?php if ($arResult['URL']['BASKET']) { ?>
                                            <div>
                                                <a href="<?= $arResult['URL']['BASKET'] ?>" class="sale-basket-small-footer-additional-button intec-cl-text intec-cl-text-light-hover">
                                                    <?= Loc::getMessage('C_SALE_BASKET_SMALL_TEMPLATE_2_TO_BASKET') ?>
                                                </a>
                                            </div>
                                        <?php } else { ?>
                                            <div class="sale-basket-small-close-wrap">
                                                <div data-role="button" data-action="close" class="sale-basket-small-footer-additional-button intec-cl-text intec-cl-text-light-hover">
                                                    <?= Loc::getMessage('C_SALE_BASKET_SMALL_TEMPLATE_2_CONTINUE_SHOPPING') ?>
                                                </div>
                                            </div>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                            <?php include(__DIR__ . '/content/paysystems.php'); ?>
                        </div>
                    </div>
                <?php } else { ?>
                    <div class="sale-basket-small-empty-basket-wrap">
                        <div class="intec-aligner"></div>
                        <div class="sale-basket-small-empty-basket">
                            <div class="sale-basket-small-empty-basket-image">
                                <img src="<?= $this->GetFolder() ?>/images/empty_basket.png" alt="empty basket" />
                            </div>
                            <div class="sale-basket-small-empty-basket-title">
                                <?= Loc::getMessage('C_SALE_BASKET_SMALL_TEMPLATE_2_BASKET_EMPTY_TITLE') ?>
                            </div>
                            <div class="sale-basket-small-empty-basket-text">
                                <?= Loc::getMessage('C_SALE_BASKET_SMALL_TEMPLATE_2_BASKET_EMPTY_DESCRIPTION') ?>
                            </div>
                            <?php if (!empty($arResult['URL']['CATALOG'])) { ?>
                                <a href="<?= $arResult['URL']['CATALOG'] ?>"
                                   class="sale-basket-small-empty-basket-btn intec-button intec-button-cl-common intec-button-lg">
                                    <?= Loc::getMessage('C_SALE_BASKET_SMALL_TEMPLATE_2_TO_CATALOG') ?>
                                </a>
                            <?php } ?>
                        </div>
                    </div>
                <?php } ?>
            </div>
        <?php } ?>

        <?php if ($arResult['DELAYED']['SHOW']) { ?>
            <div class="sale-basket-small-tab sale-basket-small-tab-delayed" data-role="tab" data-tab="delayed" data-active="false">
                <?php if ($arResult['DELAYED']['COUNT'] > 0) { ?>
                    <div class="sale-basket-small-tab-wrapper">
                        <div class="sale-basket-small-header">
                            <div class="intec-grid intec-grid-nowrap intec-grid-a-v-center">
                                <div class="sale-basket-small-header-text intec-grid-item">
                                    <?= Loc::getMessage('C_SALE_BASKET_SMALL_TEMPLATE_2_DELAYED_TITLE')?>
                                    <span class="sale-basket-small-header-count">
                                        <?= $sCountDelayed ?>
                                    </span>
                                </div>
                                <div class="intec-grid-item-auto">
                                    <div data-role="button"
                                         data-action="delayed.clear"
                                         class="sale-basket-small-header-clear intec-button intec-cl-background-hover intec-cl-border-hover intec-button-md intec-button-w-icon">
                                        <i class="fal fa-times"></i>
                                        <span class="intec-button-text">
                                            <?= Loc::getMessage('C_SALE_BASKET_SMALL_TEMPLATE_2_CLEAR')?>
                                        </span>
                                    </div>
                                </div>
                                <div class="sale-basket-small-header-btn-close-wrap intec-grid-item-auto">
                                    <div  data-role="button" data-action="close" class="sale-basket-small-header-btn-close">
                                        <i class="fal fa-times intec-cl-text-hover"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="sale-basket-small-body">
                            <?php
                            $arItems = $arResult['DELAYED']['ITEMS'];
                            include(__DIR__.'/content/products.php');
                            ?>
                        </div>
                        <div class="sale-basket-small-footer-wrap">
                            <div class="sale-basket-small-footer">
                                <div class="">
                                    <div class="sale-basket-small-footer-buttons">
                                        <?php if ($arResult['URL']['BASKET']) { ?>
                                            <a href="<?= $arResult['URL']['BASKET'] ?>"
                                               class="sale-basket-small-footer-order-button intec-button intec-button-cl-common intec-button-md">
                                                <?= Loc::getMessage('C_SALE_BASKET_SMALL_TEMPLATE_2_TO_BASKET') ?>
                                            </a>
                                        <?php } ?>
                                        <div data-role="button" data-action="close" class="sale-basket-small-close-wrap">
                                            <div class="sale-basket-small-footer-additional-button intec-cl-text intec-cl-text-light-hover">
                                                <?= Loc::getMessage('C_SALE_BASKET_SMALL_TEMPLATE_2_CONTINUE_SHOPPING') ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php include(__DIR__ . '/content/paysystems.php'); ?>
                        </div>
                    </div>
                <?php } else { ?>
                    <div class="sale-basket-small-empty-delayed-wrap">
                        <div class="intec-aligner"></div>
                        <div class="sale-basket-small-empty-delayed">
                            <div class="sale-basket-small-empty-delayed-image">
                                <img src="<?= $this->GetFolder() ?>/images/empty_delayed.png" alt="empty delayed" />
                            </div>
                            <div class="sale-basket-small-empty-delayed-title">
                                <?= Loc::getMessage('C_SALE_BASKET_SMALL_TEMPLATE_2_DELAYED_EMPTY_TITLE') ?>
                            </div>
                            <div class="sale-basket-small-empty-delayed-text">
                                <?= Loc::getMessage('C_SALE_BASKET_SMALL_TEMPLATE_2_DELAYED_EMPTY_DESCRIPTION') ?>
                                <img src="<?= $this->GetFolder() ?>/images/delayed_icon.png" alt="">
                            </div>
                            <?php if (!empty($arResult['URL']['CATALOG'])) { ?>
                                <a href="<?= $arResult['URL']['CATALOG'] ?>"
                                   class="sale-basket-small-empty-delayed-btn intec-button intec-button-cl-common intec-button-lg">
                                    <?= Loc::getMessage('C_SALE_BASKET_SMALL_TEMPLATE_2_TO_CATALOG') ?>
                                </a>
                            <?php } ?>
                        </div>
                    </div>
                <?php } ?>
            </div>
        <?php } ?>

        <?php if ($arResult['FORM']['SHOW']) { ?>
            <div class="sale-basket-small-tab sale-basket-small-tab-form" data-tab="form" data-active="false">
                <div class="sale-basket-small-tab-wrapper">
                    <?php if (!empty($arResult['FORM']['TITLE'])) { ?>
                        <div class="sale-basket-small-tab-header ">
                            <div class="intec-grid intec-grid-nowrap intec-grid-a-v-center">
                                <div class="sale-basket-small-tab-title intec-grid-item">
                                    <?= $arResult['FORM']['TITLE'] ?>
                                </div>
                                <div class="sale-basket-small-header-btn-close-wrap intec-grid-item-auto">
                                    <div class="sale-basket-small-header-btn-close" data-role="button" data-action="close">
                                        <i class="fal fa-times"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                    <div data-role="area" data-area="form" class="sale-basket-small-tab-content"></div>
                </div>
            </div>
        <?php } ?>

        <?php if ($arResult['PERSONAL']['SHOW']) { ?>
            <div class="sale-basket-small-tab sale-basket-small-tab-personal-area" data-tab="personal" data-active="false">
                <div class="sale-basket-small-tab-wrapper">
                    <div data-role="area" data-area="personal" class="sale-basket-small-tab-content"></div>
                </div>
            </div>
        <?php } ?>
    </div>
</div>