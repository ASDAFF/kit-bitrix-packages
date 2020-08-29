<?

use Bitrix\Main\Page\Asset;
use Bitrix\Main\Localization\Loc;
use Kit\Origami\Helper\Config;
use Bitrix\Main\Loader;

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

Loader::includeModule('kit.origami');
$APPLICATION->ShowAjaxHead();
Asset::getInstance()->addJs(SITE_DIR . "local/templates/.default/components/bitrix/form.result.new/origami_contacts_callback_manager/script.js");
Asset::getInstance()->addCss(SITE_DIR . "local/templates/.default/components/bitrix/form.result.new/origami_contacts_callback_manager/style.css");
$telMask = \Kit\Origami\Config\Option::get('MASK', SITE_ID);
Asset::getInstance()->addJs($templateFolder . "/js/jquery.maskedinput.min.js");
$typeMask = (Config::get('TYPE_MASK_VIEW') == 'FLAGS') ? 'Y' : 'N';
if ($typeMask == 'Y')
    CJSCore::Init(['phone_number']);

CUtil::InitJSCore(array('ajax', 'popup'));
?>

<div class="kit_order_phone_wrapper contacts-page-callback-popup">
    <div class="kit_order_phone">
        <? if ($arResult["isFormTitle"]) { ?>
            <div class="kit_order_phone__title"><?= GetMessage('FORM_TITLE') ?></div>
        <? } ?>

        <div class="popup_resizeable_content">

            <? if ($arResult["isFormErrors"] == "Y"): ?>
                <div class="popup-error-message">
                    <?= $arResult["FORM_ERRORS_TEXT"]; ?>
                </div>
            <? endif; ?>

            <div class="kit_order_success_show">

                <? if ($arResult["FORM_NOTE"]) : ?>
                    <div class="popup-window-message-content">

                        <svg class="popup-window-icon-check">
                            <use
                                xlink:href="/local/templates/kit_origami/assets/img/sprite.svg#icon_check_form"></use>
                        </svg>

                        <div>
                            <div class="popup-window-message-title"><?= GetMessage('OK_THANKS'); ?></div>
                            <div style="font-size: 16px;"><?= $arResult["FORM_NOTE"] ?></div>
                        </div>

                    </div>
                <? endif; ?>
            </div>

            <? if (empty($arResult["FORM_NOTE"])) { ?>
            <?= $arResult["FORM_HEADER"] ?>
            <?
            foreach ($arResult["QUESTIONS"] as $FIELD_SID => $arQuestion) {
                ?>
                <div class="kit_order_phone__block">

                    <? if ($arQuestion['STRUCTURE'][0]['FIELD_TYPE'] == 'tel'): ?>
                    <div class="phone_input">
                        <? if ($typeMask == 'Y'): ?>
                            <span class="phone_input__flag">
                            <span id="flag<?= $arResult['arForm']['SID'] ?>" onclick="fixCountryPopup(this)"></span>
                        </span>
                        <? endif; ?>
                        <? endif; ?>

                <? if ($arQuestion['STRUCTURE'][0]['FIELD_TYPE'] != 'textarea'): ?>
                        <div class="main-input-md__wrapper <?= ($typeMask == 'N') ? 'fullsize' : ''; ?>">

                            <input
                                name="form_<?= ($arQuestion['STRUCTURE'][0]['FIELD_TYPE'] == 'tel')
                                    ? 'text'
                                    : $arQuestion['STRUCTURE'][0]['FIELD_TYPE'] ?>_<?= $arQuestion['STRUCTURE'][0]['ID'] ?>"
                                <?= ($arQuestion['REQUIRED'] == 'Y') ? 'required' : '' ?>
                                <? if ($arQuestion['STRUCTURE'][0]['FIELD_TYPE'] == 'tel'): ?>
                                    type="text"
                                    placeholder="<?= Config::get('MASK') ?>"
                                    <?= ($arQuestion['STRUCTURE'][0]['FIELD_TYPE'] == 'tel' ? "class='main-input-md filled phone-callback--form' maxlength='17' id='number" . $arResult['arForm']['SID'] . "'" : "") ?>

                                <? elseif ($arQuestion['STRUCTURE'][0]['FIELD_TYPE'] == 'date'): ?>
                                    class='main-input-md filled'
                                    type="text"
                                    onclick="BX.calendar({node: this, field: this, bTime: false});"

                                <? else: ?>
                                    type="<?= $arQuestion['STRUCTURE'][0]['FIELD_TYPE'] ?>"
                                    class="main-input-md"
                                    id="<?= $arQuestion['STRUCTURE'][0]['ID'] ?>"
                                    onchange="isInputFilled(this)"
                                <? endif; ?>
                            >
                            <label
                                <? if ($arQuestion['STRUCTURE'][0]['FIELD_TYPE'] == 'tel'): ?>
                                    <?= ($arQuestion['STRUCTURE'][0]['FIELD_TYPE'] == 'tel' ? "for='number" . $arResult['arForm']['SID'] . "'" : "") ?>
                                <? else: ?>
                                    for="<?= $arQuestion['STRUCTURE'][0]['ID'] ?>"
                                <? endif; ?>
                                class="main-label-md">
                                <?= $arQuestion['CAPTION'] ?>
                                <?= ($arQuestion['REQUIRED'] == 'Y') ? '*' : '' ?>
                            </label>

                        </div>

                            <? if ($arQuestion['STRUCTURE'][0]['FIELD_TYPE'] == 'tel'): ?>
                        </div>
                    <? endif; ?>

                <? else: ?>

                    <div class="main-textarea-md__wrapper">
                                    <textarea name="form_textarea_<?=$arQuestion['STRUCTURE'][0]['ID']?>"
                                              rows="5"
                                              class="inputtextarea main-textarea-md"
                                               <?= ($arQuestion['REQUIRED'] == 'Y') ? 'required' : '' ?>
                                              id="origami_foundcheaper__<?= ($arQuestion['STRUCTURE'][0]['FIELD_TYPE']) ?>_<?= $arQuestion['STRUCTURE'][0]['ID'] ?>"
                                              onchange="isInputFilled(this)"></textarea>
                        <label class="main-label-textarea-md"
                               for="origami_foundcheaper__<?= ($arQuestion['STRUCTURE'][0]['FIELD_TYPE']) ?>_<?= $arQuestion['STRUCTURE'][0]['ID'] ?>">
                            <?=GetMessage("COMMENT") ?> <?= ($arQuestion['REQUIRED'] == 'Y') ? '*' : '' ?>
                        </label>
                    </div>
                <? endif ?>

                </div>
                <?
            }

            if ($arResult["isUseCaptcha"] == "Y") {
                ?>
                <div class="kit_order_phone__block">
                    <div class="feedback_block__captcha">
                        <div class="feedback_block__captcha_input main-input-md__wrapper">
                            <input type="text" class="main-input-md" name="captcha_word"
                                   id="callback-popup_captcha-input" size="30" maxlength="50" value=""
                                   onchange="isInputFilled(this)"
                                   required/>
                            <label class="main-label-md" for="callback-popup_captcha-input">
                                <?= GetMessage('CAPTCHA_TITLE'); ?>
                            </label>
                        </div>
                        <div class="feedback_block__captcha_img">
                            <input type="hidden" name="captcha_sid"
                                   value="<?= htmlspecialcharsbx($arResult["CAPTCHACode"]); ?>"/>
                            <img
                                src="/bitrix/tools/captcha.php?captcha_sid=<?= htmlspecialcharsbx($arResult["CAPTCHACode"]); ?>"/>
                            <div class="captcha-refresh" onclick="reloadCaptcha(this,'<?= SITE_DIR ?>');return false;">
                                <svg class="icon_refresh" width="16" height="14">
                                    <use
                                        xlink:href="/local/templates/kit_origami/assets/img/sprite.svg#icon_refresh"></use>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
                <?
            }
            ?>

            <div class="confidential-field">
                <div class="main_checkbox">
                    <input type="checkbox" id="personal_phone_personal" class="checkbox__input"
                           checked="checked" name="personal">
                    <label for="personal_phone_personal">
                        <span></span>
                        <span><?= Loc::getMessage('OK_CONFIDENTIAL') ?>
                            <a href="<?= Config::get('CONFIDENTIAL_PAGE', $arParams['SITE_ID']) ?>" target="_blank">
                                <?= Loc::getMessage('OK_CONFIDENTIAL2') ?>  </a></span>
                    </label>
                </div>
            </div>

            <div class="popup-window-submit_button">

                <input type="button" class="main_btn" name="web_form_submit" value="<?= htmlspecialcharsbx(strlen(trim
                ($arResult["arForm"]["BUTTON"])) <= 0 ? GetMessage("FORM_ADD") : $arResult["arForm"]["BUTTON"]); ?>"
                       onclick="sendForm('<?= $arResult['arForm']['SID'] ?>','<?= Config::get('COLOR_BASE', $arParams['SITE_ID']) ?>')">
                <input type="submit" name="web_form_submit"
                       style="display:none;" value="<?= htmlspecialcharsbx(strlen(trim
                ($arResult["arForm"]["BUTTON"])) <= 0 ? GetMessage("FORM_ADD") : $arResult["arForm"]["BUTTON"]); ?>"/>
                <?= $arResult["FORM_FOOTER"] ?>
                <? } ?>
            </div>
        </div>

    </div>
</div>
<script>
    function sendForm(sid, color) {
        if ($("form[name='" + sid + "'] input[name='personal']").is(':checked')) {

            $("form[name='" + sid + "'] input[type='submit']").trigger('click');
        } else {
            $('.feedback_block__compliance svg path').css({'stroke': color, 'stroke-dashoffset': 0});
        }
    }
    <?if($typeMask == 'Y'):?>
    BX.ready(function () {
        if (document.getElementById("number" + "<?= $arResult['arForm']['SID'] ?>")) {
            new BX.PhoneNumber.Input({
                node: BX("number" + "<?= $arResult['arForm']['SID'] ?>"),
                forceLeadingPlus: true,
                flagNode: BX("flag" + "<?= $arResult['arForm']['SID'] ?>"),
                flagSize: 16,
                defaultCountry: 'ru',
                onChange: function (e) {
                }
            });
        }
    });
    <?endif;?>
    <?if($typeMask !== 'Y'):?>
    $(function () {
        let maska = "<?=Config::get('MASK')?>";
        maska = $.trim(maska);
        if (maska !== "")
            $(".kit_order_phone form input.phone-callback--form").mask(maska, {placeholder: "_"});
    });

    <?endif;?>
</script>
