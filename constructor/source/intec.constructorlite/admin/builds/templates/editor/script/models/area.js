(function (models) {
    (function (area) {
        var prototype = area.prototype;

        prototype.isEmpty = function () {
            return !api.isDeclared(this.id());
        };

        prototype.refresh = function (callback) {
            var self = this;

            if (self.isEmpty())
                return;

            self.refreshing(true);

            $.ajax({
                type: 'POST',
                dataType: 'json',
                data: {
                    'action': 'areas.structure',
                    'id': self.id()
                },
                success: function (data) {
                    if (data != null) {
                        self.container(new constructor.models.container(data));

                        if (api.isFunction(callback))
                            callback.call(self, data);
                    }
                },
                complete: function () {
                    self.refreshing(false);
                }
            });
        };

        area.on('created', function (event, self, data) {
            self.refreshing = ko.observable(false);

            if (self.container() == null)
                self.refresh();
        });
    })(models.area);
})(constructor.models);