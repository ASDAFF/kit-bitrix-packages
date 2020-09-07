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
<?php $vOrder = function (&$arItem, $bOffer = false) use (&$arResult, &$arParams, &$arVisual) { ?>
    <?php if ($arResult['ACTION'] === 'buy') { ?>
        <?php if (!$arItem['CAN_BUY']) {
            return;
        } else { ?>
            <?php $arPrice = ArrayHelper::getValue($arItem, ['ITEM_PRICES', 0]) ?>
            <div class="catalog-element-purchase-order-buttons" data-offer="<?= $bOffer ? $arItem['ID'] : 'false' ?>">
                <?= Html::beginTag('div', [
                    'class' => [
                        'catalog-element-purchase-order-button',
                        'catalog-element-purchase-order-button-add',
                        'intec-ui' => [
                            '',
                            'control-basket-button',
                            'control-button',
                            'mod-block',
                            'mod-round-5',
                            'scheme-current'
                        ]
                    ],
                    'data' => [
                        'basket-id' => $arItem['ID'],
                        'basket-action' => 'add',
                        'basket-state' => 'none',
                        'basket-quantity' => $arItem['CATALOG_MEASURE_RATIO'],
                        'basket-price' => !empty($arPrice) ? $arPrice['PRICE_TYPE_ID'] : null
                    ]
                ]) ?>
                    <div class="intec-ui-part-content">
                        <i class="intec-button-icon glyph-icon-cart"></i>
                        <?= Html::beginTag('div', [
                            'class' => 'catalog-element-panel-mobile-price-content',
                            'data' => [
                                'role' => 'price',
                                'show' => !empty($arPrice) ? 'true' : 'false',
                                'discount' => !empty($arPrice) && $arPrice['PERCENT'] > 0 ? 'true' : 'false'
                            ]
                        ]) ?>
                            <div class="catalog-element-panel-mobile-price-discount" data-role="price.discount">
                                <?= !empty($arPrice) ? $arPrice['PRINT_DISCOUNT'] : null ?>
                            </div>
                            <div class="catalog-element-panel-mobile-price-percent-wrap">
                                <div class="catalog-element-panel-mobile-price-base" data-role="price.base">
                                    <?= !empty($arPrice) ? $arPrice['PRINT_PRICE'] : null ?>
                                </div>
                            </div>
                        <?= Html::endTag('div') ?>
                    </div>
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
                        'intec-ui' => [
                            '',
                            'control-button',
                            'mod-block',
                            'mod-round-5',
                            'scheme-current'
                        ]
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
                    'intec-ui' => [
                        '',
                        'control-button',
                        'mod-block',
                        'mod-round-5',
                        'scheme-current'
                    ]
                ],
                'data' => [
                    'role' => 'order'
                ]
            ]) ?>
                <span class="intec-ui-part-icon">
                    <i class="intec-button-icon glyph-icon-cart"></i>
                </span>
                <span class="intec-ui-part-content">
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

unset($vOrder);