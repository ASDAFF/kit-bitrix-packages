<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;
use Sotbit\Origami\Helper\Config;
use Bitrix\Main\Page\Asset;

if (!CModule::IncludeModule("sotbit.orderphone") || !CSotbitOrderphone::GetDemo()) return;
$APPLICATION->ShowAjaxHead();
Asset::getInstance()->addJs(SITE_DIR . "local/templates/.default/components/sotbit/order.phone/origami_default/script.js");
Asset::getInstance()->addCss(SITE_DIR . "local/templates/.default/components/sotbit/order.phone/origami_default/style.css");
Asset::getInstance()->addJs($templateFolder . "/js/jquery.maskedinput.min.js");
$typeMask = (Config::get('TYPE_MASK_VIEW') == 'FLAGS') ? 'Y' : 'N';
if($typeMask == 'Y')
    CJSCore::Init(['phone_number']);
?>

<div class="sotbit_order_phone_wrapper buy-in-click">
    <div class="sotbit_order_phone">
        <div class="sotbit_order_phone__title"><?= Loc::getMessage('OK_TITLE') ?></div>
        <form class="sotbit_order_phone_form">

            <div class="sotbit_order_success">
                <div class="popup-window-message-content">
                    <svg class="popup-window-icon-check">
                        <use xlink:href="/local/templates/sotbit_origami/assets/img/sprite.svg#icon_check_form"></use>
                    </svg>
                    <div>
                        <div class="popup-window-message-title"><?= GetMessage('OK_THANKS') ?></div>
                        <div style="font-size: 16px;"><?= GetMessage('OK_SUCCESS') ?></div>
                    </div>
                </div>
            </div>

            <div class="hide-on-success">

                <div class="popup_resizeable_content">

                    <div class="popup-error-message">
                        <div class="sotbit_order_error"></div>
                    </div>
                    <? if (empty($arResult["FORM_NOTE"])) : ?>
                        <? foreach ($arParams as $param => $val):
                            if (strpos($param, "~") !== false || is_array($val)) continue;
                            ?>
                            <input type="hidden" name="<?= $param ?>" value="<?= $val ?>"/>
                        <? endforeach ?>

                        <div class="sotbit_order_phone__block main-input-md__wrapper">
                            <input type="text"
                                   name="order_name"
                                   class="main-input-md"
                                   id="buy-in-click_name"
                                   onchange="isInputFilled(this)"/>
                            <label for="buy-in-click_name" class="main-label-md">
                                <?= Loc::getMessage('OK_NAME') ?>*
                            </label>
                        </div>

                        <div class="sotbit_order_phone__block">
                            <div class="buy-in-click__phone-input">
                                <?if($typeMask == 'Y'):?>
                                    <span class="phone_input__flag">
                                        <span id="buy-in-click_flag-wrapper" onclick="fixCountryPopup(this)"></span>
                                    </span>
                                <?endif;?>

                                <div class="main-input-md__wrapper <?= ($typeMask == 'N') ? 'fullsize' : ''; ?>">
                                    <input

                                        type="text"
                                        name="order_phone"
                                        class="main-input-md filled"
                                        maxlength='17'
                                        id="buy-in-click_phone"
                                        placeholder="<?= Config::get('MASK') ?>"
                                    >
                                    <label
                                        for="buy-in-click_phone"
                                        class="main-label-md">
                                        <?= Loc::getMessage('OK_PHONE') ?>*
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="sotbit_order_phone__block main-input-md__wrapper">
                            <input class="main-input-md"
                                   id="buy-in-click__email-input"
                                   type="text"
                                   onchange="isInputFilled(this)"
                                   name="order_email"/>
                            <label for="buy-in-click__email-input" class="main-label-md">
                                <?= Loc::getMessage('OK_EMAIL') ?>
                            </label>
                        </div>

                        <div class="sotbit_order_phone__block main-textarea-md__wrapper">
                            <textarea name="order_comment"
                                      class="main-textarea-md"
                                      rows="5"
                                      id="buy-in-click__textarea"
                                      onchange="isInputFilled(this)"></textarea>
                            <label for="buy-in-click__textarea" class="main-label-textarea-md">
                                <?= Loc::getMessage('OK_COMMENT') ?>
                            </label>
                        </div>

                        <?
                        if ($arResult["isUseCaptcha"] == "Y") {
                            ?>
                            <div class="sotbit_order_phone__block">
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
                                        <div class="captcha-refresh"
                                             onclick="reloadCaptcha(this,'<?= SITE_DIR ?>');return false;">
                                            <svg class="icon_refresh" width="16" height="14">
                                                <use
                                                    xlink:href="/local/templates/sotbit_origami/assets/img/sprite.svg#icon_refresh"></use>
                                            </svg>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        <? } ?>

                        <div class="confidential-field main_checkbox">
                            <input type="checkbox" id="UF_CONFIDENTIAL" name="UF_CONFIDENTIAL" class="checkbox__input"
                                   checked="checked">
                            <label for="UF_CONFIDENTIAL" class="checkbox__label fonts__middle_comment">
                                <span></span>
                                <span><?= Loc::getMessage('OK_CONFIDENTIAL', ['#CONFIDENTIAL_LINK#' => Config::get('CONFIDENTIAL_PAGE',
                                        $arParams['SITE_ID'])]) ?></span>
                            </label>
                        </div>

                        <div class="popup-window-submit_button">
                            <input class="main_btn" type="submit" name="submit" value="<?= Loc::getMessage('OK_SEND') ?>"/>
                        </div>
                    <? endif; ?>
                </div>
            </div>
        </form>
    </div>
</div>
<script>
    <?if($typeMask == 'Y'):?>
        BX.ready(function () {
            if (document.getElementById("buy-in-click_phone")) {
                new BX.PhoneNumber.Input({
                    node: BX("buy-in-click_phone"),
                    forceLeadingPlus: true,
                    flagNode: BX("buy-in-click_flag-wrapper"),
                    flagSize: 16,
                    defaultCountry: 'ru',
                    onChange: function (e) {
                    }
                });
            }
        });
    <?endif;?>

    $(function () {
        <?if($typeMask !== 'Y'):?>
            let maska = $(".sotbit_order_phone form input[name='TEL_MASK']").eq(0).val();
            maska = $.trim(maska);
            if (maska != "")
                $(".sotbit_order_phone form input[name='order_phone']").mask(maska, {placeholder: "_"});
        <?endif;?>

        $(".sotbit_order_phone").on("submit", "form", submit);
        function submit(e) {
            e.preventDefault();
            let name = $(this).find("input[name='order_name']").val();
            let email = $(this).find("input[name='order_email']").val();
            var _this = $(this);

            $(this).find('input').removeClass('error');
            $(this).find('.checkbox__label').removeClass('failed');
            $(this).find(".sotbit_order_error").hide();
            $(this).find(".sotbit_order_success").hide();

            var error = false;
            if (name.length <= 0) {
                $(this).find("input[name='order_name']").addClass('failed');
                error = true;
            }
            if ($(this).find("input[name='UF_CONFIDENTIAL']:checked").length == 0) {
                $(this).find('.checkbox__label').addClass('error');
                error = true;
            }

            if (email) {
                var pattern = /^([a-z0-9_\.-])+@[a-z0-9-]+\.([a-z]{2,4}\.)?[a-z]{2,4}$/i;
                if (!pattern.test(email)) {
                    $(this).find("input[name='order_email']").addClass('failed');
                    error = true;
                }
            }

            if (!error) {
                $(this).find("input[type='text']").removeClass("red");
                ser = $(this).serialize();
                let btnLoader = this.querySelector('.popup-window-submit_button');
                createBtnLoader(btnLoader);
                $.post("/bitrix/components/sotbit/order.phone/ajax.php", ser, function (data) {
                    removeBtnLoader(btnLoader);
                    data = $.trim(data);
                    if (data.indexOf("SUCCESS") >= 0) {
                        _this.find(".sotbit_order_success").show();
                        _this.find(".sotbit_order_error").hide();
                        _this.find(".hide-on-success").hide();
                        id = data.replace("SUCCESS", "");
                        localHref = $('input[name="LOCAL_REDIRECT"]').val();
                        orderID = $('input[name="ORDER_ID"]').val();
                        if (typeof (localHref) != "undefined" && localHref != "") {
                            location.href = localHref + "?" + orderID + "=" + id;
                        }
                    } else {
                        _this.find(".sotbit_order_success").hide();
                        _this.find(".sotbit_order_error").show().html(data);
                    }
                })
            }
        }

        function strReplace(str) {
            str = str.replace("+", "\\+");
            str = str.replace("(", "\\(");
            str = str.replace(")", "\\)");
            str = str.replace(/[0-9]/g, "[0-9]{1}");
            return new RegExp(str, 'g');
        }
    });
</script>
