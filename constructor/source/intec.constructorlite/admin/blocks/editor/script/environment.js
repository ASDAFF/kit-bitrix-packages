constructor.environment = (function () {
    var module;

    module = {};
    module.path = {};
    module.path.handle = function (path) {
        if (!api.isString(path))
            return null;

        var aliases = {};

        api.each(module.path, function (alias, value) {
            if (api.isString(value))
                aliases['#' + alias.toUpperCase() + '#'] = value;
        });

        return api.string.replace(path, aliases);
    };

    return module;
})();