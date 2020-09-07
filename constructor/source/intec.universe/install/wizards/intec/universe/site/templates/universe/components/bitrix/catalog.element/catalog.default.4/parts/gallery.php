<?php if (!defined('B_PROLOG_INCLUDED') && B_PROLOG_INCLUDED !== true) die();

use intec\core\helpers\Html;
use intec\template\Properties;

/**
 * @var array $arResult
 * @var array $arVisual
 * @var array $arData
 */

$sTag = $arVisual['GALLERY']['ACTION'] === 'source' ? 'a' : 'div';
$sStub = Properties::get('template-images-lazyload-stub');

?>
<?php $vGallery = function (&$arItem, $bOffer = false) use (&$arResult, &$arVisual, &$sTag, &$sStub) { ?>
    <?php if ($bOffer && empty($arItem['DATA']['GALLERY'])) return ?>
    <?= Html::beginTag('div', [
        'class' => 'catalog-element-gallery-item',
        'data' => [
            'role' => 'gallery',
            'offer' => $bOffer ? $arItem['ID'] : 'false',
            'action' => $arVisual['GALLERY']['ACTION'],
            'zoom' => $arVisual['GALLERY']['ZOOM'] ? 'true' : 'false'
        ]
    ]) ?>
        <div class="catalog-element-gallery-pictures-content">
            <?= Html::beginTag('div', [
                'class' => Html::cssClassFromArray([
                    'catalog-element-gallery-pictures' => true,
                    'owl-carousel' => !empty($arItem['DATA']['GALLERY'])
                ], true),
                'data-role' => !empty($arItem['DATA']['GALLERY']) ? 'gallery.pictures' : 'gallery.empty'
            ]) ?>
                <?php if (!empty($arItem['DATA']['GALLERY'])) { ?>
                    <?php foreach ($arItem['DATA']['GALLERY'] as $arPicture) {

                        $arPictureResize = CFile::ResizeImageGet(
                            $arPicture, [
                                'width' => 650,
                                'height' => 650
                            ], BX_RESIZE_IMAGE_PROPORTIONAL_ALT
                        );

                    ?>
                        <?= Html::beginTag($sTag, [
                            'class' => 'catalog-element-gallery-picture',
                            'href' => $sTag === 'a' ? $arPicture['SRC'] : null,
                            'target' => $sTag === 'a' ? '_blank' : null,
                            'data-role' => 'gallery.picture',
                            'data-src' => $arVisual['GALLERY']['ACTION'] === 'popup' || $arVisual['GALLERY']['ZOOM'] ? $arPicture['SRC'] : null
                        ]) ?>
                            <div class="intec-aligner"></div>
                            <?= Html::img($arVisual['LAZYLOAD']['USE'] ? $sStub : $arPictureResize['src'], [
                                'alt' => !empty($arItem['NAME']) ? $arItem['NAME'] : $arResult['NAME'],
                                'title' => !empty($arItem['NAME']) ? $arItem['NAME'] : $arResult['NAME'],
                                'loading' => 'lazy',
                                'data' => [
                                    'lazyload-use' => $arVisual['LAZYLOAD']['USE'] ? 'true' : 'false',
                                    'original' => $arVisual['LAZYLOAD']['USE'] ? $arPictureResize['src'] : null
                                ]
                            ]) ?>
                        <?= Html::endTag($sTag) ?>
                    <?php } ?>
                <?php } else { ?>
                    <div class="catalog-element-gallery-picture">
                        <div class="intec-aligner"></div>
                        <?= Html::img($arVisual['LAZYLOAD']['USE'] ? $sStub : SITE_TEMPLATE_PATH.'/images/picture.missing.png', [
                            'alt' => $arResult['NAME'],
                            'title' => $arResult['NAME'],
                            'loading' => 'lazy',
                            'data' => [
                                'lazyload-use' => $arVisual['LAZYLOAD']['USE'] ? 'true' : 'false',
                                'original' => $arVisual['LAZYLOAD']['USE'] ? SITE_TEMPLATE_PATH.'/images/picture.missing.png' : null
                            ]
                        ]) ?>
                    </div>
                <?php } ?>
            <?= Html::endTag('div') ?>
        </div>
        <?php if ($arVisual['GALLERY']['PREVIEW']['SHOW'] && count($arItem['DATA']['GALLERY']) > 1) { ?>
            <div class="catalog-element-gallery-previews-content">
                <div class="catalog-element-gallery-previews owl-carousel" data-role="gallery.previews">
                    <?php $bFirst = true ?>
                    <?php foreach ($arItem['DATA']['GALLERY'] as $arPicture) {

                        $arPictureResize = CFile::ResizeImageGet(
                            $arPicture, [
                                'width' => 110,
                                'height' => 110
                            ], BX_RESIZE_IMAGE_PROPORTIONAL_ALT
                        );

                    ?>
                        <?= Html::beginTag('div', [
                            'class' => 'catalog-element-gallery-preview',
                            'data' => [
                                'role' => 'gallery.preview',
                                'active' => $bFirst ? 'true' : 'false'
                            ]
                        ]) ?>
                            <div class="intec-aligner"></div>
                            <?= Html::img($arVisual['LAZYLOAD']['USE'] ? $sStub : $arPictureResize['src'], [
                                'alt' => !empty($arItem['NAME']) ? $arItem['NAME'] : $arResult['NAME'],
                                'title' => !empty($arItem['NAME']) ? $arItem['NAME'] : $arResult['NAME'],
                                'loading' => 'lazy',
                                'data' => [
                                    'lazyload-use' => $arVisual['LAZYLOAD']['USE'] ? 'true' : 'false',
                                    'original' => $arVisual['LAZYLOAD']['USE'] ? $arPictureResize['src'] : null
                                ]
                            ]) ?>
                        <?= Html::endTag('div') ?>
                        <?php if ($bFirst) $bFirst = false ?>
                    <?php } ?>
                </div>
                <div class="catalog-element-gallery-previews-nav" data-role="gallery.previews.nav"></div>
            </div>
        <?php } ?>
    <?= Html::endTag('div') ?>
<?php } ?>
<div class="catalog-element-gallery">
    <?php $vGallery($arResult) ?>
    <?php if (!empty($arResult['OFFERS'])) {
        foreach ($arResult['OFFERS'] as &$arOffer)
            $vGallery($arOffer, true);

        unset($arOffer);
    } ?>
</div>
<?php unset($sTag, $sStub, $vGallery) ?>