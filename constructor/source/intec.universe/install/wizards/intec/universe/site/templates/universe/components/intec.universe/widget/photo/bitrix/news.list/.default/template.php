<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use intec\core\helpers\ArrayHelper;
use intec\core\helpers\Html;

/**
 * @var array $arParams
 * @var array $arResult
 * @global CMain $APPLICATION
 * @global CUser $USER
 * @global CDatabase $DB
 * @var CBitrixComponentTemplate $this
 * @var string $templateName
 * @var string $templateFile
 * @var string $templateFolder
 * @var string $componentPath
 * @var CBitrixComponent $component
 */

$this->setFrameMode(true);

$sTitle = ArrayHelper::getValue($arParams, 'TITLE');
$bTitleCenter = ArrayHelper::getValue($arParams, 'ALIGHT_HEADER') == 'Y';

$arVisual = $arResult['VISUAL'];
?>
<div id="<?= $arResult['COMPONENT_HASH'] ?>" class="intec-content intec-content-visible">
    <div class="intec-content-wrapper">
        <div class="intec-gallery desktop-template">
            <?php if ($arResult['SHOW_TITLE']) { ?>
                <div class="intec-gallery-title <?= $bTitleCenter ? 'text-center' : null ?>">
                    <?= $sTitle ?>
                </div>
            <?php } ?>
            <div class="intec-gallery-slides <?= $arResult['USE_CAROUSEL'] ? 'owl-carousel' : '' ?>">
                <?php for ($i = 0; $i < $arResult['SLIDES_COUNT']; $i++) {

                    $items = array_slice(
                            $arResult['ITEMS'],
                            $arResult['ITEMS_IN_SLIDE'] * $i,
                            $arResult['ITEMS_IN_SLIDE']
                    );

                ?>
                    <div class="intec-gallery_items intec-gallery-slide clearfix">
                        <?php foreach ($items as $item) {

                            $itemData = $item['CUSTOM_DATA'];

                        ?>
                            <div class="intec-gallery_item" data-src="<?= $itemData['DETAIL_IMAGE'] ?>">
                                <a href="<?= $itemData['DETAIL_IMAGE'] ?>" class="intec-gallery_item_wrap" title="<?= $item['NAME'] ?>">
                                    <?= Html::tag('span', '', [
                                        'class' => [
                                            'intec-gallery_image',
                                        ],
                                        'data' => [
                                            'src' => $itemData['DETAIL_IMAGE'],
                                            'preview-src' => $itemData['PREVIEW_IMAGE'],
                                            'lazyload-use' => $arVisual['LAZYLOAD']['USE'] ? 'true' : 'false',
                                            'original' => $arVisual['LAZYLOAD']['USE'] ? $itemData['PREVIEW_IMAGE'] : null
                                        ],
                                        'style' => [
                                            'background-image' => !$arVisual['LAZYLOAD']['USE'] ? 'url(\''.$itemData['PREVIEW_IMAGE'].'\')' : null
                                        ]
                                    ]) ?>
                                </a>
                            </div>
                        <?php } ?>
                    </div>
                <?php } ?>
            </div>
            <?php if ($arResult['SHOW_DETAIL_LINK']) { ?>
                <div class="intec-detail-link-wrap">
                    <a href="<?= $arResult['LIST_URL'] ?>"
                       class="intec-detail-link intec-button intec-button-cl-common intec-button-transparent intec-button-s-7">
                        <span><?= $arParams['DETAIL_LINK_TEXT'] ?></span>
                        <i class="typcn typcn-arrow-right"></i>
                    </a>
                </div>
            <?php } ?>
        </div>
        <?php if (!defined('EDITOR')) { ?>
            <div class="intec-gallery mobile-template">
                <?php if ($arResult['SHOW_TITLE']) { ?>
                    <div class="intec-gallery-title"><?= $arParams['TITLE'] ?></div>
                <?php } ?>

                <div class="intec-gallery_items clearfix">
                    <?php foreach ($arResult['ITEMS'] as $item) {
                        $itemData = $item['CUSTOM_DATA'];
                    ?>
                        <div class="intec-gallery_item" data-src="<?= $itemData['DETAIL_IMAGE'] ?>">
                            <a href="<?= $itemData['DETAIL_IMAGE'] ?>" class="intec-gallery_item_wrap" title="<?= $item['NAME'] ?>">
                                <?= Html::tag('span', '', [
                                    'class' => [
                                        'intec-gallery_image',
                                    ],
                                    'data' => [
                                        'src' => $itemData['DETAIL_IMAGE'],
                                        'preview-src' => $itemData['PREVIEW_IMAGE'],
                                        'lazyload-use' => $arVisual['LAZYLOAD']['USE'] ? 'true' : 'false',
                                        'original' => $arVisual['LAZYLOAD']['USE'] ? $itemData['DETAIL_IMAGE'] : null
                                    ],
                                    'style' => [
                                        'background-image' => !$arVisual['LAZYLOAD']['USE'] ? 'url(\''.$itemData['DETAIL_IMAGE'].'\')' : null
                                    ]
                                ]) ?>
                            </a>
                        </div>
                    <?php } ?>
                </div>
                <?php if ($arResult['SHOW_DETAIL_LINK']) { ?>
                    <div class="intec-detail-link-wrap">
                        <a href="<?= $arResult['LIST_URL'] ?>"
                           class="intec-detail-link intec-button intec-button-cl-common intec-button-transparent intec-button-s-7">
                            <span><?= $arParams['DETAIL_LINK_TEXT'] ?></span>
                            <i class="typcn typcn-arrow-right"></i>
                        </a>
                    </div>
                <?php } ?>
            </div>
        <?}?>
    </div>
</div>

<style type="text/css">
    #<?= $arResult['COMPONENT_HASH'] ?> .intec-gallery.desktop-template .intec-gallery_item {
        <?= $arResult['ITEM_WIDTH'] ? 'width:'. $arResult['ITEM_WIDTH'] .';' : '' ?>
    }
</style>

<script type="text/javascript">
    $(document).ready(function(){
        <?php if ($arResult['USE_CAROUSEL']) { ?>
            $('#<?= $arResult['COMPONENT_HASH'] ?> .intec-gallery-slides.owl-carousel').owlCarousel({
                items: 1,
                margin: 15
            });
        <?php } ?>

        $('#<?= $arResult['COMPONENT_HASH'] ?> .intec-gallery').lightGallery({
            selector: '.intec-gallery_item',
            autoplay: false,
            share: false,
            exThumbImage: 'data-preview-src'
        });
    });
</script>