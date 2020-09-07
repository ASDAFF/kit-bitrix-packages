<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;
use intec\core\helpers\ArrayHelper;
use intec\core\helpers\Html;
use intec\core\helpers\JavaScript;
use intec\constructor\models\Font;

/**
 * @var array $lang
 */

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
    fade: function () { return menu.tabs.list.container.isActive() },
    with: $root.selected
}">
    <div class="constructor-form">
        <div class="constructor-form-wrap">
            <div class="constructor-menu-left-title">
                <?= Loc::getMessage('container.settings') ?>
            </div>
            <div class="constructor-form-component-settings" data-bind="{
                visible: hasComponent()
            }">
                <span class="constructor-icon-settings show-additional-modal"
                    style="cursor: pointer;"
                    title="<?= Loc::getMessage('widget.settings') ?>"
                    data-bind="{
                        click: function () {
                            $root.dialogs.list.componentProperties.open(getComponent())
                        },
                        bind: ko.models.tooltip()
                    }"
                ></span>
            </div>
            <div class="constructor-form-component-settings" data-bind="{
                visible: hasWidget()
            }">
                <span class="constructor-icon-settings show-additional-modal"
                    style="cursor: pointer;"
                    title="<?= Loc::getMessage('widget.settings') ?>"
                    data-bind="{
                        click: function () {
                            $root.menu.tabs.open($root.menu.tabs.list.widget)
                        },
                        bind: ko.models.tooltip()
                    }"
                ></span>
            </div>
            <div class="constructor-form-component-settings" data-bind="{
                visible: hasVariator()
            }">
                <span class="constructor-icon-settings show-additional-modal"
                    style="cursor: pointer;"
                    title="<?= Loc::getMessage('widget.settings') ?>"
                    data-bind="{
                        click: function () {
                            $root.menu.tabs.open($root.menu.tabs.list.variator)
                        },
                        bind: ko.models.tooltip()
                    }"
                ></span>
            </div>
            <div class="constructor-row constructor-row-i-none">
                <div class="constructor-form-group" data-bind="{
                    if: !hasElement() || hasArea() || hasVariator()
                }">
                    <div class="constructor-form-label">
                        <?= Loc::getMessage('container.settings.variant') ?>:
                        <!-- ko if: !hasArea() && !hasVariator() -->
                            <span><?= Loc::getMessage('container.settings.variant.simple') ?></span>
                        <!-- /ko -->
                        <!-- ko if: hasArea() -->
                            <span><?= Loc::getMessage('container.settings.variant.area') ?></span>
                        <!-- /ko -->
                        <!-- ko if: hasVariator() -->
                            <span><?= Loc::getMessage('container.settings.variant.variator') ?></span>
                        <!-- /ko -->
                    </div>
                    <div class="constructor-form-content">
                        <div class="constructor-grid constructor-grid-i-h-4 constructor-grid-nowrap">
                            <!-- ko if: hasArea() || hasVariator() -->
                                <div class="constructor-grid-item-6">
                                    <div class="constructor-button constructor-button-block constructor-button-c-blue constructor-button-s-1 constructor-button-f-12" data-bind="{
                                        click: function () {
                                            if (confirm(<?= JavaScript::toObject(Loc::getMessage('container.settings.convert.warning')) ?>))
                                                convertToSimple();
                                        }
                                    }" style="text-align: center;">
                                        <?= Loc::getMessage('container.settings.convert.toSimple') ?>
                                    </div>
                                </div>
                            <!-- /ko -->
                            <!-- ko if: !hasElement() || hasArea() -->
                                <div class="constructor-grid-item-6">
                                    <div class="constructor-button constructor-button-block constructor-button-c-blue constructor-button-s-1 constructor-button-f-12" data-bind="{
                                        click: function () {
                                            if (confirm(<?= JavaScript::toObject(Loc::getMessage('container.settings.convert.warning')) ?>))
                                                convertToVariator();
                                        }
                                    }" style="text-align: center;">
                                        <?= Loc::getMessage('container.settings.convert.toVariator') ?>
                                    </div>
                                </div>
                            <!-- /ko -->
                            <!-- ko if: (!hasElement() || hasVariator()) && (function () {
                                var result = true;

                                intec.each(getParents(), function (index, parent) {
                                    if ($root.isArea(parent)) {
                                        result = false;
                                        return false;
                                    }
                                });

                                return result;
                            })() -->
                                <div class="constructor-grid-item-6">
                                    <div class="constructor-button constructor-button-block constructor-button-c-blue constructor-button-s-1 constructor-button-f-12" data-bind="{
                                        click: function () {
                                            if (confirm(<?= JavaScript::toObject(Loc::getMessage('container.settings.convert.warning')) ?>))
                                                $root.dialogs.list.areaSelect.open(function (area) {
                                                    convertToArea(area);
                                                    area.refresh();
                                                });
                                        }
                                    }" style="text-align: center;">
                                        <?= Loc::getMessage('container.settings.convert.toArea') ?>
                                    </div>
                                </div>
                            <!-- /ko -->
                        </div>
                    </div>
                </div>
            </div>
            <div class="constructor-row constructor-row-i-none">
                <div class="constructor-form-group" data-bind="{
                    visible: !hasElement()
                }">
                    <div class="constructor-form-label">
                        <?= Loc::getMessage('container.settings.type') ?>
                    </div>
                    <div class="constructor-form-content">
                        <select class="constructor-input constructor-input-block" data-bind="{
                            value: type,
                            options: <?= $lang['type'] ?>,
                            optionsValue: 'key',
                            optionsText: 'value',
                            bind: ko.models.select()
                        }"></select>
                    </div>
                </div>
            </div>
            <div class="constructor-row constructor-row-i-none">
                <div class="constructor-form-group" data-bind="{
                    visible: type() == 'normal'
                }">
                    <div class="constructor-form-label">
                        <?= Loc::getMessage('container.settings.float') ?>
                    </div>
                    <div class="constructor-form-content">
                        <select class="constructor-input constructor-input-block" data-bind="{
                            value: properties.float,
                            options: <?= $lang['float'] ?>,
                            optionsValue: 'key',
                            optionsText: 'value',
                            bind: ko.models.select()
                        }"></select>
                    </div>
                </div>
            </div>
            <div class="constructor-row constructor-row-i-none">
                <div class="constructor-form-group">
                    <div class="constructor-form-label">
                        <span style="float:right;" data-bind="{ text: properties.getOpacityPercent() }"></span>
                        <span>
                            <?= Loc::getMessage('container.settings.opacity') ?>
                        </span>
                    </div>
                    <div class="constructor-form-content">
                        <div data-bind="{
                            bind: ko.models.slider({
                                'min': 0,
                                'max': 1,
                                'step': 0.01
                            }, properties.opacity)
                        }"></div>
                    </div>
                </div>
            </div>
            <div class="constructor-row constructor-row-i-none" data-bind="{ if: hasParent() }">
                <div class="constructor-form-group">
                    <div class="constructor-form-label">
                        <label>
                            <span style="margin-right: 10px;">
                                <input type="checkbox" data-bind="{
                                    bind: ko.models.switch(),
                                    checked: display
                                }" />
                            </span>
                            <span><?= Loc::getMessage('container.settings.display') ?></span>
                        </label>
                    </div>
                </div>
            </div>
            <div class="constructor-row constructor-row-i-none" data-bind="{ if: hasParent() }">
                <div class="constructor-form-group">
                    <div class="constructor-button constructor-button-block constructor-button-c-blue constructor-button-s-2 constructor-button-f-16"
                        data-bind="{ click: $root.dialogs.list.conditions.open }"
                        style="text-align: center;"
                    ><?= Loc::getMessage('container.settings.conditions') ?></div>
                </div>
            </div>
            <div class="constructor-row constructor-row-i-none">
                <div class="constructor-form-group">
                    <div class="constructor-button constructor-button-block constructor-button-c-blue constructor-button-s-2 constructor-button-f-16" data-bind="{
                        click: function () {
                            $root.dialogs.list.script.open($data);
                        }
                    }" style="text-align: center;">
                        <?= Loc::getMessage('container.settings.script') ?>
                    </div>
                </div>
            </div>
            <!-- ko if: $root.settings.development() -->
                <div class="constructor-row constructor-row-i-none">
                    <div class="constructor-form-group">
                        <div class="constructor-button constructor-button-block constructor-button-c-blue constructor-button-s-2 constructor-button-f-16" data-bind="{
                            click: function () {
                                $root.dialogs.list.structure.open($data);
                            }
                        }" style="text-align: center;">
                            <?= Loc::getMessage('container.settings.structure') ?>
                        </div>
                    </div>
                </div>
                <div class="constructor-row constructor-row-i-none">
                    <div class="constructor-form-group">
                        <div class="constructor-form-label">
                            <?= Loc::getMessage('container.settings.code') ?>
                        </div>
                        <div class="constructor-form-content">
                            <input type="text"
                                class="constructor-input"
                                data-bind="{ value: code }"
                            />
                        </div>
                    </div>
                </div>
            <!-- /ko -->
        </div>
        <div data-bind="{
            bind: ko.models.accordion({
                header: '> .constructor-property-wrapper > .constructor-property-title',
                icons: {
                    'header': 'constructor-ui-icon-arrow-static fa fa-angle-down',
                    'activeHeader': 'constructor-ui-icon-arrow-active fa fa-angle-up'
                },
                classes: {
                    'ui-accordion-header': 'constructor-accordion-header constructor-property-title',
                    'ui-accordion-header-collapsed': 'constructor-accordion-header-collapsed',
                    'ui-accordion-content': 'constructor-accordion-content constructor-clearfix'
                },
                activate: function (event, ui) {
                    $root.menu.scroll.update();
                }
            })
        }">
            <div class="constructor-property-wrapper">
                <div class="constructor-property-title">
                    <?= Loc::getMessage('container.settings.attributes') ?>
                </div>
                <div class="constructor-row constructor-row-i-none">
                    <div class="constructor-form-group">
                        <div class="constructor-form-label">
                            <?= Loc::getMessage('container.settings.id') ?>
                        </div>
                        <div class="constructor-form-content">
                            <input type="text"
                               class="constructor-input"
                               data-bind="{ value: properties.id }"
                            />
                        </div>
                    </div>
                    <div class="constructor-form-group">
                        <div class="constructor-form-label">
                            <?= Loc::getMessage('container.settings.class') ?>
                        </div>
                        <div class="constructor-form-content">
                            <input type="text"
                                class="constructor-input"
                                data-bind="{ value: properties.class }"
                            />
                        </div>
                    </div>
                </div>
            </div>
            <div class="constructor-property-wrapper">
                <div class="constructor-property-title">
                    <?= Loc::getMessage('container.settings.sizes') ?>
                    <span class="constructor-icon-settings show-additional-modal" data-bind="{
                        bind: function (node, bindings) {
                            $root.bindings.showAdditional(node, bindings, '#addition-sizes');
                        }
                    }"></span>
                </div>
                <div class="constructor-row">
                    <div class="constructor-column-6">
                        <div class="constructor-form-group" data-bind="{
                            visible: !properties.bind.horizontal()
                        }">
                            <div class="constructor-form-label">
                                <?= Loc::getMessage('container.settings.width') ?>
                            </div>
                            <div class="constructor-form-content">
                                <div class="constructor-grid constructor-grid-i-h-4 constructor-grid-nowrap">
                                    <div class="constructor-grid-item">
                                        <input type="text"
                                            class="constructor-input"
                                            data-bind="{ value: properties.width.value }"
                                        />
                                    </div>
                                    <div class="constructor-grid-item-6">
                                        <select class="constructor-input" data-bind="{
                                            value: properties.width.measure,
                                            options: properties.width.measures(),
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
                        <div class="constructor-form-group"
                             data-bind="{ visible: !properties.bind.vertical() }">
                            <div class="constructor-form-label">
                                <?= Loc::getMessage('container.settings.height') ?>
                            </div>
                            <div class="constructor-form-content">
                                <div class="constructor-grid constructor-grid-i-h-4 constructor-grid-nowrap">
                                    <div class="constructor-grid-item">
                                        <input type="text"
                                            class="constructor-input"
                                            data-bind="{ value: properties.height.value }"
                                        />
                                    </div>
                                    <div class="constructor-grid-item-6">
                                        <select class="constructor-input" data-bind="{
                                            value: properties.height.measure,
                                            options: properties.height.measures(),
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
            <div class="constructor-property-wrapper" data-bind="{
                with: properties,
                visible: type() == 'absolute'
            }">
                <div class="constructor-property-title">
                    <?= Loc::getMessage('container.settings.grid') ?>
                </div>
                <div>
                    <div class="constructor-grid constructor-grid-i-h-6 constructor-grid-nowrap constructor-p-v-10">
                        <? foreach (['none', 'adaptive', 'fixed'] as $value) { ?>
                            <div class="constructor-grid-item-auto">
                                <input type="radio" class="constructor-radio" value="<?= $value ?>" data-bind="{
                                    checked: grid.type,
                                    bind: ko.models.switch({
                                        classes: {
                                            wrap: 'api-ui-switch api-ui-radio'
                                        }
                                    })
                                }" />
                                <?= Loc::getMessage('container.settings.grid.type.' . $value) ?>
                            </div>
                        <? } ?>
                    </div>
                    <div class="constructor-grid constructor-grid-i-h-4 constructor-grid-a-v-center constructor-grid-nowrap constructor-p-v-10" data-bind="{
                        visible: grid.type() == 'adaptive'
                    }">
                        <div class="constructor-grid-item">
                            <input type="text"
                                class="constructor-input"
                                data-bind="{
                                    value: grid.x
                                }"
                            />
                        </div>
                        <div class="constructor-grid-item-auto">x</div>
                        <div class="constructor-grid-item">
                            <input type="text"
                                class="constructor-input"
                                data-bind="{ value: grid.y }"
                            />
                        </div>
                    </div>
                    <div class="constructor-grid constructor-grid-i-h-4 constructor-grid-a-v-center constructor-grid-nowrap constructor-p-v-10" data-bind="{
                        visible: grid.type() == 'fixed'
                    }">
                        <div class="constructor-grid-item">
                            <input type="text"
                                class="constructor-input"
                                data-bind="{ value: grid.width }"
                            />
                        </div>
                        <div class="constructor-grid-item-auto">x</div>
                        <div class="constructor-grid-item">
                            <input type="text"
                                class="constructor-input"
                                data-bind="{ value: grid.height }"
                            />
                        </div>
                    </div>
                    <div class="constructor-grid constructor-grid-i-6 constructor-grid-o-vertical constructor-p-v-10" data-bind="{
                        visible: grid.type() !== 'none'
                    }">
                        <div class="constructor-grid-item-auto">
                            <input type="checkbox" data-bind="{
                                checked: grid.correct.width,
                                bind: ko.models.switch()
                            }" />
                            <?= Loc::getMessage('container.settings.grid.correct.width') ?>
                        </div>
                        <div class="constructor-grid-item-auto">
                            <input type="checkbox" data-bind="{
                                checked: grid.correct.height,
                                bind: ko.models.switch()
                            }" />
                            <?= Loc::getMessage('container.settings.grid.correct.height') ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="constructor-property-wrapper" data-bind="{
                with: properties
            }">
                <div class="constructor-property-title">
                    <?= Loc::getMessage('container.settings.offset') ?>
                    <span class="constructor-icon-settings show-additional-modal" data-bind="{
                        bind: function (node, bindings) {
                            $root.bindings.showAdditional(node, bindings, '#addition-offset');
                        }
                    }"></span>
                </div>
                <div class="constructor-row constructor-row-i-none">
                    <div class="constructor-form-group">
                        <div class="constructor-form-label">
                            <?= Loc::getMessage('container.settings.margin') ?>
                            <span class="constructor-m-l-5"
                                title="<?= Loc::getMessage('container.settings.margin.auto') ?>"
                                data-bind="{
                                    bind: ko.models.tooltip()
                                }"
                            >
                                <input type="checkbox" data-bind="{
                                    checked: margin.isAuto,
                                    bind: ko.models.switch()
                                }" />
                            </span>
                        </div>
                        <div data-bind="{
                            slide: function () { return !margin.isAuto() }
                        }">
                            <div class="constructor-form-content">
                                <div class="constructor-grid constructor-grid-i-h-4 constructor-grid-nowrap">
                                    <div class="constructor-grid-item">
                                        <input type="text" class="constructor-input" data-bind="{
                                            value: margin.value
                                        }" />
                                    </div>
                                    <div class="constructor-grid-item-3">
                                        <select class="constructor-input" data-bind="{
                                            value: margin.measure,
                                            options: margin.measures(),
                                            bind: ko.models.select({
                                                'theme': 'gray'
                                            })
                                        }"></select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="constructor-form-group">
                        <div class="constructor-form-label">
                            <?= Loc::getMessage('container.settings.padding') ?>
                        </div>
                        <div class="constructor-form-content">
                            <div class="constructor-grid constructor-grid-i-h-4 constructor-grid-nowrap">
                                <div class="constructor-grid-item">
                                    <input type="text"
                                        class="constructor-input"
                                        data-bind="{
                                            value: padding.value
                                        }"
                                    />
                                </div>
                                <div class="constructor-grid-item-3">
                                    <select class="constructor-input" data-bind="{
                                        value: padding.measure,
                                        options: padding.measures(),
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
            <div class="constructor-property-wrapper" data-bind="{
                with: properties.background
            }">
                <div class="constructor-property-title">
                    <?= Loc::getMessage('container.settings.background') ?>
                    <span class="constructor-icon-settings show-additional-modal" data-bind="{
                        bind: function (node, bindings) {
                            $root.bindings.showAdditional(node, bindings, '#addition-background');
                        }
                    }"></span>
                </div>
                <div>
                    <div class="constructor-row constructor-row-i-none">
                        <div class="constructor-form-group">
                            <div class="constructor-form-label"><?= Loc::getMessage('container.settings.background.color') ?></div>
                            <div class="constructor-form-content">
                                <div class="constructor-grid constructor-grid-nowrap">
                                    <div class="constructor-grid-item-auto">
                                        <div class="constructor-colorpicker-button constructor-vertical-middle" data-bind="{
                                            bind: ko.models.colorpicker({}, color),
                                            style: {
                                                backgroundColor: color
                                            }
                                        }">
                                            <div class="constructor-aligner"></div>
                                            <i class="far fa-eye-dropper"></i>
                                        </div>
                                    </div>
                                    <div class="constructor-grid-item">
                                        <input type="text" class="constructor-input constructor-colorpicker-input" data-bind="{ value: color }" />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="constructor-form-group">
                            <div class="constructor-form-label">
                                <?= Loc::getMessage('container.settings.background.image') ?>
                            </div>
                            <div class="constructor-form-content constructor-settings-background-image-wrapper" style="text-align: center;">
                                <div class="constructor-button constructor-button-block constructor-button-c-blue constructor-button-s-2 constructor-button-f-16"
                                    onclick="$(this).parent().find('.constructor-image-chooser').trigger('click')"
                                ><?= Loc::getMessage('container.settings.background.image.load') ?></div>
                                <div class="constructor-button constructor-button-block constructor-button-c-blue constructor-button-s-2 constructor-button-f-16"
                                    data-bind="{ click: function () {
                                        $root.dialogs.list.gallery.open(function (data) {
                                            image.url(data.value);
                                            return true;
                                        });
                                    }}"
                                ><?= Loc::getMessage('container.settings.background.image.gallery') ?></div>
                                <input class="constructor-image-chooser" type="file" accept="image/*" data-bind="{
                                    event: {
                                        change: image.load
                                    }
                                }" />
                                <div class="constructor-background-image-wrapper"
                                     data-bind="css: {'has-image': image.url}">
                                    <div class="constructor-background-image" data-bind="{
                                        style: {
                                            backgroundImage: image.calculated,
                                            backgroundRepeat: repeat,
                                            backgroundSize: getSize(),
                                            backgroundPosition: position.calculated
                                        }
                                    }"></div>
                                </div>
                                <div style="text-align: left;">
                                    <input type="text"
                                        class="constructor-input"
                                        style="width: 100%;"
                                        placeholder="<?= Loc::getMessage('container.settings.background.image.url.placeholder') ?>"
                                        data-bind="{
                                            value: image.url
                                        }"
                                    />
                                </div>
                                <div class="constructor-button constructor-button-c-default constructor-button-s-2 constructor-button-f-16" data-bind="{
                                    click: image.delete
                                }"><?= Loc::getMessage('container.settings.background.image.delete') ?></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="constructor-property-wrapper" data-bind="{
                with: properties.border
            }">
                <div class="constructor-property-title">
                    <?= Loc::getMessage('container.settings.border') ?>
                    <span class="constructor-icon-settings show-additional-modal" data-bind="{
                        bind: function (node, bindings) {
                            $root.bindings.showAdditional(node, bindings, '#addition-border');
                        }
                    }"></span>
                </div>
                <div class="constructor-row">
                    <div class="constructor-column-6">
                        <div class="constructor-form-group">
                            <div class="constructor-form-label"><?= Loc::getMessage('container.settings.border.width') ?></div>
                            <div class="constructor-form-content">
                                <div class="constructor-grid constructor-grid-i-h-4 constructor-grid-nowrap">
                                    <div class="constructor-grid-item">
                                        <input type="text" class="constructor-input" data-bind="{
                                            value: width.value
                                        }" />
                                    </div>
                                    <div class="constructor-grid-item-6">
                                        <select class="constructor-input" data-bind="{
                                            value: width.measure,
                                            options: width.measures(),
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
                            <div class="constructor-form-label"><?= Loc::getMessage('container.settings.border.color') ?></div>
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
                                        <input type="text"
                                            class="constructor-input"
                                            data-bind="{
                                                value: color
                                            }"
                                        />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="constructor-column-12">
                        <div class="constructor-form-group">
                            <div class="constructor-form-label">
                                <?= Loc::getMessage('container.settings.border.style') ?>
                            </div>
                            <div class="constructor-form-content">
                                <select class="constructor-input" data-bind="{
                                    value: style,
                                    options: <?= $lang['border'] ?>,
                                    optionsValue: 'key',
                                    optionsText: 'value',
                                    bind: ko.models.select()
                                }"></select>
                            </div>
                        </div>
                    </div>
                    <div class="constructor-column-12">
                        <div class="constructor-form-group">
                            <div class="constructor-form-label">
                                <?= Loc::getMessage('container.settings.border.radius.title') ?>
                                <span class="constructor-icon-settings show-additional-modal" data-bind="{
                                    bind: function (node, bindings) {
                                        $root.bindings.showAdditional(node, bindings, '#addition-border-radius');
                                    }
                                }"></span>
                            </div>
                            <div class="constructor-form-content">
                                <div class="constructor-grid constructor-grid-i-h-6 constructor-grid-nowrap constructor-grid-a-v-center constructor-p-t-10">
                                    <div class="constructor-grid-item-auto" style="font-size: 18px; line-height: 1">
                                        <i class="constructor-icon constructor-icon-square-evolution"></i>
                                    </div>
                                    <div class="constructor-grid-item">
                                        <div data-bind="{
                                        bind: ko.models.slider({
                                            'min': 0,
                                            'max': 100,
                                            'step': 1
                                        }, radius.value)
                                    }"></div>
                                    </div>
                                    <div class="constructor-grid-item-auto">
                                        <div data-bind="{
                                            text: radius.print
                                        }"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="constructor-property-wrapper">
                <div class="constructor-property-title">
                    <?= Loc::getMessage('container.settings.overflow.title') ?>
                </div>
                <div data-bind="{
                    with: properties.overflow
                }">
                    <div class="constructor-row constructor-row-i-none">
                        <div class="constructor-form-group">
                            <div class="constructor-form-label">
                                <?= Loc::getMessage('container.settings.overflow') ?>
                            </div>
                            <div class="constructor-form-content">
                                <select class="constructor-input" data-bind="{
                                    value: value,
                                    options: <?= $lang['overflow'] ?>,
                                    optionsValue: 'key',
                                    optionsText: 'value',
                                    bind: ko.models.select()
                                }"></select>
                            </div>
                        </div>
                        <? foreach (['x', 'y'] as $axis) { ?>
                            <div class="constructor-form-group">
                                <div class="constructor-form-label"><?= Loc::getMessage('container.settings.overflow.'. $axis) ?></div>
                                <div class="constructor-form-content">
                                    <select class="constructor-input" data-bind="{
                                        value: <?= $axis ?>.value,
                                        options: <?= $lang['overflow'] ?>,
                                        optionsValue: 'key',
                                        optionsText: 'value',
                                        bind: ko.models.select()
                                    }"></select>
                                </div>
                            </div>
                        <? } ?>
                    </div>
                </div>
            </div>
            <div class="constructor-property-wrapper">
                <div class="constructor-property-title">
                    <?= Loc::getMessage('container.settings.text') ?>
                </div>
                <div data-bind="{ with: properties.text }">
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
                                    <div class="constructor-grid constructor-grid-i-h-4 constructor-nowrap">
                                        <div class="constructor-grid-item">
                                            <input type="text"
                                                class="constructor-input"
                                                data-bind="{
                                                    value: size.value
                                                }"
                                            />
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
                                <div class="constructor-form-content constructor-input-group constructor-colorpicker-wrap">
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
                                            <input type="text" class="constructor-input" data-bind="{ value: color }">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="constructor-column-12">
                            <div class="constructor-form-group">
                                <div class="constructor-form-label">
                                    <span style="margin-right: 10px;">
                                        <input type="checkbox" data-bind="{
                                            bind: ko.models.switch(),
                                            checked: uppercase
                                        }" />
                                    </span>
                                    <span><?= Loc::getMessage('text.settings.uppercase') ?></span>
                                </div>
                            </div>
                        </div>
                        <div class="constructor-column-6">
                            <div class="constructor-form-group">
                                <div class="constructor-form-label"><?= Loc::getMessage('text.settings.letter.spacing') ?></div>
                                <div class="constructor-form-content">
                                    <div class="constructor-grid constructor-grid-i-h-4 constructor-nowrap">
                                        <div class="constructor-grid-item">
                                            <input type="text" class="constructor-input" data-bind="{
                                                value: letterSpacing.value
                                            }" />
                                        </div>
                                        <div class="constructor-grid-item-6">
                                            <div class="constructor-input-wrapper">
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
                        </div>
                        <div class="constructor-column-6">
                            <div class="constructor-form-group">
                                <div class="constructor-form-label"><?= Loc::getMessage('text.settings.line.height') ?></div>
                                <div class="constructor-form-content">
                                    <div class="constructor-grid constructor-grid-i-h-4 constructor-nowrap">
                                        <div class="constructor-grid-item">
                                            <input type="text" class="constructor-input" data-bind="{
                                                value: lineHeight.value
                                            }" />
                                        </div>
                                        <div class="constructor-grid-item-6">
                                            <div class="constructor-input-wrapper">
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
                </div>
            </div>
        </div>
    </div>
    <!-- ko function: $root.menu.scroll.update --><!-- /ko -->
</div>