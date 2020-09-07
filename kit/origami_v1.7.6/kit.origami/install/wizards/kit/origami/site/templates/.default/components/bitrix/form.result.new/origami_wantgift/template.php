<?

use Bitrix\Main\Page\Asset;
use Bitrix\Main\Localization\Loc;
use Kit\Origami\Helper\Config;
use Bitrix\Main\Loader;

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
$APPLICATION->ShowAjaxHead();
Asset::getInstance()->addJs(SITE_DIR . "local/templates/.default/components/bitrix/form.result.new/origami_wantgift/script.js");
Asset::getInstance()->addCss(SITE_DIR . "local/templates/.default/components/bitrix/form.result.new/origami_wantgift/style.css");
Loader::includeModule('kit.origami');
?>

<div class="kit_want_gift_wrapper">
    <div class="kit_order_phone__title_narrowly">

        <? if ($arResult["isFormTitle"]) { ?>
            <div class="kit_order_phone__title"><?= $arResult["FORM_TITLE"] ?>
            </div>
        <? } ?>

    </div>
    <div class="want_gift-resizeable_content">
        <? if ($arResult["IMG_PRODUCT"]["SRC"]) : ?>
            <div class="kit_want_gift_image">
                <img src="<?= $arResult["IMG_PRODUCT"]["SRC"] ?>" alt="<?= $arResult["IMG_PRODUCT"]["NAME"] ?>">
                <div class="kit_want_gift_name"><?= $arResult["IMG_PRODUCT"]["NAME"] ?></div>
                <div class="kit_want_gift_price">
                    <?= $arResult["IMG_PRODUCT"]["PRICE"] ?>
                </div>
                <? if ($arResult["IMG_PRODUCT"]["OLD_PRICE"]): ?>
                    <div class="kit_want_gift_oldprice">
                        <?= $arResult["IMG_PRODUCT"]["OLD_PRICE"] ?>
                    </div>
                <? endif; ?>
            </div>
        <? endif; ?>

        <div class="kit_order_phone">
            <div class="kit_order_phone__title_wide">
                <? if ($arResult["isFormTitle"]) { ?>
                    <div class="kit_order_phone__title"><?= $arResult["FORM_TITLE"] ?>
                    </div>
                <? } ?>
            </div>
            <div class="want_gift-resizeable_content_wide">
                <div class="popup-error-message">
                    <? if ($arResult["isFormErrors"] == "Y"): ?><?= $arResult["FORM_ERRORS_TEXT"]; ?><? endif; ?>
                </div>
                <div class="kit_order_success_show">

                    <? if ($arResult["FORM_NOTE"]) : ?>
                        <div class="popup-window-message-content">

                            <svg class="popup-window-icon-check">
                                <use xlink:href="/local/templates/kit_origami/assets/img/sprite.svg#icon_check_form"></use>
                            </svg>

                            <div>
                                <div class="popup-window-message-title"><?= GetMessage('OK_THANKS'); ?></div>
                                <div style="font-size: 16px;"><?= $arResult["FORM_NOTE"] ?></div>
                            </div>

                        </div>
                    <? endif; ?>

                </div>
                <? if (empty($arResult["FORM_NOTE"]))
                {
                ?>
                <?= $arResult["FORM_HEADER"] ?>
                <?
                foreach ($arResult["QUESTIONS"] as $FIELD_SID => $arQuestion) {
                    ?>

                    <div class="kit_order_phone__block">
                        <?
                        if ($arQuestion['CAPTION'] !== Loc::getMessage('OK_LINK_PRODUCT')) { ?>
                            <p class="kit_order_phone__block_title">
                                <?= $arQuestion['CAPTION'] ?>
                                <?= ($arQuestion['REQUIRED'] == 'Y') ? '*' : '' ?>
                            </p>
                            <?
                        } ?>

                        <?
                        if ($arQuestion['STRUCTURE'][0]['FIELD_TYPE'] != 'textarea'):?>
                            <input
                                    type="<?= ($arQuestion['CAPTION'] !== Loc::getMessage('OK_LINK_PRODUCT')) ? $arQuestion['STRUCTURE'][0]['FIELD_TYPE'] : 'hidden' ?>"
                                    name="form_<?= ($arQuestion['STRUCTURE'][0]['FIELD_TYPE'] == 'tel') ? 'text' : $arQuestion['STRUCTURE'][0]['FIELD_TYPE'] ?>_<?= $arQuestion['STRUCTURE'][0]['ID'] ?>"
                                    value="<?= ($arQuestion['CAPTION'] !== Loc::getMessage('OK_LINK_PRODUCT')) ? $arQuestion['STRUCTURE'][0]['VALUE'] :
                                        (!empty($_REQUEST['url']) ? $_REQUEST['url'] : $_REQUEST["form_" . $arQuestion['STRUCTURE'][0]['FIELD_TYPE'] . "_" . $arQuestion['STRUCTURE'][0]["ID"]]) ?>"
                                <?= ($arQuestion['REQUIRED'] == 'Y') ? '' : '' ?>
                                <?= ($arQuestion['STRUCTURE'][0]['MASK']) ? 'placeholder="' . $arQuestion['STRUCTURE'][0]['MASK'] . '"' : '' ?>
                                <?= ($arQuestion['STRUCTURE'][0]['PATTERN']) ? 'pattern="' . $arQuestion['STRUCTURE'][0]['PATTERN'] . '"' : '' ?>
                            >
                        <? else: ?>
                            <?= $arQuestion["HTML_CODE"] ?>
                        <? endif ?>
                    </div>
                    <?
                }
                ?>

                <input type="hidden" name="img" value="<?= $arResult["IMG_PRODUCT"]["SRC"] ?>"/>
                <input type="hidden" name="name" value="<?= $arResult["IMG_PRODUCT"]["NAME"] ?>"/>
                <input type="hidden" name="price" value="<?= $arResult["IMG_PRODUCT"]["PRICE"] ?>"/>

                <? if ($arResult["isUseCaptcha"] == "Y") {
                    ?>
                    <div class="kit_order_phone__block">
                        <div class="feedback_block__captcha">
                            <p class="popup-window-field_description"><?= Loc::getMessage('CAPTCHA_TITLE') ?>
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
                } ?>

                <div class="confidential-field">
                    <div class="feedback_block__compliance">
                        <input type="checkbox"
                               id="personal_phone_personal"
                               name="personal"
                               class="checkbox__input"
                               checked="checked">
                        <label for="personal_phone_personal" class="checkbox__label fonts__middle_comment">
                            <?= Loc::getMessage('OK_CONFIDENTIAL') ?>
                            <a href="<?= Config::get('CONFIDENTIAL_PAGE', $arParams['SITE_ID']) ?>" target="_blank">
                                <?= Loc::getMessage('OK_CONFIDENTIAL2') ?>  </a>
                        </label>
                    </div>
                </div>
                <div class="popup-window-submit_button">
                    <input type="button" name="web_form_submit"
                           value="<?= htmlspecialcharsbx(strlen(trim($arResult["arForm"]["BUTTON"])) <= 0 ? GetMessage("FORM_ADD") : $arResult["arForm"]["BUTTON"]); ?>"
                           onclick="sendForm('<?= $arResult['arForm']['SID'] ?>', '<?= Config::get('COLOR_BASE', $arParams['SITE_ID']) ?>')">
                    <input type="submit" name="web_form_submit" style="display:none;"
                           value="<?= htmlspecialcharsbx(strlen(trim($arResult["arForm"]["BUTTON"])) <= 0 ? GetMessage("FORM_ADD") : $arResult["arForm"]["BUTTON"]); ?>">
                    <?= $arResult["FORM_FOOTER"] ?>
                    <?
                    } ?>
                </div>
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

    let image = document.querySelector(".kit_want_gift_image > img");
    image.addEventListener("load", function () {
        window.resizeWGPopup();
    })
</script>
