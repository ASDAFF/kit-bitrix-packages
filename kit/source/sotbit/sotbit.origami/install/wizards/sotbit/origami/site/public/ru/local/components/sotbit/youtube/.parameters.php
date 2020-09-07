<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

$arComponentParameters = array(
    "PARAMETERS" => array(
        "CHANEL_ID" => array(
            "NAME" => GetMessage("SOTBIT_YOUTUBE_CHANEL_ID"),
            "TYPE" => "STRING",
            "DEFAULT" => "UCljk41PuLLNRkcrxPOkj4Vg",
            "PARENT" => "BASE",
        ),
        "API_KEY" => array(
            "NAME" => GetMessage("SOTBIT_YOUTUBE_API_KEY"),
            "TYPE" => "STRING",
            "DEFAULT" => "AIzaSyAOewAB2w1ca-sedRPWbTekydn5Z42Z8iE",
            "PARENT" => "BASE",
        ),
        "VIDEO_COUNT" => array(
            "NAME" => GetMessage("SOTBIT_YOUTUBE_VIDEO_COUNT"),
            "TYPE" => "STRING",
            "DEFAULT" => "4",
            "PARENT" => "BASE",
        ),
        "TITLE" => array(
            "NAME" => GetMessage("SOTBIT_YOUTUBE_TITLE"),
            "TYPE" => "STRING",
            "DEFAULT" => GetMessage("SOTBIT_YOUTUBE_TITLE_DEFAULT"),
            "PARENT" => "BASE",
        ),
        "CACHE_TIME" => array(
			"DEFAULT" => 36000000,
        ),
    ),
);

?>