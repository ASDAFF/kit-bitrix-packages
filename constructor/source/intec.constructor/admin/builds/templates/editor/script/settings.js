(function (settings) {
    settings.development = ko.observable(false);
    settings.containers = {};
    settings.containers.structure = ko.observable(true);
    settings.containers.hidden = ko.observable(false);
})(constructor.settings);