<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;
use intec\core\helpers\Html;

/**
 * @var array $arResult
 * @var array $arParams
 */

?>
<script id="basket-total-template" type="text/html">
    <?= Html::beginTag('div', [
        'class' => 'basket-total-container',
        'data-entity' => 'basket-checkout-aligner'
    ]) ?>
        <div class="basket-total-wrapper intec-grid intec-grid-1024-wrap">
            <?php if ($arParams['HIDE_COUPON'] !== 'Y') { ?>
                <div class="intec-grid-item-auto intec-grid-item-1024-1" data-print="false">
                    <div class="basket-coupon">
                        <?php include(__DIR__.'/total/coupon.field.php') ?>
                        <?php include(__DIR__.'/total/coupon.message.php') ?>
                    </div>
                </div>
            <?php } ?>
            <div class="intec-grid-item intec-grid-item-1024-1">
                <div class="basket-price-wrap intec-grid intec-grid-a-h-end intec-grid-768-wrap">
                    <?php include(__DIR__.'/total/total.price.php') ?>
                    <?php include(__DIR__.'/total/total.order.php') ?>
                </div>
            </div>
        </div>
	<?= Html::endTag('div') ?>
</script>