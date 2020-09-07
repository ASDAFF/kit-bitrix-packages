<?php if (!defined("B_PROLOG_INCLUDED") && B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;
use intec\core\helpers\ArrayHelper;
use intec\core\helpers\Html;

/**
 * @param $arItem
 */
$vPurchase = function (&$arItem) use (&$arResult, &$arVisual, &$APPLICATION, &$component, &$sTemplateId) {

    $sLink = Html::decode($arItem['DETAIL_PAGE_URL']);

    $fRender = function (&$arItem, $bOffer = false) use (&$arResult, &$arVisual, &$sLink, &$APPLICATION, &$component, &$sTemplateId) { ?>
        <?php if ($bOffer || $arItem['ACTION'] === 'buy') { ?>
            <?php if ($arItem['CAN_BUY']) { ?>
                <?php $arPrice = ArrayHelper::getValue($arItem, ['ITEM_PRICES', 0]) ?>
                <div class="widget-item-purchase-buttons" data-offer="<?= $bOffer ? $arItem['ID'] : 'false' ?>">
                    <?= Html::beginTag('div', [
                        'class' => [
                            'intec-ui',
                            'intec-ui-control-basket-button',
                            'widget-item-purchase-button',
                            'widget-item-purchase-button-add',
                            'intec-cl-background',
                            'intec-cl-background-light-hover'
                        ],
                        'data' => [
                            'basket-id' => $arItem['ID'],
                            'basket-action' => 'add',
                            'basket-state' => 'none',
                            'basket-quantity' => $arItem['CATALOG_MEASURE_RATIO'],
                            'basket-price' => !empty($arPrice) ? $arPrice['PRICE_TYPE_ID'] : null
                        ]
                    ]) ?>
                        <span class="widget-item-purchase-button-content intec-ui-part-content">
                            <i class="glyph-icon-cart"></i>
                            <span>
                                <?= Loc::getMessage('C_WIDGET_PRODUCTS_3_BASKET_ADD') ?>
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
                            'widget-item-purchase-button',
                            'widget-item-purchase-button-added',
                            'intec-cl-background-light'
                        ],
                        'data' => [
                            'basket-id' => $arItem['ID'],
                            'basket-state' => 'none'
                        ]
                    ]) ?>
                        <span class="widget-item-purchase-button-content">
                            <span>
                                <?= Loc::getMessage('C_WIDGET_PRODUCTS_3_BASKET_ADDED') ?>
                            </span>
                        </span>
                    <?= Html::endTag('a') ?>
                </div>
            <?php } else { ?>
                <?php if ($arItem['CATALOG_SUBSCRIBE'] == 'Y') { ?>
                    <?php if (!empty($arItem['OFFERS']) && $bOffer == false) { ?>
                        <?php return; ?>
                    <?php } ?>
                    <div class="widget-item-purchase-buttons" data-offer="<?= $bOffer ? $arItem['ID'] : 'false' ?>">
                        <?php $APPLICATION->IncludeComponent(
                            "bitrix:catalog.product.subscribe",
                            ".default",
                            [
                                "BUTTON_CLASS" => "widget-item-purchase-button intec-cl-background intec-cl-background-light-hover",
                                "BUTTON_ID" => $sTemplateId . "_subscribe_" . $arItem['ID'],
                                "PRODUCT_ID" => $arItem['ID']
                            ],
                            $component
                        ); ?>
                    </div>
                <?php } else { ?>
                    <div class="widget-item-purchase-buttons" data-offer="<?= $bOffer ? $arItem['ID'] : 'false' ?>">
                        <?= Html::beginTag('div', [
                            'class' => [
                                'widget-item-purchase-button',
                                'widget-item-purchase-button-unavailable'
                            ]
                        ]) ?>
                            <span class="widget-item-purchase-button-content">
                                <i class="far fa-times-circle"></i>
                                <span>
                                    <?= Loc::getMessage('C_WIDGET_PRODUCTS_3_BASKET_NOT_AVAILABLE') ?>
                                </span>
                            </span>
                        <?= Html::endTag('div') ?>
                    </div>
                <?php } ?>
            <?php } ?>
        <?php } else if ($arItem['ACTION'] === 'detail') { ?>
            <div class="widget-item-purchase-detail">
                <?= Html::beginTag($arResult['QUICK_VIEW']['DETAIL'] ? 'div' : 'a', [
                    'class' => [
                        'widget-item-purchase-button',
                        'intec-cl-background',
                        'intec-cl-background-light-hover'
                    ],
                    'href' => !$arResult['QUICK_VIEW']['DETAIL'] ? Html::decode($sLink) : null,
                    'data' => [
                        'role' => $arResult['QUICK_VIEW']['DETAIL'] ? 'quick.view' : null
                    ]
                ]) ?>
                    <span class="widget-item-purchase-button-content">
                        <span>
                            <?= Loc::getMessage('C_WIDGET_PRODUCTS_3_MORE_INFO') ?>
                        </span>
                    </span>
                <?= Html::endTag($arResult['QUICK_VIEW']['DETAIL'] ? 'div' : 'a') ?>
            </div>
        <?php } else if ($arItem['ACTION'] === 'order') { ?>
            <div class="widget-item-purchase-order">
                <?= Html::beginTag('div', [
                    'class' => [
                        'widget-item-purchase-button',
                        'intec-cl-background',
                        'intec-cl-background-light-hover'
                    ],
                    'data' => [
                        'role' => 'item.order'
                    ]
                ]) ?>
                    <span class="widget-item-purchase-button-content">
                        <span>
                            <?= Loc::getMessage('C_WIDGET_PRODUCTS_3_ORDER') ?>
                        </span>
                    </span>
                <?= Html::endTag('div') ?>
            </div>
        <?php } ?>
    <?php };

    $fRender($arItem, false);

    if ($arItem['ACTION'] === 'buy' && $arVisual['OFFERS']['USE'] && !empty($arItem['OFFERS'])) {
        foreach ($arItem['OFFERS'] as &$arOffer) {
            $fRender($arOffer, true);

            unset($arOffer);
        }

        $arItem['ACTION'] = 'detail';
        $fRender($arItem, false);
    }
};