<?php
use Kit\Origami\Helper\Config;
global $APPLICATION, $settings;

CModule::IncludeModule('form');
$FORM_SID = "LIKEOFFER";
$rsForm = CForm::GetBySID($FORM_SID);
$arForm = $rsForm->Fetch();
$APPLICATION->IncludeComponent(
    "bitrix:form.result.new",
    "kit_webform_1",
    array(
        "CACHE_TIME" => "3600",
        "CACHE_TYPE" => "A",
        "CHAIN_ITEM_LINK" => "",
        "CHAIN_ITEM_TEXT" => "",
        "COMPOSITE_FRAME_MODE" => "A",
        "COMPOSITE_FRAME_TYPE" => "AUTO",
        "EDIT_URL" => "",
        "AJAX_MODE" => 'Y',
        "AJAX_OPTION_ADDITIONAL" => \Bitrix\Main\Security\Random::getString(3),
        "IGNORE_CUSTOM_TEMPLATE" => "N",
        "LIST_URL" => "",
        "SEF_MODE" => "N",
        "SUCCESS_URL" => "",
        "USE_EXTENDED_ERRORS" => "N",
        "VARIABLE_ALIASES" => array(
            "RESULT_ID" => "RESULT_ID",
            "WEB_FORM_ID" => "WEB_FORM_ID"
        ),
        "WEB_FORM_ID" => (isset($settings['fields']['webform_id']['value'])) ? $settings['fields']['webform_id']['value'] : $arForm['ID']
    )
);

