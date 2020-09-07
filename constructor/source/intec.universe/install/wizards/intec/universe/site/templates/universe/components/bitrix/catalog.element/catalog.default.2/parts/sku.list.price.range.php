<?php if (!defined('B_PROLOG_INCLUDED') && B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;
use intec\core\helpers\Html;

/**
 * @var array $arResult
 * @var array $arVisual
 * @var array $arPrice
 */

?>
<?php $vPriceRangeSKUList = function (&$arItem, $bOffer = false) {

    if (count($arItem['ITEM_PRICES']) <= 1)
        return;

    ?>
    <?= Html::beginTag('div', [
        'class' => 'catalog-element-offer-price-range',
        'data' => [
            'offer' => $bOffer ? $arItem['ID'] : 'false'
        ]
    ]) ?>
    <div class="catalog-element-offer-price-range-items">
        <?php foreach ($arItem['ITEM_PRICES'] as $arPrice) { ?>
            <div class="catalog-element-offer-price-range-item intec-grid intec-grid-a-v-baseline">
                <div class="catalog-element-offer-price-range-item-text intec-grid-item-auto">
                    <?php

                    if (!empty($arPrice['QUANTITY_FROM'])) {
                        echo Loc::getMessage('C_CATALOG_ELEMENT_CATALOG_DEFAULT_2_PRICE_RANGE_FROM').' ';
                        echo $arPrice['QUANTITY_FROM'];

                        if (!empty($arPrice['QUANTITY_TO'])) {
                            echo ' ';
                        } else {
                            echo ' '.Loc::getMessage('C_CATALOG_ELEMENT_CATALOG_DEFAULT_2_PRICE_RANGE_MORE');
                        }
                    }

                    if (!empty($arPrice['QUANTITY_TO'])) {
                        echo Loc::getMessage('C_CATALOG_ELEMENT_CATALOG_DEFAULT_2_PRICE_RANGE_TO').' ';
                        echo $arPrice['QUANTITY_TO'];
                    }

                    ?>
                </div>
                <div class="catalog-element-offer-price-range-item-line intec-grid-item"></div>
                <div class="catalog-element-offer-price-range-item-value intec-grid-item-auto">
                    <?php

                    echo $arPrice['PRINT_PRICE'];

                    if (!empty($arItem['CATALOG_MEASURE_NAME']))
                        echo '/'.$arItem['CATALOG_MEASURE_NAME'].'.';

                    ?>
                </div>
            </div>
        <?php } ?>
    </div>
    <?= Html::endTag('div') ?>
<? };