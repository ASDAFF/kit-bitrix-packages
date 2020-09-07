(function (models) {
    (function (block) {
        var prototype = block.prototype;

        prototype.copy = function (callback) {
            var self = this;

            $.ajax({
                type: "POST",
                dataType: 'json',
                data: {
                    'action': 'blocks.copy',
                    'block': self.id()
                },
                success: function (data) {
                    if (!api.isObject(data))
                        return;

                    if (api.isFunction(callback))
                        callback.call(constructor, new constructor.models.block({
                            'id': data.id,
                            'name': data.name
                        }));
                }
            });
        };

        prototype.isEmpty = function () {
            return !api.isDeclared(this.id());
        };

        prototype.refresh = function (callback) {
            var self = this;

            if (self.isEmpty())
                return;

            self.refreshing(true);

            $.ajax({
                type: "POST",
                data: {
                    'action': 'blocks.view',
                    'id': self.id()
                },
                success: function (data) {
                    self.view(data);

                    if (api.isFunction(callback))
                        callback.call(self, data);
                },
                complete: function () {
                    self.refreshing(false);
                }
            });
        };

        block.on('create', function (event, self, data) {
            self.view = ko.observable(data.view);
        });

        block.on('created', function (event, self, data) {
            self.refreshing = ko.observable(false);
            self.refresh();
        });
    })(models.block);
})(constructor.models);