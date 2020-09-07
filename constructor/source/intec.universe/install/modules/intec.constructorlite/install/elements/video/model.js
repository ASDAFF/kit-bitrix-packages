var self;
var width;
var height;
var properties;

self = this;
width = self.attribute('width', resolution);
height = self.attribute('height', resolution);
properties = {};

if (defaults) {
    width(100);
    width.measure('px');
    height(100);
    height.measure('px');
}

if (resolution === false) {
    properties.source = self.attribute('source', resolution);
    properties.allowFullScreen = self.attribute('allowFullScreen', resolution);

    if (defaults) {
        properties.source(null);
        properties.allowFullScreen(false);
    }

    self.properties = properties;
}