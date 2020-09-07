<?php if (!defined('B_PROLOG_INCLUDED') && B_PROLOG_INCLUDED !== true) die();

/**
 * @var array $arResult
 * @var array $arVisual
 */

?>
<div class="catalog-element-block">
    <div class="catalog-element-purchase-panel">
        <?php if ($arVisual['COUNTER']['SHOW']) {
            include(__DIR__.'/counter.php');
        } ?>
        <div class="catalog-element-purchase-panel-block">
            <div class="intec-grid intec-grid-wrap intec-grid-i-8 intec-grid-a-v-center">
                <?php if ($arResult['ORDER_FAST']['USE']) {
                    include(__DIR__.'/purchase/order.fast.php');
                } ?>
                <?php include(__DIR__.'/purchase/purchase.php') ?>
                <?php if ($arResult['DELAY']['USE'] && $arResult['COMPARE']['USE']) {
                    include(__DIR__.'/purchase/buttons.php');
                } ?>
            </div>
        </div>
    </div>
</div>