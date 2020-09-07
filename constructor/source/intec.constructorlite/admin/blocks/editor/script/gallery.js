constructor.gallery = (function () {
    var module;
    var actions;
    var images;
    var models;
    var processing;

    module = {};
    images  = ko.observableArray([]);
    processing = ko.observable(false);

    models = {};
    models.image = (function () {
        var model;

        model = function (data) {
            var self = this;

            self.name = data.name;
            self.path = data.path;
            self.value = data.value;

            self.delete = function () {
                processing(true);
                module.trigger('deleting', self);
                module.trigger('processing', 'deleting', self);
                actions.delete(self, function () {
                    images.remove(self);
                    processing(false);
                    module.trigger('delete', self);
                    module.trigger('processed', 'delete', self);
                });
            };
        };

        model.is = function (object) {
            return object instanceof model;
        };

        return model;
    })();

    api.extend(module, api.ext.events(module));

    actions = {};
    actions.request = function (action, data, callback) {
        processing(true);
        data = api.extend({}, data, {
            'action': 'gallery.' + action
        });

        $.ajax({
            'type': 'POST',
            'cache': false,
            'dataType': 'json',
            'data': data,
            'success': function () {
                processing(false);
                callback.apply(module, arguments);
            },
            'error': function (response) {
                processing(false);
                console.error('Unexpected Gallery response on action "' + action + '".');
                console.error(response);
            }
        });
    };
    actions.list = function (callback) {
        actions.request('list', null, function (data) {
            var result = [];

            api.each(data, function (index, data) {
                result.push(new models.image(data));
            });

            if (api.isFunction(callback))
                callback.call(this, result);
        });
    };
    actions.upload = function (file, callback) {
        var data = new FormData();

        if (!file.type.match(/image.*/))
            return false;

        data.append('action', 'gallery.upload');
        data.append('file', file);

        $.ajax({
            'type': 'POST',
            'cache': false,
            'dataType': 'json',
            'contentType': false,
            'processData': false,
            'data': data,
            'success': function (data) {
                var image = null;

                if (api.isObject(data))
                    image = new models.image(data);

                if (api.isFunction(callback))
                    callback.call(module, image);
            },
            'error': function () {
                processing(false);
            }
        });
    };
    actions.delete = function (list, callback) {
        var names = [];

        if (api.isArray(list)) {
            api.each(list, function (index, item) {
                names.push(item.name);
            });
        } else {
            names = [list.name];
        }

        actions.request('delete', {
            'files': names
        }, function () {
            if (api.isArray(list)) {
                images.removeAll(list);
            } else {
                images.remove(list);
            }

            if (api.isFunction(callback))
                callback.call(this, list);
        });
    };

    module.processing = ko.computed(function () { return processing(); });
    module.images = ko.computed(function () { return images(); });
    module.upload = function (file, callback) {
        processing(true);
        module.trigger('uploading', file);
        module.trigger('processing', 'uploading', file);
        actions.upload(file, function (image) {
            if (module.isImage(image))
                images.push(image);

            processing(false);
            module.trigger('upload', file, image);
            module.trigger('processed', 'upload', file, image);

            if (api.isFunction(callback))
                callback.call(module, image);
        });
    };
    module.update = function (callback) {
        processing(true);
        module.trigger('updating');
        module.trigger('processing', 'updating');
        images.removeAll();
        actions.list(function (list) {
            api.each(list, function (index, item) {
                images.push(item);
            });

            processing(false);
            module.trigger('update', list);
            module.trigger('processed', 'update', list);

            if (api.isFunction(callback))
                callback.call(module, list);
        });
    };
    module.isImage = function (object) {
        return models.image.is(object);
    };

    return module;
})();