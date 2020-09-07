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
    'background-color' => '#f8f9fb',
    'padding-top' => '40px',
    'padding-bottom' => '40px'
]]) ?>
    <?php $APPLICATION->IncludeComponent(
        "intec.universe:widget",
        "icons",
        array(
            "IBLOCK_TYPE" => "#CONTENT_ICONS_IBLOCK_TYPE#",
            "IBLOCK_ID" => "#CONTENT_ICONS_IBLOCK_ID#",
            "SECTIONS_ID" => array(),
            "ELEMENTS_ID" => array(),
            "ELEMENTS_COUNT" => "4",
            "SHOW_HEADER" => "N",
            "HEADER" => "",
            "HEADER_POSITION" => "center",
            "LINE_ELEMENTS_COUNT" => "4",
            "VIEW" => "left-float",
            "CACHE_TYPE" => "A",
            "CACHE_TIME" => "3600000",
            "PROPERTY_USE_LINK" => "USE_LINK",
            "PROPERTY_LINK" => "LINK",
            "FONT_SIZE_HEADER" => "14",
            "FONT_STYLE_HEADER" => "italic",
            "FONT_STYLE_HEADER_BOLD" => "N",
            "FONT_STYLE_HEADER_ITALIC" => "N",
            "FONT_STYLE_HEADER_UNDERLINE" => "N",
            "HEADER_TEXT_POSITION" => "left",
            "HEADER_TEXT_COLOR" => "",
            "BACKGROUND_COLOR_ICON" => "",
            "BACKGROUND_OPACITY_ICON" => "",
            "BACKGROUND_BORDER_RADIUS" => "",
            "TARGET_BLANK" => "N",
            "FONT_SIZE_DESCRIPTION" => "14",
            "FONT_STYLE_DESCRIPTION_BOLD" => "N",
            "FONT_STYLE_DESCRIPTION_ITALIC" => "N",
            "FONT_STYLE_DESCRIPTION_UNDERLINE" => "N",
            "DESCRIPTION_TEXT_POSITION" => "left",
            "DESCRIPTION_TEXT_COLOR" => ""
        ),
        false
    ); ?>
<?= Html::endTag('div') ?>