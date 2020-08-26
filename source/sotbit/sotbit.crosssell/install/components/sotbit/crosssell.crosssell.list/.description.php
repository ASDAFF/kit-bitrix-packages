<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

$arComponentDescription = array(
	"NAME" => GetMessage("SOTBIT_COMPONENTS_CROSSSELL_TITLE"),
	"DESCRIPTION" => GetMessage("SOTBIT_COMPONENTS_CROSSSELL_DESC"),
	"ICON" => "",
	"PATH" => array(
        "ID" => "sotbit",
        "NAME" => GetMessage("SOTBIT_COMPONENTS_TITLE"),
        "CHILD" => array(
            "ID" => "crosssell.collection",
            "NAME" => GetMEssage("SOTBIT_COMPONENTS_CROSSSELL_TITLE")
        )
	),
);

?>