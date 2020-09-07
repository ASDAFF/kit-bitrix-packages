<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die() ?>
<div data-bind="{
    fade: function () { return menu.tabs.list.widget.isActive() },
    with: $root.selected
}">
    <div class="constructor-form">
        <div class="constructor-form-wrap">
            <!-- ko if: hasWidget() -->
                <div class="constructor-menu-left-title" data-bind="{ text: element().model().name }"></div>
                <div class="constructor-row constructor-row-i-none" data-bind="{ with: element }">
                    <div class="constructor-form-group">
                        <div class="constructor-form-label"><?= GetMessage('container.settings.widget.template') ?></div>
                        <div class="constructor-form-content">
                            <select class="constructor-input constructor-input-block" data-bind="{
                                value: template,
                                options: model().templates,
                                optionsText: 'code',
                                bind: ko.models.select()
                            }"></select>
                        </div>
                    </div>
                </div>
                <div class="constructor-widget-settings" data-bind="{ with: element }">
                    <!-- ko with: structure -->
                        <div data-bind="{ htmlTemplate: $parent.views.settings }"></div>
                        <!-- ko function: $root.menu.scroll.update --><!-- /ko -->
                    <!-- /ko -->
                </div>
            <!-- /ko -->
        </div>
    </div>
</div>