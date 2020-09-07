<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

use Kit\Origami\Helper\Config;
use Bitrix\Main\Page\Asset;

$this->setFrameMode(true);
//CJSCore::Init(['phone_number']);

$telMask = \Kit\Origami\Config\Option::get('MASK', SITE_ID);
$prefix = '_' . \Bitrix\Main\Security\Random::getString(3);
$bxajaxid = CAjax::GetComponentID($component->__name, $component->__template->__name, $arParams['AJAX_OPTION_ADDITIONAL']);
if ($_REQUEST['formresult'] == 'addok')
    $APPLICATION->RestartBuffer();

Asset::getInstance()->addJs($templateFolder . "/js/jquery.maskedinput.min.js");
$typeMask = (Config::get('TYPE_MASK_VIEW') == 'FLAGS') ? 'Y' : 'N';
if ($typeMask == 'Y')
    CJSCore::Init(['phone_number']);
?>
<div class="feedback_block feedback_block__main-page">
    <div class="puzzle_block main-container">
        <div class="feedback_block__text">
            <? if ($arResult["isFormTitle"] == "Y"): ?>
                <div class="feedback_block__title fonts__middle_title"><?= $arResult["FORM_TITLE"] ?></div>
            <? endif ?>
            <? if ($arResult["isFormDescription"] == "Y"): ?>
                <div class="feedback_block__comment fonts__small_text"><?= $arResult["FORM_DESCRIPTION"] ?></div>
            <? endif ?>
            <? if ($arResult["FORM_NOTE"]): ?>
                <div class="success-message">
                    <span><?= $arResult["FORM_NOTE"] ?></span>
                </div>
            <? endif; ?>
        </div>

        <?= $arResult["FORM_HEADER"] ?>
        <div class="row">
            <?
            foreach ($arResult["QUESTIONS"] as $FIELD_SID => $arQuestion) {
                $fieldType = $arQuestion['STRUCTURE'][0]['FIELD_TYPE'];
                if ($fieldType == 'hidden') {
                    echo $arQuestion["HTML_CODE"];
                } else {
                    ?>
                    <div class="col-xl-3 col-lg-3 col-md-3 col-12">

                        <? if ($arQuestion['CAPTION'] == GetMessage("KIT_FORM_TEL")): ?>
                        <div class="phone_input_feedback">
                            <? if ($typeMask == 'Y'): ?>
                                <span class="phone_input__flag">
                                    <span id="flag<?= $arResult['arForm']['SID'] ?>"></span>
                                </span>
                            <? endif; ?>
                            <? endif; ?>

                            <label class="main-input-md__wrapper <?= ($typeMask == 'N') ? 'fullsize' : '' ?> ">
                                <input
                                    type="<?= $arQuestion["CAPTION"] == GetMessage("KIT_FORM_TEL") ? "text" : $fieldType ?>"
                                    name="form_<?= $fieldType ?>_<?= $arQuestion['STRUCTURE'][0]['ID'] ?>"

                                    <? if ($arQuestion['CAPTION'] == GetMessage("KIT_FORM_TEL")): ?>
                                        class="main-input-md js-phone filled"
                                        placeholder="<?= $telMask ?>"
                                        id='number<?= $arResult['arForm']['SID'] ?>'
                                    <? else: ?>
                                        class="main-input-md"
                                        id="<?= $fieldType ?>__<?= $arQuestion['STRUCTURE'][0]['ID'] . $prefix ?>"
                                        onchange="isInputFilled(this)"

                                    <? endif; ?>

                                    <?= ($arQuestion['REQUIRED'] == 'Y') ? 'required' : '' ?>

                                >
                                <label class="main-label-md"
                                       for="<?= $fieldType ?>__<?= $arQuestion['STRUCTURE'][0]['ID'] . $prefix ?>"><?= $arQuestion["CAPTION"] ?>  <?= ($arQuestion['REQUIRED'] == 'Y') ? '*' : '' ?></label>
                            </label>

                            <? if ($arQuestion['CAPTION'] == GetMessage("KIT_FORM_TEL")): ?>
                        </div>
                    <? endif; ?>

                    </div>
                    <?
                }
            }
            ?>
            <div class="col-xl-3 col-lg-3 col-md-3 col-12">
                <input type="button" class="main_btn-big" name="web_form_submit"
                       value="<?= GetMessage("FORM_SUBMIT") ?>"
                       onclick="sendForm('<?= $bxajaxid ?>','<?= \Kit\Origami\Helper\Config::get('COLOR_BASE') ?>')"
                    <? if ($_REQUEST['formresult'] == 'addok'): ?>
                        disabled="disabled"
                    <? endif; ?>
                >
                <input type="submit" style="display:none"
                       name="web_form_submit" id="submit_<?= $bxajaxid ?>">
            </div>
            <div class="feedback_block__compliance main_checkbox conf">
                <input type="checkbox" id="personal_phone_personal<?= $prefix ?>" class="checkbox__input"
                       checked="checked" name="personal">
                <label for="personal_phone_personal<?= $prefix ?>">
                    <span></span>
                    <span>
                        <span class="confidential">
                            <?= GetMessage('KIT_FORM_I_AGREE') ?>
                        </span>
                        <a href="<?= \Kit\Origami\Helper\Config::get('CONFIDENTIAL_PAGE') ?>" target="_blank">
                            <?= GetMessage('KIT_FORM_I_AGREE2') ?>
                        </a>
                    </span>
                </label>
                <input type="hidden" id="form_<?= $bxajaxid ?>" value="<?= $bxajaxid ?>">
            </div>
        </div>
        <?= $arResult["FORM_FOOTER"] ?>
    </div>
</div>

<script>
    function sendForm(sid, color) {
        let form = $('#form_' + sid).parent().parent().parent();

        if (form.find("input[name='personal']").is(':checked')) {
            form.find('input#submit_' + sid).trigger('click');
        } else {
            $('.feedback_block__compliance svg path').css({'stroke': color, 'stroke-dashoffset': 0});
        }
    }

    <?if($typeMask == 'Y'):?>
    BX.ready(function () {
        if (document.getElementById("number" + "<?= $arResult['arForm']['SID'] ?>")) {
            new BX.PhoneNumber.Input({
                node: BX("number" + "<?= $arResult['arForm']['SID'] ?>"),
                forceLeadingPlus: true,
                flagNode: BX("flag" + "<?= $arResult['arForm']['SID'] ?>"),
                flagSize: 16,
                countryPopupClassName: 'feedback_block__select-country-popup',
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
            $(".kit_order_phone form input.phone-callback--form").mask(maska, {placeholder: "_"});
    });

    <?endif;?>
</script>
<?
if ($_REQUEST['formresult'] == 'addok') {
    ?>
    <script>
        document.forms.SIMPLE_FORM_1.action = '/';
        document.forms.SIMPLE_FORM_1.reset();
    </script>
    <?
    die();
}

?>
