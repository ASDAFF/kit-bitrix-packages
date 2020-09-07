universe.basket = (function ($, api) {
    var self = {};
    var request = function (action, data, callback) {
        universe.ajax('basket.' + action, data, callback);
    };

    api.extend(self, api.ext.events(self, ['add', 'setQuantity', 'get', 'remove', 'clear', 'update', 'process', 'processed']));

    self.on('update', function(){
        self.trigger('processed');
    });

    self.add = function (data, callback) {
        self.trigger('process', data.id);
        request('add', data, function (response) {
            if (response === true) {
                if (api.isFunction(callback))
                    callback.apply(null);

                self.trigger('add', data);
                self.trigger('update', 'add', data);
            }
        });
    };

    self.setQuantity = function (data, callback) {
        self.trigger('process', data.id);
        request('setQuantity', data, function (response) {
            if (response === true) {
                if (api.isFunction(callback))
                    callback.apply(null);

                self.trigger('setQuantity', data);
                self.trigger('update', 'setQuantity', data);
            }
        });
    };

    self.remove = function (data, callback) {
        request('remove', data, function (response) {
            if (response === true) {
                if (api.isFunction(callback))
                    callback.apply(null);

                self.trigger('remove', data);
                self.trigger('update', 'remove', data);
            }
        });
    };

    self.clear = function (data, callback) {
        request('clear', data, function (response) {
            if (response === true) {
                if (api.isFunction(callback))
                    callback.apply(null);

                self.trigger('clear', data);
                self.trigger('update', 'clear', data);
            }
        });
    };

    self.update = function () {
        self.trigger('update', 'update');
    };

    return self;
})(jQuery, intec);