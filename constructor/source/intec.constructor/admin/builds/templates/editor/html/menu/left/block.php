<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die() ?>
<?php

use Bitrix\Main\Localization\Loc;

?>
<div data-bind="{
    fade: function () { return menu.tabs.list.block.isActive() },
    with: $root.selected
}">
    <div class="constructor-form">
        <div class="constructor-form-wrap">
            <!-- ko with: getBlock() -->
                <div class="constructor-menu-left-title">
                    <?= Loc::getMessage('block.settings') ?>
                </div>
                <div class="constructor-row constructor-row-i-none">
                    <div class="constructor-form-group">
                        <div class="constructor-form-label">
                            <?= Loc::getMessage('block.settings.name') ?>
                        </div>
                        <div class="constructor-form-content">
                            <input type="text" class="constructor-input" data-bind="{
                                value: name
                            }" />
                        </div>
                    </div>
                </div>
                <div class="constructor-row constructor-row-i-none">
                    <div class="constructor-form-group">
                        <div class="constructor-form-content">
                            <div class="constructor-button constructor-button-block constructor-button-s-3 constructor-button-f-12 constructor-button-c-blue" data-bind="{
                                click: function () {
                                    $root.dialogs.list.blockConvert.open($data);
                                }
                            }">
                                <?= Loc::getMessage('block.settings.convert') ?>
                            </div>
                        </div>
                    </div>
                </div>
            <!-- /ko -->
        </div>
    </div>
</div>