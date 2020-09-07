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
        "intec.universe:main.advantages",
        "template.2",
        array(
            "IBLOCK_TYPE" => "#CONTENT_ADVANTAGES_IBLOCK_TYPE#",
            "IBLOCK_ID" => "#CONTENT_ADVANTAGES_IBLOCK_ID#",
            "SECTIONS_MODE" => "id",
            "ELEMENTS_COUNT" => "3",
            "LINE_COUNT" => 3,
            "SETTINGS_USE" => "Y",
            "LAZYLOAD_USE" => "N",
            "HEADER_SHOW" => "Y",
            "HEADER_POSITION" => "left",
            "HEADER" => "Преимущества",
            "DESCRIPTION_SHOW" => "N",
            "VIEW" => "number",
            "CACHE_TYPE" => "A",
            "CACHE_TIME" => 3600000,
            "SORT_BY" => "SORT",
            "ORDER_BY" => "ASC"
        ),
        false
    ); ?>
<?= Html::endTag('div') ?>