<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;
use intec\core\helpers\Html;
use intec\core\helpers\Type;

/**
 * @var array $arParams
 * @var array $arResult
 * @var CBitrixComponentTemplate $this
 * @var CBitrixComponent $component
 */

?>
<div class="sale-personal-order-detail-block sale-personal-order-detail-block-splitted sale-personal-order-detail-basket" data-block="basket">
    <h2 class="sale-personal-order-detail-block-header intec-ui-markup-header">
        <?= Loc::getMessage('C_SALE_PERSONAL_ORDER_DETAIL_DEFAULT_BLOCKS_BASKET_TITLE') ?>
    </h2>
    <div class="sale-personal-order-detail-block-content">
        <div class="sale-personal-order-detail-basket-items">
            <table class="sale-personal-order-detail-basket-items-table">
                <thead>
                    <tr>
                        <th colspan="2"><?= Loc::getMessage('C_SALE_PERSONAL_ORDER_DETAIL_DEFAULT_BLOCKS_BASKET_TABLE_HEADERS_NAME') ?></th>
                        <th><?= Loc::getMessage('C_SALE_PERSONAL_ORDER_DETAIL_DEFAULT_BLOCKS_BASKET_TABLE_HEADERS_PRICE') ?></th>
                        <?php if (!empty($arResult['SHOW_DISCOUNT_TAB'])) { ?>
                            <th><?= Loc::getMessage('C_SALE_PERSONAL_ORDER_DETAIL_DEFAULT_BLOCKS_BASKET_TABLE_HEADERS_DISCOUNT') ?></th>
                        <?php } ?>
                        <th><?= Loc::getMessage('C_SALE_PERSONAL_ORDER_DETAIL_DEFAULT_BLOCKS_BASKET_TABLE_HEADERS_QUANTITY') ?></th>
                        <th><?= Loc::getMessage('C_SALE_PERSONAL_ORDER_DETAIL_DEFAULT_BLOCKS_BASKET_TABLE_HEADERS_SUM') ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($arResult['BASKET'] as $arItem) { ?>
                        <tr class="sale-personal-order-detail-basket-item">
                            <td>
                                <?= Html::beginTag('a', [
                                    'href' => $arItem['DETAIL_PAGE_URL'],
                                    'target' => '_blank',
                                    'class' => 'sale-personal-order-detail-basket-item-picture'
                                ]) ?>
                                    <div class="sale-personal-order-detail-basket-item-picture-wrapper">
                                        <?= Html::tag('div', null, [
                                            'class' => 'sale-personal-order-detail-basket-item-picture-wrapper-2',
                                            'style' => [
                                                'background-image' => 'url(\''.(!empty($arItem['PICTURE']) ? $arItem['PICTURE']['SRC'] : SITE_TEMPLATE_PATH.'/images/picture.missing.png').'\')'
                                            ]
                                        ]) ?>
                                    </div>
                                <?= Html::endTag('a') ?>
                            </td>
                            <td>
                                <?= Html::tag('a', $arItem['NAME'], [
                                    'href' => $arItem['DETAIL_PAGE_URL'],
                                    'target' => '_blank',
                                    'class' => 'sale-personal-order-detail-basket-item-name'
                                ]) ?>
                                <?php if (!empty($arItem['PROPS']) && Type::isArray($arItem['PROPS'])) { ?>
                                    <div class="sale-personal-order-detail-basket-item-properties">
                                        <?php foreach ($arItem['PROPS'] as $arProperty) { ?>
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
                                <div class="sale-personal-order-detail-basket-item-price">
                                    <div class="sale-personal-order-detail-basket-item-price-discount">
                                        <?= $arItem['PRICE_FORMATED'] ?>
                                    </div>
                                    <?php if ($arItem['PRICE_FORMATED'] != $arItem['BASE_PRICE_FORMATED']) { ?>
                                        <div class="sale-personal-order-detail-basket-item-price-base">
                                            <?= $arItem['BASE_PRICE_FORMATED'] ?>
                                        </div>
                                    <?php } ?>
                                </div>
                            </td>
                            <?php if (!empty($arResult['SHOW_DISCOUNT_TAB']) || !empty($arItem['DISCOUNT_PRICE_PERCENT_FORMATED'])) { ?>
                                <td>
                                    <div class="sale-personal-order-detail-basket-item-discount">
                                        <?php if (!empty($arItem['DISCOUNT_PRICE_PERCENT_FORMATED'])) { ?>
                                            <?= $arItem['DISCOUNT_PRICE_PERCENT_FORMATED'] ?>
                                        <?php } ?>
                                    </div>
                                </td>
                            <?php } ?>
                            <td>
                                <div class="sale-personal-order-detail-basket-item-quantity">
                                    <?= $arItem['QUANTITY'] ?>
                                    <?= !empty($arBasketItem['MEASURE_NAME']) ? Html::encode($arBasketItem['MEASURE_NAME']) : Loc::getMessage('C_SALE_PERSONAL_ORDER_DETAIL_DEFAULT_BLOCKS_BASKET_ITEM_MEASURE') ?>
                                </div>
                            </td>
                            <td>
                                <div class="sale-personal-order-detail-basket-item-sum">
                                    <?= $arItem['FORMATED_SUM'] ?>
                                </div>
                            </td>
                        </tr>
                    <?php } ?>
                    <?php unset($arItem) ?>
                </tbody>
            </table>
        </div>
    </div>
</div>