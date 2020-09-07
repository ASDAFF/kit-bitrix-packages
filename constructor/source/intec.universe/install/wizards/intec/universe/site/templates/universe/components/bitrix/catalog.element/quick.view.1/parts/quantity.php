<?php if (!defined('B_PROLOG_INCLUDED') && B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;

/**
 * @var array $arResult
 * @var array $arVisual
 */

/**
 * @param array $arItem
 * @param bool $bOffer
 */
$vQuantity = function (&$arItem, $bOffer = false) use (&$arVisual) { ?>
    <div class="catalog-element-quantity-text" data-offer="<?= $bOffer ? $arItem['ID'] : 'false' ?>">
        <?php if ($arVisual['QUANTITY']['MODE'] === 'number') { ?>
            <span class="catalog-element-quantity-title">
                <?= Loc::getMessage('C_CATALOG_ELEMENT_QUICK_VIEW_1_QUANTITY_TEXT') ?>
            </span>
        <?php } ?>
        <?php if ($arItem['CAN_BUY']) { ?>
            <span class="catalog-element-quantity-value">
                <i class="catalog-element-quantity-available far fa-check"></i>
                <?php if ($arVisual['QUANTITY']['MODE'] === 'number') { ?>
                    <?php if ($arItem['CATALOG_QUANTITY'] > 0) { ?>
                        <?= $arItem['CATALOG_QUANTITY'] ?>
                        <?php if (!empty($arItem['CATALOG_MEASURE_NAME']))
                            echo ' '.$arItem['CATALOG_MEASURE_NAME']
                        ?>
                    <?php } else if (($arItem['CATALOG_QUANTITY_TRACE'] === 'N' || $arItem['CATALOG_CAN_BUY_ZERO'] === 'Y') && $arItem['CATALOG_QUANTITY'] <= 0 ) { ?>
                        <?= Loc::getMessage('C_CATALOG_ELEMENT_QUICK_VIEW_1_AVAILABLE') ?>
                    <?php } ?>
                <?php } else if ($arVisual['QUANTITY']['MODE'] === 'text') { ?>
                    <?php if ($arItem['CATALOG_QUANTITY'] > 0 && $arItem['CATALOG_QUANTITY'] <= $arVisual['QUANTITY']['BOUNDS']['FEW']) { ?>
                        <?= Loc::getMessage('C_CATALOG_ELEMENT_QUICK_VIEW_1_QUANTITY_FEW') ?>
                    <?php } else if ($arItem['CATALOG_QUANTITY'] >= $arVisual['QUANTITY']['BOUNDS']['MANY']) { ?>
                        <?= Loc::getMessage('C_CATALOG_ELEMENT_QUICK_VIEW_1_QUANTITY_MANY') ?>
                    <?php } else if ($arItem['CATALOG_QUANTITY'] > $arVisual['QUANTITY']['BOUNDS']['FEW'] && $arItem['CATALOG_QUANTITY'] < $arVisual['QUANTITY']['BOUNDS']['MANY']) { ?>
                        <?= Loc::getMessage('C_CATALOG_ELEMENT_QUICK_VIEW_1_QUANTITY_ENOUGH') ?>
                    <?php } else if ($arItem['CATALOG_QUANTITY_TRACE'] === 'N' || $arItem['CATALOG_CAN_BUY_ZERO'] === 'Y') { ?>
                        <?= Loc::getMessage('C_CATALOG_ELEMENT_QUICK_VIEW_1_QUANTITY_MANY') ?>
                    <?php } ?>
                <?php } else if ($arVisual['QUANTITY']['MODE'] === 'logic') { ?>
                    <?= Loc::getMessage('C_CATALOG_ELEMENT_QUICK_VIEW_1_AVAILABLE') ?>
                <?php } ?>
            </span>
        <?php } else { ?>
            <span class="catalog-element-quantity-value">
                <i class="catalog-element-quantity-unavailable far fa-times"></i>
                <?= Loc::getMessage('C_CATALOG_ELEMENT_QUICK_VIEW_1_UNAVAILABLE') ?>
            </span>
        <?php } ?>
    </div>
<?php };

$vQuantity($arResult);

if (!empty($arResult['OFFERS']))
    foreach ($arResult['OFFERS'] as &$arOffer) {
        $vQuantity($arOffer, true);

        unset($arOffer);
    }

unset($vQuantity);