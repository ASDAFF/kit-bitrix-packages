<?php if (!defined('B_PROLOG_INCLUDED') && B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;
use intec\core\helpers\ArrayHelper;
use intec\core\helpers\Html;

/**
 * @var array $arResult
 * @var array $arVisual
 * @var CAllMain $APPLICATION
 * @var CBitrixComponent $component
 */

?>
<?php $vPurchase = function (&$arItem) use (&$arResult, &$arVisual, &$APPLICATION, &$component, &$sTemplateId) { ?>
    <?php $arParentValues = [
        'URL' => $arItem['DETAIL_PAGE_URL']
    ] ?>
    <?php $fRender = function (&$arItem, $bOffer = false) use (&$arResult, &$APPLICATION, &$component, &$sTemplateId, &$arParentValues) { ?>
        <?php if ($bOffer || $arItem['ACTION'] === 'buy') { ?>
            <?php if (!empty($arItem['OFFERS']) && !$bOffer) return ?>
            <?php if ($arItem['CAN_BUY']) { ?>
                <?php $arPrice = ArrayHelper::getValue($arItem, ['ITEM_PRICES', 0]) ?>
                <?= Html::beginTag('div', [
                    'class' => 'catalog-section-item-purchase-buttons',
                    'data-offer' => $bOffer ? $arItem['ID'] : 'false'
                ]) ?>
                    <?= Html::beginTag('div', [
                        'class' => [
                            'intec-ui',
                            'intec-ui-control-basket-button',
                            'catalog-section-item-purchase-button',
                            'catalog-section-item-purchase-button-add',
                            'intec-cl-text',
                            'intec-cl-text-light-hover'
                        ],
                        'data' => [
                            'basket-id' => $arItem['ID'],
                            'basket-action' => 'add',
                            'basket-state' => 'none',
                            'basket-quantity' => $arItem['CATALOG_MEASURE_RATIO'],
                            'basket-price' => !empty($arPrice) ? $arPrice['PRICE_TYPE_ID'] : null
                        ]
                    ]) ?>
                        <span class="intec-ui-part-content">
                            <i class="glyph-icon-cart"></i>
                        </span>
                        <span class="intec-ui-part-effect intec-ui-part-effect-folding">
                            <span class="intec-ui-part-effect-wrapper">
                                <i></i><i></i><i></i><i></i>
                            </span>
                        </span>
                    <?= Html::endTag('div') ?>
                    <?= Html::beginTag('a', [
                        'href' => $arResult['URL']['BASKET'],
                        'class' => [
                            'catalog-section-item-purchase-button',
                            'catalog-section-item-purchase-button-added',
                            'intec-cl-background-light'
                        ],
                        'data' => [
                            'basket-id' => $arItem['ID'],
                            'basket-state' => 'none',
                        ]
                    ]) ?>
                        <i class="intec-basket glyph-icon-cart"></i>
                    <?= Html::endTag('a') ?>
                <?= Html::endTag('div') ?>
            <?php } else { ?>
                <?php if ($arItem['CATALOG_SUBSCRIBE'] == 'Y') { ?>
                    <?= Html::beginTag('div', [
                        'class' => 'catalog-section-item-purchase-buttons',
                        'data-offer' => $bOffer ? $arItem['ID'] : 'false'
                    ]) ?>
                        <?php $APPLICATION->IncludeComponent(
                            'bitrix:catalog.product.subscribe',
                            '.default', [
                                'BUTTON_CLASS' => 'catalog-section-item-purchase-button intec-ui intec-button intec-button-transparent intec-button-cl-common',
                                'BUTTON_ID' => $sTemplateId.'_subscribe_'.$arItem['ID'],
                                'PRODUCT_ID' => $arItem['ID']
                            ],
                            $component
                        ) ?>
                    <?= Html::endTag('div') ?>
                <?php } ?>
            <?php } ?>
        <?php } else if ($arItem['ACTION'] === 'detail') { ?>
            <div class="catalog-section-item-purchase-buttons-detail">
                <?= Html::beginTag($arResult['QUICK_VIEW']['DETAIL'] ? 'div' : 'a', [
                    'class' => [
                        'catalog-section-item-purchase-button',
                        'catalog-section-item-purchase-button-detail',
                        'intec-cl-text',
                        'intec-cl-text-light-hover'
                    ],
                    'href' => !$arResult['QUICK_VIEW']['DETAIL'] ? $arParentValues['URL'] : null,
                    'data-role' => $arResult['QUICK_VIEW']['DETAIL'] ? 'quick.view' : null
                ]) ?>
                    <span>
                        <?= Loc::getMessage('C_CATALOG_SECTION_CATALOG_TILE_1_BUTTON_DETAIL') ?>
                    </span>
                <?= Html::endTag($arResult['QUICK_VIEW']['DETAIL'] ? 'div' : 'a') ?>
            </div>
        <?php } else if ($arItem['ACTION'] === 'order') { ?>
            <?php if ($arResult['FORM']['SHOW']) { ?>
                <div class="catalog-section-item-purchase-buttons">
                    <?= Html::beginTag('div', [
                        'class' => [
                            'catalog-section-item-purchase-button',
                            'catalog-section-item-purchase-button-add',
                            'intec-cl-text',
                            'intec-cl-text-light-hover'
                        ],
                        'data-role' => 'item.order'
                    ]) ?>
                        <i class="intec-basket glyph-icon-cart"></i>
                    <?= Html::endTag('div') ?>
                </div>
            <?php } ?>
        <?php } ?>
    <?php } ?>
    <?php $fRender($arItem, false) ?>
    <?php if ($arItem['ACTION'] === 'buy' && $arVisual['OFFERS']['USE'] && !empty($arItem['OFFERS'])) {
        foreach ($arItem['OFFERS'] as &$arOffer)
            $fRender($arOffer, true);

        if ($arVisual['COLUMNS']['MOBILE'] == 2) {
            $arItem['ACTION'] = 'detail';
            $fRender($arItem, false);
        }
    }
    ?>
<?php } ?>