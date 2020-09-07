<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die(); ?>
<?php

/**
 * @global CMain $APPLICATION
 */

?>
<?php $APPLICATION->IncludeComponent(
    'intec.universe:sale.basket.small',
    'template.1',
    array(
        "SETTINGS_USE" => "Y",
        "PANEL_SHOW" => "Y",
        "COMPARE_SHOW" => "Y",
        "COMPARE_CODE" => "compare",
        "COMPARE_IBLOCK_TYPE" => "#PRODUCTS_IBLOCK_TYPE#",
        "COMPARE_IBLOCK_ID" => "#PRODUCTS_IBLOCK_ID#",
        "AUTO" => "Y",
        "FORM_ID" => "#FORMS_CALL_ID#",
        "FORM_TITLE" => "Заказать звонок",
        "BASKET_SHOW" => "Y",
        "FORM_SHOW" => "Y",
        "PERSONAL_SHOW" => "Y",
        "DELAYED_SHOW" => "Y",
        "CATALOG_URL" => "#SITE_DIR#catalog/",
        "BASKET_URL" => "#SITE_DIR#personal/basket/",
        "ORDER_URL" => "#ORDER_PAGE_URL#",
        "COMPARE_URL" => "#SITE_DIR#catalog/compare.php",
        "PERSONAL_URL" => "#SITE_DIR#personal/profile/",
        "CONSENT_URL" => "#SITE_DIR#company/consent/"
    ),
    false,
    array()
); ?>