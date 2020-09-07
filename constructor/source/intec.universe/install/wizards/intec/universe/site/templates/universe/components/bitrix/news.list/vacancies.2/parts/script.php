<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use intec\core\helpers\JavaScript;

/**
 * @var array $arVisual
 * @var string $sTemplateId
 * @var array $arResult
 */


?>
<script type="text/javascript">
    (function ($, api) {
        var root = $(<?= JavaScript::toObject('#'.$sTemplateId) ?>);
        var items = $('[data-role="item"]', root);
        var active = null;
        var duration = 300;

        items.each(function () {
            var self = this;
            var item = $(this);
            var head = $('[data-role="item.head"]', item);

            head.on('click', function () {
                if (active === self) {
                    close(self);
                    active = null;
                } else {
                    open(self);
                }
            });
        });

        var open = function (item) {
            if (active === item)
                return;

            var block;
            var height;
            var head;

            close(active);
            active = item;

            item = $(item);
            head = $('[data-role="item.head"]', item);
            head.addClass('intec-cl-background');
            head.attr('data-active', 'true');
            block = $('[data-role="item.body"]', item);
            height = block.css({
                'height': 'auto'
            }).height();
            block.css({'height': 0}).stop().animate({'height': height + 'px'}, duration, function () {
                block.css('height', 'auto');
            });
        };

        var close = function (item) {
            var block;
            var head;

            item = $(item);
            head = $('[data-role="item.head"]', item);
            head.removeClass('intec-cl-background');
            head.attr('data-active', 'false');
            block = $('[data-role="item.body"]', item);
            block.stop().animate({'height': 0}, duration, function () {
                block.css({
                    'height': '0'
                });
            });
        };

        <?php if ($arVisual['FORM']['SHOW'] == 'Y' && !empty($arParams['FORM_SUMMARY_ID'])) { ?>
        root.find('[data-action=form]').click(function () {
            var item = $(this);
            var name = item.data('name');
            var fields = {};

            <?php if (!empty($arResult['FORM']['PROPERTY_SUMMARY_VACANCY'])) { ?>
            fields[<?= JavaScript::toObject($arResult['FORM']['PROPERTY_SUMMARY_VACANCY']) ?>] = name;
            <?php } ?>

            universe.forms.show({
                'id': <?= JavaScript::toObject($arResult['FORM']['ID']) ?>,
                'template': '.default',
                'parameters': {
                    'AJAX_OPTION_ADDITIONAL': <?= JavaScript::toObject($sTemplateId.'_FORM') ?>,
                    'CONSENT_URL': <?= JavaScript::toObject($arResult['FORM']['CONSENT_URL']) ?>
                },
                'settings': {
                    'title': <?= JavaScript::toObject(GetMessage('C_NEWS_LIST_VACANCIES_2_FORM_SUMMARY')) ?>
                },
                'fields': fields
            });

            if (window.yandex && window.yandex.metrika) {
                window.yandex.metrika.reachGoal('forms.open');
                window.yandex.metrika.reachGoal(<?= JavaScript::toObject('forms.'.$arResult['FORM']['ID'].'.open') ?>);
            }
        });
        <?php } ?>
    })(jQuery, intec);
</script>