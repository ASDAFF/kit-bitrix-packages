<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;
use intec\core\helpers\JavaScript;

/**
 * @var array $arResult
 * @var array $arVisual
 */

$arResult['JS_FILTER_PARAMS']['variable'] = 'smartFilter';

$arResult['JS_FILTER_PARAMS']['id'] = [
    'setFilter' => 'set_filter',
    'delFilter' => 'del_filter'
];

?>
<script>
    var <?= $arResult['JS_FILTER_PARAMS']['variable'] ?> = new JCSmartFilter(
        <?= JavaScript::toObject($arResult['FORM_ACTION']) ?>,
        <?= JavaScript::toObject($arVisual['VIEW']) ?>,
        <?= JavaScript::toObject($arResult['JS_FILTER_PARAMS'])?>
    );

    (function ($, api) {

        var root = $(<?= JavaScript::toObject('#'.$sTemplateId) ?>);
        var filter = {
            'container': $('[data-role="filter.container"]', root),
            'button': $('[data-role="filter.toggle"]', root),
            'body': $('[data-role="filter"]', root),
            'state': null,
            'toggle': null
        };

        (function () {
            var state = filter.container.data('expanded');

            filter.state = state;
            console.log(filter.state);
            filter.toggle = function () {
                var title = $('span', filter.button);
                var height = {
                    'current': null,
                    'full': null
                };

                if (filter.state == true) {
                    filter.state = false;

                    filter.body.stop().animate({'height': '40px'}, 500);
                    title.stop().animate({'opacity': '0'}, 100, function () {
                        title.html("<?= Loc::getMessage('FILTER_TEMP_HORIZONTAL_TOGGLE_DOWN') ?>");
                        title.animate({'opacity': '1'}, 100);
                    });

                    filter.container.attr('data-expanded', filter.state);
                } else if (filter.state === false) {
                    filter.state = true;

                    height.current = filter.body.height();

                    filter.body.css('height', '');
                    height.full = filter.body.height();
                    console.log(height);
                    filter.body.css('height', height.current).animate({'height': height.full}, 500, function () {
                        filter.body.css('height', '');
                    });

                    title.stop().animate({'opacity': '0'}, 100, function () {
                        title.html("<?= Loc::getMessage('FILTER_TEMP_HORIZONTAL_TOGGLE_UP') ?>");
                        title.animate({'opacity': '1'}, 100);
                    });

                    filter.container.attr('data-expanded', filter.state);
                }
            };
        })();

        filter.button.on('click', function () {
            filter.toggle();
        });
    })(jQuery, intec);
</script>