<?php if (!defined('B_PROLOG_INCLUDED') && B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;
use intec\core\helpers\JavaScript;

/**
 * @var string $sTemplateId
 */

?>
<script type="text/javascript">
    (function ($, api) {
        var root = $(<?= JavaScript::toObject('#'.$sTemplateId) ?>);
        var children = $('[data-role="children"]', root);
        var messages = {
            'show': '<?= Loc::getMessage('C_CATALOG_SECTION_LIST_CATALOG_TILE_4_TEMPLATE_BUTTON_SHOW') ?>',
            'hide': '<?= Loc::getMessage('C_CATALOG_SECTION_LIST_CATALOG_TILE_4_TEMPLATE_BUTTON_HIDE') ?>'
        };

        children.each(function () {
            var self = $(this);
            var items = $('[data-role="hidden"]', self);
            var button = self.siblings('[data-role="button"]');

            button.on('click', function () {
                var clicked = $(this);
                var state = self.attr('data-expanded') === 'true';
                var height = {
                    'current': self.height(),
                    'modified': null
                };

                clicked.message = $('[data-role="button.text"]', clicked);

                self.css('pointer-events', 'none');
                clicked.css('pointer-events', 'none');

                if (state) {
                    items.css('display', 'none');
                    height.modified = self.height();
                    items.css('display', '');

                    self.css('height', height.current);

                    setTimeout(function () {
                        self.attr('data-expanded', !state);
                        button.attr('data-expanded', !state);
                        self.css('height', height.modified);

                        setTimeout(function () {
                            self.css({
                                'height': '',
                                'pointer-events': ''
                            });
                            items.css('display', 'none');
                            clicked.css('pointer-events', '');
                            clicked.message.html(messages.show);
                        }, 310);
                    }, 10);
                } else {
                    items.css('display', '');
                    height.modified = self.height();
                    items.css('display', 'none');

                    self.css('height', height.current);
                    items.css('display', '');

                    setTimeout(function () {
                        self.attr('data-expanded', !state);
                        button.attr('data-expanded', !state);
                        self.css('height', height.modified);

                        setTimeout(function () {
                            self.css({
                                'height': '',
                                'pointer-events': ''
                            });
                            clicked.css('pointer-events', '');
                            clicked.message.html(messages.hide);
                        }, 310);
                    }, 10);
                }
            });
        });
    })(jQuery, intec);
</script>