<?php if (defined("B_PROLOG_INCLUDED") && B_PROLOG_INCLUDED === true) ?>
<?php

use Bitrix\Main\Localization\Loc;

/**
 * @var array $arParams
 * @var array $arResult
 * @var array $arVisual
 */

$vQuantity = function (&$arItem) use (&$arVisual) {
    $fRender = function (&$arItem, $bOffer = false) use (&$arVisual) { ?>
        <div class="catalog-section-item-quantity" data-offer="<?= $bOffer ? $arItem['ID'] : 'false' ?>">
            <div class="intec-grid intec-grid-a-v-center intec-grid-a-h-end intec-grid-a-h-1000-start">
                <?php if ($arItem['CAN_BUY']) { ?>
                    <?php if ($arVisual['QUANTITY']['MODE'] === 'number') { ?>
                        <?php if ($arItem['CATALOG_QUANTITY'] > 0) { ?>
                            <div class="catalog-section-item-quantity-icon catalog-section-item-quantity-available"></div>
                            <span class="catalog-section-item-quantity-value">
                            <?= Loc::getMessage('C_CATALOG_SECTION_CATALOG_LIST_2_QUANTITY_AVAILABLE').': ' ?>
                            <?= $arItem['CATALOG_QUANTITY'] ?>
                            <?php if (!empty($arItem['CATALOG_MEASURE_NAME'])) { ?>
                                <?= $arItem['CATALOG_MEASURE_NAME'].'.' ?>
                            <?php } ?>
                            </span>
                        <?php } else { ?>
                            <div class="catalog-section-item-quantity-icon catalog-section-item-quantity-available"></div>
                            <span class="catalog-section-item-quantity-value">
                                <?= Loc::getMessage('C_CATALOG_SECTION_CATALOG_LIST_2_QUANTITY_AVAILABLE') ?>
                            </span>
                        <?php } ?>
                    <?php } else if ($arVisual['QUANTITY']['MODE'] === 'text') {?>
                        <div class="catalog-section-item-quantity-icon catalog-section-item-quantity-available"></div>
                        <span class="catalog-section-item-quantity-value">
                            <?= Loc::getMessage('C_CATALOG_SECTION_CATALOG_LIST_2_QUANTITY_AVAILABLE').': ' ?>
                            <?php if ($arItem['CATALOG_QUANTITY'] >= $arVisual['QUANTITY']['BOUNDS']['MANY'] || $arItem['CATALOG_QUANTITY'] <= 0) { ?>
                                <?= Loc::getMessage('C_CATALOG_SECTION_CATALOG_LIST_2_QUANTITY_BOUNDS_MANY') ?>
                            <?php } else if ($arItem['CATALOG_QUANTITY'] <= $arVisual['QUANTITY']['BOUNDS']['FEW']) { ?>
                                <?= Loc::getMessage('C_CATALOG_SECTION_CATALOG_LIST_2_QUANTITY_BOUNDS_FEW') ?>
                            <?php } else { ?>
                                <?= Loc::getMessage('C_CATALOG_SECTION_CATALOG_LIST_2_QUANTITY_BOUNDS_ENOUGH') ?>
                            <?php } ?>
                        </span>
                    <?php } else if ($arVisual['QUANTITY']['MODE'] === 'logic') { ?>
                        <div class="catalog-section-item-quantity-icon catalog-section-item-quantity-available"></div>
                        <span class="catalog-section-item-quantity-value">
                            <?= Loc::getMessage('C_CATALOG_SECTION_CATALOG_LIST_2_QUANTITY_AVAILABLE') ?>
                        </span>
                    <?php } ?>
                <?php } else { ?>
                    <div class="catalog-section-item-quantity-icon catalog-section-item-quantity-unavailable"></div>
                    <span class="catalog-section-item-quantity-value">
                        <?= Loc::getMessage('C_CATALOG_SECTION_CATALOG_LIST_2_QUANTITY_UNAVAILABLE') ?>
                    </span>
                <?php } ?>
            </div>
        </div>
    <?php };

    $fRender($arItem, false);

    if (!empty($arItem['OFFERS']))
        foreach ($arItem['OFFERS'] as &$arOffer) {
            $fRender($arOffer, true);

            unset($arOffer);
        }
};