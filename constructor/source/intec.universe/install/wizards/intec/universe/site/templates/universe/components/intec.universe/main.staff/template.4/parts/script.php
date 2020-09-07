<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use intec\core\helpers\JavaScript;

/**
 * @var array $arVisual
 * @var string $sTemplateId
 */

$arSlider = [
    'items' => 1,
    'nav' => $arVisual['SLIDER']['NAV'],
    'navText' => [
        '<i class="fal fa-angle-left"></i>',
        '<i class="fal fa-angle-right"></i>'
    ],
    'dots' => false
];


?>
<script type="text/javascript">
    (function ($, api) {
        var root = $(<?= JavaScript::toObject('#'.$sTemplateId) ?>);
        var gallery = $('[data-role="items"]', root);
        var dots = $('[data-role="dots"]', root);
        var navigation = $('[data-role="navigation"]', root);
        var panel = $('[data-role="panel"]', root);

        gallery.items = $('[data-role="item"]', gallery);
        panel.counter = $('[data-role="panel.counter"]', panel);
        panel.quantity = api.controls.numeric({}, panel.counter);

        var settings = <?= JavaScript::toObject($arSlider) ?>;

        gallery.owlCarousel({
            'items': settings.items,
            'nav': settings.nav,
            'navContainer': navigation,
            'navText': settings.navText,
            'navClass': ['intec-ui-part-button-left', 'intec-ui-part-button-right'],
            'dots': settings.dots,
            'dotsContainer': dots,
            'dotClass': 'intec-ui-part-dot'
        });

        gallery.on('changed.owl.carousel', function (event) {
            panel.current.set(event.item.index + 1);
        });

        panel.current = $('[data-role="panel.current"]', panel);
        panel.current.set = function (number) {
            this.value = number;
            this.text(number + '/' + gallery.items.length);
        };
        panel.current.set(1);

    })(jQuery, intec);
</script>
