<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die() ?>
<?php

use intec\core\helpers\JavaScript;

/**
 * @var array $arParams
 * @var array $arResult
 * @var array $arVisual
 * @var string $sTemplateId
 */

if (!$arVisual['SLIDER']['USE'])
    return;

$arData = [
    'columns' => $arVisual['COLUMNS'],
    'responsive' => [
        0 => ['items' => 1],
        651 => ['items' => 2]
    ],
    'slider' => [
        'use' => $arVisual['SLIDER']['USE'],
        'loop' => $arVisual['SLIDER']['LOOP'],
        'dots' => $arVisual['SLIDER']['DOTS'],
        'navigation' => $arVisual['SLIDER']['NAVIGATION'],
        'auto' => [
            'use' => $arVisual['SLIDER']['AUTO']['USE'],
            'pause' => $arVisual['SLIDER']['AUTO']['PAUSE'],
            'speed' => $arVisual['SLIDER']['AUTO']['SPEED'],
            'time' => $arVisual['SLIDER']['AUTO']['TIME']
        ]
    ]
];

if ($arVisual['COLUMNS'] >= 3)
    $arData['responsive'][951] = ['items' => 3];

if ($arVisual['COLUMNS'] >= 4)
    $arData['responsive'][1151] = ['items' => 4];

?>
<script type="text/javascript">
    (function () {
        var root = $(<?= JavaScript::toObject('#'.$sTemplateId) ?>);
        var sliders = $('[data-role="slider"]', root);
        var settings = <?= JavaScript::toObject($arData) ?>;

        if (settings.slider.use) {
            sliders.owlCarousel({
                'items': settings.columns,
                'autoplay': settings.slider.auto.use,
                'autoplaySpeed': settings.slider.auto.speed,
                'autoplayTimeout': settings.slider.auto.time,
                'autoplayHoverPause': settings.slider.auto.pause,
                'loop': settings.slider.loop,
                'nav': settings.slider.navigation,
                'navText': [
                    '<span class="fal fa-chevron-left"></span>',
                    '<span class="fal fa-chevron-right"></span>'
                ],
                'dots': settings.slider.dots,
                'margin': 30,
                'responsive': settings.responsive
            });
        }
    })();
</script>
