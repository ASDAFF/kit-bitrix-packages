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
$sTemplateId = Html::getUniqueId(null, Component::getUniqueId($this));

?>
<?= Html::beginTag('div', [
    'id' => $sTemplateId,
    'class' => 'ns-intec-universe c-system-video-frame c-system-video-frame-default',
    'style' => [
        'width' => $arResult['WIDTH'],
        'height' => $arResult['HEIGHT']
    ]
]) ?>
    <div class="system-video-frame-content">
        <?= Html::tag('iframe', '', [
            'src' => $arResult['LINKS']['embed'],
            'frameborder' => '0',
            'width' => '100%',
            'height' => '100%',
            'data' => [
                'role' => 'frame'
            ]
        ]) ?>
    </div>
    <?php if (!$arResult['CONTROLLED']) { ?>
        <div class="system-video-frame-overlay"></div>
    <?php } ?>
    <script type="text/javascript">
        (function ($, api) {
            var root = $(<?= JavaScript::toObject('#'.$sTemplateId) ?>);
            var frame = $('[data-role="frame"]', root);
            var settings = <?= JavaScript::toObject([
                'adaptation' => [
                    'use' => $arResult['ADAPTATION']['USE'],
                    'mode' => $arResult['ADAPTATION']['MODE'],
                    'ratio' => [
                        'horizontal' => $arResult['ADAPTATION']['RATIO']['HORIZONTAL'],
                        'vertical' => $arResult['ADAPTATION']['RATIO']['VERTICAL']
                    ]
                ]
            ]) ?>;

            if (settings.adaptation.use) {
                var adapt = function () {
                    var width = root.width();
                    var widthRatio = width / settings.adaptation.ratio.horizontal;
                    var height = root.height();
                    var heightRatio = height / settings.adaptation.ratio.vertical;

                    if (settings.adaptation.mode === 'cover') {
                        if (widthRatio > heightRatio) {
                            frame.css({
                                'width': '',
                                'height': width * settings.adaptation.ratio.vertical / settings.adaptation.ratio.horizontal
                            });
                        } else {
                            frame.css({
                                'width': height * settings.adaptation.ratio.horizontal / settings.adaptation.ratio.vertical,
                                'height': ''
                            });
                        }
                    } else {
                        if (widthRatio > heightRatio) {
                            frame.css({
                                'width': height * settings.adaptation.ratio.horizontal / settings.adaptation.ratio.vertical,
                                'height': ''
                            });
                        } else {
                            frame.css({
                                'width': '',
                                'height': width * settings.adaptation.ratio.vertical / settings.adaptation.ratio.horizontal
                            });
                        }
                    }
                };

                $(adapt);
                $(window).on('resize', adapt);
            }
        })(jQuery, intec);
    </script>
<?= Html::endTag('div') ?>