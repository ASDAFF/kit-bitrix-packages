<?php

use elements\intec\constructor\image\Element;

/**
 * @var Element $this
 */

$language = $this->getLanguage();

?>
<div class="constructor-menu-section constructor-m-b-20">
    <div class="constructor-menu-section-fields">
        <div class="constructor-menu-field">
            <div class="constructor-menu-field-title">
                <?= $language->getMessage('settings.source') ?>
            </div>
            <div class="constructor-menu-field-content">
                <div class="constructor-p-b-10">
                    <input class="constructor-input constructor-input-block" type="text" data-bind="{
                        value: properties.source
                    }" />
                </div>
                <div class="constructor-button constructor-button-block constructor-button-s-2 constructor-button-c-blue" data-bind="{
                    click: function () {
                        $root.dialogs.gallery.open(function (image) {
                            properties.source(image.value);
                            return true;
                        });
                    }
                }"><?= $language->getMessage('settings.source.select') ?></div>
            </div>
        </div>
        <div class="constructor-menu-field">
            <div class="constructor-menu-field-title">
                <?= $language->getMessage('settings.repeat') ?>
            </div>
            <div class="constructor-menu-field-content">
                <select class="constructor-input constructor-input-block" data-bind="{
                    value: properties.repeat,
                    bind: ko.models.select()
                }">
                    <option value="no-repeat"><?= $language->getMessage('settings.repeat.none') ?></option>
                    <option value="repeat-x"><?= $language->getMessage('settings.repeat.x') ?></option>
                    <option value="repeat-y"><?= $language->getMessage('settings.repeat.y') ?></option>
                    <option value="repeat"><?= $language->getMessage('settings.repeat.all') ?></option>
                </select>
            </div>
        </div>
    </div>
</div>
<div class="constructor-menu-section constructor-m-b-20">
    <div class="constructor-menu-section-title">
        <?= $language->getMessage('settings.sections.position') ?>
    </div>
    <div class="constructor-menu-section-fields">
        <div class="constructor-menu-field">
            <div class="constructor-row">
                <div class="constructor-column-6">
                    <div class="constructor-menu-field-title">
                        <?= $language->getMessage('settings.position.x') ?>
                    </div>
                    <div class="constructor-menu-field-content">
                        <div class="constructor-grid constructor-grid-i-h-4 constructor-grid-nowrap">
                            <div class="constructor-grid-item">
                                <input type="text" class="constructor-input constructor-input-block" data-bind="{
                                    value: properties.position.x
                                }" />
                            </div>
                            <div class="constructor-grid-item-6">
                                <select class="constructor-input constructor-input-block" data-bind="{
                                    options: properties.position.x.measures,
                                    value: properties.position.x.measure,
                                    bind: ko.models.select()
                                }"></select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="constructor-column-6">
                    <div class="constructor-menu-field-title">
                        <?= $language->getMessage('settings.position.y') ?>
                    </div>
                    <div class="constructor-menu-field-content">
                        <div class="constructor-grid constructor-grid-i-h-4 constructor-grid-nowrap">
                            <div class="constructor-grid-item">
                                <input type="text" class="constructor-input constructor-input-block" data-bind="{
                                    value: properties.position.y
                                }" />
                            </div>
                            <div class="constructor-grid-item-6">
                                <select class="constructor-input constructor-input-block" data-bind="{
                                    options: properties.position.y.measures,
                                    value: properties.position.y.measure,
                                    bind: ko.models.select()
                                }"></select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="constructor-menu-section constructor-m-b-20">
    <div class="constructor-menu-section-title">
        <?= $language->getMessage('settings.sections.size') ?>
    </div>
    <div class="constructor-menu-section-fields">
        <div class="constructor-menu-field">
            <div class="constructor-menu-field-title">
                <?= $language->getMessage('settings.size') ?>
            </div>
            <div class="constructor-menu-field-content">
                <select class="constructor-input constructor-input-block" data-bind="{
                    value: properties.size,
                    bind: ko.models.select()
                }">
                    <option value="auto"><?= $language->getMessage('settings.size.auto') ?></option>
                    <option value="cover"><?= $language->getMessage('settings.size.cover') ?></option>
                    <option value="contain"><?= $language->getMessage('settings.size.contain') ?></option>
                    <option value="values"><?= $language->getMessage('settings.size.values') ?></option>
                </select>
            </div>
        </div>
        <!-- ko if: properties.size() == 'values' -->
            <div class="constructor-menu-field">
                <div class="constructor-row">
                    <div class="constructor-column-6">
                        <div class="constructor-menu-field-title">
                            <?= $language->getMessage('settings.size.x') ?>
                        </div>
                        <div class="constructor-menu-field-content">
                            <div class="constructor-grid constructor-grid-i-h-4 constructor-grid-nowrap">
                                <div class="constructor-grid-item">
                                    <input type="text" class="constructor-input constructor-input-block" data-bind="{
                                        value: properties.size.x
                                    }" />
                                </div>
                                <div class="constructor-grid-item-6">
                                    <select class="constructor-input constructor-input-block" data-bind="{
                                        options: properties.size.x.measures,
                                        value: properties.size.x.measure,
                                        bind: ko.models.select()
                                    }"></select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="constructor-column-6">
                        <div class="constructor-menu-field-title">
                            <?= $language->getMessage('settings.size.y') ?>
                        </div>
                        <div class="constructor-menu-field-content">
                            <div class="constructor-grid constructor-grid-i-h-4 constructor-grid-nowrap">
                                <div class="constructor-grid-item">
                                    <input type="text" class="constructor-input constructor-input-block" data-bind="{
                                        value: properties.size.y
                                    }" />
                                </div>
                                <div class="constructor-grid-item-6">
                                    <select class="constructor-input constructor-input-block" data-bind="{
                                        options: properties.size.y.measures,
                                        value: properties.size.y.measure,
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
        <!-- ko if: properties.border -->
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
        <!-- /ko -->
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
    </div>
</div>