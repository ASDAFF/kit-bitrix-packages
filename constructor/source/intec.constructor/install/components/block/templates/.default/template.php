<?php

use intec\Core;
use intec\constructor\models\block\Template;
use intec\constructor\structure\Block;
use intec\constructor\structure\block\Element;

/**
 * @var array $arParams
 * @var array $arResult
 */

/** @var Template $template */
$template = $arResult['TEMPLATE'];
/** @var Block $block */
$block = $arResult['BLOCK'];

if (!empty($block)) {
    $block->includeHeaders();

    echo $block->render();
}