var self = this;
var properties = model.properties;

if (stage === 'properties') {
    self.text = ko.observable();
    self.text.font = ko.observable();

    properties.text = self.text;
    properties.textFont = self.text.font;
} else {

}