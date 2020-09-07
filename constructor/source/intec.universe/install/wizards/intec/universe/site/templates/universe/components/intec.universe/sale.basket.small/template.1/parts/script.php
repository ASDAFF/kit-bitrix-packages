<?php

use intec\core\helpers\ArrayHelper;
use intec\core\helpers\JavaScript;

/**
 * @var array $arParams
 * @var array $arResult
 * @var string $sTemplate
 */

?>
<script type="text/javascript">
    (function ($, api) {
        var root = $(<?= JavaScript::toObject('#'.$sTemplateId) ?>);
        var switches = $('[data-role="switches"]', root);
        var products = $('[data-role="product"]', root);
        var buttons = $('[data-role="button"]', root);
        var tabs;

        tabs = (function () {
            var tabs = $('[data-role="tabs"]', root);
            var list = $('[data-tab]', tabs);
            var current = null;

            tabs.open = function (code, animate) {
                var tab;
                var width = {};

                tab = list.filter('[data-tab="' + code + '"]');

                if (tab.length !== 1)
                    return false;

                width.current = tabs.width();
                current = code;

                list.css({
                    'display': '',
                    'width': ''
                }).attr('data-active', 'false');

                tab.css('display', 'block').attr('data-active', 'true');
                width.new = tab.width();

                if (animate) {
                    tab.css('width', width.current).stop().animate({
                        'width': width.new
                    }, 500, function () {
                        tab.css('width', '');
                    });
                } else {
                    tab.css('width', '');
                }

                return true;
            };

            tabs.close = function (animate) {
                var tab;

                if (current === null)
                    return;

                tab = list.filter('[data-tab="' + current + '"]');
                current = null;

                if (tab.length !== 1)
                    return;

                if (animate) {
                    tab.stop().animate({
                        'width': 0
                    }, 500, function () {
                        list.attr('data-active', 'false');
                        tab.css({
                            'width': '',
                            'display': ''
                        });
                    });
                } else {
                    list.attr('data-active', 'false');
                    tab.css('display', '');
                }
            };

            tabs.switch = function (code, animate) {
                if (code === current) {
                    tabs.close(animate);
                } else {
                    tabs.open(code, animate);
                }
            };

            tabs.getCurrent = function () {
                return current;
            };

            return tabs;
        })();

        switches.children('[data-tab]').on('click', function () {
            var self = $(this);
            var tab = self.data('tab');

            tabs.switch(tab, true);
        });

        buttons.on('click', function () {
            var button = $(this);
            var action = button.data('action');

            if (action === 'basket.clear') {
                universe.basket.clear({'basket': 'Y'});
            } else if (action === 'delayed.clear') {
                universe.basket.clear({'delay': 'Y'});
            } else if (action === 'close') {
                tabs.close(true);
            } else if (action === 'form') {
                universe.forms.show(<?= JavaScript::toObject([
                    'id' => $arResult['FORM']['ID'],
                    'template' => '.default',
                    'parameters' => [
                        'AJAX_OPTION_ADDITIONAL' => $sTemplateId.'-FORM-POPUP',
                        'CONSENT_URL' => $arResult['URL']['CONSENT']
                    ],
                    'settings' => [
                        'title' => $arResult['FORM']['TITLE']
                    ]
                ]) ?>);

                if (window.yandex && window.yandex.metrika) {
                    window.yandex.metrika.reachGoal('forms.open');
                    window.yandex.metrika.reachGoal(<?= JavaScript::toObject('forms.'.$arForm['PARAMETERS']['id'].'.open') ?>);
                }
            }
        });

        <?php if ($arResult['FORM']['SHOW']) { ?>
            universe.forms.get(<?= JavaScript::toObject([
                'id' => $arResult['FORM']['ID'],
                'template' => '.default',
                'parameters' => [
                    'AJAX_OPTION_ADDITIONAL' => $sTemplateId.'-FORM',
                    'CONSENT_URL' => $arResult['URL']['CONSENT']
                ]
            ]) ?>, function (response) {
                tabs.find('[data-role="area"][data-area="form"]').html(response);
            });
        <?php } ?>

        $(function () {
            var data;
            var update;

            data = <?= JavaScript::toObject(array(
                'component' => $component->getName(),
                'template' => $this->getName(),
                'parameters' => ArrayHelper::merge($arParams, [
                        'AJAX_MODE' => 'N'
                ])
            )) ?>;

            update = function (tab) {
                if (update.disabled)
                    return;

                update.disabled = true;

                if (tab === true || !api.isDeclared(tab)) {
                    tab = tabs.getCurrent();
                } else if (tab === false) {
                    tab = null;
                }

                data.parameters['TAB'] = tab;

                universe.components.get(data, function (result) {
                    root.replaceWith(result);
                });
            };

            update.disabled = false;
            universe.basket.once('update', function() { update(); });
            universe.compare.once('update', function() { update(); });

            products.each(function () {
                var product = $(this);
                var id = product.data('id');
                var counter = $('[data-role="counter"]', product);
                var buttons = $('[data-role="button"]', product);

                counter.control('numeric', {}, function(configuration, instance) {
                    var timeout;

                    if (instance !== null) {
                        instance.on('change', function (event, value) {
                            clearTimeout(timeout);
                            timeout = setTimeout(function() {
                                universe.basket.setQuantity({
                                    'id': id,
                                    'quantity': value
                                });
                            }, 500);
                        });
                    }
                });

                buttons.on('click', function () {
                    var button = $(this);
                    var action = button.data('action');
                    var data = {'id': id};

                    if (action === 'product.add') {
                        data.delay = 'N';
                        universe.basket.add(data);
                    } else if (action === 'product.delay') {
                        data.delay = 'Y';
                        universe.basket.add(data);
                    } else if (action === 'product.remove') {
                        universe.basket.remove(data);
                    }
                });
            });

            <?php if ($arResult['AUTO']) { ?>
                universe.basket.once('add', function(event, data) {
                    var tab = tabs.getCurrent();

                    if (tab === null)
                        tab = 'basket';

                    if (data.delay !== 'Y')
                        update(tab);
                });
            <?php } ?>
        });

        <?php if (!empty($arResult['TAB'])) { ?>
            tabs.open(<?= JavaScript::toObject($arResult['TAB']) ?>, false);
        <?php } ?>
    })(jQuery, intec)
</script>