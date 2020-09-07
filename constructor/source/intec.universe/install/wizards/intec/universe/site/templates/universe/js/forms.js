universe.forms = (function (universe, $, api) {
    var self = {};
    var popup;

    /**
     * @param options
     * @option id Web form identifier
     * @option template Template of component
     * @option parameters Parameters of component
     * @option fields Fields of form
     * @param callback Callback function when given form code
     */
    self.get = function (options, callback) {
        var data = {};

        options = api.extend({
            'id': null,
            'template': null,
            'parameters': null,
            'fields': null
        }, options);

        if (api.isObject(options.fields))
            data.fields = options.fields;

        universe.page('forms.get', {
            'id': options.id,
            'template': options.template,
            'parameters': options.parameters
        }, data, callback);
    };

    /**
     *
     * @param options
     * @option id Web form identifier
     * @option template Template of component
     * @option parameters Parameters of component
     * @option settings
     * Object like:
     * {
     *     title: 'Title of popup',
     *     parameters: {Parameters of BX.PopupWindow}
     * }
     * @option fields Fields of form
     * @param callback Callback function when end
     * Callback variables:
     * - this - BX.PopupWindow instance
     * - options - Options object
     * - settings - Settings of popup
     */
    self.show = function (options, callback) {
        self.get(options, function (response) {
            var container;
            var settings;
            var parameters;

            if (popup) {
                popup.destroy();
            }

            settings = api.extend({}, options.settings);
            options.settings = undefined;

            container = $('<div>', {
                'style': 'display: none'
            }).append(response).appendTo($('body'));
            container = container.get(0);

            parameters = api.extend({
                content: container,
                closeIcon: {},
                zIndex: 0,
                offsetLeft: 0,
                offsetTop: 0,
                width: 450,
                overlay: true
            }, settings.parameters);

            if (settings.title) {
                parameters.titleBar = {
                    content: BX.create("span", {
                        html: settings.title,
                        props: {
                            className: 'access-title-bar'
                        }
                    })
                }
            }

            popup = new BX.PopupWindow('UniverseWebForm', null, parameters);
            popup.show();

            root = $('#UniverseWebForm');
            $('input:not([type="hidden"]), textarea, select', root).first().focus();

            setTimeout(function () {
                popup.adjustPosition();
            }, 50);

            setTimeout(function () {
                popup.adjustPosition();
            }, 250);

            if (api.isFunction(callback))
                callback.call(popup, options, settings);
        });
    };

    return self;
})(universe, jQuery, intec);