<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die() ?>
<?php

use Bitrix\Main\Localization\Loc;

?>
<div id="addition-sizes">
    <!-- ko with: $root.selected -->
        <div data-bind="{ with: properties }">
            <table class="additional-settings" cellpadding="0" cellspacing="0" style="width: 320px;">
                <thead>
                    <tr>
                        <th></th>
                        <th><?= Loc::getMessage('container.settings.min') ?></th>
                        <th><?= Loc::getMessage('container.settings.max') ?></th>
                    </tr>
                </thead>
                <tbody>
                    <? foreach (['width', 'height'] as $size) { ?>
                        <tr>
                            <td><i class="constructor-icon-size-<?= $size ?>"></i></td>
                            <td>
                                <div class="constructor-grid constructor-grid-i-h-4 constructor-grid-nowrap" style="width: 125px">
                                    <div class="constructor-grid-item">
                                        <input type="text" class="constructor-input" data-bind="{
                                            value: <?= $size ?>.min.value
                                        }" />
                                    </div>
                                    <div class="constructor-grid-item-6">
                                        <select class="constructor-input" data-bind="{
                                            value: <?= $size ?>.min.measure,
                                            options: <?= $size ?>.min.measures(),
                                            bind: ko.models.select({
                                                'theme': 'gray'
                                            })
                                        }"></select>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="constructor-grid constructor-grid-i-h-4 constructor-grid-nowrap" style="width: 125px">
                                    <div class="constructor-grid-item">
                                        <input type="text" class="constructor-input" data-bind="{
                                            value: <?= $size ?>.max.value
                                        }" />
                                    </div>
                                    <div class="constructor-grid-item-6">
                                        <select class="constructor-input" data-bind="{
                                            value: <?= $size ?>.max.measure,
                                            options: <?= $size ?>.max.measures(),
                                            bind: ko.models.select({
                                                'theme': 'gray'
                                            })
                                        }"></select>
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