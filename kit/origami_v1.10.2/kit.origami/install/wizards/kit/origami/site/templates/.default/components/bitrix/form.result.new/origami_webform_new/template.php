<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Page\Asset;
use Kit\Origami\Helper\Config;

$telMask = \Kit\Origami\Config\Option::get('MASK', SITE_ID);
Asset::getInstance()->addJs($templateFolder . "/js/jquery.maskedinput.min.js");
$typeMask = (Config::get('TYPE_MASK_VIEW') == 'FLAGS') ? 'Y' : 'N';
if ($typeMask == 'Y')
    CJSCore::Init(['phone_number']);

$prefix = \Bitrix\Main\Security\Random::getString(5);
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
        <? if ($_REQUEST["formresult"] == "addok"): ?>
            <div class="success-message">
                <span><?= GetMessage('FORM_ADDOK'); ?></span>
            </div>
        <? endif; ?>
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

                        <? if ($arQuestion['STRUCTURE'][0]['FIELD_TYPE'] == 'tel'): ?>
                            <div class="phone_input">
                            <? if ($typeMask == 'Y'): ?>
                                <span class="phone_input__flag">
                                        <span id="flag<?= $arResult['arForm']['SID'] ?>"
                                              onclick="fixCountryPopup(this)"></span>
                                </span>
                            <? endif; ?>
                        <? endif; ?>

                            <div class="main-input-md__wrapper fullsize">
                                <input
                                    name="form_<?= ($arQuestion['STRUCTURE'][0]['FIELD_TYPE'] == 'tel')
                                        ? 'text'
                                        : $arQuestion['STRUCTURE'][0]['FIELD_TYPE'] ?>_<?= $arQuestion['STRUCTURE'][0]['ID'] ?>"
                                    <?= ($arQuestion['REQUIRED'] == 'Y') ? 'required' : '' ?>

                                    <? if ($arQuestion['STRUCTURE'][0]['FIELD_TYPE'] == 'tel'): ?>
                                        placeholder="<?= Config::get('MASK') ?>"
                                        type="text"
                                        class='main-input-md main-input-bg--gray filled phone-callback--form' maxlength='17' id="number<?= $arResult['arForm']['SID'] ?>"
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

                    <? }
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

                        <div class="main-textarea-md__wrapper">
                            <textarea
                                name="form_<?= $fieldType ?>_<?= $arQuestion['STRUCTURE'][0]['ID'] ?>"
                                class="contacts-textarea main-textarea-md main-textarea-md--gray"
                                <?= ($arQuestion['REQUIRED'] == 'Y') ? 'required' : '' ?>
                                id="contacts-textarea_<?= $arQuestion['STRUCTURE'][0]['ID'] ?>"
                                onchange="isInputFilled(this)"></textarea>
                            <label class="main-label-textarea-md main-label-textarea-md--gray"
                                   for="contacts-textarea_<?= $arQuestion['STRUCTURE'][0]['ID'] ?>">
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
                <div class="feedback_block__compliance main_checkbox">
                    <input type="checkbox" id="personal_phone_personal_<?= $prefix ?>" class="checkbox__input"
                           checked="checked" name="personal">
                    <label for="personal_phone_personal_<?= $prefix ?>">
                        <span></span>
                        <span> <?= GetMessage('FORM_CONFIDENTIAL_1') ?>
                             <a class="feedback_block__compliance_link"
                                href="<?= \Kit\Origami\Helper\Config::get('CONFIDENTIAL_PAGE') ?>"><?= GetMessage('FORM_CONFIDENTIAL_2') ?>
                            </a>
                        </span>
                    </label>
                </div>

            </div>
            <div class="contacts-form-send-button">
                <input
                    type="submit"
                    class="main_btn"
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
    <?if($typeMask == 'Y'):?>
    BX.ready(function () {
        if (document.getElementById("number" + "<?= $arResult['arForm']['SID'] ?>")) {
            new BX.PhoneNumber.Input({
                node: BX("number" + "<?= $arResult['arForm']['SID'] ?>"),
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
            $("form input#numberFEEDBACK").mask(maska, {placeholder: "_"});
    });


    <?endif;?>
</script>

