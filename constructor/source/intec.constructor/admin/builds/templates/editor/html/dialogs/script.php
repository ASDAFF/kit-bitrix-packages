<?php

use Bitrix\Main\Localization\Loc;

?>
<div class="constructor-dialog constructor-dialog-script" data-bind="{
    bind: dialogs.list.script,
    with: dialogs.list
}">
    <div class="constructor-dialog-header">
        <div class="constructor-dialog-header-wrapper">
            <div class="constructor-dialog-title">
                <?= Loc::getMessage('container.modals.script.title') ?>
            </div>
            <div class="constructor-dialog-content"></div>
            <div class="constructor-dialog-buttons">
                <button class="constructor-dialog-button constructor-icon-cancel" data-bind="{
                    click: script.close
                }"></button>
            </div>
        </div>
    </div>
    <div class="constructor-dialog-body">
        <div class="constructor-dialog-script">
            <textarea data-bind="{
                bind: script.editor
            }"></textarea>
        </div>
    </div>
</div>