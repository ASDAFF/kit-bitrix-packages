<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use intec\core\helpers\JavaScript;

/**
 * @var string $sTemplateId
 * @var array $arVisual
 */

$arSlider = [
    'items' => $arVisual['COLUMNS'],
    'nav' => $arVisual['SLIDER']['NAV'],
    'navText' => [
        '<i class="fal fa-angle-left"></i>',
        '<i class="fal fa-angle-right"></i>'
    ],
    'dots' => $arVisual['SLIDER']['DOTS'],
    'loop' => $arVisual['SLIDER']['LOOP'],
    'autoplay' => $arVisual['SLIDER']['AUTO']['USE'],
    'autoplayTimeout' => $arVisual['SLIDER']['AUTO']['TIME'],
    'autoplayHoverPause' => false,
    'responsive' => [
        0 => ['items' => 1],
        650 => ['items' => 2],
        901 => ['items' => 3],
    ]
];

if ($arVisual['COLUMNS'] >= 4)
    $arSlider['responsive'][1201] = ['items' => 4];

?>
<script type="text/javascript">
    (function () {
        var root = $(<?= JavaScript::toObject('#'.$sTemplateId) ?>);
        var slider = $('[data-slider="true"]', root);
        var settings = <?= JavaScript::toObject($arSlider) ?>;

        slider.each(function () {
            var self = $(this);

            var adapt = function () {
                <?php if (!defined('EDITOR')) { ?>
                    var container = $('.owl-stage', self);
                    var item = $('.owl-item', self);

                    item.css({'height': 'initial'});
                    item.css({'height': container.height()});
                <?php }  else { ?>
                    return false;
                <?php } ?>
            };

            self.owlCarousel({
                'items': settings.items,
                'loop': settings.loop,
                'nav': settings.nav,
                'autoplay': settings.autoplay,
                'autoplayTimeout': settings.autoplayTimeout,
                'autoplayHoverPause': settings.autoplayHoverPause,
                'navText': settings.navText,
                'navContainerClass': 'intec-ui intec-ui-control-navigation',
                'navClass': ['intec-ui-part-button-left', 'intec-ui-part-button-right'],
                'dots': settings.dots,
                'dotsClass': 'intec-ui intec-ui-control-dots',
                'dotClass': 'intec-ui-part-dot',
                'responsive': settings.responsive,
                'onRefreshed': adapt
            });
        });
    })();
</script>