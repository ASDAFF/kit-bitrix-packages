<?php if (!defined('B_PROLOG_INCLUDED') && B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;
use intec\core\helpers\Html;

/**
 * @var array $arVisual
 */

?>
<?php $vPrice = function (&$arPrice) use (&$arVisual, &$arItem) { ?>
    <?= Html::beginTag('div', [
        'class' => 'catalog-section-item-price',
        'data' => [
            'role' => 'item.price',
            'show' => !empty($arPrice),
            'discount' => !empty($arPrice) && $arPrice['PERCENT'] > 0 ? 'true' : 'false',
            'align' => $arVisual['PRICE']['ALIGN']
        ]
    ]) ?>
        <div class="catalog-section-item-price-discount">
            <?php if (!$arVisual['OFFERS']['USE'] && !empty($arItem['OFFERS'])) { ?>
                <span>
                    <?= Loc::getMessage('C_CATALOG_SECTION_CATALOG_TILE_3_PRICE_FROM') ?>
                </span>
            <?php } ?>
            <span data-role="item.price.discount">
                <?= !empty($arPrice) ? $arPrice['PRINT_PRICE'] : null ?>
            </span>
        </div>
        <div class="catalog-section-item-price-base" data-role="item.price.base">
            <?= !empty($arPrice) ? $arPrice['PRINT_BASE_PRICE'] : null ?>
        </div>
    <?= Html::endTag('div') ?>
<?php } ?>