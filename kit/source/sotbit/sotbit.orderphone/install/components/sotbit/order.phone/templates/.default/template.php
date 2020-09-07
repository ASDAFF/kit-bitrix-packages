<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
if(!CModule::IncludeModule("sotbit.orderphone") || !CSotbitOrderphone::GetDemo()) return;
global $APPLICATION;
$APPLICATION->AddHeadScript($templateFolder."/js/jquery.maskedinput.min.js");
?>
<div class="sotbit_order_phone">
    <form action="" class="sotbit_order_phone_form">
        <div class="sotbit_order_success"><?=$arParams["SUCCESS_TEXT"]?></div>
        <?foreach($arParams as $param=>$val):
        if(strpos($param, "~")!==false || is_array($val)) continue;
        ?>
        <input type="hidden" name="<?=$param?>" value="<?=$val?>" />
        <?endforeach?>
        <p><?=$arParams["TEXT_TOP"]?></p>
        <input type="text" name="order_phone" value="" />
        <input type="submit" name="submit" value="<?=$arParams["SUBMIT_VALUE"]?>" />
        <p><?=$arParams["TEXT_BOTTOM"]?></p>
        <?if(isset($arResult["ORDER_PROPS"])):?>
            <h5><?=GetMessage("SOP_DOP_PROPS_TITLE")?></h5>
            <table cellpadding="0" cellspacing="0" border="0">
            <?foreach($arResult["ORDER_PROPS"] as $idProp=>$arProps):?>
            <tr>
                <td><?=$arProps["NAME"]?>: </td>
                <td><input type="text" value="" name="order_props[<?=$idProp?>]" /></td>
            </tr>
            <?endforeach?>
            </table>
        <?endif;?>
    </form>
</div>