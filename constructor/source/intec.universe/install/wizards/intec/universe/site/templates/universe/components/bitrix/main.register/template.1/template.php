<?
/**
 * Bitrix Framework
 * @package bitrix
 * @subpackage main
 * @copyright 2001-2014 Bitrix
 */

use Bitrix\Main\Localization\Loc;
use intec\core\bitrix\Component;
use intec\core\helpers\Html;
use intec\core\helpers\JavaScript;

/**
 * Bitrix vars
 * @global CMain $APPLICATION
 * @global CUser $USER
 * @param array $arParams
 * @param array $arResult
 * @param CBitrixComponentTemplate $this
 */

if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)
	die();

if ($arResult["SHOW_SMS_FIELD"] == true)
    CJSCore::Init('phone_auth');

$sTemplateId = Html::getUniqueId(null, Component::getUniqueId($this, true));
?>

<div class="ns-bitrix c-main-register c-main-register-template-1" id="<?= $sTemplateId ?>">
    <script>
        BX.message({
            phone_auth_resend: '<?=GetMessageJS('MAIN_REGISTER_TEMPLATE1_phone_auth_resend')?>',
            phone_auth_resend_link: '<?=GetMessageJS('MAIN_REGISTER_TEMPLATE1_phone_auth_resend_link')?>',
        });
    </script>

    <?if($USER->IsAuthorized()){?>
        <p>
            <?= Loc::getMessage("MAIN_REGISTER_TEMPLATE1_AUTHORIZED") ?>
        </p>
    <? } else { ?>
        <?php
        if (count($arResult["ERRORS"]) > 0) {
            foreach ($arResult["ERRORS"] as $key => $error)
                if (intval($key) == 0 && $key !== 0)
                    $arResult["ERRORS"][$key] = str_replace("#FIELD_NAME#", "&quot;" . Loc::getMessage("MAIN_REGISTER_TEMPLATE1_REGISTER_FIELD_" . $key) . "&quot;", $error);

            ShowError(implode("<br />", $arResult["ERRORS"]));

        } else if ($arResult["USE_EMAIL_CONFIRMATION"] === "Y") { ?>
            <p><?echo Loc::getMessage("MAIN_REGISTER_TEMPLATE1_REGISTER_EMAIL_WILL_BE_SENT")?></p>
        <?php } ?>

        <?php if ($arResult["SHOW_SMS_FIELD"]) { ?>
            <form class="main-register-form" method="post" action="<?=POST_FORM_ACTION_URI?>" name="regform">
                <?php if($arResult["BACKURL"] <> '') { ?>
                    <input type="hidden" name="backurl" value="<?=$arResult["BACKURL"]?>" />
                <?php } ?>
                <input type="hidden" name="SIGNED_DATA" value="<?=htmlspecialcharsbx($arResult["SIGNED_DATA"])?>" />
                <div class="main-register-form-field">
                    <div class="main-register-field-caption">
                        <?= Loc::getMessage('MAIN_REGISTER_TEMPLATE1_SMS') ?>
                            <span class="main-register-starrequired">*</span>
                    </div>
                    <div class="main-register-field-value">
                        <input size="30" type="text" name="SMS_CODE" value="<?=htmlspecialcharsbx($arResult["SMS_CODE"])?>" autocomplete="off" placeholder="" data-role="input" class="main-register-field-input">
                    </div>
                </div>
                <div class="main-register-form-field">
                    <div id="bx_main_register_error" style="display:none"><?ShowError("error")?></div>
                    <div id="bx_main_register_resend"></div>
                </div>
                <div class="main-register-button-wrap">
                    <input type="submit"
                           name="code_submit_button"
                           value="<?= Loc::getMessage("MAIN_REGISTER_TEMPLATE1_SMS_SEND")?>"
                           class="intec-button intec-button-cl-common intec-button-lg main-register-button"/>
                </div>
            </form>

            <script>
                new BX.PhoneAuth({
                    containerId: 'bx_main_register_resend',
                    errorContainerId: 'bx_main_register_error',
                    interval: <?=$arResult["PHONE_CODE_RESEND_INTERVAL"]?>,
                    data:
                    <?=CUtil::PhpToJSObject([
                        'signedData' => $arResult["SIGNED_DATA"],
                    ])?>,
                    onError:
                        function(response)
                        {
                            var errorDiv = BX('bx_main_register_error');
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
        <?php } else { ?>
            <form class="main-register-form" method="post" action="<?=POST_FORM_ACTION_URI?>" name="regform" enctype="multipart/form-data">
                <?php if($arResult["BACKURL"] <> '') { ?>
                    <input type="hidden" name="backurl" value="<?=$arResult["BACKURL"]?>" />
                <?php } ?>

                <div class="main-register-form-body">
                    <?php foreach ($arResult["SHOW_FIELDS"] as $FIELD) { ?>
                        <?php if ($FIELD == 'PERSONAL_PHOTO' ||
                                  $FIELD == 'WORK_LOGO') continue; ?>

                        <?php if ($FIELD == "AUTO_TIME_ZONE" && $arResult["TIME_ZONE_ENABLED"] == true) { ?>
                            <div class="">
                                <?= Loc::getMessage("MAIN_REGISTER_TEMPLATE1_main_profile_time_zones_auto") ?>
                                <?php if ($arResult["REQUIRED_FIELDS_FLAGS"][$FIELD] == "Y") { ?>
                                    <span class="main-register-starrequired">*</span>
                                <?php } ?>

                                <select name="REGISTER[AUTO_TIME_ZONE]"
                                        onchange="this.form.elements['REGISTER[TIME_ZONE]'].disabled=(this.value != 'N')"
                                        data-role="input"
                                        class="main-register-field-select">
                                    <option value="">
                                        <?= Loc::getMessage("MAIN_REGISTER_TEMPLATE1_main_profile_time_zones_auto_def")?>
                                    </option>
                                    <option value="Y"<?= $arResult["VALUES"][$FIELD] == "Y" ? " selected=\"selected\"" : ""?>>
                                        <?= Loc::getMessage("MAIN_REGISTER_TEMPLATE1_main_profile_time_zones_auto_yes")?>
                                    </option>
                                    <option value="N"<?= $arResult["VALUES"][$FIELD] == "N" ? " selected=\"selected\"" : ""?>>
                                        <?= Loc::getMessage("MAIN_REGISTER_TEMPLATE1_main_profile_time_zones_auto_no")?>
                                    </option>
                                </select>

                            </div>
                            <div class="">
                                <div>
                                    <?= Loc::getMessage("MAIN_REGISTER_TEMPLATE1_main_profile_time_zones_zones")?>
                                </div>
                                <div>
                                    <select name="REGISTER[TIME_ZONE]"
                                            <?if(!isset($_REQUEST["REGISTER"]["TIME_ZONE"])) echo 'disabled="disabled"'?>
                                            data-role="input"
                                            class="main-register-field-select">
                                        <?php foreach($arResult["TIME_ZONE_LIST"] as $tz=>$tz_name) { ?>
                                            <option value="<?=htmlspecialcharsbx($tz)?>"
                                                <?= $arResult["VALUES"]["TIME_ZONE"] == $tz ? " selected=\"selected\"" : ""?>>
                                                <?= htmlspecialcharsbx($tz_name)?>
                                            </option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                        <?php } else { ?>

                            <div class="main-register-form-field">
                                <div class="main-register-field-caption">
                                    <?= Loc::getMessage("MAIN_REGISTER_TEMPLATE1_REGISTER_FIELD_".$FIELD) ?>
                                    <?php if ($arResult["REQUIRED_FIELDS_FLAGS"][$FIELD] == "Y") { ?>
                                        <span class="main-register-starrequired">*</span>
                                    <?php } ?>
                                </div>
                                <div class="main-register-field-value">
                                    <?php switch ($FIELD) {
                                        case "PASSWORD": ?>
                                            <input size="30"
                                                   type="password"
                                                   name="REGISTER[<?=$FIELD?>]"
                                                   value="<?=$arResult["VALUES"][$FIELD]?>"
                                                   autocomplete="off"
                                                   data-role="input"
                                                   class="bx-auth-input main-register-field-input" />

                                            <?if($arResult["SECURE_AUTH"]):?>
                                                <span class="bx-auth-secure" id="bx_auth_secure" title="<?echo Loc::getMessage("MAIN_REGISTER_TEMPLATE1_AUTH_SECURE_NOTE")?>" style="display:none">
                                                    <div class="bx-auth-secure-icon"></div>
                                                </span>
                                                <noscript>
                                                    <span class="bx-auth-secure" title="<?echo Loc::getMessage("MAIN_REGISTER_TEMPLATE1_AUTH_NONSECURE_NOTE")?>">
                                                        <div class="bx-auth-secure-icon bx-auth-secure-unlock"></div>
                                                    </span>
                                                </noscript>
                                                <script type="text/javascript">
                                                    document.getElementById('bx_auth_secure').style.display = 'inline-block';
                                                </script>
                                            <?endif?>
                                            <? break;
                                        case "CONFIRM_PASSWORD": ?>
                                            <input size="30"
                                                   type="password"
                                                   name="REGISTER[<?=$FIELD?>]"
                                                   value="<?=$arResult["VALUES"][$FIELD]?>"
                                                   autocomplete="off"
                                                   data-role="input"
                                                   class="main-register-field-input"/>
                                        <? break;
                                        case "PERSONAL_GENDER": ?>
                                            <select name="REGISTER[<?=$FIELD?>]"
                                                    class="main-register-field-select"
                                                    data-role="input">
                                                <option value=""><?=Loc::getMessage("MAIN_REGISTER_TEMPLATE1_USER_DONT_KNOW")?></option>
                                                <option value="M"<?=$arResult["VALUES"][$FIELD] == "M" ? " selected=\"selected\"" : ""?>><?=Loc::getMessage("MAIN_REGISTER_TEMPLATE1_USER_MALE")?></option>
                                                <option value="F"<?=$arResult["VALUES"][$FIELD] == "F" ? " selected=\"selected\"" : ""?>><?=Loc::getMessage("MAIN_REGISTER_TEMPLATE1_USER_FEMALE")?></option>
                                            </select>
                                            <? break;
                                        case "PERSONAL_COUNTRY":
                                        case "WORK_COUNTRY": ?>
                                            <select name="REGISTER[<?=$FIELD?>]"
                                                    data-role="input"
                                                    class="main-register-field-select">
                                            <? foreach ($arResult["COUNTRIES"]["reference_id"] as $key => $value) { ?>
                                                <option value="<?=$value?>"<?if ($value == $arResult["VALUES"][$FIELD]):?> selected="selected"<?endif?>><?=$arResult["COUNTRIES"]["reference"][$key]?></option>
                                                <? } ?>
                                            </select>
                                            <? break;
                                        case "PERSONAL_PHOTO":
                                        case "WORK_LOGO":
                                            /*?>
                                            <input size="30"
                                                   type="file"
                                                   name="REGISTER_FILES_<?=$FIELD?>" />
                                            <?*/ break;
                                        case "PERSONAL_NOTES":
                                        case "WORK_NOTES": ?>
                                            <textarea cols="30"
                                                      rows="5"
                                                      name="REGISTER[<?=$FIELD?>]"
                                                      data-role="input"
                                                      class="main-register-field-textarea"><?=$arResult["VALUES"][$FIELD]?></textarea>
                                            <? break;
                                        default: ?>
                                            <input size="30"
                                                   type="text"
                                                   name="REGISTER[<?=$FIELD?>]"
                                                   value="<?=$arResult["VALUES"][$FIELD]?>"
                                                   placeholder="<?= ($FIELD == "PERSONAL_BIRTHDAY") ? $arResult["DATE_FORMAT"] : null ?>"
                                                   data-role="input"
                                                   class="main-register-field-input <?= $FIELD == "PERSONAL_BIRTHDAY" ? "date-picker" : null ?>"/>
                                            <? if ($FIELD == "PERSONAL_BIRTHDAY") { ?>
                                                <div class="main-register-date-picker">
                                                    <? $APPLICATION->IncludeComponent(
                                                        'bitrix:main.calendar',
                                                        '',
                                                        array(
                                                            'SHOW_INPUT' => 'N',
                                                            'FORM_NAME' => 'regform',
                                                            'INPUT_NAME' => 'REGISTER[PERSONAL_BIRTHDAY]',
                                                            'SHOW_TIME' => 'N'
                                                        ),
                                                        null,
                                                        array("HIDE_ICONS"=>"Y")
                                                    ); ?>
                                                </div>
                                            <? } ?>
                                    <? } ?>
                                </div>
                            </div>

                        <?php } ?>
                    <?php } ?>


                    <?// ********************* User properties ***************************************************?>
                    <?/*if($arResult["USER_PROPERTIES"]["SHOW"] == "Y"):?>
                        <tr><td colspan="2"><?=strlen(trim($arParams["USER_PROPERTY_NAME"])) > 0 ? $arParams["USER_PROPERTY_NAME"] : Loc::getMessage("MAIN_REGISTER_TEMPLATE1_USER_TYPE_EDIT_TAB")?></td></tr>
                        <?foreach ($arResult["USER_PROPERTIES"]["DATA"] as $FIELD_NAME => $arUserField):?>
                        <tr><td><?=$arUserField["EDIT_FORM_LABEL"]?>:<?if ($arUserField["MANDATORY"]=="Y"):?><span class="starrequired">*</span><?endif;?></td><td>
                                <?$APPLICATION->IncludeComponent(
                                    "bitrix:system.field.edit",
                                    $arUserField["USER_TYPE"]["USER_TYPE_ID"],
                                    array("bVarsFromForm" => $arResult["bVarsFromForm"], "arUserField" => $arUserField, "form_name" => "regform"), null, array("HIDE_ICONS"=>"Y"));?></td></tr>
                        <?endforeach;?>
                    <?endif;*/?>
                    <?// ******************** /User properties ***************************************************?>

                    <?
                    /* CAPTCHA */
                    if ($arResult["USE_CAPTCHA"] == "Y")
                    {
                        ?>
                            <div class="main-register-form-field">
                                <div class="main-register-field-caption">
                                    <?=Loc::getMessage("MAIN_REGISTER_TEMPLATE1_REGISTER_CAPTCHA_TITLE")?>
                                    <span class="main-register-starrequired">*</span>
                                </div>
                                <div class="main-register-field-value">
                                    <div>
                                        <input type="hidden" name="captcha_sid" value="<?=$arResult["CAPTCHA_CODE"]?>" />
                                        <img src="/bitrix/tools/captcha.php?captcha_sid=<?=$arResult["CAPTCHA_CODE"]?>" width="180" height="40" alt="CAPTCHA" />
                                    </div>
                                    <div>
                                        <div><?=Loc::getMessage("MAIN_REGISTER_TEMPLATE1_REGISTER_CAPTCHA_PROMT")?>:<span class="starrequired">*</span></div>
                                        <div><input type="text" class="main-register-field-input" name="captcha_word" maxlength="50" value="" /></div>
                                    </div>
                                </div>
                            </div>
                        <?
                    }
                    /* !CAPTCHA */
                    ?>

                </div>
                <div class="main-register-button-wrap">
                     <input type="submit"
                            name="register_submit_button"
                            value="<?= Loc::getMessage("MAIN_REGISTER_TEMPLATE1_AUTH_REGISTER")?>"
                            class="intec-button intec-button-cl-common intec-button-lg main-register-button"/>
                </div>

                <p><?echo $arResult["GROUP_POLICY"]["PASSWORD_REQUIREMENTS"];?></p>
                <p><span class="main-register-starrequired">*</span><?=Loc::getMessage("MAIN_REGISTER_TEMPLATE1_AUTH_REQ")?></p>

            </form>
        <?php } ?>
    <?php } ?>
</div>

<script>
    (function ($, api) {
        var root = $(<?= JavaScript::toObject('#'.$sTemplateId) ?>);

        var inputs = $('[data-role="input"]', root);
        var update;

        update = function() {
            var self = $(this);

            if (self.val() != '') {
                self.addClass('completed');
            } else {
                self.removeClass('completed');
            }
        };

        $(document).ready(function () {
            inputs.each(function () {
                update.call(this);
            });
        });

        inputs.on('change', function () {
            update.call(this);
        });
    })(jQuery, intec)
</script>