<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
{
	die();
}


/**
 * @global CMain $APPLICATION
 * @var array $arParams
 * @var array $arResult
 * @var CBitrixComponent $component
 */

//one css for all system.auth.* forms
//$APPLICATION->SetAdditionalCSS("/bitrix/css/main/system.auth/flat/style.css");


?>

<div class="bx-authform">

<?
if(!empty($arParams["~AUTH_RESULT"])):
	$text = str_replace(array("<br>", "<br />"), "\n", $arParams["~AUTH_RESULT"]["MESSAGE"]);
?>
	<div class="alert alert-danger"><?=nl2br(htmlspecialcharsbx($text))?></div>
<?endif?>

<?
if($arResult['ERROR_MESSAGE'] <> ''):
	$text = str_replace(array("<br>", "<br />"), "\n", $arResult['ERROR_MESSAGE']);
?>
	<div class="alert alert-danger"><?=nl2br(htmlspecialcharsbx($text))?></div>
<?endif?>

<?if($arResult["AUTH_SERVICES"]):?>
<?
$APPLICATION->IncludeComponent("bitrix:socserv.auth.form",
	"",
	array(
		"AUTH_SERVICES" => $arResult["AUTH_SERVICES"],
		"AUTH_URL" => $arResult["AUTH_URL"],
		"POST" => $arResult["POST"],
	),
	$component,
	array("HIDE_ICONS"=>"Y")
);
?>

<?endif?>

	<form name="form_auth" method="post" target="_top" action="<?=$arResult["AUTH_URL"]?>">

		<input type="hidden" name="AUTH_FORM" value="Y" />
		<input type="hidden" name="TYPE" value="AUTH" />
<?if (strlen($arResult["BACKURL"]) > 0):?>
		<input type="hidden" name="backurl" value="<?=$arResult["BACKURL"]?>" />
<?endif?>
<?foreach ($arResult["POST"] as $key => $value):?>
		<input type="hidden" name="<?=$key?>" value="<?=$value?>" />
<?endforeach?>

		<div class="bx-authform-formgroup-container">
			<div class="bx-authform-label-container"><?=GetMessage("AUTH_LOGIN")?></div>
			<div class="bx-authform-input-container">
				<input type="text" name="USER_LOGIN" maxlength="255" value="<?=$arResult["LAST_LOGIN"]?>" placeholder="<?=GetMessage("PLEACEHOLDER_LOGIN")?>"/>
			</div>
		</div>
		<div class="bx-authform-formgroup-container">
			<div class="bx-authform-label-container"><?=GetMessage("AUTH_PASSWORD")?>
				<?if($arParams["NOT_SHOW_LINKS"] != "Y"):?>
				<a href="<?=$arResult["AUTH_FORGOT_PASSWORD_URL"]?>" rel="nofollow"><?=GetMessage("AUTH_FORGOT_PASSWORD_2")?></a>
        		<?endif?>
			</div>
			<div class="bx-authform-input-container">
<?if($arResult["SECURE_AUTH"]):?>
				<div class="bx-authform-psw-protected" id="bx_auth_secure" style="display:none"><div class="bx-authform-psw-protected-desc"><span></span><?echo GetMessage("AUTH_SECURE_NOTE")?></div></div>

<script type="text/javascript">
document.getElementById('bx_auth_secure').style.display = '';
</script>
<?endif?>
				<input type="password" name="USER_PASSWORD" maxlength="255" autocomplete="off" placeholder="<?=GetMessage("PLEACEHOLDER_PASSWORD")?>" />
				<svg class="auth-icon_password_hidden" width="18px" height="18px" onmousedown=TogglePasswordVisibility() ontouchstart=TogglePasswordVisibility() onmouseup=TogglePasswordVisibility() ontouchend=TogglePasswordVisibility() onmouseout=TogglePasswordVisibilityOff()>
                        <use xlink:href="/local/templates/kit_origami/assets/img/sprite.svg#icon_password_hidden"></use>
                </svg>
			</div>
		</div>

<?if($arResult["CAPTCHA_CODE"]):?>
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
<?endif;?>

<?if($arResult["STORE_PASSWORD"] == "Y"):?>
		<div class="bx-authform-formgroup-container">
			<div class="checkbox">
				<div class="bx-filter-param-label main_checkbox">
					<input type="checkbox" id="USER_REMEMBER" name="USER_REMEMBER" value="Y" />
                    <label for="USER_REMEMBER">
                        <span></span><span><?=GetMessage("AUTH_REMEMBER_ME")?></span>
                    </label>
				</div>
			</div>
		</div>
<?endif?>
		<div class="bx-authform-formgroup-container bx-authform-formgroup-container--btn_submit">
			<input type="submit" class="btn auth-btn main_btn" name="Login" value="<?=GetMessage("AUTH_AUTHORIZE")?>" />
		</div>
	</form>

    <noindex>
		<div class="bx-authform-link-container">
			<?/*if($arParams["NOT_SHOW_LINKS"] != "Y"):?>
            <a href="<?=$arResult["AUTH_FORGOT_PASSWORD_URL"]?>" rel="nofollow"><?=GetMessage("AUTH_FORGOT_PASSWORD_2")?></a>
			<?endif*/?>
        <?if($arParams["NOT_SHOW_LINKS"] != "Y" && $arResult["NEW_USER_REGISTRATION"] == "Y" && $arParams["AUTHORIZE_REGISTRATION"] != "Y"):?>
            <a href="<?=$arResult["AUTH_REGISTER_URL"]?>" rel="nofollow"><?=GetMessage("AUTH_REGISTER")?></a>
        <?endif?>
        </div>
    </noindex>

</div>

<script>
function TogglePasswordVisibility() {
  let x = document.getElementsByName("USER_PASSWORD")[0];
  if (x.type === "password") {
    x.type = "text";
  } else {
    x.type = "password";
  }
}

function TogglePasswordVisibilityOff() {
  let x = document.getElementsByName("USER_PASSWORD")[0];
  if (x.type === "text") {
    x.type = "password";
  }
}

</script>


<script type="text/javascript">
<?if (strlen($arResult["LAST_LOGIN"])>0):?>
try{document.form_auth.USER_PASSWORD.focus();}catch(e){}
<?else:?>
try{document.form_auth.USER_LOGIN.focus();}catch(e){}
<?endif?>
</script>
