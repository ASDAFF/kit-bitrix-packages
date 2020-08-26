<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$arComponentDescription = array(
	"NAME" => GetMessage("SOTBIT_ORIGAMI_THEME"),
	"DESCRIPTION" => '',
	"ICON" => "",
	"CACHE_PATH" => "Y",
	"SORT" => 70,
	"PATH" => array(
		"ID" => "sotbit",
        "NAME" => GetMessage("SOTBIT_ORIGAMI_THEME_COMPANY"),
		"CHILD" => array(
			"ID" => "sotbit_origami",
			"NAME" => GetMessage("SOTBIT_ORIGAMI"),
			"SORT" => 30,
		),
	),
);
?>