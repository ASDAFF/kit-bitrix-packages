<?php if (!defined('B_PROLOG_INCLUDED') && B_PROLOG_INCLUDED !== true) die();

use intec\core\helpers\Html;

/**
 * @var array $arVisual
 */

/**
 * @param array $arPrice
 */
$vPrice = function (&$arPrice) use (&$arVisual) { ?>
    <?= Html::beginTag('div', [
        'class' => 'widget-item-price',
        'data' => [
            'role' => 'item.price',
            'show' => !empty($arPrice),
            'discount' => !empty($arPrice) && $arPrice['PERCENT'] > 0 ? 'true' : 'false',
            //'align' => $arVisual['PRICE']['ALIGN']
        ]
    ]) ?>
        <div class="widget-item-price-wrapper intec-grid intec-grid-wrap intec-grid-i-5 intec-grid-a-v-center intec-grid-a-h-<?= $arVisual['PRICE']['ALIGN'] ?>">
            <div class="intec-grid-item-auto">
                <div class="widget-item-price-discount" data-role="item.price.discount">
                    <?= !empty($arPrice) ? $arPrice['PRINT_PRICE'] : null ?>
                </div>
            </div>
            <div class="intec-grid-item-auto">
                <div class="widget-item-price-base" data-role="item.price.base">
                    <?= !empty($arPrice) ? $arPrice['PRINT_BASE_PRICE'] : null ?>
                </div>
            </div>
        </div>
    <?= Html::endTag('div') ?>
<?php };