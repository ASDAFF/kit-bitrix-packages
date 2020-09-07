<?php

use intec\constructor\models\Font;
use elements\intec\constructor\text\Element;
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
<div class="constructor-menu-section">
    <div class="constructor-menu-section-fields">
        <div class="constructor-menu-field">
            <div class="constructor-row">
                <div class="constructor-column-6">
                    <div class="constructor-menu-field-title">
                        <?= $language->getMessage('settings.text.align') ?>
                    </div>
                    <div class="constructor-menu-field-content">
                        <select class="constructor-input constructor-input-block" data-bind="{
                            value: attribute('textAlign'),
                            bind: ko.models.select()
                        }">
                            <option value="left">
                                <?= $language->getMessage('settings.text.align.left') ?>
                            </option>
                            <option value="center">
                                <?= $language->getMessage('settings.text.align.center') ?>
                            </option>
                            <option value="right">
                                <?= $language->getMessage('settings.text.align.right') ?>
                            </option>
                            <option value="justify">
                                <?= $language->getMessage('settings.text.align.justify') ?>
                            </option>
                        </select>
                    </div>
                </div>
                <div class="constructor-column-6">
                    <div class="constructor-menu-field-title">
                        <?= $language->getMessage('settings.text.color') ?>
                    </div>
                    <div class="constructor-menu-field-content">
                        <div class="constructor-grid constructor-grid-i-v-4 constructor-grid-nowrap">
                            <div class="constructor-grid-item-auto">
                                <div class="constructor-colorpicker-button constructor-vertical-middle" data-bind="{
                                    bind: ko.models.colorpicker({}, attribute('textColor')),
                                    style: {
                                        backgroundColor: attribute('textColor')
                                    }
                                }">
                                    <div class="constructor-aligner"></div>
                                    <i class="far fa-eye-dropper"></i>
                                </div>
                            </div>
                            <div class="constructor-grid-item">
                                <input type="text" class="constructor-input constructor-input-block" data-bind="{
                                    value: attribute('textColor')
                                }" />
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
                                    value: attribute('textSize')
                                }" />
                            </div>
                            <div class="constructor-grid-item-6">
                                <select class="constructor-input constructor-input-block" data-bind="{
                                    options: attribute('textSize').measures,
                                    value: attribute('textSize').measure,
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
                                    value: attribute('textLineHeight')
                                }" />
                            </div>
                            <div class="constructor-grid-item-6">
                                <select class="constructor-input constructor-input-block" data-bind="{
                                    options: attribute('textLineHeight').measures,
                                    value: attribute('textLineHeight').measure,
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
                                    value: attribute('textLetterSpacing')
                                }" />
                            </div>
                            <div class="constructor-grid-item-6">
                                <select class="constructor-input constructor-input-block" data-bind="{
                                    options: attribute('textLetterSpacing').measures,
                                    value: attribute('textLetterSpacing').measure,
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
<div class="constructor-menu-section constructor-m-t-20">
    <div class="constructor-menu-section-fields">
        <div class="constructor-menu-field">
            <div class="constructor-menu-field-title">
                <?= $language->getMessage('settings.text') ?>
            </div>
            <div class="constructor-menu-field-content">
                <textarea class="constructor-input constructor-input-block" data-bind="{
                    bind: ko.models.ckeditor({
                        'allowedContent': true,
                        'removeButtons': 'Font'
                    }, properties.text)
                }"></textarea>
            </div>
        </div>
    </div>
</div>