<?php if (!defined('B_PROLOG_INCLUDED') && B_PROLOG_INCLUDED !== true) die();

use intec\core\helpers\Html;

/**
 * @var array $arVisual
 */

?>
<?php $vPrice = function (&$arPrice) use (&$arVisual) { ?>
    <?= Html::beginTag('div', [
        'class' => 'catalog-section-item-price',
        'data' => [
            'role' => 'item.price',
            'show' => !empty($arPrice),
            'discount' => !empty($arPrice) && $arPrice['PERCENT'] > 0 ? 'true' : 'false'
        ]
    ]) ?>
        <?= Html::beginTag('div', [
            'class' => [
                'catalog-section-item-price-wrapper',
                'intec-grid' => [
                    '',
                    'wrap',
                    'a-v-center',
                    'a-h-'.$arVisual['PRICE']['ALIGN'],
                    'i-5'
                ]
            ]
        ]) ?>
            <div class="intec-grid-item-auto">
                <div class="catalog-section-item-price-discount" data-role="item.price.discount">
                    <?= !empty($arPrice) ? $arPrice['PRINT_PRICE'] : null ?>
                </div>
            </div>
            <div class="intec-grid-item-auto">
                <div class="catalog-section-item-price-base" data-role="item.price.base">
                    <?= !empty($arPrice) ? $arPrice['PRINT_BASE_PRICE'] : null ?>
                </div>
            </div>
        <?= Html::endTag('div') ?>
    <?= Html::endTag('div') ?>
<?php } ?>