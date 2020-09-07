<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die() ?>
<?php
use intec\core\helpers\JavaScript;

$colors = [
    '#f24841', '#00ad5d', '#00b7f4',
    '#4758a5', '#f655a0', '#7c7c7c',
    '#fa917a', '#c1de97', '#75cbc5',
    '#68cef8', '#6958a4', '#006756',
    '#c83f69', '#b29e8a', '#303f4c'
];
?>
<div data-bind="
    fade: function () { return menu.tabs.list.scheme.isActive() }
 ">
    <div class="constructor-form">
        <div class="constructor-form-wrap">
            <div class="constructor-menu-left-title"><?= GetMessage('scheme.settings') ?></div>
            <div class="constructor-form-group">
                <div class="constructor-form-label"><?= GetMessage('scheme.settings.theme') ?></div>
                <div class="constructor-form-content">
                    <select class="constructor-input" data-bind="{
                        value: scheme.themes.selected,
                        options: scheme.themes,
                        optionsText: 'name',
                        bind: ko.models.select()
                    }"></select>
                </div>
            </div>
            <!-- ko with: scheme.themes.selected -->
                <!-- ko foreach: $root.scheme.properties -->
                    <div class="constructor-property-wrapper">
                        <div class="constructor-form-group">
                            <div class="constructor-form-label constructor-p-b-15">
                                <div style="float: right;">
                                    <input type="checkbox" data-bind="{
                                        bind: ko.models.switch(),
                                        checked: active,
                                        click: function(){
                                            if (!active()) {
                                                $parent.values.get(code(), true).value(null);
                                            }
                                            return true;
                                        }
                                    }" />
                                </div>
                                <span data-bind="text: name"></span>
                            </div>
                            <div class="constructor-form-content" data-bind="{
                                slide: active,
                                with: $parent.values.get(code(), true)
                            }">
                                <div class="constructor-grid constructor-grid-wrap constructor-grid-i-7 grid-a-h-left grid-a-v-center">
                                    <? foreach ($colors as $color) { ?>
                                        <div class="constructor-grid-item-2">
                                            <div class="constructor-colorpicker-button constructor-vertical-middle" style="background: <?= $color ?>" data-bind="{
                                                click: function(){ value('<?= $color ?>') },
                                                css: value() == '<?= $color ?>' ? 'active' : ''
                                            }">
                                                <div class="constructor-aligner"></div>
                                                <i data-bind="{
                                                    visible: value() == '<?= $color ?>'
                                                }" class="constructor-icon-check"></i>
                                            </div>
                                        </div>
                                    <? } ?>
                                    <div class="constructor-grid-item-2">
                                        <div class="constructor-colorpicker-button constructor-vertical-middle" data-bind="{
                                            bind: ko.models.colorpicker({}, value),
                                            style: { backgroundColor: value }
                                        }">
                                            <div class="constructor-aligner"></div>
                                            <i class="far fa-eye-dropper"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <!-- /ko -->
            <!-- /ko -->
        </div>
    </div>
    <!-- ko function: $root.menu.scroll.update --><!-- /ko -->
</div>