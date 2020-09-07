<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$this->setFrameMode(true);
if(!$arResult["NavShowAlways"])
{
    if ($arResult["NavRecordCount"] == 0 || ($arResult["NavPageCount"] == 1 && $arResult["NavShowAll"] == false))
        return;
}
$queryString = $arResult["NavQueryString"];
$queryString = preg_replace('/[&]?\s*AJAX\s*=\s*Y\s*/', '', $queryString);
$queryString = preg_replace('/[&]?\s*bxajaxid=\s*\w*\&?/', '', $queryString);
$strNavQueryString = ($queryString != "" ? $queryString."&amp;" : "");
$strNavQueryStringFull = ($queryString != "" ? "?".$queryString : "");
?>
<?
if($arResult["NavPageCount"] > 1)
{
    if ($arResult["NavPageNomer"]+1 <= $arResult["nEndPage"])
    {
        $plus = $arResult["NavPageNomer"]+1;
        $url = $arResult["sUrlPathParams"] .
            (substr($arResult["sUrlPathParams"], -1) == "?" ? "PAGEN_".$arResult["NavNum"]."=" :
                substr($arResult["sUrlPathParams"], -1) == "&" ? "PAGEN_".$arResult["NavNum"]."=" : "") . $plus .
            (strstr($arResult["sUrlPathParams"], "AJAX=Y") == false ? "&AJAX=Y" : "");
        $tmp = preg_replace('/[&]?\s*bxajaxid=\s*\w*/', '', $url);
        ( $tmp ? $url = $tmp: '');
        ?>
        <div class="show_more_block fonts__main_comment" data-use="show-more-<?=$arResult['NavNum']?>" data-url="<?=$url?>" data-entity="pager-more">
            <?=\Bitrix\Main\Localization\Loc::getMessage('nav_more')?> <i class="icon-nav_button"></i>
        </div>
        <?
    }
}
?>
        <div class="block_page_navigation fonts__main_comment">
            <?if($arResult["bDescPageNumbering"] === true):?>
                <?if ($arResult["NavPageNomer"] < $arResult["NavPageCount"]):?>
                    <?if($arResult["bSavePage"]):?>
                        <a class="block_page_previous" href="<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>PAGEN_<?=$arResult
                        ["NavNum"]?>=<?=($arResult["NavPageNomer"]+1)?>"><i class="icon-left"></i></a>
                    <?else:?>
                        <?if ($arResult["NavPageCount"] == ($arResult["NavPageNomer"]+1) ):?>
                            <a class="block_page_previous" href="<?=$arResult["sUrlPath"]?><?=$strNavQueryStringFull
                            ?>"><i class="icon-left"></i></a>
                        <?else:?>
                            <a class="block_page_previous" href="<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>PAGEN_
				<?=$arResult["NavNum"]?>=<?=($arResult["NavPageNomer"]+1)?>"><i class="icon-left"></i></a>
                        <?endif?>
                    <?endif?>
                <?else:?>
                    <i class="icon-left"></i>
                <?endif?>

                <?while($arResult["nStartPage"] >= $arResult["nEndPage"]):?>
                    <?$NavRecordGroupPrint = $arResult["NavPageCount"] - $arResult["nStartPage"] + 1;?>

                    <?if ($arResult["nStartPage"] == $arResult["NavPageNomer"]):?>
                        <span class="block_page_current"><?=$NavRecordGroupPrint?></span>
                    <?elseif($arResult["nStartPage"] == $arResult["NavPageCount"] && $arResult["bSavePage"] == false):?>
                        <a class="block_page_normal" href="<?=$arResult["sUrlPath"]?><?=$strNavQueryStringFull?>"><?=$NavRecordGroupPrint?></a>
                    <?else:?>
                        <a class="block_page_normal" href="<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>PAGEN_<?=$arResult["NavNum"]?>=<?=$arResult["nStartPage"]?>"><?=$NavRecordGroupPrint?></a>
                    <?endif?>

                    <?$arResult["nStartPage"]--?>
                <?endwhile?>
                <?if ($arResult["NavPageNomer"] > 1):?>
                    <a class="block_page_next" href="<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>PAGEN_<?=$arResult["NavNum"]?>=<?=($arResult["NavPageNomer"]-1)?>"><i class="icon-nav_2"></i></a>
                <?else:?>

                <?endif?>

            <?else:?>
                <?if ($arResult["NavPageNomer"] > 1):?>
                    <?if($arResult["bSavePage"]):?>
                        <a class="block_page_previous" href="<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>PAGEN_<?=$arResult
                        ["NavNum"]?>=<?=($arResult["NavPageNomer"]-1)?>"><i class="icon-left"></i></a>
                    <?else:?>
                        <?if ($arResult["NavPageNomer"] > 2):?>
                            <a class="block_page_previous" href="<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>PAGEN_
				<?=$arResult["NavNum"]?>=<?=($arResult["NavPageNomer"]-1)?>"><i class="icon-left"></i></a>
                        <?else:?>
                            <a class="block_page_previous" href="<?=$arResult["sUrlPath"]?><?=$strNavQueryStringFull?>"><i
                                        class="icon-left"></i></a>
                        <?endif?>
                    <?endif?>
                <?else:?>

                <?endif?>

                <?while($arResult["nStartPage"] <= $arResult["nEndPage"]):?>

                    <?if ($arResult["nStartPage"] == $arResult["NavPageNomer"]):?>
                        <span class="block_page_current"><?=$arResult["nStartPage"]?></span>
                    <?elseif($arResult["nStartPage"] == 1 && $arResult["bSavePage"] == false):?>
                        <a class="block_page_normal" href="<?=$arResult["sUrlPath"]?><?=$strNavQueryStringFull?>"><?=$arResult["nStartPage"]?></a>
                    <?else:?>
                        <a class="block_page_normal" href="<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>PAGEN_<?=$arResult["NavNum"]?>=<?=$arResult["nStartPage"]?>"><?=$arResult["nStartPage"]?></a>
                    <?endif?>
                    <?$arResult["nStartPage"]++?>
                <?endwhile?>
                <?if($arResult["NavPageNomer"] < $arResult["NavPageCount"]):?>
                    <a class="block_page_next" href="<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>PAGEN_<?=$arResult["NavNum"]?>=<?=($arResult["NavPageNomer"]+1)?>"><i
                                class="icon-nav_2"></i></a>
                <?else:?>

                <?endif?>

            <?endif?>

        </div>