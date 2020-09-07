<?php

use Bitrix\Main\Localization\Loc;

?>
<div class="constructor-dialog constructor-dialog-component-properties constructor-component-properties" data-bind="{
    bind: dialogs.list.componentList,
    with: dialogs.list
}">
    <div class="constructor-dialog-header">
        <div class="constructor-dialog-header-wrapper">
            <div class="constructor-dialog-title">
                <?= Loc::getMessage('container.modals.component.list.title') ?>
            </div>
            <div class="constructor-dialog-content">
                <div class="constructor-dialog-search">
                    <div class="constructor-icon search-icon"></div>
                    <input
                        type="text"
                        class="constructor-dialog-search-input"
                        placeholder="<?= Loc::getMessage('container.modals.gallery.search') ?>"
                        data-bind="{
                            value: componentList.data.filter,
                            valueUpdate: 'keyup'
                        }"
                    />
                </div>
            </div>
            <div class="constructor-dialog-buttons">
                <button class="constructor-dialog-button constructor-icon-window" data-bind="{
                    click: componentList.expanded.switch
                }"></button>
                <button class="constructor-dialog-button constructor-icon-cancel" data-bind="{
                    click: componentList.close
                }"></button>
            </div>
        </div>
    </div>
    <div class="constructor-dialog-body" data-bind="{
        with: componentList.data
    }">
        <div class="constructor-dialog-loader" data-bind="{
            visible: updating
        }">
            <div class="constructor-loader constructor-loader-1"></div>
        </div>
        <div class="component-list" data-bind="{
            visible: !updating()
        }">
            <div class="component-list-content">
                <!-- ko foreach: sections -->
                    <div class="component-list-section component-list-root-section">
                        <div class="component-list-section-name component-list-root-section-name" data-bind="{
                            text: name() || code(),
                            css: {
                                'component-list-section-active': active
                            },
                            click: function () {
                                active(!active());
                            }
                        }"></div>
                        <div class="component-list-section-structure" data-bind="{
                            if: active
                        }">
                            <div class="component-list-section-sections" data-bind="{
                                template: {
                                    name: 'component-list-section',
                                    foreach: sections.visible
                                }
                            }"></div>
                            <ul class="component-list-section-components component-list-component-ul" data-bind="{
                                template: {
                                    name: 'component-list-component',
                                    foreach: components.visible
                                }
                            }"></ul>
                        </div>
                    </div>
                <!-- /ko -->
            </div>
        </div>
        <script id="component-list-section" type="text/html">
            <div class="component-list-section">
                <div class="component-list-section-name component-list-child-section-name" data-bind="{
                    text: name() || code(),
                    css: {
                        'component-list-section-active': active
                    },
                    click: function () {
                        active(!active());
                    }
                }"></div>
                <div class="component-list-section-structure" data-bind="{
                    if: active
                }">
                    <div class="component-list-section-sections" data-bind="{
                        template: {
                            name: 'component-list-section',
                            foreach: sections.visible
                        }
                    }"></div>
                    <ul class="component-list-section-components component-list-component-ul" data-bind="{
                        template: {
                            name: 'component-list-component',
                            foreach: components.visible
                        }
                    }"></ul>
                </div>
            </div>
        </script>
        <script id="component-list-component" type="text/html">
            <li class="component-list-component-name" data-bind="{
                css: {
                    'component-list-component-active': selected
                },
                click: select
            }">
                <div class="component-list-marker"></div>
                <div class="component-list-component-name-value" data-bind="{
                    text: name
                }"></div>
            </li>
        </script>
    </div>
    <div class="constructor-dialog-footer">
        <div class="constructor-dialog-footer-wrapper">
            <div class="constructor-button constructor-button-s-3 constructor-button-c-blue constructor-save-button" data-bind="{
                click: componentList.save
            }"><?= Loc::getMessage('container.modals.component.list.buttons.save') ?></div>
            <div class="constructor-button constructor-button-s-3 constructor-button-c-blue-t-c" data-bind="{
                click: componentList.close
            }"><?= Loc::getMessage('container.modals.component.list.buttons.cancel') ?></div>
        </div>
    </div>
</div>