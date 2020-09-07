<?php if (!defined('B_PROLOG_INCLUDED') && B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;
use intec\core\helpers\Html;
use intec\core\helpers\StringHelper;

/**
 * @var array $arParams
 * @var array $arVisual
 */

$vQuantity = function (&$arItem) use (&$arVisual, &$arParams, &$APPLICATION, &$component) {
    $fRender = function (&$arItem, $bOffer = false) use (&$arVisual, &$arParams, &$APPLICATION, &$component) { ?>
        <?php if ($arVisual['OFFERS']['USE'] && !empty($arItem['OFFERS']) && !$bOffer) return ?>
        <?= Html::beginTag('div', [
            'class' => 'catalog-section-item-quantity',
            'data-offer' => $bOffer ? $arItem['ID'] : 'false'
        ]) ?>
            <div class="intec-grid intec-grid-nowrap intec-grid-a-v-center intec-grid-i-h-4">
                <?php if ($arItem['CAN_BUY']) { ?>
                    <?php if ($arVisual['QUANTITY']['MODE'] !== 'text') { ?>
                        <div class="intec-grid-item-auto">
                            <div class="catalog-section-item-quantity-icon" data-quantity-state="many"></div>
                        </div>
                        <div class="intec-grid-item">
                            <div class="catalog-section-item-quantity-value" data-quantity-state="many">
                            <?php $iOffset = StringHelper::position('.', $arItem['CATALOG_QUANTITY']);

                                $iPrecision = 0;

                                if ($iOffset)
                                    $iPrecision = StringHelper::length(
                                        StringHelper::cut($arItem['CATALOG_QUANTITY'], $iOffset + 1)
                                    );

                                $arItem['CATALOG_QUANTITY'] = number_format(
                                    $arItem['CATALOG_QUANTITY'],
                                    $iPrecision,
                                    '.',
                                    ' '
                                );

                                unset($iOffset, $iPrecision);

                            ?>
                                <?php if ($arVisual['QUANTITY']['MODE'] === 'number' && $arItem['CATALOG_QUANTITY'] > 0) { ?>
                                    <span data-role="stores.popup.button" data-popup="<?= $arItem['DATA']['STORES']['SHOW'] ? 'toggle' : 'false' ?>">
                                        <?= Loc::getMessage('C_CATALOG_SECTION_CATALOG_TILE_4_QUANTITY_AVAILABLE') ?>
                                    </span>
                                    <span class="catalog-section-item-quantity-value-numeric">
                                        <?= Loc::getMessage('C_CATALOG_SECTION_CATALOG_TILE_4_QUANTITY_VALUE_MEASURE', [
                                            '#VALUE#' => $arItem['CATALOG_QUANTITY'],
                                            '#MEASURE#' => !empty($arItem['CATALOG_MEASURE_NAME']) ? ' '.$arItem['CATALOG_MEASURE_NAME']: null
                                        ]) ?>
                                    </span>
                                <?php } else { ?>
                                    <span data-role="stores.popup.button" data-popup="<?= $arItem['DATA']['STORES']['SHOW'] ? 'toggle' : 'false' ?>">
                                        <?= Loc::getMessage('C_CATALOG_SECTION_CATALOG_TILE_4_QUANTITY_AVAILABLE') ?>
                                    </span>
                                <?php } ?>
                            </div>
                        </div>
                    <?php } else {
                        $sState = 'empty';

                        if ($arItem['CATALOG_QUANTITY'] > $arVisual['QUANTITY']['BOUNDS']['MANY'])
                            $sState = 'many';
                        else if ($arItem['CATALOG_QUANTITY'] < $arVisual['QUANTITY']['BOUNDS']['MANY'] && $arItem['CATALOG_QUANTITY'] > $arVisual['QUANTITY']['BOUNDS']['FEW'])
                            $sState = 'enough';
                        else if ($arItem['CATALOG_QUANTITY'] < $arVisual['QUANTITY']['BOUNDS']['FEW'] && $arItem['CATALOG_QUANTITY'] > 0)
                            $sState = 'few';
                        else if ($arItem['CATALOG_QUANTITY_TRACE'] === 'N' || $arItem['CATALOG_CAN_BUY_ZERO'] === 'Y')
                            $sState = 'many'
                    ?>
                        <div class="intec-grid-item-auto">
                            <div class="catalog-section-item-quantity-icon" data-quantity-state="<?= $sState ?>"></div>
                        </div>
                        <div class="intec-grid-item">
                            <div class="catalog-section-item-quantity-value" data-quantity-state="<?= $sState ?>">
                                <?php if ($arVisual['QUANTITY']['MODE'] === 'text') { ?>
                                    <span>
                                        <?php if ($sState === 'many') { ?>
                                            <?= Loc::getMessage('C_CATALOG_SECTION_CATALOG_TILE_4_QUANTITY_BOUNDS_MANY') ?>
                                        <?php } else if ($sState === 'enough') { ?>
                                            <?= Loc::getMessage('C_CATALOG_SECTION_CATALOG_TILE_4_QUANTITY_BOUNDS_ENOUGH') ?>
                                        <?php } else if ($sState === 'few') { ?>
                                            <?= Loc::getMessage('C_CATALOG_SECTION_CATALOG_TILE_4_QUANTITY_BOUNDS_FEW') ?>
                                        <?php } ?>
                                    </span>
                                <?php } ?>
                            </div>
                        </div>
                    <?php } ?>
                <?php } else { ?>
                    <div class="intec-grid-item-auto">
                        <div class="catalog-section-item-quantity-icon" data-quantity-state="empty"></div>
                    </div>
                    <div class="intec-grid-item">
                        <div class="catalog-section-item-quantity-value" data-quantity-state="empty">
                            <span data-role="stores.popup.button" data-popup="<?= $arItem['DATA']['STORES']['SHOW'] ? 'toggle' : 'false' ?>">
                                <?= Loc::getMessage('C_CATALOG_SECTION_CATALOG_TILE_4_QUANTITY_UNAVAILABLE') ?>
                            </span>
                        </div>
                    </div>
                <?php } ?>
            </div>
            <?php if ($arItem['DATA']['STORES']['SHOW']) { ?>
                <?= Html::beginTag('div', [
                    'class' => 'catalog-section-item-stores',
                    'data' => [
                        'role' => 'stores.popup.window'
                    ]
                ]) ?>
                    <div class="catalog-section-item-stores-background">
                        <div class="catalog-section-item-stores-header intec-grid intec-grid-a-v-center intec-grid-a-h-between">
                            <div class="catalog-section-item-stores-title">
                                <?= Loc::getMessage('C_CATALOG_SECTION_CATALOG_TILE_4_STORES_TITLE') ?>
                            </div>
                            <?= Html::beginTag('div', [
                                'class' => 'catalog-section-item-stores-button-close',
                                'data' => [
                                    'role' => 'stores.popup.button',
                                    'popup' => 'close'
                                ]
                            ]) ?>
                            <i class="fal fa-times"></i>
                            <?= Html::endTag('div') ?>
                        </div>
                        <?php $APPLICATION->IncludeComponent(
                            'bitrix:catalog.store.amount',
                            'popup.1',
                            [
                                'ELEMENT_ID' => $arItem['ID'],
                                'STORE_PATH' => $arParams['STORE_PATH'],
                                'CACHE_TYPE' => 'N',
                                'MAIN_TITLE' => '',
                                'USE_MIN_AMOUNT' => $arParams['USE_MIN_AMOUNT'],
                                'MIN_AMOUNT' => $arParams['MIN_AMOUNT'],
                                'STORES' => $arParams['STORES'],
                                'SHOW_EMPTY_STORE' => $arParams['SHOW_EMPTY_STORE'],
                                'SHOW_GENERAL_STORE_INFORMATION' => $arParams['SHOW_GENERAL_STORE_INFORMATION'],
                                'USER_FIELDS' => $arParams['USER_FIELDS'],
                                'FIELDS' => $arParams['FIELDS']
                            ],
                            $component,
                            [
                                'HIDE_ICONS' => 'Y'
                            ]
                        ) ?>
                    </div>
                <?= Html::endTag('div') ?>
            <?php } ?>
        <?= Html::endTag('div') ?>
    <?php };

    $fRender($arItem, false);

    if (!empty($arItem['OFFERS']))
        foreach ($arItem['OFFERS'] as &$arOffer) {
            $fRender($arOffer, true);

            unset($arOffer);
        }
};