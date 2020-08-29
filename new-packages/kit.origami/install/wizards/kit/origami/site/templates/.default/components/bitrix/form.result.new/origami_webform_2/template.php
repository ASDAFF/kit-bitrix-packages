<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Page\Asset;
use Sotbit\Origami\Helper\Config;

$telMask = \Sotbit\Origami\Config\Option::get('MASK', SITE_ID);
Asset::getInstance()->addCss(SITE_DIR . "local/templates/.default/components/bitrix/form.result.new/origami_webform_2/style.css");
$prefix = \Bitrix\Main\Security\Random::getString(5);
?>

<div class="puzzle_block-form-promotion feedback">
    <div class="feedback_block__text">
        <? if ($arResult["isFormTitle"] == "Y"): ?>
            <div class="feedback_block__title fonts__middle_title"><?= $arResult["FORM_TITLE"] ?></div>
        <? endif ?>
        <? if ($arResult["isFormDescription"] == "Y"): ?>
            <div class="feedback_block__comment fonts__small_text"><?= $arResult["FORM_DESCRIPTION"] ?></div>
        <? endif ?>
    </div>

    <?= $arResult["FORM_HEADER"] ?>
    <div class="form_blocks">
        <? if ($_GET['formresult'] == 'addok') {
            ?>
            <div class="form_blocks__ok"><?= GetMessage("OK_MESSAGE") ?></div>
            <?
        }
        ?>
        <div class="row">
            <div class="col-md-6 form-block-left">
                <div class="form_block_title">
                    <?= GetMessage('SOTBIT_FORM_TITLE_1'); ?>
                </div>
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

                            <? if ($arQuestion['STRUCTURE'][0]['FIELD_TYPE'] == 'tel'): ?>
                            <div class="phone_input">
                                <? if ($typeMask == 'Y'): ?>
                                    <span class="phone_input__flag">
                                        <span id="flag<?= $arResult['arForm']['SID'] ?>"
                                              onclick="fixCountryPopup(this)"></span>
                                </span>
                                <? endif; ?>
                                <? endif; ?>

                                <div class="main-input-md__wrapper <?= ($typeMask == 'N') ? 'fullsize' : ''; ?>">
                                    <input
                                        name="form_<?= ($arQuestion['STRUCTURE'][0]['FIELD_TYPE'] == 'tel')
                                            ? 'text'
                                            : $arQuestion['STRUCTURE'][0]['FIELD_TYPE'] ?>_<?= $arQuestion['STRUCTURE'][0]['ID'] ?>"
                                        <?= ($arQuestion['REQUIRED'] == 'Y') ? 'required' : '' ?>

                                        <? if ($arQuestion['STRUCTURE'][0]['FIELD_TYPE'] == 'tel'): ?>
                                            placeholder="<?= Config::get('MASK') ?>"
                                            type="text"
                                            class='main-input-md main-input-bg--gray filled phone-callback--form js-phone' maxlength='17' id="number<?= $arResult['arForm']['SID'] ?>"
                                        <? else: ?>
                                            type="<?= $arQuestion['STRUCTURE'][0]['FIELD_TYPE'] ?>"
                                            class="main-input-md main-input-bg--gray"
                                            id="<?= $arQuestion['STRUCTURE'][0]['ID'] ?>"
                                            onchange="isInputFilled(this)"
                                        <? endif; ?>
                                    >

                                    <label
                                        <? if ($arQuestion['STRUCTURE'][0]['FIELD_TYPE'] == 'tel'): ?>
                                            for="number<?= $arResult['arForm']['SID'] ?>"
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

                        </div>
                        <?
                    }
                }
                ?>
            </div>
            <div class="col-md-6 form-block-right">
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
                        <label class="puzzle_block__form_label_promotion form_block_title" for="">
                            <?= $arQuestion["CAPTION"] ?> <?= ($arQuestion['REQUIRED'] == 'Y') ? '<span class="required">*</span>' : '(' . GetMessage('SOTBIT_NOT_REQUIRED') . ')' ?>
                        </label>

                        <div class="main-textarea-md__wrapper">
                            <textarea class="main-textarea-md main-textarea-md--gray"
                                      name="form_<?= $fieldType ?>_<?= $arQuestion['STRUCTURE'][0]['ID'] ?>"
                            <?= ($arQuestion['REQUIRED'] == 'Y') ? 'required' : '' ?>
                                id="feedback-textarea_<?= $arQuestion['STRUCTURE'][0]['ID'] ?>"
                                      onchange="isInputFilled(this)"
                            ></textarea>
                            <label class="main-label-textarea-md main-label-textarea-md--gray"
                                   for="feedback-textarea_<?= $arQuestion['STRUCTURE'][0]['ID'] ?>">
                                <?= $arQuestion["CAPTION"] ?></label>
                        </div>
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
                <div class="feedback_block__captcha-new-input-wrapper">
                    <div class="feedback_block__captcha_input main-input-md__wrapper">
                        <input type="text" class="main-input-md main-input-bg--gray" name="captcha_word"
                               id="contacts-call-back-form-captcha" size="30" maxlength="50" value=""
                               onchange="isInputFilled(this)"
                               required/>
                        <label class="main-label-md main-label-md--grey" for="contacts-call-back-form-captcha">
                            <?= GetMessage('CAPTCHA_TITLE'); ?>
                        </label>
                    </div>
                    <div class="feedback_block__captcha_img">
                        <input type="hidden" name="captcha_sid"
                               value="<?= htmlspecialcharsbx($arResult["CAPTCHACode"]); ?>"/><img
                            src="/bitrix/tools/captcha.php?captcha_sid=<?= htmlspecialcharsbx($arResult["CAPTCHACode"]); ?>"
                            width="180" height="40"/>
                        <div class="captcha-refresh" onclick="reloadCaptcha(this,'<?= SITE_DIR ?>');return false;">
                            <svg class="icon_refresh" width="16" height="14"
                                 style="color: <?= \Sotbit\Origami\Helper\Config::get('COLOR_BASE') ?>; ">
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
        <div class="feedback_block__compliance">
            <div class="feedback_block__compliance main_checkbox">
                <input type="checkbox" id="personal_phone_personal<?= $prefix ?>" class="checkbox__input"
                       checked="checked" name="personal">
                <label for="personal_phone_personal<?= $prefix ?>">
                    <span></span>
                    <span> <?= GetMessage('FORM_CONFIDENTIAL_1') ?>
                             <a class="feedback_block__compliance_link"
                                href="<?= \Sotbit\Origami\Helper\Config::get('CONFIDENTIAL_PAGE') ?>"><?= GetMessage('FORM_CONFIDENTIAL_2') ?>
                            </a>
                        </span>
                </label>
            </div>
        </div>
        <div class="feedback_block__input_wrapper">
            <input
                type="button"
                class="feedback_block__input main_btn button_feedback sweep-to-right"
                name="web_form_submit"
                value="<?= GetMessage("FORM_SUBMIT") ?>"
                onclick="sendForm('<?= $arResult['arForm']['SID'] ?>','<?= \Sotbit\Origami\Helper\Config::get('COLOR_BASE') ?>')"
            >
            <input style="display: none" type="reset" class="feedback_block__input main_btn
					button_feedback sweep-to-right" name="web_form_submit"
                   value="<?= GetMessage("FORM_RESET") ?>">
            <input type="submit" style="display:none"
                   name="web_form_submit" id="submit">
        </div>
    </div>
</div>
<?= $arResult["FORM_FOOTER"] ?>
</div>

<script>
    $(document).ready(function () {
        $('.js-phone').inputmask("<?= $telMask ?>");
    });
</script>
