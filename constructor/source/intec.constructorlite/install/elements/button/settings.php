<?php

use intec\constructor\models\Font;
use elements\intec\constructor\button\Element;
use intec\core\helpers\ArrayHelper;
use intec\core\helpers\Html;

/**
 * @var Element $this
 */

$language = $this->getLanguage();
$fonts = Font::findAvailable();
$fonts = $fonts->asArray(function ($key, $font) {
    /** @var Font $font */

    return [
        'key' => $font->code,
        'value' => $font->name
    ];
});

?>
<div class="constructor-menu-section constructor-m-b-20">
    <div class="constructor-menu-section-fields">
        <div class="constructor-menu-field">
            <div class="constructor-menu-field-title">
                <?= $language->getMessage('settings.link') ?>
            </div>
            <div class="constructor-menu-field-content">
                <input type="text" class="constructor-input constructor-input-block" data-bind="{
                    value: properties.link
                }" />
            </div>
        </div>
        <div class="constructor-menu-field">
            <div class="constructor-menu-field-title">
                <?= $language->getMessage('settings.link.target') ?>
            </div>
            <div class="constructor-menu-field-content">
                <select class="constructor-input constructor-input-block" data-bind="{
                    value: properties.link.target,
                    bind: ko.models.select()
                }">
                    <option value="_self">
                        <?= $language->getMessage('settings.link.target.self') ?>
                    </option>
                    <option value="_blank">
                        <?= $language->getMessage('settings.link.target.blank') ?>
                    </option>
                    <option value="_parent">
                        <?= $language->getMessage('settings.link.target.parent') ?>
                    </option>
                    <option value="_top">
                        <?= $language->getMessage('settings.link.target.top') ?>
                    </option>
                </select>
            </div>
        </div>
    </div>
</div>
<div class="constructor-menu-delimiter"></div>
<div class="constructor-menu-section constructor-m-t-20 constructor-m-b-20">
    <div class="constructor-menu-section-title">
        <?= $language->getMessage('settings.sections.text') ?>
    </div>
    <div class="constructor-menu-section-fields">
        <div class="constructor-menu-field">
            <div class="constructor-menu-field-title">
                <?= $language->getMessage('settings.text') ?>
            </div>
            <div class="constructor-menu-field-content">
                <input type="text" class="constructor-input constructor-input-block" data-bind="{
                    value: properties.text
                }" />
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
                                    bind: ko.models.colorpicker({}, properties.text.color.default),
                                    style: {
                                        backgroundColor: properties.text.color.default
                                    }
                                }">
                                    <div class="constructor-aligner"></div>
                                    <i class="far fa-eye-dropper"></i>
                                </div>
                            </div>
                            <div class="constructor-grid-item">
                                <input type="text" class="constructor-input constructor-input-block" data-bind="{
                                    value: properties.text.color.default
                                }" />
                            </div>
                        </div>
                    </div>
                </div>
                <div class="constructor-column-6">
                    <div class="constructor-menu-field-title">
                        <?= $language->getMessage('settings.color.hover') ?>
                    </div>
                    <div class="constructor-menu-field-content">
                        <div class="constructor-grid constructor-grid-i-v-4 constructor-grid-nowrap">
                            <div class="constructor-grid-item-auto">
                                <div class="constructor-colorpicker-button constructor-vertical-middle" data-bind="{
                                    bind: ko.models.colorpicker({}, properties.text.color.hover),
                                    style: {
                                        backgroundColor: properties.text.color.hover
                                    }
                                }">
                                    <div class="constructor-aligner"></div>
                                    <i class="far fa-eye-dropper"></i>
                                </div>
                            </div>
                            <div class="constructor-grid-item">
                                <input type="text" class="constructor-input constructor-input-block" data-bind="{
                                    value: properties.text.color.hover
                                }" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="constructor-menu-field">
            <div class="constructor-menu-field-title">
                <?= $language->getMessage('settings.text.style') ?>
            </div>
            <select class="constructor-input constructor-input-block" data-bind="{
                value: properties.text.style,
                bind: ko.models.select()
            }">
                <option value="none">
                    <?= $language->getMessage('settings.text.style.none') ?>
                </option>
                <option value="bold">
                    <?= $language->getMessage('settings.text.style.bold') ?>
                </option>
                <option value="italic">
                    <?= $language->getMessage('settings.text.style.italic') ?>
                </option>
            </select>
        </div>
        <div class="constructor-menu-field">
            <div class="constructor-row">
                <div class="constructor-column-6">
                    <div class="constructor-menu-field-title">
                        <?= $language->getMessage('settings.text.font') ?>
                    </div>
                    <div class="constructor-menu-field-content">
                        <?= Html::dropDownList('', null, ArrayHelper::merge([
                            '' => $language->getMessage('settings.text.font.default')
                        ], $fonts), [
                            'class' => 'constructor-input constructor-input-block',
                            'data' => [
                                'bind' => '{
                                    value: properties.text.font,
                                    bind: ko.models.select()
                                }'
                            ]
                        ]) ?>
                    </div>
                </div>
                <div class="constructor-column-6">
                    <div class="constructor-menu-field-title">
                        <?= $language->getMessage('settings.text.size') ?>
                    </div>
                    <div class="constructor-menu-field-content">
                        <div class="constructor-grid constructor-grid-i-h-4 constructor-grid-nowrap">
                            <div class="constructor-grid-item">
                                <input type="text" class="constructor-input constructor-input-block" data-bind="{
                                    value: properties.text.size
                                }" />
                            </div>
                            <div class="constructor-grid-item-6">
                                <select class="constructor-input constructor-input-block" data-bind="{
                                    options: properties.text.size.measures,
                                    value: properties.text.size.measure,
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
                        <?= $language->getMessage('settings.text.lineHeight') ?>
                    </div>
                    <div class="constructor-menu-field-content">
                        <div class="constructor-grid constructor-grid-i-h-4 constructor-grid-nowrap">
                            <div class="constructor-grid-item">
                                <input type="text" class="constructor-input constructor-input-block" data-bind="{
                                    value: properties.text.lineHeight
                                }" />
                            </div>
                            <div class="constructor-grid-item-6">
                                <select class="constructor-input constructor-input-block" data-bind="{
                                    options: properties.text.lineHeight.measures,
                                    value: properties.text.lineHeight.measure,
                                    bind: ko.models.select()
                                }"></select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="constructor-column-6">
                    <div class="constructor-menu-field-title">
                        <?= $language->getMessage('settings.text.letterSpacing') ?>
                    </div>
                    <div class="constructor-menu-field-content">
                        <div class="constructor-grid constructor-grid-i-h-4 constructor-grid-nowrap">
                            <div class="constructor-grid-item">
                                <input type="text" class="constructor-input constructor-input-block" data-bind="{
                                    value: properties.text.letterSpacing
                                }" />
                            </div>
                            <div class="constructor-grid-item-6">
                                <select class="constructor-input constructor-input-block" data-bind="{
                                    options: properties.text.letterSpacing.measures,
                                    value: properties.text.letterSpacing.measure,
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
<div class="constructor-menu-delimiter"></div>
<div class="constructor-menu-section constructor-m-t-20">
    <div class="constructor-menu-section-title">
        <?= $language->getMessage('settings.sections.background') ?>
    </div>
    <div class="constructor-menu-section-fields">
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
                                    bind: ko.models.colorpicker({}, properties.background.color.default),
                                    style: {
                                        backgroundColor: properties.background.color.default
                                    }
                                }">
                                    <div class="constructor-aligner"></div>
                                    <i class="far fa-eye-dropper"></i>
                                </div>
                            </div>
                            <div class="constructor-grid-item">
                                <input type="text" class="constructor-input constructor-input-block" data-bind="{
                                    value: properties.background.color.default
                                }" />
                            </div>
                        </div>
                    </div>
                </div>
                <div class="constructor-column-6">
                    <div class="constructor-menu-field-title">
                        <?= $language->getMessage('settings.color.hover') ?>
                    </div>
                    <div class="constructor-menu-field-content">
                        <div class="constructor-grid constructor-grid-i-v-4 constructor-grid-nowrap">
                            <div class="constructor-grid-item-auto">
                                <div class="constructor-colorpicker-button constructor-vertical-middle" data-bind="{
                                    bind: ko.models.colorpicker({}, properties.background.color.hover),
                                    style: {
                                        backgroundColor: properties.background.color.hover
                                    }
                                }">
                                    <div class="constructor-aligner"></div>
                                    <i class="far fa-eye-dropper"></i>
                                </div>
                            </div>
                            <div class="constructor-grid-item">
                                <input type="text" class="constructor-input constructor-input-block" data-bind="{
                                    value: properties.background.color.hover
                                }" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="constructor-menu-field">
            <div class="constructor-menu-field-title">
                <div class="constructor-grid constructor-grid-i-h-4">
                    <div class="constructor-grid-item">
                        <?= $language->getMessage('settings.opacity') ?>
                    </div>
                    <div class="constructor-grid-item-auto">
                        <span data-bind="{
                            text: properties.background.opacity() + '%'
                        }"></span>
                    </div>
                </div>
            </div>
            <div class="constructor-menu-field-content">
                <div data-bind="{
                    bind: ko.models.slider({
                        'min': 0,
                        'max': 100,
                        'step': 1
                    }, properties.background.opacity)
                }"></div>
            </div>
        </div>
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
                                    bind: ko.models.colorpicker({}, properties.border.color.default),
                                    style: {
                                        backgroundColor: properties.border.color.default
                                    }
                                }">
                                    <div class="constructor-aligner"></div>
                                    <i class="far fa-eye-dropper"></i>
                                </div>
                            </div>
                            <div class="constructor-grid-item">
                                <input type="text" class="constructor-input constructor-input-block" data-bind="{
                                    value: properties.border.color.default
                                }" />
                            </div>
                        </div>
                    </div>
                </div>
                <div class="constructor-column-6">
                    <div class="constructor-menu-field-title">
                        <?= $language->getMessage('settings.color.hover') ?>
                    </div>
                    <div class="constructor-menu-field-content">
                        <div class="constructor-grid constructor-grid-i-v-4 constructor-grid-nowrap">
                            <div class="constructor-grid-item-auto">
                                <div class="constructor-colorpicker-button constructor-vertical-middle" data-bind="{
                                    bind: ko.models.colorpicker({}, properties.border.color.hover),
                                    style: {
                                        backgroundColor: properties.border.color.hover
                                    }
                                }">
                                    <div class="constructor-aligner"></div>
                                    <i class="far fa-eye-dropper"></i>
                                </div>
                            </div>
                            <div class="constructor-grid-item">
                                <input type="text" class="constructor-input constructor-input-block" data-bind="{
                                    value: properties.border.color.hover
                                }" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
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