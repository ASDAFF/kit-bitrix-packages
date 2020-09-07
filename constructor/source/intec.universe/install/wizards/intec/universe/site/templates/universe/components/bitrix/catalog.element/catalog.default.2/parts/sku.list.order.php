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

<?php $vOrder = function (&$arItem, $bOffer = false) use (&$arResult, &$APPLICATION, &$component, &$sTemplateId) { ?>
    <?php if ($arResult['ACTION'] === 'buy') { ?>
        <?php if (!$arItem['CAN_BUY']) { ?>
            <?php if ($arItem['CATALOG_SUBSCRIBE'] == 'Y') { ?>
                <?php if (!empty($arItem['OFFERS']) && $bOffer == false) {
                    return;
                } ?>
                <div class="catalog-element-purchase-order-subscribe" data-offer="<?= $bOffer ? $arItem['ID'] : 'false' ?>">
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
            <?php } else {
                return;
            }
        } else { ?>
            <?php $arPrice = ArrayHelper::getValue($arItem, ['ITEM_PRICES', 0]) ?>
            <div class="catalog-element-purchase-order-buttons" data-offer="<?= $bOffer ? $arItem['ID'] : 'false' ?>">
                <?= Html::beginTag('div', [
                    'class' => [
                        'intec-ui',
                        'intec-ui-control-basket-button',
                        'catalog-element-purchase-order-button',
                        'catalog-element-purchase-order-button-add',
                        'intec-button',
                        'intec-button-cl-common',
                        'intec-button-block',
                        'intec-button-md ',
                        'intec-button-s-7',
                        'intec-button-fs-16',
                        'intec-button-w-icon'
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
                                    <i class="intec-button-icon glyph-icon-cart"></i>
                                    <span class="intec-button-text">
                                        <?= Loc::getMessage('C_CATALOG_ELEMENT_CATALOG_DEFAULT_2_PURCHASE_ORDER') ?>
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
                        'catalog-element-purchase-order-button',
                        'catalog-element-purchase-order-button-added',
                        'intec-button',
                        'intec-button-cl-common',
                        'intec-button-block',
                        'intec-button-md ',
                        'intec-button-s-7',
                        'intec-button-fs-16',
                        'intec-button-w-icon',
                        'hover'
                    ],
                    'data' => [
                        'basket-id' => $arItem['ID'],
                        'basket-state' => 'none'
                    ]
                ]) ?>
                <i class="intec-button-icon glyph-icon-cart"></i>
                <span class="intec-button-text">
                                    <?= Loc::getMessage('C_CATALOG_ELEMENT_CATALOG_DEFAULT_2_PURCHASE_ORDERED') ?>
                                </span>
                <?= Html::endTag('a') ?>
            </div>
        <?php } ?>
    <?php } else { ?>
        <div class="catalog-element-purchase-order-buttons">
            <?= Html::beginTag('div', [
                'class' => [
                    'catalog-element-purchase-order-button',
                    'intec-button',
                    'intec-button-cl-common',
                    'intec-button-block',
                    'intec-button-md ',
                    'intec-button-s-7',
                    'intec-button-fs-16',
                    'intec-button-w-icon'
                ],
                'data' => [
                    'role' => 'order'
                ]
            ]) ?>
            <span class="intec-ui-part-content">
                                <i class="intec-button-icon glyph-icon-cart"></i>
                                <span class="intec-button-text">
                                    <?= Loc::getMessage('C_CATALOG_ELEMENT_CATALOG_DEFAULT_2_PURCHASE_FORM_ORDER') ?>
                                </span>
                            </span>
            <?= Html::endTag('div') ?>
        </div>
    <?php } ?>
<?php };


