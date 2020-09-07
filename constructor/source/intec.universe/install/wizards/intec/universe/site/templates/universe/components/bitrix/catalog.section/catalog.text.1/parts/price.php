<?php if (!defined('B_PROLOG_INCLUDED') && B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;
use intec\core\helpers\ArrayHelper;
use intec\core\helpers\Html;

/**
 * @var array $arVisual
 */

?>
<?php $vPrice = function (&$arItem) use (&$arVisual) {
    $arPrice = null;

    if (!empty($arItem['ITEM_PRICES']))
        $arPrice = ArrayHelper::getFirstValue($arItem['ITEM_PRICES']);

?>
    <?= Html::beginTag('div', [
        'class' => 'catalog-section-item-price',
        'data' => [
            'role' => 'item.price',
            'show' => !empty($arPrice),
            'discount' => !empty($arPrice) && $arPrice['PERCENT'] > 0 ? 'true' : 'false'
        ]
    ]) ?>
        <?= Html::beginTag('div', [
            'class' => Html::cssClassFromArray([
                'catalog-section-item-price-wrapper' => true,
                'intec-grid' => [
                    '' => true,
                    'nowrap' => true,
                    'a-v-start' => true,
                    'a-h-end' => true,
                    'a-h-800-center' => !$arVisual['WIDE'],
                    'a-h-720-end' => !$arVisual['WIDE'],
                    'a-h-550-center' => true
                ]
            ], true)
        ]) ?>
            <?php if (!empty($arItem['OFFERS'])) { ?>
                <div class="intec-grid-item-auto">
                    <div class="catalog-section-item-price-from">
                        <?= Loc::getMessage('C_CATALOG_SECTION_CATALOG_TEXT_1_PRICE_FROM') ?>
                    </div>
                </div>
            <?php } ?>
            <div class="intec-grid-item-auto">
                <div class="catalog-section-item-price-discount" data-role="item.price.discount">
                    <?= !empty($arPrice) ? $arPrice['PRINT_PRICE'] : null ?>
                </div>
                <div class="catalog-section-item-price-base" data-role="item.price.base">
                    <?= !empty($arPrice) ? $arPrice['PRINT_BASE_PRICE'] : null ?>
                </div>
            </div>
        <?= Html::endTag('div') ?>
    <?= Html::endTag('div') ?>
<?php } ?>