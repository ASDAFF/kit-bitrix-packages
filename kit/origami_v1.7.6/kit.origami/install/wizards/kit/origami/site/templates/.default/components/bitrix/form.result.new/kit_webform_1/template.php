<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
	die();
}
$this->setFrameMode(true);

$telMask = \Kit\Origami\Config\Option::get('MASK',SITE_ID);
?>
<div class="feedback_block">
    <div class="puzzle_block">
        <div class="feedback_block__text">
            <?if($arResult["isFormTitle"] == "Y"):?>
                <div class="feedback_block__title fonts__middle_title"><?=$arResult["FORM_TITLE"]?></div>
            <?endif?>
            <?if($arResult["isFormDescription"] == "Y"):?>
                <div class="feedback_block__comment fonts__small_text"><?=$arResult["FORM_DESCRIPTION"]?></div>
            <?endif?>
            <span class="success-message"><?=$arResult["FORM_NOTE"]?></span>
        </div>

        <?=$arResult["FORM_HEADER"]?>
            <div class="row">
                <?
                foreach($arResult["QUESTIONS"] as $FIELD_SID => $arQuestion)
                {
                    $fieldType = $arQuestion['STRUCTURE'][0]['FIELD_TYPE'];
                    if($fieldType == 'hidden')
                    {
                        echo $arQuestion["HTML_CODE"];
                    }
                    else
                    {
                ?>
                    <div class="col-xl-3 col-lg-3 col-md-3 col-12">
                        <label class="puzzle_block__form_label">
                            <input
		                            type="<?=$arQuestion["CAPTION"] == GetMessage("KIT_FORM_TEL") ? "tel" : $fieldType?>"
		                            class="feedback_block__form_input <?= ( $arQuestion['CAPTION'] == GetMessage("KIT_FORM_TEL") ? "js-phone" : "" ) ?>"
                                    name="form_<?=$fieldType?>_<?=$arQuestion['STRUCTURE'][0]['ID']?>"
                                    placeholder="<?=$arQuestion["CAPTION"]?>"

                            >
                        </label>
                    </div>
                <?
                    }
                }
                ?>
                <div class="col-xl-3 col-lg-3 col-md-3 col-12 feedback_block__input_wrapper">
                    <input type="button" class="feedback_block__input main_btn
                    button_feedback sweep-to-right" name="web_form_submit"
                           value="<?=GetMessage("FORM_SUBMIT")?>"
                           onclick="sendForm('<?=$arResult['arForm']['SID']?>','<?=\Kit\Origami\Helper\Config::get('COLOR_BASE')?>')">
	                <input type="submit" style="display:none"
	                       name="web_form_submit" id="submit">
                </div>
                <div class="feedback_block__compliance">
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
                            <span class="feedback_block__compliance_title fonts__middle_comment">
                                <?=GetMessage('KIT_FORM_I_AGREE')?>
                            </span>
                        </label>
                        <a class="feedback_block__compliance_link fonts__middle_comment" href="<?=\Kit\Origami\Helper\Config::get('CONFIDENTIAL_PAGE')?>"><?=GetMessage('KIT_FORM_I_AGREE2')?></a>
                    </div>
                </div>
            </div>
        <?=$arResult["FORM_FOOTER"]?>
    </div>
</div>

<script>
    $('.js-phone').inputmask("<?= $telMask ?>");
</script>
