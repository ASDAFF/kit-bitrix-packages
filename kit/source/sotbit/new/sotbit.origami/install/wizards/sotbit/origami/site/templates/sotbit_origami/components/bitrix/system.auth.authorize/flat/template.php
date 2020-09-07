<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

/**
 * @global CMain $APPLICATION
 * @var array $arParams
 * @var array $arResult
 * @var CBitrixComponent $component
 */

use Bitrix\Main\Page\Asset;
//one css for all system.auth.* forms
$APPLICATION->SetAdditionalCSS("/bitrix/css/main/system.auth/flat/style.css");

Asset::getInstance()->addcss("/local/templates/sotbit_origami/components/bitrix/system.auth.registration/flat/style.css");

?>

<div class="registration_form-wrapper">
    <div class="bx-authform">

        <?
        if (!empty($arParams["~AUTH_RESULT"])):
            $text = str_replace(array("<br>", "<br />"), "\n", $arParams["~AUTH_RESULT"]["MESSAGE"]);
            ?>
            <div class="alert alert-danger"><?= nl2br(htmlspecialcharsbx($text)) ?></div>
        <? endif ?>

        <?
        if ($arResult['ERROR_MESSAGE'] <> ''):
            $text = str_replace(array("<br>", "<br />"), "\n", $arResult['ERROR_MESSAGE']);
            ?>
            <div class="alert alert-danger"><?= nl2br(htmlspecialcharsbx($text)) ?></div>
        <? endif ?>

        <? if ($arResult["AUTH_SERVICES"]): ?>
            <? /*
$APPLICATION->IncludeComponent("bitrix:socserv.auth.form",
	"flat",
	array(
		"AUTH_SERVICES" => $arResult["AUTH_SERVICES"],
		"AUTH_URL" => $arResult["AUTH_URL"],
		"POST" => $arResult["POST"],
	),
	$component,
	array("HIDE_ICONS"=>"Y")
);
*/ ?>

        <? endif ?>

        <form name="form_auth" method="post" target="_top" action="<?= $arResult["AUTH_URL"] ?>">

            <input type="hidden" name="AUTH_FORM" value="Y"/>
            <input type="hidden" name="TYPE" value="AUTH"/>
            <? if (strlen($arResult["BACKURL"]) > 0): ?>
                <input type="hidden" name="backurl" value="<?= $arResult["BACKURL"] ?>"/>
            <? endif ?>
            <? foreach ($arResult["POST"] as $key => $value): ?>
                <input type="hidden" name="<?= $key ?>" value="<?= $value ?>"/>
            <? endforeach ?>

            <!-- LOGIN SOCIALS -->
            <div class="auth-login_socials">
                <div class="login_social_icon">
                    <svg class="login_social_icon_svg-fb" width="10px" height="20px">
                        <use xlink:href="/local/templates/sotbit_origami/assets/img/sprite.svg#icon_facebook"></use>
                    </svg>
                </div>

                <div class="login_social_icon">
                    <svg class="login_social_icon_svg-vk" width="20px" height="12px">
                        <use xlink:href="/local/templates/sotbit_origami/assets/img/sprite.svg#icon_vk"></use>
                    </svg>
                </div>

                <div class="login_social_icon">
                    <svg class="login_social_icon_svg-tw" width="20px" height="16px">
                        <use xlink:href="/local/templates/sotbit_origami/assets/img/sprite.svg#icon_twitter"></use>
                    </svg>
                </div>

                <div class="login_social_icon">
                    <svg class="login_social_icon_svg-google" width="16px" height="16px">
                        <use xlink:href="/local/templates/sotbit_origami/assets/img/sprite.svg#icon_google"></use>
                    </svg>
                </div>

                <div class="login_social_icon">
                    <svg class="login_social_icon_svg-mailru" width="20px" height="20px">
                        <use xlink:href="/local/templates/sotbit_origami/assets/img/sprite.svg#icon_mailru"></use>
                    </svg>
                </div>

                <div class="login_social_icon">
                    <svg class="login_social_icon_svg-ya" width="10px" height="20px">
                        <use xlink:href="/local/templates/sotbit_origami/assets/img/sprite.svg#icon_yandex"></use>
                    </svg>
                </div>

                <div class="login_social_icon">
                    <svg class="login_social_icon_svg-telega" width="20px" height="16px">
                        <use xlink:href="/local/templates/sotbit_origami/assets/img/sprite.svg#icon_telegram"></use>
                    </svg>
                </div>

                <div class="login_social_icon">
                    <svg class="login_social_icon_svg-ok" width="12px" height="20px">
                        <use xlink:href="/local/templates/sotbit_origami/assets/img/sprite.svg#icon_odnoklassniki"></use>
                    </svg>
                </div>

                <div class="login_social_icon">
                    <svg class="login_social_icon_svg-boxc" width="26px" height="14px">
                        <use xlink:href="/local/templates/sotbit_origami/assets/img/sprite.svg#icon_box_company"></use>
                    </svg>
                </div>

                <div class="login_social_icon">
                    <svg class="login_social_icon_svg-24" width="20px" height="14px">
                        <use xlink:href="/local/templates/sotbit_origami/assets/img/sprite.svg#icon_bitrix24"></use>
                    </svg>
                </div>
            </div>
            <!-- /LOGIN SOCIALS -->

            <!-- LOGIN -->
            <div class="bx-authform-formgroup-container">
                <div class="bx-authform-label-container"><?= GetMessage("AUTH_LOGIN") ?>
                    <span class="bx-authform-label-container_important-label"><?= GetMessage("IMPORTANT_FIELD") ?></span>
                </div>
                <div class="bx-authform-input-container">
                    <input
                            type="text"
                            name="USER_LOGIN"
                            maxlength="255"
                            value="<?= $arResult["LAST_LOGIN"] ?>"
                            placeholder="<? echo GetMessage("AUTH_LOGIN_PLACEHOLDER") ?>"
                    />
                </div>
            </div>
            <!-- /LOGIN -->

            <!-- PASSWORD -->
            <div class="bx-authform-formgroup-container">

                <div class="authform-pass_labels-wrapper">
                    <!-- PASS LABEL -->
                    <div class="bx-authform-label-container"><?= GetMessage("AUTH_PASSWORD") ?>
                        <span class="bx-authform-label-container_important-label"><?= GetMessage("IMPORTANT_FIELD") ?></span>
                    </div>
                    <!-- / PASS LABEL -->

                    <!-- FORGOT PASS -->
                    <? if ($arParams["NOT_SHOW_LINKS"] != "Y"): ?>
                        <div class="bx-authform-link-container">
                            <a href="<?= $arResult["AUTH_FORGOT_PASSWORD_URL"] ?>"
                               rel="nofollow"><?= GetMessage("AUTH_FORGOT_PASSWORD_2") ?></a>
                        </div>
                    <? endif ?>
                    <!-- / FORGOT PASS -->
                </div>

                <!-- PASS INPUT -->
                <div class="bx-authform-input-container">
                    <? if ($arResult["SECURE_AUTH"]): ?>
                        <div class="bx-authform-psw-protected" id="bx_auth_secure" style="display:none">
                            <div class="bx-authform-psw-protected-desc">
                                <span></span><? echo GetMessage("AUTH_SECURE_NOTE") ?></div>
                        </div>
                        <script type="text/javascript">
                            document.getElementById('bx_auth_secure').style.display = '';
                        </script>
                    <? endif ?>
                    <input type="password"
                           name="USER_PASSWORD"
                           maxlength="255"
                           autocomplete="off"
                           placeholder="<? echo GetMessage("AUTH_PASS_PLACEHOLDER") ?>"/>
                    <svg class="auth-icon_password_hidden" width="18px" height="18px">
                        <use xlink:href="/local/templates/sotbit_origami/assets/img/sprite.svg#icon_password_hidden"></use>
                    </svg>
                </div>
                <!-- / PASS INPUT -->
            </div>
            <!-- /PASSWORD -->

            <!-- CAPTCHA -->
            <? if ($arResult["CAPTCHA_CODE"]): ?>
                <div class="feedback_block__captcha">
                    <p class="feedback_block__captcha_text">
                        <?= GetMessage('CAPTCHA_TITLE') ?>
                    </p>
                    <div class="feedback_block__captcha_input">
                        <input type="text" name="captcha_word" size="30" maxlength="50" value="" required/>
                    </div>
                    <div class="feedback_block__captcha_img">
                        <input type="hidden" name="captcha_sid" value="<?= $arResult["CAPTCHA_CODE"] ?>"/>
                        <img src="/bitrix/tools/captcha.php?captcha_sid=<?= $arResult["CAPTCHA_CODE"] ?>" width="180"
                             height="40"/>
                        <div class="feedback_block__captcha_reload"
                             onclick="reloadCaptcha(this,'<?= SITE_DIR ?>');return false;"></div>
                    </div>
                </div>
            <? endif; ?>
            <!-- / CAPTCHA -->

            <? if ($arResult["STORE_PASSWORD"] == "Y"): ?>
                <div class="bx-authform-formgroup-container bx-authform-formgroup-container--flat-remember">
                    <div class="checkbox">
                        <label class="bx-filter-param-label">
                            <input type="checkbox" id="USER_REMEMBER" name="USER_REMEMBER" value="Y"/>
                            <span class="bx-filter-param-text"><?= GetMessage("AUTH_REMEMBER_ME") ?></span>
                        </label>
                    </div>
                </div>
            <? endif ?>
            <div class="bx-authform-formgroup-container login_button">
                <input type="submit" class="btn auth-btn" name="Login" value="<?= GetMessage("AUTH_AUTHORIZE") ?>"/>
            </div>
        </form>

        <noindex>
            <div class="bx-authform-link-container">
                <? if ($arParams["NOT_SHOW_LINKS"] != "Y" && $arResult["NEW_USER_REGISTRATION"] == "Y" && $arParams["AUTHORIZE_REGISTRATION"] != "Y"): ?>
                    <a href="<?= $arResult["AUTH_REGISTER_URL"] ?>"
                       rel="nofollow"><?= GetMessage("AUTH_REGISTER") ?></a>
                <? endif ?>
            </div>
        </noindex>

    </div>
</div>

<style>
    @media (max-width: 991px) {
        .block_main_left_menu__content.active > h1 {
            text-align: center;
        }
    }

    .puzzle_block.no-padding .block_main_left_menu__content > h1 {
        text-align: center;
    }
</style>

<script type="text/javascript">
    <?if (strlen($arResult["LAST_LOGIN"]) > 0):?>
    try {
        document.form_auth.USER_PASSWORD.focus();
    } catch (e) {
    }
    <?else:?>
    try {
        document.form_auth.USER_LOGIN.focus();
    } catch (e) {
    }
    <?endif?>
</script>
