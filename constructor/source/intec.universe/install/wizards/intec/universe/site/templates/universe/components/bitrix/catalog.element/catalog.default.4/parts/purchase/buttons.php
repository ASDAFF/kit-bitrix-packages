<?php if (!defined('B_PROLOG_INCLUDED') && B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;
use intec\core\helpers\ArrayHelper;
use intec\core\helpers\FileHelper;
use intec\core\helpers\Html;

/**
 * @var array $arResult
 */

$sIconDelay = FileHelper::getFileData(__DIR__.'/../../svg/delay.svg');
$sIconCompare = FileHelper::getFileData(__DIR__.'/../../svg/compare.svg');

?>
<?php $vButtons = function (&$arItem, $bOffer = false) use (&$arResult, &$sIconDelay, &$sIconCompare) { ?>
    <?php if (!empty($arItem['OFFERS']) && !$bOffer) return ?>
    <?= Html::beginTag('div', [
        'class' => 'catalog-element-buttons',
        'data-offer' => $bOffer ? $arItem['ID'] : 'false'
    ]) ?>
        <div class="intec-grid intec-grid-nowrap intec-grid-i-4">
            <?php if ($arResult['DELAY']['USE']) { ?>
                <?php $arPrice = ArrayHelper::getFirstValue($arItem['ITEM_PRICES']) ?>
                <div class="intec-grid-item-auto" data-disable>
                    <?= Html::beginTag('div', [
                        'class' => [
                            'catalog-element-buttons-button',
                            'catalog-element-buttons-delay',
                            'intec-cl-background-hover',
                            'intec-cl-border-hover'
                        ],
                        'title' => Loc::getMessage('C_CATALOG_ELEMENT_DEFAULT_3_TEMPLATE_BUTTON_DELAY_ADD'),
                        'data' => [
                            'basket-id' => $arItem['ID'],
                            'basket-action' => 'delay',
                            'basket-state' => 'none',
                            'basket-price' => !empty($arPrice) ? $arPrice['PRICE_TYPE_ID'] : null
                        ]
                    ]) ?>
                        <?= $sIconDelay ?>
                        <div class="intec-aligner"></div>
                    <?= Html::endTag('div') ?>
                    <?= Html::beginTag('div', [
                        'class' => [
                            'catalog-element-buttons-button',
                            'catalog-element-buttons-delayed',
                            'intec-cl-background',
                            'intec-cl-border',
                            'intec-cl-background-light-hover',
                            'intec-cl-border-light-hover'
                        ],
                        'title' => Loc::getMessage('C_CATALOG_ELEMENT_DEFAULT_3_TEMPLATE_BUTTON_DELAY_ADDED'),
                        'data' => [
                            'basket-id' => $arItem['ID'],
                            'basket-action' => 'remove',
                            'basket-state' => 'none'
                        ]
                    ]) ?>
                        <?= $sIconDelay ?>
                        <div class="intec-aligner"></div>
                    <?= Html::endTag('div') ?>
                </div>
            <?php } ?>
            <?php if ($arResult['COMPARE']['USE']) { ?>
                <div class="intec-grid-item-auto">
                    <?= Html::beginTag('div', [
                        'class' => [
                            'catalog-element-buttons-button',
                            'catalog-element-buttons-compare',
                            'intec-cl-background-hover',
                            'intec-cl-border-hover'
                        ],
                        'title' => Loc::getMessage('C_CATALOG_ELEMENT_DEFAULT_3_TEMPLATE_BUTTON_COMPARE_ADD'),
                        'data' => [
                            'compare-id' => $arItem['ID'],
                            'compare-action' => 'add',
                            'compare-code' => $arResult['COMPARE']['CODE'],
                            'compare-state' => 'none',
                            'compare-iblock' => $arResult['IBLOCK_ID']
                        ]
                    ]) ?>
                        <?= $sIconCompare ?>
                        <div class="intec-aligner"></div>
                    <?= Html::endTag('div') ?>
                    <?= Html::beginTag('div', [
                        'class' => [
                            'catalog-element-buttons-button',
                            'catalog-element-buttons-compared',
                            'intec-cl-background',
                            'intec-cl-border',
                            'intec-cl-background-light-hover',
                            'intec-cl-border-light-hover'
                        ],
                        'title' => Loc::getMessage('C_CATALOG_ELEMENT_DEFAULT_3_TEMPLATE_BUTTON_COMPARE_ADDED'),
                        'data' => [
                            'compare-id' => $arItem['ID'],
                            'compare-action' => 'remove',
                            'compare-code' => $arResult['COMPARE']['CODE'],
                            'compare-state' => 'none',
                            'compare-iblock' => $arResult['IBLOCK_ID']
                        ]
                    ]) ?>
                        <?= $sIconCompare ?>
                        <div class="intec-aligner"></div>
                    <?= Html::endTag('div') ?>
                </div>
            <?php } ?>
        </div>
    <?= Html::endTag('div') ?>
<?php } ?>
<?= Html::beginTag('div', [
    'class' => 'intec-grid-item-auto'
]) ?>
    <?php $vButtons($arResult) ?>
    <?php if (!empty($arResult['OFFERS'])) {
        foreach ($arResult['OFFERS'] as &$arOffer)
            $vButtons($arOffer, true);

        unset($arOffer);
    } ?>
<?= Html::endTag('div') ?>
<?php unset($vButtons, $sIconDelay, $sIconCompare) ?>