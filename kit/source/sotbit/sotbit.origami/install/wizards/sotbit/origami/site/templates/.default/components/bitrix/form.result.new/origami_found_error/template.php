<?

//use Bitrix\Main\Page\Asset;
use Bitrix\Main\Localization\Loc;
use Sotbit\Origami\Helper\Config;
use Bitrix\Main\Loader;

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

Loader::includeModule('sotbit.origami');
$APPLICATION->ShowAjaxHead();
$this->addExternalCss(SITE_DIR . "local/templates/.default/components/bitrix/form.result.new/origami_found_error/style.css");
$this->addExternalJS(SITE_DIR . "local/templates/.default/components/bitrix/form.result.new/origami_found_error/script.js");
//Asset::getInstance()->addJs(SITE_DIR . "local/templates/.default/components/bitrix/form.result.new/origami_found_error/script.js");
//Asset::getInstance()->addCss(SITE_DIR . "local/templates/.default/components/bitrix/form.result.new/origami_found_error/style.css");
$telMask = \Sotbit\Origami\Config\Option::get('MASK', SITE_ID);
?>
<div class="sotbit_order_phone_wrapper found-error-popup">
    <div class="sotbit_order_phone">
        <div class="sotbit_order__title">
            <span class="sotbit_order__title-title">
            <?= GetMessage('POPUP_TITLE'); ?>
            </span>
            <? if (empty($arResult["FORM_NOTE"])) { ?>
                <div class="error-popup-description">
                    <span><?= GetMessage('FOUND_ERROR'); ?></span>
                </div>
            <? } ?>
        </div>

        <div class="popup_resizeable_content">

            <div class="popup-error-message">
                <? if ($arResult["isFormErrors"] == "Y"): ?><?= $arResult["FORM_ERRORS_TEXT"]; ?><? endif; ?>
            </div>

            <div class="sotbit_order_success_show">

                <? if ($arResult["FORM_NOTE"]) : ?>
                    <div class="popup-window-message-content">
                        <svg class="popup-window-icon-check">
                            <use
                                xlink:href="/local/templates/sotbit_origami/assets/img/sprite.svg#icon_check_form"></use>
                        </svg>

                        <div>
                            <div class="popup-window-message-title"><?= GetMessage('OK_THANKS'); ?></div>
                            <div style="font-size: 16px;"><?= GetMessage('OK_MESSAGE'); ?></div>
                        </div>

                    </div>
                <? endif; ?>
            </div>

            <? if (empty($arResult["FORM_NOTE"])) { ?>
                <?= $arResult["FORM_HEADER"] ?>
                <? foreach ($arResult["QUESTIONS"] as $FIELD_SID => $arQuestion): ?>
                    <? if ($arQuestion['STRUCTURE'][0]['FIELD_TYPE'] == 'textarea'): ?>
                        <div class="found-error-popup__textarea">
                            <textarea name="form_<?=$arQuestion['STRUCTURE'][0]['FIELD_TYPE']?>_<?=$arQuestion['STRUCTURE'][0]['ID']?>" cols="40" rows="5" class="inputtextarea"
                                      placeholder="<?=GetMessage("COMMENT");?>" <?if($arQuestion['REQUIRED'] == 'Y'):?> required <?endif;?> ></textarea>
                        </div>
                    <? elseif ($arQuestion['STRUCTURE'][0]['FIELD_TYPE'] == 'file'): ?>
                        <div class="error-popup__file-input">
                            <span class="error-popup__file-input-file-title">
                                 <input
                                     name="form_<?= $arQuestion['STRUCTURE'][0]['FIELD_TYPE'] ?>_<?= $arQuestion['STRUCTURE'][0]['ID'] ?>"
                                     id="found-error-input_<?= $arQuestion['STRUCTURE'][0]['ID'] ?>"
                                     class="inputfile"
                                     style="display: none"
                                     size="0"
                                     type="file"  <? if ($arQuestion['REQUIRED'] == 'Y'): ?> required <? endif; ?> >
                                 <label for="found-error-input_<?= $arQuestion['STRUCTURE'][0]['ID'] ?>">
                                   <div class="error-popup__file-input-icon-wrapper">
                                       <svg class="icon_paperclip_small" width="14" height="16">
                                        <use
                                        xlink:href="/local/templates/sotbit_origami/assets/img/sprite.svg#icon_paperclip_small"></use>
                                       </svg>
                                   </div>
                                     <?= GetMessage('GET_FILE_TITLE'); ?>
                                </label>
                            </span>
                            <span class="error-popup__file-input-file-types"><?= GetMessage('FILE_TYPES'); ?></span>
                        </div>
                    <? endif; ?>
                <? endforeach; ?>

                <div class="error-popup__files-list">
                </div>

                <? if ($arResult["isUseCaptcha"] == "Y") { ?>
                    <div class="sotbit_order_phone__block">
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

                <div class="popup-window-submit_button found-error-popup__submit-button">

                    <input type="button" name="web_form_submit"
                           value="<?= htmlspecialcharsbx(strlen(trim($arResult["arForm"]["BUTTON"])) <= 0 ? GetMessage("FORM_ADD") : $arResult["arForm"]["BUTTON"]); ?>"
                           onclick="sendForm('<?= $arResult['arForm']['SID'] ?>', '<?= Config::get('COLOR_BASE', $arParams['SITE_ID']) ?>')">
                    <input type="submit" name="web_form_submit" style="display:none;"
                           value="<?= htmlspecialcharsbx(strlen(trim($arResult["arForm"]["BUTTON"])) <= 0 ? GetMessage("FORM_ADD") : $arResult["arForm"]["BUTTON"]); ?>">

                </div>

                <?= $arResult["FORM_FOOTER"] ?>


            <? } ?>

        </div>
    </div>

</div>

<script>
    function sendForm(sid, color) {
        $("form[name='" + sid + "'] input[type='submit']").trigger('click');
    }

    function tu () {
        var inputFile = $('.inputfile')[0].files;
        $(inputFile).each(function (index, listHtml = '') {
            $('.error-popup__files-list').html('');
            listHtml = '<div class="error-popup__file">\n' +
                '                    <span>' + this["name"] + '</span>\n' +
                '                    <a class="error-popup__remove-file" onclick="deleteFile(this);">\n' +
                '                        <svg class="icon_cancel_filter_small" width="8" height="8">\n' +
                '                            <use xlink:href="/local/templates/sotbit_origami/assets/img/sprite.svg#icon_cancel_filter_small"></use>\n' +
                '                        </svg>\n' +
                '                    </a>\n' +
                '                </div>';

            $('.error-popup__files-list').append(listHtml);

        });
        document.querySelector('.inputfile').removeEventListener('change', tu);
        document.querySelector('.inputfile').addEventListener('change', tu);
    }
    document.querySelector('.inputfile').addEventListener('change', tu);


    function deleteFile(butt) {
        $(butt).parent().remove();
        $('.inputfile')[0].value = "";
    }

    $(document).ready(function () {
        $('.js-phone').inputmask("<?= $telMask ?>");

        $('.error-popup__remove-file').click(function () {
            console.log($(this).parent());
        });

    });
</script>
