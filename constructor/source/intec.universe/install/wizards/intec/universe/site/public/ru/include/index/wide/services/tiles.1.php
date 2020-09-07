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
        "intec.universe:main.services",
        "template.9",
        array(
            "IBLOCK_TYPE" => "#CATALOGS_SERVICES_IBLOCK_TYPE#",
            "IBLOCK_ID" => "#CATALOGS_SERVICES_IBLOCK_ID#",
            "ELEMENTS_COUNT" => "6",
            "SETTINGS_USE" => "Y",
            "LAZYLOAD_USE" => "N",
            "HEADER_BLOCK_SHOW" => "Y",
            "HEADER_BLOCK_POSITION" => "center",
            "HEADER_BLOCK_TEXT" => "Услуги",
            "DESCRIPTION_BLOCK_SHOW" => "N",
            "COLUMNS" => 3,
            "LINK_USE" => "Y",
            "FOOTER_SHOW" => "N",
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