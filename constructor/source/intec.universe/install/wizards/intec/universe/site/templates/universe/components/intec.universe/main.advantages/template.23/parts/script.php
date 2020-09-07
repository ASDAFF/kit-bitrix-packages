<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;
use intec\core\helpers\JavaScript;

/**
 * @var string $sTemplateId
 */

?>
<script type="text/javascript">
    (function () {
        var root = $(<?= JavaScript::toObject('#'.$sTemplateId) ?>);
        var container = $('[data-role="items"]', root);
        var items = $('[data-role="item"]', container);
        var button = $('[data-role="button"]', root);
        var state = container.data('state');
        var collapsedHeight;

        container.setState = function () {
            if (state === 'collapsed') {
                state = 'expanded';
                container.attr('data-state', state);
            } else if (state === 'expanded') {
                state = 'collapsed';
                container.attr('data-state', state);
            }
        };
        container.setCollapsedHeight = function () {
            var animatedItems = items.filter('[data-action="hide"]');
            animatedItems.css('display', 'none');

            container.css('height', 'auto');
            collapsedHeight = container.height();
        };
        container.getExpandedHeight = function () {
            var expandedHeight;

            container.css('height', 'auto');
            expandedHeight = container.height();
            container.css('height', collapsedHeight);

            return expandedHeight;
        };
        container.animateItems = function () {
            var animatedItems = items.filter('[data-action="hide"]');

            if (state === 'collapsed') {
                animatedItems.animate({'opacity': 0}, 500, function () {
                    animatedItems.css('display', 'none');
                });
            } else if (state === 'expanded') {
                animatedItems.css('display', 'block');
                animatedItems.animate({'opacity': 1}, 500);
            }
        };
        container.animateExpand = function () {
            container.animate({'height': container.getExpandedHeight()}, 500, function () {
                container.css('height', '');
            });
        };
        container.animateCollapse = function () {
            container.animate({'height': collapsedHeight}, 500);
        };
        container.adapt = function () {
            var windowWidth = $(window).width();

            container.setCollapsedHeight();

            if (state === 'collapsed') {
                container.css('height', collapsedHeight);
                items.filter('[data-action="hide"]').css('display', 'none');
            }
        };
        container.change = function () {
            container.setState();
            button.setText();
            container.animateItems();

            if (state === 'collapsed') {
                container.animateCollapse();

            } else if (state === 'expanded') {
                container.animateExpand();
            }
        };
        button.setText = function () {
            if (state === 'collapsed') {
                button.text('<?= Loc::getMessage('C_MAIN_ADVANTAGES_TEMPLATE_23_BUTTON_SHOW') ?>');
            } else if (state === 'expanded') {
                button.text('<?= Loc::getMessage('C_MAIN_ADVANTAGES_TEMPLATE_23_BUTTON_HIDE') ?>');
            }
        };

        $(function () {
            container.adapt();
            button.setText();
            button.on('click', container.change);
            $(window).on('resize', container.adapt);
        });
    })();
</script>