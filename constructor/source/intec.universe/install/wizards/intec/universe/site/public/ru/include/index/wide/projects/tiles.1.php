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
    'margin-top' => '50px'
]]) ?>
    <?php $APPLICATION->IncludeComponent(
        "intec.universe:main.projects",
        "template.2",
        array(
            "IBLOCK_TYPE" => "#CONTENT_PROJECTS_IBLOCK_TYPE#",
            "IBLOCK_ID" => "#CONTENT_PROJECTS_IBLOCK_ID#",
            "SECTIONS_MODE" => "id",
            "ELEMENTS_COUNT" => "",
            "HEADER_SHOW" => "Y",
            "HEADER_POSITION" => "center",
            "HEADER_TEXT" => "Реализованные проекты",
            "DESCRIPTION_SHOW" => "N",
            "WIDE" => "Y",
            "COLUMNS" => 5,
            "TABS_USE" => "Y",
            "TABS_POSITION" => "center",
            "LINK_USE" => "Y",
            "FOOTER_SHOW" => "Y",
            "FOOTER_POSITION" => "center",
            "FOOTER_BUTTON_SHOW" => "Y",
            "FOOTER_BUTTON_TEXT" => "Показать все",
            "LIST_PAGE_URL" => "",
            "SECTION_URL" => "",
            "DETAIL_URL" => "",
            "CACHE_TYPE" => "A",
            "CACHE_TIME" => "3600000",
            "SORT_BY" => "SORT",
            "ORDER_BY" => "DESC"
        ),
        false
    ); ?>
<?= Html::endTag('div') ?>