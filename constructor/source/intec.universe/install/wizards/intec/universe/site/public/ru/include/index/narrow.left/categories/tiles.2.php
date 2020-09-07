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
    <? $APPLICATION->IncludeComponent(
        "intec.universe:main.categories",
        "template.1",
        array(
            "IBLOCK_TYPE" => "#CONTENT_BANNERS_CATEGORIES_IBLOCK_TYPE#",
            "IBLOCK_ID" => "#CONTENT_BANNERS_CATEGORIES_IBLOCK_ID#",
            "SECTIONS_MODE" => "id",
            "ELEMENTS_COUNT" => "3",
            "LINK_MODE" => "property",
            "SETTINGS_USE" => "Y",
            "LAZYLOAD_USE" => "N",
            "PROPERTY_LINK" => "LINK",
            "HEADER_SHOW" => "Y",
            "HEADER_POSITION" => "left",
            "HEADER_TEXT" => "Рубрики",
            "DESCRIPTION_SHOW" => "N",
            "COLUMNS" => 3,
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