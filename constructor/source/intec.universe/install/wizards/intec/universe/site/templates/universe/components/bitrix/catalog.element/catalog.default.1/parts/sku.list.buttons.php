<?php if (!defined("B_PROLOG_INCLUDED") && B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;
use intec\core\helpers\ArrayHelper;
use intec\core\helpers\Html;
use intec\core\helpers\Json;

/**
 * @var array $arResult
 * @var array $arVisual
 */

?>

<?php $vButtons = function (&$arItem, $bOffer = false) use (&$arResult) { ?>
    <div class="catalog-element-purchase-buttons-wrapper">
        <?php if ($arResult['COMPARE']['USE']) { ?>
            <?= Html::beginTag('div', [
                'class' => [
                    'catalog-element-purchase-button',
                    'catalog-element-purchase-button-compare',
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
                'catalog-element-purchase-button',
                'catalog-element-purchase-button-compared',
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
        <?php } ?>
        <?php if ($arResult['DELAY']['USE'] && $arItem['CAN_BUY'] && ($bOffer || empty($arItem['OFFERS']))) { ?>
            <?php $arPrice = ArrayHelper::getValue($arItem, ['ITEM_PRICES', 0]) ?>
            <?= Html::beginTag('div', [
                'class' => [
                    'catalog-element-purchase-button',
                    'catalog-element-purchase-button-delay',
                    'intec-cl-text-hover'
                ],
                'data' => [
                    'basket-id' => $arItem['ID'],
                    'basket-action' => 'delay',
                    'basket-state' => 'none',
                    'basket-price' => !empty($arPrice) ? $arPrice['PRICE_TYPE_ID'] : null
                ]
            ]) ?>
            <i class="fas fa-heart"></i>
            <?= Html::endTag('div') ?>
            <?= Html::beginTag('div', [
            'class' => [
                'catalog-element-purchase-button',
                'catalog-element-purchase-button-delayed',
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
        <?php } ?>
    </div>
<?php };?>