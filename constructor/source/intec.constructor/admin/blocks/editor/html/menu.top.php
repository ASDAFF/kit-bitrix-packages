<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;

/**
 * @var array $arUrlTemplates
 */

?>
<div class="constructor-menu constructor-menu-top no-select">
    <div class="constructor-menu-wrapper constructor-pull-left constructor-menu-back" data-bind="{
        css: {
            'constructor-menu-back-active': elements.selected
        },
        click: function () {
            elements.selected(null);
        }
    }">
        <div class="constructor-menu-wrapper constructor-grid constructor-grid-a-h-center constructor-grid-a-v-center">
            <div class="constructor-grid-item-auto" style="line-height: 1;">
                <i class="typcn typcn-arrow-left" style="line-height: 1;"></i>
            </div>
        </div>
    </div>
    <div class="constructor-menu-wrapper constructor-pull-left constructor-grid constructor-grid-a-v-center constructor-grid-i-h-4 constructor-p-l-20">
        <div class="constructor-grid-item-auto">
            <div class="constructor-button constructor-button-s-3 constructor-button-c-blue" data-bind="{
                click: function() { dropdown.toggle(dropdown.list.widgets); }
            }">+ <?= Loc::getMessage('button.element.add') ?></div>
        </div>
        <div class="constructor-grid-item-auto">
            <div class="constructor-button constructor-button-s-3 constructor-button-c-blue-t" data-bind="{
                click: function() { menu.tabs.list.settings.toggle(); },
                css: {
                    'ui-state-hover': menu.tabs.list.settings.active()
                }
            }"><?= Loc::getMessage('button.settings') ?></div>
        </div>
        <div class="constructor-grid-item-auto">
            <div class="constructor-button constructor-button-s-3 constructor-button-c-blue-t" data-bind="{
                click: function(){ menu.tabs.list.layers.toggle(); },
                css: {
                    'ui-state-hover': menu.tabs.list.layers.active()
                }
            }"><?= Loc::getMessage('button.layers') ?></div>
        </div>
        <div class="constructor-grid-item-auto constructor-m-l-25">
            <div class="constructor-button constructor-button-s-3 constructor-button-c-blue-t" data-bind="{
                click: function () { dropdown.toggle(dropdown.list.resolutions); }
            }">
                <i class="constructor-book-icon" data-bind="{
                    css: { 'constructor-book-icon-open': dropdown.active() === dropdown.list.resolutions }
                }"></i>
            </div>
            <!-- ko with: resolutions.selected -->
                <div style="display: inline-block; vertical-align: middle; margin-left: 5px;" data-bind="text: value"></div>
            <!-- /ko -->
        </div>
    </div>

    <div class="constructor-menu-wrapper constructor-pull-right constructor-grid constructor-grid-a-v-center constructor-grid-i-h-4 constructor-p-r-20">
        <div class="constructor-grid-item-auto">
            <a href="<?= $arUrlTemplates['blocks.templates'] ?>" class="constructor-button constructor-button-s-3 constructor-button-c-blue-t">
                <?= Loc::getMessage('button.exit') ?>
            </a>
        </div>
        <div class="constructor-grid-item-auto">
            <div class="constructor-button constructor-button-s-3 constructor-button-c-blue constructor-save-button" data-bind="{
                click: save
            }">
                <!-- ko if: !saving() -->
                    <?= Loc::getMessage('button.save') ?>
                <!-- /ko -->
                <!-- ko if: saving() -->
                    <?= Loc::getMessage('button.saving') ?>
                <!-- /ko -->
            </div>
        </div>
    </div>
    <div class="constructor-menu-wrapper constructor-pull-right constructor-grid constructor-grid-a-v-center constructor-grid-i-h-10 constructor-p-r-20">
        <div class="constructor-grid-item-auto">
            <label>
                <span class="constructor-m-r-10" style="font-size: 14px;">
                    <?= Loc::getMessage('net.on') ?>
                </span>
                <input type="checkbox" data-bind="{
                    bind: ko.models.switch(),
                    checked: guides.columns.active
                }" />
            </label>
        </div>
        <div class="constructor-grid-item-auto">
            <label>
                <span class="constructor-m-r-10" style="font-size: 14px;">
                    <?= Loc::getMessage('constructor.menu.top.selectedElementOnTop') ?>
                </span>
                <input type="checkbox" data-bind="{
                    bind: ko.models.switch(),
                    checked: elements.selected.isOnTop
                }" />
            </label>
        </div>
    </div>
</div>
