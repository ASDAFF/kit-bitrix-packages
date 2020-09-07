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
            var content = $('[data-role="item.content"]', self);
            var button = $('[data-role="item.button"]', self);
            var text = {
                'show': button.text(),
                'hide': '<?= Loc::getMessage('C_MAIN_ADVANTAGES_TEMPLATE_26_TEMPLATE_BUTTON_TEXT_HIDE_DEFAULT') ?>'
            };

            button.on('click', function () {
                var state = content.attr('data-expanded') === 'true';

                button.css('pointer-events', 'none');

                if (state) {
                    content.attr('data-expanded', 'false');
                    button.text(text.show);

                    content.animate({'height': 0}, 400, function () {
                        content.css({
                            'height': '',
                            'display': 'none'
                        });

                        button.css('pointer-events', '');
                    })
                } else {
                    var height;

                    content.css('display', 'block');

                    height = content.height();

                    content.css('height', 0);
                    button.text(text.hide);

                    setTimeout(function () {
                        content.attr('data-expanded', 'true');
                        content.animate({'height': height}, 400, function () {
                            content.css('height', '');
                            button.css('pointer-events', '');
                        });
                    }, 10);
                }
            });
        });
    })();
</script>
