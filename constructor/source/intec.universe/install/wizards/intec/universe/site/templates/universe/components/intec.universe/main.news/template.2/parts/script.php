<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use intec\core\helpers\JavaScript;

/**
 * @var array $arVisual
 * @var string $sTemplateId
 */

$arSlider = [
    'items' => $arVisual['SLIDER']['ITEMS'],
    'nav' => true,
    'navClass' => [
        'widget-nav-prev intec-cl-text-hover',
        'widget-nav-next intec-cl-text-hover'
    ],
    'navText' => [
        '<i class="fal fa-chevron-left"></i>',
        '<i class="fal fa-chevron-right"></i>'
    ],
    'dots' => false,
    'loop' => $arVisual['SLIDER']['LOOP'],
    'autoplay' => $arVisual['SLIDER']['AUTO']['USE'],
    'autoplayTimeout' => $arVisual['SLIDER']['AUTO']['TIME'],
    'autoplayHoverPause' => $arVisual['SLIDER']['AUTO']['HOVER'],
    'autoHeight' => true,
    'responsive' => [
        '0' => ['items' => 1]
    ]
];

if (!$arSlider['autoplay'])
    unset(
        $arSlider['autoplay'],
        $arSlider['autoplayTimeout'],
        $arSlider['autoplayHoverPause']
    );

if ($arSlider['items'] >= 3 || $arSlider['items'] == 2)
    $arSlider['responsive']['601'] = ['items' => 2];

if ($arSlider['items'] >= 4)
    $arSlider['responsive']['1025'] = ['items' => 3];

$arSlider['responsive']['1201'] = ['items' => $arSlider['items']];

?>
<script type="text/javascript">
    (function () {
        var root = $(<?= JavaScript::toObject('#'.$sTemplateId) ?>);
        var slider = $('[data-role="slider"]', root);
        var nav = $('[data-role="nav"]', root);
        var settings = <?= JavaScript::toObject($arSlider) ?>;

        slider.owlCarousel({
            'items': settings.items,
            'nav': settings.nav,
            'navContainer': nav,
            'navClass': settings.navClass,
            'navText': settings.navText,
            'dots': settings.dots,
            'loop': settings.loop,
            'autoplay': settings.autoplay,
            'autoplayTimeout': settings.autoplayTimeout,
            'autoplayHoverPause': settings.autoplayHoverPause,
            'autoHeight': settings.autoHeight,
            'responsive': settings.responsive
        });
    })();
</script>
<?php unset($arSlider) ?>
