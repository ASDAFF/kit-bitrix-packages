<?php if (defined("B_PROLOG_INCLUDED") && B_PROLOG_INCLUDED === true) ?>
    <?php

use Bitrix\Main\Localization\Loc;
use intec\core\helpers\Html;

/**
 * @var array $arParams
 * @var array $arResult
 * @var array $arVisual
 * @var CBitrixComponent $component
 * @var CBitrixComponentTemplate $this
 */

/**
 * @param array $arItem
 */
$vOrder = function (&$arItem) use (&$arResult, &$arVisual, &$APPLICATION, &$component, &$sTemplateId) {
    $fRender = function (&$arItem, $bOffer = false) use (&$arResult, &$arVisual, &$APPLICATION, &$component, &$sTemplateId) { ?>
        <?php if ($bOffer || $arItem['ACTION'] === 'buy') { ?>
            <?php if ($arItem['CAN_BUY']) { ?>
                <div class="catalog-section-item-order-buttons" data-offer="<?= $bOffer ? $arItem['ID'] : 'false' ?>">
                    <?php if ($arVisual['BUTTON_TOGGLE']['ACTION'] === 'buy') { ?>
                        <?= Html::beginTag('div', [
                            'class' => [
                                'catalog-section-item-order-button',
                                'catalog-section-item-order-button-toggle',
                                'intec-button',
                                'intec-cl-text-hover',
                                'active'
                            ],
                            'data' => [
                                'toggle' => 'open',
                                'basket-id' => $arItem['ID'],
                                'basket-action' => 'add',
                                'basket-state' => 'none',
                                'basket-quantity' => $arItem['CATALOG_MEASURE_RATIO'],
                                'basket-price' => !empty($arPrice) ? $arPrice['PRICE_TYPE_ID'] : null
                            ],
                            'title' => Loc::getMessage('C_CATALOG_SECTION_CATALOG_TEXT_2_ICON_PURCHASE')
                        ]) ?>
                            <i class="intec-button-icon intec-basket glyph-icon-cart"></i>
                        <?= Html::endTag('div') ?>
                        <?= Html::beginTag('div', [
                            'class' => [
                                'catalog-section-item-order-button',
                                'catalog-section-item-order-button-toggle',
                                'intec-button',
                                'intec-cl-text',
                                'added',
                                'active'
                            ],
                            'data' => [
                                'toggle' => 'open',
                                'basket-id' => $arItem['ID']
                            ],
                            'title' => Loc::getMessage('C_CATALOG_SECTION_CATALOG_TEXT_2_ICON_BASKET')
                        ]) ?>
                            <i class="intec-button-icon intec-basket glyph-icon-cart"></i>
                        <?= Html::endTag('div') ?>
                    <?php } else { ?>
                        <?= Html::beginTag('div', [
                            'class' => [
                                'catalog-section-item-order-button',
                                'catalog-section-item-order-button-toggle',
                                'intec-button',
                                'intec-cl-text-hover',
                                'active'
                            ],
                            'data' => [
                                'toggle' => 'open'
                            ],
                            'title' => Loc::getMessage('C_CATALOG_SECTION_CATALOG_TEXT_2_ICON_PURCHASE')
                        ]) ?>
                            <i class="intec-button-icon intec-basket glyph-icon-cart"></i>
                        <?= Html::endTag('div') ?>
                    <?php } ?>
                    <?= Html::beginTag('div', [
                        'class' => [
                            'catalog-section-item-order-button',
                            'catalog-section-item-order-button-toggle',
                            'intec-button',
                            'intec-cl-text-hover'
                        ],
                        'data' => [
                            'toggle' => 'close'
                        ],
                        'title' => Loc::getMessage('C_CATALOG_SECTION_CATALOG_TEXT_2_ICON_PURCHASE_CLOSE')
                    ]) ?>
                        <i class="glyph-icon-cancel"></i>
                    <?= Html::endTag('div') ?>
                </div>
            <?php } else if ($arItem['CATALOG_SUBSCRIBE'] == 'Y') { ?>
                <?php if (!empty($arItem['OFFERS']) && $bOffer == false) {
                    return;
                } ?>
                <div class="catalog-section-item-order-buttons" data-offer="<?= $bOffer ? $arItem['ID'] : 'false' ?>" title="<?= Loc::getMessage('C_CATALOG_SECTION_CATALOG_TEXT_2_ICON_SUBSCRIBE') ?>">
                    <?php $APPLICATION->IncludeComponent(
                        'bitrix:catalog.product.subscribe',
                        '.default',
                        [
                            'BUTTON_CLASS' => 'catalog-section-item-order-button catalog-section-item-order-button-subscribe intec-cl-text-hover intec-ui intec-button intec-ui-control-button',
                            'BUTTON_ID' => $sTemplateId . '_subscribe_' . $arItem['ID'],
                            'PRODUCT_ID' => $arItem['ID'],
                            'ICON_USE' => 'Y',
                            'TEXT_SHOW' => 'N'
                        ],
                        $component
                    ); ?>
                </div>
            <?php } ?>
        <?php } else if ($arItem['ACTION'] === 'detail') { ?>
            <div class="catalog-section-item-order-buttons">
                <?= Html::beginTag($arResult['QUICK_VIEW']['DETAIL'] ? 'div' : 'a', [
                    'href' => !$arResult['QUICK_VIEW']['DETAIL'] ? $arItem['DETAIL_PAGE_URL'] : null,
                    'class' => [
                        'catalog-section-item-order-button',
                        'catalog-section-item-order-button-detail',
                        'intec-button',
                        'intec-cl-text-hover'
                    ],
                    'data' => [
                        'role' => $arResult['QUICK_VIEW']['DETAIL'] ? 'quick.view' : null
                    ],
                    'title' => Loc::getMessage('C_CATALOG_SECTION_CATALOG_TEXT_2_ICON_DETAIL')
                ]) ?>
                    <i class="far fa-ellipsis-h"></i>
                <?= Html::endTag($arResult['QUICK_VIEW']['DETAIL'] ? 'div' : 'a') ?>
            </div>
        <?php } else if ($arItem['ACTION'] === 'order') { ?>
            <?php if ($arResult['FORM']['SHOW']) { ?>
                <div class="catalog-section-item-order-buttons">
                    <?= Html::beginTag('div', [
                        'class' => [
                            'catalog-section-item-order-button',
                            'catalog-section-item-order-button-order',
                            'intec-button',
                            'intec-button-cl-common',
                            'intec-cl-text-hover'
                        ],
                        'data' => [
                            'role' => 'item.order'
                        ],
                        'title' => Loc::getMessage('C_CATALOG_SECTION_CATALOG_TEXT_2_ICON_ORDER')
                    ]) ?>
                        <i class="fal fa-phone"></i>
                    <?= Html::endTag('div') ?>
                </div>
            <?php } ?>
        <?php } ?>
    <?php };

    $fRender($arItem, false);

    if ($arItem['ACTION'] === 'buy' && $arVisual['OFFERS']['USE'] && !empty($arItem['OFFERS']))
        foreach ($arItem['OFFERS'] as &$arOffer) {
            $fRender($arOffer, true);

            unset($arOffer);
        }
};