<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

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

        containers = $('[data-role="items"]', root);
        containers.each(function () {
            var container = $(this);
            var children = container.find(items);
            var actions = {};

            actions.expand = function (child) {
                var content = $('[data-role="item.content"]', child);

                child.attr('data-expanded', 'true');
                content.children().stop().fadeIn(800);
                content.stop().slideDown(500);
            };

            actions.collapse = function (child) {
                var content = $('[data-role="item.content"]', child);

                child.attr('data-expanded', 'false');
                content.children().stop().fadeOut(300);
                content.stop().slideUp(500);
            };

            children.each(function () {
                var child = $(this);
                var title = $('[data-role="item.title"]', child);
                var content = $('[data-role="item.content"]', child);

                child.expand = function () {
                    children.each(function () {
                        actions.collapse($(this));
                    });

                    actions.expand(child);
                };

                child.collapse = function () {
                    actions.collapse(child);
                };

                child.toggle = function () {
                    if (content.is(':hidden')) {
                        child.expand();
                    } else {
                        child.collapse();
                    }
                };

                title.on('click', function () {
                    child.toggle();
                });
            });
        });
    })(jQuery, intec);
</script>
