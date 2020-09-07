<?php if (!defined('B_PROLOG_INCLUDED') && B_PROLOG_INCLUDED !== true) die();

use intec\core\helpers\JavaScript;

/**
 * @var string $sTemplateId
 */

?>
<script type="text/javascript">
    (function () {
        var root = $(<?= JavaScript::toObject('#'.$sTemplateId) ?>);
        var tabs = $('[data-role="element.tabs"]', root);

        tabs.items = $('[data-role="element.tabs.item"]', tabs);
        tabs.content = $('[data-role="element.tabs.content.item"]', tabs);

        tabs.items.each(function () {
            var self = $(this);

            self.on('click', function () {
                var active = self.attr('data-active') === 'true';

                if (!active) {
                    var id = self.attr('data-id');
                    var showId = tabs.content.filter('[data-id="' + id + '"]');

                    tabs.items.attr('data-active', 'false')
                        .css('pointer-events', 'none')
                        .removeClass('intec-cl-background intec-cl-background-light-hover');

                    self.attr('data-active', 'true')
                        .addClass('intec-cl-background intec-cl-background-light-hover');

                    tabs.content.attr('data-active', 'false');

                    setTimeout(function () {
                        tabs.content.css('display', 'none');
                        showId.css('display', 'block');

                        setTimeout(function () {
                            showId.attr('data-active', 'true');

                            setTimeout(function () {
                                tabs.items.css('pointer-events', '');
                            }, 310);
                        }, 15);
                    }, 305);
                }
            });
        });
    })();
</script>