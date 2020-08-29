<?

use Bitrix\Main\Localization\Loc;
use Sotbit\Origami\Helper\Config;
use Bitrix\Main\Loader;
use Bitrix\Main\Page\Asset;

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

Loader::includeModule('kit.origami');
$APPLICATION->ShowAjaxHead();
Asset::getInstance()->addJs(SITE_DIR . "local/templates/.default/components/bitrix/form.result.new/origami_foundcheaper/script.js");
Asset::getInstance()->addCss(SITE_DIR . "local/templates/.default/components/bitrix/form.result.new/origami_foundcheaper/style.css");
Asset::getInstance()->addJs($templateFolder . "/js/jquery.maskedinput.min.js");
$typeMask = (Config::get('TYPE_MASK_VIEW') == 'FLAGS') ? 'Y' : 'N';
if($typeMask == 'Y')
    CJSCore::Init(['phone_number']);
?>
<div class="kit_order_phone_wrapper found-cheaper origami_foundcheaper">
    <div class="kit_order_phone">
        <? if ($arResult["isFormTitle"]) { ?>
            <div class="kit_order_phone__title"
                 style="position: relative; z-index: 2"><?= $arResult["FORM_TITLE"] ?></div>
        <? } ?>
        <div class="popup_resizeable_content">
            <div class="popup-error-message">
                <? if ($arResult["isFormErrors"] == "Y"): ?><?= $arResult["FORM_ERRORS_TEXT"]; ?><? endif; ?>
            </div>
            <div class="kit_order_success_show">
                <? if (!empty($arResult["FORM_NOTE"])) : ?>
                    <div class="popup-window-message-content">
                        <svg class="popup-window-icon-check">
                            <use
                                xlink:href="/local/templates/kit_origami/assets/img/sprite.svg#icon_check_form"></use>
                        </svg>
                        <div>
                            <div class="popup-window-message-title"> <?= GetMessage('OK_THANKS') ?></div>
                            <div style="font-size: 16px;"><?= $arResult["FORM_NOTE"] ?></div>
                        </div>
                    </div>
                <? endif; ?>
            </div>
            <? if (empty($arResult["FORM_NOTE"])): ?>
                <? if (empty($arResult["FORM_NOTE"])) { ?>
                    <?= $arResult["FORM_HEADER"] ?>
                    <?
                } ?>
                <? foreach ($arResult["QUESTIONS"] as $FIELD_SID => $arQuestion) { ?>
                    <div class="kit_order_phone__block">
                        <? if ($arQuestion['STRUCTURE'][0]['FIELD_TYPE'] == 'tel'): ?>
                        <div class="phone_input">
                                <?if($typeMask == 'Y'):?>
                                    <span class="phone_input__flag">
                                        <span id="flag<?= $arResult['arForm']['SID'] ?>"
                                            onclick="fixCountryPopup(this)"></span>
                                    </span>
                                <? endif; ?>
                            <? endif; ?>

                            <? if ($arQuestion['STRUCTURE'][0]['FIELD_TYPE'] != 'textarea'): ?>
                                <div class="main-input-md__wrapper <?= ($typeMask == 'N' && $arQuestion['STRUCTURE'][0]['FIELD_TYPE'] == 'tel') ? 'fullsize' : ''; ?>">
                                    <input
                                        <? if ($arQuestion['STRUCTURE'][0]['FIELD_TYPE'] == 'tel'): ?>
                                            type='text'
                                            class='popup-phone_input main-input-md filled'
                                            maxlength='17'
                                            id="origami_foundcheaper__tel-input"
                                            placeholder="<?=Config::get('MASK')?>"
                                        <? else: ?>
                                            type="<?= $arQuestion['STRUCTURE'][0]['FIELD_TYPE'] ?>"
                                            id="origami_foundcheaper__<?= ($arQuestion['STRUCTURE'][0]['FIELD_TYPE'] == 'tel')
                                                ? 'text'
                                                : $arQuestion['STRUCTURE'][0]['FIELD_TYPE'] ?>_<?= $arQuestion['STRUCTURE'][0]['ID'] ?>"
                                            class="main-input-md"
                                            onchange="isInputFilled(this)"
                                        <? endif; ?>

                                        name="form_<?= ($arQuestion['STRUCTURE'][0]['FIELD_TYPE'] == 'tel')
                                            ? 'text'
                                            : $arQuestion['STRUCTURE'][0]['FIELD_TYPE'] ?>_<?= $arQuestion['STRUCTURE'][0]['ID'] ?>"

                                        <?= ($arQuestion['REQUIRED'] == 'Y')
                                            ? 'required'
                                            : '' ?>

                                        <?= ($arQuestion['STRUCTURE'][0]['PATTERN'])
                                            ? 'pattern="' . $arQuestion['STRUCTURE'][0]['PATTERN'] . '"'
                                            : '' ?>
                                    >
                                    <label
                                        class="main-label-md"
                                        <? if ($arQuestion['STRUCTURE'][0]['FIELD_TYPE'] == 'tel'): ?>
                                            for="origami_foundcheaper__tel-input"
                                        <? else: ?>
                                            for="origami_foundcheaper__<?= ($arQuestion['STRUCTURE'][0]['FIELD_TYPE'] == 'tel') ? 'text' : $arQuestion['STRUCTURE'][0]['FIELD_TYPE'] ?>_<?= $arQuestion['STRUCTURE'][0]['ID'] ?>"
                                        <? endif; ?>
                                    >
                                        <?= $arQuestion['CAPTION'] ?>
                                        &nbsp;<span><?= ($arQuestion['REQUIRED'] == 'Y') ? '*' : '' ?></span>
                                    </label>
                                </div>
                            <? else: ?>
                                <div class="found-error-popup__textarea main-textarea-md__wrapper">
                                    <textarea name="form_textarea_<?= $arQuestion['STRUCTURE'][0]['ID'] ?>"
                                              rows="5"
                                              class="inputtextarea main-textarea-md"
                                              id="origami_foundcheaper__<?= ($arQuestion['STRUCTURE'][0]['FIELD_TYPE'] == 'tel') ? 'text' : $arQuestion['STRUCTURE'][0]['FIELD_TYPE'] ?>_<?= $arQuestion['STRUCTURE'][0]['ID'] ?>"
                                              onchange="isInputFilled(this)"></textarea>
                                    <label class="main-label-textarea-md"
                                           for="origami_foundcheaper__<?= ($arQuestion['STRUCTURE'][0]['FIELD_TYPE'] == 'tel') ? 'text' : $arQuestion['STRUCTURE'][0]['FIELD_TYPE'] ?>_<?= $arQuestion['STRUCTURE'][0]['ID'] ?>"
                                    >
                                        <?=GetMessage("COMMENT") ?>
                                    </label>
                                </div>
                            <? endif ?>

                            <? if ($arQuestion['STRUCTURE'][0]['FIELD_TYPE'] == 'tel'): ?>
                        </div>
                    <? endif; ?>
                    </div>
                <? } ?>

                <? if ($arResult["isUseCaptcha"] == "Y") { ?>
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

                    <input type="button" name="web_form_submit" class="main_btn"
                           value="<?= htmlspecialcharsbx(strlen(trim($arResult["arForm"]["BUTTON"]))
                           <= 0 ? GetMessage("FORM_ADD") : $arResult["arForm"]["BUTTON"]); ?>"
                           onclick="sendForm('<?= $arResult['arForm']['SID'] ?>', '<?= Config::get('COLOR_BASE', $arParams['SITE_ID']) ?>')">
                    <input type="submit" name="web_form_submit" style="display:none;"
                           value="<?= htmlspecialcharsbx(strlen(trim($arResult[" arForm"]["BUTTON"])) <= 0 ? GetMessage("FORM_ADD") :
                               $arResult["arForm"]["BUTTON"]); ?>">
                    <?= $arResult["FORM_FOOTER"] ?>

                </div>
            <? endif; ?>
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
            if (document.getElementById("origami_foundcheaper__tel-input")) {
                new BX.PhoneNumber.Input({
                    node: BX("origami_foundcheaper__tel-input"),
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
        if (maska != "")
            $(".kit_order_phone form input.popup-phone_input").mask(maska, {placeholder: "_"});
    });

    <?endif;?>
</script>
