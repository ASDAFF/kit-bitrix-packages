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
        "intec.universe:main.slider",
        "template.3",
        array(
            "IBLOCK_TYPE" => "#CONTENT_BANNERS_IBLOCK_TYPE#",
            "IBLOCK_ID" => "#CONTENT_BANNERS_IBLOCK_ID#",
            "ELEMENTS_COUNT" => "",
            "SECTIONS_MODE" => "id",
            "LAZYLOAD_USE" => "Y",
            "BLOCKS_USE" => "Y",
            "BLOCKS_IBLOCK_TYPE" => "#CONTENT_BANNERS_SMALL_IBLOCK_TYPE#",
            "BLOCKS_IBLOCK_ID" => "#CONTENT_BANNERS_SMALL_IBLOCK_ID#",
            "BLOCKS_MODE" => "N",
            "BLOCKS_ELEMENTS_COUNT" => "2",
            "PROPERTY_HEADER" => "TITLE",
            "PROPERTY_DESCRIPTION" => "DESCRIPTION",
            "PROPERTY_HEADER_OVER" => "HEADER_OVER",
            "PROPERTY_LINK" => "LINK",
            "PROPERTY_LINK_BLANK" => "LINK_BLANK",
            "PROPERTY_BUTTON_SHOW" => "BUTTON_SHOW",
            "PROPERTY_BUTTON_TEXT" => "BUTTON_TEXT",
            "PROPERTY_TEXT_ALIGN" => "TEXT_ALIGN",
            "PROPERTY_FADE" => "BACKGROUND_FADE",
            "PROPERTY_SCHEME" => "TEXT_DARK",
            "PROPERTY_VIDEO" => "BACKGROUND_VIDEO",
            "PROPERTY_VIDEO_FILE_MP4" => "BACKGROUND_VIDEO_FILE_MP4",
            "PROPERTY_VIDEO_FILE_WEBM" => "BACKGROUND_VIDEO_FILE_WEBM",
            "PROPERTY_VIDEO_FILE_OGV" => "BACKGROUND_VIDEO_FILE_OGV",
            "BLOCKS_PROPERTY_LINK" => "LINK",
            "BLOCKS_PROPERTY_LINK_BLANK" => "LINK_BLANK",
            "WIDE" => "N",
            "HEIGHT" => "450px",
            "HEADER_SHOW" => "Y",
            "HEADER_VIEW" => "1",
            "DESCRIPTION_SHOW" => "Y",
            "DESCRIPTION_VIEW" => "1",
            "HEADER_OVER_SHOW" => "Y",
            "HEADER_OVER_VIEW" => "1",
            "BUTTON_VIEW" => "1",
            "VIDEO_SHOW" => "Y",
            "BLOCKS_POSITION" => "right",
            "BLOCKS_EFFECT_FADE" => "Y",
            "BLOCKS_EFFECT_SCALE" => "Y",
            "BLOCKS_INDENT" => "Y",
            "ROUNDED" => "Y",
            "SLIDER_NAV_SHOW" => "Y",
            "SLIDER_NAV_VIEW" => "1",
            "SLIDER_DOTS_SHOW" => "Y",
            "SLIDER_DOTS_VIEW" => "1",
            "SLIDER_LOOP" => "N",
            "SLIDER_AUTO_USE" => "N",
            "CACHE_TYPE" => "A",
            "CACHE_TIME" => "3600000",
            "SORT_BY" => "SORT",
            "ORDER_BY" => "ASC"
        ),
        false
    ); ?>
<?= Html::endTag('div') ?>