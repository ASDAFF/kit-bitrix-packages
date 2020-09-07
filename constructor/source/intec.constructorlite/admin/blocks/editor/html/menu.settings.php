<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die() ?>
<?php

use Bitrix\Main\Localization\Loc;
use intec\constructor\structure\block\Element;
use intec\core\helpers\Html;

?>
<div class="constructor-menu constructor-menu-settings" data-bind="{
    slide: function(){ return menu.tabs.list.settings.isActive(); },
    direction: 'right',
    with: menu.tabs.list.settings
}">
    <div class="constructor-menu-wrapper">
        <div class="constructor-menu-header constructor-clearfix">
            <div class="constructor-menu-close constructor-icon-cancel" data-bind="{
                click: function () { close() }
            }"></div>
            <div class="constructor-menu-header-text">
                <?= Loc::getMessage('constructor.menu.settings') ?>
            </div>
        </div>
        <div class="constructor-menu-content">
            <div class="constructor-menu-content-wrapper nano" data-bind="{
                bind: ko.models.scroll()
            }">
                <div class="constructor-menu-content-wrapper-2 nano-content">
                    <!-- ko with: $root.resolutions.selected -->
                        <!-- ko with: $root.elements.selected -->
                            <div class="constructor-menu-content-wrapper-3">
                                <div class="constructor-menu-section">
                                    <div class="constructor-menu-section-title">
                                        <?= Loc::getMessage('constructor.menu.settings.common') ?>
                                    </div>
                                    <div class="constructor-menu-section-fields">
                                        <div class="constructor-menu-field">
                                            <div class="constructor-menu-field-title">
                                                <?= Loc::getMessage('constructor.menu.settings.container') ?>
                                            </div>
                                            <div class="constructor-menu-field-content">
                                                <?= Html::dropDownList(null, null, Element::getContainers(), [
                                                    'class' => 'constructor-input constructor-input-block',
                                                    'data-bind' => '{
                                                        value: attribute(\'container\'),
                                                        bind: ko.models.select()
                                                    }'
                                                ]) ?>
                                            </div>
                                        </div>
                                        <div class="constructor-menu-field">
                                            <div class="constructor-row">
                                                <div class="constructor-column-6">
                                                    <div class="constructor-menu-field-title">
                                                        <?= Loc::getMessage('constructor.menu.settings.x') ?>
                                                    </div>
                                                    <div class="constructor-menu-field-content">
                                                        <div class="constructor-grid constructor-grid-i-h-4 constructor-grid-nowrap">
                                                            <div class="constructor-grid-item">
                                                                <input type="text" class="constructor-input constructor-input-block" data-bind="{
                                                                    value: attribute('x')
                                                                }" />
                                                            </div>
                                                            <div class="constructor-grid-item-6">
                                                                <select class="constructor-input constructor-input-block" data-bind="{
                                                                    options: attribute('x').measures,
                                                                    value: attribute('x').measure,
                                                                    bind: ko.models.select()
                                                                }"></select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="constructor-column-6">
                                                    <div class="constructor-menu-field-title">
                                                        <?= Loc::getMessage('constructor.menu.settings.y') ?>
                                                    </div>
                                                    <div class="constructor-menu-field-content">
                                                        <div class="constructor-grid constructor-grid-i-h-4 constructor-grid-nowrap">
                                                            <div class="constructor-grid-item">
                                                                <input type="text" class="constructor-input constructor-input-block" data-bind="{
                                                                    value: attribute('y')
                                                                }" />
                                                            </div>
                                                            <div class="constructor-grid-item-6">
                                                                <select class="constructor-input constructor-input-block" data-bind="{
                                                                    options: attribute('y').measures,
                                                                    value: attribute('y').measure,
                                                                    bind: ko.models.select()
                                                                }"></select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="constructor-menu-field">
                                            <div class="constructor-row">
                                                <div class="constructor-column-6">
                                                    <div class="constructor-form-field">
                                                        <div class="constructor-menu-field-title">
                                                            <?= Loc::getMessage('constructor.menu.settings.width') ?>
                                                        </div>
                                                        <div class="constructor-menu-field-content">
                                                            <div class="constructor-grid constructor-grid-i-h-4 constructor-grid-nowrap">
                                                                <div class="constructor-grid-item">
                                                                    <input type="text" class="constructor-input constructor-input-block" data-bind="{
                                                                        value: attribute('width')
                                                                    }" />
                                                                </div>
                                                                <div class="constructor-grid-item-6">
                                                                    <select class="constructor-input constructor-input-block" data-bind="{
                                                                        options: attribute('width').measures,
                                                                        value: attribute('width').measure,
                                                                        bind: ko.models.select()
                                                                    }"></select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="constructor-column-6">
                                                    <div class="constructor-form-field">
                                                        <div class="constructor-menu-field-title">
                                                            <?= Loc::getMessage('constructor.menu.settings.height') ?>
                                                        </div>
                                                        <div class="constructor-menu-field-content">
                                                            <div class="constructor-grid constructor-grid-i-h-4 constructor-grid-nowrap">
                                                                <div class="constructor-grid-item">
                                                                    <input type="text" class="constructor-input constructor-input-block" data-bind="{
                                                                        value: attribute('height')
                                                                    }" />
                                                                </div>
                                                                <div class="constructor-grid-item-6">
                                                                    <select class="constructor-input constructor-input-block" data-bind="{
                                                                        options: attribute('height').measures,
                                                                        value: attribute('height').measure,
                                                                        bind: ko.models.select()
                                                                    }"></select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="constructor-menu-field">
                                            <div class="constructor-row">
                                                <div class="constructor-column-6">
                                                    <div class="constructor-menu-field-title">
                                                        <?= Loc::getMessage('constructor.menu.settings.xAxis') ?>
                                                    </div>
                                                    <div class="constructor-menu-field-content">
                                                        <?= Html::dropDownList(null, null, Element::getXAxises(), [
                                                            'class' => 'constructor-input constructor-input-block',
                                                            'data-bind' => '{
                                                                value: attribute(\'xAxis\'),
                                                                bind: ko.models.select()
                                                            }'
                                                        ]) ?>
                                                    </div>
                                                </div>
                                                <div class="constructor-column-6">
                                                    <div class="constructor-menu-field-title">
                                                        <?= Loc::getMessage('constructor.menu.settings.yAxis') ?>
                                                    </div>
                                                    <div class="constructor-menu-field-content">
                                                        <?= Html::dropDownList(null, null, Element::getYAxises(), [
                                                            'class' => 'constructor-input constructor-input-block',
                                                            'data-bind' => '{
                                                                value: attribute(\'yAxis\'),
                                                                bind: ko.models.select()
                                                            }'
                                                        ]) ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="constructor-menu-section constructor-p-t-20">
                                    <div class="constructor-menu-section-title">
                                        <span class="constructor-p-r-10"><?= Loc::getMessage('constructor.menu.settings.indents') ?></span>
                                        <input type="checkbox" data-bind="{
                                            bind: ko.models.switch(),
                                            checked: attribute('indents')
                                        }">
                                    </div>
                                    <div class="constructor-menu-section-fields">
                                        <!-- ko if: attribute('indents') -->
                                            <div class="constructor-menu-field">
                                                <div class="constructor-row">
                                                    <div class="constructor-column-6">
                                                        <div class="constructor-menu-field-title">
                                                            <?= Loc::getMessage('constructor.menu.settings.indents.top') ?>
                                                        </div>
                                                        <div class="constructor-menu-field-content">
                                                            <div class="constructor-grid constructor-grid-i-h-4 constructor-grid-nowrap">
                                                                <div class="constructor-grid-item">
                                                                    <input type="text" class="constructor-input constructor-input-block" data-bind="{
                                                                        value: attribute('indentTop')
                                                                    }" />
                                                                </div>
                                                                <div class="constructor-grid-item-6">
                                                                    <select class="constructor-input constructor-input-block" data-bind="{
                                                                        options: attribute('indentTop').measures,
                                                                        value: attribute('indentTop').measure,
                                                                        bind: ko.models.select()
                                                                    }"></select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="constructor-column-6">
                                                        <div class="constructor-menu-field-title">
                                                            <?= Loc::getMessage('constructor.menu.settings.indents.bottom') ?>
                                                        </div>
                                                        <div class="constructor-menu-field-content">
                                                            <div class="constructor-grid constructor-grid-i-h-4 constructor-grid-nowrap">
                                                                <div class="constructor-grid-item">
                                                                    <input type="text" class="constructor-input constructor-input-block" data-bind="{
                                                                        value: attribute('indentBottom')
                                                                    }" />
                                                                </div>
                                                                <div class="constructor-grid-item-6">
                                                                    <select class="constructor-input constructor-input-block" data-bind="{
                                                                        options: attribute('indentBottom').measures,
                                                                        value: attribute('indentBottom').measure,
                                                                        bind: ko.models.select()
                                                                    }"></select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="constructor-menu-field">
                                                <div class="constructor-row">
                                                    <div class="constructor-column-6">
                                                        <div class="constructor-menu-field-title">
                                                            <?= Loc::getMessage('constructor.menu.settings.indents.left') ?>
                                                        </div>
                                                        <div class="constructor-menu-field-content">
                                                            <div class="constructor-grid constructor-grid-i-h-4 constructor-grid-nowrap">
                                                                <div class="constructor-grid-item">
                                                                    <input type="text" class="constructor-input constructor-input-block" data-bind="{
                                                                        value: attribute('indentLeft')
                                                                    }" />
                                                                </div>
                                                                <div class="constructor-grid-item-6">
                                                                    <select class="constructor-input constructor-input-block" data-bind="{
                                                                        options: attribute('indentLeft').measures,
                                                                        value: attribute('indentLeft').measure,
                                                                        bind: ko.models.select()
                                                                    }"></select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="constructor-column-6">
                                                        <div class="constructor-menu-field-title">
                                                            <?= Loc::getMessage('constructor.menu.settings.indents.right') ?>
                                                        </div>
                                                        <div class="constructor-menu-field-content">
                                                            <div class="constructor-grid constructor-grid-i-h-4 constructor-grid-nowrap">
                                                                <div class="constructor-grid-item">
                                                                    <input type="text" class="constructor-input constructor-input-block" data-bind="{
                                                                        value: attribute('indentRight')
                                                                    }" />
                                                                </div>
                                                                <div class="constructor-grid-item-6">
                                                                    <select class="constructor-input constructor-input-block" data-bind="{
                                                                        options: attribute('indentRight').measures,
                                                                        value: attribute('indentRight').measure,
                                                                        bind: ko.models.select()
                                                                    }"></select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        <!-- /ko -->
                                    </div>
                                </div>
                                <!-- ko if: type() -->
                                    <!-- ko if: type().settings.view() -->
                                        <div class="constructor-m-t-20">
                                            <div class="constructor-menu-delimiter"></div>
                                            <div class="constructor-m-t-20" data-bind="{
                                                htmlTemplate: type() ? type().settings.view() : null
                                            }"></div>
                                        </div>
                                    <!-- /ko -->
                                <!-- /ko -->
                            </div>
                        <!-- /ko -->
                    <!-- /ko -->
                </div>
            </div>
        </div>
    </div>
</div>