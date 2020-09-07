<?php if (!defined('B_PROLOG_INCLUDED') && B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;
use intec\core\helpers\ArrayHelper;

/**
 * @var array $arVisual
 */

?>
<?php $vPrice = function (&$arItem) use (&$arVisual) {
    $arPrice = null;

    if (!empty($arItem['ITEM_PRICES']))
        $arPrice = ArrayHelper::getFirstValue($arItem['ITEM_PRICES']);
?>
    <div class="catalog-section-item-price-wrapper intec-grid intec-grid-nowrap intec-grid-a-v-center">
        <div class="intec-grid-item">
            <div class="catalog-section-item-price-discount">
                <?php if (!$arVisual['OFFERS']['USE'] && !empty($arItem['OFFERS'])) { ?>
                    <?= Loc::getMessage('C_CATALOG_SECTION_CATALOG_TILE_1_PRICE_FORM') ?>
                <?php } ?>
                <span data-role="item.price.discount">
                    <?= !empty($arPrice) ? $arPrice['PRINT_PRICE'] : null ?>
                </span>
            </div>
            <div class="catalog-section-item-price-base">
                <?php if (!$arVisual['OFFERS']['USE'] && !empty($arItem['OFFERS'])) { ?>
                    <?= Loc::getMessage('C_CATALOG_SECTION_CATALOG_TILE_1_PRICE_FORM') ?>
                <?php } ?>
                <span data-role="item.price.base">
                    <?= !empty($arPrice) ? $arPrice['PRINT_BASE_PRICE'] : null ?>
                </span>
            </div>
        </div>
    </div>
<?php } ?>