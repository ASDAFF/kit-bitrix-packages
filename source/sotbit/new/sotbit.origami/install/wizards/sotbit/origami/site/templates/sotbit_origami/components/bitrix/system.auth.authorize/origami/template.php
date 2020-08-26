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


use Bitrix\Main\Localization\Loc;
if ($_REQUEST['ajax_mode'] == "Y")
    $APPLICATION->ShowAjaxHead();
else
    $APPLICATION->ShowHead();

//one css for all system.auth.* forms
$this->addExternalCss(SITE_DIR."local/templates/sotbit_origami/components/bitrix/system.auth.authorize/origami/style.css");
if (ToLower(SITE_CHARSET) == 'windows-1251') {
    foreach ($arResult as $key => &$it) {
        $it = iconv('utf-8', 'windows-1251', $it);
    }
}
?>
<div class="side-panel__main-header">
    <p class="side-panel__main-title"><?=Loc::getMessage('AUTH');?></p>
</div>
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
	"origami",
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

		<div class="bx-authform-formgroup-container bx-authform-formgroup-container--login">
			<div class="bx-authform-input-container main-input-bg__wrapper">
				<input id="USER_LOGIN" type="text" name="USER_LOGIN" maxlength="255" value="<?=$arResult["LAST_LOGIN"]?>" class="main-input-bg main-input-bg--gray" onchange="isInputFilled(this)"/>
                <label for="USER_LOGIN" class="main-label-bg"><?=GetMessage("AUTH_LOGIN")?></label>
			</div>
		</div>
		<div class="bx-authform-formgroup-container">
			<div class="bx-authform-input-container main-input-bg__wrapper">
<?if($arResult["SECURE_AUTH"]):?>
				<div class="bx-authform-psw-protected" id="bx_auth_secure" style="display:none"><div class="bx-authform-psw-protected-desc"><span></span><?echo GetMessage("AUTH_SECURE_NOTE")?></div></div>

<script type="text/javascript">
document.getElementById('bx_auth_secure').style.display = '';
</script>
<?endif?>
				<input id="USER_PASSWORD" type="password" name="USER_PASSWORD" maxlength="255" autocomplete="off" class="main-input-bg main-input-bg--gray" onchange="isInputFilled(this)" />
                <label for="USER_PASSWORD" class="main-label-bg"><?=GetMessage("AUTH_PASSWORD")?></label>
                <svg class="auth-icon_password_hidden" width="18px" height="18px" onmousedown=TogglePasswordVisibility() ontouchstart=TogglePasswordVisibility() onmouseup=TogglePasswordVisibility() ontouchend=TogglePasswordVisibility() onmouseout=TogglePasswordVisibilityOff()>
                        <use xlink:href="/local/templates/sotbit_origami/assets/img/sprite.svg#icon_password_hidden"></use>
                </svg>
			</div>
            <?if($arParams["NOT_SHOW_LINKS"] != "Y"):?>
                <a href="<?=$arResult["AUTH_FORGOT_PASSWORD_URL"]?>" rel="nofollow" class="bx-authform-forgot-link main-color-txt"><?=GetMessage("AUTH_FORGOT_PASSWORD_2")?></a>
            <?endif?>
		</div>

<?if($arResult["USE_CAPTCHA"] == "Y"):?>
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
		<div class="bx-authform-formgroup-container bx-authform-formgroup-container--user-remember">
			<div class="bx-checkbox">
				<label class="bx-filter-param-label main-color-txt-hov-base">
					<input class="bx-authform-formgroup__checkbox" type="checkbox" id="USER_REMEMBER" name="USER_REMEMBER" value="Y" />
					<span class="bx-filter-param-text"><?=GetMessage("AUTH_REMEMBER_ME")?></span>
				</label>
			</div>
		</div>
<?endif?>
		<div class="bx-authform-formgroup-container bx-authform-formgroup-container--btn_submit">
			<input type="submit" class="btn auth-btn" name="Login" value="<?=GetMessage("AUTH_AUTHORIZE")?>" />
		</div>
	</form>

    <noindex>
		<div class="bx-authform-link-container">
			<?/*if($arParams["NOT_SHOW_LINKS"] != "Y"):?>
            <a href="<?=$arResult["AUTH_FORGOT_PASSWORD_URL"]?>" rel="nofollow"><?=GetMessage("AUTH_FORGOT_PASSWORD_2")?></a>
			<?endif*/?>
        <?if($arParams["NOT_SHOW_LINKS"] != "Y" && $arResult["NEW_USER_REGISTRATION"] == "Y" && $arParams["AUTHORIZE_REGISTRATION"] != "Y"):?>
            <a href="<?=$arResult["AUTH_REGISTER_URL"]?>" rel="nofollow" class="bx-authform-link"><?=GetMessage("AUTH_REGISTER")?></a>
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
