dialogs.gallery = (function () {
    var self;
    var gallery;

    self = dialog({
        'modal': true,
        'resizable': false,
        'draggable': false,
        'show': 'fade',
        'hide': 'fade',
        'width': 920,
        'position': { my: 'center', at: 'center'},
        'theme': 'white'
    });

    gallery = constructor.gallery;

    (function () {
        var action;
        var handler;

        action = self.open;
        self.select = function (image) {
            if (api.isFunction(handler))
                if (handler(image))
                    self.close();
        };
        self.open = function (callback) {
            handler = callback;
            self.data.filter('');
            action();
        };
    })();

    self.data = (function () {
        var root;
        var updating = ko.observable(false);
        var processed = ko.observableArray([]);

        root = {};
        root.filter = ko.observable('');
        root.images = ko.computed(function () {
            var filter = root.filter();
            var expression = new RegExp(RegExp.escape(filter));
            var result = [];

            api.each(gallery.images(), function (index, image) {
                if (expression.test(image.name))
                    result.push(image);
            });

            api.each(processed(), function (index, file) {
                result.push(file);
            });

            return result;
        });
        root.updating = ko.computed(function () { return updating(); });

        gallery.on('updating', function (event) {
            updating(true);
        });

        gallery.on('update', function (event, file) {
            updating(false);
        });

        updating.subscribe(function (state) {
            if (state === false)
                self.scroll.update();
        });

        gallery.on('uploading', function (event, file) {
            processed.push(file);
        });

        gallery.on('upload', function (event, file) {
            processed.remove(file);
        });

        return root;
    })();

    self.scroll = ko.models.scroll();
    self.uploader = (function () {
        var uploader;
        var active;

        active = ko.observable(false);
        uploader = {};
        uploader.active = ko.computed(function () { return active(); });
        uploader.node = ko.observable();
        uploader.node.subscribe(function (node) {
            var self;

            self = $(node);
            self.on('change', function (event) {
                if (gallery.processing())
                    return;

                if (node.files.length > 0) {
                    api.each(node.files, function (index, file) {
                        gallery.upload(file);
                    });

                    self.val(null);
                }
            });
        });
        uploader.zone = ko.observable();
        uploader.zone.subscribe(function (node) {
            var self;

            self = $(node);
            self.on('click', function () {
                if (uploader.node())
                    $(uploader.node()).trigger('click');
            });
            self.on('dragover', function (event) {
                active(true);
                event.preventDefault();
                event.stopPropagation();

                return false;
            });
            self.on('dragleave', function (event) {
                active(false);
                event.preventDefault();
                event.stopPropagation();

                return false;
            });
            self.on('drop', function (event) {
                active(false);
                event.preventDefault();
                event.stopPropagation();

                if (gallery.processing())
                    return false;

                api.each(event.originalEvent.dataTransfer.files, function (index, file) {
                    gallery.upload(file);
                });

                return false;
            });
        });

        return uploader;
    })();

    self.on('create', function (event, ui) {
        $(ui.target).parent().draggable({
            handle: '.constructor-dialog-header',
            containment: 'window'
        });
    });
    self.on('open', function (event, ui) {
        gallery.update();
    });

    self.expanded.subscribe(function () {
        self.scroll.update();
    });

    return self;
})();