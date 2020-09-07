<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

$arComponentDescription = array(
	"NAME" => GetMessage("KIT_COMPONENTS_COLLECTION_TITLE"),
	"DESCRIPTION" => GetMessage("KIT_COMPONENTS_COLLECTION_DESC"),
	"ICON" => "",
	"PATH" => array(
        "ID" => "kit",
        "NAME" => GetMessage("KIT_COMPONENTS_TITLE"),
        "CHILD" => array(
            "ID" => "crosssell.collection",
            "NAME" => GetMEssage("CROSSSELL_COLLECTION")
        )
	),
);

?>