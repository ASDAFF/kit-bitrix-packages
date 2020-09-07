<?php if (!defined('B_PROLOG_INCLUDED') && B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;
use intec\core\helpers\Html;
use intec\core\helpers\Type;
use intec\template\Properties;

/**
 * @var array $arParams
 * @var array $arResult
 * @var array $arVisual
 */

$sStub = Properties::get('template-images-lazyload-stub');

$vGallery = function (&$arItem, $bOffer = false) use (&$arResult, &$arVisual, &$sStub) {

    if ($bOffer && empty($arItem['PICTURES']))
        return;

?>
    <?= Html::beginTag('div', [
        'data' => [
            'role' => 'gallery',
            'offer' => $bOffer ? $arItem['ID'] : 'false'
        ]
    ]) ?>
        <div class="intec-grid intec-grid-a-v-center intec-grid-a-h-start">
            <?php if ($arVisual['GALLERY']['PREVIEW']) { ?>
                <div class="catalog-element-gallery-preview intec-grid-item-auto">
                    <?= Html::beginTag('div', [
                        'class' => [
                            'catalog-element-gallery-preview-wrapper'
                        ],
                        'data' => [
                            'role' => 'gallery.preview'
                        ]
                    ]) ?>
                        <?php $iPicturesCount = count($arItem['PICTURES']) ?>
                        <?php if (!empty($arItem['PICTURES']) && $iPicturesCount > 1) { ?>
                            <?php $iCount = 0 ?>
                            <?php foreach ($arItem['PICTURES'] as $arPicture) {

                                $iCount++;

                                $arPictureResize = CFile::ResizeImageGet($arPicture, [
                                    'width' => 100,
                                    'height' => 100
                                ], BX_RESIZE_IMAGE_EXACT);

                            ?>
                                <?php if ($iCount != 6) { ?>
                                    <?= Html::beginTag('div', [
                                        'class' => 'catalog-element-gallery-preview-item',
                                        'data' => [
                                            'role' => 'gallery.preview.item',
                                            'active' => 'false'
                                        ]
                                    ]) ?>
                                        <?= Html::img($arVisual['LAZYLOAD']['USE'] ? $sStub : $arPictureResize['src'], [
                                            'alt' => Html::encode($arResult['NAME']),
                                            'title' => Html::encode($arResult['NAME']),
                                            'loading' => 'lazy',
                                            'data' => [
                                                'lazyload-use' => $arVisual['LAZYLOAD']['USE'] ? 'true' : 'false',
                                                'original' => $arVisual['LAZYLOAD']['USE'] ? $arPictureResize['src'] : null
                                            ]
                                        ]) ?>
                                    <?= Html::endTag('div') ?>
                                <?php }  else { ?>
                                    <?php if ($arVisual['GALLERY']['POPUP']) { ?>
                                        <?= Html::beginTag('div', [
                                            'class' => [
                                                'catalog-element-gallery-preview-popup',
                                                'intec-cl-text-hover'
                                            ],
                                            'data' => [
                                                'role' => 'gallery.preview.popup'
                                            ]
                                        ]) ?>
                                            <span>
                                                <?= Loc::getMessage('C_CATALOG_ELEMENT_CATALOG_DEFAULT_3_PREVIEW_MORE') ?>
                                            </span>
                                            <span>
                                                <?= $iPicturesCount - 5 ?>
                                            </span>
                                        <?= Html::endTag('div') ?>
                                        <?php $iCount = 0 ?>
                                    <?php } ?>
                                    <?php break ?>
                                <?php } ?>
                            <?php } ?>
                        <?php } ?>
                    <?= Html::endTag('div') ?>
                </div>
            <?php } ?>
            <div class="catalog-element-gallery intec-grid-item-auto">
                <div class="catalog-element-gallery-pictures">
                    <?= Html::beginTag('div', [
                        'class' => Html::cssClassFromArray([
                            'catalog-element-gallery-pictures-wrapper' => true,
                            'owl-carousel' => !empty($arItem['PICTURES'])
                        ], true),
                        'data' => [
                            'role' => !empty($arItem['PICTURES']) ? 'gallery.pictures' : 'gallery.empty'
                        ]
                    ]) ?>
                        <?php if (!empty($arItem['PICTURES'])) { ?>
                            <?php foreach ($arItem['PICTURES'] as $arPicture) {

                                $arPictureResize = CFile::ResizeImageGet($arPicture, [
                                    'width' => 550,
                                    'height' => 550
                                ], BX_RESIZE_IMAGE_PROPORTIONAL);

                            ?>
                                <?= Html::beginTag('a', [
                                    'href' => $arPicture['SRC'],
                                    'target' => '_blank',
                                    'class' => [
                                        'catalog-element-gallery-picture',
                                        'intec-image'
                                    ],
                                    'data' => [
                                        'role' => 'gallery.picture',
                                        'src' => $arPicture['SRC']
                                    ]
                                ]) ?>
                                    <div class="intec-aligner"></div>
                                    <?= Html::img($arVisual['LAZYLOAD']['USE'] ? $sStub : $arPictureResize['src'], [
                                        'alt' => Html::encode($arResult['NAME']),
                                        'title' => Html::encode($arResult['NAME']),
                                       'loading' => 'lazy',
                                        'data' => [
                                            'lazyload-use' => $arVisual['LAZYLOAD']['USE'] ? 'true' : 'false',
                                            'original' => $arVisual['LAZYLOAD']['USE'] ? $arPictureResize['src'] : null
                                        ]
                                    ]) ?>
                                <?= Html::endTag('a') ?>
                            <?php } ?>
                        <?php } else { ?>
                            <div class="catalog-element-gallery-picture intec-image" data-active="true">
                                <div class="intec-aligner"></div>
                                <?= Html::img($arVisual['LAZYLOAD']['USE'] ? $sStub : SITE_TEMPLATE_PATH.'/images/picture.missing.png', [
                                    'alt' => Html::encode($arResult['NAME']),
                                    'title' => Html::encode($arResult['NAME']),
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
                <?php if (Type::isArray($arItem['PICTURES']) && $arVisual['GALLERY']['PANEL'] && count($arItem['PICTURES']) > 1) { ?>
                    <?= Html::beginTag('div', [
                        'class' => [
                            'catalog-element-gallery-panel',
                            'intec-grid' => [
                                '',
                                'a-h-center',
                                'a-v-center'
                            ]
                        ],
                        'data' => [
                            'role' => 'gallery.panel'
                        ]
                    ]) ?>
                        <?php if ($arVisual['GALLERY']['POPUP']) { ?>
                            <div class="catalog-element-gallery-panel-item intec-grid-item-auto">
                                <div class="catalog-element-gallery-panel-popup" data-role="gallery.popup">
                                    <i class="fal fa-th-large"></i>
                                </div>
                            </div>
                        <?php } ?>
                        <div class="catalog-element-gallery-panel-item intec-grid-item-auto">
                            <div class="catalog-element-gallery-panel-navigation" data-role="gallery.previous">
                                <i class="far fa-chevron-left"></i>
                            </div>
                        </div>
                        <div class="catalog-element-gallery-panel-item intec-grid-item-auto">
                            <div class="catalog-element-gallery-panel-current" data-role="gallery.current"></div>
                        </div>
                        <div class="catalog-element-gallery-panel-item intec-grid-item-auto">
                            <div class="catalog-element-gallery-panel-navigation" data-role="gallery.next">
                                <i class="far fa-chevron-right"></i>
                            </div>
                        </div>
                        <?php if ($arVisual['GALLERY']['POPUP']) { ?>
                            <div class="catalog-element-gallery-panel-item intec-grid-item-auto">
                                <div class="catalog-element-gallery-panel-play" data-role="gallery.play">
                                    <i class="fal fa-play"></i>
                                </div>
                            </div>
                        <?php } ?>
                    <?= Html::endTag('div') ?>
                <?php } ?>
            </div>
        </div>
    <?= Html::endTag('div') ?>
<?php };

$vGallery($arResult);

if (!empty($arResult['OFFERS']))
    foreach ($arResult['OFFERS'] as &$arOffer) {
        $vGallery($arOffer, true);

        unset($arOffer);
    }

unset($vGallery, $sStub);