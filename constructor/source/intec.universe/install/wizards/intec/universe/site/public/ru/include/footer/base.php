<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die(); ?>
<?php

/**
 * @global CMain $APPLICATION
 */

?>
<?php $APPLICATION->IncludeComponent(
    "intec.universe:main.footer",
    "template.1",
    array(
        "SETTINGS_USE" => "Y",
        "PRODUCTS_VIEWED_SHOW" => "Y",
        "PRODUCTS_VIEWED_LAZYLOAD_USE" => "N",
        "REGIONALITY_USE" => "N",
        "CONTACTS_USE" => "Y",
        "CONTACTS_IBLOCK_TYPE" => "#CONTENT_CONTACTS_IBLOCK_TYPE#",
        "CONTACTS_IBLOCK_ID" => "#CONTENT_CONTACTS_IBLOCK_ID#",
        "CONTACTS_REGIONALITY_USE" => "Y",
        "CONTACTS_REGIONALITY_STRICT" => "N",
        "MENU_MAIN_ROOT" => "bottom",
        "MENU_MAIN_CHILD" => "left",
        "MENU_MAIN_LEVEL" => 4,
        "SEARCH_NUM_CATEGORIES" => 1,
        "SEARCH_TOP_COUNT" => 5,
        "SEARCH_ORDER" => "date",
        "SEARCH_USE_LANGUAGE_GUESS" => "Y",
        "SEARCH_CHECK_DATES" => "N",
        "SEARCH_SHOW_OTHERS" => "N",
        "SEARCH_INPUT_ID" => "footer-search",
        "SEARCH_TIPS_USE" => "N",
        "SEARCH_MODE" => "site",
        "LOGOTYPE_PATH" => "#SITE_DIR#include/logotype.php",
        "CONTACTS_PROPERTY_CITY" => "CITY",
        "CONTACTS_PROPERTY_ADDRESS" => "ADDRESS",
        "CONTACTS_PROPERTY_PHONE" => "PHONE",
        "CONTACTS_PROPERTY_EMAIL" => "EMAIL",
        "CONTACTS_PROPERTY_REGION" => "REGIONS",
        "PHONE_VALUE" => "#SITE_PHONE#",
        "PRODUCTS_VIEWED_IBLOCK_MODE" => "multi",
        "ADDRESS_VALUE" => "#SITE_ADDRESS#",
        "EMAIL_VALUE" => "#SITE_MAIL#",
        "COPYRIGHT_VALUE" => "&copy; #YEAR# Universe, Все права защищены",
        "FORMS_CALL_ID" => "#FORMS_CALL_ID#",
        "FORMS_CALL_TEMPLATE" => ".default",
        "SOCIAL_VK_LINK" => "https://vk.com",
        "SOCIAL_FACEBOOK_LINK" => "https://facebook.com",
        "SOCIAL_INSTAGRAM_LINK" => "https://instagram.com",
        "SOCIAL_TWITTER_LINK" => "https://twitter.com",
        "LOGOTYPE_SHOW" => "Y",
        "PHONE_SHOW" => "Y",
        "PRODUCTS_VIEWED_TITLE_SHOW" => "Y",
        "PRODUCTS_VIEWED_TITLE" => "Ранее вы смотрели",
        "PRODUCTS_VIEWED_PAGE_ELEMENT_COUNT" => "10",
        "PRODUCTS_VIEWED_COLUMNS" => 4,
        "PRODUCTS_VIEWED_SHOW_NAVIGATION" => "Y",
        "ADDRESS_SHOW" => "Y",
        "EMAIL_SHOW" => "Y",
        "COPYRIGHT_SHOW" => "Y",
        "FORMS_CALL_SHOW" => "Y",
        "FORMS_CALL_TITLE" => "Заказать звонок",
        "MENU_MAIN_SHOW" => "Y",
        "SEARCH_SHOW" => "Y",
        "SOCIAL_SHOW" => "Y",
        "THEME" => "dark",
        "TEMPLATE" => "template.1",
        "ICONS" => [
            "ALFABANK",
            "SBERBANK",
            "QIWI",
            "YANDEXMONEY",
            "VISA",
            "MASTERCARD"
        ],
        "CONSENT_URL" => "#SITE_DIR#company/consent/",
        "CATALOG_URL" => "#SITE_DIR#catalog/",
        "SEARCH_URL" => "#SITE_DIR#search/",
        "PRODUCTS_VIEWED_PRICE_CODE" => [
            "BASE"
        ],
        "SEARCH_CATEGORY_0" => [
            "no"
        ],
        "SEARCH_PRICE_CODE" => [
            "BASE"
        ],
        "SEARCH_PRICE_VAT_INCLUDE" => "Y",
        "SEARCH_CURRENCY_CONVERT" => "N"
    ),
    false
); ?>