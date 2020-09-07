universe = (function ($, api) {
    var self;

    self = {};
    self = api.extend(self, api.ext.events(self));
    self.site = {
        'id': null,
        'directory': null
    };

    self.template = {
        'id': null,
        'directory': null
    };

    self.ajax = function(action, data, callback) {
        if (!api.isFunction(callback))
            callback = function () {};

        $.ajax({
            'type': 'POST',
            'url': self.site.directory,
            'cache': false,
            'dataType': 'json',
            'data': {
                'ajax': {
                    'action': action,
                    'data': data,
                    'request': 'y'
                }
            },
            success: callback,
            error: function(response){
                console.error(response);
            }
        });
    };

    self.page = function (page, query, data, callback) {
        if (!api.isFunction(callback))
            callback = function () {};

        var url = self.site.directory;

        query = api.extend({}, query, {
            'page': {
                'page': page,
                'request': 'y'
            }
        });
        query = $.param(query);

        url = url + '?' + query;

        $.ajax({
            'type': 'POST',
            'url': url,
            'cache': false,
            'data': data,
            'success': callback,
            'error': function (response) {
                console.error(response);
            }
        });
    };

    return self;
})(jQuery, intec);