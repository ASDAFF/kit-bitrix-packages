<?php if (!defined('B_PROLOG_INCLUDED') && B_PROLOG_INCLUDED !== true) die();

use intec\core\helpers\ArrayHelper;
use intec\core\helpers\Html;

/**
 * @var array $arResult
 */

?>
<?php $vButtons = function (&$arItem) use (&$arResult) { ?>
    <?php $arParentValues = [
        'IBLOCK_ID' => $arItem['IBLOCK_ID'],
        'DELAY' => $arItem['DELAY']['USE']
    ] ?>
    <?php $fRender = function (&$arItem) use (&$arResult, &$arParentValues) { ?>
        <?php if (!empty($arItem['OFFERS'])) return ?>
        <div class="catalog-section-item-price-buttons">
            <?php if ($arResult['COMPARE']['USE']) { ?>
                <?= Html::beginTag('div', [
                    'class' => [
                        'catalog-section-item-price-button',
                        'catalog-section-item-price-button-compare',
                        'intec-cl-text-hover'
                    ],
                    'data' => [
                        'compare-id' => $arItem['ID'],
                        'compare-action' => 'add',
                        'compare-code' => $arResult['COMPARE']['CODE'],
                        'compare-state' => 'none',
                        'compare-iblock' => $arParentValues['IBLOCK_ID']
                    ]
                ]) ?>
                    <i class="glyph-icon-compare"></i>
                <?= Html::endTag('div') ?>
                <?= Html::beginTag('div', [
                    'class' => [
                        'catalog-section-item-price-button',
                        'catalog-section-item-price-button-compared',
                        'intec-cl-text'
                    ],
                    'data' => [
                        'compare-id' => $arItem['ID'],
                        'compare-action' => 'remove',
                        'compare-code' => $arResult['COMPARE']['CODE'],
                        'compare-state' => 'none',
                        'compare-iblock' => $arParentValues['IBLOCK_ID']
                    ]
                ]) ?>
                    <i class="glyph-icon-compare"></i>
                <?= Html::endTag('div') ?>
            <?php } ?>
            <?php if ($arParentValues['DELAY'] && $arItem['CAN_BUY']) { ?>
                <?php $arPrice = ArrayHelper::getValue($arItem, ['ITEM_PRICES', 0]) ?>
                <?= Html::beginTag('div', [
                    'class' => [
                        'catalog-section-item-price-button',
                        'catalog-section-item-price-button-delay',
                        'intec-cl-text-hover'
                    ],
                    'data' => [
                        'basket-id' => $arItem['ID'],
                        'basket-action' => 'delay',
                        'basket-state' => 'none',
                        'basket-price' => !empty($arPrice) ? $arPrice['PRICE_TYPE_ID'] : null
                    ]
                ]) ?>
                    <i class="intec-ui-icon intec-ui-icon-heart-1"></i>
                <?= Html::endTag('div') ?>
                <?= Html::beginTag('div', [
                    'class' => [
                        'catalog-section-item-price-button',
                        'catalog-section-item-price-button-delayed',
                        'intec-cl-text'
                    ],
                    'data' => [
                        'basket-id' => $arItem['ID'],
                        'basket-action' => 'remove',
                        'basket-state' => 'none'
                    ]
                ]) ?>
                    <i class="intec-ui-icon intec-ui-icon-heart-1"></i>
                <?= Html::endTag('div') ?>
            <?php } ?>
        </div>
    <?php } ?>
    <?php $fRender($arItem) ?>
<?php } ?>