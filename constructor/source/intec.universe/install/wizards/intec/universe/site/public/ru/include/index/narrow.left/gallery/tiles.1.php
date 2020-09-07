<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die(); ?>
<?php

use intec\core\collections\Arrays;
use intec\core\helpers\Html;
use intec\core\io\Path;

/**
 * @var Arrays $blocks
 * @var array $block
 * @var array $data
 * @var string $page
 * @var Path $path
 * @global CMain $APPLICATION
 */

?>
<?= Html::beginTag('div', ['style' => [
    'margin-bottom' => '50px'
]]) ?>
    <?php $APPLICATION->IncludeComponent(
        "intec.universe:widget",
        "photo",
        array(
            "IBLOCK_TYPE" => "#CONTENT_PHOTO_IBLOCK_TYPE#",
            "IBLOCK_ID" => "#CONTENT_PHOTO_IBLOCK_ID#",
            "SETTINGS_USE" => "Y",
            "LAZYLOAD_USE" => "N",
            "SHOW_TITLE" => "Y",
            "TITLE" => "Фотогалерея",
            "ALIGHT_HEADER" => "N",
            "SHOW_DETAIL_LINK" => "N",
            "USE_CAROUSEL" => "N",
            "COLUMNS_COUNT" => 4,
            "ITEMS_LIMIT" => 8,
            "CACHE_TYPE" => "A",
            "CACHE_TIME" => 3600000
        ),
        false
    ); ?>
<?= Html::endTag('div') ?>