<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
use Bitrix\Main\Localization\Loc;
use Sotbit\Origami\Helper\Config;
if(!CModule::IncludeModule("kit.orderphone") || !CSotbitOrderphone::GetDemo()) return;
\Bitrix\Main\Page\Asset::getInstance()->addJs($templateFolder."/js/jquery.maskedinput.min.js");
\Bitrix\Main\Page\Asset::getInstance()->addcss($templateFolder."/style.css");
?>
<div class="kit_order_phone">
	<div class="kit_order_phone__title"><?=Loc::getMessage('OK_TITLE')?></div>
    <form action="" class="kit_order_phone_form">
        <div class="kit_order_success"><?=GetMessage('OK_SUCCESS')?></div>
        <div class="kit_order_error"></div>
        <?foreach($arParams as $param=>$val):
        if(strpos($param, "~")!==false || is_array($val)) continue;
        ?>
        <input type="hidden" name="<?=$param?>" value="<?=$val?>" />
        <?endforeach?>
	    <div class="kit_order_phone__block">
		    <p class="kit_order_phone__block_title"><?=Loc::getMessage('OK_NAME')?>*</p>
		    <input type="text" name="order_name" value="<?=$arResult['USER']['NAME']?>" />
	    </div>
	    <div class="kit_order_phone__block">
		    <p class="kit_order_phone__block_title"><?=Loc::getMessage('OK_PHONE')?>*</p>
		    <input type="text" name="order_phone" value="<?=$arResult['USER']['PHONE']?>" />
	    </div>
	    <div class="kit_order_phone__block">
		    <p class="kit_order_phone__block_title"><?=Loc::getMessage('OK_EMAIL')?></p>
		    <input type="text" name="order_email" value="<?=$arResult['USER']['EMAIL']?>" />
	    </div>
	    <div class="kit_order_phone__block">
		    <p class="kit_order_phone__block_title"><?=Loc::getMessage('OK_COMMENT')?></p>
		    <textarea name="order_comment"></textarea>
	    </div>
        <?if($arParams['USE_CAPTCHA'] == 'Y'):?>
		    <div class="feedback_block__captcha">
			    <p class="feedback_block__captcha_text">
                    <?=GetMessage('CAPTCHA_TITLE')?>
			    </p>
			    <div class="feedback_block__captcha_input">
				    <input type="text" name="captcha_word" size="30" maxlength="50" value="" required />
			    </div>
			    <div class="feedback_block__captcha_img">
				    <input type="hidden" name="captcha_sid" value="<?=htmlspecialcharsbx($arResult["CAPTCHACode"]);?>" /><img src="/bitrix/tools/captcha.php?captcha_sid=<?=htmlspecialcharsbx($arResult["CAPTCHACode"]);?>" width="180" height="40" />
			    </div>
		    </div>
        <?endif;?>
	    <div class="confidential-field">
		    <input type="checkbox" id="UF_CONFIDENTIAL" name="UF_CONFIDENTIAL" class="checkbox__input" checked="checked">
		    <label for="UF_CONFIDENTIAL" class="checkbox__label fonts__middle_comment">
				<?=Loc::getMessage('OK_CONFIDENTIAL',['#CONFIDENTIAL_LINK#' => Config::get('CONFIDENTIAL_PAGE',
					$arParams['SITE_ID'])])?>
		    </label>
	    </div>
        <input type="submit" name="submit" class="main_btn" value="<?=Loc::getMessage('OK_SEND')?>" />
    </form>
</div>
<script src="<?=$this->__component->__template->__folder?>/js/jquery.maskedinput.min.js"></script>
<script>
	$(function()
	{
		$(".kit_order_phone").on("submit", "form", submitOrderPhone);

		maska = $(".kit_order_phone form input[name='TEL_MASK']").eq(0).val();
		maska = $.trim(maska);
		if(maska!="")$(".kit_order_phone form input[name='order_phone']").mask(maska, {placeholder:"_"});
		function submitOrderPhone(e)
		{
			e.preventDefault();
			var name = $(this).find("input[name='order_name']").val();
			var email = $(this).find("input[name='order_email']").val();
			v = $(this).find("input[name='TEL_MASK']").val();
			v = $.trim(v);
			req = strReplace(v);
			var _this = $(this);
			v = $(this).find("input[name='order_phone']").val();

			$(this).find('input').removeClass('error');
			$(this).find('.checkbox__label').removeClass('error');
			$(this).find(".kit_order_error").hide();
			$(this).find(".kit_order_success").hide();

			var error = false;
			if(name.length <= 0)
			{
				$(this).find("input[name='order_name']").addClass('error');
				error = true;
			}
			if(v.search(req)==-1 || v.length <= 0)
			{
				$(this).find("input[name='order_phone']").addClass('error');
				error = true;
			}
			if($(this).find("input[name='UF_CONFIDENTIAL']:checked").length == 0)
			{
				$(this).find('.checkbox__label').addClass('error');
				error = true;
			}

			if(email)
			{
				var pattern = /^([a-z0-9_\.-])+@[a-z0-9-]+\.([a-z]{2,4}\.)?[a-z]{2,4}$/i;
				if (!pattern.test(email))
				{
					$(this).find("input[name='order_email']").addClass('error');
					error = true;
				}
			}

			if(!error)
			{
				$(this).find("input[type='text']").removeClass("red");
				ser = $(this).serialize();
				$.post("/bitrix/components/kit/order.phone/ajax.php", ser, function(data)
				{
					data = $.trim(data);
					if(data.indexOf("SUCCESS")>=0)
					{
						_this.find(".kit_order_success").show();
						_this.find(".kit_order_error").hide();
						id = data.replace("SUCCESS", "");
						localHref = $('input[name="LOCAL_REDIRECT"]').val();
						orderID = $('input[name="ORDER_ID"]').val();
						if(typeof(localHref) != "undefined" && localHref!="")
						{
							location.href = localHref+"?"+orderID+"="+id;
						}
					}
					else
					{
						_this.find(".kit_order_success").hide();
						_this.find(".kit_order_error").show().html(data);
					}
				})
			}
		}
		function strReplace(str)
		{
			str = str.replace("+", "\\+");
			str = str.replace("(", "\\(");
			str = str.replace(")", "\\)");
			str = str.replace(/[0-9]/g, "[0-9]{1}");
			return new RegExp(str, 'g');;

		}
	});
</script>
