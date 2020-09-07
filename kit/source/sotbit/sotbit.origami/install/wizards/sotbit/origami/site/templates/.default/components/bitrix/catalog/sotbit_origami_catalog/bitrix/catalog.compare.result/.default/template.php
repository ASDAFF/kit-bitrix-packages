<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */

$isAjax = false;
if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
	$isAjax = (
		(isset($_POST['ajax_action']) && $_POST['ajax_action'] == 'Y')
		|| (isset($_POST['compare_result_reload']) && $_POST['compare_result_reload'] == 'Y')
	);
}

$templateData = array(
	'TEMPLATE_THEME' => $this->GetFolder().'/themes/'.$arParams['TEMPLATE_THEME'].'/style.css',
	'TEMPLATE_CLASS' => 'bx_'.$arParams['TEMPLATE_THEME']
);

?><div class="bx_compare <? echo $templateData['TEMPLATE_CLASS']; ?>" id="bx_catalog_compare_block"><?
if ($isAjax)
{
	$APPLICATION->RestartBuffer();
}
?>



<div class="table_compare_container table_compare_container_im">
    <div class="table_compare" id="table_compare">
        <table id="table_width" class="data-table">
            <tr class="table_compare_row_product">
                <?
                foreach($arResult["ITEMS"] as $arElement)
                {
                    ?>
                    <td valign="table_compare_row_product_item">
                        <a class="table_compare_product_close" onclick="CatalogCompareObj.delete('<?=CUtil::JSEscape($arElement['~DELETE_URL'])?>');" href="javascript:void(0)"></a>

                        <? if (!empty($arElement["FIELDS"]["DETAIL_PICTURE"]) && is_array($arElement["FIELDS"]["DETAIL_PICTURE"])):?>
                            <a class="table_compare_product_img" onclick="" href="<?=$arElement["DETAIL_PAGE_URL"]?>"><img
                                        border="0" src="<?=$arElement["FIELDS"]["DETAIL_PICTURE"]["SRC"]?>"
                                        alt="<?=$arElement["FIELDS"]["DETAIL_PICTURE"]["ALT"]?>" title="<?=$arElement["FIELDS"]["DETAIL_PICTURE"]["TITLE"]?>"
                                /></a>
                        <?endif;?>
                        <a class="table_compare_product_name fonts__main_comment" onclick="" href="<?=$arElement["DETAIL_PAGE_URL"]?>"><?=$arElement["NAME"]?></a>
                        <?
                        if (isset($arElement['MIN_PRICE']) && is_array($arElement['MIN_PRICE']))
                        {
                            ?><div class='fonts__small_title table_compare_product_price'><? echo $arElement['MIN_PRICE']['PRINT_DISCOUNT_VALUE']; ?></div><?
                        }
                        ?>
                        <?if($arElement["CAN_BUY"]):?>
                            <noindex><a class="main_btn" href="<?=$arElement["BUY_URL"]?>" rel="nofollow" onclick=""><?=GetMessage("CATALOG_COMPARE_BUY"); ?></a></noindex>
                        <?elseif(!empty($arResult["PRICES"]) || is_array($arElement["PRICE_MATRIX"])):?>
                            <?=GetMessage("CATALOG_NOT_AVAILABLE")?>
                        <?endif;?>


                    </td>
                    <?
                }
                unset($arElement);
                ?>
            </tr>


            <?

            if (!empty($arResult["SHOW_OFFER_FIELDS"]))
            {
                foreach ($arResult["SHOW_OFFER_FIELDS"] as $code => $arProp)
                {
                    $showRow = true;
                    if ($arResult['DIFFERENT'])
                    {
                        $arCompare = array();
                        foreach($arResult["ITEMS"] as $arElement)
                        {
                            $Value = $arElement["OFFER_FIELDS"][$code];
                            if(is_array($Value))
                            {
                                sort($Value);
                                $Value = implode(" / ", $Value);
                            }
                            $arCompare[] = $Value;
                        }
                        unset($arElement);
                        $showRow = (count(array_unique($arCompare)) > 1);
                    }
                    if ($showRow)
                    {
                        ?>
                        <tr>
                            <td><?=GetMessage("IBLOCK_OFFER_FIELD_".$code)?></td>
                            <?foreach($arResult["ITEMS"] as $arElement)
                            {
                                ?><td><?
                                switch ($code)
                                {
                                    case 'PREVIEW_PICTURE':
                                    case 'DETAIL_PICTURE':
                                        if (!empty($arElement["OFFER_FIELDS"][$code]) && is_array($arElement["OFFER_FIELDS"][$code]))
                                        {
                                            ?><img border="0" src="<?= $arElement["OFFER_FIELDS"][$code]["SRC"] ?>"
                                                   width="auto" height="150"
                                                   alt="<?= $arElement["OFFER_FIELDS"][$code]["ALT"] ?>" title="<?= $arElement["OFFER_FIELDS"][$code]["TITLE"] ?>"
                                            /><?
                                        }
                                        break;
                                    default:
                                        ?><?=(is_array($arElement["OFFER_FIELDS"][$code])? implode("/ ", $arElement["OFFER_FIELDS"][$code]): $arElement["OFFER_FIELDS"][$code])?><?
                                        break;
                                }
                                ?></td><?
                            }
                            unset($arElement);
                            ?>
                        </tr>
                        <?
                    }
                }
            }
            ?>


        </table>
    </div>
</div>



<div class="container_scrooll one <?//if( count($arResult["ITEMS"]) <=3) { echo "hidden";}?>" id="axis">
    <button id ="leftArrow" class="leftArrow"></button>
    <div class="slider_compare_wrap">
        <div id="slider_compare" class="scrooll_line_container">
            <div id="ball" class="scrooll_line object van move-right">
                <div class="container_scrooll_cursor"></div>
            </div>
        </div>
    </div>
    <button id ="rightArrow" class="rightArrow"></button>
</div>

    <div class="bx_sort_container">
        <a class="fonts__middle_text sortbutton<? echo (!$arResult["DIFFERENT"] ? ' current' : ''); ?>" href="<? echo $arResult['COMPARE_URL_TEMPLATE'].'DIFFERENT=N'; ?>" rel="nofollow"><?=GetMessage("CATALOG_ALL_CHARACTERISTICS")?></a>
        <a class="fonts__middle_text sortbutton<? echo ($arResult["DIFFERENT"] ? ' current' : ''); ?>" href="<? echo $arResult['COMPARE_URL_TEMPLATE'].'DIFFERENT=Y'; ?>" rel="nofollow"><?=GetMessage("CATALOG_ONLY_DIFFERENT")?></a>
    </div>


   <div class="block_property_add">
       <?
       if (!empty($arResult["ALL_PROPERTIES"])){
           foreach($arResult["ALL_PROPERTIES"] as $propCode => $arProperty){?>
               <div class="block_property_add__container <?=($arProperty["IS_DELETED"] != "N" ? 'visible' : '');?>">
                   <span onclick="CatalogCompareObj.delete('<?=CUtil::JSEscape($arProperty["ACTION_LINK"])?>')">+<?=$arProperty["NAME"]?></span>
               </div>
           <?}
       }
       if (!empty($arResult["ALL_OFFER_PROPERTIES"])){
           foreach($arResult["ALL_OFFER_PROPERTIES"] as $propCode => $arProperty){?>
               <div class="block_property_add__container <?=($arProperty["IS_DELETED"] != "N" ? 'visible' : '');?>">
                   <span onclick="CatalogCompareObj.delete('<?=CUtil::JSEscape($arProperty["ACTION_LINK"])?>')">+<?=$arProperty["NAME"]?></span>
               </div>
           <?}
       }
       ?>

   </div>


<div class="table_compare_container table_compare_container_tb">
    <div class="data-table-name">
        <table class="data-table">
            <?
            if (!empty($arResult["SHOW_PROPERTIES"]))
            {
                foreach ($arResult["SHOW_PROPERTIES"] as $code => $arProperty)
                {
                    $showRow = true;
                    if ($arResult['DIFFERENT'])
                    {
                        $arCompare = array();
                        foreach($arResult["ITEMS"] as $arElement)
                        {
                            $arPropertyValue = $arElement["DISPLAY_PROPERTIES"][$code]["VALUE"];
                            if (is_array($arPropertyValue))
                            {
                                sort($arPropertyValue);
                                $arPropertyValue = implode(" / ", $arPropertyValue);
                            }
                            $arCompare[] = $arPropertyValue;
                        }
                        unset($arElement);
                        $showRow = (count(array_unique($arCompare)) > 1);
                    }

                    if ($showRow)
                    {
                        ?>
                        <tr class="prop_">
                            <td><?=$arProperty["NAME"]?>
                                <a class="table_compare_product_close" onclick="CatalogCompareObj.delete('<?=CUtil::JSEscape($arProperty["ACTION_LINK"])?>')"></a> </td>
                            <?foreach($arResult["ITEMS"] as $arElement)
                            {
                                ?>
                                <td>
                                    <?=(is_array($arElement["DISPLAY_PROPERTIES"][$code]["DISPLAY_VALUE"])? implode("/ ", $arElement["DISPLAY_PROPERTIES"][$code]["DISPLAY_VALUE"]): $arElement["DISPLAY_PROPERTIES"][$code]["DISPLAY_VALUE"])?>
                                </td>
                                <?
                            }
                            unset($arElement);
                            ?>
                        </tr>
                        <?
                    }
                }
            }

            if (!empty($arResult["SHOW_OFFER_PROPERTIES"]))
            {
                foreach($arResult["SHOW_OFFER_PROPERTIES"] as $code=>$arProperty)
                {
                    $showRow = true;
                    if ($arResult['DIFFERENT'])
                    {
                        $arCompare = array();
                        foreach($arResult["ITEMS"] as $arElement)
                        {
                            $arPropertyValue = $arElement["OFFER_DISPLAY_PROPERTIES"][$code]["VALUE"];
                            if(is_array($arPropertyValue))
                            {
                                sort($arPropertyValue);
                                $arPropertyValue = implode(" / ", $arPropertyValue);
                            }
                            $arCompare[] = $arPropertyValue;
                        }
                        unset($arElement);
                        $showRow = (count(array_unique($arCompare)) > 1);
                    }
                    if ($showRow)
                    {
                        ?>
                        <tr>
                            <td><?=$arProperty["NAME"]?>
                                <a class="table_compare_product_close" onclick="CatalogCompareObj.delete('<?=CUtil::JSEscape($arProperty["ACTION_LINK"])?>')"></a>
                            </td>
                            <?foreach($arResult["ITEMS"] as $arElement)
                            {
                                ?>
                                <td>
                                    <?=(is_array($arElement["OFFER_DISPLAY_PROPERTIES"][$code]["DISPLAY_VALUE"])? implode("/ ", $arElement["OFFER_DISPLAY_PROPERTIES"][$code]["DISPLAY_VALUE"]): $arElement["OFFER_DISPLAY_PROPERTIES"][$code]["DISPLAY_VALUE"])?>
                                </td>
                                <?
                            }
                            unset($arElement);
                            ?>
                        </tr>
                        <?
                    }
                }
            }
            ?>

        </table>
    </div>

    <div class="table_compare" id="table_compare_info">
    <table class="data-table">
    <?
    if (!empty($arResult["SHOW_PROPERTIES"]))
    {
        foreach ($arResult["SHOW_PROPERTIES"] as $code => $arProperty)
        {
            $showRow = true;
            if ($arResult['DIFFERENT'])
            {
                $arCompare = array();
                foreach($arResult["ITEMS"] as $arElement)
                {
                    $arPropertyValue = $arElement["DISPLAY_PROPERTIES"][$code]["VALUE"];
                    if (is_array($arPropertyValue))
                    {
                        sort($arPropertyValue);
                        $arPropertyValue = implode(" / ", $arPropertyValue);
                    }
                    $arCompare[] = $arPropertyValue;
                }
                unset($arElement);
                $showRow = (count(array_unique($arCompare)) > 1);
            }

            if ($showRow)
            {
                ?>
                <tr class="prop_">
                    <td><?=$arProperty["NAME"]?></td>
                    <?foreach($arResult["ITEMS"] as $arElement)
                    {
                        ?>
                        <td>
                            <div class="prop_mobile_visible_prorerty">
                                <div class="prop_mobile_visible">
                                    <?=$arProperty["NAME"]?>
                                </div>
                                <div class="table_compare_product_close" onclick="CatalogCompareObj.delete('<?=CUtil::JSEscape($arProperty["ACTION_LINK"])?>')"></div>
                            </div>

                            <?=(is_array($arElement["DISPLAY_PROPERTIES"][$code]["DISPLAY_VALUE"])? implode("/ ", $arElement["DISPLAY_PROPERTIES"][$code]["DISPLAY_VALUE"]): $arElement["DISPLAY_PROPERTIES"][$code]["DISPLAY_VALUE"])?>
                        </td>
                    <?
                    }
                    unset($arElement);
                    ?>
                </tr>
            <?
            }
        }
    }

    if (!empty($arResult["SHOW_OFFER_PROPERTIES"]))
    {
        foreach($arResult["SHOW_OFFER_PROPERTIES"] as $code=>$arProperty)
        {
            $showRow = true;
            if ($arResult['DIFFERENT'])
            {
                $arCompare = array();
                foreach($arResult["ITEMS"] as $arElement)
                {
                    $arPropertyValue = $arElement["OFFER_DISPLAY_PROPERTIES"][$code]["VALUE"];
                    if(is_array($arPropertyValue))
                    {
                        sort($arPropertyValue);
                        $arPropertyValue = implode(" / ", $arPropertyValue);
                    }
                    $arCompare[] = $arPropertyValue;
                }
                unset($arElement);
                $showRow = (count(array_unique($arCompare)) > 1);
            }
            if ($showRow)
            {
            ?>
            <tr>
                <td><?=$arProperty["NAME"]?></td>
                <?foreach($arResult["ITEMS"] as $arElement)
                {
                ?>
                <td>
                    <?=(is_array($arElement["OFFER_DISPLAY_PROPERTIES"][$code]["DISPLAY_VALUE"])? implode("/ ", $arElement["OFFER_DISPLAY_PROPERTIES"][$code]["DISPLAY_VALUE"]): $arElement["OFFER_DISPLAY_PROPERTIES"][$code]["DISPLAY_VALUE"])?>
                </td>
                <?
                }
                unset($arElement);
                ?>
            </tr>
            <?
            }
        }
    }
        ?>

    </table>
    </div>
    <?
    if ($isAjax)
    {
        die();
    }
    ?>
    </div>
</div>


<script type="text/javascript">
	var CatalogCompareObj = new BX.Iblock.Catalog.CompareClass("bx_catalog_compare_block", '<?=CUtil::JSEscape($arResult['~COMPARE_URL_TEMPLATE']); ?>');
</script>
