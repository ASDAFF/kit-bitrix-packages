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
        var container = $('[data-role="container"]', root);
        var items = $('[data-role="item"]', root).filter('[data-action="hide"]');
        var button = $('[data-role="button"]', root);
        var height = {
            'start': null,
            'end': null
        };
        var messages = {
            'show': '<?= Loc::getMessage('C_MAIN_ADVANTAGES_TEMPLATE_27_TEMPLATE_BUTTON_SHOW') ?>',
            'hide': '<?= Loc::getMessage('C_MAIN_ADVANTAGES_TEMPLATE_27_TEMPLATE_BUTTON_HIDE') ?>'
        };

        button.on('click', function () {
            var expanded = container.attr('data-expanded') === 'true';

            button.css('pointer-events', 'none');

            if (!expanded) {
                height.start = container.height();

                items.css('display', '');
                height.end = container.height();

                container.css('height', height.start);

                setTimeout(function () {
                    container.attr('data-expanded', 'true');
                    container.css('height', height.end);
                    button.text(messages.hide);

                    setTimeout(function () {
                        container.css('height', '');
                        button.css('pointer-events', '');
                    }, 410);
                }, 10);
            } else {
                height.start = container.height();

                items.css('display', 'none');
                height.end = container.height();
                items.css('display', '');

                container.css('height', height.start);

                setTimeout(function () {
                    container.attr('data-expanded', 'false');
                    container.css('height', height.end);
                    button.text(messages.show);

                    setTimeout(function () {
                        items.css('display', 'none');
                        container.css('height', '');
                        button.css('pointer-events', '');
                    }, 410);
                }, 10);
            }
        });
    })();
</script>
