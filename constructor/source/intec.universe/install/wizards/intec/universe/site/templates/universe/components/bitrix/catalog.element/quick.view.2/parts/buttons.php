<?php if (!defined('B_PROLOG_INCLUDED') && B_PROLOG_INCLUDED !== true) die();

use intec\core\helpers\ArrayHelper;
use intec\core\helpers\Html;

/**
 * @var array $arResult
 * @var array $arVisual
 */

?>
<div class="catalog-element-buttons">
<?php
    $vButtons = function (&$arItem, $bOffer = false) use (&$arResult, &$arVisual) { ?>
        <div class="catalog-element-buttons-wrapper" data-offer="<?= $bOffer ? $arItem['ID'] : 'false' ?>">
            <div class="intec-grid intec-grid-a-v-center intec-grid-i-h-8">
                <?php if ($arResult['COMPARE']['USE']) { ?>
                    <div class="intec-grid-item-auto">
                        <?= Html::beginTag('div', [
                            'class' => [
                                'catalog-element-button',
                                'catalog-element-button-compare',
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
                                'catalog-element-button',
                                'catalog-element-button-compared',
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
                    </div>
                <?php } ?>
                <?php if ($arResult['DELAY']['USE'] && $arItem['CAN_BUY'] && ($bOffer || empty($arItem['OFFERS']))) { ?>
                    <?php $arPrice = ArrayHelper::getValue($arItem, ['ITEM_PRICES', 0]) ?>
                    <div class="intec-grid-item-auto">
                        <?= Html::beginTag('div', [
                            'class' => [
                                'catalog-element-button',
                                'catalog-element-button-delay',
                                'intec-cl-text-hover'
                            ],
                            'data' => [
                                'basket-id' => $arItem['ID'],
                                'basket-action' => 'delay',
                                'basket-state' => 'none',
                                'basket-price' => !empty($arPrice) ? $arPrice['PRICE_TYPE_ID'] : null,
                            ]
                        ]) ?>
                            <i class="fas fa-heart"></i>
                        <?= Html::endTag('div') ?>
                        <?= Html::beginTag('div', [
                            'class' => [
                                'catalog-element-button',
                                'catalog-element-button-delayed',
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
                    </div>
                <?php } ?>
            </div>
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