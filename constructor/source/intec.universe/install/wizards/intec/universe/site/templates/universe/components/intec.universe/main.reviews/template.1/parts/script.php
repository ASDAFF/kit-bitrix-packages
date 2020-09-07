<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use intec\core\helpers\JavaScript;

/**
 * @var string $sTemplateId
 * @var array $arVisual
 */

if ($arVisual['SLIDER']['USE']) {
    $arSettings = [
        'items' => $arVisual['COLUMNS'],
        'dots' => $arVisual['SLIDER']['DOTS'],
        'loop' => $arVisual['SLIDER']['LOOP'],
        'autoplay' => $arVisual['SLIDER']['AUTO']['USE'],
        'autoplayTimeout' => $arVisual['SLIDER']['AUTO']['TIME'],
        'autoplayHoverPause' => $arVisual['SLIDER']['AUTO']['HOVER'],
        'responsive' => [
            0 => ['items' => 1]
        ]
    ];

    if ($arVisual['COLUMNS'] >= 2)
        $arSettings['responsive'][1025] = ['items' => 2];
}

?>
<script type="text/javascript">
    (function () {
        var root = $(<?= JavaScript::toObject('#'.$sTemplateId) ?>);
        var container = $('[data-role="container"]', root);

        <?php if ($arVisual['VIDEO']['SHOW']) { ?>
            container.lightGallery({
                'selector': '[data-role="video"]'
            });
        <?php } ?>
        <?php if ($arVisual['SLIDER']['USE']) { ?>
            var settings = <?= JavaScript::toObject($arSettings) ?>;

            container.owlCarousel({
                'items': settings.items,
                'dots': settings.dots,
                'dotsClass': 'intec-ui intec-ui-control-dots',
                'dotClass': 'intec-ui-part-dot',
                'loop': settings.loop,
                'autoplay': settings.autoplay,
                'autoplayTimeout': settings.autoplayTimeout,
                'autoplayHoverPause': settings.autoplayHoverPause,
                'responsive': settings.responsive,
                'autoHeight': true
            });
        <?php } ?>
    })();
</script>