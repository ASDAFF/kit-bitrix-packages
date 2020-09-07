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
        "intec.universe:main.sections",
        "template.2",
        array(
            "IBLOCK_TYPE" => "#CATALOGS_PRODUCTS_IBLOCK_TYPE#",
            "IBLOCK_ID" => "#CATALOGS_PRODUCTS_IBLOCK_ID#",
            "QUANTITY" => "N",
            "SECTIONS_MODE" => "id",
            "DEPTH" => 2,
            "ELEMENTS_COUNT" => "4",
            "SETTINGS_USE" => "Y",
            "LAZYLOAD_USE" => "N",
            "HEADER_SHOW" => "Y",
            "HEADER_POSITION" => "left",
            "HEADER_TEXT" => "Популярные категории",
            "DESCRIPTION_SHOW" => "N",
            "LINE_COUNT" => 2,
            "SUB_SECTIONS_SHOW" => "Y",
            "SUB_SECTIONS_MAX" => 3,
            "SECTION_URL" => "",
            "CACHE_TYPE" => "A",
            "CACHE_TIME" => 3600000,
            "SORT_BY" => "SORT",
            "ORDER_BY" => "ASC"
        ),
        false
    ); ?>
<?= Html::endTag('div') ?>