(function (models) {
    models.element = (function () {
        var model;

        model = function (data) {
            var self = this;

            self.type = ko.computed(function () { return data.type; });
            self.order = ko.observable(data.order);
        };
        model.is = function (object) {
            return object instanceof model;
        };

        constructor.isElement = function (object) {
            return model.is(object);
        };

        return model;
    })();
})(constructor.models);