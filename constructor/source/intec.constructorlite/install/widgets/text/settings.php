<?php

use intec\core\helpers\ArrayHelper;
use intec\core\helpers\Html;
use intec\constructor\models\Font;
use intec\constructor\structure\Widget;

/**
 * @var Widget $this
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
<div class="constructor-row constructor-row-i-none">
    <div class="constructor-form-group">
        <div class="constructor-form-label">
            <?= $language->getMessage('settings.text.font') ?>
        </div>
        <div class="constructor-form-content">
            <?= Html::dropDownList('', null, ArrayHelper::merge([
                '' => $language->getMessage('settings.text.font.default')
            ], $fonts), [
                'class' => 'constructor-input constructor-input-block',
                'data' => [
                    'bind' => '{
                        value: text.font,
                        bind: ko.models.select()
                    }'
                ]
            ]) ?>
        </div>
    </div>
</div>
<div class="constructor-row constructor-row-i-none">
    <div class="constructor-form-group">
        <div class="constructor-form-label">
            <?= $language->getMessage('settings.text') ?>
        </div>
        <div class="constructor-form-content">
            <textarea class="constructor-input constructor-input-block" data-bind="{
                bind: ko.models.ckeditor({
                    'allowedContent': true,
                    'removeButtons': 'Font'
                }, text)
            }"></textarea>
        </div>
    </div>
</div>
