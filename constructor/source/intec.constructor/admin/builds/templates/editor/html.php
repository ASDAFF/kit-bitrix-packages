<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die() ?>
<?php

use Bitrix\Main\Localization\Loc;
use intec\core\helpers\JavaScript;
use intec\core\helpers\StringHelper;
use intec\constructor\models\Build;
use intec\constructor\models\build\template\Container;

/**
 * @var array $arUrlTemplates
 * @var Build $build
 */

?>
<div id="constructor" class="constructor">
    <div class="constructor-menu constructor-menu-top no-select">
        <div class="constructor-vertical-middle constructor-menu-top-wrapper-right constructor-button-group constructor-button-s-3">
            <div class="constructor-aligner"></div>
            <a href="<?= StringHelper::replaceMacros($arUrlTemplates['builds.templates'], [
                'build' => $build->id
            ]) ?>"
               class="constructor-button constructor-button-c-blue-t"><?= Loc::getMessage('button.exit') ?></a>
            <div class="constructor-button constructor-button-c-blue constructor-save-button"
                data-bind="click: save"
            ><?= Loc::getMessage('button.save') ?></div>
        </div>
        <div class="constructor-menu-top-buttons-wrap"
             data-bind="css: $root.menu.tabs.active().length > 0 ? 'tab-selected' : ''">
            <div class="constructor-menu-top-button constructor-back-menu typcn typcn-arrow-left"></div>
        </div>
        <div class="constructor-vertical-middle constructor-button-group constructor-button-s-3">
            <div class="constructor-aligner"></div>
            <div class="constructor-button constructor-button-c-blue-t" data-bind="{
                css: menu.tabs.active.getLast() === menu.tabs.list.main ? 'hover' : '',
                click: function(){ menu.tabs.open(menu.tabs.list.main); }
            }">
                <i class="constructor-icon-settings"></i>
                <?= Loc::getMessage('panel.widgets') ?>
            </div>
            <div class="constructor-button constructor-button-c-blue-t" data-bind="{
                css: menu.tabs.active.getLast() === menu.tabs.list.text ? 'hover' : '',
                click: function(){ menu.tabs.open(menu.tabs.list.text); }
            }">
                <i class="fa fa-font" style="font-size: 16px;"></i>
                <?= Loc::getMessage('panel.text') ?>
            </div>
            <div class="constructor-button constructor-button-c-blue-t" data-bind="{
                css: menu.tabs.active.getLast() === menu.tabs.list.scheme ? 'hover' : '',
                click: function(){ menu.tabs.open(menu.tabs.list.scheme); }
            }">
                <i class="fa fa-paint-brush" style="font-size: 18px;"></i>
                <?= Loc::getMessage('panel.scheme') ?>
            </div>
            <div class="constructor-button constructor-button-c-blue-t" data-bind="{
                css: menu.tabs.active.getLast() === menu.tabs.list.guides ? 'hover' : '',
                click: function(){ menu.tabs.open(menu.tabs.list.guides); }
            }">
                <?= Loc::getMessage('panel.guides') ?>
            </div>
            <div class="constructor-button constructor-button-c-blue-t" data-bind="{
                css: menu.tabs.active.getLast() === menu.tabs.list.visual ? 'hover' : '',
                click: function(){ menu.tabs.open(menu.tabs.list.visual); }
            }">
                <?= Loc::getMessage('panel.display') ?>
            </div>
        </div>
    </div>

    <div class="constructor-panel constructor-panel-container" id="container-panel" data-bind="{
        with: $root.selected,
        css: {
            'constructor-panel-active': $root.selected() && $root.selected().hasParent()
        }
    }">
        <!-- ko if: hasParent() && $root.isContainer(getParent()) -->
            <!-- ko if: getParentType() == 'absolute' -->
                <div class="constructor-panel-button constructor-panel-button-layer-down constructor-icon-layer-down"
                     title="<?= Loc::getMessage('panel.layer.down') ?>"
                     data-bind="{ bind: ko.models.tooltip(), click: layerUp }"
                ></div>
                <div class="constructor-panel-button constructor-panel-button-layer-up constructor-icon-layer-up"
                     title="<?= Loc::getMessage('panel.layer.up') ?>"
                     data-bind="{ bind: ko.models.tooltip(), click: layerDown }"
                ></div>
            <!-- /ko -->
            <!-- ko if: getParentType() != 'absolute' -->
                <div class="constructor-panel-button constructor-panel-button-level-down fa fa-angle-down"
                     title="<?= Loc::getMessage('panel.level.down') ?>"
                     data-bind="{ bind: ko.models.tooltip(), click: layerDown }"
                ></div>
                <div class="constructor-panel-button constructor-panel-button-level-up fa fa-angle-up"
                     title="<?= Loc::getMessage('panel.level.up') ?>"
                     data-bind="{ bind: ko.models.tooltip(), click: layerUp }"
                ></div>
            <!-- /ko -->
            <!-- ko if: !hasArea() -->
                <div class="constructor-panel-button constructor-panel-button-copy constructor-icon-copy" title="<?= Loc::getMessage('panel.copy') ?>" data-bind="{
                    bind: ko.models.tooltip(),
                    click: function () {
                        var container;

                        container = copy();
                        container.order(container.order() + 0.5);
                        parent().addContainer(container);
                    }
                }"></div>
            <!-- /ko -->
            <div class="constructor-panel-button constructor-panel-button-remove constructor-icon-cancel"
                 title="<?= Loc::getMessage('panel.delete.container') ?>"
                 data-bind="{
                    bind: ko.models.tooltip(),
                    click:
                        function() {
                            if(confirm('<?= Loc::getMessage("container.modals.conditions.remove.confirm") ?>')) {
                                remove()
                            }
                        }
                 }"
            ></div>
        <!-- /ko -->
    </div>

    <? include(__DIR__.'/html/menu/left.php') ?>
    <? include(__DIR__.'/html/modals.php') ?>
    <? include(__DIR__.'/html/blocks.php') ?>

    <div class="constructor-area" data-bind="{
        css: {
            'constructor-area-active': $root.menu.tabs.isActive(),
            'constructor-area-containers-structure': $root.settings.containers.structure
        },
        style: {
            fontFamily: settings.text.font,
            fontSize: settings.text.size.summary,
            color: settings.text.color,
            lineHeight: settings.text.lineHeight.summary,
            letterSpacing: settings.text.letterSpacing.summary,
            textTransform: settings.text.uppercase() ? 'uppercase' : 'inherit'
        }
    }">
        <div class="constructor-area-wrap">
            <div class="constructor-lock" data-bind="{
                visible: $root.menu.tabs.isLock,
                click: function () {
                    var tab = $root.menu.tabs.active.getLast();
                    if (tab) tab.close();
                }
            }"></div>
            <div class="constructor-structure" id="constructor-structure" data-bind="
                bind: function(node){
                    var $node = $(node);
                    $node.off('resize.guides').on('resize.guides', function(){
                        $root.guides.rows.space.value.valueHasMutated();
                    });
                }">
                <div class="constructor-structure-top-padding"></div>
                <!-- ko if: $root.isContainer(container()) -->
                    <div class="root" data-bind="{ template: { name: 'container', data: container } }"></div>
                <!-- /ko -->
                <!-- ko with: $root.structure.shifter -->
                    <!-- ko if: display -->
                        <div class="constructor-structure-shifter" data-bind="{
                            bind: frame,
                            style: {
                                'display': 'block'
                            }
                        }">
                            <div class="constructor-container-panel-wrapper">
                                <div class="constructor-container-panel constructor-clearfix">
                                    <span class="constructor-container-drag-icon" data-bind="{
                                        bind: holder
                                    }"></span>
                                    <!-- ko with: container -->
                                        <span class="constructor-container-name" data-bind="{
                                            text: (function(){
                                                var result;

                                                if (hasComponent()) {
                                                    result = '<?= Loc::getMessage('widget.component') ?>';
                                                } else if (hasWidget()) {
                                                    result = getWidget().model().name();
                                                } else if (hasBlock()) {
                                                    result = getBlock().name();
                                                } else if (hasVariator()) {
                                                    result = '<?= Loc::getMessage('widget.variator') ?>';
                                                } else if (hasArea()) {
                                                    result = '<?= Loc::getMessage('widget.area') ?>' + getArea().name();
                                                } else {
                                                    result = '<?= Loc::getMessage('widget.container') ?>';
                                                }

                                                return result;
                                            })()
                                        }"></span>
                                    <!-- /ko -->
                                </div>
                            </div>
                            <div class="constructor-structure-shifter-wrapper" data-bind="{
                                template: { name: 'container', data: container }
                            }"></div>
                            <div class="constructor-structure-shifter-blocker"></div>
                        </div>
                    <!-- /ko -->
                <!-- /ko -->
            </div>
        </div>
    </div>
    <!-- ko with: $root.structure.creator -->
        <!-- ko if: display -->
            <!-- ko with: object -->
                <div class="constructor-widget constructor-widget-drag" data-bind="{
                    bind: $parent.node,
                    css: 'constructor-widget-' + type()
                }">
                    <div class="constructor-widget-wrapper">
                        <div class="constructor-widget-wrapper-2">
                            <div class="constructor-widget-background"></div>
                            <div class="constructor-widget-icon" data-bind="{
                                style: {
                                    'background-image': icon.calculated
                                }
                            }"></div>
                            <div class="constructor-widget-name" data-bind="{
                                text: name
                            }"></div>
                        </div>
                    </div>
                </div>
            <!-- /ko -->
        <!-- /ko -->
    <!-- /ko -->

    <? include(__DIR__.'/html/dialogs.php') ?>
</div>

<script id="container" type="text/html">
    <!-- ko if: $root.isContainer($data) -->
        <div class="constructor-container" data-bind="{
            bind: function (node) {
                $data.node(node);
                $data.node.zone.node(node);
            },
            attr: { id: properties.id },
            css: (function () {
                var result = [];
                var type = 'container';

                if (hasWidget() || hasComponent() || hasBlock()) {
                    type = 'widget';
                } else if (hasVariator()) {
                    type = 'variator';
                } else if (hasArea()) {
                    type = 'area';
                }

                result.push(properties.class());
                result.push('container-type-' + type);

                if (id() != null)
                    result.push('container-' + id());

                if (simple())
                    result.push('constructor-container-simple');

                if (selected())
                    result.push('constructor-container-selected');

                if (node.zone.highlight())
                    result.push('constructor-container-zone');

                if (visible() || $root.settings.containers.hidden()) {
                    result.push('constructor-container-visible');
                } else {
                    result.push('constructor-container-hidden');
                }

                return result.join(' ');
            })(),
            style: (function () {
                if (simple()) {
                    return {
                        position: 'relative'
                    }
                } else {
                    return {
                        position: properties.position,
                        float: properties.float,
                        top: properties.top.sum,
                        right: properties.right.sum,
                        bottom: properties.bottom.sum,
                        left: properties.left.sum,
                        width: properties.width.summary,
                        minWidth: properties.width.min.summary,
                        maxWidth: properties.width.max.summary,
                        height: properties.height.summary,
                        minHeight: properties.height.min.summary,
                        maxHeight: properties.height.max.summary,
                        margin: properties.margin.calculated,
                        marginTop: properties.margin.top.calculated,
                        marginRight: properties.margin.right.calculated,
                        marginBottom: properties.margin.bottom.calculated,
                        marginLeft: properties.margin.left.calculated,
                        padding: properties.padding.summary,
                        paddingTop: properties.padding.top.calculated,
                        paddingRight: properties.padding.right.calculated,
                        paddingBottom: properties.padding.bottom.calculated,
                        paddingLeft: properties.padding.left.calculated,
                        backgroundColor: properties.background.color,
                        backgroundImage: properties.background.image.calculated,
                        backgroundRepeat: properties.background.repeat,
                        backgroundSize: properties.background.getSize(),
                        backgroundPosition: properties.background.position.calculated,
                        borderWidth: properties.border.width.summary,
                        borderStyle: properties.border.style,
                        borderColor: properties.border.color,
                        borderTopWidth: properties.border.top.width.calculated,
                        borderTopStyle: properties.border.top.style.calculated,
                        borderTopColor: properties.border.top.color.calculated,
                        borderRightWidth: properties.border.right.width.calculated,
                        borderRightStyle: properties.border.right.style.calculated,
                        borderRightColor: properties.border.right.color.calculated,
                        borderBottomWidth: properties.border.bottom.width.calculated,
                        borderBottomStyle: properties.border.bottom.style.calculated,
                        borderBottomColor: properties.border.bottom.color.calculated,
                        borderLeftWidth: properties.border.left.width.calculated,
                        borderLeftStyle: properties.border.left.style.calculated,
                        borderLeftColor: properties.border.left.color.calculated,
                        borderRadius: properties.border.radius.summary,
                        borderTopLeftRadius: properties.border.top.radius.calculated,
                        borderTopRightRadius: properties.border.right.radius.calculated,
                        borderBottomLeftRadius: properties.border.bottom.radius.calculated,
                        borderBottomRightRadius: properties.border.left.radius.calculated,
                    }
                }
            })(),
            visible: visible() || $root.settings.containers.hidden()
        }">
            <!-- ko if: !hasParent() -->
                <div class="constructor-guides-wrap" data-bind="with: $root.guides">
                    <div class="constructor-guides-columns-wrap" data-bind="{
                        fade: columns.active,
                        style: {
                           left: columns.space.minusSum,
                           right: columns.space.minusSum
                        }
                    }">
                        <div class="constructor-guides-columns" data-bind="{
                            style: { borderSpacing: columns.space.summary() + ' 0' },
                            foreach: new function () {
                               var arr = [],
                                   columns = $data.columns.count();

                               for (var i = 0; i < columns; i++)
                                   arr.push({});

                               return arr;
                            }
                        }">
                            <div class="constructor-guides-column"></div>
                        </div>
                    </div>
                    <!-- ko function:
                        $($parent.node()).off('resize.guides').on('resize.guides', function(){
                            rows.space.value.valueHasMutated();
                        });
                    --><!-- /ko -->
                    <div class="constructor-guides-rows" data-bind="{
                        fade: rows.active,
                        foreach: new function () {
                            var arr = [], count = 100;

                            if (rows.space.measure() == 'px')
                                count = $($parent.node()).height() / rows.space.value();

                            for (var i = 0; i < count; i++)
                                arr.push({});

                            return arr;
                        }
                    }">
                        <div class="constructor-guides-row" data-bind="{
                            style: { paddingTop: $parent.rows.space.summary }
                        }"></div>
                    </div>
                </div>
            <!-- /ko -->
            <!-- ko with: node.padding -->
                <!-- ko if: visible() && $root.settings.containers.structure() && !$parent.simple() -->
                    <div class="constructor-container-padding constructor-container-padding-top" data-bind="{
                        style: { height: top.getValue() }
                    }"></div>
                    <div class="constructor-container-padding constructor-container-padding-right" data-bind="{
                        style: {
                            width: right.getValue(),
                            marginTop: top.getValue(),
                            marginBottom: bottom.getValue()
                        }
                    }"></div>
                    <div class="constructor-container-padding constructor-container-padding-bottom" data-bind="{
                        style: { height: bottom.getValue() }
                    }"></div>
                    <div class="constructor-container-padding constructor-container-padding-left" data-bind="{
                        style: {
                            width: left.getValue(),
                            marginTop: top.getValue(),
                            marginBottom: bottom.getValue()
                        }
                    }"></div>
                    <div class="constructor-container-padding-border" data-bind="{
                        style: {
                            top: top.getValue(),
                            right: right.getValue(),
                            bottom: bottom.getValue(),
                            left: left.getValue()
                        }
                    }"></div>
                <!-- /ko -->
            <!-- /ko -->
            <!-- ko with: node.margin -->
                <!-- ko if: visible() && $root.settings.containers.structure() && !$parent.simple() -->
                    <div class="constructor-container-margin constructor-container-margin-top" data-bind="{
                        style: {
                            top: '-' + top.getValue() + 'px',
                            right: '-' + right.getIndent() + 'px',
                            left: '-' + left.getIndent() + 'px',
                            marginBottom: top.getIndent() + 'px'
                        }
                    }"></div>
                    <div class="constructor-container-margin constructor-container-margin-right" data-bind="{
                        style: {
                            right: '-' + right.getValue() + 'px',
                            top: '-' + top.getValue() + 'px',
                            bottom: '-' + bottom.getValue() + 'px',
                            marginLeft: right.getIndent() + 'px'
                        }
                    }"></div>
                    <div class="constructor-container-margin constructor-container-margin-bottom" data-bind="{
                        style: {
                            right: '-' + right.getIndent() + 'px',
                            bottom: '-' + bottom.getValue() + 'px',
                            left: '-' + left.getIndent() + 'px',
                            marginTop: bottom.getIndent() + 'px'
                        }
                    }"></div>
                    <div class="constructor-container-margin constructor-container-margin-left" data-bind="{
                        style: {
                            left: '-' + left.getValue() + 'px',
                            top: '-' + top.getValue() + 'px',
                            bottom: '-' + bottom.getValue() + 'px',
                            marginRight: left.getIndent() + 'px'
                        }
                    }"></div>
                <!-- /ko -->
            <!-- /ko -->
            <!-- ko if: hasVariator() -->
                <div class="constructor-container-variants">
                    <div class="constructor-container-variants-wrapper">
                        <!-- ko foreach: getVariator().getVariants() -->
                            <div class="constructor-container-variant" data-bind="{
                                css: selected() ? 'constructor-container-variant-selected' : null
                            }">
                                <div class="constructor-container-variant-wrapper">
                                    <div class="constructor-container-variant-name" data-bind="{
                                        text: name(),
                                        click: select
                                    }"></div>
                                </div>
                            </div>
                        <!-- /ko -->
                    </div>
                </div>
            <!-- /ko -->
            <div class="constructor-container-wrapper" data-bind="{
                style: {
                    top: properties.border.top.width.minusSum(),
                    right: properties.border.right.width.minusSum(),
                    bottom: properties.border.bottom.width.minusSum(),
                    left: properties.border.left.width.minusSum(),
                }
            }">
                <!-- ko with: node.size -->
                    <!-- ko if: visible() && $parent.hasParent() && !$parent.simple() -->
                        <!-- ko if: $parent.getParentType() == <?= JavaScript::toObject(Container::TYPE_ABSOLUTE) ?> -->
                            <div class="constructor-container-size constructor-container-size-top" data-bind="{
                                bind: top,
                                css: { 'constructor-container-size-drag': top.drag }
                            }"></div>
                            <div class="constructor-container-size constructor-container-size-top-right" data-bind="{
                                bind: tr,
                                css: { 'constructor-container-size-drag': tr.drag }
                            }"></div>
                            <div class="constructor-container-size constructor-container-size-top-left" data-bind="{
                                bind: tl,
                                css: { 'constructor-container-size-drag': tl.drag }
                            }"></div>
                            <div class="constructor-container-size constructor-container-size-left" data-bind="{
                                bind: left,
                                css: { 'constructor-container-size-drag': left.drag }
                            }"></div>
                            <div class="constructor-container-size constructor-container-size-bottom-left" data-bind="{
                                bind: bl,
                                css: { 'constructor-container-size-drag': bl.drag }
                            }"></div>
                        <!-- /ko -->
                        <div class="constructor-container-size constructor-container-size-right" data-bind="{
                            bind: right,
                            css: { 'constructor-container-size-drag': right.drag }
                        }"></div>
                        <div class="constructor-container-size constructor-container-size-bottom-right" data-bind="{
                            bind: br,
                            css: { 'constructor-container-size-drag': br.drag }
                        }"></div>
                        <div class="constructor-container-size constructor-container-size-bottom" data-bind="{
                            bind: bottom,
                            css: { 'constructor-container-size-drag': bottom.drag }
                        }"></div>
                    <!-- /ko -->
                <!-- /ko -->
            </div>
            <div class="constructor-container-panel-wrapper">
                <div class="constructor-container-panel constructor-clearfix">
                    <!-- ko if: node.shift.isAvailable() -->
                        <div class="constructor-container-panel-icon" title="<?= Loc::getMessage('container.panel.buttons.move') ?>" data-bind="{
                            bind: function (element) {
                                node.shift.node(element);
                                ko.models.tooltip()(element);
                            }
                        }" style="cursor: move;"></div>
                    <!-- /ko -->
                    <!-- ko if: hasBlock() -->
                        <div class="constructor-container-panel-buttons constructor-p-l-10">
                            <div class="constructor-grid constructor-grid-i-h-4">
                                <div class="constructor-grid-item-auto">
                                    <i class="constructor-container-panel-button fa fa-sync" title="<?= Loc::getMessage('container.panel.buttons.refresh') ?>" data-bind="{
                                        click: function () {
                                            getBlock().refresh();
                                        },
                                        bind: ko.models.tooltip()
                                    }"></i>
                                </div>
                                <!-- ko if: !getBlock().isEmpty() -->
                                    <div class="constructor-grid-item-auto">
                                        <a class="constructor-container-panel-button fa fa-pencil" title="<?= Loc::getMessage('container.panel.buttons.edit') ?>" target="_blank" data-bind="{
                                            attr: {
                                                'href': (function (api) {
                                                    return api.string.replace(<?= JavaScript::toObject($arUrlTemplates['builds.templates.blocks.editor']) ?>, {
                                                        '#block#': getBlock().id()
                                                    })
                                                })(intec)
                                            },
                                            bind: ko.models.tooltip()
                                        }"></a>
                                    </div>
                                <!-- /ko -->
                            </div>
                        </div>
                    <!-- /ko -->
                    <div class="constructor-container-panel-name" data-bind="{
                        text: (function(){
                            var result;

                            if (hasComponent()) {
                                result = '<?= Loc::getMessage('widget.component') ?>';
                            } else if (hasWidget()) {
                                result = getWidget().model().name();
                            } else if (hasBlock()) {
                                result = getBlock().name();
                            } else if (hasVariator()) {
                                result = '<?= Loc::getMessage('widget.variator') ?>';
                            }  else if (hasArea()) {
                                result = '<?= Loc::getMessage('widget.area') ?>' + getArea().name();
                            } else {
                                result = '<?= Loc::getMessage('widget.container') ?>';
                            }

                            return result;
                        })()
                    }"></div>
                    <div class="constructor-container-panel-buttons">
                        <div class="constructor-grid constructor-grid-i-h-4">
                            <div class="constructor-grid-item-auto">
                                <i class="constructor-container-panel-button constructor-icon-settings-2" title="<?= Loc::getMessage('container.panel.buttons.containerSettings') ?>" data-bind="{
                                    click: function () {
                                        var tabs = $root.menu.tabs;

                                        if (tabs.active.getLast() == tabs.list.container && $root.selected() == $data) {
                                            tabs.close(tabs.list.container);
                                        } else {
                                            tabs.open(tabs.list.container);
                                        }
                                    },
                                    bind: ko.models.tooltip()
                                }"></i>
                            </div>
                            <!-- ko if: hasWidget() || hasComponent() || hasBlock() || hasVariator() -->
                                <div class="constructor-grid-item-auto">
                                    <i class="constructor-container-panel-button fa fa-cog" title="<?= Loc::getMessage('container.panel.buttons.widgetSettings') ?>" data-bind="{
                                        click: function () {
                                            var tabs = $root.menu.tabs;
                                            var dialogs = $root.dialogs;

                                            if (hasComponent()) {
                                                dialogs.list.componentProperties.open(getComponent());
                                            } else if (hasWidget()) {
                                                if (tabs.list.widget.isActive() && $root.selected() == $data) {
                                                    tabs.list.widget.close();
                                                } else {
                                                    tabs.list.widget.open();
                                                }
                                            } else if (hasBlock()) {
                                                if (tabs.list.block.isActive() && $root.selected() == $data) {
                                                    tabs.list.block.close();
                                                } else {
                                                    tabs.list.block.open();
                                                }
                                            } else if (hasVariator()) {
                                                if (tabs.list.variator.isActive() && $root.selected() == $data) {
                                                    tabs.list.variator.close();
                                                } else {
                                                    tabs.list.variator.open();
                                                }
                                            }
                                        },
                                        bind: ko.models.tooltip()
                                    }"></i>
                                </div>
                            <!-- /ko -->
                        </div>
                    </div>
                </div>
            </div>
            <div class="constructor-container-wrap">
                <div class="constructor-container-wrap-2" data-bind="{
                    style: {
                        overflow: properties.overflow.value,
                        overflowX: properties.overflow.x.calculated,
                        overflowY: properties.overflow.y.calculated,
                        borderRadius: properties.border.radius.summary,
                        borderTopLeftRadius: properties.border.top.radius.calculated,
                        borderTopRightRadius: properties.border.right.radius.calculated,
                        borderBottomLeftRadius: properties.border.bottom.radius.calculated,
                        borderBottomRightRadius: properties.border.left.radius.calculated,
                        opacity: properties.getOpacity,
                        fontFamily: properties.text.font,
                        fontSize: properties.text.size.summary,
                        color: properties.text.color,
                        textTransform: properties.text.uppercase() ? 'uppercase' : null,
                        lineHeight: properties.text.lineHeight.summary,
                        letterSpacing: properties.text.letterSpacing.summary
                    }
                }">
                    <!-- ko if: $root.grid.show() && properties.grid.type() !== 'none' && type() == 'absolute' -->
                        <!-- ko function:
                            $(node()).off('resize.grid').on('resize.grid', function(){
                                properties.grid.width.valueHasMutated();
                                properties.grid.height.valueHasMutated();
                            });
                        --><!-- /ko -->
                        <div class="constructor-container-grid-wrap">
                            <div class="constructor-container-grid-rows" data-bind="{
                                foreach: new function () {
                                    var result = [];
                                    var top = 0;
                                    var measure = null;
                                    var count = 0;
                                    var step = 1;

                                    switch (properties.grid.type()) {
                                        case 'adaptive':
                                            measure = '%';
                                            count = properties.grid.y();
                                            step = (100 / count);
                                            break;
                                        case 'fixed':
                                            measure = 'px';
                                            step = properties.grid.height();
                                            count = $(node()).height() / step;
                                            break;
                                        default:
                                            return result;
                                    }

                                    for (var i = 1; i < count; i++) {
                                        top += step;
                                        result.push(top + measure);
                                    }

                                    return result;
                                }
                            }">
                                <div class="constructor-container-grid-row"
                                     data-bind="style: {top: $data}"
                                ></div>
                            </div>
                        </div>
                        <div class="constructor-container-grid-wrap">
                            <div class="constructor-container-grid-columns" data-bind="{
                                foreach: new function () {
                                    var result = [];
                                    var top = 0;
                                    var measure = null;
                                    var count = 0;
                                    var step = 1;

                                    switch (properties.grid.type()) {
                                        case 'adaptive':
                                            measure = '%';
                                            count = properties.grid.x();
                                            step = (100 / count);
                                            break;
                                        case 'fixed':
                                            measure = 'px';
                                            step = properties.grid.width();
                                            count = $(node()).width() / step;
                                            break;
                                        default:
                                            return result;
                                    }

                                    for (var i = 1; i < count; i++) {
                                        top += step;
                                        result.push(top + measure);
                                    }

                                    return result;
                                }
                            }">
                                <div class="constructor-container-grid-column"
                                     data-bind="style: {left: $data}"
                                ></div>
                            </div>
                        </div>
                    <!-- /ko -->
                    <!-- ko if: hasComponent() -->
                        <div class="constructor-container-content constructor-container-component" data-bind="{
                            with: element,
                            css: {
                                'constructor-container-content-refreshing': getElement().refreshing()
                            }
                        }">
                            <div class="constructor-container-view" data-bind="{
                                html: view
                            }"></div>
                            <!-- ko if: refreshing -->
                                <div class="constructor-container-loader">
                                    <div class="constructor-loader constructor-loader-1"></div>
                                </div>
                            <!-- /ko -->
                            <div class="constructor-container-locker"></div>
                        </div>
                    <!-- /ko -->
                    <!-- ko if: hasWidget() -->
                        <div class="constructor-container-content constructor-container-widget" data-bind="{
                            with: element
                        }">
                            <!-- ko with: structure -->
                                <div class="constructor-container-view" data-bind="{
                                    htmlTemplate: $parent.views.template
                                }" style="width: 100%; height: 100%;"></div>
                            <!-- /ko -->
                        </div>
                    <!-- /ko -->
                    <!-- ko if: hasBlock() -->
                        <div class="constructor-container-content constructor-container-block" data-bind="{
                            with: element,
                            css: {
                                'constructor-container-content-refreshing': getElement().refreshing() || getElement().isEmpty()
                            }
                        }">
                            <div class="constructor-container-view" data-bind="{
                                html: view
                            }"></div>
                            <!-- ko if: refreshing() || isEmpty() -->
                                <div class="constructor-container-loader">
                                    <div class="constructor-loader constructor-loader-1"></div>
                                </div>
                            <!-- /ko -->
                            <div class="constructor-container-locker"></div>
                        </div>
                    <!-- /ko -->
                    <!-- ko if: hasVariator() -->
                        <div class="constructor-container-content constructor-container-variator" data-bind="{
                            with: element
                        }">
                            <!-- ko if: getVariant() != null -->
                                <!-- ko template: {name: 'container', data: getVariant().container } --><!-- /ko -->
                            <!-- /ko -->
                        </div>
                    <!-- /ko -->
                    <!-- ko if: hasArea() -->
                        <div class="constructor-container-content constructor-container-area" data-bind="{
                            with: element,
                            css: {
                                'constructor-container-content-refreshing': getElement().refreshing()
                            }
                        }">
                            <!-- ko if: refreshing() -->
                                <div class="constructor-container-loader">
                                    <div class="constructor-loader constructor-loader-1"></div>
                                </div>
                            <!-- /ko -->
                            <!-- ko if: !refreshing() -->
                                <!-- ko template: {name: 'container', data: container } --><!-- /ko -->
                            <!-- /ko -->
                        </div>
                    <!-- /ko -->
                    <!-- ko if: !hasArea() && !hasComponent() && !hasWidget() && !hasBlock() && !hasVariator() -->
                        <!-- ko template: {name: 'container', foreach: containers.render } --><!-- /ko -->
                    <!-- /ko -->
                    <div class="constructor-clearfix"></div>
                </div>
            </div>
        </div>
    <!-- /ko -->
    <!-- ko if: $root.isElement($data) -->
        <!-- ko if: type() === 'indicator.position' -->
            <div class="constructor-container-indicator constructor-container-indicator-position"></div>
        <!-- /ko -->
    <!-- /ko -->
</script>