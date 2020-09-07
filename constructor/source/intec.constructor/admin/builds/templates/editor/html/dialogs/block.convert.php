<?php

use Bitrix\Main\Localization\Loc;

?>
<div class="constructor-dialog constructor-dialog-block-convert" data-bind="{
    bind: dialogs.list.blockConvert,
    with: dialogs.list
}">
    <div class="constructor-dialog-header">
        <div class="constructor-dialog-header-wrapper">
            <div class="constructor-dialog-title">
                <?= Loc::getMessage('container.modals.block.convert.title') ?>
            </div>
            <div class="constructor-dialog-content"></div>
            <div class="constructor-dialog-buttons">
                <button class="constructor-dialog-button constructor-icon-cancel" data-bind="{
                    click: blockConvert.close
                }"></button>
            </div>
        </div>
    </div>
    <div class="constructor-dialog-body" data-bind="{
        with: blockConvert.data
    }">
        <div class="constructor-dialog-loader" data-bind="{
            visible: processing
        }">
            <div class="constructor-loader constructor-loader-1"></div>
        </div>
        <!-- ko if: !processing() -->
            <!-- ko if: error -->
                <div class="constructor-dialog-error" data-bind="{
                    text: error
                }"></div>
            <!-- /ko -->
            <div class="constructor-p-20">
                <div class="constructor-dialog-field constructor-grid constructor-grid-a-v-center constructor-grid-i-h-6 constructor-p-b-10">
                    <div class="constructor-dialog-field-label constructor-grid-item-4">
                        <?= Loc::getMessage('container.modals.block.convert.fields.code') ?>
                    </div>
                    <div class="constructor-dialog-field-content constructor-grid-item-8">
                        <input class="constructor-input constructor-input-block constructor-input_light" type="text" data-bind="{
                            value: code
                        }" />
                    </div>
                </div>
                <div class="constructor-dialog-field constructor-grid constructor-grid-a-v-center constructor-grid-i-h-6">
                    <div class="constructor-dialog-field-label constructor-grid-item-4" style="text-align: right">
                        <?= Loc::getMessage('container.modals.block.convert.fields.name') ?>
                    </div>
                    <div class="constructor-dialog-field-content constructor-grid-item-8">
                        <input class="constructor-input constructor-input-block constructor-input_light" type="text" data-bind="{
                            value: name
                        }" />
                    </div>
                </div>
            </div>
        <!-- /ko -->
    </div>
    <div class="constructor-dialog-footer">
        <div class="constructor-dialog-footer-wrapper">
            <div class="constructor-button constructor-button-s-3 constructor-button-c-blue constructor-save-button" data-bind="{
                click: blockConvert.save
            }"><?= Loc::getMessage('container.modals.block.convert.buttons.save') ?></div>
            <div class="constructor-button constructor-button-s-3 constructor-button-c-blue-t-c" data-bind="{
                click: blockConvert.close
            }"><?= Loc::getMessage('container.modals.block.convert.buttons.cancel') ?></div>
        </div>
    </div>
</div>