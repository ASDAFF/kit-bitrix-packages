<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
CModule::IncludeModule("catalog");
CModule::IncludeModule("sale");


$dbResultList = CSaleDelivery::GetList( 
	array("SORT" => "ASC"),
	array("ACTIVE" => "Y"),
	false,
	false,
	array("ID","NAME")
);
$Deliveries=array();
while ($arDelivery = $dbResultList->Fetch())
	$Deliveries[$arDelivery['ID']]=$arDelivery['NAME'];


$dbRes = CSalePaySystem::GetList(

	array("SORT" => "ASC"),
	array(),
	false,
	false,
	array("ID","NAME")
);
$Payments=array();
while ($arPayment = $dbRes->Fetch())
	$Payments[$arPayment['ID']]=$arPayment['NAME'];

	
$rsGroups = CGroup::GetList(($by="NAME"), ($order="asc"), array("!ID"=>array(1,2)));

$arGroups[0] = GetMessage("SOP_NO_GROUP");

while($arGroup = $rsGroups->Fetch())
{
	$arGroups[$arGroup["ID"]] = $arGroup["NAME"];
}

$dbStatus = CSaleStatus::GetList(array("NAME"=>"ASC"), array("!NAME"=>false, "LID"=>SITE_ID), false, false, array("ID", "NAME"));

while($arStatus = $dbStatus->Fetch())
{
	$arSt[$arStatus["ID"]] = $arStatus["NAME"];
}

$dbPersonType = CSalePersonType::GetList(Array("ID"=>"ASC"), Array("ACTIVE"=>"Y"));
while($arPersonType = $dbPersonType->Fetch())
{
	$arPerson[$arPersonType["ID"]] = $arPersonType["NAME"];
}

if($arCurrentValues["PERSON_TYPE"])
{
	$arFilter = array(
		"PERSON_TYPE_ID" => $arCurrentValues["PERSON_TYPE"],
		"ACTIVE" => "Y",
		"UTIL" => "N",
		"IS_LOCATION" => "N",
		"IS_LOCATION4TAX" => "N"

	); 

	$dbOrderProps = CSaleOrderProps::GetList(
		array("SORT" => "ASC"),
		$arFilter,
		false,
		false,
		array("ID", "NAME")
	); 

	while ($arOrderProps = $dbOrderProps->Fetch())
	{
		$arProps[$arOrderProps["ID"]] = $arOrderProps["NAME"];    
	}   
}

$arComponentParameters = Array(
	"PARAMETERS" => Array(
		"PRODUCT_ID" => Array(
			"NAME" => GetMessage("SOP_PRODUCT_ID"),
			"TYPE" => "STRING",
			"MULTIPLE" => "N",
			"COLS" => 25,
			"PARENT" => "BASE",
		),
		"SELECT_USER" => Array(
			"NAME" => GetMessage("SOP_SELECT_USER"),
			"TYPE"=>"LIST",
			"MULTIPLE"=>"N",
			"VALUES"=>array(
				"one" => GetMessage("SOP_USER_ONE"),
				"new" => GetMessage("SOP_USER_NEW")
			),
			"DEFAULT"=>"new",
			"COLS"=>25,
			"PARENT" => "BASE",
		),
		"USER_GROUP" => Array(
			"NAME" => GetMessage("SOP_USER_GROUP"),
			"TYPE"=>"LIST",
			"MULTIPLE"=>"N",
			"VALUES"=>$arGroups,
			"DEFAULT"=>"0",
			"COLS"=>25,
			"PARENT" => "BASE",
		),
		"STATUS_ORDER" => Array(
			"NAME" => GetMessage("SOP_STATUS_ORDER"),
			"TYPE"=>"LIST",
			"MULTIPLE"=>"N",
			"VALUES"=>$arSt,
			"DEFAULT"=>"F",
			"COLS"=>25,
			"PARENT" => "BASE",
		),
		"PERSON_TYPE" => Array(
			"NAME" => GetMessage("SOP_PERSON_TYPE"),
			"TYPE"=>"LIST",
			"MULTIPLE"=>"N",
			"VALUES"=>$arPerson,
			"DEFAULT"=>"1",
			"COLS"=>25,
			"REFRESH" => "Y",
			"PARENT" => "BASE",
		),
		"ORDER_TEL_PROP" => Array(
			"NAME" => GetMessage("SOP_ORDER_TEL_PROP"),
			"TYPE"=>"LIST",
			"MULTIPLE"=>"N",
			"VALUES"=>$arProps,
			"COLS"=>25,
			"REFRESH" => "Y",
			"PARENT" => "BASE",
		),
		"ORDER_PROPS" => Array(
			"NAME" => GetMessage("SOP_ORDER_PROPS"),
			"TYPE"=>"LIST",
			"MULTIPLE"=>"Y",
			"VALUES"=>$arProps,
			"COLS"=>25,
			"REFRESH" => "Y",
			"PARENT" => "BASE",
		),
		"LOCAL_REDIRECT" => Array(
			"NAME" => GetMessage("SOP_LOCAL_REDIRECT"),
			"TYPE"=>"STRING",
			"DEFAULT"=>"",
			"PARENT" => "BASE",
		),
		"ORDER_ID" => Array(
			"NAME" => GetMessage("SOP_ORDER_ID"),
			"TYPE"=>"STRING",
			"DEFAULT"=>"ORDER_ID",
			"PARENT" => "BASE",
		),
		"TEL_MASK" => Array(
			"NAME" => GetMessage("SOP_TEL_MASK"),
			"TYPE" => "STRING",
			"MULTIPLE" => "N",
			"DEFAULT" => "+7(999)999-99-99",
			"COLS" => 25,
			"PARENT" => "BASE",
		),
		"SUCCESS_TEXT" => Array(
			"NAME" => GetMessage("SOP_SUCCES_TEXT"),
			"TYPE" => "STRING",
			"MULTIPLE" => "N",
			"DEFAULT" => GetMessage("SOP_SUCCES_TEXT_DEFAULT"),
			"COLS" => 25,
			"PARENT" => "BASE",
		),
		"SUBMIT_VALUE" => Array(
			"NAME" => GetMessage("SOP_SUBMIT_VALUE"),
			"TYPE" => "STRING",
			"MULTIPLE" => "N",
			"DEFAULT" => GetMessage("SOP_SUBMIT_VALUE_DEFAULT"),
			"COLS" => 25,
			"PARENT" => "BASE",
		),
		"SEND_EVENT" => Array(
			"NAME" => GetMessage("SOP_SEND_EVENT"),
			"TYPE" => "CHECKBOX",
			"DEFAULT" => "N",
			"COLS" => 25,
			"PARENT" => "BASE",
		),
		"TEXT_TOP" => Array(
			"NAME" => GetMessage("SOP_TEXT_TOP"),
			"TYPE" => "STRING",
			"MULTIPLE" => "N",
			"DEFAULT" => GetMessage("SOP_TEXT_TOP_DEFAULT"),
			"COLS" => 25,
			"PARENT" => "ADDITIONAL_SETTINGS",
		),
		"TEXT_BOTTOM" => Array(
			"NAME" => GetMessage("SOP_TEXT_BOTTOM"),
			"TYPE" => "STRING",
			"MULTIPLE" => "N",
			"DEFAULT" => GetMessage("SOP_TEXT_BOTTOM_DEFAULT"),
			"COLS" => 25,
			"PARENT" => "ADDITIONAL_SETTINGS",
		),
		"PRODUCT_PROPS" => Array(
			"NAME" => GetMessage("SOP_PRODUCT_PROPS"),
			"TYPE" => "STRING",
			"MULTIPLE" => "Y",
			"DEFAULT" => "",
			"COLS" => 25,
			"PARENT" => "ADDITIONAL_SETTINGS",
		),
		"OFFERS_PROPS" => Array(
			"NAME" => GetMessage("SOP_OFFERS_PROPS"),
			"TYPE" => "STRING",
			"MULTIPLE" => "Y",
			"DEFAULT" => "",
			"COLS" => 25,
			"PARENT" => "ADDITIONAL_SETTINGS",
		),
		"DELIVERY_ID" => Array(
			"NAME" => GetMessage("SOP_DELIVERY_ID"),
			"TYPE"=>"LIST",
			"MULTIPLE"=>"N",
			"VALUES"=>$Deliveries,
			"COLS"=>25,
			"REFRESH" => "N",
			"PARENT" => "BASE",
		),
		"PAY_SYSTEM_ID" => Array(
			"NAME" => GetMessage("SOP_PAY_SYSTEM_ID"),
			"TYPE"=>"LIST",
			"MULTIPLE"=>"N",
			"VALUES"=>$Payments,
			"COLS"=>25,
			"REFRESH" => "N",
			"PARENT" => "BASE",
		),
	)
);

?>