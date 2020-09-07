<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use intec\core\helpers\JavaScript;

/**
 * @var string $sTemplateId
 */

?>
<script type="text/javascript">
    (function () {
        var root = $(<?= JavaScript::toObject('#'.$sTemplateId) ?>);
        var tabs = $('[data-role="tab"]', root);
        var items = $('[data-role="item"]', root);

        tabs.each(function () {
            var self = $(this);

            self.on('click', function () {
                var active = self.attr('data-active') === 'true';

                if (!active) {
                    var type = self.attr('data-type');
                    var itemsActive = items.filter('[data-active="true"]');
                    var itemsToActivate;

                    tabs.css('pointer-events', 'none');

                    if (type !== 'all')
                        itemsToActivate = items.filter('[data-type="' + type + '"]');
                    else
                        itemsToActivate = items;

                    tabs.attr('data-active', 'false')
                        .removeClass('active');

                    self.attr('data-active', 'true')
                        .addClass('active');

                    itemsActive.attr('data-active', 'false');

                    setTimeout(function () {
                        itemsActive.css('display', 'none');
                        itemsToActivate.css('display', '');

                        setTimeout(function () {
                            itemsToActivate.attr('data-active', 'true');

                            setTimeout(tabs.css('pointer-events', 'all'), 50);
                        }, 50);
                    }, 400);
                }
            })
        })
    })();
</script>
