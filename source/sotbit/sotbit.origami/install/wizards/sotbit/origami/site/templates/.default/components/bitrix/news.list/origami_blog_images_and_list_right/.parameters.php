<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$arTemplateParameters = array(
	"DISPLAY_DATE" => Array(
		"NAME" => GetMessage("T_IBLOCK_DESC_BLOG_DATE"),
		"TYPE" => "CHECKBOX",
		"DEFAULT" => "Y",
	),
	"DISPLAY_NAME" => Array(
		"NAME" => GetMessage("T_IBLOCK_DESC_BLOG_NAME"),
		"TYPE" => "CHECKBOX",
		"DEFAULT" => "Y",
	),
	"DISPLAY_PICTURE" => Array(
		"NAME" => GetMessage("T_IBLOCK_DESC_BLOG_PICTURE"),
		"TYPE" => "CHECKBOX",
		"DEFAULT" => "Y",
	),
	"DISPLAY_PREVIEW_TEXT" => Array(
		"NAME" => GetMessage("T_IBLOCK_DESC_BLOG_TEXT"),
		"TYPE" => "CHECKBOX",
		"DEFAULT" => "Y",
    ),
    "LINK_TO_THE_FULL_LIST" => Array(
		"NAME" => GetMessage("T_IBLOCK_DESC_BLOG_LINK_TO_THE_FULL_LIST"),
		"TYPE" => "STRING",
		"DEFAULT" => "/blog/",
    ),
    "BLOCK_NAME" => Array(
		"NAME" => GetMessage("T_IBLOCK_DESC_BLOG_BLOCK_NAME"),
		"TYPE" => "STRING",
		"DEFAULT" => GetMessage("BLOG_BLOCK_NAME_DEFAULT_VALUE"),
    ),
);
?>
