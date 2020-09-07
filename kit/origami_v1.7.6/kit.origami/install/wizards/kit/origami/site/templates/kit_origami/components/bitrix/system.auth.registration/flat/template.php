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

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
use Bitrix\Main\Page\Asset;
//one css for all system.auth.* forms
$APPLICATION->SetAdditionalCSS("/bitrix/css/main/system.auth/flat/style.css");
Asset::getInstance()->addcss("/local/templates/kit_origami/components/bitrix/system.auth.registration/flat/style.css");

?>

<div class="registration_form-wrapper">
    <div class="bx-authform">

        <?
        if (!empty($arParams["~AUTH_RESULT"])):
            $text = str_replace(array("<br>", "<br />"), "\n", $arParams["~AUTH_RESULT"]["MESSAGE"]);
            ?>
            <div class="alert <?= ($arParams["~AUTH_RESULT"]["TYPE"] == "OK" ? "alert-success" : "alert-danger") ?>"><?= nl2br(htmlspecialcharsbx($text)) ?></div>
        <? endif ?>

        <? if ($arResult["USE_EMAIL_CONFIRMATION"] === "Y" && is_array($arParams["AUTH_RESULT"]) && $arParams["AUTH_RESULT"]["TYPE"] === "OK"): ?>
            <div class="alert alert-success"><? echo GetMessage("AUTH_EMAIL_SENT") ?></div>
        <? else: ?>

        <? if ($arResult["USE_EMAIL_CONFIRMATION"] === "Y"): ?>
            <div class="alert alert-warning"><? echo GetMessage("AUTH_EMAIL_WILL_BE_SENT") ?></div>
        <? endif ?>

            <noindex>
                <form method="post" action="<?= $arResult["AUTH_URL"] ?>" name="bform" enctype="multipart/form-data">
                    <? if ($arResult["BACKURL"] <> ''): ?>
                        <input type="hidden" name="backurl" value="<?= $arResult["BACKURL"] ?>"/>
                    <? endif ?>
                    <input type="hidden" name="AUTH_FORM" value="Y"/>
                    <input type="hidden" name="TYPE" value="REGISTRATION"/>

                    <div class="bx-authform-formgroup-container">
                        <div class="bx-authform-label-container"><?= GetMessage("AUTH_NAME") ?></div>
                        <div class="bx-authform-input-container">
                            <input type="text" name="USER_NAME" maxlength="255" value="<?= $arResult["USER_NAME"] ?>"
                                   placeholder="<?= GetMessage("AUTH_NAME_PLACEHOLDER") ?>"/>
                        </div>
                    </div>

                    <div class="bx-authform-formgroup-container">
                        <div class="bx-authform-label-container"><?= GetMessage("AUTH_LAST_NAME") ?></div>
                        <div class="bx-authform-input-container">
                            <input type="text" name="USER_LAST_NAME" maxlength="255"
                                   value="<?= $arResult["USER_LAST_NAME"] ?>"
                                   placeholder="<?= GetMessage("AUTH_LAST_NAME_PLACEHOLDER") ?>"/>
                        </div>
                    </div>

                    <div class="bx-authform-formgroup-container">
                        <div class="bx-authform-label-container">
                            <?= GetMessage("AUTH_LOGIN_MIN") ?>
                            <span class="bx-authform-label-container_important-label"><?= GetMessage("IMPORTANT_FIELD") ?></span>
                        </div>
                        <div class="bx-authform-input-container">
                            <input type="text" name="USER_LOGIN" maxlength="255" value="<?= $arResult["USER_LOGIN"] ?>"
                                   placeholder="<?= GetMessage("AUTH_LOGIN_PLACEHOLDER") ?>"/>
                        </div>
                    </div>

                    <div class="bx-authform-formgroup-container">
                        <div class="bx-authform-label-container">
                            <?= GetMessage("AUTH_PASSWORD_REQ") ?>
                            <span class="bx-authform-label-container_important-label"><?= GetMessage("IMPORTANT_FIELD") ?></span>
                        </div>
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
                            <input type="password" name="USER_PASSWORD" maxlength="255"
                                   value="<?= $arResult["USER_PASSWORD"] ?>" autocomplete="off"
                                   placeholder="<?= GetMessage("AUTH_PASS_PLACEHOLDER") ?>"/>
                            <svg class="auth-icon_password_hidden" width="18px" height="18px">
                                <use xlink:href="/local/templates/kit_origami/assets/img/sprite.svg#icon_password_hidden"></use>
                            </svg>
                        </div>
                    </div>

                    <div class="bx-authform-formgroup-container">
                        <div class="bx-authform-label-container"><?= GetMessage("AUTH_CONFIRM") ?>
                            <span class="bx-authform-label-container_important-label"><?= GetMessage("IMPORTANT_FIELD") ?></span>
                        </div>
                        <div class="bx-authform-input-container">
                            <? if ($arResult["SECURE_AUTH"]): ?>
                                <div class="bx-authform-psw-protected" id="bx_auth_secure_conf" style="display:none">
                                    <div class="bx-authform-psw-protected-desc">
                                        <span></span><? echo GetMessage("AUTH_SECURE_NOTE") ?></div>
                                </div>

                                <script type="text/javascript">
                                    document.getElementById('bx_auth_secure_conf').style.display = '';
                                </script>
                            <? endif ?>
                            <input type="password" name="USER_CONFIRM_PASSWORD" maxlength="255"
                                   value="<?= $arResult["USER_CONFIRM_PASSWORD"] ?>" autocomplete="off"
                                   placeholder="<?= GetMessage("AUTH_PASS_PLACEHOLDER") ?>"
                            />
                            <svg class="auth-icon_password_hidden" width="18px" height="18px">
                                <use xlink:href="/local/templates/kit_origami/assets/img/sprite.svg#icon_password_hidden"></use>
                            </svg>
                        </div>
                    </div>

                    <div class="bx-authform-formgroup-container">
                        <div class="bx-authform-label-container"><? if ($arResult["EMAIL_REQUIRED"]): ?><? endif ?><?= GetMessage("AUTH_EMAIL") ?>
                            <span class="bx-authform-label-container_important-label"><?= GetMessage("IMPORTANT_FIELD") ?></span>
                        </div>
                        <div class="bx-authform-input-container">
                            <input type="text" name="USER_EMAIL" maxlength="255" value="<?= $arResult["USER_EMAIL"] ?>"
                                   placeholder="<?= GetMessage("AUTH_EMAIL_PLACEHOLDER") ?>"/>
                        </div>
                    </div>

                    <? if ($arResult["USER_PROPERTIES"]["SHOW"] == "Y"): ?>
                        <? foreach ($arResult["USER_PROPERTIES"]["DATA"] as $FIELD_NAME => $arUserField): ?>

                            <div class="bx-authform-formgroup-container">
                                <div class="bx-authform-label-container"><? if ($arUserField["MANDATORY"] == "Y"): ?><? endif ?><?= $arUserField["EDIT_FORM_LABEL"] ?>
                                    <span class="bx-authform-label-container_important-label"><?= GetMessage("IMPORTANT_FIELD") ?></span>
                                </div>
                                <div class="bx-authform-input-container">
                                    <?
                                    $APPLICATION->IncludeComponent(
                                        "bitrix:system.field.edit",
                                        $arUserField["USER_TYPE"]["USER_TYPE_ID"],
                                        array(
                                            "bVarsFromForm" => $arResult["bVarsFromForm"],
                                            "arUserField" => $arUserField,
                                            "form_name" => "bform"
                                        ),
                                        null,
                                        array("HIDE_ICONS" => "Y")
                                    );
                                    ?>
                                </div>
                            </div>

                        <? endforeach; ?>
                    <? endif; ?>
                    <? if ($arResult["USE_CAPTCHA"] == "Y"): ?>
                        <div class="feedback_block__captcha">
                            <p class="feedback_block__captcha_text">
                                <?= GetMessage('CAPTCHA_TITLE') ?>
                                <span class="bx-authform-label-container_important-label"><?= GetMessage("IMPORTANT_FIELD") ?></span>
                            </p>

                            <div class="registration-captcha_input-wrapper">
                                <div class="feedback_block__captcha_input bx-authform-input-container">
                                    <input type="text" name="captcha_word" size="30" maxlength="50" value="" required/>
                                </div>

                                <div class="feedback_block__captcha_img">
                                    <input type="hidden" name="captcha_sid" value="<?= $arResult["CAPTCHA_CODE"] ?>"/>
                                    <img src="/bitrix/tools/captcha.php?captcha_sid=<?= $arResult["CAPTCHA_CODE"] ?>"
                                         width="180" height="40"/>
                                    <div class="feedback_block__captcha_reload"
                                         onclick="reloadCaptcha(this,'<?= SITE_DIR ?>');return false;"></div>
                                </div>
                            </div>

                        </div>
                    <? endif ?>
                    <div class="/*bx-authform-formgroup-container*/">
                        <div class="/*bx-authform-label-container*/">
                        </div>
                        <div class="/*bx-authform-input-container*/">
                            <? $APPLICATION->IncludeComponent("bitrix:main.userconsent.request", "",
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
                            ); ?>
                        </div>
                    </div>
                    <div class="bx-authform-formgroup-container btn-registration">
                        <input type="submit" class="btn auth-btn" name="Register"
                               value="<?= GetMessage("AUTH_REGISTER") ?>"/>
                    </div>

                    <div class="bx-authform-link-container">
                        <a href="<?= $arResult["AUTH_AUTH_URL"] ?>" rel="nofollow"><?= GetMessage("AUTH_AUTH") ?></a>
                    </div>

                </form>
            </noindex>

            <script type="text/javascript">
                document.bform.USER_NAME.focus();
            </script>

        <? endif ?>
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
