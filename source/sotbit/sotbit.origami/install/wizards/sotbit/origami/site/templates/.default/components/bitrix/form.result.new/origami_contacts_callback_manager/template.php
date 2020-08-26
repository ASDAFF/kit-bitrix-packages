<?

use Bitrix\Main\Localization\Loc;
use Sotbit\Origami\Helper\Config;
use Bitrix\Main\Loader;

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

Loader::includeModule('sotbit.origami');

$telMask = \Sotbit\Origami\Config\Option::get('MASK',SITE_ID);
?>
<div class="sotbit_order_phone_wrapper">
    <div class="sotbit_order_phone">
        <? if ($arResult["isFormTitle"]) { ?>
            <div class="sotbit_order_phone__title"><?=GetMessage('FORM_TITLE')?></div>
        <? } ?>
        <div class="popup_resizeable_content">
            <div class="popup-error-message">
                <? if ($arResult["isFormErrors"] == "Y"): ?><?= $arResult["FORM_ERRORS_TEXT"]; ?><? endif; ?>
            </div>
            <div class="sotbit_order_success_show">
                <? if ($arResult["FORM_NOTE"]) : ?>
                    <div class="popup-window-message-content">

                        <svg class="popup-window-icon-check">
                            <use xlink:href="/local/templates/sotbit_origami/assets/img/sprite.svg#icon_check_form"></use>
                        </svg>

                        <div>
                            <div class="popup-window-message-title"><?=GetMessage('OK_THANKS');?></div>
                            <div style="font-size: 16px;"><?= $arResult["FORM_NOTE"] ?></div>
                        </div>

                    </div>
                <? endif; ?>
            </div>
            <? if (empty($arResult["FORM_NOTE"])) {
                ?>
                <?= $arResult["FORM_HEADER"] ?>
                <?
                foreach ($arResult["QUESTIONS"] as $FIELD_SID => $arQuestion) {
                    ?>
                    <div class="sotbit_order_phone__block">
                        <?
                        if ($arQuestion['CAPTION'] !== Loc::getMessage('OK_ID_PRODUCT')) { ?>
                            <p class="sotbit_order_phone__block_title">
                                <?= $arQuestion['CAPTION'] ?>
                                &nbsp;<span><?= ($arQuestion['REQUIRED'] == 'Y') ? '*' : '' ?></span>
                            </p>
                            <?
                        } ?>

                        <?
                        if ($arQuestion['STRUCTURE'][0]['FIELD_TYPE'] != 'textarea'):?>
                            <input
                                    type="<?= $arQuestion['STRUCTURE'][0]['FIELD_TYPE'] ?>"
                                    name="form_<?= ($arQuestion['STRUCTURE'][0]['FIELD_TYPE'] == 'tel') ? 'text' : $arQuestion['STRUCTURE'][0]['FIELD_TYPE'] ?>_<?= $arQuestion['STRUCTURE'][0]['ID'] ?>"
                                    value="<?= (!empty($arQuestion['STRUCTURE'][0]['VALUE']) ? $arQuestion['STRUCTURE'][0]['VALUE'] :
                                        $_REQUEST["form_" . $arQuestion['STRUCTURE'][0]['FIELD_TYPE'] . "_" . $arQuestion['STRUCTURE'][0]['ID']]) ?>"
                                <?= ($arQuestion['REQUIRED'] == 'Y') ? 'required' : '' ?>
                                <?= ($arQuestion['STRUCTURE'][0]['MASK']) ? 'placeholder="' . $arQuestion['STRUCTURE'][0]['MASK'] . '"' :
                                    ( !empty($telMask) ? 'placeholder="' . $telMask . '"' : "" ) ?>
                                <?= ($arQuestion['STRUCTURE'][0]['PATTERN']) ? 'pattern="' . $arQuestion['STRUCTURE'][0]['PATTERN'] . '"' : '' ?>
                                <?= ( $arQuestion['STRUCTURE'][0]['FIELD_TYPE'] == 'tel' ? "class='js-phone'" : "" ) ?>
                            >
                        <? else: ?>
                            <?= $arQuestion["HTML_CODE"] ?>
                        <? endif ?>
                    </div>
                    <?
                }
                if ($arResult["isUseCaptcha"] == "Y") {
                    ?>
                    <div class="sotbit_order_phone__block">
                        <div class="feedback_block__captcha">
                            <p class="feedback_block__captcha_text">
                                <?= GetMessage('CAPTCHA_TITLE') ?>
                            </p>
                            <div class="feedback_block__captcha_input">
                                <input type="text" name="captcha_word" size="30" maxlength="50" value="" required/>
                            </div>
                            <div class="feedback_block__captcha_img">
                                <input type="hidden" name="captcha_sid"
                                       value="<?= htmlspecialcharsbx($arResult["CAPTCHACode"]); ?>"/><img
                                        src="/bitrix/tools/captcha.php?captcha_sid=<?= htmlspecialcharsbx($arResult["CAPTCHACode"]); ?>"
                                        width="180" height="40"/>
                                <div class="feedback_block__captcha_reload"
                                     onclick="reloadCaptcha(this,'<?= SITE_DIR ?>');return false;"></div>
                            </div>
                        </div>
                    </div>
                    <?
                }
                ?>
                <div class="feedback_block__compliance">
                    <div class="cntr">
                        <label for="personal_phone_personal" class="label-cbx">
                            <input id="personal_phone_personal" type="checkbox" class="invisible" name="personal">
                            <span class="checkbox">
                        <svg width="20px" height="20px" viewBox="0 0 20 20">
                            <path d="M3,1 L17,1 L17,1 C18.1045695,1 19,1.8954305 19,3 L19,17 L19,17 C19,18.1045695
                            18.1045695,19 17,19 L3,19 L3,19 C1.8954305,19 1,18.1045695 1,17 L1,3 L1,3 C1,1.8954305
                            1.8954305,1 3,1 Z"></path>
                            <polyline points="4 11 8 15 16 6"></polyline>
                        </svg>
                    </span>
                            <span class="feedback_block__compliance_title fonts__middle_comment">
                        <?= Loc::getMessage('OK_CONFIDENTIAL') ?>

                      <a class="feedback_block__compliance_link fonts__middle_comment"
                         href="<?= Config::get('CONFIDENTIAL_PAGE', $arParams['SITE_ID']) ?>">
                                    <?= Loc::getMessage('OK_CONFIDENTIAL2') ?>
                                    </a>

                    </span>
                        </label>

                    </div>
                </div>

                <div class="popup-window-submit_button">
                    <input type="button" name="web_form_submit"
                           value="<?= htmlspecialcharsbx(strlen(trim($arResult["arForm"]["BUTTON"])) <= 0 ? GetMessage("FORM_ADD") : $arResult["arForm"]["BUTTON"]); ?>"
                           onclick="sendForm('<?= $arResult['arForm']['SID'] ?>', '<?= Config::get('COLOR_BASE', $arParams['SITE_ID']) ?>')">
                    <input type="submit" name="web_form_submit" style="display:none;"
                           value="<?= htmlspecialcharsbx(strlen(trim($arResult["arForm"]["BUTTON"])) <= 0 ? GetMessage("FORM_ADD") : $arResult["arForm"]["BUTTON"]); ?>">
                </div>


                <?= $arResult["FORM_FOOTER"] ?>
                <?
            } ?>
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
    $(document).ready(function() {
        $('.js-phone').inputmask("<?= $telMask ?>");
    });
</script>
<script>
    ;(function resizeCheckStockPopup() {
        let wrapper = document.querySelector(".wrap-popup-window"),
            popupResizeableContent = wrapper.querySelector(".popup_resizeable_content"),
            popupWindow = wrapper.querySelector(".popup-window"),
            popupContent = wrapper.querySelector(".popup-content"),
            popupTitle = wrapper.querySelector(".sotbit_order_phone__title");

        resize();
        setUpListeners();

        function setUpListeners() {
            popupResizeableContent.addEventListener("scroll", putTitleShadow);

            window.addEventListener("resize", resize);

            popupContent.addEventListener("load", resize);
        }

        function resize() {
            resizePopupContent();
            putTitleShadow();
        }

        function resizePopupContent() {
            let clientHeight = document.documentElement.clientHeight * 0.97,
                newHeight;

            popupResizeableContent.style.overflowY = "hidden";
            popupResizeableContent.style.height = "auto";

            if (popupContent.clientHeight > (popupWindow.clientHeight + 2)
                || popupContent.clientHeight > (clientHeight + 2)) {

                newHeight = (clientHeight < popupWindow.clientHeight) ?
                    (clientHeight - popupTitle.clientHeight) :
                    (popupWindow.clientHeight - popupTitle.clientHeight);

                popupResizeableContent.style.overflowY = "auto";
                popupResizeableContent.style.height = newHeight + "px";
            }
        }

        function putTitleShadow() {
            let title = wrapper.querySelector(".sotbit_order_phone__title");
            let scrolled = popupResizeableContent.scrollTop;

            if (scrolled === 0) {
                title.style.boxShadow = "none";
            } else {
                title.style.boxShadow = "0 2px 5px 3px rgba(0,0,0,.1)";
            }
        }
    })();

</script>
