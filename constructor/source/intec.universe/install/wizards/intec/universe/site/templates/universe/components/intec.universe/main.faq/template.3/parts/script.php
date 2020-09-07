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
        var items = $('[data-role="item"]', root);

        items.each(function () {
            var self = $(this);
            var expanded = self.attr('data-expanded') === 'true';
            var button = $('[data-role="item.button"]', self);
            var content = $('[data-role="item.content"]', self);

            if (!expanded) {
                content.css('display', 'none');
            }

            button.on('click', function () {
                var expanded = self.attr('data-expanded') === 'true';

                button.css('pointer-events', 'none');

                if (expanded) {
                    self.attr('data-expanded', 'false');
                    content.animate({'height': 0, 'opacity': 0}, 350, function () {
                        content.css({'display': 'none', 'height': 'auto'});
                        button.css('pointer-events', 'all');
                    });
                } else {
                    var contentHeight;

                    self.attr('data-expanded', 'true');

                    content.css('display', 'block');
                    contentHeight = content.height();
                    content.css('height', 0);
                    content.animate({'height': contentHeight, 'opacity': 1}, 350, function () {
                        content.css('height', 'auto');
                        button.css('pointer-events', 'all');
                    });
                }
            })
        });

        var container = $('[data-role="container"]', root);
        var button = $('[data-role="button"]', root);
        var hideItems = items.filter('[data-action="hide"]');
        var height = {
            'start': null,
            'end': null
        };
        var messages = {
            'show': '<?= Loc::getMessage('C_MAIN_FAQ_TEMPLATE_3_TEMPLATE_BUTTON_SHOW') ?>',
            'hide': '<?= Loc::getMessage('C_MAIN_FAQ_TEMPLATE_3_TEMPLATE_BUTTON_HIDE') ?>',
        };

        button.on('click', function () {
            var expanded = container.attr('data-expanded') === 'true';

            height.start = container.height();
            button.css('pointer-events', 'none');

            if (expanded) {
                hideItems.css('display', 'none');
                height.end = container.height();
                hideItems.css('display', '');

                container.css({
                    'height': height.start,
                    'overflow': 'hidden'
                });
                button.text(messages.show);

                setTimeout(function () {
                    container.css('height', height.end)
                        .attr('data-expanded', 'false');;

                    setTimeout(function () {
                        container.css({
                            'height': '',
                            'overflow': ''
                        });
                        hideItems.css('display', 'none');
                        button.css('pointer-events', '');
                    }, 410);
                }, 10);
            } else {
                hideItems.css('display', '');
                height.end = container.height();
                hideItems.css('display', 'none');

                container.css({
                    'height': height.start,
                    'overflow': 'hidden'
                });
                hideItems.css('display', '');
                button.text(messages.hide);

                setTimeout(function () {
                    container.css('height', height.end)
                        .attr('data-expanded', 'true');;

                    setTimeout(function () {
                        container.css({
                            'height': '',
                            'overflow': ''
                        });
                        button.css('pointer-events', '');
                    }, 410);
                }, 10);
            }

            console.log(height);
        });

    })();
</script>
