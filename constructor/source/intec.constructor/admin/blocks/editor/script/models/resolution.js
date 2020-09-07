(function (resolution) {
    resolution.on('created', function (event, self) {
        self.isEditable = ko.computed(function () {
            return constructor.resolutions.editable() === self;
        });
    });
})(models.resolution);