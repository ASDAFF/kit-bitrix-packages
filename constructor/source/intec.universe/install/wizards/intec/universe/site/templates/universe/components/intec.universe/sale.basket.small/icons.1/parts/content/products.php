<?php

use Bitrix\Main\Loader;
use intec\core\helpers\ArrayHelper;
use Bitrix\Main\Localization\Loc;
use intec\core\helpers\Json;
use intec\core\helpers\Html;

/**
 * @var $APPLICATION
 * @var array $arParams
 * @var array $arResult
 * @var array $arItems
 * @var bool $bModuleCatalog
 * @var bool $bModuleSale
 * @var bool $bModuleShop
 */

?>
<div class="sale-basket-small-products-wrap">
    <div class="sale-basket-small-products">
        <?php foreach ($arItems as $arItem) { ?>
        <?php
            $sPicture = $arItem['PICTURE'];

            if (!empty($sPicture)) {
                $sPicture = CFile::ResizeImageGet($sPicture, [
                    'width' => 110,
                    'height' => 110
                ], BX_RESIZE_IMAGE_PROPORTIONAL);

                if (!empty($sPicture))
                    $sPicture = $sPicture['src'];
            }

            if (empty($sPicture))
                $sPicture = SITE_TEMPLATE_PATH.'/images/picture.missing.png';
        ?>
            <div class="sale-basket-small-product" data-role="product" data-id="<?= $arItem['ID'] ?>">
                <div class="intec-grid intec-grid-nowrap intec-grid-a-v-stretch">
                    <div class="intec-grid-item-auto">
                        <div class="sale-basket-small-product-image">
                            <a href="<?= $arItem['LINK'] ?>" class="sale-basket-small-product-image-wrapper" style="background-image: url(<?= $sPicture ?>);"></a>
                            <?= Html::beginTag('div', [
                                'class' => 'sale-basket-small-product-icon-delete intec-cl-backgroud-hover',
                                'data' => [
                                    'role' => 'button',
                                    'action' => 'product.remove'
                                ],
                                'title' => Loc::getMessage('C_SALE_BASKET_SMALL_ICONS_1_BUTTONS_PRODUCT_REMOVE')
                            ]) ?>
                                <i class="glyph-icon-cancel"></i>
                            <?= Html::endTag('div') ?>
                        </div>
                    </div>
                    <div class="sale-basket-small-product-content intec-grid-item">
                        <div class="sale-basket-small-product-name-wrap">
                            <a class="sale-basket-small-product-name intec-cl-text-hover" href="<?= $arItem['LINK'] ?>">
                                <?= $arItem['NAME'] ?>
                            </a>
                        </div>
                        <div class="sale-basket-small-product-column-price">
                            <span class="sale-basket-small-product-new-price">
                                <?php if ($component->getIsBase()) { ?>
                                    <?= CCurrencyLang::CurrencyFormat(
                                        $arItem['PRICE']['DISCOUNT']['VALUE'] * $arItem['QUANTITY']['VALUE'],
                                        $arItem['PRICE']['CURRENCY'],
                                        true
                                    ) ?>
                                <?php } else { ?>
                                    <?= CStartShopCurrency::FormatAsString(
                                        $arItem['PRICE']['DISCOUNT']['VALUE'] * $arItem['QUANTITY']['VALUE'],
                                        $arItem['PRICE']['CURRENCY']
                                    ) ?>
                                <?php } ?>
                            </span>
                            <?php if ($arItem['PRICE']['DISCOUNT']['VALUE'] != $arItem['PRICE']['BASE']['VALUE']) { ?>
                            <span class="sale-basket-small-product-old-price">
                                <?php if ($component->getIsBase()) { ?>
                                    <?= CCurrencyLang::CurrencyFormat(
                                        $arItem['PRICE']['BASE']['VALUE'] * $arItem['QUANTITY']['VALUE'],
                                        $arItem['PRICE']['CURRENCY'],
                                        true
                                    ) ?>
                                <?php } else { ?>
                                    <?= CStartShopCurrency::FormatAsString(
                                        $arItem['PRICE']['BASE']['VALUE'] * $arItem['QUANTITY']['VALUE'],
                                        $arItem['PRICE']['CURRENCY']
                                    ) ?>
                                <?php } ?>
                            </span>
                            <?php } ?>
                        </div>
                        <?php if ($arItem['DELAY']) { ?>
                            <div class="sale-basket-small-product-add-basket">
                                <?= Html::beginTag('div', [
                                    'class' => 'sale-basket-small-product-button-basket intec-cl-text intec-cl-text-light-hover',
                                    'data' => [
                                        'role' => 'button',
                                        'action' => 'product.add'
                                    ]
                                ]) ?>
                                <i class="glyph-icon-cart"></i>
                                <span><?= Loc::getMessage('C_SALE_BASKET_SMALL_ICONS_1_BUTTONS_PRODUCT_ADD')?></span>
                                <?= Html::endTag('div') ?>
                            </div>
                        <?php } else { ?>
                            <?php if (!$arItem['DELAY'] && $arResult['DELAYED']['SHOW']) { ?>
                                <div class="sale-basket-small-product-add-basket">
                                    <?= Html::beginTag('div', [
                                        'class' => 'sale-basket-small-product-button-delay intec-cl-text intec-cl-text-light-hover',
                                        'data' => [
                                            'role' => 'button',
                                            'action' => 'product.delay'
                                        ]
                                    ]) ?>
                                    <i class="fas fa-heart"></i>
                                    <span><?= Loc::getMessage('C_SALE_BASKET_SMALL_ICONS_1_BUTTONS_PRODUCT_DELAY')?></span>
                                    <?= Html::endTag('div') ?>
                                </div>
                            <?php } ?>
                        <?php } ?>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>
</div>
