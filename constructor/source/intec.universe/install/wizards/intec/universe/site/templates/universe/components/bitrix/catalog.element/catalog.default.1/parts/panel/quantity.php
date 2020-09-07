<?php if (!defined('B_PROLOG_INCLUDED') && B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;

/**
 * @var array $arResult
 * @var array $arVisual
 */

/**
 * @param $arItem
 * @param bool $bOffer
 */
$vPanelQuantity = function (&$arItem, $bOffer = false) use (&$arVisual) { ?>
    <div class="catalog-element-panel-quantity" data-offer="<?= $bOffer ? $arItem['ID'] : 'false' ?>">
        <?php if ($arItem['CAN_BUY']) { ?>
            <span class="catalog-element-panel-quantity-icon catalog-element-panel-quantity-available">
                <i class="far fa-check"></i>
            </span>
            <?php if ($arVisual['QUANTITY']['MODE'] === 'number') { ?>
                <?php if ($arItem['CATALOG_QUANTITY'] > 0) { ?>
                    <?= Loc::getMessage('C_CATALOG_ELEMENT_CATALOG_DEFAULT_1_QUANTITY_AVAILABLE').': ' ?>
                    <?= $arItem['CATALOG_QUANTITY'] ?>
                    <?php if (!empty($arItem['CATALOG_MEASURE_NAME'])) {
                        echo ' '.$arItem['CATALOG_MEASURE_NAME'];
                    } ?>
                <?php } else if (($arItem['CATALOG_QUANTITY_TRACE'] === 'N' || $arItem['CATALOG_CAN_BUY_ZERO'] === 'Y') && $arItem['CATALOG_QUANTITY'] <= 0) { ?>
                    <?= Loc::getMessage('C_CATALOG_ELEMENT_CATALOG_DEFAULT_1_QUANTITY_AVAILABLE') ?>
                <?php } ?>
            <?php } else if ($arVisual['QUANTITY']['MODE'] === 'text') { ?>
                <?= Loc::getMessage('C_CATALOG_ELEMENT_CATALOG_DEFAULT_1_QUANTITY_AVAILABLE').': ' ?>
                <?php if ($arItem['CATALOG_QUANTITY'] > 0 && $arItem['CATALOG_QUANTITY'] <= $arVisual['QUANTITY']['BOUNDS']['FEW']) { ?>
                    <?= Loc::getMessage('C_CATALOG_ELEMENT_CATALOG_DEFAULT_1_QUANTITY_BOUNDS_FEW') ?>
                <?php } else if ($arItem['CATALOG_QUANTITY'] >= $arVisual['QUANTITY']['BOUNDS']['MANY']) { ?>
                    <?= Loc::getMessage('C_CATALOG_ELEMENT_CATALOG_DEFAULT_1_QUANTITY_BOUNDS_MANY') ?>
                <?php } else if ($arItem['CATALOG_QUANTITY'] > $arVisual['QUANTITY']['BOUNDS']['FEW'] && $arItem['CATALOG_QUANTITY'] < $arVisual['QUANTITY']['BOUNDS']['MANY']) { ?>
                    <?= Loc::getMessage('C_CATALOG_ELEMENT_CATALOG_DEFAULT_1_QUANTITY_BOUNDS_ENOUGH') ?>
                <?php } else if ($arItem['CATALOG_QUANTITY_TRACE'] === 'N' || $arItem['CATALOG_CAN_BUY_ZERO'] === 'Y') { ?>
                    <?= Loc::getMessage('C_CATALOG_ELEMENT_CATALOG_DEFAULT_1_QUANTITY_BOUNDS_MANY') ?>
                <?php } ?>
            <?php } else if ($arVisual['QUANTITY']['MODE'] === 'logic') { ?>
                <?= Loc::getMessage('C_CATALOG_ELEMENT_CATALOG_DEFAULT_1_QUANTITY_AVAILABLE') ?>
            <?php } ?>
        <?php } else { ?>
            <span class="catalog-element-panel-quantity-icon catalog-element-panel-quantity-unavailable">
                <i class="far fa-times"></i>
            </span>
            <?= Loc::getMessage('C_CATALOG_ELEMENT_CATALOG_DEFAULT_1_QUANTITY_UNAVAILABLE'); ?>
        <?php } ?>
    </div>
<?php };

$vPanelQuantity($arResult);

if (!empty($arResult['OFFERS']))
    foreach ($arResult['OFFERS'] as &$arOffer) {
        $vPanelQuantity($arOffer, true);

        unset($arOffer);
    }

unset($vPanelQuantity);