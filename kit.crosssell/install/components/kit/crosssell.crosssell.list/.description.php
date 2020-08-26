<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

$arComponentDescription = array(
	"NAME" => GetMessage("KIT_COMPONENTS_CROSSSELL_TITLE"),
	"DESCRIPTION" => GetMessage("KIT_COMPONENTS_CROSSSELL_DESC"),
	"ICON" => "",
	"PATH" => array(
        "ID" => "kit",
        "NAME" => GetMessage("KIT_COMPONENTS_TITLE"),
        "CHILD" => array(
            "ID" => "crosssell.collection",
            "NAME" => GetMEssage("KIT_COMPONENTS_CROSSSELL_TITLE")
        )
	),
);

?>