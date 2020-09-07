<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use intec\core\helpers\JavaScript;

/**
 * @var string $sTemplateId
 */

?>
<script type="text/javascript">
    (function ($, api) {
        var root = $(<?= JavaScript::toObject('#'.$sTemplateId) ?>);
        var contacts = $('[data-role="contacts"]', root);

        var initialize;
        var loader;
        var move;
        var map;

        initialize = function () {

            if (!api.isObject(window.maps))
                return false;

            map = window.maps[<?= JavaScript::toObject($arParams['MAP_ID']) ?>];

            if (map == null)
                return false;

            contacts.items = $('[data-role="contacts.item"]', contacts);

            contacts.items.each(function () {
                var contact = $(this);

                contact.on('click', function () {
                    var activeContact = contacts.items.filter('[data-state="enabled"]', contacts);

                    activeContact.attr('data-state', 'disabled');
                    contact.attr('data-state', 'enabled');
                    activeContact.removeClass('intec-cl-background');
                    contact.addClass('intec-cl-background');

                    move(
                        contact.data('latitude'),
                        contact.data('longitude')
                    );

                });
            });

            return true;
        };

        move = function (latitude, longitude) {
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