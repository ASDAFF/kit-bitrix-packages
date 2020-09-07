var self;
var width;
var height;
var properties;

self = this;
width = self.attribute('width', resolution);
height = self.attribute('height', resolution);
properties = {};

if (defaults) {
    width(200);
    width.measure('px');
    height(null);
    height.measure('px');
}

self.node.subscribe(function (node) {
   $(node).on('dblclick', function () {
       self.node.draggable(false);
   });
});

if (resolution === false) {
    properties.text = self.attribute('text', resolution);
    properties.text.font = self.attribute('textFont', resolution);

    if (defaults) {
        properties.text('Enter text here ...');
    }

    self.properties = properties;
} else {
    properties.text = {};
    properties.text.align = self.attribute('textAlign', resolution);
    properties.text.color = self.attribute('textColor', resolution);

    api.each([
        'textSize',
        'textLetterSpacing',
        'textLineHeight'
    ], function (index, name) {
        var attribute;
        var measures;

        attribute = self.attribute(name, resolution);
        attribute.type(api.type.float, true);
        measures = ko.observableArray(['px', 'pt', '%', 'em']);
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

        if (name === 'textSize') {
            properties.text.size = attribute;
        } else if (name === 'textLetterSpacing') {
            properties.text.letterSpacing = attribute;
        } else if (name === 'textLineHeight') {
            properties.text.lineHeight = attribute;
        }
    });

    if (defaults) {
        properties.text.align('left');
        properties.text.color('#000000');
        properties.text.size(null);
        properties.text.size.measure('px');
        properties.text.letterSpacing(null);
        properties.text.letterSpacing.measure('px');
        properties.text.lineHeight(null);
        properties.text.lineHeight.measure('%');
    }
}