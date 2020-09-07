(function (api) {
    var $ = api.$;

    ko.isObservableArray = function (object) {
        if (!ko.isObservable(object))
            return false;

        return object.__proto__ === ko.observableArray.fn;
    };

    (function () {
        var handlers;
        var bindings;

        handlers = ko.bindingHandlers;
        bindings = ko.virtualElements.allowedBindings;

        handlers.bind = {
            init: function (element, valueAccessor, allBindings, viewModel) {
                var value = null;

                if (api.isFunction(valueAccessor))
                    value = valueAccessor();

                if (api.isFunction(value)) {
                    var bindings = {};
                    if (api.isFunction(allBindings))
                        bindings = allBindings();

                    value(element, bindings, viewModel);
                }
            }
        };

        handlers.checked.initOriginal = ko.bindingHandlers.checked.init;
        handlers.checked.init = function (element, valueAccessor, allBindings) {
            ko.bindingHandlers.checked.initOriginal.apply(this, arguments);

            var isCheckbox = element.type == "checkbox",
                isRadio = element.type == "radio";

            if (!isCheckbox && !isRadio)
                return;

            ko.computed(function () {
                ko.utils.unwrapObservable(valueAccessor());
                $(element).trigger('change');
            }, null, { disposeWhenNodeIsRemoved: element });
        };

        bindings.function = true;
        handlers.function = {
            init: function (element, valueAccessor) {
                var value = valueAccessor();

                if (api.isFunction(value))
                    value();
            }
        };

        handlers.htmlTemplate = {
            init: function () {
                return { 'controlsDescendantBindings': false };
            },
            update: function (element, valueAccessor) {
                ko.utils.setHtml(element, valueAccessor());
            }
        };
    })();

    /** Observable extensions **/
    (function () {
        var prototype;

        prototype = ko.observable.fn;
        prototype.filter = function(callback, context, event) {
            var self;
            var notifier;

            self = this;
            notifier = self.notifySubscribers;

            if (api.isBoolean(callback)) {
                self.filtering = callback;
                return self;
            }

            if (!api.isFunction(callback))
                return self;

            self.filtering = false;
            self.notifySubscribers = function() {
                if (!self.filtering)
                    notifier.apply(this, arguments);
            };

            self.subscribe(function () {
                self.filtering = true;
                callback.apply(self, arguments);
                self.filtering = false;
            }, context, event);

            return self;
        };
        prototype.type = function(type, empty) {
            var self;
            var value;

            self = this;
            self.filter(function (value) {
                if (empty) {
                    if (api.isEmpty(value) && !api.isNull(value)) {
                        self.filter(false);
                        self(null);
                        self.filter(true);
                        return;
                    }

                    if (api.isNull(value))
                        return;
                }

                value = api.to(value, type);

                if (api.isNaN(value))
                    value = 0;

                self(value);
            });

            value = self();
            value = api.isUndefined(value) ? null : value;

            self(value);

            return self;
        };
    })();

    /** ObservableArray extensions **/
    (function () {
        var prototype;

        prototype = ko.observableArray.fn;
        prototype.find = function (callback) {
            var self;
            var result;

            self = this;
            result = [];

            if (!api.isFunction(callback))
                return result;

            api.each(self(), function (index, item) {
                if (callback.call(self, index, item)) result.push(item);
            });

            return result;
        };
        prototype.findOne = function (callback) {
            var self;
            var result;

            self = this;
            result = null;

            if (!api.isFunction(callback))
                return result;

            api.each(self(), function (index, item) {
                if (callback.call(self, index, item)) {
                    result = item;
                    return false;
                }
            });

            return result;
        };
        prototype.has = function (item) {
            return this.indexOf(item) >= 0
        };
    })();
})(intec);