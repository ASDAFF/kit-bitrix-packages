<?php require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");

use Bitrix\Main\ModuleManager;

$APPLICATION->SetTitle("Заказы");

?>
<?php if (ModuleManager::isModuleInstalled('sale')) { ?>
    <?php $APPLICATION->IncludeComponent(
        "bitrix:sale.personal.order",
        ".default",
        array(
            "ACTIVE_DATE_FORMAT" => "d.m.Y",
            "ALLOW_INNER" => "Y",
            "CACHE_GROUPS" => "Y",
            "CACHE_TIME" => "3600",
            "CACHE_TYPE" => "A",
            "COMPONENT_TEMPLATE" => ".default",
            "CUSTOM_SELECT_PROPS" => array(
            ),
            "DETAIL_HIDE_USER_INFO" => array(
                0 => "0",
            ),
            "HISTORIC_STATUSES" => array(
                0 => "F",
            ),
            "NAV_TEMPLATE" => "",
            "ONLY_INNER_FULL" => "N",
            "ORDERS_PER_PAGE" => "20",
            "ORDER_DEFAULT_SORT" => "STATUS",
            "PATH_TO_BASKET" => "#SITE_DIR#personal/basket/",
            "PATH_TO_CATALOG" => "#SITE_DIR#catalog/",
            "PATH_TO_PAYMENT" => "#SITE_DIR#personal/basket/payment/",
            "PROP_1" => array(
            ),
            "PROP_2" => array(
            ),
            "RESTRICT_CHANGE_PAYSYSTEM" => array(
                0 => "0",
            ),
            "SAVE_IN_SESSION" => "Y",
            "SEF_MODE" => "N",
            "SET_TITLE" => "Y",
            "STATUS_COLOR_F" => "gray",
            "STATUS_COLOR_N" => "green",
            "STATUS_COLOR_P" => "yellow",
            "STATUS_COLOR_PSEUDO_CANCELLED" => "red"
        ),
        false
    ); ?>
<?php } else { ?>
    <?php $APPLICATION->IncludeComponent(
        "intec:startshop.orders",
        ".default",
        array(
            "COMPONENT_TEMPLATE" => ".default",
            "CURRENCY" => "rub",
            "REQUEST_VARIABLE_ORDER_ID" => "ORDER_ID",
            "USE_ADAPTABILITY" => "N",
            "URL_AUTHORIZE" => "",
            "TITLE_ORDERS_LIST" => "",
            "TITLE_ORDERS_DETAIL" => "Заказ",
            "404_SET_STATUS" => "Y",
            "404_REDIRECT" => "Y",
            "404_PAGE" => "#SITE_DIR#404.php"
        ),
        false
    ); ?>
<?php } ?>
<?php require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php") ?>
