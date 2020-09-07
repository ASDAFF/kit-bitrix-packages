<?php if (!defined('B_PROLOG_INCLUDED') && B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;
use intec\core\helpers\Html;

/**
 * @var array $arVisual
 */

?>
<?php $vPrice = function (&$arPrice, $bOffer = false) use (&$arVisual) { ?>
    <?php if (!$bOffer) { ?>
        <?= Html::beginTag('div', [
            'class' => [
                'catalog-element-item-price-wrapper'
            ],
            'data' => [
                'role' => 'item.price',
                'show' => !empty($arPrice),
                'discount' => !empty($arPrice) && $arPrice['PERCENT'] > 0 ? 'true' : 'false',
                'align' => $arVisual['PRICE']['ALIGN']
            ]
        ]) ?>
            <div class="catalog-section-item-price-discount">
                <span data-role="item.price.discount">
                    <?= !empty($arPrice) ? $arPrice['PRINT_PRICE'] : null ?>
                </span>
            </div>
            <div class="catalog-section-item-price-base" data-role="item.price.base">
                <?= !empty($arPrice) ? $arPrice['PRINT_BASE_PRICE'] : null ?>
            </div>
        <?= Html::endTag('div') ?>
    <?php } else { ?>
        <div class="catalog-element-item-price-wrapper" data-align="<?= $arVisual['PRICE']['ALIGN'] ?>">
            <div class="catalog-section-item-price-discount">
                <span>
                    <?= Loc::getMessage('C_CATALOG_SECTION_PRODUCTS_SMALL_2_PRICE_FROM') ?>
                </span>
                <span>
                    <?= $arPrice['PRINT_DISCOUNT_VALUE'] ?>
                </span>
            </div>
            <?php if (!empty($arPrice['PRINT_VALUE'])) { ?>
                <div class="catalog-section-item-price-base">
                    <?= $arPrice['PRINT_VALUE'] ?>
                </div>
            <?php } ?>
        </div>
    <?php } ?>
<?php } ?>