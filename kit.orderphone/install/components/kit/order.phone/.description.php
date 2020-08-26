<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$arComponentDescription = array(
	"NAME" => GetMessage("KIT_PHONE_DEFAULT_TEMPLATE_NAME"),
	"DESCRIPTION" => GetMessage("KIT_PHONE_DEFAULT_TEMPLATE_DESCRIPTION"),
	"ICON" => "/images/sale_order_full.gif",
	"PATH" => array(
		"ID" => "e-store",
		"CHILD" => array(
			"ID" => "kit_order_phone",
			"NAME" => GetMessage("KIT_PHONE_NAME")
		)
	),
);
?>