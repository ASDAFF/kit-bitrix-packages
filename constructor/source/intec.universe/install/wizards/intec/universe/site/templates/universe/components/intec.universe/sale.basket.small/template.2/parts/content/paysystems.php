<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die() ?>
<?php
/**
 * @var $arParams
 * @var CBitrixComponentTemplate $this
 */
?>
<div class="sale-basket-small-pay-system">
    <div class="sale-basket-small-pay-system-wrapper intec-grid intec-grid-nowrap intec-grid-a-v-center">
        <?php if ($arParams['SBERBANK_ICON_SHOW'] == 'Y') {?>
            <div class="sale-basket-small-pay-system-item intec-grid-item">
                <img src="<?= $this->GetFolder() ?>/images/sber.png" alt="" title="">
            </div>
        <?php } ?>
        <?php if ($arParams['QIWI_ICON_SHOW'] == 'Y') {?>
            <div class="sale-basket-small-pay-system-item intec-grid-item">
                <img src="<?= $this->GetFolder() ?>/images/qiwi.png" alt="" title="">
            </div>
        <?php } ?>
        <?php if ($arParams['YANDEX_MONEY_ICON_SHOW'] == 'Y') {?>
            <div class="sale-basket-small-pay-system-item intec-grid-item">
                <img src="<?= $this->GetFolder() ?>/images/yandex-money.png" alt="" title="">
            </div>
        <?php } ?>
        <?php if ($arParams['VISA_ICON_SHOW'] == 'Y') {?>
            <div class="sale-basket-small-pay-system-item intec-grid-item">
                <img src="<?= $this->GetFolder() ?>/images/visa.png" alt="" title="">
            </div>
        <?php } ?>
        <?php if ($arParams['MASTERCARD_ICON_SHOW'] == 'Y') {?>
            <div class="sale-basket-small-pay-system-item intec-grid-item">
                <img src="<?= $this->GetFolder() ?>/images/mastercard.png" alt="" title="">
            </div>
        <?php } ?>
    </div>
</div>