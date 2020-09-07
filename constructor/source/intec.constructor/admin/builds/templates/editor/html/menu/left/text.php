<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die() ?>
<?php


use Bitrix\Main\Localization\Loc;
use intec\core\helpers\ArrayHelper;
use intec\core\helpers\Html;
use intec\constructor\models\Font;

$fonts = Font::findAvailable();
$fonts = $fonts->asArray(function ($key, $font) {
    /** @var Font $font */

    return [
        'key' => $font->code,
        'value' => $font->name
    ];
});

?>

<div data-bind="{
    fade: function () { return menu.tabs.list.text.isActive() },
    with: $root.settings.text
}">
    <div class="constructor-form">
        <div class="constructor-menu-left-title"><?= Loc::getMessage('text.settings') ?></div>
        <div class="constructor-row">
            <div class="constructor-column-12">
                <div class="constructor-form-group">
                    <div class="constructor-form-label"><?= Loc::getMessage('text.settings.font') ?></div>
                    <div class="constructor-form-content">
                        <?= Html::dropDownList('', null, ArrayHelper::merge([
                            '' => Loc::getMessage('text.settings.font.default')
                        ], $fonts), [
                            'class' => 'constructor-input constructor-input-block',
                            'data' => [
                                'bind' => '{
                                    value: font,
                                    bind: ko.models.select()
                                }'
                            ]
                        ]) ?>
                    </div>
                </div>
            </div>
            <div class="constructor-column-6">
                <div class="constructor-form-group">
                    <div class="constructor-form-label"><?= Loc::getMessage('text.settings.size') ?></div>
                    <div class="constructor-form-content">
                        <div class="constructor-grid constructor-grid-i-h-4 constructor-grid-nowrap">
                            <div class="constructor-grid-item">
                                <input type="text" class="constructor-input" data-bind="{ value: size.value }" />
                            </div>
                            <div class="constructor-grid-item-6">
                                <select class="constructor-input" data-bind="{
                                    value: size.measure,
                                    options: size.measures(),
                                    bind: ko.models.select({
                                        'theme': 'gray'
                                    })
                                }"></select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="constructor-column-6">
                <div class="constructor-form-group">
                    <div class="constructor-form-label"><?= Loc::getMessage('text.settings.color') ?></div>
                    <div class="constructor-form-content">
                        <div class="constructor-grid constructor-grid-nowrap">
                            <div class="constructor-grid-item-auto">
                                <div class="constructor-colorpicker-button constructor-vertical-middle" data-bind="{
                                    bind: ko.models.colorpicker({}, color),
                                    style: { backgroundColor: color }
                                }">
                                    <div class="constructor-aligner"></div>
                                    <i class="far fa-eye-dropper"></i>
                                </div>
                            </div>
                            <div class="constructor-grid-item">
                                <input type="text" class="constructor-input" data-bind="value: color">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="constructor-column-12">
                <div class="constructor-form-group">
                    <div class="constructor-form-label">
                        <label>
                            <input type="checkbox" data-ui-switch="{}" data-bind="{ checked: uppercase }" />
                            <span><?= Loc::getMessage('text.settings.uppercase') ?></span>
                        </label>
                    </div>
                </div>
            </div>
            <div class="constructor-column-6">
                <div class="constructor-form-group">
                    <div class="constructor-form-label"><?= Loc::getMessage('text.settings.letter.spacing') ?></div>
                    <div class="constructor-form-content">
                        <div class="constructor-grid constructor-grid-i-h-4 constructor-grid-nowrap">
                            <div class="constructor-grid-item">
                                <input type="text" class="constructor-input" data-bind="{
                                    value: letterSpacing.value
                                }" />
                            </div>
                            <div class="constructor-grid-item-6">
                                <select class="constructor-input" data-bind="{
                                    value: letterSpacing.measure,
                                    options: letterSpacing.measures(),
                                    bind: ko.models.select({
                                        'theme': 'gray'
                                    })
                                }"></select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="constructor-column-6">
                <div class="constructor-form-group">
                    <div class="constructor-form-label"><?= Loc::getMessage('text.settings.line.height') ?></div>
                    <div class="constructor-form-content">
                        <div class="constructor-grid constructor-grid-i-h-4 constructor-grid-nowrap">
                            <div class="constructor-grid-item">
                                <input type="text" class="constructor-input" data-bind="{
                                    value: lineHeight.value
                                }" />
                            </div>
                            <div class="constructor-grid-item-6">
                                <select class="constructor-input" data-bind="{
                                    value: lineHeight.measure,
                                    options: lineHeight.measures(),
                                    bind: ko.models.select({
                                        'theme': 'gray'
                                    })
                                }"></select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- ko function: $root.menu.scroll.update --><!-- /ko -->
</div>