<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$arComponentDescription = array(
	"NAME" => GetMessage("SOTBIT_PHONE_DEFAULT_TEMPLATE_NAME"),
	"DESCRIPTION" => GetMessage("SOTBIT_PHONE_DEFAULT_TEMPLATE_DESCRIPTION"),
	"ICON" => "/images/sale_order_full.gif",
	"PATH" => array(
		"ID" => "e-store",
		"CHILD" => array(
			"ID" => "sotbit_order_phone",
			"NAME" => GetMessage("SOTBIT_PHONE_NAME")
		)
	),
);
?>