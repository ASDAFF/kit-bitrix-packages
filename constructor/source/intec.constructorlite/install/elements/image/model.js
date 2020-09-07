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
    properties.source = self.attribute('source', resolution);
    properties.source.summary = ko.computed(function () {
        var image;

        image = properties.source();

        if (api.isEmpty(image))
            return null;

        return 'url(\'' + constructor.environment.path.handle(image) + '\')';
    });

    properties.position = {};
    properties.size = self.attribute('size', resolution);
    properties.repeat = self.attribute('repeat', resolution);

    properties.border = self.attribute('border', resolution);
    properties.border.color = self.attribute('borderColor', resolution);
    properties.border.style = self.attribute('borderStyle', resolution);
    properties.border.radius = self.attribute('borderRadius', resolution);

    api.each([
        'positionX',
        'positionY',
        'sizeX',
        'sizeY',
        'borderWidth'
    ], function (index, name) {
        var attribute;
        var measures;

        attribute = self.attribute(name, resolution);
        attribute.type(api.type.float);

        if (name === 'borderWidth') {
            measures = ko.observableArray(['px', 'em', 'cm']);
        } else {
            measures = ko.observableArray(['px', 'cm', '%', 'em']);
        }

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

        if (name === 'positionX') {
            properties.position.x = attribute;
        } else if (name === 'positionY') {
            properties.position.y = attribute;
        } else if (name === 'sizeX') {
            properties.size.x = attribute;
        } else if (name === 'sizeY') {
            properties.size.y = attribute;
        } else if (name === 'borderWidth') {
            properties.border.width = attribute;
        }
    });

    properties.position.summary = ko.computed(function () {
        var x;
        var y;

        x = properties.position.x;
        y = properties.position.y;

        if (api.isEmpty(x()) && api.isEmpty(y()))
            return null;

        return x.summary() + ' ' + y.summary();
    });

    properties.size.summary = ko.computed(function () {
        var value;
        var x;
        var y;

        value = properties.size;
        x = value.x;
        y = value.y;

        if (value() !== 'values') {
            return value();
        }

        return x.summary() + ' ' + y.summary();
    });

    properties.border.summary = ko.computed(function () {
        if (
            !properties.border() ||
            !properties.border.color() ||
            !properties.border.style() ||
            !properties.border.width()
        ) return null;

        return properties.border.width.summary() + ' ' +
            properties.border.style() + ' ' +
            properties.border.color();
    });

    if (defaults) {
        properties.repeat('no-repeat');
        properties.size('auto');
        properties.position.x(0);
        properties.position.x.measure('px');
        properties.position.y(0);
        properties.position.y.measure('px');

        properties.border(false);
        properties.border.style('solid');
        properties.border.width(0);
        properties.border.width.measure('px');
        properties.border.radius(0);
    }

    self.properties = properties;
}