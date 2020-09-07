<?php if (defined("B_PROLOG_INCLUDED") && B_PROLOG_INCLUDED === true) ?>
    <?php

use Bitrix\Main\Localization\Loc;
use intec\core\helpers\ArrayHelper;
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
$vPurchase = function (&$arItem) use (&$arResult, &$arVisual, &$APPLICATION, &$component, &$sTemplateId) {
    $fRender = function (&$arItem, $bOffer = false) use (&$arResult, &$APPLICATION, &$component, &$sTemplateId) { ?>
        <?php if ($bOffer || $arItem['ACTION'] === 'buy' && $arItem['CAN_BUY']) { ?>
            <?php $arPrice = ArrayHelper::getValue($arItem, ['ITEM_PRICES', 0]) ?>
            <div class="catalog-section-item-purchase-buttons" data-offer="<?= $bOffer ? $arItem['ID'] : 'false' ?>">
                <?= Html::beginTag('div', [
                    'class' => [
                        'intec-ui',
                        'intec-ui-control-basket-button',
                        'catalog-section-item-purchase-button',
                        'catalog-section-item-purchase-button-add',
                        'intec-button',
                        'intec-button-w-icon',
                        'intec-button-cl-common'
                    ],
                    'data' => [
                        'basket-id' => $arItem['ID'],
                        'basket-action' => 'add',
                        'basket-state' => 'none',
                        'basket-price' => !empty($arPrice) ? $arPrice['PRICE_TYPE_ID'] : null
                    ]
                ]) ?>
                    <span class="intec-ui-part-content">
                        <i class="intec-button-icon intec-basket glyph-icon-cart"></i>
                        <span class="intec-button-text">
                            <?= Loc::getMessage('C_CATALOG_SECTION_CATALOG_TEXT_2_BUTTON_ADD') ?>
                        </span>
                    </span>
                    <span class="intec-ui-part-effect intec-ui-part-effect-bounce">
                        <span class="intec-ui-part-effect-wrapper">
                            <i></i><i></i><i></i>
                        </span>
                    </span>
                <?= Html::endTag('div') ?>
                <?= Html::beginTag('a', [
                    'href' => $arResult['URL']['BASKET'],
                    'class' => [
                    'catalog-section-item-purchase-button',
                    'catalog-section-item-purchase-button-added',
                    'intec-button',
                    'intec-button-w-icon',
                    'intec-button-cl-common',
                    'hover'
                    ],
                    'data' => [
                        'basket-id' => $arItem['ID'],
                        'basket-action' => 'add',
                        'basket-state' => 'none',
                        'basket-price' => !empty($arPrice) ? $arPrice['PRICE_TYPE_ID'] : null
                    ]
                ]) ?>
                    <i class="intec-button-icon intec-basket glyph-icon-cart"></i>
                    <span class="intec-button-text">
                        <?= Loc::getMessage('C_CATALOG_SECTION_CATALOG_TEXT_2_BUTTON_ADDED') ?>
                    </span>
                <?= Html::endTag('a') ?>
                <?= Html::beginTag('div', [
                    'class' => [
                        'catalog-section-item-purchase-button',
                        'catalog-section-item-purchase-button-update',
                        'intec-button',
                        'intec-button-w-icon',
                        'intec-button-cl-common'
                    ],
                    'data' => [
                        'basket-id' => $arItem['ID'],
                        'basket-action' => 'setQuantity',
                        'basket-state' => 'none',
                        'basket-price' => !empty($arPrice) ? $arPrice['PRICE_TYPE_ID'] : null
                    ]
                ]) ?>
                    <i class="intec-button-icon intec-basket glyph-icon-cart"></i>
                    <span class="intec-button-text">
                        <?= Loc::getMessage('C_CATALOG_SECTION_CATALOG_TEXT_2_BUTTON_UPDATE') ?>
                    </span>
                <?= Html::endTag('div') ?>
            </div>
        <?php } ?>
    <?php };

    $fRender($arItem, false);

    if ($arItem['ACTION'] === 'buy' && $arVisual['OFFERS']['USE'] && !empty($arItem['OFFERS']))
        foreach ($arItem['OFFERS'] as &$arOffer) {
            $fRender($arOffer, true);

            unset($arOffer);
        }
};