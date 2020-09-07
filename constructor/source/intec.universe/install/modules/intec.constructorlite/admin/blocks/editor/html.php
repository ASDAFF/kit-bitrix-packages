<?php

use Bitrix\Main\Localization\Loc;
use intec\core\helpers\StringHelper;
use intec\core\helpers\JavaScript;
use intec\core\helpers\Json;

?>
<div id="constructor" class="constructor">
    <? require_once(__DIR__.'/html/menu.top.php') ?>
    <? require_once(__DIR__.'/html/menu.dropdown.php') ?>

    <!-- ko if: !menu.tabs.list.layers.isActive() -->
        <div class="constructor-panel constructor-panel-element" id="container-panel" data-bind="{
            with: $root.elements.selected
        }">
            <div class="constructor-panel-button constructor-panel-button-layer-down-end constructor-icon-layer-down-end"
                 title="<?= Loc::getMessage('panel.layer.down.end') ?>"
                 data-bind="{ bind: ko.models.tooltip(), click: layerDownEnd }"
            ></div>
            <div class="constructor-panel-button constructor-panel-button-layer-down constructor-icon-layer-down"
                 title="<?= Loc::getMessage('panel.layer.down') ?>"
                 data-bind="{ bind: ko.models.tooltip(), click: layerDown }"
            ></div>
            <div class="constructor-panel-button constructor-panel-button-layer-up constructor-icon-layer-up"
                 title="<?= Loc::getMessage('panel.layer.up') ?>"
                 data-bind="{ bind: ko.models.tooltip(), click: layerUp }"
            ></div>
            <div class="constructor-panel-button constructor-panel-button-layer-up-end constructor-icon-layer-up-end"
                 title="<?= Loc::getMessage('panel.layer.up.end') ?>"
                 data-bind="{ bind: ko.models.tooltip(), click: layerUpEnd }"
            ></div>
            <div class="constructor-panel-button constructor-panel-button-copy constructor-icon-copy"
                 title="<?= Loc::getMessage('panel.copy') ?>"
                 data-bind="{ bind: ko.models.tooltip(), click: copy }"
            ></div>
            <div class="constructor-panel-button constructor-panel-button-remove constructor-icon-cancel"
                 title="<?= Loc::getMessage('panel.delete') ?>"
                 data-bind="{
                        bind: ko.models.tooltip(),
                        click:
                            function() {
                                if(confirm('<?= Loc::getMessage('panel.delete.confirm') ?>')) {
                                    remove()
                                }
                            }
                     }"
            ></div>
        </div>
    <!-- /ko -->

    <div class="constructor-area">
        <div class="constructor-area-wrapper">
            <div class="constructor-guides-wrap" data-bind="with: $root.guides">
                <div class="constructor-guides-columns-wrap" data-bind="
                    fade: columns.active,
                    style: {
                       left: columns.space.minusSum,
                       right: columns.space.minusSum
                    }
                ">
                    <div class="constructor-guides-columns" data-bind="{
                        style: { borderSpacing: columns.space.summary() + ' 0' },
                        foreach: new function () {
                           var arr = [],
                               columns = $data.columns.count();

                           for (var i = 0; i < columns; i++)
                               arr.push({});

                           return arr;
                        }
                    }">
                        <div class="constructor-guides-column"></div>
                    </div>
                </div>
            </div>
            <div class="constructor-structure">
                <!-- ko with: resolutions.selected() -->
                    <div class="constructor-structure-wrapper constructor-vertical-middle">
                        <div class="constructor-structure-wrapper-2">
                            <div class="constructor-structure-wrapper-3" data-bind="{
                                bind: $root.nodes.containers.window
                            }">
                                <div class="constructor-structure-bound constructor-structure-bound-left" data-bind="{
                                    style: {
                                        'z-index': $root.elements().length + 1
                                    }
                                }"></div>
                                <div class="constructor-structure-wrapper-4" data-bind="{
                                    bind: $root.nodes.containers.grid,
                                    style: {
                                        'width': width() + 'px',
                                        'height': height() + 'px'
                                    }
                                }">
                                    <div class="constructor-structure-bound constructor-structure-bound-left" data-bind="{
                                        style: {
                                            'z-index': $root.elements().length + 1
                                        }
                                    }"></div>
                                    <div class="constructor-structure-layers" data-bind="{
                                        template: {
                                            name: 'element',
                                            foreach: (function () {
                                                return $root.elements.find(function (index, element) {
                                                    return element.attribute('container')() === 'grid';
                                                });
                                            })()
                                        }
                                    }">
                                    </div>
                                    <div class="constructor-structure-bound constructor-structure-bound-right" data-bind="{
                                        style: {
                                            'z-index': $root.elements().length + 1
                                        }
                                    }"></div>
                                    <!-- ko with: $root.elements.selected -->
                                        <!-- ko if: attribute('container')() === 'grid' -->
                                            <div data-bind="{
                                                template: {
                                                    name: 'axis',
                                                    data: $data
                                                }
                                            }"></div>
                                        <!-- /ko -->
                                    <!-- /ko -->
                                </div>
                                <div class="constructor-structure-layers" data-bind="{
                                    template: {
                                        name: 'element',
                                        foreach: (function () {
                                            return $root.elements.find(function (index, element) {
                                                return element.attribute('container')() === 'window';
                                            });
                                        })()
                                    }
                                }">
                                </div>
                                <div class="constructor-structure-bound constructor-structure-bound-right" data-bind="{
                                    style: {
                                        'z-index': $root.elements().length + 1
                                    }
                                }"></div>
                                <!-- ko with: $root.elements.selected -->
                                    <!-- ko if: attribute('container')() === 'window' -->
                                        <div data-bind="{
                                            template: {
                                                name: 'axis',
                                                data: $data
                                            }
                                        }"></div>
                                    <!-- /ko -->
                                <!-- /ko -->
                            </div>
                        </div>
                    </div>
                <!-- /ko -->
                <!-- ko if: !resolutions.selected() -->

                <!-- /ko -->
            </div>

            <script type="text/html" id="element">
                <div class="constructor-structure-layer" data-bind="{
                    if: attribute('display'),
                    style: (function () {
                        var xAxis;
                        var yAxis;
                        var result;

                        xAxis = attribute('xAxis');
                        yAxis = attribute('yAxis');
                        result = {};

                        result['-webkit-box-pack'] = 'start';
                        result['-moz-box-pack'] = 'start';
                        result['-webkit-justify-content'] = 'flex-start';
                        result['-ms-flex-pack'] = 'start';
                        result['justify-content'] = 'flex-start';

                        if (xAxis() === 'center') {
                            result['-webkit-box-pack'] = 'center';
                            result['-moz-box-pack'] = 'center';
                            result['-webkit-justify-content'] = 'center';
                            result['-ms-flex-pack'] = 'center';
                            result['justify-content'] = 'center';
                        } else if (xAxis() === 'right') {
                            result['-webkit-box-pack'] = 'end';
                            result['-moz-box-pack'] = 'end';
                            result['-webkit-justify-content'] = 'flex-end';
                            result['-ms-flex-pack'] = 'end';
                            result['justify-content'] = 'flex-end';
                        }

                        result['-webkit-box-align'] = 'start';
                        result['-moz-box-align'] = 'start';
                        result['-webkit-align-items'] = 'flex-start';
                        result['-ms-flex-align'] = 'start';
                        result['align-items'] = 'flex-start';

                        if (yAxis() === 'center') {
                            result['-webkit-box-align'] = 'center';
                            result['-moz-box-align'] = 'center';
                            result['-webkit-align-items'] = 'center';
                            result['-ms-flex-align'] = 'center';
                            result['align-items'] = 'center';
                        } else if (yAxis() === 'bottom') {
                            result['-webkit-box-align'] = 'end';
                            result['-moz-box-align'] = 'end';
                            result['-webkit-align-items'] = 'flex-end';
                            result['-ms-flex-align'] = 'end';
                            result['align-items'] = 'flex-end';
                        }

                        return result;
                    })()
                }">
                    <div class="constructor-structure-element" data-bind="{
                        bind: node,
                        css: {
                            'constructor-structure-element-selected': isSelected,
                            'constructor-structure-element-locked': attribute('locked', false),
                        },
                        style: {
                            'top': attribute('y').summary(),
                            'left': attribute('x').summary(),
                            'width': attribute('width').summary(),
                            'min-width': attribute('width').summary(),
                            'height': attribute('height').summary(),
                            'min-height': attribute('height').summary(),
                            'z-index': (function () {
                                if (!$root.elements.selected.isOnTop() || !isSelected())
                                    return order() + 1;

                                return $root.elements().length + 1;
                            })()
                        }
                    }">
                        <div class="constructor-structure-element-wrapper" data-bind="{
                            style: {
                                'padding-top': attribute('indents')() ? attribute('indentTop').summary() : null,
                                'padding-bottom': attribute('indents')() ? attribute('indentBottom').summary() : null,
                                'padding-left': attribute('indents')() ? attribute('indentLeft').summary() : null,
                                'padding-right': attribute('indents')() ? attribute('indentRight').summary() : null
                            }
                        }">
                            <div class="constructor-structure-element-wrapper-2" data-bind="{
                                htmlTemplate: type() ? type().view : null
                            }"></div>
                        </div>
                        <div class="constructor-structure-element-border" data-bind="{
                            visible: isSelected
                        }">
                            <div class="constructor-structure-element-sizes" data-bind="{
                                bind: node.size,
                                visible: node.size.visible
                            }">
                                <div class="constructor-structure-element-size-top" data-bind="{
                                    bind: node.size.top
                                }"></div>
                                <div class="constructor-structure-element-size-top-right" data-bind="{
                                    bind: node.size.tr
                                }"></div>
                                <div class="constructor-structure-element-size-top-left" data-bind="{
                                    bind: node.size.tl
                                }"></div>
                                <div class="constructor-structure-element-size-right" data-bind="{
                                    bind: node.size.right
                                }"></div>
                                <div class="constructor-structure-element-size-left" data-bind="{
                                    bind: node.size.left
                                }"></div>
                                <div class="constructor-structure-element-size-bottom" data-bind="{
                                    bind: node.size.bottom
                                }"></div>
                                <div class="constructor-structure-element-size-bottom-right" data-bind="{
                                    bind: node.size.br
                                }"></div>
                                <div class="constructor-structure-element-size-bottom-left" data-bind="{
                                    bind: node.size.bl
                                }"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </script>

            <script type="text/html" id="axis">
                <div class="constructor-structure-axis" data-bind="{
                    style: (function (element) {
                        var result;
                        var resolution;
                        var xAxis;
                        var yAxis;

                        resolution = $root.resolutions.selected();
                        xAxis = element.attribute('xAxis')();
                        yAxis = element.attribute('yAxis')();
                        result = {};
                        result.top = null;
                        result.left = null;
                        result.bottom = null;
                        result.right = null;
                        result.zIndex = $root.elements().length + 1;

                        if (xAxis === 'left') {
                            result.left = '-1px';
                        } else if (xAxis === 'center') {
                            result.left = '50%';
                        } else if (xAxis === 'right') {
                            result.right = '0';
                        }

                        if (yAxis === 'top') {
                            result.top = '-1px';
                        } else if (yAxis === 'center') {
                            result.top = '50%';
                        } else if (yAxis === 'bottom') {
                            result.bottom = '0';
                        }

                        return result;
                    })($data)
                }">
                    <div class="constructor-structure-vertical"></div>
                    <div class="constructor-structure-horizontal"></div>
                    <div class="constructor-structure-point"></div>
                </div>
            </script>
        </div>
    </div>
    <? require_once(__DIR__.'/html/menu.settings.php') ?>
    <? require_once(__DIR__.'/html/menu.layers.php') ?>
    <? require_once(__DIR__.'/html/dialogs.php') ?>
</div>