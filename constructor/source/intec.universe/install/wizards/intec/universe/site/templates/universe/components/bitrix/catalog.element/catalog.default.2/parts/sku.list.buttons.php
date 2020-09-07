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
    <div class="catalog-element-buttons-wrapper" data-offer="<?= $bOffer ? $arItem['ID'] : 'false' ?>">
        <?php if ($arResult['DELAY']['USE'] && $arItem['CAN_BUY'] && ($bOffer || empty($arItem['OFFERS']))) { ?>
            <?php $arPrice = ArrayHelper::getValue($arItem, ['ITEM_PRICES', 0]) ?>
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
                    'basket-price' => !empty($arPrice) ? $arPrice['PRICE_TYPE_ID'] : null
                ]
            ]) ?>
            <div class="catalog-element-button-wrapper">
                <div class="catalog-element-button-icon">
                    <i class="fas fa-heart"></i>
                </div>
            </div>
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
            <div class="catalog-element-button-wrapper">
                <div class="catalog-element-button-icon">
                    <i class="fas fa-heart"></i>
                </div>
            </div>
            <?= Html::endTag('div') ?>
        <?php } ?>
        <?php if ($arResult['COMPARE']['USE']) { ?>
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
            <div class="catalog-element-button-wrapper">
                <div class="catalog-element-button-icon">
                    <i class="glyph-icon-compare"></i>
                </div>
            </div>
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
            <div class="catalog-element-button-wrapper">
                <div class="catalog-element-button-icon">
                    <i class="glyph-icon-compare"></i>
                </div>
            </div>
            <?= Html::endTag('div') ?>
        <?php } ?>
    </div>
<?php };