<?php if (!defined('B_PROLOG_INCLUDED') && B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;
use intec\core\helpers\Html;

/**
 * @var array $arResult
 * @var array $arVisual
 * @var array $arPrice
 */

?>
<?php $vPrice = function (&$arItem, $bOffer = false) use ($arVisual) {

    $iBasePrice = $arItem['MIN_PRICE']['PRICE_ID'];

    if (empty($arItem['PRICES']))
        return;

?>
    <?php foreach ($arItem['PRICES'] as $arPrice) {

        if ($arPrice['PRICE_ID'] == $iBasePrice)
            continue;

    ?>
        <?= Html::beginTag('div', [
            'class' => 'catalog-element-price intec-grid-item intec-grid-item-2 intec-grid-item-1150-1',
            'data' => [
                'show' => !empty($arPrice) ? 'true' : 'false',
                'discount' => !empty($arPrice) && $arPrice['DISCOUNT_DIFF_PERCENT'] > 0 ? 'true' : 'false',
                'extended' => 'true',
                'offer' => $bOffer ? $arItem['ID'] : 'false'
            ]
        ]) ?>
            <div class="catalog-element-price-title">
                <?= !empty($arPrice['TITLE']) ? $arPrice['TITLE'] : $arPrice['CODE'] ?>
            </div>
            <div class="catalog-element-price-discount">
                <?= !empty($arPrice) ? $arPrice['PRINT_DISCOUNT_VALUE'] : null ?>
            </div>
            <div class="catalog-element-price-base intec-grid intec-grid-wrap intec-grid-a-v-center intec-grid-i-7">
                <div class="catalog-element-price-percent intec-grid-item-auto">
                    <div class="catalog-element-price-percent-wrapper">
                        <?= !empty($arPrice) ? '-'.$arPrice['DISCOUNT_DIFF_PERCENT'].'%' : null ?>
                    </div>
                </div>
                <div class="catalog-element-price-value intec-grid-item-auto">
                    <?= !empty($arPrice) ? $arPrice['PRINT_VALUE'] : null ?>
                </div>
            </div>
            <?php if ($arVisual['PRICE']['DIFFERENCE']) { ?>
                <div class="catalog-element-price-difference">
                    <span>
                        <?= Loc::getMessage('C_CATALOG_ELEMENT_CATALOG_DEFAULT_2_PRICE_DIFFERENCE');?>
                    </span>
                    <span>
                        <?= !empty($arPrice) ? $arPrice['PRINT_DISCOUNT_DIFF'] : null ?>
                    </span>
                </div>
            <?php } ?>
        <?= Html::endTag('div') ?>
    <?php } ?>
<? }; ?>

<div class="intec-grid intec-grid-wrap intec-grid-i-h-10">
    <?= Html::beginTag('div', [
        'class' => 'catalog-element-price intec-grid-item intec-grid-item-2 intec-grid-item-1150-1',
        'data' => [
            'role' => 'price',
            'show' => !empty($arPrice) ? 'true' : 'false',
            'discount' => !empty($arPrice) && $arPrice['PERCENT'] > 0 ? 'true' : 'false',
            'extended' => 'false'
        ]
    ]) ?>
        <div class="catalog-element-price-title" data-role="price.title">
            <?php if (!empty($arPrice)) echo !empty($arPrice['TITLE']) ? $arPrice['TITLE'] : $arPrice['CODE'] ?>
        </div>
        <div class="catalog-element-price-discount" data-role="price.discount">
            <?= !empty($arPrice) ? $arPrice['PRINT_PRICE'] : null ?>
        </div>
        <div class="catalog-element-price-base intec-grid intec-grid-wrap intec-grid-a-v-center intec-grid-i-7">
            <div class="catalog-element-price-percent intec-grid-item-auto">
                <div class="catalog-element-price-percent-wrapper" data-role="price.percent">
                    <?= !empty($arPrice) ? '-'.$arPrice['PERCENT'].'%' : null ?>
                </div>
            </div>
            <div class="catalog-element-price-value intec-grid-item-auto" data-role="price.base">
                <?= !empty($arPrice) ? $arPrice['PRINT_BASE_PRICE'] : null ?>
            </div>
        </div>
        <?php if ($arVisual['PRICE']['DIFFERENCE']) { ?>
            <div class="catalog-element-price-difference">
                <span>
                    <?= Loc::getMessage('C_CATALOG_ELEMENT_CATALOG_DEFAULT_2_PRICE_DIFFERENCE');?>
                </span>
                <span  data-role="price.difference">
                    <?= !empty($arPrice) ? $arPrice['PRINT_DISCOUNT'] : null ?>
                </span>
            </div>
        <?php } ?>
    <?= Html::endTag('div') ?>
    <?php $vPrice($arResult);

        if ($arVisual['PRICE']['EXTENDED']) {
            if (!empty($arResult['OFFERS']))
                foreach ($arResult['OFFERS'] as &$arOffer) {
                    $vPrice($arOffer, true);

                    unset($arOffer);
                }

            unset($vPrice);
        }
    ?>
</div>