<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use intec\core\helpers\JavaScript;

/**
 * @var string $sTemplateId
 */

?>
<script type="text/javascript">
    (function () {
        var root = $(<?= JavaScript::toObject('#'.$sTemplateId) ?>);
        var frame = $('[data-role="view"]', root);
        var container = $('[data-role="items"]', root);
        var items = $('[data-role="item"]', container);

        container.scrollbar();

        items.each(function () {
            var self = $(this);

            self.on('click', function () {
                var id = self.attr('data-id');

                items.attr('data-active', 'false');
                items.removeClass('intec-cl-text');

                self.attr('data-active', 'true');
                self.addClass('intec-cl-text');

                frame.attr('src', 'https://www.youtube.com/embed/' + id + '?autoplay=1');
            });
        });

        (function () {
            items.eq(0)
                .attr('data-active', 'true')
                .addClass('intec-cl-text');
        })();
    })();
</script>