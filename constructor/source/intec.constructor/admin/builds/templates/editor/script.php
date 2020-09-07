<?php
use intec\core\helpers\JavaScript;
use intec\core\helpers\Json;
use intec\constructor\models\Build;
use intec\constructor\models\build\Template;

/**
 * @var Build $build
 * @var Template $template
 * @var string $scheme
 * @var string $sites
 */
?>
<script type="text/javascript">
    (function ($, api) {
        var constructor = window.constructor();
        var directions = ['top', 'right', 'bottom', 'left'];

        <?php include(__DIR__.'/script/nodes.js') ?>
        <?php include(__DIR__.'/script/structure.js') ?>

        constructor.environment = (function () {
            var self;

            self = {};
            self.sites = ko.computed(function () {
                var site;
                var sites = <?= $sites ?>;
                var result = [];

                site = function (data) {
                    var self;

                    self = this;
                    self.id = ko.computed(function () { return data.id; });
                    self.name = ko.computed(function () { return data.name; });
                    self.directory = ko.computed(function () { return data.directory; });
                };

                api.each(sites, function (index, data) {
                    result.push(new site(data));
                });

                return result;
            });
            self.site = ko.observable(null);

            if (self.sites().length > 0)
                self.site(self.sites()[0]);

            self.site.subscribe(function () {
                var update;

                update = function (container) {
                    if (!constructor.isContainer(container))
                        return;

                    if (container.hasComponent()) {
                        container.getComponent().refresh();
                    } else if (container.hasVariator()) {
                        var variator = container.getVariator();
                        var variant = variator.getVariant();

                        if (variant != null)
                            update(variant.container());
                    } else if (container.hasArea()) {
                        update(container.getArea().container());
                    } else if (container.hasContainers()) {
                        api.each(container.getContainers(), function (index, container) {
                            update(container);
                        });
                    }
                };

                update(constructor.container());
            });

            self.template = ko.computed(function () { return <?= JavaScript::toObject($build->code) ?>; });
            self.path = {};
            self.path.template = <?= JavaScript::toObject($build->getDirectory(false, true, '/')); ?>;
            self.path.handle = function (path) {
                if (!api.isString(path))
                    return null;

                var aliases = {};

                api.each(self.path, function (alias, value) {
                    if (api.isString(value))
                        aliases['#' + alias.toUpperCase() + '#'] = value;
                });

                return api.string.replace(path, aliases);
            };

            return self;
        })();
        constructor.scheme = (function (data) {
            var module;
            var models;
            var styles;
            var update;

            module = {};
            models = {};
            styles = null;
            update = function () {
                var theme = module.themes.selected();
                var values = {};

                if (!theme)
                    return;

                api.each(theme.values(), function (index, value) {
                    values[value.code()] = value.value();
                });

                $.ajax({
                    'type': 'POST',
                    'cache': false,
                    'data': {
                        'action': 'template.styles',
                        'theme': theme.code(),
                        'values': values
                    },
                    'success': function (data) {
                        if (styles)
                            styles.remove();

                        styles = $(data);
                        styles.appendTo('head');
                    }
                })
            };

            models.theme = (function () {
                var model;

                model = function (data) {
                    var self = this;
                    var values = [];

                    self.code = ko.computed(function () { return data.code; });
                    self.name = ko.computed(function () { return data.name});
                    self.values = function () {
                        return values;
                    };
                    self.values.get = function (code, object) {
                        var result = null;

                        api.each(values, function (index, value) {
                            if (value.code() === code)
                                result = value;
                        });

                        if (!result) {
                            api.each(module.properties(), function (index, property) {
                                if (code === property.code()) {
                                    result = new models.value({
                                        'code': code,
                                        'value': null
                                    });

                                    values.push(result);
                                    return false;
                                }
                            });
                        }

                        if (result && !object) {
                            return result.value();
                        }

                        return result;
                    };
                    self.values.set = function (code, value) {
                        var property = self.values.get(code, true);

                        if (property) {
                            property.value(value);
                        }
                    };

                    api.each(data.values, function (index, value) {
                        self.values.set(value.code, value.value);
                    });
                };

                model.prototype.save = function () {
                    var self = this;
                    var result = {};

                    result.code = self.code();
                    result.name = self.name();
                    result.values = [];

                    api.each(self.values(), function (index, value) {
                        result.values.push(value.save());
                    });

                    return result;
                };

                return model;
            })();

            models.value = (function () {
                var model;
                
                model = function (data) {
                    var self = this;

                    self.code = ko.computed(function () { return data.code; });
                    self.value = ko.observable(data.value);
                    self.value.subscribe(update)
                };

                model.prototype.save = function () {
                    var self = this;
                    var result = {};

                    result.code = self.code();
                    result.value = self.value();

                    return result;
                };

                return model;
            })();

            models.property = (function () {
                var model;

                model = function (data) {
                    var self = this;

                    self.code = ko.computed(function () { return data.code; });
                    self.name = ko.computed(function () { return data.name; });
                    self.value = ko.computed(function () { return data.default; });
                    self.active = ko.observable(self.value() ? 1 : 0);
                };

                return model;
            })();

            module.properties = ko.observableArray([]);
            module.themes = ko.observableArray([]);
            module.themes.selected = ko.observable();

            api.each(data.properties, function (index, property) {
                module.properties.push(new models.property(property));
            });

            api.each(data.themes, function (index, theme) {
                theme = new models.theme(theme);
                module.themes.push(theme);

                if (theme.code() === data.theme)
                    module.themes.selected(theme);
            });

            module.themes.selected.subscribe(update);
            module.save = function () {
                var result = {};

                result.themes = [];
                result.theme = null;

                if (module.themes.selected())
                    result.theme = module.themes.selected().code();

                api.each(module.themes(), function (index, theme) {
                    result.themes.push(theme.save());
                });

                return result;
            };

            constructor.on('save', function (event, container, result) {
                result.scheme = module.save();
            });

            return module;
        })(<?= $scheme ?>);
        constructor.gallery = (function () {
            var module;
            var actions;
            var images;
            var models;
            var processing;

            module = {};
            images  = ko.observableArray([]);
            processing = ko.observable(false);

            models = {};
            models.image = (function () {
                var model;

                model = function (data) {
                    var self = this;

                    self.name = data.name;
                    self.path = data.path;
                    self.value = data.value;

                    self.delete = function () {
                        processing(true);
                        module.trigger('deleting', self);
                        module.trigger('processing', 'deleting', self);
                        actions.delete(self, function () {
                            images.remove(self);
                            processing(false);
                            module.trigger('delete', self);
                            module.trigger('processed', 'delete', self);
                        });
                    };
                };

                model.is = function (object) {
                    return object instanceof model;
                };

                return model;
            })();

            api.extend(module, api.ext.events(module));

            actions = {};
            actions.request = function (action, data, callback) {
                processing(true);
                data = api.extend({}, data, {
                    'action': 'gallery.' + action
                });

                $.ajax({
                    'type': 'POST',
                    'cache': false,
                    'dataType': 'json',
                    'data': data,
                    'success': function () {
                        processing(false);
                        callback.apply(module, arguments);
                    },
                    'error': function (response) {
                        processing(false);
                        console.error('Unexpected Gallery response on action "' + action + '".');
                        console.error(response);
                    }
                });
            };
            actions.list = function (callback) {
                actions.request('list', null, function (data) {
                    var result = [];

                    api.each(data, function (index, data) {
                        result.push(new models.image(data));
                    });

                    if (api.isFunction(callback))
                        callback.call(this, result);
                });
            };
            actions.upload = function (file, callback) {
                var data = new FormData();

                if (!file.type.match(/image.*/)) {
                    notify('<?= GetMessage('container.modals.gallery.wrong.extension') ?>', 'danger');
                    return false;
                }

                data.append('action', 'gallery.upload');
                data.append('file', file);

                $.ajax({
                    'type': 'POST',
                    'cache': false,
                    'dataType': 'json',
                    'contentType': false,
                    'processData': false,
                    'data': data,
                    'success': function (data) {
                        var image = null;

                        if (api.isObject(data))
                            image = new models.image(data);

                        if (api.isFunction(callback))
                            callback.call(module, image);
                    },
                    'error': function () {
                        processing(false);
                    }
                });
            };
            actions.delete = function (list, callback) {
                var names = [];

                if (api.isArray(list)) {
                    api.each(list, function (index, item) {
                        names.push(item.name);
                    });
                } else {
                    names = [list.name];
                }

                actions.request('delete', {
                    'files': names
                }, function () {
                    if (api.isArray(list)) {
                        images.removeAll(list);
                    } else {
                        images.remove(list);
                    }

                    if (api.isFunction(callback))
                        callback.call(this, list);
                });
            };

            module.processing = ko.computed(function () { return processing(); });
            module.images = ko.computed(function () { return images(); });
            module.upload = function (file, callback) {
                processing(true);
                module.trigger('uploading', file);
                module.trigger('processing', 'uploading', file);
                actions.upload(file, function (image) {
                    if (module.isImage(image))
                        images.push(image);
                    
                    processing(false);
                    module.trigger('upload', file, image);
                    module.trigger('processed', 'upload', file, image);

                    if (api.isFunction(callback))
                        callback.call(module, image);
                });
            };
            module.update = function (callback) {
                processing(true);
                module.trigger('updating');
                module.trigger('processing', 'updating');
                images.removeAll();
                actions.list(function (list) {
                    api.each(list, function (index, item) {
                         images.push(item);
                    });

                    processing(false);
                    module.trigger('update', list);
                    module.trigger('processed', 'update', list);

                    if (api.isFunction(callback))
                        callback.call(module, list);
                });
            };
            module.isImage = function (object) {
                return models.image.is(object);
            };

            return module;
        })();

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
                    if (value) {
                        $(element).stop(true, true).slideDown(300);
                    } else {
                        $(element).stop(true, true).slideUp(300);
                    }
                }
            }
        };

        // Dialogs && Menu
        (function () {
            var queue;

            queue = function (settings) {
                var self = this;
                var events = api.ext.events();
                var list;
                var add;
                var remove;

                settings = api.extend({
                    'fields': {
                        'list': 'list'
                    },
                    'methods': {
                        'add': 'add',
                        'remove': 'remove'
                    },
                    'moveToEnd': true,
                    'mode': queue.mode.append
                }, settings);

                list = (function () {
                    var list;

                    list = ko.observableArray([]);
                    list.getLast = function () {
                        var array = list();

                        if (array.length > 0)
                            return array[array.length - 1];

                        return null;
                    };

                    return list;
                })();

                add = function (object) {
                    if (list.indexOf(object) < 0) {
                        if (settings.mode === queue.mode.append) {
                            list.push(object);
                        } else {
                            list.unshift(object);
                        }
                    } else if (settings.moveToEnd) {
                        list.sort(function(a, b) {
                            if (a === object) return 1;
                            if (b === object) return -1;
                            return 0;
                        });
                    }

                    events.trigger(settings.methods.add, self, object);
                };

                remove = function (object) {
                    if (api.isEmpty(object))
                        object = list.getLast();

                    list.remove(object);
                    events.trigger(settings.methods.remove, self, object);
                };

                self[settings.fields.list] = list;
                self[settings.methods.add] = add;
                self[settings.methods.remove] = remove;

                api.extend(self, events);
            };

            queue.mode = {};
            queue.mode.prepend = 'prepend';
            queue.mode.append = 'append';

            /** Block menu */
            constructor.menu = (function () {
                var menu = {};

                menu.models = {};
                menu.models.tab = function (data) {
                    var self = this;

                    data = api.isObject(data) ? data : {};

                    self.open = function () {
                        menu.tabs.open(self);
                    };
                    self.close = function () {
                        menu.tabs.close(self);
                    };
                    self.isLock = function () { return data.lock; };
                    self.isActive = function () {
                        if (menu.tabs.active.getLast() !== self)
                            return false;

                        if (api.isFunction(data.handler))
                            return data.handler.call(self);

                        return true;
                    };

                    return self;
                };

                menu.scroll = ko.models.scroll();
                menu.tabs = (function () {
                    var tabs;

                    tabs = new queue({
                        'fields': {
                            'list': 'active'
                        },
                        'methods': {
                            'add': 'open',
                            'remove': 'close'
                        }
                    });

                    tabs.list = {
                        'main': new menu.models.tab(),
                        'container': new menu.models.tab({
                            'handler': function () {
                                return constructor.selected() != null;
                            }
                        }),
                        'widget': new menu.models.tab({
                            'handler': function () {
                                return constructor.selected() != null &&
                                    constructor.selected().hasWidget();
                            }
                        }),
                        'scheme': new menu.models.tab(),
                        'text': new menu.models.tab(),
                        'guides': new menu.models.tab(),
                        'visual': new menu.models.tab(),
                        'block': new menu.models.tab({
                            'handler': function () {
                                return constructor.selected() != null &&
                                    constructor.selected().hasBlock();
                            }
                        }),
                        'variator': new menu.models.tab({
                            'handler': function () {
                                return constructor.selected() != null &&
                                    constructor.selected().hasVariator();
                            }
                        }),
                        'blocks': (function () {
                            var tab;
                            var callback;

                            tab = new menu.models.tab({
                                'lock': true
                            });
                            tab.select = function (template) {
                                if (api.isFunction(callback))
                                    callback.call(tab, template);

                                callback = null;
                            };
                            tab.open = (function (method) {
                                return function (handler) {
                                    callback = handler;
                                    method();
                                };
                            })(tab.open);

                            return tab;
                        })()
                    };

                    tabs.isActive = ko.computed(function () {
                        var tab = tabs.active.getLast();

                        if (tab != null)
                            return tab.isActive();

                        return false;
                    });

                    tabs.isLock = ko.computed(function () {
                        var tab = tabs.active.getLast();

                        if (tab != null)
                            return tab.isActive() && tab.isLock();

                        return false;
                    });

                    return tabs;
                })();

                return menu;
            })();

            /** Block dialog */
            constructor.dialogs = (function () {
                var active = new queue({moveToEnd: false}),
                    dialogs = {
                        list: {},
                        create: function (settings) {
                            var dialog;

                            settings = api.extend({}, settings, {
                                'dialogClass': 'constructor-dialog'
                            });
                            dialog = ko.models.dialog(settings);
                            dialog.on('open', function () {
                                active.add(dialog);
                            });
                            dialog.on('close', function () {
                                active.remove(dialog);
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
                                        var container = node.closest('.ui-dialog.constructor-dialog');

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
                        },
                        getLast: function () {
                            return active.list.getLast();
                        },
                        closeLast: function () {
                            var dialog = this.getLast();

                            if (dialog)
                                dialog.close();
                        },
                        closeAll: function () {
                            api.each(active.list(), function (index, dialog) {
                                dialog.close();
                            });
                        }
                    };

                dialogs.list.gallery = (function () {
                    var dialog;
                    var gallery;

                    gallery = constructor.gallery;
                    dialog = dialogs.create({
                        modal: true,
                        resizable: true,
                        draggable: false,
                        show: 'fade',
                        hide: 'fade',
                        width: 920,
                        position: { my: 'center', at: 'center'},
                        classes: {
                            "ui-dialog": 'constructor-gallery'
                        }
                    });

                    (function () {
                        var action;
                        var handler;

                        action = dialog.open;
                        dialog.select = function (image) {
                            if (api.isFunction(handler))
                                if (handler(image))
                                    dialog.close();
                        };
                        dialog.open = function (callback) {
                            handler = callback;
                            action();
                        };
                    })();

                    dialog.data = (function () {
                        var root;
                        var updating = ko.observable(false);
                        var processed = ko.observableArray([]);

                        root = {};
                        root.filter = ko.observable('');
                        root.images = ko.computed(function () {
                            var filter = root.filter();
                            var expression = new RegExp(RegExp.escape(filter));
                            var result = [];

                            api.each(gallery.images(), function (index, image) {
                                if (expression.test(image.name))
                                    result.push(image);
                            });

                            api.each(processed(), function (index, file) {
                                result.push(file);
                            });

                            return result;
                        });
                        root.updating = ko.computed(function () { return updating(); });

                        gallery.on('updating', function (event) {
                            updating(true);
                        });

                        gallery.on('update', function (event, file) {
                            updating(false);
                        });

                        updating.subscribe(function (state) {
                            if (state === false)
                                dialog.scroll.update();
                        });

                        gallery.on('uploading', function (event, file) {
                            processed.push(file);
                        });

                        gallery.on('upload', function (event, file) {
                            processed.remove(file);
                        });

                        return root;
                    })();

                    dialog.scroll = ko.models.scroll();
                    dialog.uploader = (function () {
                        var uploader;
                        var active;

                        active = ko.observable(false);
                        uploader = {};
                        uploader.active = ko.computed(function () { return active(); });
                        uploader.node = ko.observable();
                        uploader.node.subscribe(function (node) {
                            var self;

                            self = $(node);
                            self.on('change', function (event) {
                                if (gallery.processing())
                                    return;
                                
                                if (node.files.length > 0) {
                                    api.each(node.files, function (index, file) {
                                        gallery.upload(file);
                                    });

                                    self.val(null);
                                }
                            });
                        });
                        uploader.zone = ko.observable();
                        uploader.zone.subscribe(function (node) {
                            var self;

                            self = $(node);
                            self.on('click', function () {
                                if (uploader.node())
                                    $(uploader.node()).trigger('click');
                            });
                            self.on('dragover', function (event) {
                                active(true);
                                event.preventDefault();
                                event.stopPropagation();

                                return false;
                            });
                            self.on('dragleave', function (event) {
                                active(false);
                                event.preventDefault();
                                event.stopPropagation();

                                return false;
                            });
                            self.on('drop', function (event) {
                                active(false);
                                event.preventDefault();
                                event.stopPropagation();

                                if (gallery.processing())
                                    return false;

                                api.each(event.originalEvent.dataTransfer.files, function (index, file) {
                                    gallery.upload(file);
                                });
                                
                                return false;
                            });
                        });

                        return uploader;
                    })();

                    dialog.on('create', function (event, ui) {
                        $(ui.target).parent().draggable({
                            handle: '.constructor-dialog-header',
                            containment: 'window'
                        });
                    });
                    dialog.on('open', function (event, ui) {
                        gallery.update();
                    });

                    dialog.expanded.subscribe(function () {
                        dialog.scroll.update();
                    });

                    return dialog;
                })();

                dialogs.list.confirm = (function () {
                    var dialog = dialogs.create({
                        modal: true,
                        resizable: false,
                        draggable: false,
                        show: 'fade',
                        hide: 'fade',
                        width: 500,
                        height: 300,
                        position: { my: 'center', at: 'center'},
                        title: <?= JavaScript::toObject(GetMessage('container.modals.confirm.title')) ?>,
                        classes: {
                            "ui-dialog": "constructor-gallery"
                        }
                    });

                    return dialog;
                })();

                dialogs.list.script = (function () {
                    var container = ko.observable(null);
                    var script = ko.observable(null);
                    var dialog = dialogs.create({
                        modal: true,
                        resizable: false,
                        draggable: false,
                        show: 'fade',
                        hide: 'fade',
                        width: 800,
                        position: { my: 'center', at: 'center'},
                        title: <?= JavaScript::toObject(GetMessage('container.modals.script.title')) ?>,
                        classes: {
                            "ui-dialog": "constructor-script"
                        }
                    });

                    dialog.open = (function () {
                        var method = dialog.open;

                        return function (object) {
                            if (!constructor.isContainer(object))
                                return;

                            container(object);
                            method();
                        }
                    })();

                    dialog.container = ko.computed(function () { return container(); });
                    dialog.editor = ko.models.codeMirror({
                        'mode': 'text/x-php'
                    }, script);

                    container.subscribe(function (container) {
                        script(null);

                        if (container !== null)
                            script(container.script());
                    });

                    script.subscribe(function (script) {
                        if (container() !== null)
                            container().script(script);
                    });

                    dialog.on('open', function () {
                        if (dialog.editor()) {
                            dialog.editor.getEditor().refresh();
                        }
                    });
                    dialog.on('close', function () {
                        container(null);
                    });

                    return dialog;
                })();

                dialogs.list.structure = (function () {
                    var callback = ko.observable(null);
                    var dialog = dialogs.create({
                        modal: true,
                        resizable: false,
                        draggable: false,
                        show: 'fade',
                        hide: 'fade',
                        width: 800,
                        position: { my: 'center', at: 'center'},
                        title: <?= JavaScript::toObject(GetMessage('container.modals.structure.title')) ?>,
                        classes: {
                            "ui-dialog": "constructor-structure"
                        }
                    });

                    dialog.open = (function () {
                        var method = dialog.open;

                        return function (object) {
                            if (api.isFunction(object)) {
                                callback(object);
                            } else if (constructor.isContainer(object)) {
                                dialog.value(JSON.stringify(object.save(true), null, '\t'));
                            } else {
                                return;
                            }

                            method();
                        }
                    })();

                    dialog.mode = ko.computed(function () { return api.isFunction(callback()) ? 'write' : 'read'; });
                    dialog.value = ko.observable(null);
                    dialog.on('close', function () {
                        dialog.value(null);
                        callback(null);
                    });

                    dialog.save = function () {
                        if (dialog.mode() === 'write') {
                            try {
                                callback().call(dialog, JSON.parse(dialog.value()));
                            } catch (exception) {}
                        }

                        dialog.close();
                    };

                    return dialog;
                })();

                dialogs.list.areaSelect = (function () {
                    var processing = ko.observable(false);
                    var handler;
                    var dialog = dialogs.create({
                        modal: true,
                        resizable: false,
                        draggable: false,
                        show: 'fade',
                        hide: 'fade',
                        width: 600,
                        position: { my: 'center', at: 'center'}
                    });

                    dialog.data = {};
                    dialog.data.area = ko.observable();
                    dialog.data.processing = ko.computed(function () { return processing(); });
                    dialog.data.areas = ko.observableArray([]);
                    dialog.data.error = ko.observable(null);

                    dialog.open = (function () {
                        var method = dialog.open;

                        return function (callback) {
                            if (!api.isFunction(callback))
                                return;

                            handler = callback;
                            processing(true);

                            $.ajax({
                                'type': 'POST',
                                'dataType': 'json',
                                'data': {
                                    'action': 'areas.list'
                                },
                                'success': function (data) {
                                    var areas = [];

                                    if (constructor.container())
                                        constructor.container().containers.each(function () {
                                            if (this.hasArea())
                                                areas.push(this.getArea())
                                        }, true);

                                    api.each(data, function (index, data) {
                                        var exists = false;

                                        api.each(areas, function (index, area) {
                                            if (area.id() === data.id)
                                                exists = true;
                                        });

                                        if (!exists)
                                            dialog.data.areas.push(new constructor.models.area(data));
                                    });
                                },
                                'error': function () {
                                    dialog.data.error('Request error!');
                                },
                                'complete': function () {
                                    processing(false);
                                }
                            });

                            method();
                        }
                    })();

                    dialog.select = function () {
                        var area = dialog.data.area();

                        if (processing())
                            return;

                        if (area == null)
                            return;

                        processing(true);
                        dialog.close();
                        handler.call(dialog, area);
                    };

                    dialog.on('close', function () {
                        dialog.data.area(null);
                        dialog.data.areas.removeAll();
                        dialog.data.error(null);
                        processing(false);
                    });

                    return dialog;
                })();

                dialogs.list.blockConvert = (function () {
                    var block = ko.observable();
                    var processing = ko.observable(false);
                    var dialog = dialogs.create({
                        modal: true,
                        resizable: false,
                        draggable: false,
                        show: 'fade',
                        hide: 'fade',
                        width: 600,
                        position: { my: 'center', at: 'center'}
                    });

                    dialog.data = {};
                    dialog.data.block = ko.computed(function () { return block(); });
                    dialog.data.processing = ko.computed(function () { return processing(); });
                    dialog.data.code = ko.observable(null);
                    dialog.data.name = ko.observable(null);
                    dialog.data.error = ko.observable(null);

                    dialog.data.code.subscribe(function () { dialog.data.error(null); });
                    dialog.data.name.subscribe(function () { dialog.data.error(null); });

                    dialog.open = (function () {
                        var method = dialog.open;

                        return function (object) {
                            if (!constructor.isBlock(object))
                                return;

                            block(object);
                            dialog.data.name(object.name());
                            method();
                        }
                    })();

                    dialog.save = function () {
                        if (processing())
                            return;

                        if (block() == null)
                            return;

                        if (!dialog.data.code() || !dialog.data.name())
                            return;

                        processing(true);

                        $.ajax({
                            'type': 'POST',
                            'dataType': 'json',
                            'data': {
                                'action': 'blocks.convert',
                                'block': block().id(),
                                'code': dialog.data.code(),
                                'name': dialog.data.name()
                            },
                            'success': function (data) {
                                if (data.result) {
                                    dialog.close();
                                } else {
                                    dialog.data.error(data.error);
                                }
                            },
                            'error': function () {
                                dialog.data.error('Request error!');
                            },
                            'complete': function () {
                                processing(false);
                            }
                        });
                    };

                    dialog.on('close', function () {
                        block(null);
                        dialog.data.code(null);
                        dialog.data.name(null);
                        dialog.data.error(null);
                        processing(false);
                    });

                    return dialog;
                })();

                (function () {
                    dialogs.list.componentProperties = (function () {
                        var component = ko.observable();
                        var dialog = dialogs.create({
                            modal: true,
                            resizable: false,
                            draggable: false,
                            show: 'fade',
                            hide: 'fade',
                            width: 800,
                            height: 520,
                            title: <?= JavaScript::toObject(GetMessage('container.modals.component.properties.form-title')) ?>,
                            classes: {
                                "ui-dialog": "constructor-component-properties"
                            }
                        });

                        dialog.open = (function () {
                            var method = dialog.open;

                            return function (object) {
                                if (!constructor.isComponent(object))
                                    return;

                                component(object);
                                method();
                            }
                        })();

                        dialog.on('create', function (event, ui) {
                            $(ui.target).parent().draggable({
                                handle: '.constructor-dialog-header',
                                containment: 'window'
                            });
                        });
                        dialog.on('open', function () {
                            dialog.data.update(component().template(), component().properties);
                        });

                        dialog.data = (function () {
                            var root;
                            var name;
                            var description;
                            var scripts;
                            var groups;
                            var templates;
                            var models;
                            var updating;
                            var update;
                            var sorting;
                            var refresh;

                            name = ko.observable();
                            description = ko.observable();
                            scripts  = ko.observableArray([]);
                            templates = ko.observableArray([]);

                            groups = ko.observableArray([]);
                            groups.visible = ko.computed(function () {
                                var result = [];

                                api.each(groups(), function (index, group) {
                                    if (group.visible()) result.push(group);
                                });

                                return result;
                            });

                            updating = ko.observable(false);
                            update = function () {
                                root.update(
                                    root.template(),
                                    root.properties(),
                                    false
                                );
                            };

                            sorting = function (a, b) {
                                if (a.sort() < b.sort()) return -1;
                                if (a.sort() > b.sort()) return 1;
                                return 0;
                            };

                            refresh = function () {
                                var current;
                                var node;

                                node = root.groups.node();
                                node = $(node);

                                api.each(groups(), function (index, group) {
                                    group.active(false);
                                });

                                api.each(root.groups.visible(), function (index, group) {
                                    current = group;

                                    if ($(group.node()).offset().top >= node.offset().top) {
                                        return false;
                                    }
                                });

                                if (current)
                                    current.active(true);
                            };

                            models = {};
                            models.group = (function () {
                                var model;

                                model = function (data) {
                                    var self = this;
                                    var parameters = ko.observableArray([]);

                                    self.code = ko.computed(function () { return data.code; });
                                    self.name = ko.computed(function () { return data.name; });
                                    self.active = ko.observable(false);
                                    self.visible = ko.computed(function () {
                                        var result = false;

                                        if (self.code() === 'COMPONENT_TEMPLATE')
                                            return true;

                                        api.each(parameters(), function (index, parameter) {
                                             if (parameter.visible() === true) {
                                                 result = true;
                                                 return false;
                                             }
                                        });

                                        return result;
                                    });
                                    self.go = function () {
                                        var nodes;
                                        var position;

                                        nodes = {};
                                        nodes.container = $(root.groups.node());
                                        nodes.self = $(self.node());

                                        nodes.container.scrollTop(0);
                                        position = nodes.self.offset().top - nodes.container.offset().top;
                                        nodes.container.scrollTop(position);
                                    };
                                    self.sort = ko.computed(function () { return data.name; });
                                    self.node = ko.observable();
                                    self.parameters = ko.computed(function () {
                                        return parameters();
                                    });

                                    api.each(data.parameters, function (index, parameter) {
                                        parameters.push(new models.parameter(parameter, self));
                                    });
                                };

                                return model;
                            })();
                            models.parameter = (function () {
                                var model;

                                model = function (data, group) {
                                    var self = this;

                                    self.code = ko.computed(function () { return data.code; });
                                    self.name = ko.computed(function () { return data.name; });
                                    self.type = ko.computed(function () { return data.type; });
                                    self.group = ko.computed(function () { return data.group; });
                                    self.hidden = ko.computed(function () { return data.hidden; });
                                    self.visible = ko.computed(function () {
                                        var expression;
                                        var result;

                                        if (self.hidden())
                                            return false;
                                        
                                        if (!root.filter())
                                            return true;

                                        expression = new RegExp(RegExp.escape(root.filter()), 'i');
                                        result =
                                            expression.test(self.name()) ||
                                            expression.test(self.code());

                                        return result;
                                    });
                                    self.refresh = ko.computed(function () { return data.refresh; });
                                    self.raw = ko.computed(function () { return data.raw; });
                                    self.nodes = {
                                        'container': ko.observable(),
                                        'input': ko.observable()
                                    };
                                    self.save = function () { return null; };

                                    if (self.type() === 'CHECKBOX') {
                                        self.value = ko.observable(data.value === 'Y');
                                        self.save = function () {
                                            return self.value() ? 'Y' : 'N';
                                        };

                                        if (self.refresh())
                                            self.value.subscribe(update);
                                    } else if (self.type() === 'STRING') {
                                        self.multiple = ko.computed(function () { return data.multiple; });

                                        if (!self.multiple()) {
                                            self.value = ko.observable(data.value);
                                            self.save = function () {
                                                return self.value();
                                            };

                                            if (self.refresh())
                                                self.value.subscribe(update);
                                        } else {
                                            self.values = ko.observableArray([]);
                                            self.values.add = function (value) {
                                                value = ko.observable(value);
                                                self.values.push({
                                                    'value': value
                                                });

                                                if (self.refresh())
                                                    value.subscribe(update);
                                            };

                                            api.each(data.value, function (index, value) {
                                                self.values.add(value);
                                            });

                                            if (self.values().length === 0)
                                                self.values.add('');

                                            self.save = function () {
                                                var result = [];

                                                api.each(self.values(), function (index, value) {
                                                    value = value.value();

                                                    if (!api.isString(value))
                                                        return;

                                                    if (value.length === 0)
                                                        return;

                                                    result.push(value);
                                                });

                                                if (result.length > 0)
                                                    return result;

                                                return [''];
                                            };
                                        }
                                    } else if (self.type() === 'LIST') {
                                        var values = ko.observableArray([]);

                                        values.custom = function (data) {
                                            var self = this;

                                            self.value = ko.observable(data.value);
                                        };

                                        self.multiple = ko.computed(function () { return data.multiple; });
                                        self.extended = ko.computed(function () { return data.extended; });
                                        self.values = ko.computed(function () { return values(); });
                                        self.values.get = function (value) {
                                            var result = null;

                                            api.each(self.values(), function (index, object) {
                                                if (object.value() == value) {
                                                    result = object;
                                                    return false;
                                                }
                                            });

                                            return result;
                                        };
                                        self.values.has = function (value) {
                                            return self.values.get(value) !== null;
                                        };

                                        api.each(data.values, function (index, value) {
                                            values.push({
                                                'value': ko.computed(function () { return value.value; }),
                                                'name': ko.computed(function () { return value.name; })
                                            })
                                        });

                                        if (!self.multiple()) {
                                            self.value = ko.observable(self.values.get(data.value));

                                            if (self.value() === null)
                                                self.value(undefined);

                                            if (self.extended()) {
                                                self.values.custom = new values.custom({
                                                    'value': null
                                                });

                                                self.values.custom.selected = ko.computed(function () {
                                                    return self.value() === undefined;
                                                });

                                                if (self.values.custom.selected()) {
                                                    self.values.custom.value(data.value);
                                                }
                                            }

                                            self.save = function () {
                                                if (self.extended())
                                                    if (self.values.custom.selected())
                                                        return self.values.custom.value();

                                                if (api.isDeclared(self.value()))
                                                    return self.value().value();

                                                return null;
                                            };

                                            if (self.refresh()) {
                                                self.value.subscribe(update);

                                                if (self.extended())
                                                    self.values.custom.value.subscribe(function () {
                                                        if (self.values.custom.selected())
                                                            update();
                                                    });
                                            }
                                        } else {
                                            self.value = ko.observableArray([]);

                                            if (self.extended()) {
                                                self.custom = ko.observableArray([]);
                                                self.custom.add = function (value) {
                                                    value = new values.custom({
                                                        'value': value
                                                    });
                                                    self.custom.push(value);

                                                    if (self.refresh())
                                                        value.value.subscribe(update);
                                                }
                                            }

                                            api.each(data.value, function (index, value) {
                                                var object = self.values.get(value);

                                                if (object !== null) {
                                                    self.value.push(object);
                                                } else if (self.extended()) {
                                                    self.custom.add(value);
                                                }
                                            });

                                            if (self.extended())
                                                if (self.custom().length === 0)
                                                    self.custom.add('');

                                            self.save = function () {
                                                var result = [];

                                                api.each(self.value(), function (index, value) {
                                                    if (!api.isDeclared(value))
                                                        return;

                                                    value = value.value();

                                                    if (result.indexOf(value) === -1)
                                                        result.push(value);
                                                });

                                                if (self.extended())
                                                    api.each(self.custom(), function (index, value) {
                                                        value = value.value();

                                                        if (!api.isString(value))
                                                            return;

                                                        if (value.length === 0)
                                                            return;

                                                        if (result.indexOf(value) === -1)
                                                            result.push(value);
                                                    });

                                                if (result.length > 0)
                                                    return result;

                                                return null;
                                            };

                                            if (self.refresh())
                                                self.value.subscribe(update)
                                        }
                                    } else if (self.type() === 'COLORPICKER') {
                                        self.value = ko.observable(data.value);
                                        self.save = function () {
                                            return self.value();
                                        };

                                        if (self.refresh())
                                            self.value.subscribe(update);
                                    } else if (self.type() === 'CUSTOM') {
                                        self.value = ko.observable(data.value);
                                        self.nodes.input.subscribe(function (node) {
                                            node.onchange = function () {
                                                self.value($(node).val());
                                            }
                                        });
                                        self.javascript = {
                                            'file': ko.computed(function() { return data.javascript.file}),
                                            'data': ko.computed(function() { return data.javascript.data}),
                                            'event': ko.computed(function() { return data.javascript.event})
                                        };
                                        self.run = function () {
                                            var event = window[self.javascript.event()];

                                            if (api.isFunction(event))
                                                event.call(window, {
                                                    'data': self.javascript.data(),
                                                    'fChange': function () {
                                                        self.value($(node).val());
                                                    },
                                                    'getElements': root.inputs,
                                                    'oCont': self.nodes.container(),
                                                    'oInput': self.nodes.input(),
                                                    'propertyID': self.code(),
                                                    'propertyParams': self.raw()
                                                });
                                        };
                                        self.save = function () {
                                            return self.value();
                                        };

                                        if (self.refresh())
                                            self.value.subscribe(update);
                                    } else if (self.hidden()) {
                                        self.value = ko.computed(function () { return data.value; });
                                        self.save = function () { return self.value(); };
                                    }
                                };

                                return model;
                            })();
                            models.template = (function () {
                                var model;

                                model = function (data) {
                                    var self = this;

                                    self.code = ko.computed(function () { return data.code; });
                                    self.name = ko.computed(function () { return data.name; });
                                };

                                return model;
                            })();

                            root = {};
                            root.name = ko.computed(function () { return name(); });
                            root.description = ko.computed(function () { return description(); });
                            root.scripts = ko.computed(function () { return scripts(); });
                            root.component = ko.computed(function () { return component(); });
                            root.filter = ko.observable();
                            root.filter.subscribe(refresh);
                            root.groups = ko.computed(function () { return groups(); });
                            root.groups.visible = groups.visible;
                            root.groups.node = ko.observable();
                            root.groups.node.subscribe(function (node) {
                                node = $(node);
                                node.on('scroll', function () {
                                    refresh();
                                });
                            });
                            root.properties = function () {
                                var result = {};

                                api.each(groups(), function (index, group) {
                                    api.each(group.parameters(), function (index, parameter) {
                                        var value = parameter.save();

                                        if (!api.isDeclared(value))
                                            return;

                                        result[parameter.code()] = value;
                                    })
                                });

                                return result;
                            };
                            root.inputs = function () {
                                var result = {};

                                result['COMPONENT_TEMPLATE'] = root.template.input();

                                api.each(groups(), function (index, group) {
                                    api.each(group.parameters(), function (index, parameter) {
                                        result[parameter.code()] = parameter.nodes.input();
                                    })
                                });

                                return result;
                            };
                            root.templates = ko.computed(function () { return templates(); });
                            root.template = ko.observable();
                            root.template.container = ko.observable();
                            root.template.input = ko.observable();
                            root.template.subscribe(function () {
                                if (!updating())
                                    update();
                            });
                            root.updating = ko.computed(function () { return updating(); });
                            root.update = function (template, properties, clear) {
                                if (updating())
                                    return;

                                var environment = constructor.environment;
                                var site = null;
                                var directory = null;

                                if (environment.site()) {
                                    site = environment.site().id();
                                    directory = environment.site().directory();
                                }

                                updating(true);

                                $.ajax({
                                    'type': 'post',
                                    'dataType': 'json',
                                    'data': {
                                        'action': 'components.properties',
                                        'component': component().code(),
                                        'template': template,
                                        'constants': {
                                            'site': site,
                                            'template': environment.template(),
                                            'directory': directory
                                        },
                                        'properties': properties,
                                        'clear': clear ? 1 : 0
                                    },
                                    'success': function (response) {
                                        groups.removeAll();
                                        templates.removeAll();
                                        root.template(null);

                                        name(response.name);
                                        description(response.description);

                                        api.each(response.scripts, function (index, script) {
                                            scripts.push(script);
                                        });

                                        api.each(response.groups, function (index, group) {
                                            groups.push(new models.group(group));
                                        });

                                        api.each(response.templates, function (index, template) {
                                            templates.push(new models.template(template));
                                        });

                                        root.template(response.template);

                                        updating(false);

                                        api.each(groups(), function (index, group) {
                                            api.each(group.parameters(), function (index, parameter) {
                                                if (parameter.type() === 'CUSTOM')
                                                    parameter.run();
                                            });
                                        });

                                        refresh();
                                    },
                                    'error': function () {
                                        updating(false);
                                    }
                                });
                            };
                            
                            return root;
                        })();

                        dialog.update = function (clear) {
                            dialog.data.update(
                                dialog.data.template(),
                                dialog.data.properties(),
                                clear
                            );
                        };

                        dialog.save = function () {
                            component().template(dialog.data.template());
                            component().properties = dialog.data.properties();
                            component().refresh();
                            dialog.close();
                        };

                        return dialog;
                    })();

                    dialogs.list.componentList = (function () {
                        var selected = ko.observable(null);
                        var handler;
                        var dialog = dialogs.create({
                            modal: true,
                            resizable: false,
                            draggable: false,
                            show: 'fade',
                            hide: 'fade',
                            width: 800,
                            height: 600,
                            classes: {
                                "ui-dialog": "constructor-component-list"
                            },
                            dialogClass: 'constructor-dialog'
                        });

                        dialog.open = (function () {
                            var method = dialog.open;

                            return function (callback) {
                                handler = callback;
                                method();
                            }
                        })();

                        dialog.on('create', function (event, ui) {
                            $(ui.target).parent().draggable({
                                handle: '.constructor-dialog-header',
                                containment: 'window'
                            });
                        });
                        dialog.on('open', function () {
                            dialog.data.update();
                        });
                        dialog.on('close', function() {
                            handler = null;
                        });

                        dialog.data = (function () {
                            var root;
                            var sections;
                            var models;
                            var updating;
                            var sorting;

                            sections = ko.observableArray([]);
                            updating = ko.observable(false);
                            sorting = function (a, b) {
                                if (a.sort() < b.sort()) return -1;
                                if (a.sort() > b.sort()) return 1;
                                return 0;
                            };

                            models = {};
                            models.section = (function () {
                                var model;

                                model = function (data, parent) {
                                    var self = this;
                                    var sections = ko.observableArray([]);
                                    var components = ko.observableArray([]);

                                    sections.visible = ko.computed(function () {
                                        var result = [];

                                        api.each(sections(), function (index, section) {
                                            if (section.visible())
                                                result.push(section);
                                        });

                                        return result;
                                    });

                                    components.visible = ko.computed(function () {
                                        var result = [];

                                        api.each(components(), function (index, component) {
                                            if (component.visible())
                                                result.push(component);
                                        });

                                        return result;
                                    });

                                    self.active = ko.observable(false);
                                    self.code = ko.computed(function () { return data.code; });
                                    self.name = ko.computed(function () { return data.name; });
                                    self.sort = ko.computed(function () { return data.sort; });
                                    self.parent = ko.computed(function () { return parent; });
                                    self.visible = ko.computed(function () {
                                        var result;

                                        result = sections.visible().length > 0;

                                        if (result)
                                            return true;

                                        return components.visible().length > 0;
                                    });
                                    self.sections = ko.computed(function () { return sections(); });
                                    self.sections.visible = sections.visible;
                                    self.components = ko.computed(function () { return components(); });
                                    self.components.visible = components.visible;

                                    api.each(data.sections, function (index, section) {
                                        sections.push(new model(section, self));
                                    });

                                    api.each(data.components, function (index, component) {
                                        components.push(new models.component(component, self));
                                    });

                                    sections.sort(sorting);
                                    components.sort(sorting);
                                };

                                return model;
                            })();
                            models.component = (function () {
                                var model;

                                model = function (data, parent) {
                                    var self = this;

                                    self.code = ko.computed(function () { return data.code; });
                                    self.name = ko.computed(function () { return data.name; });
                                    self.section = ko.computed(function () { return parent; });
                                    self.namespace = ko.computed(function () { return data.namespace; });
                                    self.description = ko.computed(function () { return data.description; });
                                    self.visible = ko.computed(function () {
                                        var expression;
                                        var result;

                                        if (!root.filter())
                                            return true;

                                        expression = new RegExp(RegExp.escape(root.filter()), 'i');
                                        result =
                                            expression.test(self.name()) ||
                                            expression.test(self.code());

                                        return result;
                                    });
                                    self.complex = ko.computed(function () { return data.complex; });
                                    self.sort = ko.computed(function () { return data.sort; });
                                    self.select = function () { selected(self); };
                                    self.selected = ko.computed(function () { return selected() === self; });
                                };

                                return model;
                            })();

                            root = {};
                            root.sections = ko.computed(function () {
                                var result = [];

                                api.each(sections(), function (index, section) {
                                    if (section.visible())
                                        result.push(section);
                                });

                                return result;
                            });
                            root.filter = ko.observable();
                            root.updating = ko.computed(function () { return updating(); });
                            root.update = function () {
                                updating(true);
                                selected(null);

                                $.ajax({
                                    'type': 'post',
                                    'dataType': 'json',
                                    'data': {
                                        'action': 'components.list'
                                    },
                                    'success': function(response) {
                                        sections.removeAll();

                                        api.each(response, function (index, data) {
                                            sections.push(new models.section(data));
                                        });

                                        sections.sort(sorting);
                                        updating(false);
                                    },
                                    'error': function () {
                                        updating(false);
                                    }
                                });
                            };

                            return root;
                        })();

                        dialog.save = function () {
                            if (api.isFunction(handler))
                                handler.call(dialog, selected());

                            dialog.close();
                        };

                        return dialog;
                    })();
                })();

                dialogs.list.conditions = (function () {
                    var dialog = dialogs.create({
                        modal: true,
                        resizable: false,
                        draggable: false,
                        show: 'fade',
                        hide: 'fade',
                        width: 1000,
                        classes: {
                            "ui-dialog": "constructor-conditions"
                        }
                    });
                    dialog.on('create', function (event, ui) {
                        $(ui.target).parent().draggable({
                            handle: '.constructor-dialog-header',
                            containment: 'window'
                        });
                    });

                    dialog.types = <?= JavaScript::toObject($conditionTypes) ?>;
                    dialog.operators = <?= JavaScript::toObject($conditionOperators) ?>;
                    dialog.scroll = ko.models.scroll();

                    dialog.groups = ko.observable([]);
                    dialog.group = ko.observable();
                    dialog.type = ko.observable();

                    function refreshGroups () {
                        var groups = [];
                        api.each(constructor.selected().condition.getConditionGroups(), function(i, condition){
                            var name = '- '.repeat(condition.model.level());
                            if (!condition.model.hasParent()) {
                                name += '<?= GetMessage('container.modals.conditions.group.root') ?>';
                            } else {
                                name += '<?= GetMessage('container.modals.conditions.group') ?> ' +
                                    condition.model.level() + '-' + (condition.index + 1);
                            }
                            groups.push({name: name, value: condition.model});
                        });
                        dialog.groups(groups);
                    }

                    dialog.on('open', function () {
                        dialog.scroll.update();
                        refreshGroups();
                        constructor.selected().condition.on('updateConditions', function(){
                            refreshGroups();
                        });
                    });
                    dialog.add = function (data) {
                        data = api.extend({type: dialog.type()}, data);
                        var condition = new constructor.models.condition(data),
                            target = dialog.group();

                        if (!constructor.models.condition.is(target)) {
                            target = constructor.selected().condition;
                        }
                        target.addCondition(condition);

                        if (dialog.type() == 'group') {
                            refreshGroups();
                        }
                    };

                    return dialog;
                })();

                return dialogs;
            })();
        })();

        constructor.guides = (function(){
            var self = {};

            self.columns = {};
            self.columns.active = ko.observable(0);
            self.columns.count = ko.observable(12);
            self.columns.count.type(api.type.integer, true);
            self.columns.space = new constructor.models.property.measured(15, null, ['px', 'pt', 'em']);
            self.columns.space.value.type(api.type.float, true);
            self.columns.space.minusSum = ko.computed(function(){
                var value = self.columns.space.value();
                if (value > 0) {
                    return value * -1 + self.columns.space.measure();
                }
                return self.columns.space.summary();
            });

            self.rows = {};
            self.rows.active = ko.observable(0);
            self.rows.space = new constructor.models.property.measured(50, null, ['px', 'pt', 'em']);
            self.rows.space.value.type(api.type.float, true);

            return self;
        })();

        constructor.dragAction = null;
        function dragAction (container, settings) {
            var action = this;
            action.parent = null;

            if (api.isEmpty(settings)) {
                settings = {};
            }

            var isGrid = false,
                offset = {},
                measure = 'px',
                properties = container.properties,
                dx, dy, // Current action
                totalX = null, // x offset from starting position
                totalY = null, // y offset from starting position
                parentWidth = null,
                parentHeight = null;

            var grid = {
                fixPosition: function(stepX, stepY){ // container must be in grid
                    // TODO support for right and bottom positions
                    if (stepX) {
                        var modX = offset.left % stepX;
                        dx += modX > stepX / 2 ? stepX - modX : modX * -1;
                    }
                    if (stepY) {
                        var modY = offset.top % stepY;
                        dy += modY > stepY / 2 ? stepY - modY : modY * -1;
                    }
                },
                move: function(){
                    var stepX = 1,
                        stepY = 1,
                        modifyX = totalX > 1 ? 1 : -1,
                        modifyY = totalY > 1 ? 1 : -1,
                        parentGrid = action.parent.properties.grid;

                    switch (parentGrid.type()) {
                        case 'adaptive':
                            measure = '%';
                            stepX = 100 / parentGrid.x();
                            stepY = 100 / parentGrid.y();

                            if (parentWidth && stepX < 100 && totalX !== null) {
                                dx = Math.floor((Math.abs(totalX) / parentWidth * 100) / stepX) * stepX * modifyX;
                            }
                            if (parentHeight && stepY < 100 && totalY !== null) {
                                dy = Math.floor((Math.abs(totalY) / parentHeight * 100) / stepY) * stepY * modifyY;
                            }
                            break;
                        case 'fixed':
                            measure = 'px';
                            stepX = parentGrid.width();
                            stepY = parentGrid.height();

                            if (totalX !== null) {
                                dx = Math.floor(Math.abs(totalX) / stepX) * stepX * modifyX;
                            }
                            if (totalY !== null) {
                                dy = Math.floor(Math.abs(totalY) / stepY) * stepY * modifyY;
                            }
                            break;
                    }

                    this.fixPosition(stepX, stepY);
                }
            };

            // Convert offset measures
            function convertOffset (){ // if offset not compatible with grid type
                if (!action.parent) {
                    return;
                }

                var forbidMeasure = '%',
                    ratioX = parentWidth / 100,
                    ratioY = parentHeight / 100;

                if (isGrid && action.parent.properties.grid.type() == 'adaptive') {
                    forbidMeasure = 'px';
                    ratioX = 1 / parentWidth * 100;
                    ratioY = 1 / parentHeight * 100;
                }

                api.each(directions, function(i, direction){
                    if (properties[direction].measure() == forbidMeasure) {
                        offset[direction] = properties[direction].value() *
                            (['top', 'bottom'].indexOf(direction) > 0 ? ratioY : ratioX );
                    }
                });
            }

            function init () {
                if (container.hasParent()) {
                    action.setParent(container.getParent());
                }

                api.each(directions, function (i, direction) {
                    var val = container.properties[direction].value();
                    offset[direction] = api.isNumber(val) ? val : 0;
                });

                convertOffset();
            }

            // Set resulted offsets
            function setOffset () {
                if (!properties.bottom.bind() || properties.top.bind()) {
                    properties.top.value(offset.top + dy);
                    properties.top.measure(measure);
                }

                if (properties.right.bind()) {
                    properties.right.value(offset.right - dx);
                    properties.right.measure(measure);
                }

                if (properties.bottom.bind()) {
                    properties.bottom.value(offset.bottom - dy);
                    properties.bottom.measure(measure);
                }

                if (!properties.right.bind() || properties.left.bind()) {
                    properties.left.value(offset.left + dx);
                    properties.left.measure(measure);
                }
            }

            // set container parent, must be knockout container model
            action.setParent = function(model){
                if (api.isFunction(model.node)) {
                    action.parent = model;
                    var parentNode = $(action.parent.node());
                    parentWidth = parentNode.width();
                    parentHeight = parentNode.height();
                }
                if (action.parent &&
                    action.parent.type() == 'absolute' &&
                    ['adaptive', 'fixed'].indexOf(action.parent.properties.grid.type()) >= 0
                ) {
                    isGrid = true;
                }
            };

            // Move container by cursor
            action.move = function(x, y){
                dx = api.isNumber(x) ? x : 0;
                totalX = (totalX === null ? 0 : totalX) + dx;

                dy = api.isNumber(y) ? y : 0;
                totalY = (totalY === null ? 0 : totalY) + dy;

                if (isGrid) {
                    grid.move();
                } else {
                    dx = totalX;
                    dy = totalY;
                }

                setOffset();
            };

            // move container by keys
            action.moveCell = function(x, y){
                var stepX = 1,
                    stepY = 1;

                if (isGrid) {
                    var parentGrid = action.parent.properties.grid;

                    switch (parentGrid.type()) {
                        case 'adaptive':
                            measure = '%';
                            stepX = 100 / parentGrid.x();
                            stepY = 100 / parentGrid.y();
                            break;
                        case 'fixed':
                            measure = 'px';
                            stepX = parentGrid.width();
                            stepY = parentGrid.height();
                            break;
                    }
                }

                dx = totalX = (totalX === null ? 0 : totalX) + (api.isNumber(x) ? x : 0) * stepX;
                dy = totalY = (totalY === null ? 0 : totalY) + (api.isNumber(y) ? y : 0) * stepY;

                grid.fixPosition(stepX, stepY);

                setOffset();
            };

            init();
        }

        function loadButton ($selector, context) {
            $selector = $($selector);
            if ($selector.length) {
                if (api.isEmpty(context)) {
                    $selector.removeAttr('disabled');
                    $selector.each(function(){
                        $(this).html($(this).data('old-context'));
                    });
                } else {
                    $selector.attr('disabled', 'disabled');
                    $selector.each(function(){
                        $(this).data('old-context', $(this).html());
                        $(this).html(context);
                    });
                }
            }
        }

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

        constructor.selected = (function () {
            var object = ko.observable();
            var lock = false;

            object.lock = function() {
                lock = true;
            };

            object.unlock = function () {
                lock = false;
            };

            object.isLocked = function () {
                return lock;
            };

            return object;
        })();

        constructor.selected.subscribe(function (model) {
            var node;

            if (!model)
                return;

            node = model.node();

            if (!node)
                return;

            $('html').unbind('keydown')
                .unbind('keyup');

            $('.additional-modal:visible').trigger('eventHide');
        }, null, 'beforeChange');

        constructor.selected.subscribe(function (model) {
            var tabs = constructor.menu.tabs;
            var node;

            if (!model) {
                tabs.close(tabs.list.container);
                tabs.close(tabs.list.widget);
                tabs.close(tabs.list.block);

                return;
            }

            node = model.node();

            if (!node)
                return;

            $('html')
                .bind('keydown', function(event){
                    event.stopPropagation();
                    if ($('input:focus, select:focus, textarea:focus').size()) {
                        return;
                    }
                    // left - 37; top - 38; right - 39; bottom - 40
                    if (event.keyCode >= 37 && event.keyCode <= 40) {
                        var dx = 0,
                            dy = 0;
                        switch (event.keyCode) {
                            case 37:
                                dx = -1;
                                break;
                            case 38:
                                dy = -1;
                                break;
                            case 39:
                                dx = 1;
                                break;
                            case 40:
                                dy = 1;
                                break;
                        }

                        if (constructor.dragAction === null) {
                            constructor.dragAction = new dragAction(constructor.selected());
                        }
                        constructor.dragAction.moveCell(dx, dy);
                        event.preventDefault();
                    }
                    // delete
                    if (event.keyCode == 46) {
                        constructor.selected().remove();
                    }
                })
                .bind('keyup', function(event){
                    event.stopPropagation();
                    // left - 37; top - 38; right - 39; bottom - 40
                    if (event.keyCode >= 37 && event.keyCode <= 40) {
                        constructor.dragAction = null;
                        event.preventDefault();
                    }
                });
        });

        $('.constructor-back-menu').on('click', function(){
            var menuTabs = constructor.menu.tabs;
            if (menuTabs.active.getLast() === menuTabs.list.container) {
                constructor.selected(null);
                menuTabs.close(menuTabs.list.widget);
            }
            menuTabs.close();
        });

        // for modal window with detailed settings
        $.fn.additionalModal = function(settings){
            if (!api.isObject(settings)) {
                settings = {};
            }

            var modal = $(this),
                animateTime = settings.animateTime || 300,
                offset = settings.offset || 15;

            if (modal.size() > 1) {
                modal = $(modal.get(0));
            }

            modal.addClass('additional-modal');

            modal.off('eventHide')
                .on('eventHide', function(event){
                    modal.stop();
                    modal.fadeOut({queue: false, duration: animateTime});
                    modal.animate({left: 0}, {queue: false, duration: animateTime});
                });
            modal.off('eventShow')
                .on('eventShow', function(event, top){
                    $('.additional-modal:visible').trigger('eventHide');
                    modal.stop();

                    var windowHeight = $(window).height(),
                        modalHeight = modal.outerHeight(),
                        bottom = 'auto';

                    if (top + modalHeight + offset > windowHeight) {
                        modal.css({top: 'auto', bottom: offset});
                    } else {
                        modal.css({top: top, bottom: 'auto'});
                    }

                    if (modal.is(':hidden')) {
                        modal.fadeIn({queue: false, duration: animateTime});
                        modal.animate({left: offset + 'px'}, {queue: false, duration: animateTime});
                    } else {
                        modal.fadeIn(animateTime);
                    }
                });
            modal.off('eventToggle')
                .on('eventToggle', function(event, top){
                    modal.stop();
                    if (modal.is(':visible')) {
                        modal.trigger('eventHide');
                    } else {
                        modal.trigger('eventShow', top);
                    }
                });
            return modal;
        };

        // For custom bindingHandler "bind"
        constructor.bindings = {
            styler: function (node, bindings, settings) { // Must be after binding value
                if (!api.isObject(settings)) {
                    settings = {};
                }

                var $node = $(node),
                    resultSettings = {},
                    defaultSettings = {
                        onSelectOpened: function () {
                            var $dropdown = $('.jq-selectbox__dropdown', this),
                                $dropdownUl = $('ul', $dropdown),
                                dropdownUlHeight = $dropdownUl.height();
                            if (dropdownUlHeight < $dropdownUl.get(0).scrollHeight) {
                                $dropdown.addClass('nano');
                                $dropdownUl.addClass('nano-content');
                                $dropdown.css({'min-height': dropdownUlHeight});
                                $dropdown.nanoScroller({alwaysVisible: true});
                            } else {
                                $dropdown.removeClass('nano');
                                $dropdownUl.removeClass('nano-content');
                                $dropdown.css({'min-height': 'none'});
                            }

                            if (api.isFunction(settings.onSelectOpened)){
                                settings.onSelectOpened();
                            }
                        },
                        locales: {
                            en: {
                                selectPlaceholder: '<?= GetMessage('formstyler.select.placeholder') ?>',
                                selectSearchPlaceholder: '<?= GetMessage('formstyler.select.search.placeholder') ?>',
                                selectSearchNotFound: '<?= GetMessage('formstyler.select.search.notfound') ?>',
                                filePlaceholder: '<?= GetMessage('formstyler.file.placeholder') ?>',
                                fileBrowse: '<?= GetMessage('formstyler.file.browse') ?>',
                                fileNumber: '<?= GetMessage('formstyler.file.number') ?>'
                            }
                        }
                    };

                api.each(settings, function(i, value){
                    resultSettings[i] = value;
                });
                api.each(defaultSettings, function(i, value){
                    resultSettings[i] = value;
                });

                $node.styler(resultSettings);

                api.each(bindings, function(key, bind){
                    if (ko.isObservable(bind)) {
                        bind.subscribe(function () {
                            $node.trigger('refresh');
                        });
                    }
                });
            },
            showAdditional: function (node, bindings, selector) {
                var $node = $(node),
                    $modal = $(selector).additionalModal();

                $node.css({cursor: 'pointer'})
                    .off('click')
                    .on('click', function(event){
                        event.stopPropagation();
                        $modal.trigger('eventToggle', $node.parent().offset().top);
                    });
            }
        };

        // values must be observables, isActive must be observable
        constructor.shareValues = function(values, isActive){
            var busy = false;

            if (!api.isArray(values)) {
                values = [values];
            }

            api.each(values, function(i, value){
                value.subscribe(function (data) {
                    if (!busy) {
                        busy = true;
                        if (isActive()) {
                            api.each(values, function (j, val) {
                                if (i !== j) {
                                    val(data);
                                }
                            });
                        }
                        busy = false;
                    }
                });
            });
        };

        <?php include(__DIR__.'/script/models.php') ?>
        <?php include(__DIR__.'/script/grid.js') ?>
        <?php include(__DIR__.'/script/actions.js') ?>

        (function (models) {
            (function (container) {
                container.on('created', function (event, self, data) {
                    var properties = self.properties,
                        background = properties.background,
                        border = properties.border;

                    properties.grid.correct.width.subscribe(function(data){
                        if (data) {
                            api.each(self.containers(), function (i, item) {
                                constructor.grid.correct.size(item);
                            });
                        }
                    });
                    properties.grid.correct.height.subscribe(function(data){
                        if (data) {
                            api.each(self.containers(), function (i, item) {
                                constructor.grid.correct.size(item);
                            });
                        }
                    });

                    border.radius.measure('px');
                    api.each(directions, function(i, direction){
                        properties[direction].sum = ko.computed(function(){
                            if (self.getParentType() != 'absolute') {
                                return 'initial';
                            } else {
                                return properties[direction].summary();
                            }
                        });

                        border[direction].width.minusSum = function() {
                            var sum = border[direction].width.calculated();
                            var val = parseInt(sum);
                            if (val > 0) {
                                sum = '-' + sum;
                            }
                            return sum;
                        };

                        border[direction].radius.measure('px');
                    });
                    border.radius.print = ko.computed(function(){
                        var val = border.radius.summary();
                        if (!val) {
                            val = 0 + border.radius.measure();
                        }
                        return val;
                    });

                    background.getSize = function(){
                        if (background.size.type() == 'custom') {
                            return background.size.calculated();
                        } else {
                            return background.size.type();
                        }
                    };
                    background.image.load = function(data, e) {
                        var file = e.target.files[0];

                        if (file) {
                            constructor.gallery.upload(file, function (data) {
                                background.image.url(data.value);
                            });
                        }
                    };
                    background.image.delete = function(){
                        background.image.url(null);
                    };
                    background.image.calculated = ko.computed(function(){
                        var url = constructor.environment.path.handle(background.image.url());

                        if (url)
                            return 'url('+ url +')';

                        return null;
                    });

                    properties.getOpacity = ko.computed(function(){
                        return 1 - properties.opacity();
                    });
                    properties.getOpacityPercent = ko.computed(function(){
                        var value = properties.opacity();
                        if (api.isEmpty(value)) {
                            value = 0;
                        }
                        return Math.floor(value * 100) + '%';
                    });

                    properties.margin.isShared = ko.observable(0);
                    constructor.shareValues(
                        [
                            properties.margin.top.value,
                            properties.margin.right.value,
                            properties.margin.bottom.value,
                            properties.margin.left.value
                        ],
                        properties.margin.isShared
                    );

                    properties.padding.isShared = ko.observable(0);
                    constructor.shareValues(
                        [
                            properties.padding.top.value,
                            properties.padding.right.value,
                            properties.padding.bottom.value,
                            properties.padding.left.value
                        ],
                        properties.padding.isShared
                    );

                    border.radius.isShared = ko.observable(0);
                    constructor.shareValues(
                        [
                            border.top.radius.value,
                            border.right.radius.value,
                            border.bottom.radius.value,
                            border.left.radius.value
                        ],
                        border.radius.isShared
                    );

                    $(self.node()).checkSizes();
                });
            })(models.container);
        })(constructor.models);

        $(document)
            .on('mouseover', '.constructor-container', function (event) {
                var containers = $('.constructor-container');
                var container = $(this);
                var target = null;

                event.stopPropagation();
                containers.removeClass('constructor-container-hovered');

                if (!constructor.selected.isLocked()) {
                    if (container.hasClass('constructor-container-simple')) {
                        target = container.parent().closest('.constructor-container');
                    } else {
                        target = container;
                    }
                    
                    target.addClass('constructor-container-hovered');
                }
            }).on('mouseout', '.constructor-container', function(event) {
                var container = $(this);

                event.stopPropagation();
                container.removeClass('constructor-container-hovered');
            });

        constructor.load(<?= $data ?>);

        <?php include(__DIR__.'/script/settings.js') ?>
        <?php include(__DIR__.'/script/objects.js') ?>

        (function (objects, widgets) {
            objects.add({
                'type': 'container',
                'name': <?= JavaScript::toObject(GetMessage('widget.container')) ?>
            });

            objects.add({
                'available': function () {
                    return constructor.settings.development();
                },
                'type': 'structure',
                'name': <?= JavaScript::toObject(GetMessage('widget.structure')) ?>
            });

            objects.add({
                'type': 'component',
                'name': <?= JavaScript::toObject(GetMessage('widget.component')) ?>
            });

            objects.add({
                'type': 'block',
                'name': <?= JavaScript::toObject(GetMessage('widget.block')) ?>
            });

            api.each(widgets, function (index, widget) {
                objects.add({
                    'type': 'widget',
                    'code': widget.code(),
                    'name': widget.name(),
                    'icon': widget.icon()
                });
            });
        })(constructor.objects, constructor.widgets())

        constructor.on('save', function (event, constructor, result) {
            var $button = $('.constructor-save-button'),
                data = {
                    action: 'template.save',
                    data: JSON.stringify(result)
                };

            loadButton($button, '<?= GetMessage('button.save.process') ?>');
            $.ajax({
                type: 'POST',
                cache: false,
                dataType: 'json',
                data: data,
                success: function (response) {
                    notify('<?= GetMessage('result.save.success') ?>', 'success');
                    loadButton($button, null);
                    console.log(response);
                },
                error: function(response){
                    console.log(response);
                    notify('<?= GetMessage('result.save.error') ?>', 'danger');
                    loadButton($button, null);
                }
            });
        });

        constructor.blocks = (function(){
            var blocks;
            var data;

            data = <?= JavaScript::toObject($blocksData) ?>;
            blocks = {};
            blocks.models = (function () {
                var models;

                models = {};
                models.category = (function () {
                    var model;

                    model = function (data) {
                        var self;

                        data = api.isObject(data) ? data : {};
                        self = this;
                        self.code = ko.computed(function () { return data.code; });
                        self.sort = ko.computed(function () { return data.sort; });
                        self.name = ko.computed(function () { return data.name; });
                        self.isSelected = ko.computed(function () {
                            return blocks.categories.selected() === self;
                        });
                        self.select = function () {
                            blocks.categories.selected(self);
                        };
                        self.toggle = function () {
                            if (self.isSelected()) {
                                blocks.categories.selected(null);
                            } else {
                                self.select();
                            }
                        };
                        self.templates = ko.computed(function () {
                            return blocks.templates.find(function (index, template) {
                                if (self.code() == null)
                                    return true;

                                return template.category() === self;
                            });
                        });
                    };

                    model.is = function (object) {
                        return object instanceof model;
                    };

                    return model;
                })();
                models.template = (function () {
                    var model;

                    model = function (data) {
                        var self;

                        data = api.isObject(data) ? data : {};
                        self = this;
                        self.code = ko.computed(function () { return data.code; });
                        self.sort = ko.computed(function () { return data.sort; });
                        self.name = ko.computed(function () { return data.name; });
                        self.category = ko.computed(function () { return blocks.categories.get(data.category); });
                        self.image = ko.computed(function () { return data.image; });
                        self.select = function () {
                            constructor.menu.tabs.list.blocks.select(self.code());
                        }
                    };

                    model.is = function (object) {
                        return object instanceof model;
                    };

                    return model;
                })();

                return models;
            })();

            blocks.categories = (function () {
                var categories;

                categories = ko.observableArray([]);
                categories.selected = ko.observable();
                categories.get = function (code) {
                    return categories.findOne(function (index, item) {
                        return item.code() === code;
                    });
                };

                return categories;
            })();

            blocks.templates = ko.observableArray([]);

            api.each(data.categories, function (index, category) {
                blocks.categories.push(new blocks.models.category(category));
            });

            api.each(data.templates, function (index, template) {
                blocks.templates.push(new blocks.models.template(template));
            });

            constructor.menu.tabs.active.subscribe(function() {
                blocks.categories.selected(blocks.categories()[0]);
            });

            return blocks;
        })();


        ko.applyBindings(constructor, constructor.nodes.root.get(0));

        $(document).on('click', '.constructor-container-component', function(){
            return false;
        });

        // Close opened dialogs
        $(document)
            .on('click', '.ui-widget-overlay.ui-front, .constructor-dialog-close', function(){
                constructor.dialogs.closeAll();
            }).on('click', '.constructor-dialog-search', function(event){
                event.stopPropagation();
                $('input[type=text]', this).trigger('focus');
            });

        // bicycle for additional modal
        $(window).on('click', function(event) {
            var target = $(event.target);
            var modals = $('.additional-modal:visible');

            if (
                target.closest('.additional-modal').length > 0 ||
                target.closest('.ui-menu').length > 0 ||
                target.is('.ui-menu-item-wrapper') ||
                target.is('.ui-selectmenu-text')
            ) return;

            modals.trigger('eventHide');
        });

        // Add widget on click
        $(document).on('click', '.constructor-widget', function(){
            var selected = constructor.selected();
            if (selected) {
                var containerType = $(this).data('type'),
                    widgetCode = $(this).data('code');
                var container = constructor.actions.create(selected, containerType, widgetCode);
                $('.constructor-structure').scrollTo($('> .constructor-container-panel-wrapper > .constructor-container-panel', container.node()));
                if (container) {
                    var menuTabs = constructor.menu.tabs;
                    menuTabs.open(menuTabs.list.container);
                }
            }
        });

        $(document).ready(function () {
            intec.ui.update();
        });
    })(jQuery, intec);
</script>