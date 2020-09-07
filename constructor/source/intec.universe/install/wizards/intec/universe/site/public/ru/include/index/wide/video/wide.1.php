<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die(); ?>
<?php

use intec\core\collections\Arrays;
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
<?php $APPLICATION->IncludeComponent(
    "intec.universe:main.video",
    "template.1",
    array(
        "IBLOCK_TYPE" => "#CONTENT_VIDEO_IBLOCK_TYPE#",
        "IBLOCK_ID" => "#CONTENT_VIDEO_IBLOCK_ID#",
        "SECTIONS_MODE" => "id",
        "ELEMENTS_MODE" => "code",
        "ELEMENT" => "video_1",
        "SETTINGS_USE" => "Y",
        "LAZYLOAD_USE" => "N",
        "PROPERTY_LINK" => "LINK",
        "HEADER_SHOW" => "N",
        "DESCRIPTION_SHOW" => "N",
        "WIDE" => "Y",
        "HEIGHT" => 500,
        "FADE" => "N",
        "SHADOW_USE" => "N",
        "THEME" => "light",
        "PARALLAX_USE" => "N",
        "SORT_BY" => "SORT",
        "ORDER_BY" => "ASC"
    ),
    false
); ?>