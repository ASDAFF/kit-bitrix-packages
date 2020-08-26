<?
/**
 * Bitrix Framework
 * @package bitrix
 * @subpackage main
 * @copyright 2001-2014 Bitrix
 */

/**
 * Bitrix vars
 * @global CMain $APPLICATION
 * @var array $arParams
 * @var array $arResult
 * @var CBitrixComponentTemplate $this
 */

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

if ($_REQUEST['ajax_mode'] == "Y")
    $APPLICATION->ShowAjaxHead();
else
    $APPLICATION->ShowHead();

if (ToLower(SITE_CHARSET) == 'windows-1251') {
    foreach ($arResult as $key => $it) {
        $arResult[$key] = iconv('utf-8', 'windows-1251', $it);
    }
}

$this->addExternalCss(SITE_DIR."local/templates/sotbit_origami/components/bitrix/system.auth.registration/origami/style.css");

if($arResult["SHOW_SMS_FIELD"] == true)
{
	CJSCore::Init('phone_auth');
}
?>
<?/*<div class="bx-auth">*/?>
<div class="side-panel__main-header">
    <p class="side-panel__main-title"><?=GetMessage('AUTH_REGISTER');?></p>
</div>
<div class="origami-auth">

<div class="origami-auth__result">
    <?ShowMessage($arParams["~AUTH_RESULT"]);?>
    <?echo GetMessage("AUTH_EMAIL_SENT")?>
    <?echo GetMessage("AUTH_EMAIL_WILL_BE_SENT")?>
</div>

<?//if($arResult["SHOW_EMAIL_SENT_CONFIRMATION"]):?>
<!--	<p class="origami-auth__message">--><?//echo GetMessage("AUTH_EMAIL_SENT")?><!--</p>-->
<?//endif;?>
<!---->
<?//if(!$arResult["SHOW_EMAIL_SENT_CONFIRMATION"] && $arResult["USE_EMAIL_CONFIRMATION"] === "Y"):?>
<!--	<p class="origami-auth__message">--><?//echo GetMessage("AUTH_EMAIL_WILL_BE_SENT")?><!--</p>-->
<?//endif?>



<noindex>



<?if($arResult["SHOW_SMS_FIELD"] == true):?>

			<form method="post" action="<?=$arResult["AUTH_URL"]?>" name="regform">
			<input type="hidden" name="SIGNED_DATA" value="<?=htmlspecialcharsbx($arResult["SIGNED_DATA"])?>" />

			<div class="origami-auth__sms-wrapper">
                <div class="origami-auth__input-sms-wrapper main-input-bg__wrapper">
                    <input id="SMS_CODE" size="30" type="text" name="SMS_CODE" value="<?=htmlspecialcharsbx($arResult["SMS_CODE"])?>"
                           autocomplete="off" class="main-input-bg main-input-bg--gray"/>
                    <label for="SMS_CODE" class="main-label-bg"><?echo GetMessage("main_register_sms_code")?></label>
                </div>
				<input type="submit" name="code_submit_button" value="<?echo GetMessage("main_register_sms_send")?>"
                    class="code_submit_button"/>

			</div>
			</form>


			<script>
			new BX.PhoneAuth({
				containerId: 'bx_register_resend',
				errorContainerId: 'bx_register_error',
				interval: <?=$arResult["PHONE_CODE_RESEND_INTERVAL"]?>,
				data:
					<?=CUtil::PhpToJSObject([
						'signedData' => $arResult["SIGNED_DATA"],
					])?>,
				onError:
					function(response)
					{
						var errorDiv = BX('bx_register_error');
						var errorNode = BX.findChildByClassName(errorDiv, 'errortext');
						errorNode.innerHTML = '';
						for(var i = 0; i < response.errors.length; i++)
						{
							errorNode.innerHTML = errorNode.innerHTML + BX.util.htmlspecialchars(response.errors[i].message) + '<br>';
						}
						errorDiv.style.display = '';
					}
			});
			</script>

			<div id="bx_register_error" style="display:none"><?ShowError("error")?></div>

			<div id="bx_register_resend"></div>

<?elseif(!$arResult["SHOW_EMAIL_SENT_CONFIRMATION"]):?>

				<form method="post" action="<?=$arResult["AUTH_URL"]?>" name="bform" enctype="multipart/form-data" class="register-input-form">
					<input type="hidden" name="AUTH_FORM" value="Y" />
					<input type="hidden" name="TYPE" value="REGISTRATION" />
                    <div class="register-input__name main-input-bg__wrapper">
                        <input id="USER_NAME" type="text" name="USER_NAME" maxlength="50" value="<?=$arResult["USER_NAME"]?>"
                               class="main-input-bg main-input-bg--gray" onchange="isInputFilled(this)"/>
                        <label for="USER_NAME" class="main-label-bg"><?=GetMessage("AUTH_NAME")?></label>
                    </div>
                    <div class="register-input__last-name main-input-bg__wrapper">
                        <input id="USER_LAST_NAME" type="text" name="USER_LAST_NAME" maxlength="50" value="<?=$arResult["USER_LAST_NAME"]?>"
                               class="main-input-bg main-input-bg--gray" onchange="isInputFilled(this)" />
                        <label for="USER_LAST_NAME" class="main-label-bg"><?=GetMessage("AUTH_LAST_NAME")?></label>
                    </div>
                    <div class="register-input__login main-input-bg__wrapper">
					    <input id="USER_LOGIN" type="text" name="USER_LOGIN" maxlength="50" value="<?=$arResult["USER_LOGIN"]?>"
                               class="main-input-bg main-input-bg--gray" onchange="isInputFilled(this)" />
					    <label for="USER_LOGIN" class="main-label-bg"><?=GetMessage("AUTH_LOGIN_MIN")?></label>
                    </div>
                    <div class="register-input__password main-input-bg__wrapper">
                        <input id="USER_PASSWORD" type="password" name="USER_PASSWORD" maxlength="50" value="<?=$arResult["USER_PASSWORD"]?>"
                               class="main-input-bg main-input-bg--gray" autocomplete="off" onchange="isInputFilled(this)"/>
                        <label for="USER_PASSWORD" class="main-label-bg"><?=GetMessage("AUTH_PASSWORD_REQ")?></label>

                        <?if($arResult["SECURE_AUTH"]):?>
                            <span class="bx-auth-secure" id="bx_auth_secure" title="<?echo GetMessage("AUTH_SECURE_NOTE")?>" style="display:none">
									<div class="bx-auth-secure-icon"></div>
							</span>
                            <noscript>
								<span class="bx-auth-secure" title="<?echo GetMessage("AUTH_NONSECURE_NOTE")?>">
									<div class="bx-auth-secure-icon bx-auth-secure-unlock"></div>
								</span>
                            </noscript>
                            <script type="text/javascript">
                                document.getElementById('bx_auth_secure').style.display = 'inline-block';
                            </script>
                        <?endif?>
                    </div>

                    <div class="register-input__confirm-password main-input-bg__wrapper">
                        <input id="USER_CONFIRM_PASSWORD" type="password" name="USER_CONFIRM_PASSWORD" maxlength="50"
                               value="<?=$arResult["USER_CONFIRM_PASSWORD"]?>" class="main-input-bg main-input-bg--gray"
                               autocomplete="off" onchange="isInputFilled(this)"/>
                        <label for="USER_CONFIRM_PASSWORD" class="main-label-bg"><?=GetMessage("AUTH_CONFIRM")?></label>
                    </div>

                    <?if($arResult["EMAIL_REGISTRATION"]):?>
                        <div class="register-input__user-email main-input-bg__wrapper">
                            <input id="USER_EMAIL" type="text" name="USER_EMAIL" maxlength="255"
                                   value="<?=$arResult["USER_EMAIL"]?>" class="main-input-bg main-input-bg--gray"
                                   onchange="isInputFilled(this)"/>
                            <?echo '<label for="USER_EMAIL" class="main-label-bg">'?>
                            <?=GetMessage("AUTH_EMAIL")?>
                            <?if($arResult["EMAIL_REQUIRED"]):?>
                                <span class="starrequired">*</span>
                            <?endif?>
                            <?echo '</label>'?>
                        </div>
                    <?endif?>


                    <?if($arResult["PHONE_REGISTRATION"]):?>
                        <div class="register-input__phone-registration main-input-bg__wrapper">
                                <input id="USER_PHONE_NUMBER" type="text" name="USER_PHONE_NUMBER" maxlength="255"
                                       value="<?=$arResult["USER_PHONE_NUMBER"]?>" class="main-input-bg main-input-bg--gray"
                                       onchange="isInputFilled(this)"/>
                                <?echo '<label for="USER_PHONE_NUMBER" class="main-label-bg">'?>
                                <?echo GetMessage("main_register_phone_number")?>
                                <?if($arResult["PHONE_REQUIRED"]):?>
                                    <span class="starrequired">*</span>
                                <?endif?>
                                <?echo '</label>'?>
                        </div>
                    <?endif?>

<?// ********************* User properties ***************************************************?>
							<?if($arResult["USER_PROPERTIES"]["SHOW"] == "Y"):?>
								<?=strlen(trim($arParams["USER_PROPERTY_NAME"])) > 0 ? $arParams["USER_PROPERTY_NAME"] : GetMessage("USER_TYPE_EDIT_TAB")?>
								<?foreach ($arResult["USER_PROPERTIES"]["DATA"] as $FIELD_NAME => $arUserField):?>
									<?if ($arUserField["MANDATORY"]=="Y"):?>
										<span class="starrequired">*</span>
									<?endif;?>
									<?=$arUserField["EDIT_FORM_LABEL"]?>:
									<?$APPLICATION->IncludeComponent(
										"bitrix:system.field.edit",
										$arUserField["USER_TYPE"]["USER_TYPE_ID"],
										array(
											"bVarsFromForm" => $arResult["bVarsFromForm"],
											"arUserField" => $arUserField,
											"form_name" => "bform"),
										null,
										array("HIDE_ICONS"=>"Y")
									);?>
								<?endforeach;?>
							<?endif;?>
<?// ******************** /User properties ***************************************************

	/* CAPTCHA */
	if ($arResult["USE_CAPTCHA"] == "Y")
	{
		?>


		<div class="origami-auth__captcha-field">
			<?/*<b><?=GetMessage("CAPTCHA_REGF_TITLE")?></b>*/?>
            <div class="register-input__captha">
                <input type="hidden" name="captcha_sid" value="<?=$arResult["CAPTCHA_CODE"]?>" />
            </div>
			<div class="register-input__captcha-controls main-input-bg__wrapper">
				<input id="captcha_sid" type="text" name="captcha_word" maxlength="50" value="" autocomplete="off"
                       class="main-input-bg main-input-bg--gray" onchange="isInputFilled(this)"/>
                <label for="captcha_sid" class="main-label-bg"><?=GetMessage("CAPTCHA_REGF_PROMT")?>:</label>
			</div>

            <div class="feedback_block__captcha">
                <img src="/bitrix/tools/captcha.php?captcha_sid=<?=$arResult["CAPTCHA_CODE"]?>" width="180" height="40" alt="CAPTCHA" />
                <div class="feedback_block__captcha_reload" onclick="reloadCaptcha(this,'<?=SITE_DIR?>');return false;">
                    <svg class="icon_refresh" width="16" height="14">
                        <use xlink:href="/local/templates/sotbit_origami/assets/img/sprite.svg#icon_refresh"></use>
                    </svg>
                </div>
            </div>
		</div>


		<?
	}
	/* CAPTCHA */
	?>
				<?$APPLICATION->IncludeComponent("bitrix:main.userconsent.request", "",
					array(
						"ID" => COption::getOptionString("main", "new_user_agreement", ""),
						"IS_CHECKED" => "Y",
						"AUTO_SAVE" => "N",
						"IS_LOADED" => "Y",
						"ORIGINATOR_ID" => $arResult["AGREEMENT_ORIGINATOR_ID"],
						"ORIGIN_ID" => $arResult["AGREEMENT_ORIGIN_ID"],
						"INPUT_NAME" => $arResult["AGREEMENT_INPUT_NAME"],
						"REPLACE" => array(
							"button_caption" => GetMessage("AUTH_REGISTER"),
							"fields" => array(
								rtrim(GetMessage("AUTH_NAME"), ":"),
								rtrim(GetMessage("AUTH_LAST_NAME"), ":"),
								rtrim(GetMessage("AUTH_LOGIN_MIN"), ":"),
								rtrim(GetMessage("AUTH_PASSWORD_REQ"), ":"),
								rtrim(GetMessage("AUTH_EMAIL"), ":"),
							)
						),
					)
				);?>

		<input type="submit" name="Register" value="<?=GetMessage("AUTH_REGISTER")?>" class="register-input__btn-submit"/>
</form>

	<?/*<p><?echo $arResult["GROUP_POLICY"]["PASSWORD_REQUIREMENTS"];?></p>*/?>
	<?/*<p><span class="starrequired">*</span><?=GetMessage("AUTH_REQ")?></p>*/?>

<p class="origami-auth__gotoauthorize"><a href="<?=$arResult["AUTH_AUTH_URL"]?>" rel="nofollow"><?=GetMessage("AUTH_AUTH")?></a></p>

<script type="text/javascript">
document.bform.USER_NAME.focus();
</script>

<?endif?>

</noindex>
</div>
