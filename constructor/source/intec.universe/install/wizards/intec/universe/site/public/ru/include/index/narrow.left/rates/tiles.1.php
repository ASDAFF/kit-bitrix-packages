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
        "intec.universe:main.rates",
        "template.1",
        array(
            "IBLOCK_TYPE" => "#CONTENT_RATES_IBLOCK_TYPE#",
            "IBLOCK_ID" => "#CONTENT_RATES_IBLOCK_ID#",
            "ELEMENTS_COUNT" => "6",
            "PROPERTY_LIST" => array(
                "PROPERTY_PRODUCT_COUNT",
                "PROPERTY_PHOTO_COUNT",
                "PROPERTY_DOCUMENTS_COUNT",
                "PROPERTY_DISK_SPACE"
            ),
            "PROPERTY_PRICE" => "PRICE",
            "PROPERTY_CURRENCY" => "CURRENCY",
            "PROPERTY_DISCOUNT" => "DISCOUNT",
            "PROPERTY_DISCOUNT_TYPE" => "",
            "PROPERTY_DETAIL_URL" => "",
            "HEADER_SHOW" => "Y",
            "HEADER_POSITION" => "center",
            "HEADER_TEXT" => "Тарифы",
            "DESCRIPTION_SHOW" => "N",
            "COLUMNS" => 3,
            "VIEW" => "tabs",
            "TABS_POSITION" => "center",
            "SECTION_DESCRIPTION_SHOW" => "Y",
            "SECTION_DESCRIPTION_POSITION" => "center",
            "COUNTER_SHOW" => "Y",
            "COUNTER_TEXT" => "ТАРИФ",
            "PRICE_SHOW" => "Y",
            "DISCOUNT_SHOW" => "Y",
            "PREVIEW_SHOW" => "Y",
            "PROPERTIES_SHOW" => "Y",
            "BUTTON_SHOW" => "N",
            "SLIDER_USE" => "N",
            "CACHE_TYPE" => "A",
            "CACHE_TIME" => 3600000,
            "SORT_BY" => "SORT",
            "ORDER_BY" => "ASC"
        ),
        false
    ); ?>
<?= Html::endTag('div') ?>