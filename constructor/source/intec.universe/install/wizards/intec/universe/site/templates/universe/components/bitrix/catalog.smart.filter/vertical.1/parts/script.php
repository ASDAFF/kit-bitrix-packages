<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;
use intec\core\helpers\JavaScript;

/**
 * @var array $arResult
 * @var array $arVisual
 */

$arResult['JS_FILTER_PARAMS']['id'] = [
    'setFilter' => 'set_filter',
    'delFilter' => 'del_filter'
];

$arResult['JS_FILTER_PARAMS']['variable'] = 'smartFilter'.($arVisual['MOBILE'] ? 'Mobile' : null);

if ($arVisual['MOBILE'])
    foreach ($arResult['JS_FILTER_PARAMS']['id'] as $sKey => $sValue)
        $arResult['JS_FILTER_PARAMS']['id'][$sKey] = $sValue.'_mobile';

?>
<script>
    var <?= $arResult['JS_FILTER_PARAMS']['variable'] ?> = new JCSmartFilterVertical1(
        <?= JavaScript::toObject($arResult['FORM_ACTION']) ?>,
        <?= JavaScript::toObject($arVisual['VIEW']) ?>,
        <?= JavaScript::toObject($arResult['JS_FILTER_PARAMS']) ?>
    );

    (function ($, api) {
        var root = $(<?= JavaScript::toObject('#'.$sTemplateId) ?>);
        var filter = {
            'button': $('[data-role="filter.toggle"]', root),
            'body': $('[data-role="filter"]', root),
            'state': $('[data-role="filter"]', root).data('expanded'),
            'toggle': null
        };
        var popup = {
            'button': $('[data-role="popup.close"]', root),
            'close': null
        };

        popup.close = function () {
            var container = popup.button.closest('[data-role="popup"]');

            container.animate({'opacity': '0'}, 200, function () {
                container.css({'opacity': '', 'display': 'none'});
            });
        };

        (function () {
            filter.toggle = function () {
                var title = $('span', filter.button);
                var height = {
                    'current': null,
                    'full': null
                };

                if (filter.state === true) {
                    filter.state = false;
                    filter.body.attr('data-expanded', filter.state);

                    filter.body.stop().animate({'height': '0'}, 300, function () {
                        filter.body.css('display', 'none');
                    });
                    title.stop().animate({'opacity': '0'}, 100, function () {
                        title.html("<?= Loc::getMessage('C_CATALOG_SMART_FILTER_VERTICAL_1_TOGGLE_DOWN') ?>");
                        title.animate({'opacity': '1'}, 100);
                    });
                } else if (filter.state === false) {
                    filter.state = true;
                    filter.body.attr('data-expanded', filter.state);

                    filter.body.stop().css('display', '');
                    height.current = filter.body.height();

                    filter.body.css('height', '');
                    height.full = filter.body.height();

                    filter.body.css('height', height.current).animate({'height': height.full}, 300, function () {
                        filter.body.css('height', '');
                    });
                    title.stop().animate({'opacity': '0'}, 100, function () {
                        title.html("<?= Loc::getMessage('C_CATALOG_SMART_FILTER_VERTICAL_1_TOGGLE_UP') ?>");
                        title.animate({'opacity': '1'}, 100);
                    });
                }
            };
        })();

        filter.button.on('click', function () {
            filter.toggle();
        });

        popup.button.on('click', function () {
            popup.close()
        });
    })(jQuery, intec);
</script>
