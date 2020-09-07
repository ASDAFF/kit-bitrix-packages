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
            <div class="intec-grid intec-grid-a-v-baseline">
                <?php if ($arItem['CAN_BUY']) { ?>
                    <?php if ($arVisual['QUANTITY']['MODE'] === 'number') { ?>
                        <?php if ($arItem['CATALOG_QUANTITY'] > 0) { ?>
                            <div class="catalog-section-item-quantity-icon intec-grid-item-auto catalog-section-item-quantity-available"></div>
                            <div class="catalog-section-item-quantity-value intec-grid-item">
                                <div>
                                    <?= Loc::getMessage('C_CATALOG_SECTION_CATALOG_TEXT_2_QUANTITY_AVAILABLE') ?>
                                </div>
                                <div>
                                    <?= $arItem['CATALOG_QUANTITY'] ?> <?= !empty($arItem['CATALOG_MEASURE_NAME']) ? $arItem['CATALOG_MEASURE_NAME'].'.' : '' ?>
                                </div>
                            </div>
                        <?php } else { ?>
                            <div class="catalog-section-item-quantity-icon intec-grid-item-auto catalog-section-item-quantity-available"></div>
                            <span class="catalog-section-item-quantity-value intec-grid-item">
                                <?= Loc::getMessage('C_CATALOG_SECTION_CATALOG_TEXT_2_QUANTITY_AVAILABLE') ?>
                            </span>
                        <?php } ?>
                    <?php } else if ($arVisual['QUANTITY']['MODE'] === 'text') {?>
                        <?php if ($arItem['CATALOG_QUANTITY'] >= $arVisual['QUANTITY']['BOUNDS']['MANY'] || $arItem['CATALOG_QUANTITY'] <= 0) { ?>
                            <div class="catalog-section-item-quantity-icon intec-grid-item-auto catalog-section-item-quantity-available"></div>
                            <div class="catalog-section-item-quantity-value intec-grid-item">
                                <?= Loc::getMessage('C_CATALOG_SECTION_CATALOG_TEXT_2_QUANTITY_BOUNDS_MANY') ?>
                            </div>
                        <?php } else if ($arItem['CATALOG_QUANTITY'] <= $arVisual['QUANTITY']['BOUNDS']['FEW']) { ?>
                            <div class="catalog-section-item-quantity-icon intec-grid-item-auto catalog-section-item-quantity-few"></div>
                            <div class="catalog-section-item-quantity-value intec-grid-item">
                                <?= Loc::getMessage('C_CATALOG_SECTION_CATALOG_TEXT_2_QUANTITY_BOUNDS_FEW') ?>
                            </div>
                        <?php } else if ($arItem['CATALOG_QUANTITY'] >= $arVisual['QUANTITY']['BOUNDS']['FEW'] && $arItem['CATALOG_QUANTITY'] <= $arVisual['QUANTITY']['BOUNDS']['MANY']) { ?>
                            <div class="catalog-section-item-quantity-icon intec-grid-item-auto catalog-section-item-quantity-available"></div>
                            <div class="catalog-section-item-quantity-value intec-grid-item">
                                <?= Loc::getMessage('C_CATALOG_SECTION_CATALOG_TEXT_2_QUANTITY_BOUNDS_ENOUGH') ?>
                            </div>
                        <?php } ?>
                    <?php } else if ($arVisual['QUANTITY']['MODE'] === 'logic') { ?>
                        <div class="catalog-section-item-quantity-icon intec-grid-item-auto catalog-section-item-quantity-available"></div>
                        <div class="catalog-section-item-quantity-value intec-grid-item">
                            <?= Loc::getMessage('C_CATALOG_SECTION_CATALOG_TEXT_2_QUANTITY_AVAILABLE') ?>
                        </div>
                    <?php } ?>
                <?php } else { ?>
                    <div class="catalog-section-item-quantity-icon intec-grid-item-auto catalog-section-item-quantity-unavailable"></div>
                    <div class="catalog-section-item-quantity-value intec-grid-item">
                        <?= Loc::getMessage('C_CATALOG_SECTION_CATALOG_TEXT_2_QUANTITY_UNAVAILABLE') ?>
                    </div>
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