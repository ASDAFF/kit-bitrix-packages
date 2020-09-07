<?php

use elements\intec\constructor\shape\Element;

/**
 * @var Element $this
 */

$language = $this->getLanguage();

?>
<div class="constructor-menu-section constructor-m-b-20">
    <div class="constructor-menu-section-fields">
        <div class="constructor-menu-field">
            <div class="constructor-menu-field-title">
                <?= $language->getMessage('settings.type') ?>
            </div>
            <div class="constructor-menu-field-content">
                <select class="constructor-input constructor-input-block" data-bind="{
                    value: properties.type,
                    bind: ko.models.select()
                }">
                    <option value="rectangle">
                        <?= $language->getMessage('settings.type.rectangle') ?>
                    </option>
                    <option value="circle">
                        <?= $language->getMessage('settings.type.circle') ?>
                    </option>
                </select>
            </div>
        </div>
        <div class="constructor-menu-field">
            <div class="constructor-menu-field-title">
                <?= $language->getMessage('settings.color') ?>
            </div>
            <div class="constructor-menu-field-content">
                <div class="constructor-grid constructor-grid-i-v-4 constructor-grid-nowrap">
                    <div class="constructor-grid-item-auto">
                        <div class="constructor-colorpicker-button constructor-vertical-middle" data-bind="{
                            bind: ko.models.colorpicker({}, properties.color),
                            style: {
                                backgroundColor: properties.color
                            }
                        }">
                            <div class="constructor-aligner"></div>
                            <i class="far fa-eye-dropper"></i>
                        </div>
                    </div>
                    <div class="constructor-grid-item">
                        <input type="text" class="constructor-input constructor-input-block" data-bind="{
                            value: properties.color
                        }" />
                    </div>
                </div>
            </div>
        </div>
        <div class="constructor-menu-field">
            <div class="constructor-menu-field-title">
                <?= $language->getMessage('settings.image') ?>
            </div>
            <div class="constructor-menu-field-content">
                <div class="constructor-p-b-10">
                    <input class="constructor-input constructor-input-block" type="text" data-bind="{
                        value: properties.image
                    }" />
                </div>
                <div class="constructor-button constructor-button-block constructor-button-s-2 constructor-button-c-blue" data-bind="{
                    click: function () {
                        $root.dialogs.gallery.open(function (image) {
                            properties.image(image.value);
                            return true;
                        });
                    }
                }"><?= $language->getMessage('settings.image.select') ?></div>
                <div class="constructor-grid constructor-grid-i-4 constructor-grid-a-v-center constructor-p-t-20">
                    <div class="constructor-grid-item-auto">
                        <input type="checkbox" data-bind="{
                            bind: ko.models.switch(),
                            checked: properties.image.contain
                        }" />
                    </div>
                    <div class="constructor-grid-item constructor-menu-text">
                        <?= $language->getMessage('settings.image.contain') ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="constructor-menu-delimiter"></div>
<div class="constructor-menu-section constructor-m-t-20">
    <div class="constructor-menu-section-title">
        <span class="constructor-m-r-10">
            <?= $language->getMessage('settings.sections.border') ?>
        </span>
        <input type="checkbox" data-bind="{
            checked: properties.border,
            bind: ko.models.switch()
        }" />
    </div>
    <div class="constructor-menu-section-fields">
        <div class="constructor-menu-field">
            <div class="constructor-row">
                <div class="constructor-column-6">
                    <div class="constructor-menu-field-title">
                        <?= $language->getMessage('settings.border.width') ?>
                    </div>
                    <div class="constructor-menu-field-content">
                        <div class="constructor-grid constructor-grid-i-h-4 constructor-grid-nowrap">
                            <div class="constructor-grid-item">
                                <input type="text" class="constructor-input constructor-input-block" data-bind="{
                                    value: properties.border.width
                                }" />
                            </div>
                            <div class="constructor-grid-item-6">
                                <select class="constructor-input constructor-input-block" data-bind="{
                                    options: properties.border.width.measures,
                                    value: properties.border.width.measure,
                                    bind: ko.models.select()
                                }"></select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="constructor-column-6">
                    <div class="constructor-menu-field-title">
                        <?= $language->getMessage('settings.border.style') ?>
                    </div>
                    <div class="constructor-menu-field-content">
                        <select class="constructor-input constructor-input-block" data-bind="{
                            value: properties.border.style,
                            bind: ko.models.select()
                        }">
                            <option value="solid">
                                <?= $language->getMessage('settings.border.style.solid') ?>
                            </option>
                            <option value="dotted">
                                <?= $language->getMessage('settings.border.style.dotted') ?>
                            </option>
                            <option value="dashed">
                                <?= $language->getMessage('settings.border.style.dashed') ?>
                            </option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="constructor-menu-field">
            <div class="constructor-row">
                <div class="constructor-column-6">
                    <div class="constructor-menu-field-title">
                        <?= $language->getMessage('settings.color') ?>
                    </div>
                    <div class="constructor-menu-field-content">
                        <div class="constructor-grid constructor-grid-i-v-4 constructor-grid-nowrap">
                            <div class="constructor-grid-item-auto">
                                <div class="constructor-colorpicker-button constructor-vertical-middle" data-bind="{
                                    bind: ko.models.colorpicker({}, properties.border.color),
                                    style: {
                                        backgroundColor: properties.border.color
                                    }
                                }">
                                    <div class="constructor-aligner"></div>
                                    <i class="far fa-eye-dropper"></i>
                                </div>
                            </div>
                            <div class="constructor-grid-item">
                                <input type="text" class="constructor-input constructor-input-block" data-bind="{
                                    value: properties.border.color
                                }" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- ko if: properties.type() === 'rectangle' -->
            <div class="constructor-menu-field">
                <div class="constructor-menu-field-title">
                    <div class="constructor-grid constructor-grid-i-h-4">
                        <div class="constructor-grid-item">
                            <?= $language->getMessage('settings.border.radius') ?>
                        </div>
                        <div class="constructor-grid-item-auto">
                            <span data-bind="{
                                text: properties.border.radius() + 'px'
                            }"></span>
                        </div>
                    </div>
                </div>
                <div class="constructor-menu-field-content">
                    <div data-bind="{
                        bind: ko.models.slider({
                            'min': 0,
                            'max': 50,
                            'step': 1
                        }, properties.border.radius)
                    }"></div>
                </div>
            </div>
        <!-- /ko -->
    </div>
</div>