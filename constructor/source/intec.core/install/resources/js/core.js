(function ($) {

    // Convert form values to object
    $.fn.serializeObject = function() {
        var object = {};

        $.each(this.serializeArray(), function() {
            if (object[this.name]) {
                if (!object[this.name].push) {
                    object[this.name] = [object[this.name]];
                }
                object[this.name].push(this.value || '');
            } else {
                object[this.name] = this.value || '';
            }
        });

        return object;
    };

    // Running event when element sizes change
    $.fn.checkSizes = function (timeout) {
        var $target = $(this),
            width = $target.width(),
            height = $target.height();

        if ($target.data('isChecksizes'))
            return;

        if (!api.isNumber(timeout))
            timeout = 500;

        function checkSizes () {
            var newWidth = $target.width(),
                newHeight = $target.height(),
                changed = false;

            if (height !== newHeight) {
                height = newHeight;
                changed = true;
            }

            if (width !== newWidth) {
                width = newWidth;
                changed = true;
            }

            if (changed) {
                $target.trigger('resize');
            }
        }

        $target.data('isChecksizes', true);
        setInterval(checkSizes, timeout);
    };

    var wrapper = function (object) {
        this.get = function () {
            return object;
        };
    };

    var api = function (object) {
        if (object instanceof wrapper)
            return object;

        return new wrapper(object);
    };

    api.type = {
        'string': 'string',
        'number': 'number',
        'integer': 'integer',
        'float': 'float',
        'nan': 'nan',
        'object': 'object',
        'array': 'array',
        'function': 'function',
        'null': 'null',
        'undefined': 'undefined',
        'boolean': 'boolean'
    };

    api.isWrapper = function (object) {
        return object instanceof wrapper;
    };
    api.isString = function (object) {
        return typeof object == api.type.string;
    };
    api.toString = function (object) {
        return String(object);
    };
    api.isNumber = function (object) {
        return typeof object == api.type.number;
    };
    api.toNumber = function (object) {
        return Number(object);
    };
    api.isInteger = function (object) {
        if (api.isNumber(object))
            return object % 1 === 0;

        return false;
    };
    api.toInteger = function (object) {
        return parseInt(object);
    };
    api.isFloat = function (object) {
        if (api.isNumber(object))
            return object % 1 !== 0;

        return false;
    };
    api.toFloat = function (object) {
        return parseFloat(object);
    };
    api.isNaN = function (object) {
        return isNaN(object);
    };
    api.isObject = function (object) {
        return typeof object == api.type.object && object !== null && !(object instanceof Array);
    };
    api.isArray = function (object) {
        return (object instanceof Array);
    };
    api.isFunction = function (object) {
        return typeof object == api.type.function;
    };
    api.isNull = function (object) {
        return object === null;
    };
    api.isUndefined = function (object) {
        return typeof object == api.type.undefined;
    };
    api.isBoolean = function (object) {
        return typeof object == api.type.boolean;
    };
    api.toBoolean = function (object) {
        return Boolean(object);
    };
    api.isDeclared = function (object) {
        return !api.isNull(object) && !api.isUndefined(object);
    };
    api.isEmpty = function (object) {
        if (!api.isDeclared(object))
            return true;

        if (api.isNumber(object))
            return api.isNaN(object);

        if (api.isBoolean(object))
            return object === false;

        if (api.isArray(object) || api.isString(object))
            return object.length === 0;

        if (api.isObject(object))
            return api.isEmpty(Object.getOwnPropertyNames(object));

        return false;
    };
    api.is = function () {
        if (arguments.length == 0)
            return false;

        var object = arguments[0];
        var types = Array.prototype.slice.call(arguments, 1);
        var result = false;

        if (types.length == 0)
            return false;

        if (api.isArray(types[0]))
            types = types[0];

        api.each(types, function (index, type) {
            switch (type) {
                case api.type.string:   result = api.isString(object);      break;
                case api.type.number:   result = api.isNumber(object);      break;
                case api.type.integer:  result = api.isInteger(object);     break;
                case api.type.float:    result = api.isFloat(object);       break;
                case api.type.nan:      result = api.isNaN(object);         break;
                case api.type.object:   result = api.isObject(object);      break;
                case api.type.array:    result = api.isArray(object);       break;
                case api.type.function: result = api.isFunction(object);    break;
                case api.type.null:     result = api.isNull(object);        break;
                case api.type.undefined:result = api.isUndefined(object);   break;
                case api.type.boolean:  result = api.isBoolean(object);     break;
            }

            if (result)
                return false;
        });

        return result;
    };
    api.to = function (object, type) {
        var result = object;

        switch (type) {
            case api.type.string: result = api.toString(object); break;
            case api.type.number: result = api.toNumber(object); break;
            case api.type.integer: result = api.toInteger(object); break;
            case api.type.float: result = api.toFloat(object); break;
            case api.type.boolean: result = api.toBoolean(object); break;
        }

        return result;
    };
    api.each = function (object, callback) {
        var key;

        if (api.isFunction(callback)) {
            if (api.isObject(object)) {
                for (key in object) {
                    if (object.hasOwnProperty(key)) {
                        if (callback(key, object[key]) == false) {
                            break;
                        }
                    }
                }
            } else if (api.isArray(object)) {
                var length = object.length;

                for (key = 0; key < length; key++) {
                    if (callback(key, object[key]) == false) {
                        break;
                    }
                }
            }
        }
    };
    api.some = function (object, callback) {
        var result = false;

        if (api.isFunction(callback)) {
            api.each(object, function (key, element) {
                if (callback(key, element) == true) {
                    result = true;
                    return false;
                }
            });
        }

        return result;
    };
    api.every = function (object, callback) {
        var result = true;

        if (api.isFunction(callback)) {
            api.each(object, function (key, element) {
                if (callback(key, element) == false) {
                    result = false;
                    return false;
                }
            });
        }

        return result;
    };
    api.extend = function () {
        if (arguments.length == 0) return {};

        var object = arguments[0];
        var objects = Array.prototype.slice.call(arguments, 1);

        if (!api.is(object, api.type.object, api.type.function, api.type.array))
            object = {};

        api.each(objects, function (index, value) {
            api.each(value, function (key, value) {
                if (api.is(value, api.type.object)) {
                    object[key] = api.extend(object[key], value);
                } else {
                    object[key] = value;
                }
            });
        });

        return object;
    };
    api.inherit = function (children, parent) {
        if (!api.isObject(children) && !api.isObject(parent))
            return;

        var object = function () {};

        object.prototype = parent.prototype;
        children.prototype = new object();
        children.prototype.constructor = children;
        children.superclass = parent;
    };
    api.bind = function (method, context) {
        if (!api.isFunction(method))
            return null;

        return function () {
            return method.apply(context, arguments);
        }
    };

    api.objectToArray = function (obj) {
        return Object.keys(obj).map(function (key) { return obj[key]; });
    };

    api.isEqual = function (a, b) {
        if (api.isObject(a) && api.isObject(b)) {
            var aKeys = Object.keys(a),
                bKeys = Object.keys(b);

            if (aKeys.length != bKeys.length )
                return false;

            return !aKeys.filter(function(key){
                if (api.is(a[key], [api.type.object, api.type.array])) {
                    return !api.isEqual(a[key], b[key]);
                }
                return a[key] !== b[key];
            }).length;
        }

        return false;
    };

    api.inArray = function (needle, haystack, isStrict) {
        var result = false;
        isStrict = !!isStrict;

        api.each(haystack, function(key, value) {
            if (api.is(needle, [api.type.object, api.type.array])) {
                if (api.isEqual(needle, value)) {
                    result = true;
                    return false;
                }
            } else if (isStrict && value === needle || !isStrict && value == needle) {
                result = true;
                return false;
            }

        });

        return result;
    };

    api.string = {};
    api.string.replace = function (string, rules) {
        string = api.toString(string);

        if (!api.isObject(rules))
            return string;

        api.each(rules, function (from, to) {
            string = string.replace(new RegExp(RegExp.escape(from), 'g'), to);
        });

        return string;
    };

    api.ext = {};

    /** Класс событий **/
    (function () {
        var getName = function (name) {
            name = api.toString(name);
            return name.split('.').shift();
        };
        var getNamespace = function (name) {
            var namespace;
            namespace = api.toString(name);
            namespace = namespace.split('.');
            namespace.shift();
            namespace = namespace.join('.');
            return namespace.length ? namespace : null;
        };

        var event = function () {
            var self = this;

            self.once = false;
            self.name = null;
            self.context = null;
            self.function = null;
        };
        event.prototype.getName = function () {
            return getName(this.name);
        };
        event.prototype.getNamespace = function () {
            return getNamespace(this.name);
        };
        event.prototype.fire = function (context, parameters) {
            var self = this;
            parameters.unshift(self);
            if (this.context) context = this.context;
            return self.function.apply(context, parameters);
        };

        var events = function (context, available) {
            var self = this;
            var list = [];

            list.add = function (events, callback, context, once) {
                var self = this;

                if (api.isFunction(callback) && (
                        api.isString(events) ||
                        api.isArray(events) ||
                        api.isObject(events)
                    ) && !api.isEmpty(events)) {
                    if (api.isString(events)) { events = events.split(' '); }

                    api.each(events, function (index, eventName) {
                        var name = event.prototype.getName.call({ name: eventName });

                        if (api.isEmpty(available) || api.some(available, function (key, value) {
                                return name == value;
                            })) {
                            var instance = new event();

                            instance.name = eventName;
                            instance.once = once;
                            instance.function = callback;
                            instance.context = context;

                            self.push(instance);
                        }
                    });
                }
            };

            list.removeAt = function (id) {
                var self = this;
                if (api.isUndefined(id) || api.isNull(id)) return;
                if (!api.isArray(id)) id = [id];
                var shift = 0;

                api.each(id, function (index, id) {
                    self.splice(id - shift, 1);
                    shift++;
                });
            };

            list.remove = function (events, callback) {
                var self = this;
                var indexes = [];

                if (!api.isString(events) && !api.isArray(events) && !api.isObject(events)) { return; }
                if (api.isString(events)) { events = events.split(' '); }

                api.each(self, function (index, instance) {
                    api.each(events, function (index, eventName) {
                        var name = getName(eventName);
                        var namespace = getNamespace(eventName);

                        if (name != instance.getName())
                            return true;

                        if (namespace !== null)
                            if (namespace !== instance.getNamespace())
                                return true;

                        if (api.isFunction(callback))
                            if (instance.function != callback)
                                return true;

                        indexes.push(index);
                        return true;
                    });
                });

                self.removeAt(indexes);
            };

            self.setContext = function (object) { context = object; };
            self.getContext = function () { return context; };

            self.on = function (events, callback, context) {
                list.add(events, callback, context, false);
            };

            self.once = function (events, callback, context) {
                list.add(events, callback, context, true);
            };

            self.off = function (events, callback) {
                list.remove(events, callback);
            };

            //event, argument1, ..., argumentN
            self.trigger = function () {
                if (arguments.length == 0) return false;

                var eventName = arguments[0];
                var name = event.prototype.getName.call({ name: eventName });
                var namespace = event.prototype.getNamespace.call({ name: eventName });
                var indexes = [];
                var result = true;
                var tempParameters = arguments;

                api.each(list, function (index, instance) {
                    var parameters = Array.prototype.slice.call(tempParameters, 1);

                    if (name != instance.getName())
                        return true;

                    if (namespace !== null)
                        if (namespace !== instance.getNamespace())
                            return true;

                    if (instance.fire(context, parameters) === false)
                        result = false;

                    if (instance.once) indexes.push(index);
                });

                list.removeAt(indexes);
                return result;
            };
        };

        api.ext.events = function (context, available) {
            return new events(context, available)
        };
    })();

    api.fn = wrapper.prototype;
    api.fn.isString = function () { return api.isString(this.get()) };
    api.fn.toString = function () { return api.toString(this.get()) };
    api.fn.isNumber = function () { return api.isNumber(this.get()) };
    api.fn.toNumber = function () { return api.toNumber(this.get()) };
    api.fn.isInteger = function () { return api.isInteger(this.get()) };
    api.fn.toInteger = function () { return api.toInteger(this.get()) };
    api.fn.isFloat = function () { return api.isFloat(this.get()) };
    api.fn.toFloat = function () { return api.toFloat(this.get()) };
    api.fn.isNaN = function () { return api.isNaN(this.get()) };
    api.fn.isObject = function () { return api.isObject(this.get()) };
    api.fn.isArray = function () { return api.isArray(this.get()) };
    api.fn.isFunction = function () { return api.isFunction(this.get()) };
    api.fn.isNull = function () { return api.isNull(this.get()) };
    api.fn.isUndefined = function () { return api.isUndefined(this.get()) };
    api.fn.isBoolean = function () { return api.isBoolean(this.get()) };
    api.fn.isDeclared = function () { return api.isDeclared(this.get()) };
    api.fn.isEmpty = function () { return api.isEmpty(this.get()) };
    api.fn.is = function () {
        Array.prototype.unshift.call(arguments, this.get());
        return api.is.apply(api, arguments);
    };
    api.fn.to = function (type) { return api.to(this.get(), type); };
    api.fn.each = function (callback) { api.each(this.get(), callback); return this; };
    api.fn.some = function (callback) { api.some(this.get(), callback); return this; };
    api.fn.every = function (callback) { api.every(this.get(), callback); return this; };

    api.ui = {};
    api.ui.update = function () {
        api.each(api.ui.controls, function (key, constructor) {
            $('[data-ui-' + key + ']').each(function () {
                var element = $(this);
                if (element.parent().is('label.api-ui-switch')) {
                    return;
                }
                var attribute = 'ui' + key.charAt(0).toUpperCase() + key.slice(1);
                var object = element.data(attribute);

                if (!(object instanceof constructor)) {
                    try {
                        object = eval('(' + object + ')');
                    } catch (error) {}

                    element.data(attribute, new constructor(element, api.isObject(object) ? object : {}));
                }
            })
        });
    };
    api.ui.controls = {};
    api.ui.controls.switch = function (element, settings) {
        var wrap, control, flag;
        var self = this;

        element = $(element);
        settings = api.extend({}, api.ui.controls.switch.settings, settings);

        self.getElement = function () {
            return element;
        };
        self.getWrap = function () {
            return wrap;
        };
        self.getControl = function () {
            return control;
        };
        self.getFlag = function () {
            return flag;
        };

        self.isBuilt = function () {
            return api.isDeclared(wrap) && api.isDeclared(control) && api.isDeclared(flag);
        };

        self.build = function () {
            if (self.isBuilt())
                return;

            wrap = $('<label />', {
                'class': settings.classes.wrap
            }).insertBefore(element);
            element.appendTo(wrap);
            control = $('<div />', {
                'class': settings.classes.control
            }).appendTo(wrap);
            flag = $('<div />', {
                'class': settings.classes.flag
            }).appendTo(control);

            element.on('change.api.ui.checkbox', function () {
                wrap.removeClass(settings.classes.active);

                if (element.prop('checked')) {
                    wrap.addClass(settings.classes.active);
                }
            });

            wrap.on('mouseover.api.ui.switch', function () {
                wrap.addClass(settings.classes.hover);
            }).on('mouseout.api.ui.switch', function () {
                wrap.removeClass(settings.classes.hover);
            });

            if (element.prop('checked')) {
                wrap.addClass(settings.classes.active);
            }
        };

        self.destroy = function () {
            if (!self.isBuilt())
                return;

            element.off('change.api.ui.switch');
            wrap.off('mouseover.api.ui.switch')
                .off('mouseout.api.ui.switch')
                .off('click.api.ui.switch');

            element.insertBefore(wrap);
            flag.remove();
            control.remove();
            wrap.remove();
        };

        self.build();
    };
    api.ui.controls.switch.settings = {
        'classes': {
            'wrap': 'api-ui-switch',
            'control': 'api-ui-switch-control',
            'flag': 'api-ui-switch-control-flag',
            'hover': 'api-ui-switch-hover',
            'active': 'api-ui-switch-active'
        }
    };

    if (!api.isFunction(RegExp.escape)) {
        RegExp.escape = function (value) {
            if (api.isString(value))
                return value.replace(/[\-\[\]\/\{\}\(\)\*\+\?\.\\\^\$\|]/g, "\\$&");

            return value;
        };
    }

    if (!api.isFunction(Math.count)) {
        Math.count = {};
        Math.count.decimals = function (value) {
            var count = 0;

            value = api.toFloat(value);

            while (value % 1 !== 0) {
                value = value * 10;
                count++;
            }

            return count;
        };
        Math.count.integers = function (value) {
            var count = 0;

            value = api.toFloat(value);

            while (value !== 0) {
                value = Math.floor(value / 10);
                count++;
            }

            return count;
        }
    }

    if (!api.isFunction(Math.extend)) {
        Math.extend = function (value, rank) {
            value = api.toFloat(value);

            for (i = 0; i < rank; i++)
                value = value * 10;

            return value;
        }
    }

    api.$ = $;

    window.intec = api;
})(jQuery);