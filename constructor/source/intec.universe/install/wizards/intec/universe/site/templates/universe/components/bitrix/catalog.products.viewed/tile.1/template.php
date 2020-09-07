<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die() ?>
<?php

use intec\core\bitrix\Component;
use intec\core\helpers\Html;
use intec\core\helpers\JavaScript;
use intec\core\helpers\ArrayHelper;

/**
 * @var array $arParams
 * @var array $arResult
 */

if (empty($arResult['ITEMS']))
    return

$this->setFrameMode(true);
$sTemplateId = Html::getUniqueId(null, Component::getUniqueId($this));

$arVisual = $arResult['VISUAL'];
?>
<?= Html::beginTag('div', [
    'id' => $sTemplateId,
    'class' => [
        'ns-bitrix',
        'c-catalog-products-viewed',
        'c-catalog-products-viewed-tile-1'
    ],
    'data' => [
        'collapsed' => $arVisual['COLUMNS'] > 5 ? 'true' : 'false'
    ]
]) ?>
    <div class="catalog-products-viewed-wrapper">
        <div class="intec-content intec-content-visible catalog-products-viewed-wrapper-2">
            <div class="intec-content-wrapper catalog-products-viewed-wrapper-3">
                <div class="catalog-products-viewed catalog-products-viewed-wrapper-4">
                    <?php if ($arVisual['TITLE']['SHOW'] && !empty($arVisual['TITLE']['VALUE'])) { ?>
                        <div class="catalog-products-viewed-header">
                            <?= $arVisual['TITLE']['VALUE'] ?>
                        </div>
                    <?php } ?>
                    <div class="catalog-products-viewed-items owl-carousel">
                        <?php $iIndex = 0 ?>
                        <?php foreach ($arResult['ITEMS'] as $arItem) { ?>
                        <?php
                            $sId = $sTemplateId.'_'.$arItem['ID'];
                            $sAreaId = $this->GetEditAreaId($sId);
                            $this->AddEditAction($sId, $arItem['EDIT_LINK']);
                            $this->AddDeleteAction($sId, $arItem['DELETE_LINK']);

                            $arPrice = ArrayHelper::getFirstValue($arItem['ITEM_PRICES']);
                            $sPicture = null;

                            if (!empty($arItem['PREVIEW_PICTURE'])) {
                                $sPicture = $arItem['PREVIEW_PICTURE'];
                            } else if (!empty($arItem['DETAIL_PICTURE'])) {
                                $sPicture = $arItem['DETAIL_PICTURE'];
                            }

                            if (!empty($sPicture))
                                $sPicture = CFile::ResizeImageGet($sPicture['ID'], array(
                                    'width' => 100,
                                    'height' => 100
                                ), BX_RESIZE_IMAGE_PROPORTIONAL_ALT);

                            if (!empty($sPicture)) {
                                $sPicture = $sPicture['src'];
                            } else {
                                $sPicture = SITE_TEMPLATE_PATH.'/images/picture.missing.png';
                            }
                        ?>
                            <a href="<?= $arItem['DETAIL_PAGE_URL'] ?>" class="catalog-products-viewed-item" id="<?= $sAreaId ?>">
                                <div class="catalog-products-viewed-item-wrapper intec-grid">
                                    <div class="catalog-products-viewed-image intec-grid-item">
                                        <?= Html::tag('div', null, [
                                            'class' => 'catalog-products-viewed-image-wrapper',
                                            'data' => [
                                                'lazyload-use' => $arVisual['LAZYLOAD']['USE'] ? 'true' : 'false',
                                                'original' => $arVisual['LAZYLOAD']['USE'] ? $sPicture : null
                                            ],
                                            'style' => [
                                                'background-image' => !$arVisual['LAZYLOAD']['USE'] ? 'url(\''.$sPicture.'\')' : null
                                            ]
                                        ]) ?>
                                    </div>
                                    <div class="catalog-products-viewed-information intec-grid-item">
                                        <div class="catalog-products-viewed-name">
                                            <div class="catalog-products-viewed-name-wrapper intec-cl-text-hover">
                                                <?= $arItem['NAME'] ?>
                                            </div>
                                        </div>
                                        <?php if (!empty($arPrice)) { ?>
                                            <div class="catalog-products-viewed-price-wrap">
                                                <div class="catalog-products-viewed-price">
                                                    <?= $arPrice['PRINT_PRICE'] ?>
                                                </div>
                                                <?php if ($arPrice['DISCOUNT'] !== 0) { ?>
                                                    <div class="catalog-products-viewed-price-base">
                                                        <?= $arPrice['PRINT_BASE_PRICE'] ?>
                                                    </div>
                                                <?php } ?>
                                            </div>
                                        <?php } ?>
                                    </div>
                                </div>
                            </a>
                            <?php $iIndex++ ?>
                        <?php } ?>
                        <?php while ($iIndex < $arVisual['COLUMNS']) { ?>
                            <div class="catalog-products-viewed-item">
                                <div class="catalog-products-viewed-item-wrapper blank">
                                </div>
                            </div>
                            <?php $iIndex++ ?>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?= Html::endTag('div') ?>
<script type="text/javascript">
    (function ($, api) {
        var root = $(<?= JavaScript::toObject('#'.$sTemplateId) ?>);
        var data = <?= JavaScript::toObject([
            'columns' => $arVisual['COLUMNS'],
            'navigation' => $arVisual['SLIDER']['NAVIGATION']
        ]) ?>;

        handler = function () {
            var items = root.find('.owl-stage');
            items.children('.owl-item').css('visibility', 'collapse');
            items.children('.owl-item.active').css('visibility', '');
        };

        var slider = $('.catalog-products-viewed-items', root);
        var responsive = {
            0: {'items': 1},
            450: {'items': 2},
            600: {'items': 3},
            820: {'items': 4}
        };

        if (data.columns > 4)
            responsive[1100] = {'items': 5};

        responsive[1200] = {'items': data.columns};
        slider.owlCarousel({
            'center': false,
            'loop': false,
            'nav': data.navigation,
            'margin': 10,
            'stagePadding': 1,
            'navText': [
                '<i class="fa fa-arrow-left intec-cl-text-hover"></i>',
                '<i class="fa fa-arrow-right intec-cl-text-hover"></i>'
            ],
            'dots': false,
            'responsive': responsive,
            'onResized': handler,
            'onRefreshed': handler,
            'onInitialized': handler,
            'onTranslated': handler
        });
    })(jQuery, intec);
</script>
