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
$this->setFrameMode(true);

if(!$arResult["NavShowAlways"])
{
	if ($arResult["NavRecordCount"] == 0 || ($arResult["NavPageCount"] == 1 && $arResult["NavShowAll"] == false))
		return;
}
$queryString = $arResult["NavQueryString"];
$queryString = preg_replace('/[&]?\s*ajaxFilter=\s*\w*\&?/', '', $queryString);
$strNavQueryString = ($queryString != "" ? $queryString."&amp;" : "");
$strNavQueryStringFull = ($queryString != "" ? "?".$queryString : "");
?>

<div data-pagination-num="1">
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

	</div></div>
