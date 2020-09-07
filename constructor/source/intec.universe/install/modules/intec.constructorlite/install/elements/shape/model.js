var self;
var width;
var height;
var properties;

self = this;
width = self.attribute('width', resolution);
height = self.attribute('height', resolution);

if (defaults) {
    width(100);
    width.measure('px');
    height(100);
    height.measure('px');
}

if (resolution === false) {
    properties = {};
    properties.type = self.attribute('type', resolution);
    properties.color = self.attribute('color', resolution);

    properties.border = self.attribute('border', resolution);
    properties.border.color = self.attribute('borderColor', resolution);
    properties.border.style = self.attribute('borderStyle', resolution);
    properties.border.radius = self.attribute('borderRadius', resolution);

    properties.image = self.attribute('image', resolution);
    properties.image.contain = self.attribute('imageContain', resolution);
    properties.image.summary = ko.computed(function () {
        var image;

        image = properties.image();

        if (api.isEmpty(image))
            return null;

        return 'url(\'' + constructor.environment.path.handle(image) + '\')';
    });

    api.each([
        'borderWidth'
    ], function (index, name) {
        var attribute;
        var measures;

        attribute = self.attribute(name, resolution);
        attribute.type(api.type.float);

        measures = ko.observableArray(['px', 'em', 'cm']);

        attribute.measure = self.attribute(name + 'Measure', resolution);
        attribute.measure.filter(function (value) {
            if (measures.indexOf(value) < 0)
                this(measures()[0]);
        });
        attribute.measures = ko.computed(function () {
            return measures();
        });

        attribute.summary = ko.computed(function () {
            var value;
            var measure;

            value = attribute();
            measure = attribute.measure();

            if (value === null)
                return null;

            return value + measure;
        });

        properties.border.width = attribute;
    });

    properties.border.summary = ko.computed(function () {
        if (
            !properties.border() ||
            !properties.border.color() ||
            !properties.border.style() ||
            !properties.border.width.summary()
        ) return null;

        return properties.border.width.summary() + ' ' +
            properties.border.style() + ' ' +
            properties.border.color();
    });

    if (defaults) {
        properties.type('rectangle');
        properties.color('#fff794');

        properties.border(false);
        properties.border.width(0);
        properties.border.width.measure('px');
        properties.border.style('solid');
        properties.border.radius(0);
    }

    self.properties = properties;
}