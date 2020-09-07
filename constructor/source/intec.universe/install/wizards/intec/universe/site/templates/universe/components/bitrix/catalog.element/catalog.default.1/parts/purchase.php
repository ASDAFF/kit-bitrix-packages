<?php if (!defined('B_PROLOG_INCLUDED') && B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;
use intec\core\helpers\ArrayHelper;
use intec\core\helpers\Html;

/**
 * @var array $arParams
 * @var array $arResult
 * @var array $arVisual
 */

?>
<div class="catalog-element-purchase" data-role="purchase">
    <?php if ($arVisual['COUNTER']['SHOW']) { ?>
        <div class="catalog-element-purchase-counter">
            <div class="catalog-element-purchase-counter-control intec-ui intec-ui-control-numeric intec-ui-view-1 intec-ui-scheme-current" data-role="counter">
                <?= Html::tag('a', '-', [
                    'class' => 'intec-ui-part-decrement',
                    'href' => 'javascript:void(0)',
                    'data-type' => 'button',
                    'data-action' => 'decrement'
                ]) ?>
                <?= Html::input('text', null, 0, [
                    'data-type' => 'input',
                    'class' => 'intec-ui-part-input'
                ]) ?>
                <?= Html::tag('a', '+', [
                    'class' => 'intec-ui-part-increment',
                    'href' => 'javascript:void(0)',
                    'data-type' => 'button',
                    'data-action' => 'increment'
                ]) ?>
            </div>
            <div class="catalog-element-purchase-counter-quantity">
            <?php
                $vMeasure = function (&$arItem, $bOffer = false) {
                    if (empty($arItem['CATALOG_MEASURE_NAME']))
                        return;
                ?>
                    <div class="catalog-element-purchase-counter-quantity-wrapper" data-offer="<?= $bOffer ? $arItem['ID'] : 'false' ?>">
                        <?= $arItem['CATALOG_MEASURE_NAME'].'.' ?>
                    </div>
                <?php };

                $vMeasure($arResult);

                if (!empty($arResult['OFFERS']))
                    foreach ($arResult['OFFERS'] as &$arOffer) {
                        $vMeasure($arOffer, true);

                        unset($arOffer);
                    }

                unset($vMeasure);
            ?>
            </div>
        </div>
    <?php } ?>
    <div class="catalog-element-purchase-order">
        <?php if ($arResult['ACTION'] !== 'none') { ?>
            <?php $vOrder = function (&$arItem, $bOffer = false) use (&$arResult, &$APPLICATION, &$component, &$sTemplateId) { ?>
                <?php if ($arResult['ACTION'] === 'buy') { ?>
                    <?php if (!$arItem['CAN_BUY']) { ?>
                        <?php if ($arItem['CATALOG_SUBSCRIBE'] == 'Y') { ?>
                            <?php if (!empty($arItem['OFFERS']) && $bOffer == false) {
                                return;
                            } ?>
                            <div class="catalog-element-purchase-order-subscribe" data-offer="<?= $bOffer ? $arItem['ID'] : 'false' ?>">
                                <?php $APPLICATION->IncludeComponent(
                                    "bitrix:catalog.product.subscribe",
                                    ".default",
                                    [
                                        "BUTTON_CLASS" => "intec-button intec-button-cl-common intec-button-s-7 intec-button-fs-16",
                                        "BUTTON_ID" => $sTemplateId . "_subscribe_" . $arItem['ID'],
                                        "PRODUCT_ID" => $arItem['ID']
                                    ],
                                    $component
                                ); ?>
                            </div>
                        <?php } else {
                            return;
                        }
                    } else { ?>
                        <?php $arPrice = ArrayHelper::getValue($arItem, ['ITEM_PRICES', 0]) ?>
                        <div class="catalog-element-purchase-order-buttons" data-offer="<?= $bOffer ? $arItem['ID'] : 'false' ?>">
                            <?= Html::beginTag('div', [
                                'class' => [
                                    'intec-ui',
                                    'intec-ui-control-basket-button',
                                    'catalog-element-purchase-order-button',
                                    'catalog-element-purchase-order-button-add',
                                    'intec-button',
                                    'intec-button-cl-common',
                                    'intec-button-block',
                                    'intec-button-md ',
                                    'intec-button-s-7',
                                    'intec-button-fs-16',
                                    'intec-button-w-icon'
                                ],
                                'data' => [
                                    'basket-id' => $arItem['ID'],
                                    'basket-action' => 'add',
                                    'basket-state' => 'none',
                                    'basket-quantity' => $arItem['CATALOG_MEASURE_RATIO'],
                                    'basket-price' => !empty($arPrice) ? $arPrice['PRICE_TYPE_ID'] : null
                                ]
                            ]) ?>
                                <span class="intec-ui-part-content">
                                    <i class="intec-button-icon glyph-icon-cart"></i>
                                    <span class="intec-button-text">
                                        <?= Loc::getMessage('C_CATALOG_ELEMENT_CATALOG_DEFAULT_1_PURCHASE_ORDER') ?>
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
                                    'catalog-element-purchase-order-button',
                                    'catalog-element-purchase-order-button-added',
                                    'intec-button',
                                    'intec-button-cl-common',
                                    'intec-button-block',
                                    'intec-button-md ',
                                    'intec-button-s-7',
                                    'intec-button-fs-16',
                                    'intec-button-w-icon',
                                    'hover'
                                ],
                                'data' => [
                                    'basket-id' => $arItem['ID'],
                                    'basket-state' => 'none'
                                ]
                            ]) ?>
                                <i class="intec-button-icon glyph-icon-cart"></i>
                                <span class="intec-button-text">
                                    <?= Loc::getMessage('C_CATALOG_ELEMENT_CATALOG_DEFAULT_1_PURCHASE_ORDERED') ?>
                                </span>
                            <?= Html::endTag('a') ?>
                        </div>
                    <?php } ?>
                <?php } else { ?>
                    <div class="catalog-element-purchase-order-buttons">
                        <?= Html::beginTag('div', [
                            'class' => [
                                'catalog-element-purchase-order-button',
                                'intec-button',
                                'intec-button-cl-common',
                                'intec-button-block',
                                'intec-button-md ',
                                'intec-button-s-7',
                                'intec-button-fs-16',
                                'intec-button-w-icon'
                            ],
                            'data' => [
                                'role' => 'order'
                            ]
                        ]) ?>
                            <span class="intec-ui-part-content">
                                <i class="intec-button-icon glyph-icon-cart"></i>
                                <span class="intec-button-text">
                                    <?= Loc::getMessage('C_CATALOG_ELEMENT_CATALOG_DEFAULT_1_PURCHASE_FORM_ORDER') ?>
                                </span>
                            </span>
                        <?= Html::endTag('div') ?>
                    </div>
                <?php } ?>
            <?php };

            $vOrder($arResult);

            if (!empty($arResult['OFFERS']) && $arResult['ACTION'] === 'buy')
                foreach ($arResult['OFFERS'] as &$arOffer) {
                    $vOrder($arOffer, true);

                    unset($arOffer);
                }

            unset($vOrder) ?>
            <?php if ($arResult['ORDER_FAST']['USE']) { ?>
                <div class="catalog-element-purchase-order-fast intec-button intec-button-link intec-button-w-icon" data-role="orderFast">
                    <i class="intec-button-icon glyph-icon-one_click"></i>
                    <div class="intec-button-text">
                        <?= Loc::getMessage('C_CATALOG_ELEMENT_CATALOG_DEFAULT_1_PURCHASE_ORDER_FAST') ?>
                    </div>
                </div>
            <?php } ?>
        <?php } ?>
    </div>
    <div class="catalog-element-purchase-buttons">
    <?php
        $vButtons = function (&$arItem, $bOffer = false) use (&$arResult) { ?>
            <div class="catalog-element-purchase-buttons-wrapper" data-offer="<?= $bOffer ? $arItem['ID'] : 'false' ?>">
                <?php if ($arResult['COMPARE']['USE']) { ?>
                    <?= Html::beginTag('div', [
                        'class' => [
                            'catalog-element-purchase-button',
                            'catalog-element-purchase-button-compare',
                            'intec-cl-text-hover'
                        ],
                        'data' => [
                            'compare-id' => $arItem['ID'],
                            'compare-action' => 'add',
                            'compare-code' => $arResult['COMPARE']['CODE'],
                            'compare-state' => 'none',
                            'compare-iblock' => $arResult['IBLOCK_ID']
                        ]
                    ]) ?>
                        <i class="glyph-icon-compare"></i>
                    <?= Html::endTag('div') ?>
                    <?= Html::beginTag('div', [
                        'class' => [
                            'catalog-element-purchase-button',
                            'catalog-element-purchase-button-compared',
                            'intec-cl-text'
                        ],
                        'data' => [
                            'compare-id' => $arItem['ID'],
                            'compare-action' => 'remove',
                            'compare-code' => $arResult['COMPARE']['CODE'],
                            'compare-state' => 'none',
                            'compare-iblock' => $arResult['IBLOCK_ID']
                        ]
                    ]) ?>
                        <i class="glyph-icon-compare"></i>
                    <?= Html::endTag('div') ?>
                <?php } ?>
                <?php if ($arResult['DELAY']['USE'] && $arItem['CAN_BUY'] && ($bOffer || empty($arItem['OFFERS']))) { ?>
                    <?php $arPrice = ArrayHelper::getValue($arItem, ['ITEM_PRICES', 0]) ?>
                    <?= Html::beginTag('div', [
                        'class' => [
                            'catalog-element-purchase-button',
                            'catalog-element-purchase-button-delay',
                            'intec-cl-text-hover'
                        ],
                        'data' => [
                            'basket-id' => $arItem['ID'],
                            'basket-action' => 'delay',
                            'basket-state' => 'none',
                            'basket-price' => !empty($arPrice) ? $arPrice['PRICE_TYPE_ID'] : null
                        ]
                    ]) ?>
                        <i class="fas fa-heart"></i>
                    <?= Html::endTag('div') ?>
                    <?= Html::beginTag('div', [
                        'class' => [
                            'catalog-element-purchase-button',
                            'catalog-element-purchase-button-delayed',
                            'intec-cl-text'
                        ],
                        'data' => [
                            'basket-id' => $arItem['ID'],
                            'basket-action' => 'remove',
                            'basket-state' => 'none'
                        ]
                    ]) ?>
                        <i class="fas fa-heart"></i>
                    <?= Html::endTag('div') ?>
                <?php } ?>
            </div>
        <?php };

        $vButtons($arResult);

        if (!empty($arResult['OFFERS']))
            foreach ($arResult['OFFERS'] as &$arOffer) {
                $vButtons($arOffer, true);

                unset($arOffer);
            }

        unset($vButtons);
    ?>
    </div>
</div>