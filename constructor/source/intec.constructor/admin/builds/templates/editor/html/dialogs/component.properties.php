<?php

use Bitrix\Main\Localization\Loc;
use intec\core\helpers\JavaScript;

?>
<div class="constructor-dialog constructor-dialog-component-properties constructor-component-properties" data-bind="{
    bind: dialogs.list.componentProperties,
    with: dialogs.list
}">
    <div class="constructor-dialog-header">
        <div class="constructor-dialog-header-wrapper">
            <div class="constructor-dialog-title">
                <?= Loc::getMessage('container.modals.component.properties.title') ?>
            </div>
            <div class="constructor-dialog-content"></div>
            <div class="constructor-dialog-buttons">
                <button class="constructor-dialog-button constructor-icon-window" data-bind="{
                    click: componentProperties.expanded.switch
                }"></button>
                <button class="constructor-dialog-button constructor-icon-cancel" data-bind="{
                    click: componentProperties.close
                }"></button>
            </div>
        </div>
    </div>
    <div class="constructor-dialog-body" data-bind="{
        with: componentProperties.data
    }">
        <div class="constructor-dialog-loader" data-bind="{
            visible: updating
        }">
            <div class="constructor-loader constructor-loader-1"></div>
        </div>
        <div data-bind="{
            html: (function (api) {
                var result = '';

                intec.each(scripts(), function (index, script) {
                    result += '<script type=\'text/javascript\' src=\'' + script + '\'></script>';
                });

                return result;
            })(intec)
        }"></div>
        <div class="component-properties" data-bind="{
            if: component,
            visible: !updating()
        }">
            <div class="component-properties-content">
                <div class="component-properties-left">
                    <div class="component-properties-search">
                        <div class="component-properties-search-wrapper">
                            <input type="text" data-bind="{
                                value: filter,
                                valueUpdate: 'keyup'
                            }" />
                            <div class="component-properties-search-icon"></div>
                        </div>
                    </div>
                    <div class="component-properties-groups">
                        <div class="component-properties-groups-wrapper" data-bind="{
                            foreach: groups.visible
                        }">
                            <div class="component-properties-group" data-bind="{
                                text: name,
                                css: {'component-properties-group-active': active},
                                click: go
                            }"></div>
                        </div>
                    </div>
                </div>
                <div class="component-properties-right">
                    <div class="component-properties-right-wrapper">
                        <div class="component-properties-description">
                            <div class="component-properties-description-name">
                                <div class="component-properties-description-name-wrapper" data-bind="{
                                    text: name
                                }"></div>
                                <!-- ko if: description -->
                                    <div class="component-properties-description-name-hint" data-bind="{
                                        attr: {
                                            title: description
                                        }
                                    }">i</div>
                                <!-- /ko -->
                            </div>
                            <div class="component-properties-description-code" data-bind="{
                                text: component().code
                            }"></div>
                        </div>
                        <div class="component-properties-parameters">
                            <div class="component-properties-parameters-wrapper" data-bind="{
                                bind: groups.node
                            }">
                                <div class="component-properties-parameters-wrapper-2">
                                    <table class="component-properties-parameters-table bxcompprop-content-table" data-bind="{
                                        foreach: groups
                                    }">
                                        <tr class="component-properties-parameters-row component-properties-parameters-title">
                                            <td class="component-properties-parameters-cell" colspan="2" data-bind="{
                                                text: name,
                                                bind: node,
                                                style: {
                                                    display: visible() ? null : 'none'
                                                }
                                            }"></td>
                                        </tr>
                                        <!-- ko if: code() === 'COMPONENT_TEMPLATE' -->
                                            <tr class="component-properties-parameters-row component-properties-parameters-parameter bxcompprop-prop-tr">
                                                <td class="component-properties-parameters-cell component-properties-parameters-cell-left bxcompprop-cont-table-l">
                                                    <label class="component-properties-parameters-label bxcompprop-label"><?= GetMessage('settings.component.properties.template') ?>:</label>
                                                </td>
                                                <td class="component-properties-parameters-cell component-properties-parameters-cell-right bxcompprop-cont-table-r" data-bind="{
                                                        bind: $parent.template.container
                                                    }">
                                                    <select data-bind="{
                                                        bind: $parent.template.input,
                                                        options: $parent.templates,
                                                        optionsValue: 'code',
                                                        optionsText: 'name',
                                                        value: $parent.template
                                                    }"></select>
                                                </td>
                                            </tr>
                                        <!-- /ko -->
                                        <!-- ko if: code() !== 'COMPONENT_TEMPLATE' -->
                                            <!-- ko foreach: parameters() -->
                                                <tr class="component-properties-parameters-row component-properties-parameters-parameter bxcompprop-prop-tr" data-bind="{
                                                    style: {
                                                        display: visible() ? null : 'none'
                                                    }
                                                }">
                                                    <td class="component-properties-parameters-cell component-properties-parameters-cell-left bxcompprop-cont-table-l">
                                                        <label class="component-properties-parameters-label bxcompprop-label" data-bind="{
                                                            text: name() + ':',
                                                            attr: {
                                                                title: code
                                                            }
                                                        }"></label>
                                                    </td>
                                                    <td class="component-properties-parameters-cell component-properties-parameters-cell-right bxcompprop-cont-table-r" data-bind="{
                                                        bind: nodes.container
                                                    }">
                                                        <!-- ko if: type() === 'CHECKBOX' -->
                                                            <input type="checkbox" data-bind="{
                                                                checked: value,
                                                                bind: function (node) {
                                                                    ko.models.switch({}, node);
                                                                    nodes.input(node);
                                                                }
                                                            }" />
                                                        <!-- /ko -->
                                                        <!-- ko if: type() === 'STRING' -->
                                                            <!-- ko if: !multiple() -->
                                                                <input type="text" data-bind="{
                                                                    value: value,
                                                                    bind: nodes.input
                                                                }" />
                                                            <!-- /ko -->
                                                            <!-- ko if: multiple() -->
                                                                <!-- ko foreach: values -->
                                                                    <input type="text" data-bind="{
                                                                        value: value,
                                                                        bind: $parent.nodes.input
                                                                    }" />
                                                                <!-- /ko -->
                                                                <input type="button" class="component-prop-button-ok" data-bind="{
                                                                    click: function () { values.add(''); }
                                                                }" value="+" />
                                                            <!-- /ko -->
                                                        <!-- /ko -->
                                                        <!-- ko if: type() === 'LIST' -->
                                                            <!-- ko if: !multiple() -->
                                                                <select data-bind="{
                                                                    options: values,
                                                                    optionsText: 'name',
                                                                    optionsCaption: extended() ? <?= JavaScript::toObject(GetMessage('settings.component.properties.custom')) ?> : null,
                                                                    value: value,
                                                                    bind: nodes.input
                                                                }"></select>
                                                                <!-- ko if: extended() -->
                                                                    <input type="text" data-bind="{
                                                                        value: values.custom.value,
                                                                        attr: {
                                                                            'disabled': !values.custom.selected() ? 'disabled' : null
                                                                        }
                                                                    }" />
                                                                <!-- /ko -->
                                                            <!-- /ko -->
                                                            <!-- ko if: multiple() -->
                                                                <select multiple="multiple" data-bind="{
                                                                    options: values,
                                                                    optionsText: 'name',
                                                                    optionsCaption: <?= JavaScript::toObject(GetMessage('settings.component.properties.unselected')) ?>,
                                                                    selectedOptions: value,
                                                                    bind: nodes.input
                                                                }"></select>
                                                                <!-- ko if: extended() -->
                                                                    <!-- ko foreach: custom -->
                                                                        <input type="text" data-bind="{
                                                                            value: value
                                                                        }" />
                                                                    <!-- /ko -->
                                                                    <input type="button" class="component-prop-button-ok" data-bind="{
                                                                        click: function () { custom.add(''); }
                                                                    }" value="+" />
                                                                <!-- /ko -->
                                                            <!-- /ko -->
                                                        <!-- /ko -->
                                                        <!-- ko if: type() === 'COLORPICKER' -->
                                                            <input type="text" data-bind="{
                                                                value: value,
                                                                bind: function (node) {
                                                                    ko.models.colorpicker({}, value, node);
                                                                    nodes.input(node);
                                                                }
                                                            }" />
                                                        <!-- /ko -->
                                                        <!-- ko if: type() === 'CUSTOM' -->
                                                            <input type="hidden" data-bind="{
                                                                bind: nodes.input,
                                                                value: value
                                                            }" />
                                                        <!-- /ko -->
                                                    </td>
                                                </tr>
                                            <!-- /ko -->
                                        <!-- /ko -->
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="constructor-dialog-footer">
        <div class="constructor-dialog-footer-wrapper">
            <div class="constructor-button constructor-button-s-3 constructor-button-c-blue constructor-save-button" data-bind="{
                click: componentProperties.save
            }"><?= Loc::getMessage('container.modals.component.properties.buttons.save') ?></div>
            <!-- ko if: $root.settings.development -->
                <div class="constructor-button constructor-button-s-3 constructor-button-c-blue constructor-save-button" data-bind="{
                    click: function () { return componentProperties.update(false); }
                }"><?= Loc::getMessage('container.modals.component.properties.buttons.update') ?></div>
                <div class="constructor-button constructor-button-s-3 constructor-button-c-blue constructor-save-button" data-bind="{
                    click: function () { return componentProperties.update(true); }
                }"><?= Loc::getMessage('container.modals.component.properties.buttons.update-clear') ?></div>
            <!-- /ko -->
            <div class="constructor-button constructor-button-s-3 constructor-button-c-blue-t-c" data-bind="{
                click: componentProperties.close
            }"><?= Loc::getMessage('container.modals.component.properties.buttons.cancel') ?></div>
        </div>
    </div>
</div>