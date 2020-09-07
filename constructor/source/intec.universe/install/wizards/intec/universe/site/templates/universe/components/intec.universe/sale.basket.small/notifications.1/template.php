<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die() ?>
<?php

use Bitrix\Main\Localization\Loc;
use intec\core\bitrix\Component;
use intec\core\helpers\Html;
use intec\core\helpers\JavaScript;

/**
 * @var $APPLICATION
 * @var array $arParams
 * @var array $arResult
 * @var IntecSaleBasketSmallComponent $component
 * @var CBitrixComponentTemplate $this
 */

if (!defined('EDITOR')) {
    if (empty($arResult['CURRENCY']))
        return;

    if (!$component->getIsBase() && !$component->getIsLite())
        return;
}

$this->setFrameMode(true);
$sTemplateId = Html::getUniqueId(null, Component::getUniqueId($this, true));

$sBasketLink = $arResult['URL']['BASKET'];
if (!empty($sBasketLink)) {
    $sTag = 'a';
} else {
    $sTag = 'div';
}

?>
<?php if (!defined('EDITOR')) { ?>
    <?php if (empty($arParams['ID'])) { ?>
    <div id="<?= $sTemplateId ?>" class="ns-intec-universe c-sale-basket-small c-sale-basket-small-notifications-1">
        <div class="sale-basket-small-content">
            <div class="sale-basket-small-products" data-role="container">
                <?php include(__DIR__.'/parts/script.php') ?>
            </div>
        </div>
    </div>
    <?php } else { ?>
        <?php $arItems = &$arResult['BASKET']['ITEMS'] ?>
        <?php if (!empty($arItems)) { ?>
            <?php foreach ($arItems as $arItem) { ?>
            <?php
                if ($arItem['ID'] != $arParams['ID'])
                    continue;

                $sPicture = $arItem['PICTURE'];

                if (!empty($sPicture)) {
                    $sPicture = CFile::ResizeImageGet($sPicture, [
                        'width' => 75,
                        'height' => 75
                    ], BX_RESIZE_IMAGE_PROPORTIONAL);

                    if (!empty($sPicture))
                        $sPicture = $sPicture['src'];
                }

                if (empty($sPicture))
                    $sPicture = SITE_TEMPLATE_PATH.'/images/picture.missing.png';
            ?>
                <div class="sale-basket-small-product" data-product-id="<?= $arItem['ID'] ?>">
                    <div class="sale-basket-small-product-wrapper intec-grid intec-grid-a-v-center">
                        <?= Html::beginTag($sTag, [
                            'class' => "intec-grid-item-auto sale-basket-small-product-image-wrap",
                            'href' => !empty($sBasketLink) ? $sBasketLink : null
                        ]) ?>
                            <span class="sale-basket-small-product-image" style="background-image: url(<?= $sPicture ?>);"></span>
                        <?= Html::endTag($sTag)?>
                        <div class="intec-grid-item">
                            <span class="sale-basket-small-product-added-text">
                                <?= Loc::getMessage('C_SALE_BASKET_SMALL_NOTIFICATIONS_1_PRODUCT_ADDED') ?>
                            </span>
                            <?= Html::Tag($sTag, $arItem['NAME'], [
                                'class' => "sale-basket-small-product-name",
                                'href' => !empty($sBasketLink) ? $sBasketLink : null
                            ]) ?>
                        </div>
                    </div>
                    <span class="sale-basket-small-product-btn-close intec-cl-background intec-cl-background-light-hover" data-role="close">
                        <i class="fal fa-times"></i>
                    </span>
                </div>
            <?php } ?>
        <?php } ?>
    <?php } ?>
<?php } else { ?>
    <div class="constructor-element-stub">
        <div class="constructor-element-stub-wrapper">
            <?= Loc::getMessage('C_SALE_BASKET_SMALL_NOTIFICATIONS_1_EDITOR') ?>
        </div>
    </div>
<?php } ?>