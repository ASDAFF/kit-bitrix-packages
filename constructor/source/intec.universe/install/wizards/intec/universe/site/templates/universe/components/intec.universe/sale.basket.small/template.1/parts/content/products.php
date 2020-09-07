<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die() ?>
<?php

use Bitrix\Main\Localization\Loc;
use intec\core\helpers\Html;
use intec\core\helpers\Json;

/**
 * @var array $arParams
 * @var array $arResult
 * @var string $sTemplate
 * @var array $arItems
 * @var array $arVisual
 * @var IntecSaleBasketSmallComponent $component
 * @var CBitrixComponentTemplate $this
 */

?>
<div class="sale-basket-small-products">
    <table class="sale-basket-small-products-table">
        <thead>
            <tr>
                <th class="sale-basket-small-products-columns-image"></th>
                <th class="sale-basket-small-products-columns-name">
                    <?= Loc::getMessage('C_SALE_BASKET_SMALL_TEMPLATE_1_COLUMNS_NAME') ?>
                </th>
                <th class="sale-basket-small-products-columns-price">
                    <?= Loc::getMessage('C_SALE_BASKET_SMALL_TEMPLATE_1_COLUMNS_PRICE') ?>
                </th>
                <th class="sale-basket-small-products-columns-quantity">
                    <?= Loc::getMessage('C_SALE_BASKET_SMALL_TEMPLATE_1_COLUMNS_QUANTITY') ?>
                </th>
                <th class="sale-basket-small-products-columns-sum">
                    <?= Loc::getMessage('C_SALE_BASKET_SMALL_TEMPLATE_1_COLUMNS_SUM') ?>
                </th>
                <th class="sale-basket-small-products-columns-controls"></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($arItems as $arItem) { ?>
            <?php
                $sPicture = $arItem['PICTURE'];

                if (!empty($sPicture)) {
                    $sPicture = CFile::ResizeImageGet($sPicture, [
                        'width' => 65,
                        'height' => 65
                    ], BX_RESIZE_IMAGE_PROPORTIONAL);

                    if (!empty($sPicture))
                        $sPicture = $sPicture['src'];
                }

                if (empty($sPicture))
                    $sPicture = SITE_TEMPLATE_PATH.'/images/picture.missing.png';
            ?>
                <tr data-role="product" data-id="<?= $arItem['ID'] ?>">
                    <td class="sale-basket-small-products-columns-image">
                        <?= Html::tag('a', '', [
                            'href' => $arItem['LINK'],
                            'data' => [
                                'lazyload-use' => $arVisual['LAZYLOAD']['USE'] ? 'true' : 'false',
                                'original' => $arVisual['LAZYLOAD']['USE'] ? $sPicture : null
                            ],
                            'style' => [
                                'background-image' => !$arVisual['LAZYLOAD']['USE'] ? 'url(\''.$sPicture.'\')' : null
                            ]
                        ]) ?>
                    </td>
                    <td class="sale-basket-small-products-columns-name">
                        <a href="<?= $arItem['LINK'] ?>">
                            <?= $arItem['NAME'] ?>
                        </a>
                    </td>
                    <td class="sale-basket-small-products-columns-price">
                        <div class="sale-basket-small-products-columns-price-current">
                            <?= $arItem['PRICE']['DISCOUNT']['DISPLAY'] ?>
                        </div>
                        <?php if ($arItem['PRICE']['DISCOUNT']['VALUE'] != $arItem['PRICE']['BASE']['VALUE']) { ?>
                            <div class="sale-basket-small-products-columns-price-old">
                                <?= $arItem['PRICE']['BASE']['DISPLAY'] ?>
                            </div>
                        <?php } ?>
                    </td>
                    <td class="sale-basket-small-products-columns-quantity">
                        <?php if ($arItem['DELAY']) {
                            echo $arItem['QUANTITY']['VALUE'];
                        } else { ?>
                            <div data-role="counter" data-settings="<?= Html::encode(Json::htmlEncode([
                                 'bounds' => array(
                                     'minimum' => $arItem['MEASURE']['RATIO'],
                                     'maximum' => $arItem['QUANTITY']['TRACE'] && $arItem['QUANTITY']['UNLIMITED'] ? $arItem['QUANTITY']['AVAILABLE'] : false
                                 ),
                                 'step' => $arItem['MEASURE']['RATIO'],
                                 'value' => $arItem['QUANTITY']['VALUE']
                             ])) ?>">
                                <span data-type="button" data-action="decrement">-</span>
                                <input type="text" data-type="input" class="intec-input" value="<?= $arItem['QUANTITY']['VALUE'] ?>" />
                                <span data-type="button" data-action="increment">+</span>
                            </div>
                        <?php } ?>
                    </td>
                    <td class="sale-basket-small-products-columns-sum">
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
                    </td>
                    <td class="sale-basket-small-products-columns-controls">
                        <?php if (!$arItem['DELAY']) { ?>
                            <?= Html::beginTag('div', [
                                'class' => 'sale-basket-small-products-columns-control intec-cl-text-hover',
                                'data' => [
                                    'role' => 'button',
                                    'action' => 'product.delay'
                                ],
                                'title' => Loc::getMessage('C_SALE_BASKET_SMALL_TEMPLATE_1_BUTTONS_PRODUCT_DELAY')
                            ]) ?>
                                <i class="fas fa-heart"></i>
                            <?= Html::endTag('div') ?>
                        <?php } else { ?>
                            <?= Html::beginTag('div', [
                                'class' => 'sale-basket-small-products-columns-control intec-cl-text-hover',
                                'data' => [
                                    'role' => 'button',
                                    'action' => 'product.add'
                                ],
                                'title' => Loc::getMessage('C_SALE_BASKET_SMALL_TEMPLATE_1_BUTTONS_PRODUCT_ADD')
                            ]) ?>
                                <i class="glyph-icon-cart"></i>
                            <?= Html::endTag('div') ?>
                        <?php } ?>
                        <?= Html::beginTag('div', [
                            'class' => 'sale-basket-small-products-columns-control',
                            'data' => [
                                'role' => 'button',
                                'action' => 'product.remove'
                            ],
                            'title' => Loc::getMessage('C_SALE_BASKET_SMALL_TEMPLATE_1_BUTTONS_PRODUCT_REMOVE')
                        ]) ?>
                            <i class="fal fa-times"></i>
                        <?= Html::endTag('div') ?>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>