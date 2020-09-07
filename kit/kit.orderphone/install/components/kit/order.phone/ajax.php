<?
define("NO_KEEP_STATISTIC", true);
define("STOP_STATISTICS", true);
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
CModule::IncludeModule("main");
CModule::IncludeModule("iblock");
CModule::IncludeModule("sale");
CModule::IncludeModule("catalog");
CModule::IncludeModule("kit.orderphone");

foreach($_REQUEST as $id=>$val)
{
	if($id!="order_phone") $arParams[$id] = $val;
}

$fields = [];
if(isset($_REQUEST["order_name"]))
{
	$fields['NAME'] = $_REQUEST["order_name"];
}
if(isset($_REQUEST["order_phone"]))
{
	$fields['PHONE'] = $_REQUEST["order_phone"];
}
if(isset($_REQUEST["order_email"]))
{
	$fields['EMAIL'] = $_REQUEST["order_email"];
}
if(isset($_REQUEST["order_comment"]))
{
	$fields['COMMENT'] = $_REQUEST["order_comment"];
}

if($fields && count($arParams)>0)
{
	//CKitOrderphone::StartAjax($arParams, $_REQUEST["order_phone"]);
	
	$order = new CKitOrderphone($arParams, $fields);
	$order->StartAjax();
	if(empty($order->error))
	{
		echo "SUCCESS".$order->orderID;
	}else{
		echo $order->error[0];
	}
}
?>