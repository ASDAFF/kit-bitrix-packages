<?php if (!defined('B_PROLOG_INCLUDED') && B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;
use intec\core\helpers\Html;

/**
 * @var array $arResult
 */
?>
<?php $vPriceRange = function (&$arItem, $bOffer = false) {

    if (!empty($arItem['OFFERS']) && !$bOffer)
        return;

    if (count($arItem['ITEM_PRICES']) < 2)
        return;

?>
    <?= Html::beginTag('div', [
        'class' => 'catalog-element-price-range',
        'data' => [
            'offer' => $bOffer ? $arItem['ID'] : 'false'
        ]
    ]) ?>
        <div class="intec-grid intec-grid-wrap intec-grid-i-h-12 intec-grid-i-v-6">
            <?php foreach ($arItem['ITEM_PRICES'] as $arPrice) { ?>
                <div class="intec-grid-item-auto intec-grid-item-500-1">
                    <div class="catalog-element-price-range-item">
                        <?php if (!empty($arPrice['QUANTITY_FROM']) && !empty($arPrice['QUANTITY_TO'])) { ?>
                            <?= Html::tag('span', Loc::getMessage('C_CATALOG_ELEMENT_DEFAULT_3_TEMPLATE_PRICE_RANGE_FROM_TO', [
                                '#FROM#' => $arPrice['QUANTITY_FROM'],
                                '#TO#' => $arPrice['QUANTITY_TO']
                            ]), [
                                'class' => 'catalog-element-price-range-quantity'
                            ]) ?>
                        <?php } else if (empty($arPrice['QUANTITY_FROM']) && !empty($arPrice['QUANTITY_TO'])) { ?>
                            <?= Html::tag('span', Loc::getMessage('C_CATALOG_ELEMENT_DEFAULT_3_TEMPLATE_PRICE_RANGE_TO', [
                                '#FROM#' => !empty($arItem['CATALOG_MEASURE_RATIO']) ? $arItem['CATALOG_MEASURE_RATIO'] : '1',
                                '#TO#' => $arPrice['QUANTITY_TO']
                            ]), [
                                'class' => 'catalog-element-price-range-quantity'
                            ]) ?>
                        <?php } else if (!empty($arPrice['QUANTITY_FROM']) && empty($arPrice['QUANTITY_TO'])) { ?>
                            <?= Html::tag('span', Loc::getMessage('C_CATALOG_ELEMENT_DEFAULT_3_TEMPLATE_PRICE_RANGE_FROM', [
                                '#FROM#' => $arPrice['QUANTITY_FROM']
                            ]), [
                                'class' => 'catalog-element-price-range-quantity'
                            ]) ?>
                        <?php } ?>
                        <?= Html::tag('span', $arPrice['PRINT_PRICE'], [
                            'class' => 'catalog-element-price-range-value'
                        ]) ?>
                    </div>
                </div>
            <?php } ?>
        </div>
    <?= Html::endTag('div') ?>
<?php } ?>
<div class="catalog-element-block">
    <?php $vPriceRange($arResult) ?>
    <?php if (!empty($arResult['OFFERS'])) {
        foreach ($arResult['OFFERS'] as &$arOffer)
            $vPriceRange($arOffer, true);

        unset($arOffer);
    } ?>
</div>
<?php unset($vPriceRange) ?>