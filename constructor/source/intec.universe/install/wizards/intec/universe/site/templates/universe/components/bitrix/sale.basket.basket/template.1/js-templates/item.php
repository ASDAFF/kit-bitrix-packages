<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use intec\core\helpers\ArrayHelper;
use intec\core\helpers\Html;

/**
 * @var array $arParams
 */

$bPicture = ArrayHelper::isIn('PREVIEW_PICTURE', $arParams['COLUMNS_LIST']);
$bPriceApart = ArrayHelper::isIn('PRICE', $arParams['COLUMNS_LIST']) && $arParams['PRICE_DISPLAY_MODE'] === 'Y';
$bTotal = ArrayHelper::isIn('SUM', $arParams['COLUMNS_LIST']);
$bAction = ArrayHelper::isIn('DELETE', $arParams['COLUMNS_LIST']);

?>
<script id="basket-item-template" type="text/html">
    <?= Html::beginTag('div', [
        'id' => 'basket-item-{{ID}}',
        'class' => 'basket-item',
        'data' => [
            'entity' => 'basket-item',
            'id' => '{{ID}}'
        ]
    ]) ?>
		{{#SHOW_RESTORE}}
			<?php include(__DIR__.'/item/restore.php') ?>
		{{/SHOW_RESTORE}}
		{{^SHOW_RESTORE}}
            <?= Html::beginTag('div', [
                'class' => [
                    'basket-item-wrapper',
                    'intec-grid' => [
                        '',
                        'a-v-stretch',
                        '650-wrap'
                    ]
                ]
            ]) ?>
                <div class="intec-grid-item intec-grid-item-650-1">
                    <div class="basket-item-content">
                        <div class="intec-grid intec-grid-i-10 intec-grid-650-wrap">
                            <div class="intec-grid-item intec-grid-item-800-2 intec-grid-item-650-1">
                                <div class="basket-item-main">
                                    <div class="intec-grid intec-grid-800-wrap">
                                        <?php if ($bPicture) {
                                            include(__DIR__.'/item/image.php');
                                        } ?>
                                        <div class="basket-item-info-wrap intec-grid-item intec-grid-item-800-1">
                                            <div class="basket-item-info">
                                                <?php include(__DIR__.'/item/name.php') ?>
                                                <?php include(__DIR__.'/item/alert.unavailable.php') ?>
                                                <?php include(__DIR__.'/item/alert.delayed.php') ?>
                                                <?php include(__DIR__.'/item/alert.warnings.php') ?>
                                                <?php if (!empty($arParams['PRODUCT_BLOCKS_ORDER'])) { ?>
                                                    <div class="basket-item-properties">
                                                        <?php foreach ($arParams['PRODUCT_BLOCKS_ORDER'] as $sBlock) {
                                                            if ($sBlock === 'sku')
                                                                include(__DIR__.'/item/properties.offers.php');
                                                            else if ($sBlock === 'props')
                                                                include(__DIR__.'/item/properties.basket.php');
                                                            else if ($sBlock === 'columns')
                                                                include(__DIR__.'/item/properties.product.php');
                                                        } ?>
                                                        <?php unset($sBlock) ?>
                                                    </div>
                                                <?php } ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?= Html::beginTag('div', [
                                'class' => [
                                    'intec-grid-item' => [
                                        '',
                                        '1200-4',
                                        '1000-3',
                                        '800-2',
                                        '650-1'
                                    ]
                                ]
                            ]) ?>
                                <div class="basket-item-additional">
                                    <?= Html::beginTag('div', [
                                        'class' => [
                                            'intec-grid' => [
                                                '',
                                                'nowrap',
                                                'a-h-end',
                                                'a-v-center',
                                                'i-10',
                                                '1200-wrap'
                                            ]
                                        ]
                                    ]) ?>
                                        <?php if ($bPriceApart) {
                                            include(__DIR__.'/item/price.apart.php');
                                        } ?>
                                        <?= Html::beginTag('div', [
                                            'class' => [
                                                'basket-item-quantity-wrap',
                                                'intec-grid-item' => [
                                                    'auto',
                                                    '1200-1',
                                                ]
                                            ]
                                        ]) ?>
                                            <div class="basket-item-quantity-wrapper">
                                                <?php include(__DIR__.'/item/counter.php') ?>
                                                <?php if (!$bPriceApart) {
                                                    include(__DIR__.'/item/price.along.php');
                                                } ?>
                                            </div>
                                        <?= Html::endTag('div') ?>
                                        <?php if ($bTotal) {
                                            include(__DIR__.'/item/price.total.php');
                                        } ?>
                                    <?= Html::endTag('div') ?>
                                </div>
                            <?= Html::endTag('div') ?>
                        </div>
                    </div>
                </div>
                <?php if ($bAction) {
                    include(__DIR__.'/item/actions.php');
                } ?>
            <?= Html::endTag('div') ?>
		{{/SHOW_RESTORE}}
	<?= Html::endTag('div') ?>
</script>
<?php unset($bPicture, $bPriceApart, $bTotal, $bAction) ?>