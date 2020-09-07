<?php if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die() ?>
<?php

use intec\core\helpers\ArrayHelper;
use intec\core\helpers\JavaScript;
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
 * @var string $sTemplateId
 */

$arImages = ArrayHelper::getValue($arResult, 'IMAGES');

if (empty($arImages))
    return;

?>
<div class="project-section project-section-images">
    <div class="intec-content intec-content-visible">
        <div class="intec-content-wrapper">
            <div class="project-images">
                <div class="project-images-wrapper">
                    <?php $iNumber = 0; ?>
                    <?php foreach ($arImages as $arImage) { ?>
                    <?php
                        $iNumber++;
                        $sImageBig = $arImage['SRC'];
                        $sImageSmall = CFile::ResizeImageGet($arImage, array(
                            'width' => 750,
                            'height' => 750
                        ));
                        $sImageSmall = $sImageSmall['src'];
                        $sDescription = $arImage['DESCRIPTION'];

                        if (empty($sDescription))
                            $sDescription = $arImage['ORIGINAL_NAME'];
                    ?>
                        <div class="project-images-image">
                            <div class="project-images-image-wrapper">
                                <a href="<?= $sImageBig ?>" class="project-images-image-wrapper-2"
                                   data-preview-src="<?=$sImageSmall?>"
                                >
                                    <div class="project-images-image-wrapper-3"
                                        data-src="<?= $sImageBig ?>"
                                    >
                                        <?= Html::tag('div', '', [
                                            'class' => [
                                                'project-images-image-wrapper-4'
                                            ],
                                            'data' => [
                                                'lazyload-use' => $arLazyLoad['USE'] ? 'true' : 'false',
                                                'original' => $arLazyLoad['USE'] ? $sImageSmall : null
                                            ],
                                            'style' => [
                                                'background-image' => !$arLazyLoad['USE'] ? 'url(\''.$sImageSmall.'\')' : null
                                            ]
                                        ]) ?>
                                        <?= Html::img($arLazyLoad['USE'] ? $arLazyLoad['STUB'] : $sImageSmall, [
                                            'class' => 'widget-element-picture',
                                            'alt' => $sDescription,
                                            'loading' => 'lazy',
                                            'data' => [
                                                'lazyload-use' => $arLazyLoad['USE'] ? 'true' : 'false',
                                                'original' => $arLazyLoad['USE'] ? $sImageSmall : null
                                            ]
                                        ]) ?>
                                    </div>
                                </a>
                            </div>
                        </div>
                    <?php } ?>
                    <div class="clearfix"></div>
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript">
        (function ($, api) {
            $(document).ready(function () {
                var root = $(<?= JavaScript::toObject('#'.$sTemplateId) ?>);
                var gallery = root.find('.project-images .project-images-wrapper');

                gallery.lightGallery({
                    selector: '.project-images-image-wrapper-2',
                    exThumbImage: 'data-preview-src',
                    autoplay: false,
                    share: false
                });
            });
        })(jQuery, intec);
    </script>
</div>
