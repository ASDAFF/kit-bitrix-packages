<?php if (defined("B_PROLOG_INCLUDED") && B_PROLOG_INCLUDED === true) ?>
<?php

use Bitrix\Main\Localization\Loc;
use intec\core\helpers\ArrayHelper;
use intec\core\helpers\Html;

/**
 * @var array $arVisual
 */

$vPrice = function (&$arItem) use (&$arVisual) {
    $vPriceRange = function (&$arItem, $bOffer = false) {

        if (count($arItem['ITEM_PRICES']) <= 1)
            return;

        ?>
        <div class="catalog-section-item-price-range-items" data-offer="<?= $bOffer ? $arItem['ID'] : 'false' ?>" data-role="price.range">
            <?= Html::tag('div', Loc::getMessage('C_CATALOG_SECTION_CATALOG_TEXT_2_PRICE_RANGE_TITLE', [
                '#MEASURE#' => !empty($arItem['CATALOG_MEASURE_NAME']) ? $arItem['CATALOG_MEASURE_NAME'] : ''
            ]), [
                'class' => 'catalog-section-item-price-range-items-title'
            ]) ?>
            <?php foreach ($arItem['ITEM_PRICES'] as $arPrice) { ?>
                <div class="catalog-section-item-price-range-item intec-grid intec-grid-a-h-between">
                    <?php if (!empty($arPrice['QUANTITY_FROM']) && !empty($arPrice['QUANTITY_TO'])) { ?>
                        <?= Html::tag('div', Loc::getMessage('C_CATALOG_SECTION_CATALOG_TEXT_2_PRICE_RANGE_FROM_TO', [
                            '#FROM#' => $arPrice['QUANTITY_FROM'],
                            '#TO#' => $arPrice['QUANTITY_TO']
                        ]), [
                            'class' => 'catalog-section-item-price-range-quantity'
                        ]) ?>
                    <?php } else if (empty($arPrice['QUANTITY_FROM']) && !empty($arPrice['QUANTITY_TO'])) { ?>
                        <?= Html::tag('div', Loc::getMessage('C_CATALOG_SECTION_CATALOG_TEXT_2_PRICE_RANGE_TO', [
                            '#FROM#' => !empty($arItem['CATALOG_MEASURE_RATIO']) ? $arItem['CATALOG_MEASURE_RATIO'] : '1',
                            '#TO#' => $arPrice['QUANTITY_TO']
                        ]), [
                            'class' => 'catalog-section-item-price-range-quantity'
                        ]) ?>
                    <?php } else if (!empty($arPrice['QUANTITY_FROM']) && empty($arPrice['QUANTITY_TO'])) { ?>
                        <?= Html::tag('div', Loc::getMessage('C_CATALOG_SECTION_CATALOG_TEXT_2_PRICE_RANGE_FROM', [
                            '#FROM#' => $arPrice['QUANTITY_FROM']
                        ]), [
                            'class' => 'catalog-section-item-price-range-quantity'
                        ]) ?>
                    <?php } ?>
                    <div class="catalog-section-item-price-range-value">
                        <?= $arPrice['PRINT_PRICE'] ?>
                    </div>
                </div>
            <?php } ?>
        </div>
    <? };

    $arPrice = null;

    if (!empty($arItem['ITEM_PRICES']))
        $arPrice = ArrayHelper::getFirstValue($arItem['ITEM_PRICES']);
?>
    <?= Html::beginTag('div', [
        'class' => 'catalog-section-item-price',
        'data' => [
            'role' => 'item.price',
            'show' => !empty($arPrice),
            'discount' => !empty($arPrice) && $arPrice['PERCENT'] > 0 ? 'true' : 'false'
        ]
    ]) ?>
        <div class="catalog-section-item-price-wrapper">
            <div class="catalog-section-item-price-discount">
                <div class="catalog-section-item-price-discount-wrapper intec-cl-border-hover">
                    <?php if (!$arVisual['OFFERS']['USE'] && !empty($arItem['OFFERS'])) { ?>
                        <?= Loc::getMessage('C_CATALOG_SECTION_CATALOG_TEXT_2_PRICE_FORM') ?>
                    <?php } ?>
                    <span data-role="item.price.discount">
                        <?= !empty($arPrice) ? $arPrice['PRINT_PRICE'] : null ?>
                    </span>
                    <?php

                    $vPriceRange($arItem, false);

                    if ($arVisual['OFFERS']['USE'] && !empty($arItem['OFFERS'])) {
                        foreach ($arItem['OFFERS'] as &$arOffer) {
                            $vPriceRange($arOffer, true);

                            unset($arOffer);
                        }
                    }

                    ?>
                </div>
            </div>
            <div class="catalog-section-item-price-base intec-grid intec-grid-a-v-center intec-grid-a-h-center  intec-grid-a-h-900-start">
                <div class="catalog-section-item-price-base-wrapper">
                    <?php if (!$arVisual['OFFERS']['USE'] && !empty($arItem['OFFERS'])) { ?>
                        <?= Loc::getMessage('C_CATALOG_SECTION_CATALOG_TEXT_2_PRICE_FORM') ?>
                    <?php } ?>
                    <span data-role="item.price.base">
                        <?= !empty($arPrice) ? $arPrice['PRINT_BASE_PRICE'] : null ?>
                    </span>
                </div>
                <div class="catalog-section-item-price-percent">
                    <span data-role="item.price.percent">
                        <?= !empty($arPrice) ? '-'.$arPrice['PERCENT'].'%' : null ?>
                    </span>
                </div>
            </div>
        </div>
    <?= Html::endTag('div') ?>
<?php };