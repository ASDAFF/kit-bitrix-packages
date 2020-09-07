<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

$arComponentDescription = array(
	"NAME" => GetMessage("SOTBIT_COMPONENTS_COLLECTION_TITLE"),
	"DESCRIPTION" => GetMessage("SOTBIT_COMPONENTS_COLLECTION_DESC"),
	"ICON" => "",
	"PATH" => array(
        "ID" => "sotbit",
        "NAME" => GetMessage("SOTBIT_COMPONENTS_TITLE"),
        "CHILD" => array(
            "ID" => "crosssell.collection",
            "NAME" => GetMEssage("CROSSSELL_COLLECTION")
        )
	),
);

?>