(function (api) {
    var $ = api.$;

    ko.models = {};
    ko.models.switch = function (settings, node) {
        var self;
        var model;

        self = ko.observable(null);
        settings = api.extend({}, settings);

        self.subscribe(function (node) {
            if (model) model.destroy();
            model = new api.ui.controls.switch(node, settings);
        });

        self(node);

        return self;
    };

    if (api.isDeclared($.ui)) {
        var model;

        /**
         * @param control
         * @param options
         * @returns {*}
         */
        model = function (control, options) {
            var model;

            options = api.extend({
                'initialize': null,
                'methods': [],
                'events': {},
                'settings': {},
                'theme': {
                    'default': 'default',
                    'classes': []
                }
            }, options);

            model = ko.observable(null);
            model.getNode = function () {
                return $(model());
            };
            model.getSettings = function (settings) {
                var events;

                events = {};

                if (api.isObject(settings))
                    api.each(options.events, function (name, callback) {
                        var event;

                        event = settings[name];

                        if (api.isFunction(event))
                            events[name] = function () {
                                options.settings[name].apply(this, arguments);
                                return event.apply(this, arguments);
                            }
                    });

                settings = api.extend({}, settings, options.settings, events);

                if (!api.isDeclared(settings.theme))
                    settings.theme = options.theme.default;

                if (!api.isObject(settings.classes))
                    settings.classes = {};

                if (api.isDeclared(settings.theme))
                    api.each(options.theme.classes, function (index, name) {
                        if (api.isDeclared(settings.classes[name])) {
                            settings.classes[name] += ' ui-theme-' + settings.theme;
                        } else {
                            settings.classes[name] = 'ui-theme-' + settings.theme;
                        }
                    });

                settings.theme = undefined;

                return settings;
            };
            model.option = function (name, value) {
                if (!api.isUndefined(value))
                    return $(model())[control]('option', name, value);

                return $(model())[control]('option', name);
            };

            api.extend(model, api.ext.events(model));
            api.each(options.methods, function (i, method) {
                model[method] = function () {
                    return $(model())[control](method);
                };
            });
            api.each(options.events, function (name, callback) {
                options.settings[name] = function (event, ui) {
                    if (api.isFunction(callback))
                        callback.apply(model, arguments);

                    model.trigger(name, event, ui);
                }
            });

            if (api.isFunction(options.initialize))
                model.subscribe(function (node) {
                    options.initialize.call(model, node);
                });

            model(options.node);

            return model;
        };

        ko.models.accordion = function (settings, node) {
            settings = api.extend({
                'active': false,
                'collapsible': true,
                'heightStyle': 'content'
            }, settings);

            return model('accordion', {
                'initialize': function (node) {
                    $(node).accordion(this.getSettings(settings));
                },
                'methods': [
                    'destroy', 'disable', 'enable',
                    'instance', 'option', 'refresh',
                    'widget'
                ],
                'events': {
                    'activate': null,
                    'beforeActivate': null,
                    'create': null
                },
                'theme': {
                    'classes': [
                        'ui-accordion'
                    ]
                },
                'node': node
            });
        };
        ko.models.dialog = function (settings, node) {
            settings = api.extend({
                'modal': false
            }, settings);

            return model('dialog', {
                'initialize': function (node) {
                    $(node).dialog(this.getSettings(settings));
                },
                'methods': [
                    'close', 'destroy', 'instance',
                    'isOpen', 'moveToTop', 'open',
                    'widget'
                ],
                'events': {
                    'create': null,
                    'open': null,
                    'focus': null,
                    'dragStart': null,
                    'drag': null,
                    'dragEnd': null,
                    'resizeStart': null,
                    'resize': null,
                    'resizeStop': null,
                    'beforeClose': null,
                    'close': null
                },
                'settings': {
                    'autoOpen': false
                },
                'theme': {
                    'classes': [
                        'ui-dialog'
                    ]
                },
                'node': node
            });
        };
        ko.models.slider = function (settings, property, node) {
            var readonly;

            if (!ko.isObservable(property))
                return null;

            settings = api.extend({
                'min': 0,
                'max': 100,
                'step': 1,
                'range': 'min',
                'value': property()
            }, settings);

            return model('slider', {
                'initialize': function (node) {
                    var self;

                    self = this;
                    node = $(node);
                    node.slider(self.getSettings(settings));

                    property.subscribe(function (value) {
                        if (!$.contains(document, node.get(0))) {
                            this.dispose();
                            return;
                        }

                        if (!readonly) {
                            readonly = true;
                            node.slider('value', value);
                            readonly = false;
                        }
                    });
                },
                'methods': [
                    'destroy', 'disable', 'enable',
                    'instance', 'option', 'value',
                    'values', 'widget'
                ],
                'events': {
                    'create': null,
                    'change': null,
                    'slide': function (event, ui) {
                        if (!readonly) {
                            readonly = true;
                            property(ui.value);
                            readonly = false;
                        }
                    },
                    'start': null,
                    'stop': null
                },
                'settings': {},
                'theme': {
                    'classes': [
                        'ui-slider'
                    ]
                },
                'node': node
            });
        };
        ko.models.select = function (settings, node) {
            return model('selectmenu', {
                'initialize': function (node) {
                    var model;

                    model = this;
                    node = $(node);

                    node.selectmenu(this.getSettings(settings));
                    node.on('change', function () {
                        model.refresh();
                    })
                },
                'methods': [
                    'close', 'destroy', 'disable',
                    'enable', 'instance', 'menuWidget',
                    'open', 'option', 'refresh',
                    'widget'
                ],
                'events': {
                    'create': null,
                    'change': function () {
                        this.getNode().trigger('change');
                    },
                    'open': function () {
                        this.refresh();
                    },
                    'focus': null,
                    'close': null,
                    'select': null
                },
                'theme': {
                    'classes': [
                        'ui-selectmenu-button',
                        'ui-selectmenu-menu'
                    ]
                },
                'node': node
            });
        };
        ko.models.tooltip = function (settings, node) {
            return model('tooltip', {
                'initialize': function (node) {
                    $(node).tooltip(this.getSettings(settings));
                },
                'methods': [
                    'close', 'destroy', 'disable',
                    'enable', 'instance', 'open',
                    'option', 'widget'
                ],
                'events': {
                    'close': null,
                    'create': null,
                    'open': null
                },
                'theme': {
                    'classes': [
                        'ui-tooltip'
                    ]
                },
                'node': node
            });
        }
    }

    if (api.isDeclared($.fn.ColorPicker)) {
        ko.models.colorpicker = function (settings, property, node) {
            var self;
            var parse;

            if (!ko.isObservable(property))
                return null;

            self = ko.observable(null);
            settings = api.extend({}, settings);
            parse = function (value) {
                if (api.isString(value)) {
                    return api.toString(value).slice(1);
                }

                return '';
            };

            self.subscribe(function (node) {
                $(node).ColorPicker(api.extend({}, settings, {
                    'color': parse(property()),
                    'onSubmit': function (hsb, hex, rgb) {
                        if (api.isFunction(settings.onSubmit))
                            settings.onSubmit.apply(this, arguments);

                        property('#' + hex);
                    }
                }));

                property.subscribe(function (value) {
                    if (!$.contains(document, node)) {
                        this.dispose();
                        return;
                    }

                    $(node).ColorPickerSetColor(parse(value));
                });
            });

            self(node);

            return self;
        }
    }

    if (api.isDeclared($.fn.nanoScroller)) {
        ko.models.scroll = function (settings, node) {
            var self = ko.observable();

            settings = api.extend({}, settings);

            self.subscribe(function (node) {
                $(node).nanoScroller(settings);
            });

            self.subscribe(function () {
                $(self()).nanoScroller({destroy: true});
            }, self, 'beforeChange');

            self.update = function () {
                $(self()).nanoScroller();
            };

            self.scrollTo = function (element) {
                $(self()).nanoScroller({scrollTo: $(element)});
            };

            self(node);

            return self;
        };
    }

    if (api.isDeclared(CKEDITOR)) {
        ko.models.ckeditor = function (settings, property, node) {
            var self = ko.observable();
            var busy = false;
            var editor;

            if (!ko.isObservable(property))
                return null;

            settings = api.extend({}, settings);

            self.subscribe(function (node) {
                if (settings.inline) {
                    editor = CKEDITOR.inline(node, settings);
                } else {
                    editor = CKEDITOR.replace(node, settings);
                }

                editor.setData(property());
                editor.on(settings.inline ? 'blur' : 'change', function () {
                    if (!busy) {
                        busy = true;
                        property(editor.getData());
                        busy = false;
                    }
                });

                property.subscribe(function (value) {
                    if (!$.contains(document, node)) {
                        this.dispose();
                        return;
                    }

                    if (editor && !busy) {
                        busy = true;
                        editor.setData(value);
                        busy = false;
                    }
                });
            });

            self.getEditor = function () {
                return editor;
            };

            self(node);

            return self;
        }
    }

    if (api.isDeclared(window.CodeMirror)) {
        ko.models.codeMirror = function (settings, property, node) {
            var self = ko.observable();
            var busy = false;
            var editor;

            settings = api.extend({
                'theme': 'dracula',
                'lineNumbers': true
            }, settings);

            self.subscribe(function (node) {
                editor = CodeMirror.fromTextArea(node, settings);

                if (ko.isObservable(property)) {
                    var value = property();

                    if (!api.isString(value))
                        value = '';

                    editor.doc.setValue(value);
                    editor.on('change', function () {
                        if (!busy) {
                            busy = true;
                            property(editor.doc.getValue());
                            busy = false;
                        }
                    });

                    property.subscribe(function (value) {
                        if (!$.contains(document, node)) {
                            this.dispose();
                            return;
                        }

                        if (editor && !busy) {
                            busy = true;

                            if (!api.isString(value))
                                value = '';

                            editor.doc.setValue(value);
                            busy = false;
                        }
                    });
                }
            });

            self.getEditor = function () {
                return editor;
            };

            self(node);

            return self;
        }
    }
})(intec);