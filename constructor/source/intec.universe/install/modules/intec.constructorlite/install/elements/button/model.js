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
    height(40);
    height.measure('px');
}

if (resolution === false) {
    properties.link = self.attribute('link', resolution);
    properties.link.target = self.attribute('linkTarget', resolution);

    properties.text = self.attribute('text', resolution);
    properties.text.color = {};
    properties.text.color.default = self.attribute('textColor', resolution);
    properties.text.color.hover = self.attribute('textColorHover', resolution);
    properties.text.font = self.attribute('textFont', resolution);
    properties.text.style = self.attribute('textStyle', resolution);

    properties.border = self.attribute('border', resolution);
    properties.border.color = {};
    properties.border.color.default = self.attribute('borderColor', resolution);
    properties.border.color.hover = self.attribute('borderColorHover', resolution);
    properties.border.style = self.attribute('borderStyle', resolution);
    properties.border.radius = self.attribute('borderRadius', resolution);

    properties.background = {};
    properties.background.color = {};
    properties.background.color.default = self.attribute('backgroundColor', resolution);
    properties.background.color.hover = self.attribute('backgroundColorHover', resolution);
    properties.background.opacity = self.attribute('backgroundOpacity', resolution);
    properties.background.opacity.type(api.type.float);
    properties.background.opacity.summary = ko.computed(function () {
        return ((100 - properties.background.opacity()) / 100).toFixed(2);
    });

    api.each([
        'textSize',
        'textLetterSpacing',
        'textLineHeight',
        'borderWidth'
    ], function (index, name) {
        var attribute;
        var measures;

        attribute = self.attribute(name, resolution);
        attribute.type(api.type.float);

        if (name === 'borderWidth') {
            measures = ko.observableArray(['px', 'em', 'cm']);
        } else {
            measures = ko.observableArray(['px', 'pt', '%', 'em']);
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

        if (name === 'textSize') {
            properties.text.size = attribute;
        } else if (name === 'textLetterSpacing') {
            properties.text.letterSpacing = attribute;
        } else if (name === 'textLineHeight') {
            properties.text.lineHeight = attribute;
        } else if (name === 'borderWidth') {
            properties.border.width = attribute;
        }
    });

    properties.border.summary = ko.computed(function () {
        if (
            !properties.border() ||
            !properties.border.color.default() ||
            !properties.border.style() ||
            !properties.border.width()
        ) return null;

        return properties.border.width.summary() + ' ' +
            properties.border.style() + ' ' +
            properties.border.color.default();
    });

    if (defaults) {
        properties.text('Button');
        properties.text.color.default('#ffffff');
        properties.text.color.hover('#ffffff');
        properties.text.style('none');
        properties.text.size(14);
        properties.text.size.measure('px');
        properties.text.letterSpacing(0);
        properties.text.letterSpacing.measure('px');
        properties.text.lineHeight(150);
        properties.text.lineHeight.measure('%');

        properties.border(false);
        properties.border.style('solid');
        properties.border.width(0);
        properties.border.width.measure('px');
        properties.border.radius(25);

        properties.background.color.default('#00b6f5');
        properties.background.color.hover('#b8def5');
        properties.background.opacity(0);
    }

    self.properties = properties;
}