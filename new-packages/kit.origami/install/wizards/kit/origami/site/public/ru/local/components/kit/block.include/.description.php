<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$arComponentDescription = array(
	"NAME" => GetMessage("SOTBIT_BLOCKS_MANAGE_BLOCKS"),
	"DESCRIPTION" => '',
	"ICON" => "",
	"CACHE_PATH" => "Y",
	"SORT" => 70,
	"PATH" => array(
		"ID" => "kit",
        "NAME" => GetMessage("SOTBIT_BLOCKS_MANAGE_COMPANY"),
		"CHILD" => array(
			"ID" => "kit_origami",
			"NAME" => GetMessage("SOTBIT_ORIGAMI"),
			"SORT" => 30,
		),
	),
);
?>