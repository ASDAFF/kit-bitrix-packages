(function (models) {
    (function (container) {
        var prototype = container.prototype;

        prototype.copy = (function () {
            var method = prototype.copy;
            var update;

            update = function (container) {
                if (container.hasBlock()) {
                    var block = container.element();

                    container.element(undefined);
                    block.copy(function (block) {
                        container.element(block);
                    });
                } else if (container.hasContainers()) {
                    api.each(container.getChildren(), function (index, container) {
                        update(container);
                    });
                }

                return container;
            };

            return function () {
                return update(method.call(this));
            };
        })();

        prototype.convertToSimple = function () {
            var self = this;

            if (self.hasArea()) {
                var area = self.getArea();
                var container = area.container();
                var containers = [];

                area.parent(null);

                if (constructor.isContainer(container)) {
                    if (container.hasElement()) {
                        containers.push(container.copy());
                    } else {
                        api.each(container.getContainers(), function (index, container) {
                            containers.push(container.copy());
                        });
                    }
                }

                api.each(containers, function (index, container) {
                    self.addContainer(container);
                });
            } else if (self.hasVariator()) {
                var variator = self.getVariator();
                var variants = variator.getVariants();

                variator.parent(null);

                api.each(variants, function (index, variant) {
                    self.addContainer(variant.container());
                });
            } else {
                return false;
            }

            return true;
        };

        prototype.convertToVariator = function () {
            var self = this;

            self.convertToSimple();

            var containers = [];
            var variator = new constructor.models.variator();
            var counter = 1;

            api.each(self.getContainers(), function (index, container) {
                containers.push(container);
            });

            api.each(containers, function (index, container) {
                var parent;
                var variant = new constructor.models.variant({
                    'name': 'Variant ' + counter
                });

                if (container.hasElement()) {
                    parent = new constructor.models.container({
                        'display': true,
                        'type': 'normal'
                    });

                    parent.addContainer(container);
                    container = parent;
                }

                variant.container(container);
                variator.addVariant(variant);

                counter++;
            });

            if (variator.getVariants().length > 0)
                variator.variant(0);

            self.setVariator(variator);

            return true;
        };

        prototype.convertToArea = function (area) {
            var self = this;

            if (!constructor.isArea(area))
                return false;

            self.containers.removeAll();
            self.element(area);

            return true;
        };

        container.on('remove', function (event, self) {
            if (constructor.selected() === self) {
                constructor.selected(null);
            }
        });

        container.on('init', function (event, self, data) {
            self.node = ko.observable();

            self.getParentNode = function () {
                return self.hasParent() ? self.getParent().node() : $(self.node()).parent().get(0);
            };

            self.simple = ko.computed(function () {
                var parent;

                if (!self.hasParent())
                    return false;

                parent = self.getParent();

                return constructor.isArea(parent) || constructor.isVariant(parent);
            });

            self.selected = ko.computed(function () {
                return constructor.selected() === self;
            });

            self.node.subscribe(function (node) {
                interact(node).on('down', function (event) {
                    var container;
                    var selection = constructor.selected;
                    var parent;

                    event.stopPropagation();

                    if (self.simple()) {
                        parent = self.parent();

                        if (constructor.isArea(parent)) {
                            container = parent.parent();
                        } else if (constructor.isVariant(parent) && parent.hasParent()) {
                            container = parent.parent().parent();
                        }
                    } else {
                        container = self;
                    }

                    if (container != null && selection() !== container && !selection.isLocked())
                        selection(container);
                });
            });

            self.node.size = (function (actions, grid) {
                var size = {};
                var edges = {
                    'top': {'top': true},
                    'right': {'right': true},
                    'bottom': {'bottom': true},
                    'left': {'left': true},
                    'tr': {'top': true, 'right': true},
                    'tl': {'top': true, 'left': true},
                    'br': {'bottom': true, 'right': true},
                    'bl': {'bottom': true, 'left': true}
                };

                api.each(edges, function (name, edges) {
                    size[name] = ko.observable();
                    size[name].drag = ko.observable(false);
                    size[name].subscribe(function (node) {
                        var action = null;

                        interact(node).draggable({
                            onstart: function (event) {
                                action = new actions.resize(self, edges);
                                size[name].drag(true);
                                constructor.selected.lock();
                            },
                            onmove: function (event) {
                                action.execute(event);
                            },
                            onend: function (event) {
                                grid.correct(self);
                                size[name].drag(false);
                                action = null;
                                constructor.selected.unlock();
                            }
                        });
                    });
                });

                size.visible = ko.computed(function () {
                    return self.selected();
                });

                return size;
            })(constructor.actions, constructor.grid);

            self.node.padding = (function (actions) {
                var padding = {};
                var sides = ['top', 'right', 'bottom', 'left'];
                var properties = self.properties;

                api.each(sides, function (index, side) {
                    var object = {};
                    var update = function () {
                        properties.padding.value();
                        properties.padding.measure();
                        properties.padding.summary();
                        properties.padding[side].value();
                        properties.padding[side].measure();
                        properties.padding[side].summary();
                        properties.width.summary();
                        properties.height.summary();
                    };

                    object.getValue = function () {
                        update();

                        var node = $(self.node());
                        var value = api.toInteger(node.css('padding-' + side));

                        return value + 'px';
                    };

                    padding[side] = object;
                });

                padding.visible = ko.computed(function () {
                    return self.selected();
                });

                return padding;
            })(constructor.actions);

            self.node.margin = (function (actions) {
                var margin = {};
                var sides = ['top', 'right', 'bottom', 'left'];
                var properties = self.properties;

                api.each(sides, function (index, side) {
                    var object = ko.observable();
                    var update = function () {
                        properties.margin.value();
                        properties.margin.measure();
                        properties.margin.summary();
                        properties.margin.isAuto();
                        properties.margin[side].value();
                        properties.margin[side].measure();
                        properties.margin[side].summary();
                        properties.margin[side].isAuto();
                        properties.border.width.summary();
                        properties.border.style();
                        properties.border[side].width.summary();
                        properties.border[side].style.value();
                        properties.width.summary();
                        properties.height.summary();
                    };

                    object.getValue = function () {
                        update();

                        var node = $(self.node());
                        var value = api.toInteger(node.css('margin-' + side));

                        return value + object.getIndent();
                    };
                    object.getIndent = function () {
                        update();

                        var node = $(self.node());

                        return api.toInteger(
                            node.css('border-' + side + '-width')
                        );
                    };

                    margin[side] = object;
                });

                margin.visible = ko.computed(function () {
                    return self.selected();
                });

                return margin;
            })(constructor.actions);

            self.node.shift = (function (actions) {
                var shift = {};
                var action = actions.shift();

                shift.node = ko.observable();
                shift.isAvailable = function () {
                    return self.hasParent() && constructor.isContainer(self.getParent());
                };
                shift.current = function () {
                    var container = null;

                    if (action.current())
                        return true;

                    if (self.hasParent()) {
                        container = self.getParent();

                        while (container !== null && !constructor.isContainer(container))
                            container = container.getParent();

                        if (container !== null)
                            return container.node.shift.current();
                    }

                    return false;
                };
                shift.node.subscribe(function (node) {
                    if (!shift.isAvailable())
                        return;

                    var parent;
                    var holder;
                    var shifter = constructor.structure.shifter;

                    holder = interact(node).draggable(action({
                        'start': function (event) {
                            parent = self.parent();
                            constructor.selected(null);
                            self.parent(null);
                            self.order(self.order() - 0.5);
                            shifter.container(self);
                        },
                        'move': function (event) {
                            var height;
                            var offset;
                            var size;
                            var position;
                            var nodes = {
                                'root': constructor.nodes.structure,
                                'frame': $(shifter.frame()),
                                'holder': $(shifter.holder())
                            };

                            position = {};
                            position.top = event.pageY - nodes.root.offset().top - 17 + nodes.root.scrollTop();
                            position.left = event.pageX - nodes.holder.offset().left;
                            height = nodes.root.prop('scrollHeight');

                            nodes.frame.css({
                                'top': position.top,
                                'left': position.left
                            });

                            offset = nodes.holder.offset();
                            size = {
                                width: nodes.holder.width(),
                                height: nodes.holder.height()
                            };

                            position.top += offset.top + (size.height / 2) - (event.pageY - nodes.root.offset().top);
                            position.left -= offset.left + (size.width / 2) - event.pageX;

                            if (position.top + nodes.frame.height() + 20 >= height)
                                position.top -= position.top + nodes.frame.height() - height + 22;

                            nodes.frame.css({
                                'top': position.top,
                                'left': position.left
                            });
                        },
                        'end': function () {
                            shifter.container(null);

                            if (action.target()) {
                                var targetArea = null;
                                var selfArea = null;

                                api.each(action.target().getParents(), function (index, parent) {
                                    if (constructor.isArea(parent)) {
                                        targetArea = parent;
                                        return false;
                                    }
                                });

                                if (targetArea) {
                                    if (self.hasElement() && self.hasArea()) {
                                        selfArea = self.getArea();
                                    } else {
                                        self.containers.each(function () {
                                            if (this.hasArea()) {
                                               selfArea = this.getArea();
                                               return false;
                                            }
                                        });
                                    }
                                }

                                if (!targetArea || !selfArea) {
                                    self.order(action.indicator().order() - 0.5);
                                    self.parent(action.target());
                                }
                            }

                            if (!self.parent())
                                self.parent(parent);

                            self.parent().containers.order();
                            parent = null;
                        }
                    }));
                });

                return shift;
            })(constructor.actions);

            self.node.zone = (function (actions) {
                var zone = {};
                var highlight;
                var action = actions.shift;
                var target;

                highlight = function (state) {
                    target().node.zone.highlight(state);
                };

                target = function () {
                    if (self.hasElement())
                        return self.getParent();

                    return self;
                };

                zone.node = ko.observable();
                zone.node.subscribe(function (node) {
                    if (self.node.shift.current())
                        return;

                    interact(node).dropzone({
                        'ondragenter': function (event) {
                            if (!action.active())
                                return;

                            highlight(true);
                            action.target(target());
                        },
                        'ondragleave': function (event) {
                            if (!action.active())
                                return;

                            highlight(false);
                            action.target(null);
                        },
                        'ondrop': function () {
                            if (!action.active())
                                return;

                            highlight(false);
                        }
                    });
                });
                zone.highlight = ko.observable();

                return zone;
            })(constructor.actions);

            self.elements = (function () {
                var object;

                object = ko.observableArray();
                object.order = function () {
                    object.sort(function(left, right){
                        return left.order() == right.order() ? 0 : (left.order() < right.order() ? -1 : 1)
                    });
                };

                return object;
            })();

            (function () {
                self.containers.render = ko.computed(function () {
                    var result;

                    result = ko.observableArray();
                    self.elements.order();

                    api.each(self.containers(), function (position, container) {
                        api.each(self.elements(), function (index, element) {
                            if (position >= element.order() && !result.has(element))
                                result.push(element);
                        });

                        result.push(container);
                    });

                    api.each(self.elements(), function (index, element) {
                        if (!result.has(element))
                            result.push(element);
                    });

                    return result();
                });
            })();
        });
    })(models.container);
})(constructor.models);