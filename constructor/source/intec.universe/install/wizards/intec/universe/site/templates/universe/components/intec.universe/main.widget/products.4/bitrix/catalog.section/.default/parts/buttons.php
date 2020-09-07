<?php if (!defined('B_PROLOG_INCLUDED') && B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;
use intec\core\helpers\ArrayHelper;
use intec\core\helpers\Html;

/**
 * @var array $arVisual
 * @var array $arResult
 */

?>
<?php $vButtons = function (&$arItem) use (&$arResult, &$arVisual) { ?>
    <?php $arParentValues = [
        'IBLOCK_ID' => $arItem['IBLOCK_ID'],
        'DELAY' => $arItem['DELAY']['USE']
    ] ?>
    <?php $fRender = function (&$arItem, $bOffer = false) use (&$arResult, &$arVisual, &$arParentValues) { ?>
        <?php if (!empty($arItem['OFFERS']) && !$bOffer) return ?>
        <?= Html::beginTag('div', [
            'class' => 'widget-item-action-buttons',
            'data-offer' => $bOffer ? $arItem['ID'] : 'false'
        ]) ?>
            <?php if ($arParentValues['DELAY'] && $arItem['CAN_BUY']) { ?>
                <?php $arPrice = ArrayHelper::getValue($arItem, ['ITEM_PRICES', 0]) ?>
                <?= Html::beginTag('div', [
                    'class' => [
                        'widget-item-action-button',
                        'widget-item-action-button-delay',
                        'intec-cl-text-hover'
                    ],
                    'title' => Loc::getMessage('C_WIDGET_PRODUCTS_4_DELAY_ADD_TITLE'),
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
                        'widget-item-action-button',
                        'widget-item-action-button-delayed',
                        'intec-cl-text'
                    ],
                    'data' => [
                        'basket-id' => $arItem['ID'],
                        'basket-action' => 'remove',
                        'basket-state' => 'none'
                    ],
                    'title' => Loc::getMessage('C_WIDGET_PRODUCTS_4_DELAY_ADDED_TITLE')
                ]) ?>
                    <i class="intec-ui-icon intec-ui-icon-heart-1"></i>
                <?= Html::endTag('div') ?>
            <?php } ?>
            <?php if ($arResult['COMPARE']['USE']) { ?>
                <?= Html::beginTag('div', [
                    'class' => [
                        'widget-item-action-button',
                        'widget-item-action-button-compare',
                        'intec-cl-text-hover'
                    ],
                    'data' => [
                        'compare-id' => $arItem['ID'],
                        'compare-action' => 'add',
                        'compare-code' => $arResult['COMPARE']['CODE'],
                        'compare-state' => 'none',
                        'compare-iblock' => $arParentValues['IBLOCK_ID']
                    ],
                    'title' => Loc::getMessage('C_WIDGET_PRODUCTS_4_COMPARE_ADD_TITLE')
                ]) ?>
                    <i class="glyph-icon-compare"></i>
                <?= Html::endTag('div') ?>
                <?= Html::beginTag('div', [
                    'class' => [
                        'widget-item-action-button',
                        'widget-item-action-button-compared',
                        'intec-cl-text'
                    ],
                    'data' => [
                        'compare-id' => $arItem['ID'],
                        'compare-action' => 'remove',
                        'compare-code' => $arResult['COMPARE']['CODE'],
                        'compare-state' => 'none',
                        'compare-iblock' => $arParentValues['IBLOCK_ID']
                    ],
                    'title' => Loc::getMessage('C_WIDGET_PRODUCTS_4_COMPARE_ADDED_TITLE')
                ]) ?>
                    <i class="glyph-icon-compare"></i>
                <?= Html::endTag('div') ?>
            <?php } ?>
            <?php if ($arItem['ORDER_FAST']['USE'] && $arItem['CAN_BUY']) { ?>
                <?= Html::beginTag('div', [
                    'class' => [
                        'widget-item-action-button',
                        'widget-item-action-button-order-fast',
                        'intec-cl-text-hover'
                    ],
                    'data' => [
                        'role' => 'orderFast'
                    ],
                    'title' => Loc::getMessage('C_WIDGET_PRODUCTS_4_ORDER_FAST_TITLE')
                ]) ?>
                    <i class="intec-button-icon glyph-icon-one_click"></i>
                <?= Html::endTag('div') ?>
            <?php } ?>
            <?php if ($arResult['QUICK_VIEW']['USE']) { ?>
                <?= Html::beginTag('div', [
                    'class' => [
                        'widget-item-action-button',
                        'widget-item-action-button-quick-view',
                        'intec-cl-text-hover'
                    ],
                    'data' => [
                        'role' => 'quick.view'
                    ],
                    'title' => Loc::getMessage('C_WIDGET_PRODUCTS_4_QUICK_VIEW_TITLE')
                ]) ?>
                    <i class="intec-ui-icon intec-ui-icon-eye-1"></i>
                <?= Html::endTag('div') ?>
            <?php } ?>
        <?= Html::endTag('div') ?>
    <?php } ?>
    <?php $fRender($arItem) ?>
    <?php if ($arVisual['OFFERS']['USE'] && !empty($arItem['OFFERS']))
        foreach ($arItem['OFFERS'] as &$arOffer)
            $fRender($arOffer, true)
    ?>
<?php } ?>