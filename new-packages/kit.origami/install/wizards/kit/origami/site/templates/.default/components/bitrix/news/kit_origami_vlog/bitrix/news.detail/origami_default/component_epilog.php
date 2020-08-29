<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$APPLICATION->IncludeComponent(
    "bitrix:sender.subscribe",
    "kit_sender_subscribe_campaign",
    array(
        "AJAX_MODE" => "Y",
        "AJAX_OPTION_ADDITIONAL" => "",
        "AJAX_OPTION_HISTORY" => "N",
        "AJAX_OPTION_JUMP" => "N",
        "AJAX_OPTION_STYLE" => "Y",
        "CACHE_TIME" => "3600",
        "CACHE_TYPE" => "A",
        "COMPONENT_TEMPLATE" => "kit_sender_subscribe_campaign",
        "COMPOSITE_FRAME_MODE" => "A",
        "COMPOSITE_FRAME_TYPE" => "AUTO",
        "CONFIRMATION" => "N",
        "FORM_SUBSCRIBE_TITLE" => "����������� �� ����",
        "HIDE_MAILINGS" => "N",
        "MAILING_LISTS" => array(
        ),
        "SET_TITLE" => "N",
        "SHOW_HIDDEN" => "Y",
        "USER_CONSENT" => "N",
        "USER_CONSENT_ID" => "0",
        "USER_CONSENT_IS_CHECKED" => "Y",
        "USER_CONSENT_IS_LOADED" => "N",
        "USE_PERSONALIZATION" => "Y"
    ),
    false
);
?>
</div>
</div>
</div>
</div>
