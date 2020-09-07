<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
{
	die();
}

/**
 * @global CMain $APPLICATION
 * @var array $arParams
 * @var array $arResult
 */

use Bitrix\Main\Page\Asset;
//one css for all system.auth.* forms
$APPLICATION->SetAdditionalCSS("/bitrix/css/main/system.auth/flat/style.css");


Asset::getInstance()->addcss("/local/templates/kit_origami/components/bitrix/system.auth.forgotpasswd/.default/style.css");
?>

<div class="bx-authform">

<?
if(!empty($arParams["~AUTH_RESULT"])):
	$text = str_replace(array("<br>", "<br />"), "\n", $arParams["~AUTH_RESULT"]["MESSAGE"]);
?>
	<div class="alert <?=($arParams["~AUTH_RESULT"]["TYPE"] == "OK"? "alert-success":"alert-danger")?>"><?=nl2br(htmlspecialcharsbx($text))?></div>
<?endif?>

	<p class="bx-authform-content-container"><?=GetMessage("AUTH_FORGOT_PASSWORD_1")?></p>

	<form name="bform" method="post" target="_top" action="<?=$arResult["AUTH_URL"]?>">
<?if($arResult["BACKURL"] <> ''):?>
		<input type="hidden" name="backurl" value="<?=$arResult["BACKURL"]?>" />
<?endif?>
		<input type="hidden" name="AUTH_FORM" value="Y">
		<input type="hidden" name="TYPE" value="SEND_PWD">

		<div class="bx-authform-formgroup-container">
			<div class="bx-authform-label-container"><?echo GetMessage("AUTH_LOGIN_EMAIL")?></div>
			<div class="bx-authform-input-container">
				<input type="text" name="USER_LOGIN" maxlength="255" value="<?=$arResult["LAST_LOGIN"]?>" />
				<input type="hidden" name="USER_EMAIL" />
			</div>
		</div>

<?if ($arResult["USE_CAPTCHA"]):?>
	<div class="feedback_block__captcha">
		<p class="feedback_block__captcha_text">
            <?=GetMessage('CAPTCHA_TITLE')?>
		</p>
		<div class="feedback_block__captcha_input">
			<input type="text" name="captcha_word" size="30" maxlength="50" value="" required />
		</div>
		<div class="feedback_block__captcha_img">
			<input type="hidden" name="captcha_sid" value="<?=$arResult["CAPTCHA_CODE"]?>" />
			<img src="/bitrix/tools/captcha.php?captcha_sid=<?=$arResult["CAPTCHA_CODE"]?>" width="180" height="40" />
			<div class="feedback_block__captcha_reload" onclick="reloadCaptcha(this,'<?=SITE_DIR?>');return false;"></div>
		</div>
	</div>
<?endif?>

		<div class="bx-authform-formgroup-container">
			<input type="submit" class="btn auth-btn" name="send_account_info" value="<?=GetMessage("AUTH_SEND")?>" />
		</div>

		<div class="bx-authform-link-container">
			<a href="<?=$arResult["AUTH_AUTH_URL"]?>"><?=GetMessage("AUTH_AUTH")?></a>
		</div>

	</form>

</div>

<script type="text/javascript">
document.bform.onsubmit = function(){document.bform.USER_EMAIL.value = document.bform.USER_LOGIN.value;};
document.bform.USER_LOGIN.focus();
</script>
