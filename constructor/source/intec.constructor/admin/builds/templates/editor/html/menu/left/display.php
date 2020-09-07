<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die() ?>
<?php

use Bitrix\Main\Localization\Loc;

?>
<div data-bind="{
    fade: function () { return menu.tabs.list.visual.isActive() }
}">
    <div class="constructor-form">
        <div class="constructor-form-wrap">
            <div class="constructor-menu-left-title">
                <?= Loc::getMessage('display.settings') ?>
            </div>
            <div class="constructor-form-group">
                <div class="constructor-form-label">
                    <?= Loc::getMessage('display.settings.site') ?>
                </div>
                <div class="constructor-form-content">
                    <select class="constructor-input constructor-input-block" data-bind="{
                        value: $root.environment.site,
                        options: $root.environment.sites,
                        optionsText: function (site) { return '[' + site.id() + '] ' + site.name(); },
                        bind: ko.models.select()
                    }"></select>
                </div>
            </div>
            <div class="constructor-form-group">
                <div class="constructor-form-label">
                    <div class="constructor-grid constructor-grid-i-h-8 constructor-grid-a-v-center constructor-grid-nowrap">
                        <div class="constructor-grid-item-auto">
                            <input type="checkbox" data-bind="{
                                bind: ko.models.switch(),
                                checked: $root.settings.development
                            }" />
                        </div>
                        <div class="constructor-grid-item">
                            <?= Loc::getMessage('display.settings.development') ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="constructor-form-group">
                <div class="constructor-form-label">
                    <div class="constructor-grid constructor-grid-i-h-8 constructor-grid-a-v-center constructor-grid-nowrap">
                        <div class="constructor-grid-item-auto">
                            <input type="checkbox" data-bind="{
                                bind: ko.models.switch(),
                                checked: $root.settings.containers.hidden
                            }" />
                        </div>
                        <div class="constructor-grid-item">
                            <?= Loc::getMessage('display.settings.containers.hidden') ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="constructor-form-group">
                <div class="constructor-form-label">
                    <div class="constructor-grid constructor-grid-i-h-8 constructor-grid-a-v-center constructor-grid-nowrap">
                        <div class="constructor-grid-item-auto">
                            <input type="checkbox" data-bind="{
                                bind: ko.models.switch(),
                                checked: $root.settings.containers.structure
                            }" />
                        </div>
                        <div class="constructor-grid-item">
                            <?= Loc::getMessage('display.settings.containers.structure') ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="constructor-form-group">
                <div class="constructor-form-label">
                    <div class="constructor-grid constructor-grid-i-h-8 constructor-grid-a-v-center constructor-grid-nowrap">
                        <div class="constructor-grid-item-auto">
                            <input type="checkbox" data-bind="{
                                bind: ko.models.switch(),
                                checked: $root.grid.show
                            }" />
                        </div>
                        <div class="constructor-grid-item">
                            <?= Loc::getMessage('display.settings.containers.grid') ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>