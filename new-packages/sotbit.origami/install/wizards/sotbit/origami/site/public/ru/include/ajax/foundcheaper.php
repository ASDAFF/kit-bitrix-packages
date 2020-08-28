<?php
define('STOP_STATISTICS', true);
define('NOT_CHECK_PERMISSIONS', true);
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");

if(ToLower(LANG_CHARSET) == 'windows-1251' && !isset($_REQUEST["web_form_submit"]) && !isset($_REQUEST["web_form_apply"]) && isset($_REQUEST) && is_array($_REQUEST))
{
    foreach($_REQUEST as $key => &$val)
    {
        if(!is_array($val) && $key != 'web_form_submit' && $key != 'web_form_apply')
            $val = iconv('utf-8', 'windows-1251', $val);
    }
}

if(ToLower(LANG_CHARSET) == 'windows-1251' && !isset($_POST["web_form_submit"]) && !isset($_POST["web_form_apply"]) && isset($_POST) && is_array($_POST))
{
    foreach($_POST as $key => &$val)
    {
        if(!is_array($val) && $key != 'web_form_submit' && $key != 'web_form_apply')
            $val = iconv('utf-8', 'windows-1251', $val);
    }
}

$APPLICATION->IncludeComponent(
    "bitrix:form.result.new",
    "origami_foundcheaper",
    array(
        "CACHE_TIME" => "3600",
        "CACHE_TYPE" => "A",
        "CHAIN_ITEM_LINK" => "",
        "CHAIN_ITEM_TEXT" => "",
        "COMPOSITE_FRAME_MODE" => "A",
        "COMPOSITE_FRAME_TYPE" => "AUTO",
        "EDIT_URL" => "",
        "AJAX_MODE" => 'Y',
        "IGNORE_CUSTOM_TEMPLATE" => "N",
        "LIST_URL" => "",
        "SEF_MODE" => "N",
        "SUCCESS_URL" => "",
        "SITE_ID" => $lid,
        "USE_EXTENDED_ERRORS" => "N",
        "VARIABLE_ALIASES" => array(
            "RESULT_ID" => "RESULT_ID",
            "WEB_FORM_ID" => "WEB_FORM_ID"
        ),
        "WEB_FORM_ID" => "5"
    )
);
?>
