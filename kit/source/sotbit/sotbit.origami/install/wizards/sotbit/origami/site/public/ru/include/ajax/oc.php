<?php
define("STOP_STATISTICS", true);
define("NOT_CHECK_PERMISSIONS", true);
use \Sotbit\Origami\Helper\Config;
use \Sotbit\Origami\Config\Option;
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");
$moduleIncluded = false;
try
{
	\Bitrix\Main\Loader::includeModule('sotbit.origami');
	\Bitrix\Main\Loader::includeModule('sotbit.orderphone');
}
catch (\Bitrix\Main\LoaderException $e)
{
}

$props = unserialize(base64_decode($basketData));
if(!is_array($props))
{
	$props = [];
}

$APPLICATION->IncludeComponent("sotbit:order.phone", "origami_default", array(
	"PRODUCT_ID" => $id,
	"IBLOCK_ID" => $iblockId,
	"SELECT_USER" => "isset",
	"USER_GROUP" => "0",
	"STATUS_ORDER" => "N",
	"PERSON_TYPE" => Config::get('PERSON_TYPE',$site_id),
	"ORDER_TEL_PROP" => Config::get('PROP_PHONE',$site_id),
	"ORDER_NAME_PROP" => Config::get('PROP_NAME',$site_id),
	"ORDER_EMAIL_PROP" => Config::get('PROP_EMAIL',$site_id),
	"ORDER_PROPS" => array(
	),
	"PAY_SYSTEM_ID" => Config::get('PAYMENT',$site_id),
	"DELIVERY_ID" => Config::get('DELIVERY',$site_id),
	"LOCAL_REDIRECT" => "",
	"ORDER_ID" => "ORDER_ID",
	"TEL_MASK" => Config::get('MASK',$site_id),
	"SUCCESS_TEXT" => GetMessage("MS_PHONE_SUCCESS_TEXT"),
	"SUBMIT_VALUE" => GetMessage("MS_PHONE_SUBMIT_VALUE"),
	"SEND_EVENT" => "Y",
	"TEXT_TOP" => GetMessage("MS_PHONE_TEXT_TOP"),
	"TEXT_BOTTOM" => GetMessage("MS_PHONE_TEXT_BOTTOM"),
	"ERROR_TEXT" => GetMessage("MS_PHONE_ERROR_TEXT"),
	"OFFERS_PROPS" => $props,
	'SITE_ID' => $site_id,
    'USE_CAPTCHA' => (Option::get('CAPTCHA',$site_id) == 'BITRIX')?'Y':'N'
),
	false
);
