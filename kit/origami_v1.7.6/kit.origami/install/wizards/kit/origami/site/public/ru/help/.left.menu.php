<?
\Bitrix\Main\Loader::includeModule('kit.origami');
$aMenuLinks = Array(
    Array(
        "Оплата",
        SITE_DIR."help/payment/index.php",
        Array(),
        Array(),
        ""
    ),
    Array(
        "Возврат товара",
        SITE_DIR."help/return/",
        Array(),
        Array(),
        ""
    ),
    Array(
        "Доставка",
        SITE_DIR."help/delivery/",
        Array(),
        Array(),
        ""
    ),
    Array(
        "Как оформить заказ",
        SITE_DIR."help/checkout/",
        Array(),
        Array(),
        ""
    ),
    Array(
        "Правила продажи товаров",
        SITE_DIR."help/rules/",
        Array(),
        Array(),
        ""
    ),
    Array(
        "Публичная оферта",
        SITE_DIR."help/oferta/",
        Array(),
        Array(),
        ""
    ),
    Array(
        "Конфиденциальность",
        \Kit\Origami\Helper\Config::get('CONFIDENTIAL_PAGE'),
        Array(),
        Array(),
        ""
    ),
);
?>