<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use intec\core\helpers\JavaScript;

/**
 * @var string $sTemplateId
 * @var array $arVisual
 */

$arSettings = [
    'items' => 1,
    'loop' => $arVisual['SLIDER']['LOOP'],
    'dots' => $arVisual['SLIDER']['DOTS'],
    'nav' => $arVisual['SLIDER']['NAV'],
    'navText' => [
        '<i class="far fa-angle-left"></i>',
        '<i class="far fa-angle-right"></i>'
    ],
    'autoplay' => $arVisual['SLIDER']['AUTO']['USE'],
    'autoplayTimeout' => $arVisual['SLIDER']['AUTO']['TIME'],
    'autoplayHoverPause' => $arVisual['SLIDER']['AUTO']['HOVER'],
    'autoHeight' => !defined('EDITOR') ? true : false
];

?>
<script type="text/javascript">
    (function () {
        var root = $(<?= JavaScript::toObject('#'.$sTemplateId) ?>);
        var container = $('[data-role="container"]', root);
        var settings = <?= JavaScript::toObject($arSettings) ?>;

        container.owlCarousel({
            'items': settings.items,
            'loop': settings.loop,
            'dots': settings.dots,
            'dotsClass': 'intec-ui intec-ui-control-dots',
            'dotClass': 'intec-ui-part-dot',
            'nav': settings.nav,
            'navText': settings.navText,
            'navContainerClass': 'intec-ui intec-ui-control-navigation',
            'navClass': ['intec-ui-part-button-left', 'intec-ui-part-button-right'],
            'autoplay': settings.autoplay,
            'autoplayTimeout': settings.autoplayTimeout,
            'autoplayHoverPause': settings.autoplayHoverPause,
            'autoHeight': settings.autoHeight
        });
    })();
</script>