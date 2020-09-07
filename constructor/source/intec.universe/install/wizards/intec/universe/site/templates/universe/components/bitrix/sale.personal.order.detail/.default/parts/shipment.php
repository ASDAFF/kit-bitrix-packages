<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;
use intec\core\helpers\ArrayHelper;
use intec\core\helpers\Html;
use intec\core\helpers\Type;

/**
 * @var array $arParams
 * @var array $arResult
 * @var CBitrixComponentTemplate $this
 * @var CBitrixComponent $component
 */

?>
<div class="sale-personal-order-detail-block sale-personal-order-detail-block-splitted sale-personal-order-detail-shipment" data-block="shipment">
    <h2 class="sale-personal-order-detail-block-header intec-ui-markup-header">
        <?= Loc::getMessage('C_SALE_PERSONAL_ORDER_DETAIL_DEFAULT_BLOCKS_SHIPMENT_TITLE') ?>
    </h2>
    <div class="sale-personal-order-detail-block-content">
        <div class="sale-personal-order-detail-shipment-items">
            <?php foreach ($arResult['SHIPMENT'] as $arShipment) { ?>
            <?php
                $arStore = !empty($arShipment['STORE_ID']) && !empty($arResult['DELIVERY']['STORE_LIST'][$arShipment['STORE_ID']]) ?
                    $arResult['DELIVERY']['STORE_LIST'][$arShipment['STORE_ID']] :
                    null
            ?>
                <div class="sale-personal-order-detail-shipment-item">
                    <div class="sale-personal-order-detail-shipment-item-name">
                    <?php
                        echo Loc::getMessage('C_SALE_PERSONAL_ORDER_DETAIL_DEFAULT_BLOCKS_SHIPMENT_ITEM_NAME_1', [
                            '#NUMBER#' => Html::encode($arShipment['ACCOUNT_NUMBER'])
                        ]);

                        if (!empty($arShipment['DATE_DEDUCTED'])) {
                            echo ' ';
                            echo Loc::getMessage('C_SALE_PERSONAL_ORDER_DETAIL_DEFAULT_BLOCKS_SHIPMENT_ITEM_NAME_2', [
                                '#DATE#' => $arShipment['DATE_DEDUCTED']->format($arParams['ACTIVE_DATE_FORMAT'])
                            ]);
                        }

                        echo Loc::getMessage('C_SALE_PERSONAL_ORDER_DETAIL_DEFAULT_BLOCKS_SHIPMENT_ITEM_NAME_3', [
                            '#SUM#' => !empty($arShipment['PRICE_DELIVERY_FORMATED']) ? $arShipment['PRICE_DELIVERY_FORMATED'] : '0'
                        ]);
                    ?>
                    </div>
                    <?php if (!empty($arShipment['DELIVERY_NAME']) || !empty($arShipment['STATUS_NAME']) || !empty($arShipment['TRACKING_NUMBER'])) { ?>
                        <div class="sale-personal-order-detail-shipment-item-fields sale-personal-order-detail-block-fields intec-grid intec-grid-wrap intec-grid-a-v-start intec-grid-i-v-10">
                            <?php if (!empty($arShipment['DELIVERY_NAME'])) { ?>
                                <div class="sale-personal-order-detail-block-field intec-grid-item-1" data-field="name">
                                    <div class="sale-personal-order-detail-block-field-name">
                                        <?= Loc::getMessage('C_SALE_PERSONAL_ORDER_DETAIL_DEFAULT_BLOCKS_SHIPMENT_ITEM_FIELDS_NAME') ?>:
                                    </div>
                                    <div class="sale-personal-order-detail-block-field-value">
                                        <?= Html::encode($arShipment['DELIVERY_NAME']) ?>
                                    </div>
                                </div>
                            <?php } ?>
                            <?php if (!empty($arShipment['STATUS_NAME'])) { ?>
                                <div class="sale-personal-order-detail-block-field intec-grid-item-1" data-field="name">
                                    <div class="sale-personal-order-detail-block-field-name">
                                        <?= Loc::getMessage('C_SALE_PERSONAL_ORDER_DETAIL_DEFAULT_BLOCKS_SHIPMENT_ITEM_FIELDS_STATE') ?>:
                                    </div>
                                    <div class="sale-personal-order-detail-block-field-value">
                                        <?= Html::encode($arShipment['STATUS_NAME']) ?>
                                    </div>
                                </div>
                            <?php } ?>
                            <?php if (!empty($arShipment['TRACKING_NUMBER'])) { ?>
                                <div class="sale-personal-order-detail-block-field intec-grid-item-1" data-field="name">
                                    <div class="sale-personal-order-detail-block-field-name">
                                        <?= Loc::getMessage('C_SALE_PERSONAL_ORDER_DETAIL_DEFAULT_BLOCKS_SHIPMENT_ITEM_FIELDS_TRACK') ?>:
                                    </div>
                                    <div class="sale-personal-order-detail-block-field-value">
                                        <span class="sale-personal-order-detail-shipment-item-track"><?= Html::encode($arShipment['TRACKING_NUMBER']) ?></span>
                                        <?php if (!empty($arShipment['TRACKING_URL'])) { ?>
                                            <span>
                                                (<?= Html::tag('a', Loc::getMessage('C_SALE_PERSONAL_ORDER_DETAIL_DEFAULT_BLOCKS_SHIPMENT_ITEM_TRACKING'), [
                                                    'href' => $arShipment['TRACKING_URL'],
                                                    'target' => '_blank',
                                                    'class' => 'intec-ui-mod-dashed'
                                                ]) ?>)
                                            </span>
                                        <?php } ?>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                    <?php } ?>
                    <?php if (!empty($arStore) || !empty($arShipment['ITEMS'])) { ?>
                        <div class="sale-personal-order-detail-shipment-item-switch">
                            <a class="sale-personal-order-detail-shipment-item-switch-expand intec-ui-mod-dashed">
                                <?= Loc::getMessage('C_SALE_PERSONAL_ORDER_DETAIL_DEFAULT_BLOCKS_SHIPMENT_ITEM_SWITCH_EXPAND') ?>
                            </a>
                            <a class="sale-personal-order-detail-shipment-item-switch-collapse intec-ui-mod-dashed">
                                <?= Loc::getMessage('C_SALE_PERSONAL_ORDER_DETAIL_DEFAULT_BLOCKS_SHIPMENT_ITEM_SWITCH_COLLAPSE') ?>
                            </a>
                        </div>
                        <div class="sale-personal-order-detail-shipment-item-information">
                            <?php if (!empty($arStore)) { ?>
                                <div class="sale-personal-order-detail-shipment-item-store">
                                    <h6 class="sale-personal-order-detail-shipment-item-store-title intec-ui-markup-header">
                                        <?= Loc::getMessage('C_SALE_PERSONAL_ORDER_DETAIL_DEFAULT_BLOCKS_SHIPMENT_ITEM_STORE_TITLE') ?>
                                    </h6>
                                    <div class="sale-personal-order-detail-shipment-item-store-map">
                                        <?php $APPLICATION->IncludeComponent(
                                            'bitrix:map.yandex.view',
                                            '.default',
                                            [
                                                'INIT_MAP_TYPE' => 'COORDINATES',
                                                'MAP_DATA' => !empty($arStore['GPS_S']) && !empty($arStore['GPS_N']) ? serialize([
                                                    'yandex_lon' => $arStore['GPS_S'],
                                                    'yandex_lat' => $arStore['GPS_N'],
                                                    'PLACEMARKS' => [[
                                                        'LON' => $arStore['GPS_S'],
                                                        'LAT' => $arStore['GPS_N'],
                                                        'TEXT' => Html::encode(!empty($arStore['TITLE']) ? $arStore['TITLE'] : $arStore['ADDRESS'])
                                                    ]]
                                                ]) : null,
                                                'MAP_WIDTH' => '100%',
                                                'MAP_HEIGHT' => '300px',
                                                'OVERLAY' => 'Y',
                                                'CONTROLS' => [
                                                    'ZOOM',
                                                    'SMALLZOOM',
                                                    'SCALELINE'
                                                ],
                                                'OPTIONS' => [
                                                    'ENABLE_DRAGGING',
                                                    'ENABLE_SCROLL_ZOOM',
                                                    'ENABLE_DBLCLICK_ZOOM'
                                                ],
                                                'MAP_ID' => ''
                                            ],
                                            $component
                                        ) ?>
                                    </div>
                                    <?php if (!empty($arStore['ADDRESS'])) { ?>
                                        <div class="sale-personal-order-detail-shipment-item-store-address">
                                            <div class="sale-personal-order-detail-shipment-item-store-address-title">
                                                <?= Loc::getMessage('C_SALE_PERSONAL_ORDER_DETAIL_DEFAULT_BLOCKS_SHIPMENT_ITEM_STORE_ADDRESS') ?>:
                                            </div>
                                            <div class="sale-personal-order-detail-shipment-item-store-address-value">
                                                <?= Html::encode($arStore['ADDRESS']) ?>
                                            </div>
                                        </div>
                                    <?php } ?>
                                </div>
                            <?php } ?>
                            <?php if (!empty($arShipment['ITEMS'])) { ?>
                                <div class="sale-personal-order-detail-shipment-item-items">
                                    <table class="sale-personal-order-detail-shipment-item-items-table">
                                        <thead>
                                            <tr>
                                                <th colspan="2"><?= Loc::getMessage('C_SALE_PERSONAL_ORDER_DETAIL_DEFAULT_BLOCKS_BASKET_TABLE_HEADERS_NAME') ?></th>
                                                <th><?= Loc::getMessage('C_SALE_PERSONAL_ORDER_DETAIL_DEFAULT_BLOCKS_BASKET_TABLE_HEADERS_QUANTITY') ?></th>
                                            </tr>
                                        </thead>
                                        <?php foreach ($arShipment['ITEMS'] as $arItem) { ?>
                                        <?php
                                            $arBasketItem = ArrayHelper::getValue($arResult['BASKET'], $arItem['BASKET_ID']);

                                            if (empty($arBasketItem))
                                                continue;
                                        ?>
                                            <tr class="sale-personal-order-detail-shipment-item-item">
                                                <td>
                                                    <?= Html::beginTag('a', [
                                                        'href' => $arBasketItem['DETAIL_PAGE_URL'],
                                                        'target' => '_blank',
                                                        'class' => 'sale-personal-order-detail-shipment-item-item-picture'
                                                    ]) ?>
                                                    <div class="sale-personal-order-detail-shipment-item-item-picture-wrapper">
                                                        <?= Html::tag('div', null, [
                                                            'class' => 'sale-personal-order-detail-shipment-item-item-picture-wrapper-2',
                                                            'style' => [
                                                                'background-image' => 'url(\''.(!empty($arBasketItem['PICTURE']) ? $arBasketItem['PICTURE']['SRC'] : SITE_TEMPLATE_PATH.'/images/picture.missing.png').'\')'
                                                            ]
                                                        ]) ?>
                                                    </div>
                                                    <?= Html::endTag('a') ?>
                                                </td>
                                                <td>
                                                    <?= Html::tag('a', $arBasketItem['NAME'], [
                                                        'href' => $arBasketItem['DETAIL_PAGE_URL'],
                                                        'target' => '_blank',
                                                        'class' => 'sale-personal-order-detail-basket-item-name'
                                                    ]) ?>
                                                    <?php if (!empty($arBasketItem['PROPS']) && Type::isArray($arBasketItem['PROPS'])) { ?>
                                                        <div class="sale-personal-order-detail-basket-item-properties">
                                                            <?php foreach ($arBasketItem['PROPS'] as $arProperty) { ?>
                                                                <div class="sale-personal-order-detail-basket-item-property">
                                                                    <div class="sale-personal-order-detail-basket-item-property-name">
                                                                        <?= Html::encode($arProperty['NAME']) ?>:
                                                                    </div>
                                                                    <div class="sale-personal-order-detail-basket-item-property-value">
                                                                        <?= Html::encode($arProperty['VALUE']) ?>
                                                                    </div>
                                                                </div>
                                                            <?php } ?>
                                                            <?php unset($arProperty) ?>
                                                        </div>
                                                    <?php } ?>
                                                </td>
                                                <td>
                                                    <div class="sale-personal-order-detail-basket-item-quantity">
                                                        <?= $arItem['QUANTITY'] ?>
                                                        <?= !empty($arItem['MEASURE_NAME']) ? Html::encode($arItem['MEASURE_NAME']) : Loc::getMessage('C_SALE_PERSONAL_ORDER_DETAIL_DEFAULT_BLOCKS_BASKET_ITEM_MEASURE') ?>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                        <?php unset($arItem, $arBasketItem) ?>
                                    </table>
                                </div>
                            <?php } ?>
                        </div>
                    <?php } ?>
                </div>
            <?php } ?>
            <?php unset($arStore) ?>
        </div>
    </div>
</div>