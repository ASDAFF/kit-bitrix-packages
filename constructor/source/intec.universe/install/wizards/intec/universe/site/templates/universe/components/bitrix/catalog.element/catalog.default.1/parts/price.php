<?php if (defined("B_PROLOG_INCLUDED") && B_PROLOG_INCLUDED === true) ?>
<?php

use Bitrix\Main\Localization\Loc;
use intec\core\helpers\Html;

/**
 * @var array $arParams
 * @var array $arResult
 */

?>
<?php $vPrice = function (&$arItem, $bOffer = false) { ?>
    <?php $iBasePrice = $arItem['MIN_PRICE']['PRICE_ID']; ?>
    <?php if (empty($arItem['PRICES'])) return; ?>
    <?php foreach ($arItem['PRICES'] as $arPrice) { ?>
        <?php if ($arPrice['PRICE_ID'] == $iBasePrice) continue ?>
        <?= Html::beginTag('div', [
            'class' => 'catalog-element-price',
            'data' => [
                'offer' => $bOffer ? $arItem['ID'] : 'false'
            ]
        ]) ?>
            <div class="catalog-element-price-wrapper">
                <?= !empty($arPrice['TITLE']) ? $arPrice['TITLE'] : $arPrice['CODE'] ?>: <?= !empty($arPrice) ? $arPrice['PRINT_DISCOUNT_VALUE'] : null ?>
            </div>
        <?= Html::endTag('div') ?>
    <?php } ?>
<? }; ?>

<?= Html::beginTag('div', [
    'class' => [
        'catalog-element-price',
        'intec-grid' => [
            '',
            'wrap',
            'i-5',
            'a-v-center',
            'a-h-start'
        ]
    ],
    'data' => [
        'role' => 'price',
        'show' => !empty($arPrice) ? 'true' : 'false',
        'discount' => !empty($arPrice) && $arPrice['PERCENT'] > 0 ? 'true' : 'false'
    ]
]) ?>
    <div class="catalog-element-price-discount intec-grid-item-auto" data-role="price.discount">
        <?= !empty($arPrice) ? $arPrice['PRINT_PRICE'] : null ?>
    </div>
    <div class="catalog-element-price-base intec-grid-item-auto" data-role="price.base">
        <?= !empty($arPrice) ? $arPrice['PRINT_BASE_PRICE'] : null ?>
    </div>
    <?php if ($arVisual['PRICE']['DIFFERENCE']) { ?>
        <div class="catalog-element-price-difference intec-grid-item-1">
            <span>
                <?= Loc::getMessage('C_CATALOG_ELEMENT_CATALOG_DEFAULT_1_PRICE_DIFFERENCE');?>
            </span>
            <span data-role="price.difference">
                <?= !empty($arPrice) ? $arPrice['PRINT_DISCOUNT'] : null ?>
            </span>
        </div>
    <?php } ?>
<?= Html::endTag('div') ?>

<?php if ($arVisual['PRICE']['EXTENDED']) {
    $vPrice($arResult);

    if (!empty($arResult['OFFERS']))
        foreach ($arResult['OFFERS'] as &$arOffer) {
            $vPrice($arOffer, true);

            unset($arOffer);
        }

    unset($vPrice);
} ?>