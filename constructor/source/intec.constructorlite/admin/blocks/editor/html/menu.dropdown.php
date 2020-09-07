<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

/**
 * @var array $arUrlTemplates
 */

?>
<div class="constructor-menu constructor-menu-dropdown constructor-menu-dropdown-elements" data-bind="{
    slide: function(){ return dropdown.active() === dropdown.list.widgets; },
    direction: 'up'
}">
    <div class="constructor-menu-wrapper" data-bind="style: {
        height: 61 * elements.types().length - 1 + 'px'
     }">
        <div class="constructor-menu-wrapper-2 nano" data-bind="bind: dropdown.scroll">
            <div class="constructor-menu-wrapper-3 nano-content">
                <div class="constructor-menu-elements" data-bind="{
                    foreach: elements.types
                }">
                    <div class="constructor-menu-element" data-bind="click: function () { $root.elements.add($data); $root.dropdown.close(); }">
                        <i class="constructor-menu-element-icon" data-bind="{
                            style: {
                                background: icon() ? 'url(\'' + icon() + '\')' : null
                            }
                        }"></i>
                        <span class="constructor-menu-element-name" data-bind="{
                            text: name
                        }"></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="constructor-menu constructor-menu-dropdown constructor-menu-dropdown-resolutions" data-bind="{
    slide: function () {
        return dropdown.active() === dropdown.list.resolutions;
    },
    direction: 'up'
}">
    <div class="constructor-menu-wrapper">
        <div class="constructor-menu-resolutions">
            <!-- ko foreach: resolutions -->
                <!-- ko if: !isEditable() -->
                    <div class="constructor-menu-resolution" data-bind="{
                        click: select,
                        css: { 'constructor-menu-resolution-selected': isSelected() }
                    }">
                        <div class="constructor-menu-resolution-wrapper">
                            <i class="constructor-menu-resolution-icon-active">&#x25CF;</i>
                            <i class="constructor-menu-resolution-icon-gadget" data-bind="{
                                css: 'constructor-menu-resolution-icon-gadget-' + gadget()
                            }"></i>
                            <span class="constructor-menu-resolution-name" data-bind="{
                                text: value
                            }"></span>
                            <span class="constructor-menu-resolution-icon-edit constructor-icon-pencil" data-bind="{
                                click: (function () {
                                    $root.resolutions.editable($data);
                                }),
                                clickBubble: false
                            }"></span>
                        </div>
                    </div>
                <!-- /ko -->
                <!-- ko if: isEditable() -->
                    <div class="constructor-menu-resolution" data-bind="{
                        template: {
                            name: 'resolution-edit',
                            data: $data
                        }
                    }"></div>
                <!-- /ko -->
            <!-- /ko -->
            <!-- ko with: $root.resolutions.editable -->
                <!-- ko if: !isAvailable() -->
                    <div class="constructor-menu-resolution" data-bind="{
                        template: {
                            name: 'resolution-edit',
                            data: $data
                        }
                    }"></div>
                <!-- /ko -->
            <!-- /ko -->
            <div class="constructor-menu-resolution constructor-menu-resolution-add" data-bind="{
                click: $root.resolutions.editable.create
            }">+</div>
        </div>
    </div>
    <script type="text/html" id="resolution-edit">
        <div class="constructor-menu-resolution-edit">
            <div class="constructor-menu-resolution-button constructor-menu-resolution-button-save constructor-icon-check" data-bind="{
                click: $root.resolutions.editable.save
            }"></div>
            <div class="constructor-menu-resolution-fields">
                <div class="constructor-menu-resolution-field">
                    <input type="text" data-bind="{
                        value: $root.resolutions.editable.width
                    }" />
                </div>
                <div class="constructor-menu-resolution-delimiter">x</div>
                <div class="constructor-menu-resolution-field">
                    <input type="text" data-bind="{
                        value: $root.resolutions.editable.height
                    }" />
                </div>
                <div class="constructor-clearfix"></div>
            </div>
            <div class="constructor-menu-resolution-button constructor-menu-resolution-button-remove constructor-icon-times" data-bind="{
                click: $root.resolutions.editable.remove
            }"></div>
        </div>
    </script>
</div>