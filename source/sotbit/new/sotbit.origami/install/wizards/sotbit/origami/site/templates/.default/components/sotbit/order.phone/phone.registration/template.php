<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;
use Sotbit\Origami\Helper\Config;
use Bitrix\Main\Page\Asset;

if (!CModule::IncludeModule("sotbit.orderphone") || !CSotbitOrderphone::GetDemo()) return;
$APPLICATION->ShowAjaxHead();
Asset::getInstance()->addJs(SITE_DIR . "local/templates/.default/components/sotbit/order.phone/phone.registration/script.js");
Asset::getInstance()->addCss(SITE_DIR . "local/templates/.default/components/sotbit/order.phone/phone.registration/style.css");
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
                        <div style="font-size: 16px;"><?= GetMessage('OK_SUCCESS') ?>
                            <div class="sotbit_order_timedate">
                                <p style="margin-bottom: 5px; padding-bottom: 5px; padding-top: 15px; font-size: 16px; font-weight: bold;"><?= GetMessage('TITLE_FORM_DATE') ?></p>
                                <span class="time_date_login"><?=GetMessage('FORM_LOGIN')?></span><br>
                                <span class="time_date_pass"><?=GetMessage('FORM_PASSWORD')?></span>
                            </div>
                        </div>
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

                        <? foreach ($arResult['DISPLAY'] as $field): ?>
                            <div class="sotbit_order_phone__block 
							<?= ($field == 'PHONE') ? '' : 
							(($field == 'COMMENT') ? 'main-textarea-md__wrapper' : 
							'main-input-md__wrapper')?>">
                                <? if ($field == 'PHONE'): ?>
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
                                                <?= (in_array($field, $arResult['REQUIRE'])) ? 'data-req-'.$field.'="y"' : '' ?>
                                            >
											<label for="buy-in-click_phone" class="main-label-md">
                                    			<?= Loc::getMessage('OK_'.$field) ; if (in_array($field, $arResult['REQUIRE'])) echo '<span class="star">  *</span>'?>
                                			</label>
                                        </div>
                                    </div>
                                <? elseif ($field == 'COMMENT'): ?>
                                    <textarea name="order_comment"
                                          class="main-textarea-md"
                                          rows="5"
                                          id="buy-in-click_comment"
                                          onchange="isInputFilled(this)">
                                          <?= (in_array($field, $arResult['REQUIRE'])) ? 'data-req-'.$field.'="y"' : '' ?>
                                    </textarea>
									<label for="buy-in-click_comment" class="main-label-textarea-md">
                                    	<?= Loc::getMessage('OK_'.$field) ; if (in_array($field, $arResult['REQUIRE'])) echo '<span class="star">  *</span>'?>
                                	</label>
                                <? else : ?>
                                    <input type="text"
                                           name="order_<?=mb_strtolower($field)?>"
                                           class="main-input-md"
                                           id="buy-in-click_<?=mb_strtolower($field)?>"
                                           onchange="isInputFilled(this)"
                                           <?= (in_array($field, $arResult['REQUIRE'])) ? 'data-req-'.$field.'="y"' : '' ?>
                                    />
									<label for="buy-in-click_<?=mb_strtolower($field)?>" class="main-label-md">
										<?= Loc::getMessage('OK_'.$field) ; if (in_array($field, $arResult['REQUIRE'])) echo '<span class="star">  *</span>'?>
									</label>
                                <? endif ?>
                            </div>
                        <? endforeach ?>

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
			let phone = $(this).find("input[name='order_phone']").val();
            let fio = $(this).find("input[name='order_fio']").val();
            let zip = $(this).find("input[name='order_zip']").val();
            let city = $(this).find("input[name='order_city']").val();
            let prop_location = $(this).find("input[name='order_location']").val();
            let address = $(this).find("input[name='order_address']").val();
            let company = $(this).find("input[name='order_company']").val();
            let company_adr = $(this).find("input[name='order_company_adr']").val();
            let inn = $(this).find("input[name='order_inn']").val();
            let kpp = $(this).find("input[name='order_kpp']").val();
            let contact_person = $(this).find("input[name='order_contact_person']").val();
            let fax = $(this).find("input[name='order_fax']").val();
            let comment = $(this).find("textarea[name='order_comment']").val();
            let smscode = $(this).find("input[name='SMS_CODE']").val();
            let smsconfirm = $(this).find("input[name='SMS_CONFIRM']").val();
            let checked = 'N';
            var _this = $(this);

            $(this).find('input').removeClass('error');
            $(this).find('.checkbox__label').removeClass('failed');
            $(this).find(".sotbit_order_error").hide();
            $(this).find(".sotbit_order_success").hide();

            var error = false;

            if ($(this).find("input[name='UF_CONFIDENTIAL']:checked").length == 0) {
                $(this).find('.checkbox__label').addClass('failed');
                error = true;
            }

            let reqName = this.querySelector('[data-req-name]');
            if (reqName) {
                if (name.length <= 0) {
                    $(this).find("input[name='order_name']").addClass('failed');
                    error = true;
                }
            }

            let reqFio = this.querySelector('[data-req-fio]');
            if (reqFio) {
                if (fio.length <= 0) {
                    $(this).find("input[name='order_fio']").addClass('failed');
                    error = true;
                }
            }

            let reqZip = this.querySelector('[data-req-zip]');
            if (reqZip) {
                if (zip.length <= 0) {
                    $(this).find("input[name='order_zip']").addClass('failed');
                    error = true;
                }
            }

            let reqCity = this.querySelector('[data-req-city]');
            if (reqCity) {
                if (city.length <= 0) {
                    $(this).find("input[name='order_city']").addClass('failed');
                    error = true;
                }
            }

            let reqLocation = this.querySelector('[data-req-location]');
            if (reqLocation) {
                if (prop_location.length <= 0) {
                    $(this).find("input[name='order_location']").addClass('failed');
                    error = true;
                }
            }

            let reqAddress = this.querySelector('[data-req-address]');
            if (reqAddress) {
                if (address.length <= 0) {
                    $(this).find("input[name='order_address']").addClass('failed');
                    error = true;
                }
            }

            let reqCompany = this.querySelector('[data-req-company]');
            if (reqCompany) {
                if (company.length <= 0) {
                    $(this).find("input[name='order_company']").addClass('failed');
                    error = true;
                }
            }

            let reqCompany_adr = this.querySelector('[data-req-company_adr]');
            if (reqCompany_adr) {
                if (company_adr.length <= 0) {
                    $(this).find("input[name='order_company_adr']").addClass('failed');
                    error = true;
                }
            }

            let reqInn = this.querySelector('[data-req-inn]');
            if (reqInn) {
                if (inn.length <= 0) {
                    $(this).find("input[name='order_inn']").addClass('failed');
                    error = true;
                }
            }

            let reqKpp = this.querySelector('[data-req-kpp]');
            if (reqKpp) {
                if (kpp.length <= 0) {
                    $(this).find("input[name='order_kpp']").addClass('failed');
                    error = true;
                }
            }

            let reqContact_person = this.querySelector('[data-req-contact_person]');
            if (reqContact_person) {
                if (contact_person.length <= 0) {
                    $(this).find("input[name='order_contact_person']").addClass('failed');
                    error = true;
                }
            }

            let reqFax = this.querySelector('[data-req-fax]');
            if (reqFax) {
                if (fax.length <= 0) {
                    $(this).find("input[name='order_fax']").addClass('failed');
                    error = true;
                }
            }

            let reqEmail = this.querySelector('[data-req-email]');
            if (reqEmail) {
                var pattern = /^([a-z0-9_\.-])+@[a-z0-9-]+\.([a-z]{2,4}\.)?[a-z]{2,4}$/i;
                if (!pattern.test(email)) {
                    $(this).find("input[name='order_email']").addClass('failed');
                    error = true;
                }
            }

            let reqComment = this.querySelector('[data-req-comment]');
            if (reqComment) {
                if (comment.length <= 0) {
                    $(this).find("textarea[name='order_comment']").addClass('failed');
                    error = true;
                }
            }

			if (phone.length < 8) {
                    $(this).find("input[name='order_phone']").addClass('failed');
                    error = true;
                }

            if (!error) {
                $(this).find("input[type='text']").removeClass("red");
                ser = $(this).serialize();
                let btnLoader = this.querySelector('.popup-window-submit_button');
                createBtnLoader(btnLoader);
                userType = 0;

                if (smsconfirm === 'Y') {

                    BX.ajax.post("/bitrix/components/sotbit/order.phone/ajax.php",
                        {'order_phone':v, 'SMS_CONFIRM':smsconfirm, 'SMS_CODE':smscode, 'PHONE_CHECKED':checked}, function (response) {

                            switch (response) {
                                case 'sent':
                                    _this.find(".confidential-field").hide();
                                    _this.find(".popup-window-submit_button").hide();
                                    _this.find(".sms-confirm").show();

                                    let orderPhone = document.querySelector("input[name='order_phone']");
                                    orderPhone.classList.add("blocked");

                                    let form = document.querySelector("form.sotbit_order_phone_form .sms-resend"),
                                        seconds = parseInt(document.querySelector("input[name=SMS_REPEATED_TIME]").getAttribute("value"),10),
                                        timeText = document.createElement("span"),
                                        resend = document.createElement("span");
                                    timeText.classList.add("sms-resend__timer");
                                    resend.classList.add("sms-resend__resend");
                                    resend.innerHTML = "Отправить код повторно";
                                    timeText.innerHTML = "Вы сможете отправить код повторно через <span class='seconds'>"+seconds+"</span> сек.";

                                    startTimer(seconds);
                                    resend.removeEventListener("click", SendAjax);
                                    resend.addEventListener("click", SendAjax);


                                function SendAjax(){
                                    BX.ajax.post("/bitrix/components/sotbit/order.phone/ajax.php",
                                        {'SMS_RESEND':'Y', 'order_phone':v, 'SMS_CONFIRM':smsconfirm, 'SMS_CODE':smscode, 'PHONE_CHECKED':checked}, function (response) {
                                            if (response === 'exceeded') {
                                                _this.find(".popup-sms-error-message").show();
                                                _this.find(".sotbit_sms_exceeded").show();
                                                _this.find(".sms-resend").hide();
                                            }
                                        });

                                }

                                function startTimer(seconds){
                                    form.append(timeText);
                                    let counter = seconds,
                                        timer = timeText.querySelector(".seconds");
                                    timer.innerHTML = counter;
                                    let interval = setInterval(() => {
                                        if(counter>1){
                                            counter--;
                                            timer.innerHTML = counter;
                                        }
                                        else{
                                            form.append(resend);
                                            timeText.remove();
                                            clearInterval(interval);
                                        }
                                    }, 1000);
                                }

                                    resend.addEventListener("click",function(){

                                        resend.remove();
                                        startTimer(seconds);

                                    });
                                    break;

                                case 'confirm':
                                    _this.find(".popup-sms-error-message").hide();
                                    checked = 'Y';
                                    ajaxOneClick(ser);
                                    break;

                                case 'wrongcode':
                                    _this.find(".sotbit_sms_code_error").show();
                                    _this.find(".popup-sms-error-message").show();
                                    break;

                                case 'exceeded':
                                    _this.find(".sotbit_sms_exceeded").show();
                                    _this.find(".popup-sms-error-message").show();
                                    break;

                                default:
                                    let sendError = document.querySelector(".sotbit_sms_send_error");
                                    sendError.innerHTML = response;
                                    _this.find(".sotbit_sms_send_error").show();
                                    _this.find(".popup-sms-error-message").show();


                            }
                        });
                } else {
                    ajaxOneClick(ser);
                }


                function ajaxOneClick(ser){
                    BX.ajax.post("/bitrix/components/sotbit/order.phone/ajax.php", ser, function (data) {
                        removeBtnLoader(btnLoader);
                        data = $.trim(data);
                        if (data.indexOf("SUCCESS") >= 0) {
							_this.find(".sotbit_order_timedate").hide();
                            _this.find(".sotbit_order_success").show();
                            _this.find(".sotbit_order_error").hide();
                            _this.find(".hide-on-success").hide();
                            id = data.replace("SUCCESS", "").split(',')[0];
                            userPassword = data.replace("SUCCESS", "").split(',')[1];
                            localHref = $('input[name="LOCAL_REDIRECT"]').val();
                            orderID = $('input[name="ORDER_ID"]').val();
                            if (userPassword.length > 0) {
                                _this.find(".sotbit_order_timedate").show();
                                _this.find(".time_date_login").text('<?=Loc::getMessage('FORM_LOGIN'); ?> ' + data.replace("SUCCESS", "").split(',')[2]);
                                _this.find(".time_date_pass").text('<?=Loc::getMessage("FORM_PASSWORD"); ?> ' + userPassword);
                            }

                            if (typeof (localHref) != "undefined" && localHref != "") {
                                location.href = localHref + "?" + orderID + "=" + id;
                            }
                        } else {
                            _this.find(".sotbit_order_success").hide();
                            _this.find(".sotbit_order_timedate").hide();
                            _this.find(".sotbit_order_error").show().html(data);
                        }
                    });
                }
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