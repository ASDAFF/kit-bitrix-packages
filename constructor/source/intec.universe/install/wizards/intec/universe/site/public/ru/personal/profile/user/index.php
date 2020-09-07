<?php require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");

$APPLICATION->SetTitle("Личный кабинет");

?>
<?php $APPLICATION->IncludeComponent(
    "bitrix:main.profile",
    "personal",
    array(
        "CHECK_RIGHTS" => "N",
        "SEND_INFO" => "N",
        "SET_TITLE" => "Y",
        "USER_PROPERTY" => array(
        ),
        "USER_PROPERTY_NAME" => "",
        "USER_URL_CHANGE_PSW" => "pass.php",
        "COMPOSITE_FRAME_MODE" => "A",
        "COMPOSITE_FRAME_TYPE" => "AUTO"
    ),
    false
); ?>
<?php require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php") ?>
