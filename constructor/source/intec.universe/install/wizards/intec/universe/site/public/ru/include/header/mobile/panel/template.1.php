<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die(); ?>
<?php

/**
 * @global CMain $APPLICATION
 */

?>
<?php $APPLICATION->IncludeComponent(
    'intec.universe:sale.basket.small',
    'panel.1',
    array(
        "COMPARE_SHOW" => "Y",
        "COMPARE_CODE" => "compare",
        "COMPARE_IBLOCK_TYPE" => "#CATALOGS_PRODUCTS_IBLOCK_TYPE#",
        "COMPARE_IBLOCK_ID" => "#CATALOGS_PRODUCTS_IBLOCK_ID#",
        "SETTINGS_USE" => "Y",
        "FORM_ID" => "#FORMS_CALL_ID#",
        "BASKET_SHOW" => "Y",
        "FORM_SHOW" => "Y",
        "PERSONAL_SHOW" => "Y",
        "FORM_TITLE" => "Заказать звонок",
        "DELAYED_SHOW" => "Y",
        "CATALOG_URL" => "#SITE_DIR#catalog/",
        "BASKET_URL" => "#SITE_DIR#personal/basket/",
        "ORDER_URL" => "#SITE_DIR#personal/basket/order.php",
        "COMPARE_URL" => "#SITE_DIR#catalog/compare.php",
        "PERSONAL_URL" => "#SITE_DIR#personal/profile/",
        "CONSENT_URL" => "#SITE_DIR#company/consent/",
        "REGISTER_URL" => "#SITE_DIR#personal/profile/",
        "FORGOT_PASSWORD_URL" => "#SITE_DIR#personal/profile/?forgot_password=yes",
        "PROFILE_URL" => "#SITE_DIR#personal/profile/"
    ),
    false,
    array()
); ?>