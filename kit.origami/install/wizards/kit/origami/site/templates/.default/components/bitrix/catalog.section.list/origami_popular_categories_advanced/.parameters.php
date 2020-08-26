<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

$arTemplateParameters = array(
    "LINK_TO_THE_CATALOG" => Array(
		"NAME" => GetMessage("KIT_POPULAR_CATEGORIES_LINK_TO_THE_CATALOG"),
		"TYPE" => "STRING",
		"DEFAULT" => "/catalog/",
    ),
    "BLOCK_NAME" => Array(
		"NAME" => GetMessage("KIT_POPULAR_CATEGORIES_BLOCK_NAME"),
		"TYPE" => "STRING",
		"DEFAULT" => GetMessage("POPULAR_CATEGORIES_BLOCK_NAME_DEFAULT_VALUE"),
    ),
	"SHOW_SUBSECTIONS" => Array(
		"NAME" => GetMessage("KIT_POPULAR_CATEGORIES_SHOW_SUBSECTION"),
		"TYPE" => "CHECKBOX",
		"DEFAULT" => "Y",
	),

);
?>
