constructor.grid = (function () {
    var grid = {};
    var check = function (container) {
        if (
            !container.hasParent() ||
            !constructor.isContainer(container.getParent())
        ) return false;

        var parent = container.getParent();
        return parent.type() === 'absolute';
    };

    grid.show = ko.observable(true);
    grid.correct = function (container) {
        grid.correct.position(container);
        grid.correct.size(container);
    };
    grid.correct.position = function (container) {
        if (!check(container)) return;

        var parent = container.getParent();
        var properties = container.properties;
        var settings = parent.properties.grid;
        var node = {
            'container': $(container.node()),
            'parent': $(parent.node())
        };
        var position = node.container.position();

        api.each({
            'x': 'left',
            'y': 'top'
        }, function(axis, property) {
            var step;
            var coefficient;
            var diagonal = axis == 'x' ? 'width' : 'height';

            if (settings.type() === 'adaptive') {
                if (properties[property].measure() === 'px') {
                    properties[property].value(position[property] / node.parent[diagonal] * 100);
                    properties[property].measure('%');
                }

                step = 100 / settings[axis]();
                coefficient = properties[property].value() / step;
            } else if (settings.type() === 'fixed') {
                if (properties[property].measure() === '%') {
                    properties[property].value(position[property]);
                    properties[property].measure('px');
                }

                step = settings[diagonal]();
                coefficient = properties[property].value() / step;
            }

            if (step) {
                coefficient = Math.round(coefficient);
                properties[property].value(step * coefficient);
            }
        })
    };
    grid.correct.size = function (container) {
        if (!check(container)) return;

        var parent = container.getParent();
        var properties = container.properties;
        var settings = parent.properties.grid;
        var node = {
            'container': $(container.node()),
            'parent': $(parent.node())
        };

        api.each({
            'x': 'width',
            'y': 'height'
        }, function(axis, property) {
            if (settings.correct[property]()) {
                var step;
                var coefficient;

                if (settings.type() === 'adaptive') {
                    if (properties[property].measure() === 'px') {
                        properties[property].value(node.container[property]() / node.parent[property]() * 100);
                        properties[property].measure('%');
                    }

                    step = 100 / settings[axis]();
                    coefficient = properties[property].value() / step;
                } else if (settings.type() === 'fixed') {
                    if (properties[property].measure() === '%') {
                        properties[property].value(node.container[property]());
                        properties[property].measure('px');
                    }

                    step = settings[property]();
                    coefficient = properties[property].value() / step;
                }

                if (step && coefficient) {
                    coefficient = coefficient < 1 ? 1 : Math.round(coefficient);
                    properties[property].value(step * coefficient);
                }
            }
        });
    };

    return grid;
})();