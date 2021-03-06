<?

use Bitrix\Main\Page\Asset;
use Bitrix\Main\Localization\Loc;
use Kit\Origami\Helper\Config;
use Bitrix\Main\Loader;

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

Loader::includeModule('kit.origami');
$APPLICATION->ShowAjaxHead();
Asset::getInstance()->addJs(SITE_DIR . "local/templates/.default/components/bitrix/form.result.new/origami_callbackphone/script.js");
Asset::getInstance()->addCss(SITE_DIR . "local/templates/.default/components/bitrix/form.result.new/origami_callbackphone/style.css");
$telMask = \Kit\Origami\Config\Option::get('MASK', SITE_ID);
?>
<div class="kit_order_phone_wrapper callback-popup">
    <div class="kit_order_phone">

        <?
        if ($arResult["isFormTitle"]) {
            ?>
            <div class="kit_order__title"><?= $arResult["FORM_TITLE"] ?></div>
        <? } ?>

        <div class="popup_resizeable_content">

            <div class="popup-error-message">
                <? if ($arResult["isFormErrors"] == "Y"): ?><?= $arResult["FORM_ERRORS_TEXT"]; ?><? endif; ?>
            </div>

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
                    <p class="popup-window-field_description">
                        <?= $arQuestion['CAPTION'] ?>
                        <?= ($arQuestion['REQUIRED'] == 'Y') ? '*' : '' ?>
                    </p>
                    <input
                        type="<?= $arQuestion['STRUCTURE'][0]['FIELD_TYPE'] ?>"
                        name="form_<?= ($arQuestion['STRUCTURE'][0]['FIELD_TYPE'] ==
                            'tel') ? 'text' : $arQuestion['STRUCTURE'][0]['FIELD_TYPE'] ?>_<?= $arQuestion['STRUCTURE'][0]['ID'] ?>"
                        <?= ($arQuestion['REQUIRED'] == 'Y') ? '' : '' ?>
                        <?
                        if ($arQuestion['STRUCTURE'][0]['FIELD_TYPE'] == 'tel'): ?>
                            <?= ($arQuestion['STRUCTURE'][0]['FIELD_TYPE'] == 'tel' ? "class='js-phone'" : "") ?>
                            <?= ($arQuestion['STRUCTURE'][0]['MASK']) ? 'placeholder="' . $arQuestion['STRUCTURE'][0]['MASK'] . '"' : "" ?>
                            <?= ($arQuestion['STRUCTURE'][0]['PATTERN']) ? 'pattern="' . $arQuestion['STRUCTURE'][0]['PATTERN'] . '"' : '' ?>
                        <? endif; ?>
                    >
                </div>
                <?
            }

            if ($arResult["isUseCaptcha"] == "Y") {
                ?>
                <div class="kit_order_phone__block">
                    <div class="feedback_block__captcha">
                        <p class="popup-window-field_description"><?= GetMessage('CAPTCHA_TITLE'); ?>
                        </p>
                        <div class="feedback_block__captcha_input">
                            <input type="text" name="captcha_word" size="30" maxlength="50" value="" required/>
                        </div>
                        <div class="feedback_block__captcha_img">
                            <input type="hidden" name="captcha_sid"
                                   value="<?= htmlspecialcharsbx($arResult["CAPTCHACode"]); ?>"/>
                            <img
                                src="/bitrix/tools/captcha.php?captcha_sid=<?= htmlspecialcharsbx($arResult["CAPTCHACode"]); ?>"/>
                            <div class="captcha-refresh"  onclick="reloadCaptcha(this,'<?= SITE_DIR ?>');return false;">
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
                <div class="feedback_block__compliance">
                    <input type="checkbox" id="personal_phone_personal" class="checkbox__input"
                           checked="checked" name="personal">
                    <label for="personal_phone_personal" class="checkbox__label fonts__middle_comment">
                        <?= Loc::getMessage('OK_CONFIDENTIAL') ?>
                        <a href="<?= Config::get('CONFIDENTIAL_PAGE', $arParams['SITE_ID']) ?>" target="_blank">
                            <?= Loc::getMessage('OK_CONFIDENTIAL2') ?>  </a>
                    </label>
                </div>
            </div>

            <div class="popup-window-submit_button">

                <input type="button" name="web_form_submit" value="<?= htmlspecialcharsbx(strlen(trim
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

    $(document).ready(function () {
        $('.js-phone').inputmask("<?= $telMask ?>");
    });
</script>
