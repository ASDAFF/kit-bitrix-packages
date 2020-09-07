<?php if (!defined('B_PROLOG_INCLUDED') && B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;
use intec\core\helpers\Html;
use intec\core\helpers\ArrayHelper;

/**
 * @var array $arVisual
 */

$vPrice = function (&$arItem) use (&$arVisual) {
    $vPriceRange = function (&$arItem, $bOffer = false) {
        if (count($arItem['ITEM_PRICES']) <= 1)
            return;

    ?>
        <div class="widget-item-price-extended-wrap" data-offer="<?= $bOffer ? $arItem['ID'] : 'false' ?>">
            <div class="widget-item-price-extended-button intec-cl-border-hover intec-cl-background-hover intec-grid-item-auto" data-role="price.extended.popup.toggle">
                <div class="dots intec-grid intec-grid-a-v-center intec-grid-a-h-center">
                    <i class="dot intec-grid-item-auto"></i>
                    <i class="dot intec-grid-item-auto"></i>
                    <i class="dot intec-grid-item-auto"></i>
                </div>
            </div>
            <div class="widget-item-price-extended" data-role="price.extended.popup.window">
                <div class="widget-item-price-extended-background">
                    <div class="widget-item-price-extended-header intec-grid intec-grid-a-v-center intec-grid-a-h-between">
                        <div class="widget-item-price-extended-title">
                            <?= Loc::getMessage('C_WIDGET_PRODUCTS_4_PRICE_EXTENDED_TITLE') ?>
                        </div>
                        <?= Html::beginTag('div', [
                            'class' => 'widget-item-price-extended-button-close',
                            'data' => [
                                'role' => 'price.extended.popup.close'
                            ]
                        ]) ?>
                        <i class="fal fa-times"></i>
                        <?= Html::endTag('div') ?>
                    </div>
                    <div class="widget-item-price-extended-items">
                        <?php foreach ($arItem['ITEM_PRICES'] as $arPrice) { ?>
                            <div class="widget-item-price-extended-item intec-grid intec-grid-a-h-between">
                                <div class="background-border"></div>
                                <?php if (!empty($arPrice['QUANTITY_FROM']) && !empty($arPrice['QUANTITY_TO'])) { ?>
                                    <?= Html::tag('div', Loc::getMessage('C_WIDGET_PRODUCTS_4_PRICE_EXTENDED_FROM_TO', [
                                        '#FROM#' => $arPrice['QUANTITY_FROM'],
                                        '#TO#' => $arPrice['QUANTITY_TO']
                                    ]), [
                                        'class' => 'widget-item-price-extended-quantity'
                                    ]) ?>
                                <?php } else if (empty($arPrice['QUANTITY_FROM']) && !empty($arPrice['QUANTITY_TO'])) { ?>
                                    <?= Html::tag('div', Loc::getMessage('C_WIDGET_PRODUCTS_4_PRICE_EXTENDED_TO', [
                                        '#FROM#' => !empty($arItem['CATALOG_MEASURE_RATIO']) ? $arItem['CATALOG_MEASURE_RATIO'] : '1',
                                        '#TO#' => $arPrice['QUANTITY_TO']
                                    ]), [
                                        'class' => 'widget-item-price-extended-quantity'
                                    ]) ?>
                                <?php } else if (!empty($arPrice['QUANTITY_FROM']) && empty($arPrice['QUANTITY_TO'])) { ?>
                                    <?= Html::tag('div', Loc::getMessage('C_WIDGET_PRODUCTS_4_PRICE_EXTENDED_FROM', [
                                        '#FROM#' => $arPrice['QUANTITY_FROM']
                                    ]), [
                                        'class' => 'widget-item-price-extended-quantity'
                                    ]) ?>
                                <?php } ?>
                                <div class="widget-item-price-extended-value">
                                    <?= $arPrice['PRINT_PRICE'] ?>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    <?php };

    $arPrice = null;

    if (!empty($arItem['ITEM_PRICES']))
        $arPrice = ArrayHelper::getFirstValue($arItem['ITEM_PRICES']);
    ?>

    <?= Html::beginTag('div', [
        'class' => 'widget-item-price',
        'data' => [
            'role' => 'item.price',
            'show' => !empty($arPrice),
            'discount' => !empty($arPrice) && $arPrice['PERCENT'] > 0 ? 'true' : 'false'
        ]
    ]) ?>
        <?= Html::beginTag('div', [
            'class' => [
                'widget-item-price-wrapper',
                'intec-grid' => [
                    '',
                    'wrap',
                    'a-v-end',
                    'i-8'
                ]
            ]
        ]) ?>
            <div class="intec-grid-item-auto">
                <div class="widget-item-price-discount intec-grid intec-grid-a-v-center">
                    <?php if (count($arItem['ITEM_PRICES']) > 1) { ?>
                    <?php
                        $vPriceRange($arItem, false);

                        if ($arVisual['OFFERS']['USE'] && !empty($arItem['OFFERS'])) {
                            foreach ($arItem['OFFERS'] as &$arOffer) {
                                $vPriceRange($arOffer, true);

                                unset($arOffer);
                            }
                        }
                    ?>
                    <?php } ?>
                    <div class="intec-grid-item-auto">
                        <span data-role="item.price.discount">
                            <?= !empty($arPrice) ? $arPrice['PRINT_PRICE'] : null ?>
                        </span>
                        <?php if (!empty($arItem['CATALOG_MEASURE_NAME'])) { ?>
                            <span>
                                <?= '/ ' . $arItem['CATALOG_MEASURE_NAME'] ?>
                            </span>
                        <?php } ?>
                    </div>
                </div>
            </div>
            <div class="intec-grid-item-auto">
                <div class="widget-item-price-base" data-role="item.price.base">
                    <?= !empty($arPrice) ? $arPrice['PRINT_BASE_PRICE'] : null ?>
                </div>
            </div>
        <?= Html::endTag('div') ?>
    <?= Html::endTag('div') ?>
<?php } ?>