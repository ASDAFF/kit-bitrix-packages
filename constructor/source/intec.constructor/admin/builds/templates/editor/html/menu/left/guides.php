<?php
use Bitrix\Main\Localization\Loc;

?>
<div data-bind="{
    with: $root.guides,
    fade: function () { return menu.tabs.list.guides.isActive() }
}">
    <div class="constructor-form">
        <div class="constructor-menu-left-title"><?= Loc::getMessage('guides.settings') ?></div>
        <div class="constructor-row">
            <div class="constructor-column-12 no-select">
                <div class="constructor-form-group">
                    <div class="constructor-form-label">
                        <?= GetMessage('guides.settings.columns') ?>
                        <input type="checkbox" data-bind="{
                            checked: columns.active,
                            bind: ko.models.switch()
                        }" />
                    </div>
                </div>
            </div>
            <div class="constructor-clearfix" data-bind="slide: columns.active">
                <div class="constructor-column-6">
                    <div class="constructor-form-group">
                        <div class="constructor-form-label"><?= Loc::getMessage('guides.settings.columns.count') ?></div>
                        <div class="constructor-form-content">
                            <input type="text" class="constructor-input" data-bind="{ value: columns.count }" />
                        </div>
                    </div>
                </div>
                <div class="constructor-column-6">
                    <div class="constructor-form-group">
                        <div class="constructor-form-label"><?= Loc::getMessage('guides.settings.columns.space') ?></div>
                        <div class="constructor-form-content">
                            <div class="constructor-grid constructor-grid-i-h-4 constructor-nowrap">
                                <div class="constructor-grid-item">
                                    <input type="text" class="constructor-input" data-bind="{ value: columns.space.value }" />
                                </div>
                                <div class="constructor-grid-item-6">
                                    <select class="constructor-input" data-bind="{
                                        value: columns.space.measure,
                                        options: columns.space.measures(),
                                        bind: ko.models.select({
                                            'theme': 'gray'
                                        })
                                    }"></select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="constructor-column-12 no-select">
                <div class="constructor-form-group">
                    <div class="constructor-form-label">
                        <?= GetMessage('guides.settings.rows') ?>
                        <input type="checkbox" data-bind="{
                            checked: rows.active,
                            bind: ko.models.switch()
                        }" />
                    </div>
                </div>
            </div>
            <div class="constructor-clearfix" data-bind="slide: rows.active">
                <div class="constructor-column-6">
                    <div class="constructor-form-group">
                        <div class="constructor-form-label"><?= Loc::getMessage('guides.settings.rows.space') ?></div>
                        <div class="constructor-form-content">
                            <div class="constructor-grid constructor-grid-i-h-4 constructor-nowrap">
                                <div class="constructor-grid-item">
                                    <input type="text" class="constructor-input" data-bind="{ value: rows.space.value }" />
                                </div>
                                <div class="constructor-grid-item-6">
                                    <select class="constructor-input" data-bind="
                                        value: rows.space.measure,
                                        options: rows.space.measures(),
                                        bind: ko.models.select({
                                            'theme': 'gray'
                                        })
                                    "></select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- ko function: $root.menu.scroll.update --><!-- /ko -->
</div>