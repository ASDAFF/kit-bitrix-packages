<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die() ?>
<?php

use Bitrix\Main\Localization\Loc;

/**
 * @var array $directions
 */

?>
<div id="addition-offset" style="width: 350px;">
    <!-- ko with: $root.selected -->
        <!-- ko with: properties -->
            <div class="constructor-form-label">
                <?= Loc::getMessage('container.settings.positioning') ?>
            </div>
            <div class="constructor-four-direction-settings constructor-position-settings constructor-row">
                <? foreach ($directions as $direction) { ?>
                    <div class="constructor-column-5">
                        <div class="constructor-form-group">
                            <div class="constructor-form-content">
                                <div class="constructor-grid constructor-grid-a-v-center constructor-grid-i-h-4 constructor-grid-nowrap">
                                    <div class="constructor-grid-item-auto">
                                        <div class="constructor-icon-arrow-offset-<?= $direction ?>"></div>
                                    </div>
                                    <div class="constructor-grid-item">
                                        <input type="text" class="constructor-input" data-bind="{
                                            value: <?= $direction ?>.value
                                        }" />
                                    </div>
                                    <div class="constructor-grid-item-5">
                                        <select class="constructor-input" data-bind="{
                                            value: <?= $direction ?>.measure,
                                            options: <?= $direction ?>.measures(),
                                            bind: ko.models.select({
                                                'theme': 'gray'
                                            })
                                        }"></select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <? } ?>
            </div>
            <div class="constructor-form-label"><?= Loc::getMessage('container.settings.margin') ?></div>
            <div class="constructor-four-direction-settings constructor-row">
                <div class="shared-icon constructor-vertical-middle"
                    title="<?= Loc::getMessage('button.share') ?>"
                    data-bind="{
                        bind: ko.models.tooltip(),
                        event: {click: function(){ margin.isShared(margin.isShared() ? 0 : 1) }},
                        css: margin.isShared() ? 'active' : ''
                    }">
                    <i class="constructor-icon-linked"></i>
                    <i class="constructor-icon-unlinked"></i>
                </div>
                <? foreach ($directions as $direction) { ?>
                    <div class="constructor-column-5">
                        <div class="constructor-form-group">
                            <div class="constructor-form-content">
                                <div class="constructor-grid constructor-grid-a-v-center constructor-grid-i-h-4 constructor-grid-nowrap">
                                    <div class="constructor-grid-item-auto">
                                        <div class="constructor-icon-arrow-margin-<?= $direction ?>"></div>
                                    </div>
                                    <div class="constructor-grid-item">
                                        <input type="text" class="constructor-input" data-bind="{
                                            value: margin.<?= $direction ?>.value
                                        }" />
                                    </div>
                                    <div class="constructor-grid-item-5">
                                        <select class="constructor-input" data-bind="{
                                            value: margin.<?= $direction ?>.measure,
                                            options: margin.<?= $direction ?>.measures(),
                                            bind: ko.models.select({
                                                'theme': 'gray'
                                            })
                                        }"></select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <? } ?>
            </div>
            <div class="constructor-form-label" style="margin-top: 10px;">
                <?= Loc::getMessage('container.settings.padding') ?>
            </div>
            <div class="constructor-four-direction-settings constructor-row">
                <div class="shared-icon constructor-vertical-middle"
                    title="<?= Loc::getMessage('button.share') ?>"
                    data-bind="{
                        bind: ko.models.tooltip(),
                        event: {click: function(){ padding.isShared(padding.isShared() ? 0 : 1) }},
                        css: padding.isShared() ? 'active' : ''
                    }">
                    <i class="constructor-icon-linked"></i>
                    <i class="constructor-icon-unlinked"></i>
                </div>
                <? foreach ($directions as $direction) { ?>
                    <div class="constructor-column-5">
                        <div class="constructor-form-group">
                            <div class="constructor-form-content constructor-input-group constructor-input-group-measures">
                                <div class="constructor-grid constructor-grid-a-v-center constructor-grid-i-h-4 constructor-grid-nowrap">
                                    <div class="constructor-grid-item-auto">
                                        <div class="constructor-icon-arrow-padding-<?= $direction ?>"></div>
                                    </div>
                                    <div class="constructor-grid-item">
                                        <input type="text" class="constructor-input" data-bind="{
                                            value: padding.<?= $direction ?>.value
                                        }" />
                                    </div>
                                    <div class="constructor-grid-item-5">
                                        <select class="constructor-input" data-bind="{
                                            value: padding.<?= $direction ?>.measure,
                                            options: padding.<?= $direction ?>.measures(),
                                            bind: ko.models.select({
                                                'theme': 'gray'
                                            })
                                        }"></select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <? } ?>
            </div>
        <!-- /ko -->
    <!-- /ko -->
</div>