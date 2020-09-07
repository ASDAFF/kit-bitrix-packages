<?php if (!defined('B_PROLOG_INCLUDED') && B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;

/**
 * @var array $arResult
 * @var array $arVisual
 */

?>
<div class="intec-grid intec-grid-a-v-center intec-grid-i-h-25">
    <?php if ($arVisual['INFORMATION']['PAYMENT']) { ?>
        <div class="catalog-element-information-item intec-grid-item">
            <div class="catalog-element-information-item-name">
                <?= Loc::getMessage('C_CATALOG_ELEMENT_QUICK_VIEW_1_INFORMATION_PAYMENT') ?>
            </div>
            <div class="catalog-element-information-item-value">
                <a href="<?= $arResult['URL']['PAYMENT'] ?>" target="_blank">
                    <?= Loc::getMessage('C_CATALOG_ELEMENT_QUICK_VIEW_1_INFORMATION_DETAILED') ?>
                </a>
            </div>
        </div>
    <?php } ?>
    <?php if ($arVisual['INFORMATION']['SHIPMENT']) { ?>
        <div class="catalog-element-information-item intec-grid-item">
            <div class="catalog-element-information-item-name">
                <?= Loc::getMessage('C_CATALOG_ELEMENT_QUICK_VIEW_1_INFORMATION_SHIPMENT') ?>
            </div>
            <div class="catalog-element-information-item-value">
                <a href="<?= $arResult['URL']['SHIPMENT'] ?>" target="_blank">
                    <?= Loc::getMessage('C_CATALOG_ELEMENT_QUICK_VIEW_1_INFORMATION_DETAILED') ?>
                </a>
            </div>
        </div>
    <?php } ?>
</div>
