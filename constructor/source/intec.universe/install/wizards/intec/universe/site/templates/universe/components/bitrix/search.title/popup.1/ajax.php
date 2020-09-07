<?php use intec\core\helpers\Html;

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die() ?>
<?php

if (empty($arResult['CATEGORIES']))
    return;

?>
<div class="ns-bitrix c-search-title c-search-title-popup-1 search-title-results intec-content-wrap">
    <div class="search-title-items">
        <?php foreach ($arResult['CATEGORIES'] as $sKey => $arCategory) { ?>
            <?php foreach ($arCategory['ITEMS'] as $arItem) { ?>
                <?php if (!empty($arItem['ITEM_ID'])) { ?>
                <?php
                    $sName = $arItem['NAME'];
                    $sLink = $arItem['URL'];
                    $sImage = null;
                    $arPrices = null;

                    $arSection = $arItem['SECTION'];
                    $arItem = $arItem['ITEM'];

                    if (!empty($arItem)) {
                        if (!empty($arItem['PREVIEW_PICTURE'])) {
                            $sImage = $arItem['PREVIEW_PICTURE'];
                        } else if (!empty($arItem['DETAIL_PICTURE'])) {
                            $sImage = $arItem['DETAIL_PICTURE'];
                        }

                        if (!empty($sImage)) {
                            $sImage = CFile::ResizeImageGet($sImage['ID'], [
                                'width' => 80,
                                'height' => 80
                            ], BX_RESIZE_IMAGE_PROPORTIONAL_ALT);

                            if (!empty($sImage)) {
                                $sImage = $sImage['src'];
                        }

                        if (empty($sImage))
                            $sImage = null;

                        $arPrices = $arItem['PRICES'];
                    }
                }
                ?>
                    <div class="search-title-item search-title-item-hover">
                        <div class="search-title-item-wrapper intec-content">
                            <div class="search-title-item-wrapper-2 intec-content-wrapper">
                                <div class="search-title-item-wrapper-3 intec-grid intec-grid-nowrap intec-grid-i-h-15 intec-grid-a-v-center">
                                    <?php if (!empty($sImage)) { ?>
                                        <div class="search-title-item-image-wrap intec-grid-item-auto">
                                            <a href="<?= $sLink ?>" class="search-title-item-image intec-image">
                                                <div class="intec-aligner"></div>
                                                <img loading="lazy" alt="<?= Html::encode($sName) ?>" src="<?= $sImage ?>" />
                                            </a>
                                        </div>
                                    <?php } ?>
                                    <div class="search-title-item-content-wrap intec-grid-item intec-grid-item-shrink-1">
                                        <div class="intec-grid intec-grid-nowrap intec-grid-600-wrap intec-grid-i-h-15 intec-grid-a-v-center">
                                            <div class="intec-grid-item intec-grid-item-600-1 intec-grid-item-shrink-1">
                                                <div class="search-title-item-content">
                                                    <a href="<?= $sLink ?>" class="search-title-item-name intec-cl-text-hover">
                                                        <?= $sName ?>
                                                    </a>
                                                    <?php if (!empty($arSection)) { ?>
                                                        <a href="<?= $arSection['SECTION_PAGE_URL'] ?>" class="search-title-item-section intec-cl-text-hover">
                                                            <?= $arSection['NAME'] ?>
                                                        </a>
                                                    <?php } ?>
                                                </div>
                                            </div>
                                            <?php if (!empty($arPrices)) { ?>
                                                <div class="search-title-item-prices-wrap intec-grid-item-600-1 intec-grid-item-auto">
                                                    <div class="search-title-item-prices">
                                                        <?php foreach ($arPrices as $arPrice) { ?>
                                                            <div class="search-title-item-price">
                                                                <div class="search-title-item-price-current">
                                                                    <?= $arPrice['PRINT_VALUE'] ?>
                                                                </div>
                                                                <?php if (!empty($arPrice['DISCOUNT_DIFF'])) { ?>
                                                                    <div class="search-title-item-price-discount">
                                                                        <?= $arPrice['PRINT_DISCOUNT_VALUE'] ?>
                                                                    </div>
                                                                <?php } ?>
                                                            </div>
                                                        <?php } ?>
                                                    </div>
                                                </div>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php } else if ($sKey === 'all') { ?>
                    <div class="search-title-item">
                        <div class="search-title-item-wrapper intec-content">
                            <div class="search-title-item-wrapper-2 intec-content-wrapper">
                                <div class="search-title-item-wrapper-3 intec-grid intec-grid-nowrap intec-grid-i-h-15 intec-grid-a-v-center">
                                    <div class="search-title-item-content-wrap intec-grid-item-auto intec-grid-item-shrink-1">
                                        <div class="search-title-item-content">
                                            <a href="<?= $arItem['URL'] ?>" class="search-title-item-button">
                                                <?= $arItem['NAME'] ?>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php } else { ?>
                    <div class="search-title-item">
                        <div class="search-title-item-wrapper intec-content">
                            <div class="search-title-item-wrapper-2 intec-content-wrapper">
                                <div class="search-title-item-wrapper-3 intec-grid intec-grid-nowrap intec-grid-i-h-15 intec-grid-a-v-center">
                                    <div class="search-title-item-content-wrap intec-grid-item intec-grid-item-shrink-1">
                                        <div class="search-title-item-content">
                                            <a href="<?= $arItem['URL'] ?>" class="search-title-item-name">
                                                <?= $arItem['NAME'] ?>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            <?php } ?>
        <?php } ?>
    </div>
</div>