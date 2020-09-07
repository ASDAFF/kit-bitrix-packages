<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use intec\core\helpers\Html;

/**
 * @var array $arItem
 * @var array $arVisual
 * @var Closure $vImages()
 */

?>
<div class="widget-item-main-content intec-grid intec-grid-i-h-15 intec-grid-1024-wrap">
    <div class="widget-item-compact-text intec-grid-item-auto intec-grid-item-1024-1">
        <div class="widget-item-name">
            <?php if (!empty($arItem['DATA']['NAME'])) { ?>
                <?= Html::stripTags($arItem['DATA']['NAME'], ['br']) ?>
            <?php } else { ?>
                <?= $arItem['NAME'] ?>
            <?php } ?>
        </div>
        <?php if (!empty($arItem['PREVIEW_TEXT'])) { ?>
            <div class="widget-item-preview">
                <?= $arItem['PREVIEW_TEXT'] ?>
            </div>
        <?php } ?>
    </div>
    <?php if (
        ($arVisual['IMAGES']['SHOW'] && !empty($arItem['DATA']['IMAGES'])) ||
        !empty($arItem['PREVIEW_PICTURE']) || !empty($arItem['DETAIL_PICTURE'])
    ) { ?>
        <div class="intec-grid-item intec-grid-item-1024-1">
            <?php $vImages($arItem) ?>
        </div>
    <?php } ?>
</div>
