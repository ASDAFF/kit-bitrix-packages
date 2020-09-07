(function (element) {
    element.on('create', function (event, self) {
        self.node = ko.observable();
        self.node.draggable = ko.observable(true);
        self.node.reset = function () {
            self.node.draggable(true);
        };
    });

    element.on('created', function (event, self) {
        var locked = self.attribute('locked', false);

        self.isSelected = ko.computed(function () {
            return constructor.elements.selected() === self;
        });

        self.isSelected.subscribe(function (value) {
             if (!value) self.node.reset();
        });

        self.select = function () {
            if (constructor.elements.selected.isLocked())
                return;

            if (constructor.elements.selected() === self)
                return;

            constructor.elements.selected(self);
        };

        self.toggle = function () {
            if (self.isSelected()) {
                constructor.elements.selected(null);
            } else {
                self.select();
            }
        };

        self.node.subscribe(function (node) {
            var interactable;

            interactable = interact(node);
            interactable.draggable({
                'interia': false,
                'manualStart': true,
                'onmove': function (event) {
                    var x;
                    var xMeasure;
                    var y;
                    var yMeasure;
                    var resolution = constructor.resolutions.selected();

                    if (resolution == null)
                        return;

                    if (locked())
                        return;

                    x = self.attribute('x');
                    xMeasure = self.attribute('xMeasure');

                    if (xMeasure() === 'px') {
                        x(x() + event.dx);
                    } else {
                        x((((x() * resolution.width() / 100) + event.dx) / resolution.width() * 100).toFixed(2));
                    }

                    y = self.attribute('y');
                    yMeasure = self.attribute('yMeasure');

                    if (yMeasure() === 'px') {
                        y(y() + event.dy);
                    } else {
                        y((((y() * resolution.height() / 100) + event.dy) / resolution.height() * 100).toFixed(2));
                    }
                }
            });

            interactable.on('down', function (event) {
                var interaction = event.interaction;
                var target = event.target;

                if (self.node.draggable() && target && target.parentNode)
                    if (target.parentNode !== self.node.size())
                        interaction.start(
                            {'name': 'drag'},
                            event.interactable,
                            event.currentTarget
                        );

                self.select();
                event.stopPropagation();
            });
        });

        self.node.size = (function () {
            var action;
            var size;
            var edges;

            size = ko.observable(null);
            edges = {
                'top': {'top': true},
                'right': {'right': true},
                'bottom': {'bottom': true},
                'left': {'left': true},
                'tr': {'top': true, 'right': true},
                'tl': {'top': true, 'left': true},
                'br': {'bottom': true, 'right': true},
                'bl': {'bottom': true, 'left': true}
            };

            action = function (event, edges) {
                var areas;
                var deltas;
                var values;
                var modifiers;
                var resolution;

                areas = {};
                deltas = {};
                values = {};
                modifiers = {};
                resolution = constructor.resolutions.selected();

                areas.grid = 0;
                areas.window = 120;

                values.container = self.attribute('container')();
                values.x = self.attribute('x')();
                values.xMeasure = self.attribute('x').measure();
                values.xAxis = self.attribute('xAxis')();
                values.y = self.attribute('y')();
                values.yMeasure = self.attribute('y').measure();
                values.yAxis = self.attribute('yAxis')();
                values.width = self.attribute('width')();
                values.widthMeasure = self.attribute('width').measure();
                values.height = self.attribute('height')();
                values.heightMeasure = self.attribute('height').measure();

                deltas.x = values.xMeasure === '%' ? (event.dx / (resolution.width() + areas[values.container]) * 100) : event.dx;
                deltas.y = values.yMeasure === '%' ? (event.dy / resolution.height() * 100) : event.dy;
                deltas.width = values.widthMeasure === '%' ? (event.dx / (resolution.width() + areas[values.container]) * 100) : event.dx;
                deltas.height = values.heightMeasure === '%' ? (event.dy / resolution.height() * 100) : event.dy;

                modifiers.horizontal = edges.left ? -1 : 1;
                modifiers.vertical = edges.top ? -1 : 1;

                if (event.dx != 0 && (edges.left || edges.right)) {
                    if (values.xAxis === 'left') {
                        if (edges.left) {
                            values.x += deltas.x;
                            values.width -= deltas.width;
                        } else {
                            values.width += deltas.width;
                        }
                    } else if (values.xAxis === 'right') {
                        if (edges.right) {
                            values.x += deltas.x;
                            values.width += deltas.width;
                        } else {
                            values.width -= deltas.width;
                        }
                    } else {
                        values.width += (deltas.width * modifiers.horizontal * 2);
                    }
                }

                if (event.dy != 0 && (edges.top || edges.bottom)) {
                    if (values.yAxis === 'top') {
                        if (edges.top) {
                            values.y += deltas.y;
                            values.height -= deltas.height;
                        } else {
                            values.height += deltas.height;
                        }
                    } else if (values.yAxis === 'bottom') {
                        if (edges.bottom) {
                            values.y += deltas.y;
                            values.height += deltas.height;
                        } else {
                            values.height -= deltas.height;
                        }
                    } else {
                        values.height += (deltas.height * modifiers.vertical * 2);
                    }
                }

                if (values.width < 0) {
                    if (values.xAxis === 'left') {
                        if (edges.left)
                            values.x += Math.round(values.width / deltas.width) * deltas.x;
                    } else if (values.xAxis === 'right') {
                        if (edges.right)
                            values.x -= Math.round(values.width / deltas.width) * deltas.x;
                    }

                    values.width = 0;
                }

                if (values.height < 0) {
                    if (values.yAxis === 'top') {
                        if (edges.top)
                            values.y += Math.round(values.height / deltas.height) * deltas.y;
                    } else if (values.yAxis === 'bottom') {
                        if (edges.bottom)
                            values.y -= Math.round(values.height / deltas.height) * deltas.y;
                    }

                    values.height = 0;
                }

                self.attribute('x')(values.x);
                self.attribute('y')(values.y);
                self.attribute('width')(values.width);
                self.attribute('height')(values.height);
            };

            api.each(edges, function (name, edges) {
                size[name] = ko.observable();
                size[name].drag = ko.observable(false);
                size[name].subscribe(function (node) {
                    interact(node).draggable({
                        onstart: function (event) {
                            if (locked())
                                return;

                            action(event, edges);
                            size[name].drag(true);
                            constructor.elements.selected.lock();
                        },
                        onmove: function (event) {
                            if (locked())
                                return;

                            action(event, edges);
                        },
                        onend: function (event) {
                            if (locked())
                                return;

                            size[name].drag(false);
                            constructor.elements.selected.unlock();
                        }
                    });
                });
            });

            size.visible = ko.computed(function () {
                return self.isSelected() && !locked();
            });

            return size;
        })();
    });
})(models.element);