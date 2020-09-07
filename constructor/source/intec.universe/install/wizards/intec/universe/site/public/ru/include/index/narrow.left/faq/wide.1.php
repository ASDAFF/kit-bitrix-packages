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
    'margin-bottom' => '100px'
]]) ?>
    <?php $APPLICATION->IncludeComponent(
        "intec.universe:main.faq",
        "template.1",
        array(
            "IBLOCK_TYPE" => "#CONTENT_FAQ_IBLOCK_TYPE#",
            "IBLOCK_ID" => "#CONTENT_FAQ_IBLOCK_ID#",
            "SECTIONS_MODE" => "id",
            "HEADER_SHOW" => "Y",
            "HEADER_POSITION" => "center",
            "HEADER_TEXT" => "Вопрос - ответ",
            "DESCRIPTION_SHOW" => "N",
            "BY_SECTION" => "N",
            "ELEMENT_TEXT_ALIGN" => "center",
            "FOOTER_SHOW" => "N",
            "CACHE_TYPE" => "A",
            "CACHE_TIME" => 3600000,
            "SORT_BY" => "SORT",
            "ORDER_BY" => "ASC"
        ),
        false
    ); ?>
<?= Html::endTag('div') ?>