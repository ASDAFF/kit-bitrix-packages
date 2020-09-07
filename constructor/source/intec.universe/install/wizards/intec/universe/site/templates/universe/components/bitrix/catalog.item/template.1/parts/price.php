<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use intec\core\helpers\ArrayHelper;
use intec\core\helpers\Type;

/**
 * @var array $arParams
 * @var array $item
 */

$arPrice = ArrayHelper::getFirstValue($item['ITEM_PRICES']);

?>
<?php if (!empty($arPrice) && Type::isArray($arPrice)) { ?>
    <div class="catalog-item-price-container">
        <div class="catalog-item-price-current">
            <?= $arPrice['PRINT_RATIO_PRICE'] ?>
        </div>
        <?php if ($arParams['SHOW_OLD_PRICE'] === 'Y' && $arPrice['DISCOUNT'] > 0) { ?>
            <div class="catalog-item-price-discount">
                <?= $arPrice['PRINT_RATIO_BASE_PRICE'] ?>
            </div>
        <?php } ?>
    </div>
<?php } ?>
<?php unset($arPrice) ?>
