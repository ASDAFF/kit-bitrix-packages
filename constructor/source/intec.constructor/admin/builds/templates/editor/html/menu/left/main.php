<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die(); ?>

<div data-bind="{
    fade: function () { return menu.tabs.list.main.isActive() }
}">
    <div class="constructor-widgets">
        <!-- ko foreach: (function (api) {
            var result = [];
            var objects = [];

            api.each($root.objects(), function (index, object) {
                if (!object.available())
                    return;

                objects.push(object);
            });

            api.each(objects, function (index, object) {


                if (index % 2 == 0) {
                    var row = [object];

                    if (index + 1 < objects.length)
                        row.push(objects[index + 1]);

                    result.push(row);
                }
            });

            return result;
        })(intec) -->
            <div class="constructor-widgets-row" data-bind="{ foreach: $data }">
                <div class="constructor-widget" data-bind="{
                    bind: node,
                    css: 'constructor-widget-' + type()
                }">
                    <div class="constructor-widget-wrapper">
                        <div class="constructor-widget-wrapper-2">
                            <div class="constructor-widget-background"></div>
                            <div class="constructor-widget-icon" data-bind="{
                                style: {
                                    'background-image': icon.calculated
                                }
                            }"></div>
                            <div class="constructor-widget-name" data-bind="{
                                text: name
                            }"></div>
                        </div>
                    </div>
                </div>
            </div>
        <!-- /ko -->
    </div>
    <!-- ko function: $root.menu.scroll.update --><!-- /ko -->
</div>