/**
 * @var object data
 * @var object model
 */

var self = this;
var properties = model.properties;
var environment = constructor.environment;

if (stage === 'properties') {
    self.models = {};
    self.models.item = (function () {
        var model;

        model = function (data) {
            var self = this;

            data = api.isObject(data) ? data : {};
            model.trigger('create', self, data);

            self.name = ko.observable(data.name);
            self.description = ko.observable(data.description);
            self.link = ko.observable(data.link);
            self.image = ko.observable(data.image);
            self.image.calculated = ko.computed(function () {
                if (self.image())
                    return 'url(\'' + environment.path.handle(self.image()) + '\')';

                return null;
            });
            self.opacity = ko.observable(data.opacity);

            model.trigger('created', self, data);
        };
        model.prototype.save = function () {
            var self = this;
            var result;

            result = {};
            result.name = this.name();
            result.description = this.description();
            result.link = this.link();
            result.image = this.image();
            result.opacity = this.opacity();

            model.trigger('save', self, result);

            return result;
        };
        model.is = function (object) {
            return object instanceof model;
        };

        api.extend(model, api.extend(model, api.ext.events(constructor)));

        return model;
    })();

    self.dialogs = (function () {
        var dialogs;

        dialogs = {};
        dialogs.items = (function () {
            var dialog;

            dialog = ko.models.dialog({
                modal: true,
                resizable: false,
                draggable: true,
                show: 'fade',
                hide: 'fade',
                width: 666,
                position: { my: 'center', at: 'center'},
                dialogClass: 'constructor-dialog'
            });

            dialog.on('open', function () {
                dialogs.item.selected(null);
            });
            dialog.on('close', function () {
                dialogs.item.close();
            });

            return dialog;
        })();

        dialogs.item = (function () {
            var dialog;

            dialog = ko.models.dialog({
                modal: true,
                resizable: false,
                draggable: true,
                show: 'fade',
                hide: 'fade',
                width: 611,
                position: {my: 'center', at: 'center'},
                dialogClass: 'constructor-dialog'
            });
            dialog.selected = ko.observable(null);

            return dialog;
        })();

        return dialogs;
    })();

    self.header = {};
    self.header.value = ko.observable();
    self.header.show = ko.observable(false);

    self.caption = {};
    self.description = {};

    var opacity = function () {
        var self = {};

        self = ko.observable(0);
        self.type(api.type.integer, false);
        self.calculated = ko.computed(function () {
            var value;

            value = self();
            value = api.toInteger(value);

            if (api.isNaN(value))
                value = 0;

            return ((100 - value) / 100);
        });
        self.display = ko.computed(function () {
            var value;

            value = self();
            value = api.toInteger(value);

            if (api.isNaN(value))
                value = 0;

            return value + '%';
        });

        return self;
    };

    (function () {
        var switchable = function (value) {
            var property;

            property = ko.observable(value);
            property.switch = function () {
                if (property()) {
                    property(false);
                } else {
                    property(true);
                }
            };

            return property;
        };

        var settings = function () {
            var object = {};

            object.style = {};
            object.style.bold = switchable(false);
            object.style.italic = switchable(false);
            object.style.underline = switchable(false);
            object.text = {};
            object.text.align = new constructor.models.property.enumerable('center', ['left', 'center', 'right'], false);
            object.text.size = new constructor.models.property.measured(14, 'px', ['px', '%', 'em', 'pt']);
            object.text.color = ko.observable();
            object.opacity = opacity();

            return object;
        };

        self.caption = settings();
        self.description = settings();
    })();

    self.background = {};
    self.background.show = ko.observable(false);
    self.background.color = ko.observable();
    self.background.rounding = (function () {
        var self;
        var directions;
        var busy;

        self = new constructor.models.property.measured(0, 'px', ['px', '%']);
        busy = false;
        directions = ['top', 'right', 'bottom', 'left'];
        self.value.type(api.type.integer, true);
        self.shared = ko.observable(false);
        self.shared.switch = function () {
            self.shared(!self.shared());
        };

        api.each(directions, function (index, direction) {
            direction = self[direction] = new constructor.models.property.measured(null, 'px', ['px', '%']);
            direction.value.type(api.type.integer, true);
            direction.calculated = ko.computed(function () {
                if (!direction.summary()) {
                    return self.summary();
                } else {
                    return direction.summary();
                }
            });

            direction.value.subscribe(function (value) {
                if (self.shared() && !busy) {
                    busy = true;

                    api.each(directions, function (index, direction) {
                        direction = self[direction]
                        direction.value(value);
                    });

                    busy = false;
                }
            });
        });

        self.calculated = ko.computed(function () {
            var value = '';

            api.each(directions, function (index, direction) {
                direction = self[direction];

                if (direction.summary()) {
                    value = value + ' ' + direction.summary();
                } else if (self.summary()) {
                    value = value + ' ' + self.summary();
                } else {
                    value = value + ' 0 ';
                }
            });

            return value;
        });

        return self;
    })();
    self.background.opacity = opacity();

    self.items = ko.observableArray([]);
    self.items.add = function (data) {
        var item;

        item = new self.models.item(data);
        self.items.push(item);
        return item;
    };
    self.count = ko.observable(4);

    properties.header = self.header;
    properties.caption = self.caption;
    properties.description = self.description;
    properties.background = self.background;
    properties.items = new constructor.models.property.custom({
        'save': function () {
            var result = [];

            api.each(self.items(), function (index, item) {
                result.push(item.save());
            });

            return result;
        },
        'load': function (data) {
            api.each(data, function (index, item) {
                self.items.add(item);
            });
        }
    });
    properties.count = self.count;
} else {

}