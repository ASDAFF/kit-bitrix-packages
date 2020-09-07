constructor.structure = (function () {
    var structure;

    structure = {};
    structure.shifter = (function () {
        var shifter;

        shifter = {};
        shifter.frame = ko.observable();
        shifter.holder = ko.observable();
        shifter.container = ko.observable();
        shifter.display = ko.computed(function () {
            return constructor.isContainer(shifter.container());
        });

        return shifter;
    })();

    structure.creator = (function () {
        var creator;

        creator = {};
        creator.node = ko.observable();
        creator.object = ko.observable();
        creator.display = ko.computed(function () {
            return !api.isEmpty(creator.object);
        });

        return creator;
    })();

    return structure;
})();