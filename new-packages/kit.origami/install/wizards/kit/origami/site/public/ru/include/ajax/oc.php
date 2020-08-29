<?php
define("STOP_STATISTICS", true);
define("NOT_CHECK_PERMISSIONS", true);
use \Kit\Origami\Helper\Config;
use \Kit\Origami\Config\Option;
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");
$moduleIncluded = false;
try
{
	\Bitrix\Main\Loader::includeModule('kit.origami');
	\Bitrix\Main\Loader::includeModule('kit.orderphone');
}
catch (\Bitrix\Main\LoaderException $e)
{
}

$props = unserialize(base64_decode($basketData));
if(!is_array($props))
{
	$props = [];
}

$APPLICATION->IncludeComponent("kit:order.phone", "origami_default", array(
    "PRODUCT_ID" => $id,
    "IBLOCK_ID" => $iblockId,
    "SELECT_USER" => Config::get('SOP_SELECT_USER', $site_id),
    "ONE_USER_ID" => Config::get('SOP_ONE_USER_ID', $site_id),
    "USER_GROUP" => Config::get('SOP_USER_GROUP', $site_id),
    "STATUS_ORDER" => Config::get('SOP_STATUS_ORDER', $site_id),
    "PERSON_TYPE" => Config::get('PERSON_TYPE', $site_id),
    "ORDER_TEL_PROP" => Config::get('PROP_PHONE', $site_id),
    "ORDER_NAME_PROP" => Config::get('PROP_NAME', $site_id),
    "ORDER_EMAIL_PROP" => Config::get('PROP_EMAIL', $site_id),
    "COMMENT_TEMPLATE" => Config::get('SOP_COMMENT_TEMPLATE', $site_id),
    "LOGIN_MASK" => Config::get('SOP_LOGIN_MASK', $site_id),
    "EMAIL_MASK" => Config::get('SOP_EMAIL_MASK', $site_id),
    "DISPLAYED_FIELDS" => unserialize(Config::get('SOP_DISPLAYED_FIELDS', $site_id)),
    "REQUIRED_FIELDS" => unserialize(Config::get('SOP_REQUIRED_FIELDS', $site_id)),
    "SMS_CONFIRM" => Config::get('SOP_SMS_CONFIRM', $site_id),
    "ORDER_PROPS" => array(
    ),
    "PAY_SYSTEM_ID" => Config::get('PAYMENT', $site_id),
    "DELIVERY_ID" => Config::get('DELIVERY', $site_id),
    "LOCAL_REDIRECT" => "",
    "ORDER_ID" => "ORDER_ID",
    "TEL_MASK" => Config::get('MASK', $site_id),
    "SUCCESS_TEXT" => GetMessage("MS_PHONE_SUCCESS_TEXT"),
    "SUBMIT_VALUE" => GetMessage("MS_PHONE_SUBMIT_VALUE"),
    "SEND_EVENT" => "Y",
    "TEXT_TOP" => GetMessage("MS_PHONE_TEXT_TOP"),
    "TEXT_BOTTOM" => GetMessage("MS_PHONE_TEXT_BOTTOM"),
    "ERROR_TEXT" => GetMessage("MS_PHONE_ERROR_TEXT"),
    "OFFERS_PROPS" => $props,
    'SITE_ID' => $site_id,
    'USE_CAPTCHA' => (Option::get('CAPTCHA', $site_id) == 'BITRIX')?'Y':'N'
),
    false
);
