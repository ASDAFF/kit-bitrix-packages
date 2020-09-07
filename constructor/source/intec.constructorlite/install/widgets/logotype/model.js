/**
 * @var object data
 * @var object model
 */

var self = this;
var properties = model.properties;
var environment = constructor.environment;

if (stage === 'properties') {
    self.type = new constructor.models.property.enumerable('image', ['image', 'text'], false);
    self.link = ko.observable(false);

    self.image = {};
    self.image.url = ko.observable();
    self.image.calculated = ko.computed(function () {
        if (self.image.url())
            return 'url(\'' + environment.path.handle(self.image.url()) + '\')';

        return null;
    });
    self.image.width = new constructor.models.property.measured(null, 'px', ['px', '%']);
    self.image.width.value.type(api.type.integer, true);
    self.image.height = new constructor.models.property.measured(100, 'px', ['px', '%']);
    self.image.height.value.type(api.type.integer, true);
    self.image.proportions = ko.observable(true);
    self.text = ko.observable();
    self.text.font = ko.observable();

    properties.type = self.type.value;
    properties.link = self.link;
    properties.image = self.image;
    properties.text = self.text;
    properties.textFont = self.text.font;
} else {

}