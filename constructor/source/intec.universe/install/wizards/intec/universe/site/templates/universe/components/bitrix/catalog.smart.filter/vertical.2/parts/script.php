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
    var <?= $arResult['JS_FILTER_PARAMS']['variable'] ?> = new JCSmartFilterVertical2(
        <?= JavaScript::toObject($arResult['FORM_ACTION']) ?>,
        <?= JavaScript::toObject($arVisual['VIEW']) ?>,
        <?= JavaScript::toObject($arResult['JS_FILTER_PARAMS']) ?>
    );

    (function ($, api) {
        var root = $(<?= JavaScript::toObject('#'.$sTemplateId) ?>);
        var filter = {
            'button': $('[data-role="filter.toggle"]', root),
            'content': $('[data-role="filter.content"]', root),
            'form': $('[data-role="filter.form"]', root),
            'state': false,
            'toggle': null
        };

        filter.state = filter.content.attr('data-expanded') === 'true';

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

        filter.toggle = function () {
            var height = {
                'current': null,
                'full': null
            };

            if (filter.state === true) {
                filter.state = false;
                filter.content.attr('data-expanded', filter.state ? 'true' : 'false');

                filter.content.stop().animate({'height': '0'}, 300, function () {
                    filter.content.css('display', 'none');
                });
            } else if (filter.state === false) {
                filter.state = true;
                filter.content.attr('data-expanded', filter.state ? 'true' : 'false');

                filter.content.stop().css('display', '');
                height.current = filter.content.height();

                filter.content.css('height', '');
                height.full = filter.content.height();

                filter.content.css('height', height.current).animate({'height': height.full}, 300, function () {
                    filter.content.css('height', '');
                });
            }
        };

        filter.button.on('click', function () {
            filter.toggle();
        });

        popup.button.on('click', function () {
            popup.close()
        });
    })(jQuery, intec);
</script>
