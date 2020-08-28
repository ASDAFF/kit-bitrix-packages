<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

$arTemplateParameters = array(
    "LINK_TO_THE_CATALOG" => Array(
		"NAME" => GetMessage("SOTBIT_POPULAR_CATEGORIES_LINK_TO_THE_CATALOG"),
		"TYPE" => "STRING",
		"DEFAULT" => "/catalog/",
    ),
    "BLOCK_NAME" => Array(
		"NAME" => GetMessage("SOTBIT_POPULAR_CATEGORIES_BLOCK_NAME"),
		"TYPE" => "STRING",
		"DEFAULT" => GetMessage("POPULAR_CATEGORIES_BLOCK_NAME_DEFAULT_VALUE"),
    ),
	"COUNT_SECTIONS" => Array(
		"NAME" => GetMessage("SOTBIT_POPULAR_CATEGORIES_COUNT_SECTIONS"),
		"TYPE" => "STRING",
		"DEFAULT" => "8",
	),
);
?>
