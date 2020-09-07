<?php define("NEED_AUTH", true);

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");

use Bitrix\Main\ModuleManager;

$APPLICATION->SetTitle("Личный кабинет пользователя");

?>
<?php if (ModuleManager::isModuleInstalled('sale')) { ?>
    <?php $APPLICATION->IncludeComponent(
        "bitrix:sale.personal.section",
        ".default",
        array(
            "ACCOUNT_PAYMENT_ELIMINATED_PAY_SYSTEMS" => array(
                0 => "0",
            ),
            "ACCOUNT_PAYMENT_PERSON_TYPE" => "1",
            "ACCOUNT_PAYMENT_SELL_SHOW_FIXED_VALUES" => "Y",
            "ACCOUNT_PAYMENT_SELL_TOTAL" => array(
                0 => "100",
                1 => "200",
                2 => "500",
                3 => "1000",
                4 => "5000",
                5 => "",
            ),
            "ACCOUNT_PAYMENT_SELL_USER_INPUT" => "Y",
            "ACTIVE_DATE_FORMAT" => "d.m.Y",
            "CACHE_GROUPS" => "Y",
            "CACHE_TIME" => "3600",
            "CACHE_TYPE" => "A",
            "CHECK_RIGHTS_PRIVATE" => "N",
            "COMPATIBLE_LOCATION_MODE_PROFILE" => "N",
            "CUSTOM_PAGES" => "",
            "CUSTOM_SELECT_PROPS" => array(
            ),
            "NAV_TEMPLATE" => "",
            "ORDER_HISTORIC_STATUSES" => array(
                0 => "F",
            ),
            "PATH_TO_BASKET" => "#SITE_DIR#personal/basket/",
            "PATH_TO_CATALOG" => "#SITE_DIR#catalog/",
            "PATH_TO_CONTACT" => "#SITE_DIR#contacts/",
            "PATH_TO_PAYMENT" => "#SITE_DIR#personal/order/payment",
            "MAILING_PATH" => "#SITE_DIR#personal/profile/mailing/",
            "PER_PAGE" => "20",
            "PROP_1" => array(
            ),
            "PROP_2" => array(
            ),
            "SAVE_IN_SESSION" => "Y",
            "SEF_FOLDER" => "#SITE_DIR#personal/profile/",
            "SEF_MODE" => "Y",
            "SEND_INFO_PRIVATE" => "N",
            "SET_TITLE" => "Y",
            "SHOW_ACCOUNT_COMPONENT" => "Y",
            "SHOW_ACCOUNT_PAGE" => "Y",
            "SHOW_ACCOUNT_PAY_COMPONENT" => "Y",
            "SHOW_BASKET_PAGE" => "Y",
            "SHOW_CONTACT_PAGE" => "Y",
            "SHOW_ORDER_PAGE" => "Y",
            "SHOW_PRIVATE_PAGE" => "Y",
            "SHOW_PROFILE_PAGE" => "N",
            "MAILING_SHOW" => "Y",
            "ALLOW_INNER" => "N",
            "ONLY_INNER_FULL" => "N",
            "SHOW_SUBSCRIBE_PAGE" => "Y",
            "USER_PROPERTY_PRIVATE" => "",
            "USE_AJAX_LOCATIONS_PROFILE" => "N",
            "COMPONENT_TEMPLATE" => ".default",
            "ACCOUNT_PAYMENT_SELL_CURRENCY" => "RUB",
            "ORDER_HIDE_USER_INFO" => array(
                0 => "0",
            ),
            "ORDER_RESTRICT_CHANGE_PAYSYSTEM" => array(
                0 => "0",
            ),
            "ORDER_DEFAULT_SORT" => "STATUS",
            "ORDERS_PER_PAGE" => "20",
            "PROFILES_PER_PAGE" => "20",
            "MAIN_CHAIN_NAME" => "",
            "COMPOSITE_FRAME_MODE" => "A",
            "COMPOSITE_FRAME_TYPE" => "AUTO",
            "SEF_URL_TEMPLATES" => array(
                "index" => "index.php",
                "orders" => "orders/",
                "account" => "account/",
                "subscribe" => "subscribes/",
                "profile" => "profiles/",
                "profile_detail" => "profiles/#ID#",
                "private" => "user/",
                "order_detail" => "orders/#ID#",
                "order_cancel" => "cancel/#ID#",
            )
        ),
        false
    ); ?>
<?php } else { ?>
    <?php $APPLICATION->IncludeComponent(
        "intec.universe:personal.section",
        ".default",
        array(
            "COMPONENT_TEMPLATE" => ".default",
            "URL_PERSONAL_DATA" => "#SITE_DIR#personal/profile/user/",
            "URL_BASKET" => "#SITE_DIR#personal/basket/",
            "URL_ORDER" => "#SITE_DIR#personal/profile/orders/",
            "URL_CONTACTS" => "#SITE_DIR#contacts/"
        ),
        false
    ); ?>
<?php } ?>
<?php require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php") ?>