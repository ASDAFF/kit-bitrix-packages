<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die() ?>
<?php

use Bitrix\Main\Localization\Loc;

/**
 * @var array $directions
 * @var array $lang
 */

?>
<div id="addition-background" style="width:300px;">
    <!-- ko with: $root.selected -->
        <div data-bind="{ with: properties.background }">
            <div class="constructor-form-group">
                <div class="constructor-form-label"><?= Loc::getMessage('container.settings.background.repeat') ?></div>
                <div class="constructor-form-content">
                    <select class="constructor-input" data-bind="{
                        value: repeat,
                        options: <?= $lang['repeat'] ?>,
                        optionsValue: 'key',
                        optionsText: 'value',
                        bind: ko.models.select({
                            'theme': 'gray'
                        })
                    }">
                        <? foreach (['inherit', 'repeat', 'repeat-x', 'repeat-y', 'no-repeat'] as $value) { ?>
                            <option value="<?= $value ?>"><?= Loc::getMessage('container.settings.background.repeat.'. $value) ?></option>
                        <? } ?>
                    </select>
                </div>
            </div>
            <div class="constructor-row">
                <? foreach (['top', 'left'] as $direction) { ?>
                    <div class="constructor-column-6">
                        <div class="constructor-form-group">
                            <div class="constructor-form-label"><?= Loc::getMessage('container.settings.background.position.'. $direction) ?></div>
                            <div class="constructor-form-content">
                                <div class="constructor-grid constructor-grid-i-h-4 constructor-grid-nowrap">
                                    <div class="constructor-grid-item">
                                        <input type="text" class="constructor-input" data-bind="{
                                            value: position.<?= $direction ?>.value
                                        }" />
                                    </div>
                                    <div class="constructor-grid-item-6">
                                        <select class="constructor-input" data-bind="{
                                            value: position.<?= $direction ?>.measure,
                                            options: position.<?= $direction ?>.measures(),
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
            <div class="constructor-form-group">
                <div class="constructor-form-label">
                    <?= Loc::getMessage('container.settings.background.size') ?>
                </div>
                <div class="constructor-form-content">
                    <select class="constructor-input" data-bind="{
                        value: size.type,
                        bind: ko.models.select({
                            'theme': 'gray'
                        })
                    }">
                        <? foreach (['auto', 'cover', 'contain', 'custom'] as $value) { ?>
                            <option value="<?= $value ?>">
                                <?= Loc::getMessage('container.settings.background.size.'.$value) ?>
                            </option>
                        <? } ?>
                    </select>
                </div>
            </div>
            <div class="constructor-row" data-bind="{ visible: size.type() == 'custom' }">
                <? foreach (['width', 'height'] as $axis) { ?>
                    <div class="constructor-column-6">
                        <div class="constructor-form-group" data-bind="{ with: size }">
                            <div class="constructor-form-label"><?= Loc::getMessage('container.settings.background.size.'. $axis) ?></div>
                            <div class="constructor-form-content">
                                <div class="constructor-grid constructor-grid-i-h-4 constructor-grid-nowrap">
                                    <div class="constructor-grid-item">
                                        <input type="text" class="constructor-input" data-bind="{
                                            value: <?= $axis ?>.value
                                        }" />
                                    </div>
                                    <div class="constructor-grid-item-6">
                                        <select class="constructor-input" data-bind="{
                                            value: <?= $axis ?>.measure,
                                            options: <?= $axis ?>.measures(),
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
        </div>
    <!-- /ko -->
</div>
