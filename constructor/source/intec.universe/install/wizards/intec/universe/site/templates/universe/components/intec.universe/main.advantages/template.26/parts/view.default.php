<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use intec\core\helpers\Html;

/**
 * @var array $arVisual
 * @var array $arItem
 * @var Closure $vImages()
 */

?>
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
<?php $vImages($arItem) ?>
<?php if (!empty($arItem['DATA']['ADDITIONAL_TEXT'])) { ?>
    <?= Html::tag('div', $arItem['DATA']['ADDITIONAL_TEXT'], [
        'class' => 'widget-item-additional-text',
        'data' => [
            'narrow' => $arItem['DATA']['DEFAULT']['ADDITIONAL_TEXT']['NARROW'] ? 'true' : 'false'
        ]
    ]) ?>
<?php } ?>
