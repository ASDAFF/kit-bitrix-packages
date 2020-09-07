<?php if (!defined('B_PROLOG_INCLUDED') && B_PROLOG_INCLUDED !== true) die();

use intec\core\helpers\ArrayHelper;
use intec\core\helpers\Html;

/**
 * @var array $arResult
 */

?>
<?php $vPanelButtons = function (&$arItem, $bOffer = false) use (&$arResult) { ?>
    <div class="catalog-element-panel-buttons-wrapper" data-offer="<?= $bOffer ? $arItem['ID'] : 'false' ?>">
        <div class="intec-grid intec-grid-a-v-center intec-grid-i-h-8">
            <?php if ($arResult['DELAY']['USE'] && $arItem['CAN_BUY'] && ($bOffer || empty($arItem['OFFERS']))) { ?>
                <?php $arPrice = ArrayHelper::getValue($arItem, ['ITEM_PRICES', 0]) ?>
                <div class="intec-grid-item-auto">
                    <?= Html::beginTag('div', [
                        'class' => [
                            'catalog-element-panel-button',
                            'catalog-element-panel-button-delay',
                            'intec-cl-text-hover'
                        ],
                        'data' => [
                            'basket-id' => $arItem['ID'],
                            'basket-action' => 'delay',
                            'basket-state' => 'none',
                            'basket-price' => !empty($arPrice) ? $arPrice['PRICE_TYPE_ID'] : null
                        ]
                    ]) ?>
                        <span class="catalog-element-panel-button-icon">
                            <i class="fas fa-heart"></i>
                        </span>
                    <?= Html::endTag('div') ?>
                    <?= Html::beginTag('div', [
                        'class' => [
                            'catalog-element-panel-button',
                            'catalog-element-panel-button-delayed',
                            'intec-cl-text'
                        ],
                        'data' => [
                            'basket-id' => $arItem['ID'],
                            'basket-action' => 'remove',
                            'basket-state' => 'none'
                        ]
                    ]) ?>
                        <span class="catalog-element-panel-button-icon">
                            <i class="fas fa-heart"></i>
                        </span>
                    <?= Html::endTag('div') ?>
                </div>
            <?php } ?>
            <?php if ($arResult['COMPARE']['USE']) { ?>
                <div class="intec-grid-item-auto">
                    <?= Html::beginTag('div', [
                        'class' => [
                            'catalog-element-panel-button',
                            'catalog-element-panel-button-compare',
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
                        <span class="catalog-element-panel-button-icon">
                            <i class="glyph-icon-compare"></i>
                        </span>
                    <?= Html::endTag('div') ?>
                    <?= Html::beginTag('div', [
                        'class' => [
                            'catalog-element-panel-button',
                            'catalog-element-panel-button-compared',
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
                        <span class="catalog-element-panel-button-icon">
                            <i class="glyph-icon-compare"></i>
                        </span>
                    <?= Html::endTag('div') ?>
                </div>
            <?php } ?>
        </div>
    </div>
<?php };

$vPanelButtons($arResult);

if (!empty($arResult['OFFERS']))
    foreach ($arResult['OFFERS'] as &$arOffer) {
        $vPanelButtons($arOffer, true);

        unset($arOffer);
    }

unset($vPanelButtons);