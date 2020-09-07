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
    'padding-top' => '50px',
    'padding-bottom' => '50px',
    'background-color' => '#f8f9fb'
]]) ?>
    <?php $APPLICATION->IncludeComponent(
        "intec.universe:main.widget",
        "products.2",
        array(
            "SETTINGS_USE" => "Y",
            "LAZYLOAD_USE" => "N",
            "IBLOCK_TYPE" => "#CATALOGS_PRODUCTS_IBLOCK_TYPE#",
            "IBLOCK_ID" => "#CATALOGS_PRODUCTS_IBLOCK_ID#",
            "MODE" => "all",
            "ACTION" => "buy",
            "PRICE_CODE" => array(
                "BASE"
			),
            "DISCOUNT_SHOW" => "Y",
            "SLIDER_USE" => "N",
            "TITLE_SHOW" => "N",
            "DESCRIPTION_SHOW" => "N",
            "COLUMNS" => 4,
            "SECTION_URL" => "",
            "DETAIL_URL" => "",
            "BASKET_URL" => "#SITE_DIR#personal/basket/",
            "CONSENT_URL" => "#SITE_DIR#company/consent/",
            "CACHE_TYPE" => "A",
            "CACHE_TIME" => 3600000,
            "FORM_ID" => "#FORMS_PRODUCT_ID#",
            "FORM_PROPERTY_PRODUCT" => "#FORMS_PRODUCT_FIELDS_PRODUCT_ID#",
            "DELAY_USE" => "Y",
            "OFFERS_LIMIT" => "0",
            "QUICK_VIEW_USE" => "Y",
            "QUICK_VIEW_DETAIL" => "N",
            "QUICK_VIEW_TEMPLATE" => 2,
            "QUICK_VIEW_PROPERTY_CODE" => array(
                "PROPERTY_TYPE",
                "PROPERTY_QUANTITY_OF_STRIPS",
                "PROPERTY_POWER",
                "PROPERTY_PROCREATOR",
                "PROPERTY_SCOPE",
                "PROPERTY_DISPLAY",
                "PROPERTY_WEIGTH",
                "PROPERTY_ENERGY_CONSUMPTION",
                "PROPERTY_SETTINGS",
                "PROPERTY_COMPOSITION",
                "PROPERTY_LENGTH",
                "PROPERTY_SEASON",
                "PROPERTY_PATTERN"
			),
            "QUICK_VIEW_PROPERTY_MARKS_HIT" => "HIT",
            "QUICK_VIEW_PROPERTY_MARKS_NEW" => "NEW",
            "QUICK_VIEW_PROPERTY_MARKS_RECOMMEND" => "RECOMMEND",
            "QUICK_VIEW_PROPERTY_PICTURES" => "PICTURES",
            "QUICK_VIEW_OFFERS_PROPERTY_PICTURES" => "PICTURES",
            "QUICK_VIEW_DELAY_USE" => "Y",
            "QUICK_VIEW_MARKS_SHOW" => "Y",
            "QUICK_VIEW_MARKS_ORIENTATION" => "horizontal",
            "QUICK_VIEW_GALLERY_PREVIEW" => "Y",
            "QUICK_VIEW_QUANTITY_SHOW" => "Y",
            "QUICK_VIEW_QUANTITY_MODE" => "number",
            "QUICK_VIEW_ACTION" => "buy",
            "QUICK_VIEW_COUNTER_SHOW" => "Y",
            "QUICK_VIEW_DESCRIPTION_SHOW" => "Y",
            "QUICK_VIEW_DESCRIPTION_MODE" => "preview",
            "QUICK_VIEW_PROPERTIES_SHOW" => "Y",
            "QUICK_VIEW_DETAIL_SHOW" => "Y",
            "PROPERTY_CATEGORY" => "CATEGORY",
            "PROPERTY_ORDER_USE" => "ORDER_USE",
            "PROPERTY_MARKS_HIT" => "HIT",
            "PROPERTY_MARKS_NEW" => "NEW",
            "PROPERTY_MARKS_RECOMMEND" => "RECOMMEND",
            "PROPERTY_PICTURES" => "PICTURES",
            "OFFERS_PROPERTY_PICTURES" => "PICTURES",
            "COMPARE_PATH" => "#SITE_DIR#catalog/compare.php",
            "COMPARE_NAME" => "compare",
            "SHOW_PRICE_COUNT" => "1",
            "TABS_ALIGN" => "left",
            "BORDERS" => "N",
            "BORDERS_STYLE" => "squared",
            "MARKS_SHOW" => "Y",
            "NAME_POSITION" => "middle",
            "NAME_ALIGN" => "left",
            "PRICE_ALIGN" => "start",
            "IMAGE_SLIDER_SHOW" => "Y",
            "COUNTER_SHOW" => "Y",
            "OFFERS_USE" => "Y",
            "OFFERS_ALIGN" => "left",
            "OFFERS_VIEW" => "default",
            "VOTE_SHOW" => "Y",
            "VOTE_ALIGN" => "left",
            "VOTE_MODE" => "rating",
            "QUANTITY_SHOW" => "Y",
            "QUANTITY_MODE" => "number",
            "QUANTITY_ALIGN" => "left",
            "USE_COMPARE" => "Y",
            "CONVERT_CURRENCY" => "N",
            "USE_PRICE_COUNT" => "N",
            "PRICE_VAT_INCLUDE" => "N"
        ),
        false
    ); ?>
<?= Html::endTag('div') ?>