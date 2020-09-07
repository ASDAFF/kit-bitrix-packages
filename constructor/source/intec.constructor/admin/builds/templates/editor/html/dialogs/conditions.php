<?

use Bitrix\Main\Localization\Loc;
use intec\core\helpers\JavaScript;
use intec\constructor\models\build\template\Container;

?>
<div class="constructor-dialog constructor-dialog-conditions" data-bind="{
    bind: dialogs.list.conditions,
    with: dialogs.list
}">
    <div class="constructor-dialog-header">
        <div class="constructor-dialog-header-wrapper">
            <div class="constructor-dialog-title">
                <?= Loc::getMessage('container.modals.conditions.title') ?>
            </div>
            <div class="constructor-dialog-content"></div>
            <div class="constructor-dialog-buttons">
                <button class="constructor-dialog-button constructor-icon-cancel" data-bind="{
                    click: conditions.close
                }"></button>
            </div>
        </div>
    </div>
    <div class="constructor-dialog-body">
        <div class="no-select" data-bind="with: $root.selected">
            <div class="constructor-condition-main-settings">
                <div class="constructor-row">
                    <div class="constructor-column-2">
                        <div class="constructor-condition-field">
                            <div class="constructor-condition-field-title">
                                <?= GetMessage('container.modals.conditions.group.header.operators') ?>
                            </div>
                            <div class="constructor-condition-field-content">
                                <select class="constructor-input" data-bind="{
                                    options: $root.dialogs.list.conditions.operators,
                                    optionsText: 'value',
                                    optionsValue: 'key',
                                    value: $root.selected().condition.operator,
                                    bind: ko.models.select({
                                        'theme': 'white'
                                    })
                                }"></select>
                            </div>
                        </div>
                    </div>
                    <div class="constructor-column-2">
                        <div class="constructor-condition-field">
                            <div class="constructor-condition-field-title">
                                <?= GetMessage('container.modals.conditions.group.result') ?>
                            </div>
                            <div class="constructor-condition-field-content">
                                <select class="constructor-input" data-bind="{
                                    value: $root.selected().condition.result,
                                    bind: ko.models.select({
                                        'theme': 'white'
                                    })
                                }">
                                    <option value="1"><?= GetMessage('container.modals.conditions.group.result.yes') ?></option>
                                    <option value="0"><?= GetMessage('container.modals.conditions.group.result.no') ?></option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="constructor-column-2 constructor-column-offset-2">
                        <div class="constructor-condition-field">
                            <div class="constructor-condition-field-title">
                                <?= GetMessage('container.modals.conditions.group') ?>
                            </div>
                            <div class="constructor-condition-field-content">
                                <select class="constructor-input" data-bind="{
                                    options: $root.dialogs.list.conditions.groups,
                                    optionsText: 'name',
                                    optionsValue: 'value',
                                    value: $root.dialogs.list.conditions.group,
                                    bind: ko.models.select({
                                        'theme': 'white'
                                    })
                                }"></select>
                            </div>
                        </div>
                    </div>
                    <div class="constructor-column-2">
                        <div class="constructor-condition-field">
                            <div class="constructor-condition-field-title">
                                <?= GetMessage('container.modals.conditions.group.footer.type') ?>
                            </div>
                            <div class="constructor-condition-field-content">
                                <select class="constructor-input" data-bind="{
                                    options: $root.dialogs.list.conditions.types,
                                    optionsText: 'value',
                                    optionsValue: 'key',
                                    value: $root.dialogs.list.conditions.type,
                                    bind: ko.models.select({
                                        'theme': 'white'
                                    })
                                }"></select>
                            </div>
                        </div>
                    </div>
                    <div class="constructor-column-2">
                        <div class="constructor-condition-field">
                            <div class="constructor-condition-field-title"></div>
                            <div class="constructor-condition-field-content">
                                <div class="constructor-button constructor-button-block constructor-button-c-blue constructor-button-s-2"
                                     data-bind="click: function(){ $root.dialogs.list.conditions.add(); }">
                                    <?= GetMessage('container.modals.conditions.group.footer.add') ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="constructor-condition-list-scroller nano" data-bind="{
                bind: $root.dialogs.list.conditions.scroll
            }">
                <div class="constructor-condition-list constructor-condition-list-root nano-content">
                    <div class="constructor-condition-list-wrap" data-bind="{
                        template: {
                            name: 'condition',
                            data: condition
                        }
                    }"></div>
                </div>
            </div>
        </div>
        <script id="condition" type="text\html">
            <div class="constructor-condition" data-bind="{
                css: 'constructor-condition-type-' + (hasParent() ? type() : 'root')
            }">
                <div class="constructor-condition-wrap">
                    <div data-bind="
                            if: type() === <?= JavaScript::toObject(Container::CONDITION_TYPE_GROUP) ?>,
                            bind: function (node, bindings) {
                                var model;

                                if (!hasParent())
                                    return;

                                model = ko.models.accordion({
                                    active: true,
                                    header: '> .constructor-condition-group-header-wrap',
                                    icons: {
                                        'header': 'constructor-ui-icon-arrow-static fa fa-angle-down',
                                        'activeHeader': 'constructor-ui-icon-arrow-active fa fa-angle-up'
                                    },
                                    classes: {
                                        'ui-accordion-header': 'constructor-accordion-header',
                                        'ui-accordion-header-collapsed': 'constructor-accordion-header-collapsed',
                                        'ui-accordion-content': 'constructor-accordion-content constructor-clearfix'
                                    },
                                    activate: function (event, ui) {
                                        $root.dialogs.list.conditions.scroll.update();
                                    },
                                    beforeActivate: function (event, ui) {
                                        var element;

                                        element = $(event.srcElement);

                                        if (element.is('select, input, .ui-selectmenu-button, .ui-selectmenu-icon, .ui-selectmenu-text, .constructor-icon-cancel'))
                                            return false;
                                    }
                                });

                                model(node);

                                $data.on('add', function(){
                                    model.refresh();
                                });
                            }">
                        <div class="constructor-condition-group-header-wrap" data-bind="if: hasParent()">
                            <div class="constructor-condition-group-header constructor-clearfix">
                                <div class="constructor-condition-group-bg"></div>
                                <div class="constructor-condition-group-settings">
                                    <div class="constructor-condition-field constructor-condition-field-inline constructor-condition-group-settings-operator">
                                        <div class="constructor-condition-field-title">
                                            <?= GetMessage('container.modals.conditions.group.header.operators') ?>
                                        </div>
                                        <div class="constructor-condition-field-content">
                                            <select class="constructor-input" data-bind="{
                                                options: $root.dialogs.list.conditions.operators,
                                                optionsText: 'value',
                                                optionsValue: 'key',
                                                value: operator,
                                                bind: ko.models.select({
                                                    'theme': 'white'
                                                })
                                            }"></select>
                                        </div>
                                    </div>
                                    <div class="constructor-condition-field constructor-condition-field-inline constructor-condition-group-settings-result">
                                        <div class="constructor-condition-field-title">
                                            <?= GetMessage('container.modals.conditions.group.result') ?>
                                        </div>
                                        <div class="constructor-condition-field-content">
                                            <select class="constructor-input" data-bind="{
                                                value: result,
                                                bind: ko.models.select({
                                                    'theme': 'white'
                                                })
                                            }">
                                                <option value="1"><?= GetMessage('container.modals.conditions.group.result.yes') ?></option>
                                                <option value="0"><?= GetMessage('container.modals.conditions.group.result.no') ?></option>
                                            </select>
                                        </div>
                                    </div>
                                    <i class="constructor-icon-cancel" title="<?= GetMessage('container.modals.conditions.remove') ?>" data-bind="{
                                        bind: ko.models.tooltip(),
                                        click: remove
                                    }"></i>
                                </div>
                                <div class="constructor-condition-group-title">
                                    <span class="constructor-condition-group-title-arrows">
                                        <!-- ko if: hasConditions() -->
                                        <span class="fa fa-angle-down"></span>
                                        <span class="fa fa-angle-right"></span>
                                        <!-- /ko -->
                                    </span>
                                    <span class="constructor-condition-group-title-text">
                                        <?= GetMessage('container.modals.conditions.group') ?>
                                        <span data-bind="text: level() + '-' + ($index() + 1)"></span>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <!-- ko if: hasConditions() -->
                        <div class="constructor-condition-list">
                            <div class="constructor-condition-list-wrap"
                                 data-bind="template: { name: 'condition', foreach: conditions }"></div>
                        </div>
                        <!-- /ko -->
                    </div>
                    <!-- ko if: (
                        type() === <?= JavaScript::toObject(Container::CONDITION_TYPE_PATH) ?> ||
                        type() === <?= JavaScript::toObject(Container::CONDITION_TYPE_MATCH) ?> ||
                        type() === <?= JavaScript::toObject(Container::CONDITION_TYPE_PARAMETER_GET) ?> ||
                        type() === <?= JavaScript::toObject(Container::CONDITION_TYPE_PARAMETER_PAGE) ?> ||
                        type() === <?= JavaScript::toObject(Container::CONDITION_TYPE_PARAMETER_TEMPLATE) ?> ||
                        type() === <?= JavaScript::toObject(Container::CONDITION_TYPE_EXPRESSION) ?> ||
                        type() === <?= JavaScript::toObject(Container::CONDITION_TYPE_SITE) ?>
                    ) -->
                        <!-- ko if: hasParent() -->
                            <div class="constructor-condition-remove-wrap constructor-condition-field">
                                <div class="constructor-condition-field-title"></div>
                                <div class="constructor-button constructor-button-c-blue-t-c constructor-button-s-2"
                                     data-bind="click: remove">
                                     <?= GetMessage('container.modals.conditions.remove') ?>
                                 </div>
                            </div>
                        <!-- /ko -->
                        <div class="constructor-condition-field constructor-condition-result">
                            <div class="constructor-condition-field-title">
                                <?= GetMessage('container.modals.conditions.group.result') ?>
                            </div>
                            <div class="constructor-condition-field-content">
                                <select class="constructor-input" data-bind="{
                                    value: result,
                                    bind: ko.models.select({
                                        'theme': 'white'
                                    })
                                }">
                                    <option value="1"><?= GetMessage('container.modals.conditions.group.result.true') ?></option>
                                    <option value="0"><?= GetMessage('container.modals.conditions.group.result.false') ?></option>
                                </select>
                            </div>
                        </div>
                        <!-- ko if:
                            type() === <?= JavaScript::toObject(Container::CONDITION_TYPE_PATH) ?> ||
                            type() === <?= JavaScript::toObject(Container::CONDITION_TYPE_EXPRESSION) ?>
                        -->
                            <div class="constructor-row">
                                <div class="constructor-column-12">
                                    <div class="constructor-condition-field">
                                        <div class="constructor-condition-field-title">
                                            <!-- ko if: type() === <?= JavaScript::toObject(Container::CONDITION_TYPE_PATH) ?> -->
                                                <?= GetMessage('container.modals.conditions.path') ?>
                                            <!-- /ko -->
                                            <!-- ko if: type() === <?= JavaScript::toObject(Container::CONDITION_TYPE_EXPRESSION) ?> -->
                                                <?= GetMessage('container.modals.conditions.expression') ?>
                                            <!-- /ko -->
                                        </div>
                                        <div class="constructor-condition-field-content">
                                            <input class="constructor-input constructor-input_light"
                                                type="text"
                                                data-bind="value: value"
                                            />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <!-- /ko -->
                        <!-- ko if:
                            type() === <?= JavaScript::toObject(Container::CONDITION_TYPE_SITE) ?>
                        -->
                            <div class="constructor-row">
                                <div class="constructor-column-12">
                                    <div class="constructor-condition-field">
                                        <div class="constructor-condition-field-title">
                                            <!-- ko if: type() === <?= JavaScript::toObject(Container::CONDITION_TYPE_SITE) ?> -->
                                                <?= GetMessage('container.modals.conditions.site') ?>
                                            <!-- /ko -->
                                        </div>
                                        <div class="constructor-condition-field-content">
                                            <select data-bind="{
                                                value: value,
                                                options: $root.environment.sites,
                                                optionsValue: 'id',
                                                optionsText: function (site) { return '[' + site.id() + '] ' + site.name(); },
                                                bind: ko.models.select({
                                                    'theme': 'white'
                                                })
                                            }"></select>
                                            <?/*<input class="constructor-input constructor-input_light"
                                                type="text"
                                                data-bind="value: value"
                                            />*/?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <!-- /ko -->
                        <!-- ko if:
                            type() === <?= JavaScript::toObject(Container::CONDITION_TYPE_PARAMETER_GET) ?> ||
                            type() === <?= JavaScript::toObject(Container::CONDITION_TYPE_PARAMETER_PAGE) ?> ||
                            type() === <?= JavaScript::toObject(Container::CONDITION_TYPE_PARAMETER_TEMPLATE) ?>
                        -->
                            <div class="constructor-row">
                                <div class="constructor-column-6">
                                    <div class="constructor-condition-field">
                                        <div class="constructor-condition-field-title">
                                            <!-- ko if: type() === <?= JavaScript::toObject(Container::CONDITION_TYPE_PARAMETER_GET) ?> -->
                                                <?= GetMessage('container.modals.conditions.parameter.get') ?>
                                            <!-- /ko -->
                                            <!-- ko if: type() === <?= JavaScript::toObject(Container::CONDITION_TYPE_PARAMETER_PAGE) ?> -->
                                                <?= GetMessage('container.modals.conditions.parameter.page') ?>
                                            <!-- /ko -->
                                            <!-- ko if: type() === <?= JavaScript::toObject(Container::CONDITION_TYPE_PARAMETER_TEMPLATE) ?> -->
                                                <?= GetMessage('container.modals.conditions.parameter.template') ?>
                                            <!-- /ko -->
                                        </div>
                                        <div class="constructor-condition-field-content">
                                            <input class="constructor-input constructor-input_light"
                                                type="text"
                                                data-bind="value: key"
                                            />
                                        </div>
                                    </div>
                                </div>
                                <!-- ko if: type() === <?= JavaScript::toObject(Container::CONDITION_TYPE_PARAMETER_GET) ?> -->
                                    <div class="constructor-column-6">
                                        <div class="constructor-condition-field">
                                            <div class="constructor-condition-field-title">
                                                <?= GetMessage('container.modals.conditions.value') ?>
                                            </div>
                                            <div class="constructor-condition-field-content">
                                                <input class="constructor-input constructor-input_light"
                                                    type="text"
                                                    data-bind="value: value"
                                                />
                                            </div>
                                        </div>
                                    </div>
                                <!-- /ko -->
                                <!-- ko if: type() !== <?= JavaScript::toObject(Container::CONDITION_TYPE_PARAMETER_GET) ?> -->
                                    <div class="constructor-column-2">
                                        <div class="constructor-condition-field">
                                            <div class="constructor-condition-field-title">
                                                <?= GetMessage('container.modals.conditions.logic') ?>
                                            </div>
                                            <div class="constructor-condition-field-content">
                                                <select class="constructor-input" data-bind="{
                                                    value: logic,
                                                    bind: ko.models.select({
                                                        'theme': 'white'
                                                    })
                                                }">
                                                    <?php $logics = Container::getConditionLogics() ?>
                                                    <?php foreach ($logics as $key => $logic) { ?>
                                                        <option value="<?= $key ?>"><?= $logic ?></option>
                                                    <?php } ?>
                                                    <?php unset($logics) ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="constructor-column-4">
                                        <div class="constructor-condition-field">
                                            <div class="constructor-condition-field-title">
                                                <?= GetMessage('container.modals.conditions.value') ?>
                                            </div>
                                            <div class="constructor-condition-field-content">
                                                <input class="constructor-input constructor-input_light"
                                                    type="text"
                                                    data-bind="value: value"
                                                />
                                            </div>
                                        </div>
                                    </div>
                                <!-- /ko -->
                            </div>
                        <!-- /ko -->
                        <!-- ko if: type() === <?= JavaScript::toObject(Container::CONDITION_TYPE_MATCH) ?> -->
                            <div class="constructor-row">
                                <div class="constructor-column-6">
                                    <div class="constructor-condition-field">
                                        <div class="constructor-condition-field-title">
                                            <?= GetMessage('container.modals.conditions.match') ?>
                                        </div>
                                        <div class="constructor-condition-field-content">
                                            <input class="constructor-input constructor-input_light"
                                                type="text"
                                                data-bind="value: value"
                                            />
                                        </div>
                                    </div>
                                </div>
                                <div class="constructor-column-6">
                                    <div class="constructor-condition-field">
                                        <div class="constructor-condition-field-title">
                                                <?= GetMessage('container.modals.conditions.compare') ?>
                                        </div>
                                        <div class="constructor-condition-field-content">
                                            <select class="constructor-input constructor-input_light" data-bind="{
                                                value: match,
                                                bind: ko.models.select({
                                                    'theme': 'white'
                                                })
                                            }">
                                                <?php $matches = Container::getConditionMatches() ?>
                                                <?php foreach ($matches as $key => $match) { ?>
                                                    <option value="<?= $key ?>"><?= $match ?></option>
                                                <?php } ?>
                                                <?php unset($matches) ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <!-- /ko -->
                        <div class="constructor-clearfix"></div>
                    <!-- /ko -->
                    <!-- ko function: $root.dialogs.list.conditions.scroll.update --><!-- /ko -->
                </div>
            </div>
            <!-- ko if: type() === <?= JavaScript::toObject(Container::CONDITION_TYPE_GROUP) ?> && hasParent() && hasConditions() -->
                <hr class="constructor-condition-separator" />
            <!-- /ko -->
        </script>
    </div>
</div>