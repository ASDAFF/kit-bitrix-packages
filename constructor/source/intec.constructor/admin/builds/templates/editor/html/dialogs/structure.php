<?php

use Bitrix\Main\Localization\Loc;

?>
<div class="constructor-dialog constructor-dialog-structure" data-bind="{
    bind: dialogs.list.structure,
    with: dialogs.list
}">
    <div class="constructor-dialog-header">
        <div class="constructor-dialog-header-wrapper">
            <div class="constructor-dialog-title">
                <?= Loc::getMessage('container.modals.structure.title') ?>
            </div>
            <div class="constructor-dialog-content"></div>
            <div class="constructor-dialog-buttons">
                <button class="constructor-dialog-button constructor-icon-cancel" data-bind="{
                    click: structure.close
                }"></button>
            </div>
        </div>
    </div>
    <div class="constructor-dialog-body">
        <div class="constructor-dialog-structure-body">
            <textarea class="constructor-dialog-structure-text" data-bind="{
                value: structure.value
            }"></textarea>
        </div>
    </div>
    <!-- ko if: structure.mode() === 'write' -->
        <div class="constructor-dialog-footer">
            <div class="constructor-dialog-footer-wrapper">
                <div class="constructor-button constructor-button-s-3 constructor-button-c-blue constructor-save-button" data-bind="{
                    click: structure.save
                }"><?= Loc::getMessage('container.modals.structure.buttons.save') ?></div>
                <div class="constructor-button constructor-button-s-3 constructor-button-c-blue-t-c" data-bind="{
                    click: structure.close
                }"><?= Loc::getMessage('container.modals.structure.buttons.cancel') ?></div>
            </div>
        </div>
    <!-- /ko -->
</div>