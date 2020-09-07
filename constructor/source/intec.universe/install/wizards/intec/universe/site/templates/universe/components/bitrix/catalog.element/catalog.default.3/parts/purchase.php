<?php if (!defined('B_PROLOG_INCLUDED') && B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;
use intec\core\helpers\ArrayHelper;
use intec\core\helpers\Html;
use intec\core\helpers\Json;

/**
 * @var array $arResult
 */

?>
<?php $vOrder = function (&$arItem, $bOffer = false) use (&$arResult, &$APPLICATION, &$component, &$sTemplateId) { ?>
    <?php if ($bOffer || $arResult['ACTION'] === 'buy') { ?>
        <?php if ($arItem['CAN_BUY']) { ?>
            <?php $arPrice = ArrayHelper::getValue($arItem, ['ITEM_PRICES', 0]) ?>
            <div class="catalog-element-purchase-buttons" data-offer="<?= $bOffer ? $arItem['ID'] : 'false' ?>">
                <?= Html::beginTag('div', [
                    'class' => [
                        'intec-ui',
                        'intec-ui-control-basket-button',
                        'catalog-element-purchase-button',
                        'catalog-element-purchase-button-add',
                        'intec-cl-background',
                        'intec-cl-background-light-hover'
                    ],
                    'data' => [
                        'basket-id' => $arItem['ID'],
                        'basket-action' => 'add',
                        'basket-state' => 'none',
                        'basket-quantity' => $arItem['CATALOG_MEASURE_RATIO'],
                        'basket-price' => !empty($arPrice) ? $arPrice['PRICE_TYPE_ID'] : null,
                        'basket-data' => Json::htmlEncode([
                            'additional' => true
                        ])
                    ]
                ]) ?>
                    <span class="catalog-element-purchase-button-content intec-ui-part-content">
                        <i class="glyph-icon-cart"></i>
                        <span>
                            <?= Loc::getMessage('C_CATALOG_ELEMENT_CATALOG_DEFAULT_3_PURCHASE_ADD') ?>
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
                        'catalog-element-purchase-button',
                        'catalog-element-purchase-button-added',
                        'intec-cl-background-light'
                    ],
                    'data' => [
                        'basket-id' => $arItem['ID'],
                        'basket-state' => 'none'
                    ]
                ]) ?>
                    <span class="catalog-element-purchase-button-content">
                        <i class="glyph-icon-cart"></i>
                        <span>
                            <?= Loc::getMessage('C_CATALOG_ELEMENT_CATALOG_DEFAULT_3_PURCHASE_ADDED') ?>
                        </span>
                    </span>
                <?= Html::endTag('a') ?>
            </div>
        <?php } else { ?>
            <?php if ($arItem['CATALOG_SUBSCRIBE'] == 'Y') { ?>
                <?php if (!empty($arItem['OFFERS']) && $bOffer == false) { ?>
                    <?php return; ?>
                <?php } ?>
                <div class="catalog-element-purchase-subscribe" data-offer="<?= $bOffer ? $arItem['ID'] : 'false' ?>">
                    <?php $APPLICATION->IncludeComponent(
                        "bitrix:catalog.product.subscribe",
                        ".default",
                        [
                            "BUTTON_CLASS" => "intec-button intec-button-cl-common intec-button-s-7 intec-button-fs-16",
                            "BUTTON_ID" => $sTemplateId . "_subscribe_" . $arItem['ID'],
                            "PRODUCT_ID" => $arItem['ID']
                        ],
                        $component
                    ); ?>
                </div>
            <?php } else { ?>
                <?= Html::beginTag('div', [
                    'class' => 'catalog-element-purchase-buttons',
                    'data' => [
                        'offer' => $bOffer ? $arItem['ID'] : 'false'
                    ]
                ]) ?>
                    <?= Html::beginTag('div', [
                        'class' => [
                            'catalog-element-purchase-button',
                            'catalog-element-purchase-button-unavailable',
                            'intec-cl-background'
                        ],
                        'title' => Loc::getMessage('C_CATALOG_ELEMENT_CATALOG_DEFAULT_3_PURCHASE_UNAVAILABLE')
                    ]) ?>
                    <span class="catalog-element-purchase-button-content">
                                <i class="far fa-times-circle"></i>
                                <span>
                                    <?= Loc::getMessage('C_CATALOG_ELEMENT_CATALOG_DEFAULT_3_PURCHASE_UNAVAILABLE') ?>
                                </span>
                            </span>
                    <?= Html::endTag('div') ?>
                <?= Html::endTag('div') ?>
            <?php } ?>
        <?php } ?>
    <?php } else if ($arResult['ACTION'] === 'order') { ?>
        <div class="catalog-section-item-purchase-buttons">
            <?= Html::beginTag('div', [
                'class' => [
                    'catalog-element-purchase-button',
                    'intec-cl-background',
                    'intec-cl-background-light-hover'
                ],
                'data' => [
                    'role' => 'order'
                ]
            ]) ?>
                <span class="catalog-element-purchase-button-content">
                    <span>
                        <?= Loc::getMessage('C_CATALOG_ELEMENT_CATALOG_DEFAULT_3_PURCHASE_ORDER') ?>
                    </span>
                </span>
            <?= Html::endTag('div') ?>
        </div>
    <?php } ?>
<?php };

$vOrder($arResult);

if (!empty($arResult['OFFERS']) && $arResult['ACTION'] === 'buy')
    foreach ($arResult['OFFERS'] as &$arOffer) {
        $vOrder($arOffer, true);

        unset($arOffer);
    }

unset($vOrder);