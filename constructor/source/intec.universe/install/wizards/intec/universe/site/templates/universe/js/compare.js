if (universe) {
    universe.compare = (function ($, api) {
        var self = {};
        var request = function (action, data, callback) {
            universe.ajax('compare.' + action, data, callback);
        };

        api.extend(self, api.ext.events(self, ['add', 'remove', 'clear', 'update']));

        self.add = function (data, callback) {
            request('add', data, function (response) {
                if (response === true) {
                    if (api.isFunction(callback))
                        callback.apply(null);

                    self.trigger('add');
                    self.trigger('update');
                }
            });
        };

        self.get = function (data, callback) {
            request('get', data, function (response) {
                if (api.isDeclared(response)) {
                    if (api.isFunction(callback))
                        callback.call(null, response);

                    self.trigger('get', response);
                }
            });
        };

        self.remove = function (data, callback) {
            request('remove', data, function (response) {
                if (response === true) {
                    if (api.isFunction(callback))
                        callback.apply(null);

                    self.trigger('remove');
                    self.trigger('update');
                }
            });
        };

        self.clear = function (data, callback) {
            request('clear', data, function (response) {
                if (response === true) {
                    if (api.isFunction(callback))
                        callback.apply(null);

                    self.trigger('clear');
                    self.trigger('update');
                }
            });
        };

        return self;
    })(jQuery, intec);
}