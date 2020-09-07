<?php if (!defined('B_PROLOG_INCLUDED') && B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;
use intec\core\helpers\ArrayHelper;
use intec\core\helpers\Html;

/**
 * @var array $arResult
 * @var array $arVisual
 */

?>
<?php $vButtons = function (&$arItem) use (&$arResult, &$arVisual) { ?>
    <?php $arParentValues = [
        'IBLOCK_ID' => $arItem['IBLOCK_ID'],
        'DELAY' => $arItem['DELAY']['USE']
    ] ?>
    <?php $fRender = function (&$arItem, $bOffer = false) use (&$arResult, &$arVisual, &$arParentValues) { ?>
        <?php if ($arVisual['OFFERS']['USE'] && !empty($arItem['OFFERS'])) return ?>
        <?= Html::beginTag('div', [
            'class' => 'catalog-section-item-buttons',
            'data-offer' => $bOffer ? $arItem['ID'] : 'false'
        ]) ?>
            <?php if ($arParentValues['DELAY'] && $arItem['CAN_BUY'] && empty($arItem['OFFERS'])) { ?>
                <?php $arPrice = ArrayHelper::getValue($arItem, ['ITEM_PRICES', 0]) ?>
                <?= Html::beginTag('div', [
                    'class' => [
                        'catalog-section-item-button',
                        'catalog-section-item-button-delay',
                        'intec-cl-text-hover'
                    ],
                    'data' => [
                        'basket-id' => $arItem['ID'],
                        'basket-action' => 'delay',
                        'basket-state' => 'none',
                        'basket-price' => !empty($arPrice) ? $arPrice['PRICE_TYPE_ID'] : null
                    ],
                    'title' => Loc::getMessage('C_CATALOG_SECTION_CATALOG_TILE_3_DELAY_ADD_TITLE')
                ]) ?>
                    <i class="intec-ui-icon intec-ui-icon-heart-1"></i>
                <?= Html::endTag('div') ?>
                <?= Html::beginTag('div', [
                    'class' => [
                        'catalog-section-item-button',
                        'catalog-section-item-button-delayed',
                        'intec-cl-text'
                    ],
                    'data' => [
                        'basket-id' => $arItem['ID'],
                        'basket-action' => 'remove',
                        'basket-state' => 'none'
                    ],
                    'title' => Loc::getMessage('C_CATALOG_SECTION_CATALOG_TILE_3_DELAY_ADDED_TITLE')
                ]) ?>
                    <i class="intec-ui-icon intec-ui-icon-heart-1"></i>
                <?= Html::endTag('div') ?>
            <?php } ?>
            <?php if ($arResult['COMPARE']['USE']) { ?>
                <?= Html::beginTag('div', [
                    'class' => [
                        'catalog-section-item-button',
                        'catalog-section-item-button-compare',
                        'intec-cl-text-hover'
                    ],
                    'data' => [
                        'compare-id' => $arItem['ID'],
                        'compare-action' => 'add',
                        'compare-code' => $arResult['COMPARE']['CODE'],
                        'compare-state' => 'none',
                        'compare-iblock' => $arParentValues['IBLOCK_ID']
                    ],
                    'title' => Loc::getMessage('C_CATALOG_SECTION_CATALOG_TILE_3_COMPARE_ADD_TITLE')
                ]) ?>
                    <i class="glyph-icon-compare"></i>
                <?= Html::endTag('div') ?>
                <?= Html::beginTag('div', [
                    'class' => [
                        'catalog-section-item-button',
                        'catalog-section-item-button-compared',
                        'intec-cl-text'
                    ],
                    'data' => [
                        'compare-id' => $arItem['ID'],
                        'compare-action' => 'remove',
                        'compare-code' => $arResult['COMPARE']['CODE'],
                        'compare-state' => 'none',
                        'compare-iblock' => $arParentValues['IBLOCK_ID']
                    ],
                    'title' => Loc::getMessage('C_CATALOG_SECTION_CATALOG_TILE_3_COMPARE_ADDED_TITLE')
                ]) ?>
                    <i class="glyph-icon-compare"></i>
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