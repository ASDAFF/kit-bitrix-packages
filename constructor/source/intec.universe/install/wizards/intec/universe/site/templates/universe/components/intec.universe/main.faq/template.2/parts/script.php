<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die() ?>
<?php

use intec\core\helpers\JavaScript;

/**
 * @var string $sTemplateId
 */

?>
<script>
    (function ($, api) {
        var root = $(<?= JavaScript::toObject('#'.$sTemplateId) ?>);
        var containers = $('[data-role="items"]', root);
        var items = $('[data-role="item"]', containers);

        items.each(function () {
            var item = $(this);
            var button = $('[data-role="item.toggle"]', item);
            var content = $('[data-role="item.content"]', item);
            var state = false;

            button.on('click', function () {
                if (!state) {
                    content.stop().slideDown({
                        'duration': 500,
                        'start': function () {
                            content.children().animate({'opacity': '1'}, 1000);
                        }
                    });
                } else {
                    content.stop().slideUp({
                        'duration': 500,
                        'start': function () {
                            content.children().animate({'opacity': '0'}, 300);
                        }
                    });
                }

                state = !state;
                item.attr('data-expanded', state ? 'true' : 'false');
            });
        });
    })(jQuery, intec);
</script>
