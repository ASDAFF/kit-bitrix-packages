<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;
use Kit\Origami\Helper\Config;
use Bitrix\Main\Page\Asset;

if (!CModule::IncludeModule("kit.orderphone")) return;
\Bitrix\Main\Page\Asset::getInstance()->addJs($templateFolder . "/js/jquery.maskedinput.min.js");
$APPLICATION->ShowAjaxHead();
Asset::getInstance()->addJs(SITE_DIR . "local/templates/.default/components/kit/order.phone/origami_default/script.js");
Asset::getInstance()->addCss(SITE_DIR . "local/templates/.default/components/kit/order.phone/origami_default/style.css");
?>

<div class="kit_order_phone_wrapper buy-in-click">
    <div class="kit_order_phone">
        <div class="kit_order_phone__title"><?= Loc::getMessage('OK_TITLE') ?></div>
        <form class="kit_order_phone_form">

            <div class="kit_order_success">
                <div class="popup-window-message-content">
                    <svg class="popup-window-icon-check">
                        <use xlink:href="/local/templates/kit_origami/assets/img/sprite.svg#icon_check_form"></use>
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
                        <div class="kit_order_error"></div>
                    </div>
                    <? if (empty($arResult["FORM_NOTE"])) : ?>
                        <? foreach ($arParams as $param => $val):
                            if (strpos($param, "~") !== false || is_array($val)) continue;
                            ?>
                            <input type="hidden" name="<?= $param ?>" value="<?= $val ?>"/>
                        <? endforeach ?>
                        <div class="kit_order_phone__block">
                            <p class="kit_order_phone__block_title"><?= Loc::getMessage('OK_NAME') ?>*</p>
                            <input type="text" name="order_name" value="<?= $arResult['USER']['NAME'] ?>"/>
                        </div>
                        <div class="kit_order_phone__block">
                            <p class="kit_order_phone__block_title"><?= Loc::getMessage('OK_PHONE') ?>*</p>
                            <input type="text" name="order_phone" value="<?= $arResult['USER']['PHONE'] ?>"/>
                        </div>
                        <div class="kit_order_phone__block">
                            <p class="kit_order_phone__block_title"><?= Loc::getMessage('OK_EMAIL') ?></p>
                            <input type="text" name="order_email" value="<?= $arResult['USER']['EMAIL'] ?>"/>
                        </div>
                        <div class="kit_order_phone__block">
                            <p class="kit_order_phone__block_title"><?= Loc::getMessage('OK_COMMENT') ?></p>
                            <textarea name="order_comment"></textarea>
                        </div>

                        <? if ($arResult["isUseCaptcha"] == "Y") {
                            ?>
                            <div class="kit_order_phone__block">
                                <div class="feedback_block__captcha">
                                    <p class="popup-window-field_description"><?= GetMessage('CAPTCHA_TITLE') ?>
                                    </p>
                                    <div class="feedback_block__captcha_input">
                                        <input type="text" name="captcha_word" size="30" maxlength="50" value=""
                                               required/>
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
                            <input type="checkbox" id="UF_CONFIDENTIAL" name="UF_CONFIDENTIAL" class="checkbox__input"
                                   checked="checked">
                            <label for="UF_CONFIDENTIAL" class="checkbox__label fonts__middle_comment">
                                <?= Loc::getMessage('OK_CONFIDENTIAL', ['#CONFIDENTIAL_LINK#' => Config::get('CONFIDENTIAL_PAGE',
                                    $arParams['SITE_ID'])]) ?>
                            </label>
                        </div>


                        <div class="popup-window-submit_button">
                            <input type="submit" name="submit" value="<?= Loc::getMessage('OK_SEND') ?>"/>
                        </div>
                    <? endif; ?>

                </div>

            </div>

        </form>
    </div>
</div>
<script src="<?= $this->__component->__template->__folder ?>/js/jquery.maskedinput.min.js"></script>
<script>
    $(function () {
        $(".kit_order_phone").on("submit", "form", submitOrderPhone);

        maska = $(".kit_order_phone form input[name='TEL_MASK']").eq(0).val();
        maska = $.trim(maska);
        if (maska != "") $(".kit_order_phone form input[name='order_phone']").mask(maska, {placeholder: "_"});

        function submitOrderPhone(e) {
            e.preventDefault();
            var name = $(this).find("input[name='order_name']").val();
            var email = $(this).find("input[name='order_email']").val();
            v = $(this).find("input[name='TEL_MASK']").val();
            v = $.trim(v);
            req = strReplace(v);
            var _this = $(this);
            v = $(this).find("input[name='order_phone']").val();

            $(this).find('input').removeClass('error');
            $(this).find('.checkbox__label').removeClass('error');
            $(this).find(".kit_order_error").hide();
            $(this).find(".kit_order_success").hide();

            var error = false;
            if (name.length <= 0) {
                $(this).find("input[name='order_name']").addClass('error');
                error = true;
            }
            if (v.search(req) == -1 || v.length <= 0) {
                $(this).find("input[name='order_phone']").addClass('error');
                error = true;
            }
            if ($(this).find("input[name='UF_CONFIDENTIAL']:checked").length == 0) {
                $(this).find('.checkbox__label').addClass('error');
                error = true;
            }

            if (email) {
                var pattern = /^([a-z0-9_\.-])+@[a-z0-9-]+\.([a-z]{2,4}\.)?[a-z]{2,4}$/i;
                if (!pattern.test(email)) {
                    $(this).find("input[name='order_email']").addClass('error');
                    error = true;
                }
            }

            if (!error) {
                $(this).find("input[type='text']").removeClass("red");
                ser = $(this).serialize();
                let btnLoader = this.querySelector('.popup-window-submit_button');
                createBtnLoader(btnLoader);
                $.post("/bitrix/components/kit/order.phone/ajax.php", ser, function (data) {
                    removeBtnLoader(btnLoader);
                    data = $.trim(data);
                    if (data.indexOf("SUCCESS") >= 0) {
                        _this.find(".kit_order_success").show();
                        _this.find(".kit_order_error").hide();
                        _this.find(".hide-on-success").hide();
                        id = data.replace("SUCCESS", "");
                        localHref = $('input[name="LOCAL_REDIRECT"]').val();
                        orderID = $('input[name="ORDER_ID"]').val();
                        if (typeof (localHref) != "undefined" && localHref != "") {
                            location.href = localHref + "?" + orderID + "=" + id;
                        }
                    } else {
                        _this.find(".kit_order_success").hide();
                        _this.find(".kit_order_error").show().html(data);
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
            ;

        }
    });
</script>
<script>
    ;(function resizeOrderPhonePopup() {
        let wrapper,
            wrappers = document.querySelectorAll(".wrap-popup-window");

        for (let i = 0; i < wrappers.length; i++) {
            if (wrappers[i].querySelector(".popup_resizeable_content")) {
                wrapper = wrappers[i];
                break;
            }
        }

        let popupResizeableContent = wrapper.querySelector(".popup_resizeable_content"),
            popupWindow = wrapper.querySelector(".popup-window"),
            popupContent = wrapper.querySelector(".popup-content"),
            popupTitle = wrapper.querySelector(".kit_order_phone__title"),
            submitBtn = wrapper.querySelector(".popup-window-submit_button");

        resizePopupContent();
        putTitleShadow();
        setUpListeners();

        function setUpListeners() {
            popupResizeableContent.addEventListener("scroll", putTitleShadow);

            window.addEventListener("resize", () => {
                resizePopupContent();
                putTitleShadow();
            });

            popupContent.addEventListener("load", () => {
                resizePopupContent();
                putTitleShadow();
            });

            submitBtn.addEventListener("click", () => {
                resizePopupContent();
                putTitleShadow();
            });
        }

        function resizePopupContent() {
            let clientHeight = document.documentElement.clientHeight * 0.97,
                newHeight;

            popupResizeableContent.style.overflowY = "hidden";
            popupResizeableContent.style.height = "auto";

            if ((popupContent.clientHeight > (popupWindow.clientHeight + 2)) || (popupContent.clientHeight > clientHeight)) {

                newHeight = (clientHeight < popupWindow.clientHeight) ?
                    (clientHeight - popupTitle.clientHeight) :
                    (popupWindow.clientHeight - popupTitle.clientHeight);

                popupResizeableContent.style.overflowY = "auto";
                popupResizeableContent.style.height = newHeight + "px";
            }
        }

        function putTitleShadow() {
            let scrolled = popupResizeableContent.scrollTop;

            if (scrolled === 0) {
                popupTitle.style.boxShadow = "none";
            } else {
                popupTitle.style.boxShadow = "0 2px 5px 3px rgba(0,0,0,.1)";
            }
        }

    })();
</script>
