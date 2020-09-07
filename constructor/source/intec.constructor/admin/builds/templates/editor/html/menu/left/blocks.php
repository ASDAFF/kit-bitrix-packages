<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

/**
 * @var array $lang
 */
?>
<div data-bind="{
    fade: function () { return menu.tabs.list.blocks.isActive() }
}">
    <div class="constructor-form constructor-blocks-menu-wrapper no-select">
        <div class="constructor-form-wrap">
            <div class="constructor-menu-left-title">
                <?= GetMessage('container.blocks') ?>
            </div>
            <!-- ko if: $root.blocks.categories().length > 0 -->
                <div class="constructor-row" data-bind="foreach: $root.blocks.categories">
                    <div class="constructor-blocks-group" data-bind="{
                        visible: templates().length > 0,
                        css: isSelected() ? 'active' : '',
                        click: toggle
                    }">
                        <span class="constructor-blocks-group-name" data-bind="text: name"></span>
                        <i class="typcn typcn-arrow-right"></i>
                    </div>
                </div>
            <!-- /ko -->
            <!-- ko if: $root.blocks.categories().length <= 0 -->
                <div class="constructor-row" style="padding-left: 15px;">
                    <?= GetMessage('container.blocks.groups.empty') ?>
                </div>
            <!-- /ko -->
        </div>
    </div>
</div>
