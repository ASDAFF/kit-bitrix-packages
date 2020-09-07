<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die() ?>
<?php

use Bitrix\Main\Localization\Loc;
use intec\core\helpers\JavaScript;

?>
<div data-bind="{
    fade: function () { return menu.tabs.list.variator.isActive() },
    with: $root.selected
}">
    <div class="constructor-form">
        <div class="constructor-form-wrap">
            <!-- ko with: getVariator() -->
                <div class="constructor-menu-left-title">
                    <?= Loc::getMessage('variator.settings') ?>
                </div>
                <div class="constructor-row constructor-row-i-none">
                    <div class="constructor-form-group">
                        <div class="constructor-form-label">
                            <?= Loc::getMessage('variator.settings.variants') ?>
                        </div>
                        <!-- ko if: getVariants().length > 0 -->
                            <div class="constructor-form-content">
                                <div class="constructor-form-variants">
                                    <div class="constructor-form-variants-wrapper" data-bind="{
                                        bind: function (node) {
                                            var self = $(node);

                                            if (!$context.refresh)
                                                $context.display = ko.observable(true);

                                            self.sortable({
                                                'axis': 'y',
                                                'handle': '.constructor-form-variant-move',
                                                'stop': function (event, ui) {
                                                    var item = $(ui.item);
                                                    var model = ko.dataFor(item.get(0));
                                                    var selected = model.selected();

                                                    if (item.index() > model.order()) {
                                                        model.order(item.index() + 0.5);
                                                    } else if (item.index() < model.order()) {
                                                        model.order(item.index() - 0.5);
                                                    }

                                                    if (selected)
                                                        model.select();

                                                    $context.display(false);
                                                    $context.display(true);
                                                }
                                            });
                                        }
                                    }">
                                        <!-- ko if: $context.display -->
                                            <!-- ko foreach: getVariants() -->
                                                <div class="constructor-form-variant" data-bind="{
                                                    click: select,
                                                    css: {
                                                        'constructor-form-variant-selected': selected()
                                                    }
                                                }">
                                                    <div class="constructor-form-variant-wrapper">
                                                        <div class="constructor-grid constructor-grid-nowrap constructor-grid-a-v-center constructor-grid-i-h-4">
                                                            <!-- ko if: selected() -->
                                                                <div class="constructor-grid-item-auto">
                                                                    <div class="constructor-form-variant-move"></div>
                                                                </div>
                                                            <!-- /ko -->
                                                            <div class="constructor-grid-item">
                                                                <!-- ko if: !selected() -->
                                                                    <div class="constructor-form-variant-name" data-bind="{
                                                                        text: $root.settings.development() && code() != null ? name() + ' (' + code() + ')' : name()
                                                                    }"></div>
                                                                <!-- /ko -->
                                                                <!-- ko if: selected() -->
                                                                    <input class="constructor-input constructor-input-block" type="text" data-bind="{
                                                                        value: name
                                                                    }" />
                                                                    <!-- ko if: $root.settings.development() -->
                                                                        <input class="constructor-input constructor-input-block" type="text" style="margin-top: 10px;" data-bind="{
                                                                            value: code
                                                                        }" />
                                                                    <!-- /ko -->
                                                                <!-- /ko -->
                                                            </div>
                                                            <!-- ko if: selected() -->
                                                                <div class="constructor-grid-item-auto">
                                                                    <div class="constructor-form-variant-delete constructor-icon-cancel" data-bind="{
                                                                        click: remove
                                                                    }"></div>
                                                                </div>
                                                            <!-- /ko -->
                                                        </div>
                                                    </div>
                                                </div>
                                            <!-- /ko -->
                                        <!-- /ko -->
                                    </div>
                                </div>
                            </div>
                        <!-- /ko -->
                        <div class="constructor-row constructor-row-i-none">
                            <div class="constructor-form-group">
                                <div class="constructor-form-content">
                                    <div class="constructor-button constructor-button-block constructor-button-s-3 constructor-button-f-12 constructor-button-c-blue" data-bind="{
                                        click: function () {
                                            addVariant(new $root.models.variant({
                                                'name': <?= JavaScript::toObject(Loc::getMessage('variator.settings.variants.new')) ?>,
                                                'order': variants().length,
                                                'container': {
                                                    'type': 'normal',
                                                    'order': 0,
                                                    'display': true
                                                }
                                            }));
                                        }
                                    }">
                                        <?= Loc::getMessage('variator.settings.variants.add') ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <!-- /ko -->
        </div>
    </div>
</div>