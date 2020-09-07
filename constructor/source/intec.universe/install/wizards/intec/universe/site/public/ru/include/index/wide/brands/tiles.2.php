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
<?= Html::beginTag('div') ?>
    <?php $APPLICATION->IncludeComponent(
        "intec.universe:main.brands",
        "template.2",
        array(
            "IBLOCK_TYPE" => "#CONTENT_BRANDS_IBLOCK_TYPE#",
            "IBLOCK_ID" => "#CONTENT_BRANDS_IBLOCK_ID#",
            "SECTIONS_MODE" => "id",
            "ELEMENTS_COUNT" => "5",
            "SETTINGS_USE" => "Y",
            "LAZYLOAD_USE" => "N",
            "HEADER_SHOW" => "Y",
            "HEADER_POSITION" => "center",
            "HEADER_TEXT" => "Нам доверяют",
            "DESCRIPTION_SHOW" => "N",
            "COLUMNS" => 5,
            "LINK_USE" => "Y",
            "BACKGROUND_USE" => "Y",
            "BACKGROUND_THEME" => "light",
            "OPACITY" => "50",
            "GRAYSCALE" => "N",
            "FOOTER_SHOW" => "Y",
            "FOOTER_POSITION" => "center",
            "FOOTER_BUTTON_SHOW" => "N",
            "LIST_PAGE_URL" => "",
            "SECTION_URL" => "",
            "DETAIL_URL" => "",
            "CACHE_TYPE" => "A",
            "CACHE_TIME" => 3600000,
            "SORT_BY" => "SORT",
            "ORDER_BY" => "ASC"
        ),
        false
    ); ?>
<?= Html::endTag('div') ?>