<?php if (!defined('B_PROLOG_INCLUDED') && B_PROLOG_INCLUDED !== true) die();

use intec\core\helpers\Html;

/**
 * @var array $arResult
 */

?>
<?php $vPriceExtended = function (&$arItem, $bOffer = false) { ?>
    <?php if (empty($arItem['PRICES']) || !empty($arItem['OFFERS']) && !$bOffer) return ?>
    <?php $sBasePriceId = $arItem['MIN_PRICE']['PRICE_ID'] ?>
    <?= Html::beginTag('div', [
        'class' => 'catalog-element-price-extended',
        'data' => [
            'offer' => $bOffer ? $arItem['ID'] : 'false'
        ]
    ]) ?>
        <?php foreach ($arItem['PRICES'] as $arPrice) { ?>
            <?php if ($arPrice['PRICE_ID'] === $sBasePriceId) continue ?>
            <div class="catalog-element-price-extended-item">
                <div class="catalog-element-price-extended-name">
                    <?= !empty($arPrice['TITLE']) ? $arPrice['TITLE'] : $arPrice['CODE'] ?>
                </div>
                <div class="catalog-element-price-extended-value">
                    <?= $arPrice['PRINT_DISCOUNT_VALUE'] ?>
                </div>
            </div>
        <?php } ?>
    <?= Html::endTag('div') ?>
<?php } ?>
<div class="catalog-element-price-popup">
    <?php $vPriceExtended($arResult) ?>
    <?php if (!empty($arResult['OFFERS'])) {
        foreach ($arResult['OFFERS'] as &$arOffer)
            $vPriceExtended($arOffer, true);

        unset($arOffer);
    } ?>
</div>
<?php unset($vPriceExtended) ?>