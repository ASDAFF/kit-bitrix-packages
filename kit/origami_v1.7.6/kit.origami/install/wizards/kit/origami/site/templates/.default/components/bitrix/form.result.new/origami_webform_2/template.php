<?

use Bitrix\Main\Page\Asset;

if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

Asset::getInstance()->addCss(SITE_DIR . "local/templates/.default/components/bitrix/form.result.new/origami_webform_2/style.css");

$telMask = \Kit\Origami\Config\Option::get('MASK',SITE_ID);
?>

<div class="puzzle_block-form-promotion">
    <div class="feedback_block__text">
        <?if($arResult["isFormTitle"] == "Y"):?>
            <div class="feedback_block__title fonts__middle_title"><?=$arResult["FORM_TITLE"]?></div>
        <?endif?>
        <?if($arResult["isFormDescription"] == "Y"):?>
            <div class="feedback_block__comment fonts__small_text"><?=$arResult["FORM_DESCRIPTION"]?></div>
        <?endif?>
    </div>

    <?=$arResult["FORM_HEADER"]?>
		<div class="form_blocks">
            <?if($_GET['formresult'] == 'addok')
            {
                ?>
				<div class="form_blocks__ok"><?=GetMessage("OK_MESSAGE")?></div>
                <?
            }
            ?>
	        <div class="row">
		        <div class="col-md-6 form-block-left">
			        <div class="form_block_title">
				        <?=GetMessage('KIT_FORM_TITLE_1');?>
			        </div>
	                <?
	                foreach($arResult["QUESTIONS"] as $FIELD_SID => $arQuestion)
	                {
	                    $fieldType = $arQuestion['STRUCTURE'][0]['FIELD_TYPE'];
	                    if($fieldType == 'textarea')
	                    {
	                        continue;
	                    }
	                    if($fieldType == 'hidden')
	                    {
	                        echo $arQuestion["HTML_CODE"];
	                    }
	                    else
	                    {
	                        ?>
		                    <div class="form_row">
						        <label class="puzzle_block__form_label_promotion" for="<?=$arQuestion['STRUCTURE'][0]['ID']?>">
							        <?=$arQuestion["CAPTION"]?> <?=($arQuestion['REQUIRED'] == 'Y')?'<span class="required">*</span>':''?>
						        </label>
							    <input id="<?=$arQuestion['STRUCTURE'][0]['ID']?>"
                                       type="<?= $arQuestion['STRUCTURE'][0]['FIELD_TYPE'] ?>"
							           class="feedback_block__form_input <?= ($arQuestion['STRUCTURE'][0]['FIELD_TYPE'] == 'tel' ? "js-phone" : "") ?>"
                                       name="form_<?= ($arQuestion['STRUCTURE'][0]['FIELD_TYPE'] == 'tel') ? 'text' : $arQuestion['STRUCTURE'][0]['FIELD_TYPE'] ?>_<?= $arQuestion['STRUCTURE'][0]['ID'] ?>"
                                       <?if ($arQuestion['STRUCTURE'][0]['FIELD_TYPE'] == 'tel'): ?>
                                           <?= ($arQuestion['STRUCTURE'][0]['MASK']) ? 'placeholder="' . $arQuestion['STRUCTURE'][0]['MASK'] . '"' : "" ?>
                                           <?= ($arQuestion['STRUCTURE'][0]['PATTERN']) ? 'pattern="' . $arQuestion['STRUCTURE'][0]['PATTERN'] . '"' : '' ?>
                                       <?endif; ?>
                                >
		                    </div>
	                        <?
	                    }
	                }
	                ?>
		        </div>
		        <div class="col-md-6 form-block-right">
	                <?
	                foreach($arResult["QUESTIONS"] as $FIELD_SID => $arQuestion)
	                {
	                    $fieldType = $arQuestion['STRUCTURE'][0]['FIELD_TYPE'];
	                    if($fieldType != 'textarea')
	                    {
	                        continue;
	                    }
	                    if($fieldType == 'hidden')
	                    {
	                        echo $arQuestion["HTML_CODE"];
	                    }
	                    else
	                    {
	                        ?>
					        <label class="puzzle_block__form_label_promotion form_block_title" for="">
	                            <?=$arQuestion["CAPTION"]?> <?=($arQuestion['REQUIRED'] == 'Y')?'<span class="required">*</span>':'('.GetMessage('KIT_NOT_REQUIRED').')'?>
					        </label>
					        <textarea class="feedback_block__form_input"
					           name="form_<?=$fieldType?>_<?=$arQuestion['STRUCTURE'][0]['ID']?>" <?=($arQuestion['REQUIRED'] == 'Y')?'':''?>></textarea>
	                        <?
	                    }
	                }
	                ?>
		        </div>
	            </div>
				<?
                if($arResult["isUseCaptcha"] == "Y")
                {
                    ?>
					<div class="feedback_block__captcha">
                        <div class="captcha_form-2">
                            <p class="puzzle_block__form_label_promotion captcha_title">
                                <?=GetMessage('CAPTCHA_TITLE')?>
                            </p>
                            <div class="form_row">
                                <div class="feedback_block__captcha_input">
                                    <input type="text" name="captcha_word" size="30" maxlength="50" value="" required class="feedback_block__form_input "/>
                                </div>
                                <div class="feedback_block__captcha_img">
                                    <input type="hidden" name="captcha_sid" value="<?=htmlspecialcharsbx($arResult["CAPTCHACode"]);?>" />
                                    <img
                                        src="/bitrix/tools/captcha.php?captcha_sid=<?=htmlspecialcharsbx($arResult["CAPTCHACode"]);?>" width="180" height="40" />
                                    <div class="captcha-refresh"  onclick="reloadCaptcha(this,'<?= SITE_DIR ?>');return false;">
                                        <svg class="icon_refresh" width="16" height="14">
                                            <use
                                                xlink:href="/local/templates/kit_origami/assets/img/sprite.svg#icon_refresh"></use>
                                        </svg>
                                    </div>
                                </div>
                            </div>
                        </div>
					</div>
                    <?
                }
				?>
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
							<p class="feedback_block__compliance_title
							fonts__middle_comment">
	                        <?=GetMessage('FORM_CONFIDENTIAL_1')?>
								<a class="feedback_block__compliance_link
						fonts__middle_comment"
								   href="<?=\Kit\Origami\Helper\Config::get('CONFIDENTIAL_PAGE')?>"><?=GetMessage('FORM_CONFIDENTIAL_2')?></a>
	                    </p>
						</label>

					</div>
				</div>
				<div class="feedback_block__input_wrapper">
					<input
							type="button"
							class="feedback_block__input main_btn button_feedback sweep-to-right"
							name="web_form_submit"
							value="<?=GetMessage("FORM_SUBMIT")?>"
							onclick="sendForm('<?=$arResult['arForm']['SID']?>','<?=\Kit\Origami\Helper\Config::get('COLOR_BASE')?>')"
					>
					<input  style="display: none" type="reset" class="feedback_block__input main_btn
					button_feedback sweep-to-right" name="web_form_submit"
					       value="<?=GetMessage("FORM_RESET")?>">
					<input type="submit" style="display:none"
					       name="web_form_submit" id="submit">
				</div>
			</div>
        </div>
    <?=$arResult["FORM_FOOTER"]?>
	</div>

<script>
    $(document).ready(function() {
        $('.js-phone').inputmask("<?= $telMask ?>");
    });
</script>
