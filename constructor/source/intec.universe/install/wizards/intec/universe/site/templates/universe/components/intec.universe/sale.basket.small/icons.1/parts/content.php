<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die() ?>
<?php

use Bitrix\Main\Localization\Loc;
use intec\core\helpers\Html;

/**
 * @var $APPLICATION
 * @var array $arParams
 * @var array $arResult
 * @var IntecBasketLiteComponent $component
 * @var CBitrixComponentTemplate $this
 * @var boolean $bStub
 */

$arTitlesCount = array(
    Loc::getMessage('C_SALE_BASKET_SMALL_ICONS_1_DECLINE_1'),
    Loc::getMessage('C_SALE_BASKET_SMALL_ICONS_1_DECLINE_2'),
    Loc::getMessage('C_SALE_BASKET_SMALL_ICONS_1_DECLINE_3')
);
$fDeclOfNum = function ($fNumber, $arTitles)
{
    $arCasesTitle = array (2, 0, 1, 1, 1, 2);
    return $fNumber." ".$arTitles[
        ($fNumber%100 > 4 && $fNumber %100 < 20) ? 2 : $arCasesTitle[ min($fNumber%10, 5) ]
    ];
};
$sCountBasket = $fDeclOfNum($arResult['BASKET']['COUNT'], $arTitlesCount);
$sCountDelayed = $fDeclOfNum($arResult['DELAYED']['COUNT'], $arTitlesCount);

?>
<div class="sale-basket-small-content">
    <?php if (!$bStub) { ?>
        <div class="sale-basket-small-tabs" data-role="tabs">
            <?= Html::beginTag('div', [
                'class' => [
                    'sale-basket-small-items',
                    'intec-grid' => [
                        '',
                        'nowrap',
                        'a-v-center',
                        'i-h-10'
                    ]
                ]
            ]) ?>
                <?php if ($arResult['COMPARE']['SHOW']) { ?>
                <?php $bActive = $arResult['COMPARE']['COUNT'] > 0 ?>
                    <div class="sale-basket-small-tab-wrap intec-grid-item-auto">
                        <?= Html::beginTag('div', [
                            'class' => Html::cssClassFromArray([
                                'sale-basket-small-tab' => [
                                    '' => true,
                                    'active' => $bActive
                                ],
                                'intec-cl-text' => [
                                    '' => $bActive,
                                    'hover' => true
                                ]
                            ], true)
                        ]) ?>
                        <a rel="nofollow" href="<?= $arResult['URL']['COMPARE'] ?>" class="sale-basket-small-tab-wrapper">
                            <i class="sale-basket-small-tab-icon glyph-icon-compare"></i>
                            <?php if ($bActive) { ?>
                                <div class="sale-basket-small-tab-counter intec-cl-background-dark">
                                    <?= Html::encode($arResult['COMPARE']['COUNT']) ?>
                                </div>
                            <?php } ?>
                        </a>
                        <?= Html::endTag('div') ?>
                    </div>
                <?php } ?>
                <?php if ($arResult['DELAYED']['SHOW']) { ?>
                <?php
                    $bActive = $arResult['DELAYED']['COUNT'] > 0;
                    $sTag = !empty($arResult['URL']['DELAYED']) ? 'a' : 'div';
                ?>
                    <div class="sale-basket-small-tab-wrap intec-grid-item-auto" data-role="tab" data-active="false" data-tab="delay">
                        <?= Html::beginTag($sTag, [
                            'class' => Html::cssClassFromArray([
                                'sale-basket-small-tab' => [
                                    '' => true,
                                    'active' => $bActive
                                ],
                                'intec-cl-text' => [
                                    '' => $bActive,
                                    'hover' => true
                                ]
                            ], true),
                            'data-role' => "tab.icon",
                            'rel' => $sTag === 'a' ? 'nofollow' : null,
                            'href' => !empty($arResult['URL']['DELAYED']) ? $arResult['URL']['DELAYED'] : null
                        ]) ?>
                            <span class="sale-basket-small-tab-wrapper">
                                <i class="sale-basket-small-tab-icon glyph-icon-heart"></i>
                                <?php if ($bActive) { ?>
                                    <span class="sale-basket-small-tab-counter intec-cl-background-dark">
                                        <?= Html::encode($arResult['DELAYED']['COUNT']) ?>
                                    </span>
                                <?php } ?>
                            </span>
                        <?= Html::endTag($sTag) ?>
                        <?php if ($bActive) { ?>
                            <div class="sale-basket-small-popup sale-basket-small-popup-delayed" data-role="tab.popup">
                                <div class="sale-basket-small-popup-wrapper">
                                    <div class="sale-basket-small-header">
                                        <div class="intec-grid intec-grid-nowrap intec-grid-a-v-center">
                                            <div class="sale-basket-small-header-text intec-grid-item">
                                                <?= Loc::getMessage('C_SALE_BASKET_SMALL_ICONS_1_DELAYED_TITLE')?>
                                                <span class="sale-basket-small-header-count">
                                                    <?= $sCountDelayed ?>
                                                </span>
                                            </div>
                                            <div class="sale-basket-small-header-clear-wrap intec-grid-item-auto">
                                                <div data-role="button"
                                                     data-action="delayed.clear"
                                                     class="sale-basket-small-header-clear intec-button intec-cl-background-hover intec-cl-border-hover intec-button-md intec-button-w-icon">
                                                    <i class="fal fa-times"></i>
                                                    <span class="intec-button-text">
                                                        <?= Loc::getMessage('C_SALE_BASKET_SMALL_ICONS_1_CLEAR')?>
                                                    </span>
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
                                            <?php if (!empty($arResult['URL']['DELAYED'])) { ?>
                                                <div class="sale-basket-small-footer-buttons intec-grid intec-grid-nowrap intec-grid-a-v-start">
                                                    <div class="intec-grid-item">
                                                        <a rel="nofollow" href="<?= $arResult['URL']['DELAYED'] ?>"
                                                           class="sale-basket-small-footer-order-button intec-ui intec-ui-control-button intec-ui-mod-block intec-ui-scheme-current intec-ui-size-2">
                                                            <?= Loc::getMessage('C_SALE_BASKET_SMALL_ICONS_1_TO_BASKET') ?>
                                                        </a>
                                                    </div>
                                                </div>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                <?php } ?>
                <?php if ($arResult['BASKET']['SHOW']) { ?>
                <?php
                    $bActive = $arResult['BASKET']['COUNT'] > 0;
                    $sTag = !empty($arResult['URL']['BASKET']) ? 'a' : 'div';
                ?>
                    <div class="sale-basket-small-tab-wrap intec-grid-item-auto" data-role="tab" data-active="false" data-tab="basket">
                        <?= Html::beginTag($sTag, [
                            'class' => Html::cssClassFromArray([
                                'sale-basket-small-tab' => [
                                    '' => true,
                                    'active' => $bActive
                                ],
                                'intec-cl-text' => [
                                    '' => $bActive,
                                    'hover' => true
                                ]
                            ], true),
                            'data-role' => 'tab.icon',
                            'href' => !empty($arResult['URL']['BASKET']) ? $arResult['URL']['BASKET'] : null
                        ]) ?>
                            <span class="sale-basket-small-tab-wrapper">
                                <i class="sale-basket-small-tab-icon glyph-icon-cart"></i>
                                <?php if ($bActive) { ?>
                                    <span class="sale-basket-small-tab-counter intec-cl-background-dark">
                                        <?= Html::encode($arResult['BASKET']['COUNT']) ?>
                                    </span>
                                <?php } ?>
                            </span>
                        <?= Html::endTag($sTag) ?>
                        <?php if ($bActive) { ?>
                            <div class="sale-basket-small-popup sale-basket-small-popup-basket" data-role="tab.popup">
                                <div class="sale-basket-small-popup-wrapper">
                                    <div class="sale-basket-small-header">
                                        <div class="intec-grid intec-grid-nowrap intec-grid-a-v-center">
                                            <div class="sale-basket-small-header-text intec-grid-item">
                                                <?= Loc::getMessage('C_SALE_BASKET_SMALL_ICONS_1_BASKET_TITLE')?>
                                                <span class="sale-basket-small-header-count">
                                                    <?= $sCountBasket ?>
                                                </span>
                                            </div>
                                            <div class="sale-basket-small-header-clear-wrap intec-grid-item-auto">
                                                <div data-role="button"
                                                     data-action="basket.clear"
                                                     class="sale-basket-small-header-clear intec-button intec-cl-background-hover intec-cl-border-hover intec-button-md intec-button-w-icon">
                                                    <i class="fal fa-times"></i>
                                                    <span class="intec-button-text">
                                                        <?= Loc::getMessage('C_SALE_BASKET_SMALL_ICONS_1_CLEAR')?>
                                                    </span>
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
                                            <div class="sale-basket-small-footer-sum-wrap">
                                                <div class="intec-grid intec-grid-nowrap intec-grid-a-v-end">
                                                    <div class="sale-basket-small-footer-sum intec-grid-item">
                                                        <div class="sale-basket-small-footer-sum-title">
                                                            <?= Loc::getMessage('C_SALE_BASKET_SMALL_ICONS_1_BASKET_SUM_TITLE')?>
                                                        </div>
                                                    </div>
                                                    <div class="sale-basket-small-footer-sum intec-grid-item-auto">
                                                        <span class="sale-basket-small-footer-new-sum">
                                                            <?= $arResult['BASKET']['SUM']['DISCOUNT']['DISPLAY'] ?>
                                                        </span>
                                                    </div>
                                                    <div class="sale-basket-small-footer-sum intec-grid-item">
                                                        <?php if ($arResult['BASKET']['SUM']['DISCOUNT']['VALUE'] != $arResult['BASKET']['SUM']['BASE']['VALUE']) { ?>
                                                            <span class="sale-basket-small-footer-old-sum">
                                                                <?= $arResult['BASKET']['SUM']['BASE']['DISPLAY'] ?>
                                                            </span>
                                                        <?php } ?>
                                                    </div>
                                                </div>
                                            </div>
                                            <?php if (!empty($arResult['URL']['BASKET']) || !empty($arResult['URL']['ORDER'])) { ?>
                                                <div class="sale-basket-small-footer-buttons intec-grid intec-grid-nowrap intec-grid-a-v-start">
                                                    <?php if (!empty($arResult['URL']['BASKET'])) { ?>
                                                        <div class="intec-grid-item">
                                                            <a href="<?= $arResult['URL']['BASKET'] ?>"
                                                               class="sale-basket-small-footer-order-button intec-ui intec-ui-control-button intec-ui-mod-block intec-ui-scheme-current intec-ui-size-2">
                                                                <?= Loc::getMessage('C_SALE_BASKET_SMALL_ICONS_1_TO_BASKET') ?>
                                                            </a>
                                                        </div>
                                                    <?php } ?>
                                                    <?php if (!empty($arResult['URL']['ORDER'])) { ?>
                                                        <div class="intec-grid-item">
                                                            <a href="<?= $arResult['URL']['ORDER'] ?>"
                                                               class="sale-basket-small-footer-order-button intec-ui intec-ui-control-button intec-ui-mod-block intec-ui-size-2">
                                                                <?= Loc::getMessage('C_SALE_BASKET_SMALL_ICONS_1_CREATE_ORDER') ?>
                                                            </a>
                                                        </div>
                                                    <?php } ?>
                                                </div>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                <?php } ?>
            <?= Html::endTag('div') ?>
        </div>
    <?php } else { ?>
        <div class="sale-basket-small-tabs" data-role="tabs">
            <?= Html::beginTag('div', [
                'class' => [
                    'sale-basket-small-items',
                    'intec-grid' => [
                        '',
                        'nowrap',
                        'a-v-center',
                        'i-h-10'
                    ]
                ]
            ]) ?>
                <?php if ($arResult['COMPARE']['SHOW']) { ?>
                    <div class="sale-basket-small-tab-wrap intec-grid-item-auto">
                        <?= Html::beginTag('div', [
                            'class' => [
                                'sale-basket-small-tab',
                                'intec-cl-text-hover'
                            ]
                        ]) ?>
                            <a rel="nofollow" href="<?= $arResult['URL']['COMPARE'] ?>" class="sale-basket-small-tab-wrapper">
                                <i class="sale-basket-small-tab-icon glyph-icon-compare"></i>
                            </a>
                        <?= Html::endTag('div') ?>
                    </div>
                <?php } ?>
                <?php if ($arResult['DELAYED']['SHOW']) { ?>
                <?php
                    $bActive = $arResult['DELAYED']['COUNT'] > 0;
                    $sTag = !empty($arResult['URL']['DELAYED']) ? 'a' : 'div';
                ?>
                    <div class="sale-basket-small-tab-wrap intec-grid-item-auto" data-role="tab" data-active="false" data-tab="delay">
                        <?= Html::beginTag($sTag, [
                            'class' => [
                                'sale-basket-small-tab',
                                'intec-cl-text-hover'
                            ],
                            'data-role' => "tab.icon",
                            'rel' => $sTag === 'a' ? 'nofollow' : null,
                            'href' => !empty($arResult['URL']['DELAYED']) ? $arResult['URL']['DELAYED'] : null
                        ]) ?>
                            <span class="sale-basket-small-tab-wrapper">
                                <i class="sale-basket-small-tab-icon glyph-icon-heart"></i>
                            </span>
                        <?= Html::endTag($sTag) ?>
                    </div>
                <?php } ?>
                <?php if ($arResult['BASKET']['SHOW']) { ?>
                <?php
                    $bActive = $arResult['BASKET']['COUNT'] > 0;
                    $sTag = !empty($arResult['URL']['BASKET']) ? 'a' : 'div';
                ?>
                    <div class="sale-basket-small-tab-wrap intec-grid-item-auto" data-role="tab" data-active="false" data-tab="basket">
                        <?= Html::beginTag($sTag, [
                            'class' => [
                                'sale-basket-small-tab',
                                'intec-cl-text-hover'
                            ],
                            'data-role' => 'tab.icon',
                            'href' => !empty($arResult['URL']['BASKET']) ? $arResult['URL']['BASKET'] : null
                        ]) ?>
                            <span class="sale-basket-small-tab-wrapper">
                                <i class="sale-basket-small-tab-icon glyph-icon-cart"></i>
                            </span>
                        <?= Html::endTag($sTag) ?>
                    </div>
                <?php } ?>
            <?= Html::endTag('div') ?>
        </div>
    <?php } ?>
</div>