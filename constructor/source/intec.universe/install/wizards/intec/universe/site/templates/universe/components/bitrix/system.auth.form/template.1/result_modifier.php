<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

if (!$GLOBALS['USER']->IsAuthorized())
{
	$arParamsToDelete = array(
		"login",
		"logout",
		"register",
		"forgot_password",
		"change_password",
		"confirm_registration",
		"confirm_code",
		"confirm_user_id",
	);
}
?>