<?php if (defined("B_PROLOG_INCLUDED") && B_PROLOG_INCLUDED === true) ?>
<?php

use Bitrix\Main\Localization\Loc;
use intec\core\helpers\ArrayHelper;
use intec\core\helpers\Html;

/**
 * @var array $arParams
 * @var array $arResult
 * @var array $arVisual
 * @var CBitrixComponent $component
 * @var CBitrixComponentTemplate $this
 */

$vButtons = function (&$arItem) use (&$arResult, &$arVisual) {
    $arParentValues = [
        'IBLOCK_ID' => $arItem['IBLOCK_ID'],
        'DELAY' => $arItem['DELAY']['USE']
    ];
    $fRender = function (&$arItem, $bOffer = false) use (&$arResult, &$arParentValues) { ?>
        <div class="catalog-section-item-action-buttons" data-offer="<?= $bOffer ? $arItem['ID'] : 'false' ?>">
            <?php if ($arResult['COMPARE']['USE']) { ?>
                <?= Html::beginTag('div', [
                    'class' => [
                        'catalog-section-item-action-button',
                        'catalog-section-item-action-button-compare',
                        'intec-cl-text-hover'
                    ],
                    'data' => [
                        'compare-id' => $arItem['ID'],
                        'compare-action' => 'add',
                        'compare-code' => $arResult['COMPARE']['CODE'],
                        'compare-state' => 'none',
                        'compare-iblock' => $arParentValues['IBLOCK_ID']
                    ],
                    'title' => Loc::getMessage('C_CATALOG_SECTION_CATALOG_TEXT_2_ICON_COMPARE_ADD')
                ]) ?>
                    <i class="glyph-icon-compare"></i>
                <?= Html::endTag('div') ?>
                <?= Html::beginTag('div', [
                    'class' => [
                        'catalog-section-item-action-button',
                        'catalog-section-item-action-button-compared',
                        'intec-cl-text'
                    ],
                    'data' => [
                        'compare-id' => $arItem['ID'],
                        'compare-action' => 'remove',
                        'compare-code' => $arResult['COMPARE']['CODE'],
                        'compare-state' => 'none',
                        'compare-iblock' => $arParentValues['IBLOCK_ID']
                    ],
                'title' => Loc::getMessage('C_CATALOG_SECTION_CATALOG_TEXT_2_ICON_COMPARE_REMOVE')
                ]) ?>
                    <i class="glyph-icon-compare"></i>
                <?= Html::endTag('div') ?>
            <?php } else if ($arResult['COMPARE']['SHOW_INACTIVE']) { ?>
                <div class="catalog-section-item-action-button catalog-section-item-action-button-compare inactive">
                    <i class="glyph-icon-compare"></i>
                </div>
            <?php } ?>
            <?php if ($arParentValues['DELAY'] && $arItem['CAN_BUY']) { ?>
                <?php $arPrice = ArrayHelper::getValue($arItem, ['ITEM_PRICES', 0]) ?>
                <?= Html::beginTag('div', [
                    'class' => [
                        'catalog-section-item-action-button',
                        'catalog-section-item-action-button-delay',
                        'intec-cl-text-hover'
                    ],
                    'data' => [
                        'basket-id' => $arItem['ID'],
                        'basket-action' => 'delay',
                        'basket-state' => 'none',
                        'basket-price' => !empty($arPrice) ? $arPrice['PRICE_TYPE_ID'] : null
                    ],
                    'title' => Loc::getMessage('C_CATALOG_SECTION_CATALOG_TEXT_2_ICON_DELAY_ADD')
                ]) ?>
                    <i class="intec-ui-icon intec-ui-icon-heart-1"></i>
                <?= Html::endTag('div') ?>
                <?= Html::beginTag('div', [
                    'class' => [
                        'catalog-section-item-action-button',
                        'catalog-section-item-action-button-delayed',
                        'intec-cl-text'
                    ],
                    'data' => [
                        'basket-id' => $arItem['ID'],
                        'basket-action' => 'remove',
                        'basket-state' => 'none'
                    ],
                'title' => Loc::getMessage('C_CATALOG_SECTION_CATALOG_TEXT_2_ICON_DELAY_REMOVE')
                ]) ?>
                    <i class="intec-ui-icon intec-ui-icon-heart-1"></i>
                <?= Html::endTag('div') ?>
            <?php } else if ($arResult['DELAY']['SHOW_INACTIVE']) { ?>
                <div class="catalog-section-item-action-button catalog-section-item-action-button-delay inactive">
                    <i class="intec-ui-icon intec-ui-icon-heart-1"></i>
                </div>
            <?php } ?>
        </div>
    <?php };

    $fRender($arItem);

    if ($arVisual['OFFERS']['USE'] && !empty($arItem['OFFERS']))
        foreach ($arItem['OFFERS'] as &$arOffer)
            $fRender($arOffer, true);
};