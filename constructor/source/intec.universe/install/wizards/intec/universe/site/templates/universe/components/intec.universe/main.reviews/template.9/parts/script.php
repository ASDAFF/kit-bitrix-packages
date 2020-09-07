<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use intec\core\helpers\JavaScript;

/**
 * @var string $sTemplateId
 * @var array $arVisual
 */

$arSettings = [
    'loop' => $arVisual['SLIDER']['LOOP'],
    'navText' => [
        '<i class="fal fa-angle-left"></i>',
        '<i class="fal fa-angle-right"></i>'
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
            'items': 1,
            'loop': settings.loop,
            'dots': false,
            'nav': true,
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