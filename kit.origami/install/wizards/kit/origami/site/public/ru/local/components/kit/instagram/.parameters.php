<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

$arComponentParameters = array(
    "PARAMETERS" => array(
        "LOGIN" => array(
            "NAME" => GetMessage("INWIDGET_LOGIN"),
            "TYPE" => "STRING",
            "DEFAULT" => "kit_insta",
            "PARENT" => "BASE",
        ),
        "IMG_COUNT" => array(
            "NAME" => GetMessage("INWIDGET_IMG_COUNT"),
            "TYPE" => "STRING",
            "DEFAULT" => "4",
            "PARENT" => "BASE",
        ),
        "IMG_DEFAULT" => array(
            "NAME" => GetMessage("INWIDGET_IMG_DEFAULT"),
            "TYPE" => "CHECKBOX",
            "DEFAULT" => "Y",
            "PARENT" => "BASE",
            "HINT" => "qqqqqqqqqqqqqqq",
        ),
        "TITLE" => array(
            "NAME" => GetMessage("INWIDGET_TITLE"),
            "TYPE" => "STRING",
            "DEFAULT" => GetMessage("INWIDGET_TITLE_DEFAULT"),
            "PARENT" => "BASE",
        ),
        "CACHE_TIME" => array(
			"DEFAULT" => 36000000,
        ),
    ),
);

?>