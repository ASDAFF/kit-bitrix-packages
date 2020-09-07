<?php if (!defined('B_PROLOG_INCLUDED') && B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;
use intec\core\helpers\Html;

/**
 * @var array $arPrice
 */

?>
<?= Html::beginTag('div', [
    'class' => 'catalog-element-panel-price-content',
    'data' => [
        'role' => 'price',
        'show' => !empty($arPrice) ? 'true' : 'false',
        'discount' => !empty($arPrice) && $arPrice['PERCENT'] > 0 ? 'true' : 'false'
    ]
]) ?>
    <div class="catalog-element-panel-price-discount" data-role="price.discount">
        <?= !empty($arPrice) ? $arPrice['PRINT_PRICE'] : null ?>
    </div>
    <div class="catalog-element-panel-price-percent-wrap">
        <div class="catalog-element-panel-price-percent" data-role="price.percent">
            <?= !empty($arPrice) ? '-'.$arPrice['PERCENT'].'%' : null ?>
        </div>
        <div class="catalog-element-panel-price-base" data-role="price.base">
            <?= !empty($arPrice) ? $arPrice['PRINT_PRICE'] : null ?>
        </div>
        <?php if ($arVisual['PRICE']['DIFFERENCE']) { ?>
            <div class="catalog-element-panel-price-difference">
                <span>
                    <?= Loc::getMessage('C_CATALOG_ELEMENT_CATALOG_DEFAULT_2_PRICE_DIFFERENCE');?>
                </span>
                    <span data-role="price.difference">
                    <?= !empty($arPrice) ? $arPrice['PRINT_DISCOUNT_DIFF'] : null ?>
                </span>
            </div>
        <?php } ?>
    </div>
<?= Html::endTag('div') ?>