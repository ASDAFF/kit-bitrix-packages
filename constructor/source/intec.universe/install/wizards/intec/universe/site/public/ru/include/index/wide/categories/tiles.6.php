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
    <? $APPLICATION->IncludeComponent(
        "intec.universe:main.categories",
        "template.13",
        array(
            "IBLOCK_TYPE" => "#CONTENT_BANNERS_CATEGORIES_IBLOCK_TYPE#",
            "IBLOCK_ID" => "#CONTENT_BANNERS_CATEGORIES_IBLOCK_ID#",
            "SECTIONS_MODE" => "id",
            "ELEMENTS_COUNT" => "4",
            "LINK_MODE" => "property",
            "SETTINGS_USE" => "Y",
            "LAZYLOAD_USE" => "N",
            "PROPERTY_LINK" => "LINK",
            "HEADER_SHOW" => "N",
            "DESCRIPTION_SHOW" => "N",
            "COLUMNS" => 4,
            "WIDE" => "Y",
            "PREVIEW_SHOW" => "Y",
            "LINK_USE" => "Y",
            "LINK_BLANK" => "Y",
            "CACHE_TYPE" => "A",
            "CACHE_TIME" => 3600000,
            "SORT_BY" => "SORT",
            "SORT_ORDER" => "ASC"
        ),
        false
    ); ?>
<?= Html::endTag('div') ?>