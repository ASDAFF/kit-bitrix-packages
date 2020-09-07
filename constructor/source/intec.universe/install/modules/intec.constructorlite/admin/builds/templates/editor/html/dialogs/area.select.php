<?php

use Bitrix\Main\Localization\Loc;
use intec\core\helpers\JavaScript;

?>
<div class="constructor-dialog constructor-dialog-area-select" data-bind="{
    bind: dialogs.list.areaSelect,
    with: dialogs.list
}">
    <div class="constructor-dialog-header">
        <div class="constructor-dialog-header-wrapper">
            <div class="constructor-dialog-title">
                <?= Loc::getMessage('container.modals.area.select.title') ?>
            </div>
            <div class="constructor-dialog-content"></div>
            <div class="constructor-dialog-buttons">
                <button class="constructor-dialog-button constructor-icon-cancel" data-bind="{
                    click: areaSelect.close
                }"></button>
            </div>
        </div>
    </div>
    <div class="constructor-dialog-body" data-bind="{
        with: areaSelect.data
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
                        <?= Loc::getMessage('container.modals.area.select.fields.area') ?>
                    </div>
                    <div class="constructor-dialog-field-content constructor-grid-item-8">
                        <select class="constructor-input constructor-input-block constructor-input_light" data-bind="{
                            value: area,
                            options: areas,
                            optionsText: 'name',
                            optionsCaption: <?= JavaScript::toObject(Loc::getMessage('container.modals.area.select.fields.area.caption')) ?>
                        }"></select>
                    </div>
                </div>
            </div>
            <div class="constructor-dialog-info">
                <?= Loc::getMessage('container.modals.area.select.description') ?>
            </div>
        <!-- /ko -->
    </div>
    <div class="constructor-dialog-footer">
        <div class="constructor-dialog-footer-wrapper">
            <!-- ko if: areaSelect.data.area -->
                <div class="constructor-button constructor-button-s-3 constructor-button-c-blue constructor-save-button" data-bind="{
                    click: areaSelect.select
                }"><?= Loc::getMessage('container.modals.area.select.buttons.select') ?></div>
            <!-- /ko -->
            <div class="constructor-button constructor-button-s-3 constructor-button-c-blue-t-c" data-bind="{
                click: areaSelect.close
            }"><?= Loc::getMessage('container.modals.area.select.buttons.cancel') ?></div>
        </div>
    </div>
</div>