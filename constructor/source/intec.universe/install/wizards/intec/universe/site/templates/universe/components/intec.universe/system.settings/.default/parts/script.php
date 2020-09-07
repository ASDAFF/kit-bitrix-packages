<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die() ?>
<?php

use intec\core\helpers\JavaScript;

/**
 * @var array $arParams
 * @var array $arResult
 * @var string $sTemplateId
 */

?>
<script type="text/javascript">
    (function ($, api) {
        var root = $(<?= JavaScript::toObject('#'.$sTemplateId) ?>);
        var buttons = $('[data-role="buttons"] [data-role="button"]', root);
        var scrollbar = $('[data-role="scrollbar"]', root);
        var properties = $('[data-role="properties"] [data-role="property"]', root);
        var overlay = $('[data-role="overlay"]', root);
        var tabs = $('[data-tab]', root);
        var contentTabs = $('[data-role="content-tab"]', root);
        var form;
        var inputs;
        var views;

        buttons.settings = buttons.filter('[data-show="settings"]');
        buttons.preferences = buttons.filter('[data-show="preferences"]');
        buttons.apply = buttons.filter('[data-action="apply"]');
        form = $('[data-role="form"]', root);
        inputs = properties.find('[data-role="property.input"]');

        contentTabs.settings = contentTabs.filter('[data-show="settings"]');
        contentTabs.preferences = contentTabs.filter('[data-show="preferences"]');

        views = {};
        views.blocks = properties.filter('[data-view="blocks"]');
        views.boolean = properties.filter('[data-view="boolean"]');
        views.color = properties.filter('[data-view="color"]');

        views.blocks.each(function () {
            var view = $(this);
            var blocks = $('[data-role="property.blocks"] [data-role="property.block"]', view);

            blocks.each(function () {
                var block = $(this);
                var inputs = $('[data-role="property.input"]', block);
                var templates = $('[data-role="property.block.templates"] [data-role="property.block.template"]', block);

                inputs.active = inputs.filter('[data-type="active"]');
                inputs.template = inputs.filter('[data-type="template"]');
                templates.container = $('[data-role="property.block.templates"]', block);

                if (!inputs.active.prop('checked'))
                    templates.container.hide();

                new api.ui.controls.switch(inputs.active, {
                    'classes': {
                        'control': 'api-ui-switch-control'
                    }
                });

                inputs.active.on('change', function () {
                    templates.container.stop().slideToggle(600);
                });
            })
        });

        views.boolean.each(function () {
            var view = $(this);
            var input = $('[data-role="property.input"]', view);

            new api.ui.controls.switch(input, {
                'classes': {
                    'control': 'api-ui-switch-control api-ui-lg'
                }
            });
        });

        views.color.each(function () {
            var view = $(this);
            var input = $('[data-role="property.input"]', view);
            var values = $('[data-role="property.values"] [data-role="property.value"]', view);

            values.custom = values.filter('[data-value="custom"]');
            values.custom.background = values.custom.find('[data-role="property.value.background"]');
            values.set = function (node, value) {
                values.custom.background.css('background', value);
                values.attr('data-active', 'false');
                node.attr('data-active', 'true');
                input.val(value).trigger('change');
            };

            values.on('click', function () {
                var node = $(this);
                var value = node.data('value');

                if (value != null && value !== 'custom')
                    values.set(node, value);
            });

            values.custom.ColorPicker({
                'color': input.val(),
                'onSubmit': function (hsb, hex, rgb) {
                    var node = values.custom;
                    var value = '#' + hex;

                    values.set(node, value);
                }
            });
        });

        root.expanded = root.data('expanded');
        root.open = function (animate) {
            if (root.expanded)
                return;

            root.expanded = true;
            overlay.css('visibility', 'visible');
            root.attr('data-expanded', 'true');

            if (animate) {
                form.css('visibility', 'visible');
                form.stop().animate({
                    'left': 0
                }, 650);
            } else {
                form.stop().css({
                    'left': 0,
                    'visibility': 'visible'
                });
            }
        };

        root.close = function (animate) {
            if (!root.expanded)
                return;

            root.expanded = false;
            root.attr('data-expanded', 'false');

            if (animate) {
                var left = {
                    'current': form.css('left'),
                    'new': form.css('left', '').css('left')
                };

                form.css('left', left.current);
                form.stop().animate({
                    'left': left.new
                }, 650, function () {
                    overlay.css('visibility', '');
                    form.css({
                        'left': '',
                        'visibility': ''
                    });
                });
            } else {
                overlay.css('visibility', '');
                form.stop().css({
                    'left': '',
                    'visibility': ''
                });
            }
        };

        root.toggle = function (animate) {
            if (root.expanded) {
                root.close(animate);
            } else {
                root.open(animate);
            }
        };

        if (root.expanded) {
            root.expanded = false;
            root.open();
        }

        scrollbar.scrollbar();

        tabs.on('click', function () {
            var tab = $(this);
            var code = tab.data('tab');

            root.find('input[name="category"]').val(code);
        });

        overlay.on('click', function () {
            root.close(true);
            buttons.removeClass("active");
        });

        buttons.settings.on('click', function (animate) {
            if (root.expanded && buttons.activeTab === 'settings') {
                root.close(animate);
                buttons.settings.removeClass("active");
            } else {
                root.open(animate);
                contentTabs.settings.addClass("active");
                contentTabs.preferences.removeClass("active");
                buttons.settings.addClass("active");
                buttons.preferences.removeClass("active");
                buttons.activeTab = 'settings';
            }
        });

        buttons.preferences.on('click', function (animate) {
            if (root.expanded && buttons.activeTab === 'preferences') {
                root.close(animate);
                buttons.preferences.removeClass("active");
            } else {
                root.open(animate);
                contentTabs.preferences.addClass("active");
                contentTabs.settings.removeClass("active");
                buttons.preferences.addClass("active");
                buttons.settings.removeClass("active");
                buttons.activeTab = 'preferences';
            }
        });

        if (root.expanded && !buttons.activeTab) {
            buttons.activeTab = 'settings';
            buttons.settings[0].classList.add("active");
        }

        buttons.apply.on('click', function () {
            form.trigger('submit');
        });

        inputs.on('change', function () {
            root.attr('data-changed', 'true');
        });
    })(jQuery, intec)
</script>