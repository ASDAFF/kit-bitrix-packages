<?
use Bitrix\Main\Localization\Loc;
use Sotbit\Origami\Helper\Config;
use Bitrix\Main\Loader;
use Bitrix\Main\Page\Asset;

if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

$APPLICATION->ShowAjaxHead();
Asset::getInstance()->addCss(SITE_DIR . "local/templates/.default/components/bitrix/form.result.new/origami_findbetter/style.css");

Loader::includeModule('sotbit.origami');
?>
	<div class="sotbit_order_phone">
		<?
        if ($arResult["isFormTitle"])
        {
		?>
			<div class="sotbit_order_phone__title"><?=$arResult["FORM_TITLE"]?></div>
	     <?}?>


        <?if ($arResult["isFormErrors"] == "Y"):?><?=$arResult["FORM_ERRORS_TEXT"];?><?endif;?>
		<div class="sotbit_order_success_show">
			<?=$arResult["FORM_NOTE"]?></div>
        <?if (empty($arResult["FORM_NOTE"])) {?>
        <?=$arResult["FORM_HEADER"]?>
	<?
foreach ($arResult["QUESTIONS"] as $FIELD_SID => $arQuestion)
{
	?>
	<div class="sotbit_order_phone__block">
        <?if ($arQuestion['CAPTION'] !== Loc::getMessage('OK_ID_PRODUCT')) {?>
		    <p class="sotbit_order_phone__block_title">
			    <?=$arQuestion['CAPTION']?>
			    <?=($arQuestion['REQUIRED'] == 'Y')?'*':''?></p>
        <?}?>
		<input
			type="<?=$arQuestion['STRUCTURE'][0]['FIELD_TYPE']?>"
			name="form_<?=($arQuestion['STRUCTURE'][0]['FIELD_TYPE'] ==
				'tel')?'text':$arQuestion['STRUCTURE'][0]['FIELD_TYPE']?>_<?=$arQuestion['STRUCTURE'][0]['ID']?>"
            value="<?=$arQuestion['STRUCTURE'][0]['VALUE']?>"
			<?=($arQuestion['REQUIRED'] == 'Y')?'':''?>
			<?=($arQuestion['STRUCTURE'][0]['MASK'])?'placeholder="'.$arQuestion['STRUCTURE'][0]['MASK'].'"':''?>
			<?=($arQuestion['STRUCTURE'][0]['PATTERN'])?'pattern="'.$arQuestion['STRUCTURE'][0]['PATTERN'].'"':''?>
		>
	</div>
	<?
}
            if($arResult["isUseCaptcha"] == "Y")
            {
                ?>
		        <div class="feedback_block__captcha">
			        <p class="feedback_block__captcha_text">
                        <?=GetMessage('CAPTCHA_TITLE')?>
			        </p>
			        <div class="feedback_block__captcha_input">
				        <input type="text" name="captcha_word" size="30" maxlength="50" value="" required />
			        </div>
			        <div class="feedback_block__captcha_img">
				        <input type="hidden" name="captcha_sid" value="<?=htmlspecialcharsbx($arResult["CAPTCHACode"]);?>" />
                        <img
                            src="/bitrix/tools/captcha.php?captcha_sid=<?=htmlspecialcharsbx($arResult["CAPTCHACode"]);?>" width="180" height="40" />
                        <div class="captcha-refresh"  onclick="reloadCaptcha(this,'<?= SITE_DIR ?>');return false;">
                            <svg class="icon_refresh" width="16" height="14">
                                <use
                                    xlink:href="/local/templates/sotbit_origami/assets/img/sprite.svg#icon_refresh"></use>
                            </svg>
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
                            <?=Loc::getMessage('OK_CONFIDENTIAL')?>                       </span>
			        </label>
			        <a class="feedback_block__compliance_link
			        fonts__middle_comment" href="<?=Config::get('CONFIDENTIAL_PAGE',$arParams['SITE_ID'])?>"><?=Loc::getMessage
				        ('OK_CONFIDENTIAL2')?>  </a>
		        </div>
	        </div>

	        <input type="button"  name="web_form_submit" value="<?=htmlspecialcharsbx(strlen(trim
            ($arResult["arForm"]["BUTTON"])) <= 0 ? GetMessage("FORM_ADD") : $arResult["arForm"]["BUTTON"]);?>" onclick="sendForm
		                           ('<?=$arResult['arForm']['SID']?>',
		                           '<?=Config::get('COLOR_BASE',$arParams['SITE_ID'])?>')">
	<input type="submit" name="web_form_submit"
	       style="display:none;" value="<?=htmlspecialcharsbx(strlen(trim
	       ($arResult["arForm"]["BUTTON"])) <= 0 ? GetMessage("FORM_ADD") : $arResult["arForm"]["BUTTON"]);?>" />
<?=$arResult["FORM_FOOTER"]?>
<?}?>
</div>
<script>
    function sendForm(sid, color)
    {console.log(1);
        if ($("form[name='" + sid + "'] input[name='personal']").is(':checked'))
        {
            console.log(1);
            $("form[name='" + sid + "'] input[type='submit']").trigger('click');
        }
        else
        {
            $('.feedback_block__compliance svg path').css({'stroke': color, 'stroke-dashoffset': 0});
        }
    }
</script>
