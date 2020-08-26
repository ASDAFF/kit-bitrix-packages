<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
$mask = '';
?>

<div class="contacts-call-back-form">
    <div class="contacts-call_back-title-wrapper">
        <? if ($arResult["isFormTitle"] == "Y"): ?>
            <div class="contacts-call_back-title"><?= $arResult["FORM_TITLE"] ?></div>
        <? endif ?>
        <? if ($arResult["isFormDescription"] == "Y"): ?>
            <div class="contacts-call_back-title"><?= $arResult["FORM_DESCRIPTION"] ?></div>
        <? endif ?>
    </div>

    <?= $arResult["FORM_HEADER"] ?>
    <div>

        <div class="form_block_title">
            <?= GetMessage('KIT_FORM_TITLE_1'); ?>
        </div>
        <div class="inputs_container">
            <div class="form-block-left">
                <?
                foreach ($arResult["QUESTIONS"] as $FIELD_SID => $arQuestion) {
                    $fieldType = $arQuestion['STRUCTURE'][0]['FIELD_TYPE'];
                    if ($fieldType == 'textarea') {
                        continue;
                    }
                    if ($fieldType == 'hidden') {
                        echo $arQuestion["HTML_CODE"];
                    } else {
                        ?>
                        <div class="form_row">

                            <input id="<?= $arQuestion['STRUCTURE'][0]['ID'] ?>"
                                   type="<?= $arQuestion['STRUCTURE'][0]['FIELD_TYPE'] ?>"
                                   name="form_<?= ($arQuestion['STRUCTURE'][0]['FIELD_TYPE'] == 'tel') ? 'text' : $arQuestion['STRUCTURE'][0]['FIELD_TYPE'] ?>_<?= $arQuestion['STRUCTURE'][0]['ID'] ?>"
                                   value="<?= $arQuestion['STRUCTURE'][0]['VALUE'] ?>"
                                   class="feedback_block__form_input <?= ($arQuestion['STRUCTURE'][0]['FIELD_TYPE'] == 'tel') ? 'js-phone' : '' ?>"
                                   placeholder="<?= ($arQuestion['STRUCTURE'][0]['FIELD_TYPE'] != 'tel') ? $arQuestion["CAPTION"] : $arQuestion['STRUCTURE'][0]['MASK'] ?>"
                                   placeholder="<?= ($arQuestion['STRUCTURE'][0]['FIELD_TYPE'] != 'tel') ? $arQuestion["CAPTION"] : $arQuestion['STRUCTURE'][0]['MASK'] ?>"
                                <?= ($arQuestion['REQUIRED'] == 'Y') ? '' : '' ?>
                                <?
                                if ($arQuestion['STRUCTURE'][0]['MASK']):
                                    $mask = $arQuestion['STRUCTURE'][0]['MASK'];
                                endif;
                                ?>
                                <?= ($arQuestion['STRUCTURE'][0]['PATTERN']) ? 'pattern="' . $arQuestion['STRUCTURE'][0]['PATTERN'] . '"' : '' ?>

                            >
                            <?= ($arQuestion['REQUIRED'] == 'Y') ? ' <div class="required-star">
                           <span>*</span></div>' : '' ?>


                        </div>
                        <?
                    }
                }
                ?>
            </div>
            <div class="form-block-right">
                <?
                foreach ($arResult["QUESTIONS"] as $FIELD_SID => $arQuestion) {
                    $fieldType = $arQuestion['STRUCTURE'][0]['FIELD_TYPE'];
                    if ($fieldType != 'textarea') {
                        continue;
                    }
                    if ($fieldType == 'hidden') {
                        echo $arQuestion["HTML_CODE"];
                    } else {
                        ?>

                        <textarea class="contacts-textarea"
                                  placeholder="<?= $arQuestion["CAPTION"] ?>"
                                  name="form_<?= $fieldType ?>_<?= $arQuestion['STRUCTURE'][0]['ID'] ?>"<?= ($arQuestion['REQUIRED'] == 'Y') ? 'required' : '' ?>
                        ></textarea>
                        <?
                    }
                }
                ?>
            </div>
        </div>
        <?
        if ($arResult["isUseCaptcha"] == "Y") {
            ?>
            <div class="feedback_block__captcha">
                <p class="feedback_block__captcha_text">
                    <?= GetMessage('CAPTCHA_TITLE') ?>
                </p>
                <div class="feedback_block__captcha-new-input-wrapper">
                    <div class="feedback_block__captcha_input">
                        <input type="text" name="captcha_word" size="30" maxlength="50" value="" required/>
                    </div>
                    <div class="feedback_block__captcha_img">
                        <input type="hidden" name="captcha_sid"
                               value="<?= htmlspecialcharsbx($arResult["CAPTCHACode"]); ?>"/><img
                            src="/bitrix/tools/captcha.php?captcha_sid=<?= htmlspecialcharsbx($arResult["CAPTCHACode"]); ?>"
                            width="180" height="40"/>
                        <div class="captcha-refresh" onclick="reloadCaptcha(this,'<?= SITE_DIR ?>');return false;">
                            <svg class="icon_refresh" width="16" height="14"
                                 style="color: <?= \Kit\Origami\Helper\Config::get('COLOR_BASE') ?>; ">
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
        <div class="call-back-buttons">
            <div class="acceptance-checkbox">
                <div class="cntr">
                    <label for="cbx" class="label-cbx">
                        <input id="cbx" type="checkbox" class="invisible"
                               name="personal">
                        <span class="checkbox">
	                        <svg width="20px" height="20px" viewBox="0 0 20 20">
	                            <path d="M3,1 L17,1 L17,1 C18.1045695,1 19,1.8954305 19,3 L19,17 L19,17 C19,18.1045695
	                            18.1045695,19 17,19 L3,19 L3,19 C1.8954305,19 1,18.1045695 1,17 L1,3 L1,3 C1,1.8954305
	                            1.8954305,1 3,1 Z"></path>
	                            <polyline points="4 11 8 15 16 6"></polyline>
	                        </svg>
	                    </span>
                        <p>
                            <?= GetMessage('FORM_CONFIDENTIAL_1') ?>
                            <a class="feedback_block__compliance_link"
                               href="<?= \Kit\Origami\Helper\Config::get('CONFIDENTIAL_PAGE') ?>"><?= GetMessage('FORM_CONFIDENTIAL_2') ?>
                            </a>
                        </p>
                    </label>

                </div>
            </div>
            <div class="contacts-form-send-button">
                <input
                    type="submit"
                    name="web_form_submit"
                    value="<?= GetMessage("FORM_SUBMIT") ?>"
                    onclick="sendForm('<?= $arResult['arForm']['SID'] ?>','<?= \Kit\Origami\Helper\Config::get('COLOR_BASE') ?>')"
                >
                <input type="submit" style="display:none"
                       name="web_form_submit" id="submit">
            </div>
        </div>
    </div>
</div>
<?= $arResult["FORM_FOOTER"] ?>

<script>
    $('.js-phone').inputmask("<?= $mask ?>");
</script>
