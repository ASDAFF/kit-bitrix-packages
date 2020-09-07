<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die() ?>
<?php

use intec\core\bitrix\Component;
use intec\core\helpers\Html;
use intec\core\helpers\JavaScript;

/**
 * @var array $arResult
 * @var array $arParams
 */

$this->setFrameMode(true);

if (empty($arResult['FILES']))
    return;

$sTemplateId = Html::getUniqueId(null, Component::getUniqueId($this));

$arVisual = $arResult['VISUAL'];
?>
<?= Html::beginTag('div', [
    'id' => $sTemplateId,
    'class' => 'ns-intec-universe c-system-video-tag c-system-video-tag-default',
    'style' => [
        'width' => $arResult['WIDTH'],
        'height' => $arResult['HEIGHT']
    ]
]) ?>
    <div class="system-video-tag-content">
        <?= Html::beginTag('video', [
            'autoplay' => $arResult['AUTOPLAY'] ? 'autoplay' : null,
            'controls' => $arResult['CONTROLS'] ? 'controls' : null,
            'loop' => $arResult['LOOP'] ? 'loop' : null,
            'muted' => $arResult['MUTE'] ? 'muted' : null,
            'preload' => $arResult['PRELOAD'],
            'data' => [
                'role' => 'tag',
                'lazyload-use' => $arVisual['LAZYLOAD']['USE'] ? 'true' : 'false',
                'original' => $arVisual['LAZYLOAD']['USE'] ? $arResult['PICTURE'] : null
            ],
            'style' => [
                'background-image' => !$arVisual['LAZYLOAD']['USE'] ? 'url(\''.$arResult['PICTURE'].'\')' : null
            ]
        ]) ?>
            <?php if (!empty($arResult['FILES']['MP4'])) { ?>
                <source src="<?= Html::encode($arResult['FILES']['MP4']) ?>" type="video/mp4" />
            <?php } ?>
            <?php if (!empty($arResult['FILES']['WEBM'])) { ?>
                <source src="<?= Html::encode($arResult['FILES']['WEBM']) ?>" type="video/webm" />
            <?php } ?>
            <?php if (!empty($arResult['FILES']['OGV'])) { ?>
                <source src="<?= Html::encode($arResult['FILES']['OGV']) ?>" type="video/ogg" />
            <?php } ?>
        <?= Html::endTag('video') ?>
    </div>
    <?php if (!$arResult['CONTROLLED']) { ?>
        <div class="system-video-tag-overlay"></div>
    <?php } ?>
    <script type="text/javascript">
        (function ($, api) {
            var root = $(<?= JavaScript::toObject('#'.$sTemplateId) ?>);
            var tag = $('[data-role="tag"]', root);
            var settings = <?= JavaScript::toObject([
                'adaptation' => [
                    'use' => $arResult['ADAPTATION']['USE'],
                    'mode' => $arResult['ADAPTATION']['MODE']
                ]
            ]) ?>;

            if (settings.adaptation.use) {
                var adapt = function () {
                    tag.css({
                        'width': 'auto',
                        'height': 'auto'
                    });

                    var ratio = {
                        'horizontal': api.toFloat(((tag.width() / tag.height()) * 9).toFixed(2)),
                        'vertical': 9
                    };

                    var width = root.width();
                    var widthRatio = width / ratio.horizontal;
                    var height = root.height();
                    var heightRatio = height / ratio.vertical;

                    if (settings.adaptation.mode === 'cover') {
                        if (widthRatio > heightRatio) {
                            tag.css({
                                'height': width * ratio.vertical / ratio.horizontal
                            });
                        } else {
                            tag.css({
                                'width': height * ratio.horizontal / ratio.vertical
                            });
                        }
                    } else {
                        if (widthRatio > heightRatio) {
                            tag.css({
                                'width': height * ratio.horizontal / ratio.vertical
                            });
                        } else {
                            tag.css({
                                'height': width * ratio.vertical / ratio.horizontal
                            });
                        }
                    }
                };

                $(adapt);
                $(window).on('resize', adapt);

                tag.on('canplay canplaythrough loadeddata loadedmetadata', adapt);
            }
        })(jQuery, intec);
    </script>
<?= Html::endTag('div') ?>