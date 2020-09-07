<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use intec\core\helpers\JavaScript;
use intec\constructor\structure\Widgets;

/**
 * @var Widgets $widgets
 */
?>
<div class="constructor-menu constructor-menu-left" data-bind="{
    css: menu.tabs.isActive() ? 'active' : null
}">
    <div class="constructor-menu-wrap">
        <div class="nano" data-bind="bind: menu.scroll">
            <div class="nano-content">
                <?
                include(__DIR__.'/left/main.php');
                include(__DIR__.'/left/container.php');
                include(__DIR__.'/left/block.php');
                include(__DIR__.'/left/widget.php');
                include(__DIR__.'/left/text.php');
                include(__DIR__.'/left/scheme.php');
                include(__DIR__.'/left/display.php');
                include(__DIR__.'/left/blocks.php');
                include(__DIR__.'/left/guides.php');
                include(__DIR__.'/left/variator.php');
                ?>
            </div>
        </div>
    </div>
</div>