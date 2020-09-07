<?php if (!defined('B_PROLOG_INCLUDED') && B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;
use intec\core\helpers\ArrayHelper;
use intec\core\helpers\Html;

/**
 * @var array $arResult
 * @var string $sTemplateId
 * @var CAllMain $APPLICATION
 * @var CBitrixComponent $compponent
 */

?>
<?php $vPurchase = function (&$arItem) use (&$arResult, &$sTemplateId, &$APPLICATION, &$component) { ?>
    <?php $fRender = function (&$arItem) use (&$arResult, &$sTemplateId, &$APPLICATION, &$component) { ?>
        <?php if ($arItem['ACTION'] === 'buy') { ?>
            <?php if (!empty($arItem['OFFERS'])) return ?>
            <?php if ($arItem['CAN_BUY']) { ?>
                <?php $arPrice = ArrayHelper::getValue($arItem, ['ITEM_PRICES', 0]) ?>
                <div class="catalog-section-item-purchase-buttons">
                    <?= Html::beginTag('div', [
                        'class' => [
                            'intec-ui',
                            'intec-ui-control-basket-button',
                            'catalog-section-item-purchase-button',
                            'catalog-section-item-purchase-button-add',
                            'intec-button',
                            'intec-button-w-icon',
                            'intec-button-transparent',
                            'intec-button-cl-common'
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
                            <i class="intec-button-icon intec-basket glyph-icon-cart"></i>
                            <span class="intec-button-text">
                                <?= Loc::getMessage('C_CATALOG_SECTION_CATALOG_TEXT_1_BUTTON_ADD') ?>
                            </span>
                        </span>
                        <span class="intec-ui-part-effect intec-ui-part-effect-bounce">
                            <span class="intec-ui-part-effect-wrapper">
                                <i></i><i></i><i></i>
                            </span>
                        </span>
                    <?= Html::endTag('div') ?>
                    <?= Html::beginTag('a', [
                        'href' => $arResult['URL']['BASKET'],
                        'class' => [
                            'catalog-section-item-purchase-button',
                            'catalog-section-item-purchase-button-added',
                            'intec-button',
                            'intec-button-w-icon',
                            'intec-button-cl-common',
                            'hover'
                        ],
                        'data' => [
                            'basket-id' => $arItem['ID'],
                            'basket-state' => 'none',
                        ]
                    ]) ?>
                        <i class="intec-button-icon intec-basket glyph-icon-cart"></i>
                        <span class="intec-button-text">
                            <?=Loc::getMessage('C_CATALOG_SECTION_CATALOG_TEXT_1_BUTTON_ADDED')?>
                        </span>
                    <?= Html::endTag('a') ?>
                </div>
            <?php } else if ($arItem['CATALOG_SUBSCRIBE'] == 'Y') { ?>
                <div class="catalog-section-item-purchase-buttons">
                    <?php $APPLICATION->IncludeComponent(
                        'bitrix:catalog.product.subscribe',
                        '.default', [
                            'BUTTON_CLASS' => Html::cssClassFromArray([
                                'catalog-section-item-purchase-button',
                                'intec-ui',
                                'intec-button' => [
                                    '',
                                    'transparent',
                                    'cl-common'
                                ]
                            ]),
                            'BUTTON_ID' => $sTemplateId.'_subscribe_'.$arItem['ID'],
                            'PRODUCT_ID' => $arItem['ID']
                        ],
                        $component
                    ) ?>
                </div>
            <?php } ?>
        <?php } else if ($arItem['ACTION'] === 'detail') { ?>
            <div class="catalog-section-item-purchase-buttons">
                <?= Html::beginTag($arResult['QUICK_VIEW']['DETAIL'] ? 'div' : 'a', [
                    'class' => [
                        'catalog-section-item-purchase-button',
                        'catalog-section-item-purchase-button-detail',
                        'intec-button',
                        'intec-button-transparent',
                        'intec-button-cl-common'
                    ],
                    'href' => !$arResult['QUICK_VIEW']['DETAIL'] ? $arItem['DETAIL_PAGE_URL'] : null,
                    'data-role' => $arResult['QUICK_VIEW']['DETAIL'] ? 'quick.view' : null
                ]) ?>
                    <span>
                        <?= Loc::getMessage('C_CATALOG_SECTION_CATALOG_TEXT_1_BUTTON_DETAIL') ?>
                    </span>
                <?= Html::endTag($arResult['QUICK_VIEW']['DETAIL'] ? 'div' : 'a') ?>
            </div>
        <?php } else if ($arItem['ACTION'] === 'order') { ?>
            <?php if ($arResult['FORM']['SHOW']) { ?>
                <div class="catalog-section-item-purchase-buttons">
                    <?= Html::beginTag('div', [
                        'class' => [
                            'catalog-section-item-purchase-button',
                            'catalog-section-item-purchase-button-order',
                            'intec-button',
                            'intec-button-transparent',
                            'intec-button-cl-common'
                        ],
                        'data-role' => 'item.order'
                    ]) ?>
                        <span>
                            <?= Loc::getMessage('C_CATALOG_SECTION_CATALOG_TEXT_1_BUTTON_ORDER') ?>
                        </span>
                    <?= Html::endTag('div') ?>
                </div>
            <?php } ?>
        <?php } ?>
    <?php } ?>
    <?php $fRender($arItem) ?>
<?php } ?>