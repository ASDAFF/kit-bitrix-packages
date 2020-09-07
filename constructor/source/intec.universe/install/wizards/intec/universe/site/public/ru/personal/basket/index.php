<?php require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php") ?>
<?php

use Bitrix\Main\ModuleManager;

$APPLICATION->SetTitle("Корзина")

?>
<?php if (ModuleManager::isModuleInstalled('sale')) { ?>
    <? $APPLICATION->IncludeComponent(
        "bitrix:sale.basket.basket",
        "template.1",
        array(
            "DEFERRED_REFRESH" => "N",
            "USE_DYNAMIC_SCROLL" => "Y",
            "SHOW_FILTER" => "Y",
            "SHOW_RESTORE" => "Y",
            "ORDER_FAST_USE" => "N",
            "COLUMNS_LIST_EXT" => array(
                "PREVIEW_PICTURE",
                "DISCOUNT",
                "PROPS",
                "DELETE",
                "DELAY",
                "SUM",
            ),
            "COLUMNS_LIST_MOBILE" => array(
                "PREVIEW_PICTURE",
                "DISCOUNT",
                "DELETE",
                "DELAY",
                "SUM",
            ),
            "TOTAL_BLOCK_DISPLAY" => array(
                "top",
            ),
            "PRICE_DISPLAY_MODE" => "Y",
            "SHOW_DISCOUNT_PERCENT" => "Y",
            "DISCOUNT_PERCENT_POSITION" => "bottom-right",
            "PRODUCT_BLOCKS_ORDER" => "props,sku,columns",
            "USE_PRICE_ANIMATION" => "Y",
            "LABEL_PROP" => array(),
            "PATH_TO_ORDER" => "#SITE_DIR#personal/basket/order.php",
            "HIDE_COUPON" => "N",
            "PRICE_VAT_SHOW_VALUE" => "N",
            "USE_PREPAYMENT" => "N",
            "QUANTITY_FLOAT" => "Y",
            "CORRECT_RATIO" => "Y",
            "AUTO_CALCULATION" => "Y",
            "SET_TITLE" => "Y",
            "ACTION_VARIABLE" => "basketAction",
            "COMPATIBLE_MODE" => "Y",
            "EMPTY_BASKET_HINT_PATH" => "/",
            "OFFERS_PROPS" => array(),
            "BASKET_IMAGES_SCALING" => "adaptive",
            "USE_GIFTS" => "N",
            "USE_ENHANCED_ECOMMERCE" => "N"
        ),
        false
    ); ?>
<?php } else { ?>
    <? $APPLICATION->IncludeComponent(
        "intec:startshop.basket",
        ".default",
        array(
            "COMPONENT_TEMPLATE" => ".default",
            "CURRENCY" => "rub",
            "REQUEST_VARIABLE_ACTION" => "action",
            "REQUEST_VARIABLE_ITEM" => "item",
            "REQUEST_VARIABLE_QUANTITY" => "quantity",
            "REQUEST_VARIABLE_PAGE" => "page",
            "URL_BASKET_EMPTY" => "",
            "USE_ITEMS_PICTURES" => "Y",
            "USE_BUTTON_CLEAR" => "Y",
            "USE_BUTTON_BASKET" => "Y",
            "USE_SUM_FIELD" => "Y",
            "TITLE_BASKET" => "",
            "TITLE_ORDER" => "Оформление заказа",
            "TITLE_PAYMENT" => "Оплата",
            "URL_ORDER_CREATED" => "#SITE_DIR#personal/profile/orders/?ORDER_ID=#ID#",
            "USE_ADAPTABILITY" => "Y",
            "REQUEST_VARIABLE_PAYMENT" => "payment",
            "REQUEST_VARIABLE_VALUE_RESULT" => "result",
            "REQUEST_VARIABLE_VALUE_SUCCESS" => "success",
            "REQUEST_VARIABLE_VALUE_FAIL" => "fail",
            "URL_ORDER_CREATED_TO_USER" => "#SITE_DIR#personal/profile/",
            "AJAX_MODE" => "N",
            "USE_BUTTON_FAST_ORDER" => "N",
            "USE_BUTTON_CONTINUE_SHOPPING" => "Y",
            "URL_CATALOG" => "#SITE_DIR#catalog/",
            "VERIFY_CONSENT_TO_PROCESSING_PERSONAL_DATA" => "Y",
            "URL_RULES_OF_PERSONAL_DATA_PROCESSING" => "#SITE_DIR#company/consent/",
            "USE_FAST_ORDER" => "N"
        ),
        false
    ); ?>
<?php } ?>
<?php require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php") ?>