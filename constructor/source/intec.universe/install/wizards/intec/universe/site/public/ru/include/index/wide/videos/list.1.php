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
    'margin-top' => '50px',
    'margin-bottom' => '50px'
]]) ?>
    <?php $APPLICATION->IncludeComponent(
        "intec.universe:main.videos",
        "template.2",
        array(
            "IBLOCK_TYPE" => "#CONTENT_VIDEO_IBLOCK_TYPE#",
            "IBLOCK_ID" => "#CONTENT_VIDEO_IBLOCK_ID#",
            "SECTIONS_MODE" => "id",
            "ELEMENTS_COUNT" => "",
            "PICTURE_SOURCES" => array(
                "service",
                "preview",
                "detail"
            ),
            "PICTURE_SERVICE_QUALITY" => "sddefault",
            "SETTINGS_USE" => "Y",
            "LAZYLOAD_USE" => "N",
            "PROPERTY_URL" => "LINK",
            "HEADER_SHOW" => "Y",
            "HEADER_POSITION" => "center",
            "HEADER" => "Видеогалерея",
            "DESCRIPTION_SHOW" => "N",
            "FOOTER_SHOW" => "N",
            "CACHE_TYPE" => "A",
            "CACHE_TIME" => 3600000,
            "SORT_BY" => "SORT",
            "ORDER_BY" => "ASC"
        ),
        false
    ); ?>
<?= Html::endTag('div') ?>