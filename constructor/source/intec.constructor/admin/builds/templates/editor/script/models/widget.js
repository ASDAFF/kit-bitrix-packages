(function (models) {
    (function (widget) {
        widget.on('created', function (event, self, data, manager) {
            var send;

            send = function (action, settings, data, template, callback) {
                if (self.model() === null) return;
                if (self.template() === null && template) return;

                data = api.extend({}, data, {
                    'action': 'widget.' + action,
                    'widget': self.model().code(),
                    'template': template ? self.template().code() : null
                });

                settings = api.extend({}, settings, {
                    'type': 'POST',
                    'cache': false,
                    'data': data
                });

                if (api.isFunction(callback))
                    settings.success = callback;

                $.ajax(settings);
            };

            self.data.update = function (sync) {
                send('data', {
                    'dataType': 'json',
                    'async': !sync
                }, {
                    'properties': self.getProperties()
                }, true, function (response) {
                    self.data(response);
                });
            };

            if (api.isUndefined(self.data()))
                self.data.update(true);

            self.handle = function (parameters, callback, template) {
                parameters = api.isObject(parameters) ? parameters : {};
                send('handle', {
                    'dataType': 'json'
                }, {
                    'properties': self.getProperties(),
                    'parameters': parameters
                }, template, callback);
            };

            (function () {
                var action;

                action = function (widget, template) {
                    send('headers', {
                        'async': false
                    }, {
                        'apply': {
                            'widget': widget ? 1 : 0,
                            'template': template ? 1 : 0
                        }
                    }, true, function (response) {
                        $('head').append(response);
                    });
                };

                action(true, true);

                self.template.subscribe(function () {
                    action(false, true);
                });
            })();
        });
    })(models.widget)
})(constructor.models);