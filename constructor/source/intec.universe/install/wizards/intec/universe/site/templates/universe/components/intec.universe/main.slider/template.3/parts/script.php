<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use intec\core\helpers\JavaScript;

/**
 * @var string $sTemplateId
 * @var array $arVisual
 * @var bool $bSliderUse
 */

$arSettings = [
    'attribute' => $arParams['~ATTRIBUTE'],
    'selector' => $arParams['~SELECTOR'],
    'slider' => [
        'use' => $bSliderUse,
        'items' => 1,
        'nav' => $arVisual['SLIDER']['NAV']['SHOW'],
        'navContainer' => '[data-role="container.nav"]',
        'navClass' => [
            'nav-prev',
            'nav-next'
        ],
        'navText' => [
            '<i class="far fa-angle-left"></i>',
            '<i class="far fa-angle-right"></i>'
        ],
        'dots' => $arVisual['SLIDER']['DOTS']['SHOW'],
        'dotsContainer' => '[data-role="container.dots"]',
        'loop' => $arVisual['SLIDER']['LOOP'],
        'autoplay' => $arVisual['SLIDER']['AUTO']['USE'],
        'autoplayTimeout' => $arVisual['SLIDER']['AUTO']['TIME'],
        'autoplayHoverPause' => $arVisual['SLIDER']['AUTO']['HOVER']
    ]
];

?>
<script type="text/javascript">
    (function () {
        var root = $(<?= JavaScript::toObject('#'.$sTemplateId) ?>);
        var container = $('[data-role="container"]', root);
        var settings = <?= JavaScript::toObject($arSettings) ?>;
        var schemeChange;

        schemeChange = function () {
            var content = $('[data-role="content"]', root);
            var item;
            var scheme;

            if (settings.slider.use) {
                item = $('.owl-item.active .widget-item', container);
            } else {
                item = $('.widget-item', container).eq(0);
            }

            scheme = item.attr('data-item-scheme');
            content.attr('data-scheme', scheme);

            if (settings.selector) {
                content = root.closest(settings.selector);

                if (settings.attribute)
                    content.attr(settings.attribute, scheme);
            }
        };

        if (settings.slider.use) {
            container.owlCarousel({
                'items': settings.slider.items,
                'nav': settings.slider.nav,
                'navContainer': $(settings.slider.navContainer, root),
                'navClass': settings.slider.navClass,
                'navText': settings.slider.navText,
                'dots': settings.slider.dots,
                'dotsContainer': $(settings.slider.dotsContainer, root),
                'loop': settings.slider.loop,
                'autoplay': settings.slider.autoplay,
                'autoplayTimeout': settings.slider.autoplayTimeout,
                'autoplayHoverPause': settings.slider.autoplayHoverPause,
                'onTranslated': schemeChange
            });
        }

        schemeChange();
    })();
</script>