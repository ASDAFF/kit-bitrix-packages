<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die() ?>
<?php

use Bitrix\Main\Localization\Loc;
use intec\constructor\structure\block\Element;
use intec\core\helpers\Html;

?>
<div class="constructor-menu constructor-menu-layers" data-bind="{
    slide: function(){ return menu.tabs.list.layers.isActive(); },
    direction: 'left',
    with: menu.tabs.list.layers
}">
    <div class="constructor-menu-wrapper">
        <div class="constructor-menu-header constructor-clearfix">
            <div class="constructor-grid constructor-grid-i-h-4 constructor-grid-a-v-center">
                <div class="constructor-grid-item">
                    <div class="constructor-menu-header-text">
                        <?= Loc::getMessage('constructor.menu.layers') ?>
                    </div>
                </div>
                <div class="colnstructor-grid-item-auto">
                    <div class="constructor-menu-close constructor-icon-cancel" data-bind="{
                        click: function () { close() }
                    }"></div>
                </div>
            </div>
        </div>
        <div class="constructor-menu-content">
            <div class="constructor-menu-content-wrapper nano" data-bind="{
                bind: ko.models.scroll()
            }">
                <div class="constructor-menu-content-wrapper-2 nano-content">
                    <div class="constructor-menu-content-wrapper-3" data-bind="{
                        bind: function (node) {
                            var self = $(node);

                            if (!$context.refresh)
                                $context.display = ko.observable(true);

                            self.sortable({
                                'axis': 'y',
                                'handle': '.constructor-menu-layer-drag',
                                'stop': function (event, ui) {
                                    var item = $(ui.item);
                                    var model = ko.dataFor(item.get(0));

                                    if (item.index() > model.order()) {
                                        model.order(item.index() + 0.5);
                                    } else if (item.index() < model.order()) {
                                        model.order(item.index() - 0.5);
                                    }

                                    $context.display(false);
                                    $context.display(true);
                                }
                            });
                        }
                    }">
                        <!-- ko if: $root.elements().length > 0 -->
                            <!-- ko if: $context.display -->
                                <!-- ko foreach: $root.elements -->
                                    <!-- ko if: type -->
                                        <div class="constructor-menu-layer" data-bind="{
                                            click: function () {
                                                select()
                                            },
                                            css: {
                                                'constructor-menu-layer-active': isSelected()
                                            }
                                        }">
                                            <div class="constructor-grid constructor-grid-i-h-4 constructor-grid-a-v-center">
                                                <div class="constructor-grid-item-auto">
                                                    <div class="constructor-menu-layer-drag">
                                                        <i class="fas fa-ellipsis-v"></i>
                                                    </div>
                                                </div>
                                                <div class="constructor-grid-item-auto">
                                                    <div class="constructor-menu-layer-image">
                                                        <img class="constructor-p-l-15" data-bind="{
                                                            attr: {
                                                                'src': type().icon()
                                                            }
                                                        }" />
                                                    </div>
                                                </div>
                                                <div class="constructor-grid-item">
                                                    <div class="constructor-menu-layer-name" data-bind="{
                                                        text: type().name
                                                    }"></div>
                                                </div>
                                                <div class="constructor-grid-item-auto">
                                                    <div class="constructor-menu-layer-icon constructor-menu-layer-icon-lock" data-bind="{
                                                        click: function () {
                                                            attribute('locked', false)(!attribute('locked', false)());
                                                        },
                                                        clickBubble: false
                                                    }">
                                                        <!-- ko if: attribute('locked', false)() -->
                                                            <i class="far fa-lock-alt"></i>
                                                        <!-- /ko -->
                                                        <!-- ko if: !attribute('locked', false)() -->
                                                            <i class="far fa-unlock-alt"></i>
                                                        <!-- /ko -->
                                                    </div>
                                                </div>
                                                <div class="constructor-grid-item-auto">
                                                    <div class="constructor-menu-layer-icon constructor-menu-layer-icon-visibility" data-bind="{
                                                        click: function () {
                                                            attribute('display')(!attribute('display')());
                                                        },
                                                        clickBubble: false
                                                    }">
                                                        <!-- ko if: attribute('display')() -->
                                                            <i class="far fa-eye"></i>
                                                        <!-- /ko -->
                                                        <!-- ko if: !attribute('display')() -->
                                                            <i class="far fa-eye-slash"></i>
                                                        <!-- /ko -->
                                                    </div>
                                                </div>
                                                <div class="constructor-grid-item-auto">
                                                    <div class="constructor-menu-layer-icon constructor-menu-layer-icon-remove" data-bind="{
                                                        click: function () {
                                                            if(confirm('<?= Loc::getMessage('panel.delete.confirm') ?>')) {
                                                                remove()
                                                            }
                                                        },
                                                        clickBubble: false
                                                    }">
                                                        <i class="far fa-times"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <!-- /ko -->
                                <!-- /ko -->
                            <!-- /ko -->
                        <!-- /ko -->
                        <!-- ko if: $root.elements().length == 0 -->
                            <div class="constructor-menu-message">
                                <?= Loc::getMessage('constructor.menu.layers.messages.empty') ?>
                            </div>
                        <!-- /ko -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>