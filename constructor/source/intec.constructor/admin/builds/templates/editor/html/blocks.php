<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

/**
 * @var array $lang
 */
?>
<div class="constructor-additional-left-menu-wrapper" data-bind="
    fade: function(){ return menu.tabs.active.getLast() === menu.tabs.list.blocks && $root.blocks.categories.selected(); }">
    <div class="nano" data-bind="bind: $root.menu.scroll">
        <div class="nano-content">
            <!-- ko with: $root.blocks.categories.selected -->
                <div class="constructor-additional-left-menu constructor-additional-left-menu-blocks">
                    <!-- ko if: templates().length > 0 -->
                        <div data-bind="foreach: $data.templates">
                            <div class="constructor-additional-left-menu-block"
                                 data-bind="click: select">
                                <!-- ko if: image() !== null -->
                                    <div class="constructor-additional-left-menu-block-image"
                                         data-bind="style: { backgroundImage: 'url(' + image() + ')' }"></div>
                                <!-- /ko -->
                                <!-- ko if: image() === null -->
                                    <div class="constructor-additional-left-menu-block-image no-image"></div>
                                <!-- /ko -->
                                <div class="constructor-additional-left-menu-block-name"
                                     data-bind="text: name"></div>
                            </div>
                        </div>
                    <!-- /ko -->
                    <!-- ko if: templates().length <= 0 -->
                        <div class=""><?= GetMessage('container.blocks.elements.empty') ?></div>
                    <!-- /ko -->
                </div>
            <!-- /ko -->
        </div>
    </div>
</div>