(function (models) {
    (function (component) {
        component.prototype.isEmpty = function () {
            return !api.isDeclared(this.code());
        };
        component.prototype.refresh = function (callback) {
            var self = this;
            var environment = constructor.environment;
            var site = null;
            var template = environment.template();
            var directory = null;

            if (environment.site()) {
                site = environment.site().id();
                directory = environment.site().directory();
            }

            if (self.isEmpty())
                return;

            self.refreshing(true);

            $.ajax({
                type: "POST",
                data: {
                    'action': 'components.view',
                    'code': self.code(),
                    'template' : self.template(),
                    'constants': {
                        'site': site,
                        'template': template,
                        'directory': directory
                    },
                    'parameters' : self.properties
                },
                success: function(data){
                    self.view(data);

                    if (api.isFunction(callback))
                        callback.call(self, data);
                },
                complete: function () {
                    self.refreshing(false);
                }
            });
        };

        component.on('create', function (event, self, data) {
            self.view = ko.observable(data.view);
        });

        component.on('created', function (event, self, data) {
            self.refreshing = ko.observable(false);
            self.refresh();
        });
    })(models.component);
})(constructor.models);