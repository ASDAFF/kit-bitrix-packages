<?php if (!defined('B_PROLOG_INCLUDED') && B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;
use intec\core\helpers\ArrayHelper;
use intec\core\helpers\Html;

/**
 * @var array $arResult
 * @var array $arVisual
 */

$bPrice = false;
$arPrice = null;

if (!empty($arResult['ITEM_PRICES'])) {
    $bPrice = true;
    $arPrice = ArrayHelper::getFirstValue($arResult['ITEM_PRICES']);
}

?>
<div class="catalog-element-price-order intec-grid-item intec-grid-item-1024-1">
    <?= Html::beginTag('div', [
        'class' => [
            'catalog-element-price',
            'catalog-element-block'
        ],
        'data' => [
            'role' => 'price',
            'show' => $bPrice ? 'true' : 'false',
            'measure' => !empty($arResult['CATALOG_MEASURE_NAME']),
            'discount' => !empty($arPrice) && $arPrice['DISCOUNT_DIFF_PERCENT'] > 0 ? 'true' : 'false',
            'extended' => 'false'
        ]
    ]) ?>
        <?= Html::tag('div', Loc::getMessage('C_CATALOG_ELEMENT_DEFAULT_3_TEMPLATE_PRICE_MEASURE', [
            '#MEASURE#' => !empty($arResult['CATALOG_MEASURE_NAME']) ? $arResult['CATALOG_MEASURE_NAME'] : ''
        ]), [
            'class' => 'catalog-element-price-measure'
        ]) ?>
        <div class="catalog-element-price-values">
            <div class="intec-grid intec-grid-wrap intec-grid-a-v-center intec-grid-i-h-12 intec-grid-i-v-6">
                <div class="intec-grid-item-auto intec-grid-item-500-1">
                    <?= Html::tag('div', $bPrice ? $arPrice['PRINT_PRICE'] : null, [
                        'class' => [
                            'catalog-element-price-current',
                            'intec-cl-border-hover'
                        ],
                        'data-role' => 'price.discount'
                    ]) ?>
                    <?php if ($arVisual['PRICE']['EXTENDED']) {
                        include(__DIR__.'/price.extended.php');
                    } ?>
                </div>
                <div class="catalog-element-price-discount-wrap intec-grid-item-auto intec-grid-item-500-1">
                    <div class="catalog-element-price-discount">
                        <?= Html::tag('div', $bPrice ? $arPrice['PRINT_DISCOUNT_VALUE'] : null, [
                            'class' => 'catalog-element-price-discount-value',
                            'data-role' => 'price.base'
                        ]) ?>
                        <?= Html::tag('div', $bPrice ? '- '.$arPrice['PERCENT'].'%': null, [
                            'class' => 'catalog-element-price-discount-percent',
                            'data-role' => 'price.percent'
                        ]) ?>
                    </div>
                </div>
            </div>
        </div>
    <?= Html::endTag('div') ?>
</div>
<?php unset($bPrice, $arPrice) ?>