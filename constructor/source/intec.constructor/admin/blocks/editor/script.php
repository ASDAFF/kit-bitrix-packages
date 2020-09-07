<?php

use Bitrix\Main\Localization\Loc;
use intec\core\helpers\JavaScript;

/**
 * @var array $paths
 * @var array $data
 */

?>
<script type="text/javascript">
    (function ($, api) {
        var constructor;
        var data;

        constructor = window.block();
        data = <?= JavaScript::toObject($data) ?>;

        constructor.on('load', function (data) {});
        constructor.saving = ko.observable(false);
        constructor.on('save', function (event, result) {
            if (constructor.saving())
                return;

            constructor.saving(true);

            $.ajax({
                'cache': false,
                'type': 'POST',
                'dataType': 'json',
                'data': {
                    'action': 'block.save',
                    'data': JSON.stringify(result)
                },
                'success': function (value) {
                    if (value) {
                        notify('<?= Loc::getMessage('notify.saved') ?>', 'success');
                    } else {
                        notify('<?= Loc::getMessage('notify.saveError') ?>', 'danger');
                    }
                },
                'error': function () {
                    notify('<?= Loc::getMessage('notify.saveError') ?>', 'danger');
                },
                'complete': function () {
                    constructor.saving(false);
                }
            });
        });

        constructor.nodes = {
            root: $('#constructor'),
            structure: $('#constructor-structure'),
            containers: {
                window: ko.observable(),
                grid: ko.observable()
            }
        };

        <? include(__DIR__.'/script/environment.js') ?>
        <? include(__DIR__.'/script/gallery.js') ?>

        api.each(data.paths, function (key, path) {
            constructor.environment.path[key] = path;
        });

        ko.bindingHandlers.fade = {
            init: function (element, valueAccessor, allBindings) {
                var value = null,
                    $element = $(element);

                if (api.isFunction(valueAccessor))
                    value = valueAccessor();

                if ($element.is(':visible'))
                    $element.hide();

                if (api.isFunction(value)) {
                    if (value()) {
                        $element.stop(true, true).fadeIn(500);
                    } else {
                        $element.stop(true, true).fadeOut(500);
                    }
                }
            },
            update: function (element, valueAccessor, allBindings) {
                var value = null;

                if (api.isFunction(valueAccessor))
                    value = valueAccessor();

                if (api.isFunction(value)) {
                    if (value()) {
                        $(element).stop(true, true).fadeIn(500);
                    } else {
                        $(element).stop(true, true).fadeOut(500);
                    }
                }
            }
        };
        ko.bindingHandlers.slide = {
            update: function (element, valueAccessor, allBindings) {
                var value = null;

                if (api.isFunction(valueAccessor)) {
                    var v = valueAccessor();
                    if (api.isFunction(v)) {
                        value = v();
                    }
                }

                if (!api.isNull(value)) {
                    var direction = allBindings.get('direction'),
                        time = allBindings.get('time') || 300;

                    if (['left', 'right', 'down', 'up'].indexOf(direction) < 0) {
                        direction = 'up';
                    }

                    if (value) {
                        $(element).stop(true, true).show('slide', { direction: direction }, time);
                    } else {
                        $(element).stop(true, true).hide('slide', { direction: direction }, time);
                    }
                }
            }
        };

        // Menu & dialog & dropdown
        (function () {
            /** Block menu */
            constructor.menu = (function () {
                var menu = {};

                menu.models = {};
                menu.models.tab = (function () {
                    var model;
                    var prototype;

                    model = function (data) {
                        var self = this;

                        data = api.isObject(data) ? data : {};

                        self.name = data.name;
                        self.node = $(data.node || null);
                        self.active = ko.observable(false);
                        self.active.subscribe(function (value) {
                            if (value && api.isFunction(data.activate))
                                data.activate.call(self);

                            if (!value && api.isFunction(data.deactivate))
                                data.deactivate.call(self);
                        });

                        self.isActive = function () {
                            if (!self.active())
                                return false;

                            if (api.isFunction(data.handler))
                                return data.handler.call(self);

                            return true;
                        };

                        return self;
                    };

                    prototype = model.prototype;
                    prototype.open = function () { this.active(true); };
                    prototype.close = function () { this.active(false); };
                    prototype.toggle = function () { this.active(!this.active()); };

                    return model;
                })();

                menu.scroll = ko.models.scroll();
                menu.tabs = {};
                menu.tabs.close = function(){
                    api.each(menu.tabs.list, function (code, tab) {
                        tab.active(false);
                    })
                };
                menu.tabs.list = {
                    'settings': new menu.models.tab({
                        'activate': function () {
                            constructor.dropdown.close();
                        },
                        'handler': function () {
                            return constructor.resolutions.selected() != null &&
                                constructor.elements.selected() != null;
                        }
                    }),
                    'layers': new menu.models.tab({
                        'handler': function () {
                            return constructor.resolutions.selected() != null;
                        }
                    })
                };

                return menu;
            })();

            /** Block dropdown */
            constructor.dropdown = (function(){
                var dropdown = {};

                dropdown.models = {};
                dropdown.models.dropdown = function (data) {
                    var self = this;

                    data = api.isObject(data) ? data : {};

                    self.name = data.name;
                    self.node = $(data.node || null);

                    return self;
                };

                dropdown.scroll = ko.models.scroll();
                dropdown.list = {
                    widgets: new dropdown.models.dropdown(),
                    resolutions: new dropdown.models.dropdown()
                };

                dropdown.active = ko.observable(null);
                dropdown.active.subscribe(function(value){
                    if (value) {
                        constructor.menu.tabs.list.settings.close();
                    }
                });
                dropdown.close = function(){
                    dropdown.active(null);
                };
                dropdown.toggle = function(value){
                    if (dropdown.active() === value) {
                        dropdown.close();
                    } else {
                        dropdown.active(value);
                    }
                };

                return dropdown;
            })();

            constructor.dialogs = (function () {
                var dialogs;
                var dialog;

                dialog = function (settings) {
                    var dialog;

                    settings = api.extend({}, settings);

                    dialog = ko.models.dialog(settings);
                    dialog.on('close', function () {
                        dialog.expanded(false);
                    });
                    dialog.expanded = (function () {
                        var expanded;

                        expanded = ko.observable(false);
                        expanded.switch = function () {
                            dialog.expanded(!dialog.expanded());
                        };
                        expanded.update = function () {
                            if (dialog()) {
                                var node = $(dialog());
                                var container = node.closest('.ui-dialog');

                                if (dialog.expanded()) {
                                    container.addClass('ui-dialog-expanded');
                                } else {
                                    container.removeClass('ui-dialog-expanded');
                                }
                            }
                        };
                        expanded.subscribe(expanded.update);
                        dialog.subscribe(expanded.update);

                        return expanded;
                    })();

                    return dialog;
                };

                dialogs = {};

                <? include(__DIR__.'/script/dialogs.php') ?>

                return dialogs;
            })();
        })();

        constructor.guides = (function(){
            var self = {};

            self.columns = {};
            self.columns.active = ko.observable(0);
            self.columns.count = ko.observable(12);
            self.columns.count.type(api.type.integer, true);
            self.columns.space = new constructor.models.property.measured(30, 'px', ['px', 'pt', 'em']);
            self.columns.space.value.type(api.type.float, true);
            self.columns.space.minusSum = ko.computed(function(){
                var value = self.columns.space.value();
                if (value > 0) {
                    return value * -1 + self.columns.space.measure();
                }
                return self.columns.space.summary();
            });

            return self;
        })();

        $.notify.addStyle('constructor', {
            html: "<div><span data-notify-text/></div>"
        });
        function notify (text, status) {
            $.notify(text, {
                className: status,
                style: 'constructor',
                showAnimation: 'fadeIn',
                hideAnimation: 'fadeOut',
                autoHideDelay: 5000
            });
        }

        <? include(__DIR__.'/script/models.php') ?>

        constructor.resolutions.editable = (function () {
            var resolutions;
            var editable;

            resolutions = constructor.resolutions;
            editable = ko.observable();
            editable.subscribe(function (resolution) {
                if (resolution === null)
                    return;

                if (!constructor.models.resolution.is(resolution)) {
                    editable(null);
                    editable.width(null);
                    editable.height(null);
                    return;
                }

                editable.width(resolution.width());
                editable.height(resolution.height());
            });
            editable.width = ko.observable();
            editable.width.type(api.type.integer);
            editable.height = ko.observable();
            editable.height.type(api.type.integer);
            editable.create = function () {
                editable(new constructor.models.resolution());
            };
            editable.save = function () {
                var save;

                save = true;

                if (editable() === null)
                    return;

                api.each(resolutions(), function (index, item) {
                    if (editable.width() === item.width()) {
                        save = editable() === item;
                        return false;
                    }
                });

                if (save) {
                    editable().width(editable.width());
                    editable().height(editable.height());

                    if (!editable().isAvailable())
                        resolutions.push(editable());
                }

                editable(null);
            };
            editable.remove = function () {
                if (editable() === null)
                    return;

                if (editable().isAvailable())
                    resolutions.remove(editable());

                editable(null);
            };

            return editable;
        })();
        constructor.elements.add = function (type) {
            var element;

            if (!constructor.isElementType(type))
                return;

            element = new constructor.models.element({
                'code': type.code(),
                'order': 0
            });

            constructor.elements.push(element);
            element.layerUpEnd();
            element.select();

            return element;
        };
        constructor.elements.selected = (function (list) {
            var selected = ko.observable();
            var lock = false;

            selected.lock = function() { lock = true; };
            selected.unlock = function () { lock = false; };
            selected.isLocked = function () { return lock; };
            selected.isOnTop = ko.observable(false);

            selected.subscribe(function (model) {
                /** Before change model */
            }, null, 'beforeChange');

            selected.subscribe(function (model) {
                /** After select model */
            });

            list.subscribe(function (changes) {
                api.each(changes, function (index, change) {
                    if (change.status === 'deleted' && api.isUndefined(change.moved))
                        if (selected() === change.value)
                            selected(null);
                });
            }, null, 'arrayChange');

            $(window).on('keydown', function (event) {
                var key = event.keyCode;
                var element = selected();
                var attribute;

                if (element != null) {
                    switch (key) {
                        case 27: {
                            selected(null);
                            break;
                        }
                        case 37: {
                            attribute = element.attribute('x');
                            attribute(attribute() - 1);
                            break;
                        }
                        case 38: {
                            attribute = element.attribute('y');
                            attribute(attribute() - 1);
                            break;
                        }
                        case 39: {
                            attribute = element.attribute('x');
                            attribute(attribute() + 1);
                            break;
                        }
                        case 40: {
                            attribute = element.attribute('y');
                            attribute(attribute() + 1);
                            break;
                        }
                        case 46: {
                            element.remove();
                            break;
                        }
                    }
                }
            });

            return selected;
        })(constructor.elements);

        constructor.load(data);

        ko.applyBindings(constructor, constructor.nodes.root.get(0));

        $(document).ready(function () {
            $(document).on('click', function(e) {
                var target = $(e.target);
                var isWorkArea = target.closest('.constructor-area').length;
                var isTopMenu = target.hasClass('constructor-menu');

                if (isWorkArea || isTopMenu)
                    constructor.dropdown.close();
            });

        });

        // TODO
        window.block = block;

    })(jQuery, intec);
</script>
