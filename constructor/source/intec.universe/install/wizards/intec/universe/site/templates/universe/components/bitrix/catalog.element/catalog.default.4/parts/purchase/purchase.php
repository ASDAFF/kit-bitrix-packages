<?php if (!defined('B_PROLOG_INCLUDED') && B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;
use intec\core\helpers\ArrayHelper;
use intec\core\helpers\FileHelper;
use intec\core\helpers\Html;
use intec\core\helpers\Json;

/**
 * @var array $arResult
 * @var string $sTemplateId
 * @var CAllMain $APPLICATION
 * @var CBitrixComponent $component
 */

$sIcon = FileHelper::getFileData(__DIR__.'/../../svg/basket.svg');

?>
<?php $vPurchase = function (&$arItem, $bOffer = false) use (&$arResult, &$sIcon, &$APPLICATION, &$component, &$sTemplateId) { ?>
    <?php if (!empty($arItem['OFFERS']) && !$bOffer) return ?>
    <?php if ($bOffer || $arResult['ACTION'] === 'buy') { ?>
        <?php if ($arItem['CAN_BUY']) { ?>
            <?php $arPrice = ArrayHelper::getFirstValue($arItem['ITEM_PRICES']) ?>
            <?= Html::beginTag('div', [
                'class' => 'catalog-element-purchase-buttons',
                'data-offer' => $bOffer ? $arItem['ID'] : 'false'
            ]) ?>
                <?= Html::beginTag('div', [
                    'class' => [
                        'catalog-element-purchase-button',
                        'catalog-element-purchase-button-add',
                        'intec-ui',
                        'intec-ui-control-basket-button',
                        'intec-cl-background',
                        'intec-cl-background-light-hover',
                        'intec-cl-border',
                        'intec-cl-border-light-hover'
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
                    <span class="intec-aligner"></span>
                    <span class="catalog-element-purchase-button-content intec-ui-part-content">
                        <?= $sIcon ?>
                        <span>
                            <?= Loc::getMessage('C_CATALOG_ELEMENT_DEFAULT_3_TEMPLATE_BUTTON_BASKET_ADD') ?>
                        </span>
                    </span>
                    <span class="intec-ui-part-effect intec-ui-part-effect-bounce">
                        <span class="intec-ui-part-effect-wrapper">
                            <i></i><i></i><i></i>
                        </span>
                    </span>
                <?= Html::endTag('div') ?>
                <?= Html::beginTag('a', [
                    'class' => [
                        'catalog-element-purchase-button',
                        'catalog-element-purchase-button-added',
                        'intec-cl-background-light',
                        'intec-cl-border-light'
                    ],
                    'href' => $arResult['URL']['BASKET'],
                    'title' => Loc::getMessage('C_CATALOG_ELEMENT_DEFAULT_3_TEMPLATE_BUTTON_BASKET_ADDED_TITLE'),
                    'data' => [
                        'basket-id' => $arItem['ID'],
                        'basket-state' => 'none'
                    ]
                ]) ?>
                    <span class="intec-aligner"></span>
                    <span class="catalog-element-purchase-button-content">
                        <?= $sIcon ?>
                        <span>
                            <?= Loc::getMessage('C_CATALOG_ELEMENT_DEFAULT_3_TEMPLATE_BUTTON_BASKET_ADDED') ?>
                        </span>
                    </span>
                <?= Html::endTag('a') ?>
            <?= Html::endTag('div') ?>
        <?php } else { ?>
            <?= Html::beginTag('div', [
                'class' => 'catalog-element-purchase-buttons',
                'data-offer' => $bOffer ? $arItem['ID'] : 'false'
            ]) ?>
                <?php if ($arItem['CATALOG_SUBSCRIBE'] === 'Y') { ?>
                    <?php $APPLICATION->IncludeComponent(
                        'bitrix:catalog.product.subscribe',
                        '.default', [
                            'BUTTON_CLASS' => Html::cssClassFromArray([
                                'catalog-element-purchase-button',
                                'catalog-element-purchase-button-subscribe',
                                'intec-cl-background',
                                'intec-cl-background-light-hover',
                            ]),
                            'BUTTON_ID' => $sTemplateId.'_subscribe_'.$arItem['ID'],
                            'PRODUCT_ID' => $arItem['ID']
                        ]
                    ) ?>
                <?php } else { ?>
                    <?= Html::beginTag('div', [
                        'class' => [
                            'catalog-element-purchase-button',
                            'catalog-element-purchase-button-unavailable',
                            'intec-cl-background-dark'
                        ]
                    ]) ?>
                        <span class="intec-aligner"></span>
                        <span class="catalog-element-purchase-button-content">
                            <span>
                                <?= Loc::getMessage('C_CATALOG_ELEMENT_DEFAULT_3_TEMPLATE_BUTTON_BASKET_UNAVAILABLE') ?>
                            </span>
                        </span>
                    <?= Html::endTag('div') ?>
                <?php } ?>
            <?= Html::endTag('div') ?>
        <?php } ?>
    <?php } else if ($arResult['ACTION'] === 'order') { ?>

    <?php } ?>
<?php } ?>
<?= Html::beginTag('div', [
    'class' => 'intec-grid-item-auto'
]) ?>
    <?php $vPurchase($arResult) ?>
    <?php if (!empty($arResult['OFFERS']) && $arResult['ACTION'] === 'buy') {
        foreach ($arResult['OFFERS'] as &$arOffer)
            $vPurchase($arOffer, true);

        unset($arOffer);
    } ?>
<?= Html::endTag('div') ?>
<?php unset($sIcon, $vPurchase) ?>
