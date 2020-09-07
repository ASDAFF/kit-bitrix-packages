<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use intec\core\helpers\JavaScript;

/**
 * @var string $sTemplateId
 */

?>
<script type="text/javascript">
    (function ($, api) {
        var root = $(<?= JavaScript::toObject('#'.$sTemplateId) ?>);
        var maps = $('[data-role="maps"]', root);
        var scroll = maps.offset().top;
        var buttons = $('[data-role="button"]', root);

        var initialize;
        var loader;
        var map;
        var mapLocation;

        buttons.main = buttons.filter('[data-list="main"]');
        buttons.additional = buttons.filter('[data-list="additional"]');

        initialize = function () {

            if (!api.isObject(window.maps))
                return false;

            map = window.maps[<?= JavaScript::toObject($arParams['MAP_ID']) ?>];

            if (map == null)
                return false;

            buttons.on('click', function () {
                var index;

                buttons.main.active = buttons.main.filter('[data-state="enabled"]');

                if ($(this).is(buttons.main)) {
                    index = buttons.main.index($(this));
                } else {
                    index = buttons.additional.index($(this));
                    $('html, body').animate({'scrollTop': scroll}, 350);
                }

                buttons.main.active.attr('data-state', 'disabled');
                buttons.main.eq(index).attr('data-state', 'enabled');
                buttons.main.active.removeClass('intec-cl-background');
                buttons.main.eq(index).addClass('intec-cl-background');

                mapLocation(
                    $(this).data('latitude'),
                    $(this).data('longitude')
                );
            });
            
            return true;
        };

        mapLocation = function (latitude, longitude) {
            latitude = api.toFloat(latitude);
            longitude = api.toFloat(longitude);

            <?php if ($arParams['MAP_VENDOR'] == 'google') { ?>
            map.panTo(new google.maps.LatLng(latitude, longitude));
            <?php } else if ($arParams['MAP_VENDOR'] == 'yandex') { ?>
            map.panTo([latitude, longitude]);
            <?php } ?>
        };

        <?php if ($arParams['MAP_VENDOR'] == 'google') { ?>
        BX.ready(initialize);
        <?php } else if ($arParams['MAP_VENDOR'] == 'yandex') { ?>
        loader = function () {
            var load;

            load = function () {
                if (!initialize())
                    setTimeout(load, 100);
            };

            if (window.ymaps) {
                ymaps.ready(load);
            } else {
                setTimeout(loader, 100);
            }
        };

        loader();
        <?php } ?>
    })(jQuery, intec);
</script>