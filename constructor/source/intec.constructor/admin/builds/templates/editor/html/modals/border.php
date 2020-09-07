<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die() ?>
<?php

use Bitrix\Main\Localization\Loc;

/**
 * @var array $directions
 * @var array $lang
 */

?>
<div id="addition-border">
    <!-- ko with: $root.selected -->
        <div data-bind="{ with: properties.border }">
            <table class="additional-settings" cellpadding="0" cellspacing="0" style="width: 420px;">
                <thead>
                    <tr>
                        <th></th>
                        <th style="width: 120px;"><?= Loc::getMessage('container.settings.border.width') ?></th>
                        <th><?= Loc::getMessage('container.settings.border.style') ?></th>
                        <th style="width: 110px;"><?= Loc::getMessage('container.settings.border.color') ?></th>
                    </tr>
                </thead>
                <tbody>
                    <? foreach ($directions as $direction) { ?>
                        <tr>
                            <td>
                                <div class="constructor-icon constructor-icon-arrow-padding-<?= $direction ?>"></div>
                            </td>
                            <td>
                                <div class="constructor-grid constructor-grid-i-h-4 constructor-grid-nowrap" style="width: 125px">
                                    <div class="constructor-grid-item">
                                        <input type="text" class="constructor-input" data-bind="{
                                            value: <?= $direction ?>.width.value
                                        }" />
                                    </div>
                                    <div class="constructor-grid-item-7">
                                        <select class="constructor-input" data-bind="{
                                            value: <?= $direction ?>.width.measure,
                                            options: <?= $direction ?>.width.measures(),
                                            bind: ko.models.select({
                                                'theme': 'gray'
                                            })
                                        }"></select>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div style="width: 140px;">
                                    <select class="constructor-input" data-bind="{
                                        value: <?= $direction ?>.style.value,
                                        options: <?= $lang['border'] ?>,
                                        optionsValue: 'key',
                                        optionsText: 'value',
                                        bind: ko.models.select({
                                            'theme': 'gray'
                                        })
                                    }"></select>
                                </div>
                            </td>
                            <td>
                                <div class="constructor-grid constructor-grid-nowrap">
                                    <div class="constructor-grid-item-auto">
                                        <div class="constructor-colorpicker-button constructor-vertical-middle" data-bind="{
                                            bind: ko.models.colorpicker({}, <?= $direction ?>.color.value),
                                            style: {
                                                backgroundColor: <?= $direction ?>.color.value
                                            }
                                        }">
                                            <div class="constructor-aligner"></div>
                                            <i class="far fa-eye-dropper"></i>
                                        </div>
                                    </div>
                                    <div class="constructor-grid-item">
                                        <input type="text" class="constructor-input constructor-colorpicker-input" data-bind="{
                                            value: <?= $direction ?>.color.value
                                        }" />
                                    </div>
                                </div>
                            </td>
                        </tr>
                    <? } ?>
                </tbody>
            </table>
        </div>
    <!-- /ko -->
</div>