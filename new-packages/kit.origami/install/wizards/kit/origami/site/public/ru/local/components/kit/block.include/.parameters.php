<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
	die();

$arComponentParameters = array(
	"GROUPS" => array(
		"DATA_SOURCE" => array(
			"NAME" => GetMessage("BLOCK_INCLUDE_GROUP_MAIN"),
		),
	),
	"PARAMETERS" => array(
		"PART" => array(
			"PARENT" => "DATA_SOURCE",
			"NAME" => GetMessage("BLOCK_INCLUDE_PART"),
			"TYPE" => "STRING",
			"DEFAULT" => '',
		),
    ),
);
?>