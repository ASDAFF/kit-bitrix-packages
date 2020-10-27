<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

include($_SERVER["DOCUMENT_ROOT"].$templateFolder."/props_format.php");

foreach($arResult["ORDER_PROP"] as $type => $props)
{
    if($type == "USER_PROPS_Y" || $type == "USER_PROPS_N")
        foreach($props as $property)
        {
            if(in_array($property['CODE'], array("CONTACT_PERSON", "EMAIL", "PHONE", "FAX")))
            {
                $arForSecondColumn[] = $property['CODE'];
            }
            else
            {
                $arForMainColumn[] = $property['CODE'];
            }
        }
}
if(sizeof($arForSecondColumn) == 2) // for physical person
{
	$arForMainColumn = array_merge($arForMainColumn, $arForSecondColumn);
	$arForSecondColumn = array();
}
?>

<div class="col-md-6 info_order_item__margin">
    <div class="info_order_item">
        <div class="main_order_block__top_line without_bg">
            <span class="main_order_block__title fonts__main_text">
                <?=($arForSecondColumn) ? GetMessage("SOA_TEMPL_BILLING_INFO") : GetMessage("SOA_TEMPL_BUYER_INFO")?>
                <?
                if(array_key_exists('ERROR', $arResult) && is_array($arResult['ERROR']) && !empty($arResult['ERROR']))
                {
                    $bHideProps = false;
                }

                // if($bHideProps && $_POST["showProps"] != "Y"):
                ?>
                    <!-- <a href="#" class="slide" onclick="fGetBuyerProps(this); return false;">
                        <?=GetMessage('SOA_TEMPL_BUYER_SHOW');?>
                    </a> -->
                <?
                // elseif(($bHideProps && $_POST["showProps"] == "Y")):
                ?>
                    <!-- <a href="#" class="slide" onclick="fGetBuyerProps(this); return false;">
                        <?=GetMessage('SOA_TEMPL_BUYER_HIDE');?>
                    </a> -->
                <?
                // endif;
                ?>
                <input type="hidden" name="showProps" id="showProps" value="Y<?/*=($_POST["showProps"] == 'Y' ? 'Y' : 'N')*/?>">
            </span>
        </div>
        <div class="pay_info_block__input_block info_order_item_content">
            <?
            $bHideProps = true;
            if(is_array($arResult["ORDER_PROP"]["USER_PROFILES"]) && !empty($arResult["ORDER_PROP"]["USER_PROFILES"])):
                if($arParams["ALLOW_NEW_PROFILE"] == "Y"):
                ?>
                    <div class="info_order_item_content__input_item">
                        <span class="pay_info_block__input_title fonts__main_comment"><?=GetMessage("SOA_TEMPL_PROP_CHOOSE")?></span>
                        <div class="select_block">
                            <div class="select-side"></div>
                            <select name="PROFILE_ID" id="ID_PROFILE_ID" onChange="SetContact(this.value)" style="width: 100%;">
                                <option value="0"><?=GetMessage("SOA_TEMPL_PROP_NEW_PROFILE")?></option>
                                <?
                                foreach($arResult["ORDER_PROP"]["USER_PROFILES"] as $arUserProfiles)
                                {
                                    ?>
                                    <option value="<?= $arUserProfiles["ID"] ?>"<?if ($arUserProfiles["CHECKED"]=="Y") echo " selected";?>><?=$arUserProfiles["NAME"]?></option>
                                    <?
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                <?
                else:
                ?>
                    <div class="info_order_item_content__input_item">
                        <div class="select_block">
                            <?=GetMessage("SOA_TEMPL_EXISTING_PROFILE")?>
                            <?
                            if(count($arResult["ORDER_PROP"]["USER_PROFILES"]) == 1)
                            {
                                foreach($arResult["ORDER_PROP"]["USER_PROFILES"] as $arUserProfiles)
                                {
                                    echo "<strong>".$arUserProfiles["NAME"]."</strong>";
                                    ?>
                                    <input type="hidden" name="PROFILE_ID" id="ID_PROFILE_ID" value="<?=$arUserProfiles["ID"]?>">
                                    <?
                                }
                            }
                            else
                            {
                                ?>
                                <select name="PROFILE_ID" id="ID_PROFILE_ID" onChange="SetContact(this.value)">
                                    <?
                                    foreach($arResult["ORDER_PROP"]["USER_PROFILES"] as $arUserProfiles)
                                    {
                                        ?>
                                        <option value="<?= $arUserProfiles["ID"] ?>"<?if ($arUserProfiles["CHECKED"]=="Y") echo " selected";?>><?=$arUserProfiles["NAME"]?></option>
                                        <?
                                    }
                                    ?>
                                </select>
                                <?
                            }
                            ?>
                        </div>
                    </div>
                <?
                endif;
            else:
                $bHideProps = false;
            endif;
            ?>
            <div class="sale_order_props" <?/*=($bHideProps && $_POST["showProps"] != "Y")?"style='display:none;'":''*/?>>
                <?
                PrintPropsForm($arResult["ORDER_PROP"]["USER_PROPS_N"], $arParams["TEMPLATE_LOCATION"], $arForMainColumn);
                PrintPropsForm($arResult["ORDER_PROP"]["USER_PROPS_Y"], $arParams["TEMPLATE_LOCATION"], $arForMainColumn);
                ?>
            </div>
        </div>
    </div>
</div>

<div class="col-md-6 info_order_item__margin">
    <div class="info_order_item">
        <div class="main_order_block__top_line without_bg">
            <span class="main_order_block__title fonts__main_text">
                <?=($arForSecondColumn) ? GetMessage("SOA_TEMPL_BUYER_INFO") : GetMessage("SOA_TEMPL_SUM_COMMENTS")?>
            </span>
        </div>
        <div class="pay_info_block__input_block info_order_item_content">
            <div class="sale_order_props">
                <?
                PrintPropsForm($arResult["ORDER_PROP"]["USER_PROPS_N"], $arParams["TEMPLATE_LOCATION"], $arForSecondColumn);
                PrintPropsForm($arResult["ORDER_PROP"]["USER_PROPS_Y"], $arParams["TEMPLATE_LOCATION"], $arForSecondColumn);
                ?>
            </div>
            <div class="info_order_item_content__comment">
                <?if($arForSecondColumn):?>
                    <span class="pay_info_block__input_title_comment fonts__main_comment"><?=GetMessage("SOA_TEMPL_SUM_COMMENTS")?></span>
                <?endif;?>
                <textarea name="ORDER_DESCRIPTION" id="ORDER_DESCRIPTION" class="fonts__middle_comment" placeholder="<?=GetMessage("SOA_TEMPL_SUM_COMMENTS")?>" style="min-height: 150px;"><?=$arResult["USER_VALS"]["ORDER_DESCRIPTION"]?></textarea>
                <input type="hidden">
            </div>
        </div>
    </div>
</div>

<script>
	function fGetBuyerProps(el)
	{
		var show = '<?=GetMessageJS('SOA_TEMPL_BUYER_SHOW')?>';
		var hide = '<?=GetMessageJS('SOA_TEMPL_BUYER_HIDE')?>';
		var status = BX('sale_order_props').style.display;
		var startVal = 0;
		var startHeight = 0;
		var endVal = 0;
		var endHeight = 0;
		var pFormCont = BX('sale_order_props');
		pFormCont.style.display = "block";
		pFormCont.style.overflow = "hidden";
		pFormCont.style.height = 0;
		var display = "";

		if(status == 'none')
		{
			el.text = '<?=GetMessageJS('SOA_TEMPL_BUYER_HIDE');?>';

			startVal = 0;
			startHeight = 0;
			endVal = 100;
			endHeight = pFormCont.scrollHeight;
			display = 'block';
			BX('showProps').value = "Y";
			el.innerHTML = hide;
		}
		else
		{
			el.text = '<?=GetMessageJS('SOA_TEMPL_BUYER_SHOW');?>';

			startVal = 100;
			startHeight = pFormCont.scrollHeight;
			endVal = 0;
			endHeight = 0;
			display = 'none';
			BX('showProps').value = "N";
			pFormCont.style.height = startHeight+'px';
			el.innerHTML = show;
		}

		(new BX.easing({
			duration : 700,
			start : { opacity : startVal, height : startHeight},
			finish : { opacity: endVal, height : endHeight},
			transition : BX.easing.makeEaseOut(BX.easing.transitions.quart),
			step : function(state){
				pFormCont.style.height = state.height + "px";
				pFormCont.style.opacity = state.opacity / 100;
			},
			complete : function(){
					BX('sale_order_props').style.display = display;
					BX('sale_order_props').style.height = '';

					pFormCont.style.overflow = "visible";
			}
		})).animate();
	}
</script>

<?if(!CSaleLocation::isLocationProEnabled()):?>
	<div style="display:none;">
		<?$APPLICATION->IncludeComponent(
			"bitrix:sale.ajax.locations",
			$arParams["TEMPLATE_LOCATION"],
			array(
				"AJAX_CALL" => "N",
				"COUNTRY_INPUT_NAME" => "COUNTRY_tmp",
				"REGION_INPUT_NAME" => "REGION_tmp",
				"CITY_INPUT_NAME" => "tmp",
				"CITY_OUT_LOCATION" => "Y",
				"LOCATION_VALUE" => "",
				"ONCITYCHANGE" => "submitForm()",
			),
			null,
			array('HIDE_ICONS' => 'Y')
		);?>
	</div>
<?endif?>