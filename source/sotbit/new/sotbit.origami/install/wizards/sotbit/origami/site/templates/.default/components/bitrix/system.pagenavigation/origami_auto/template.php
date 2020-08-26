<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$this->setFrameMode(true);
if($arResult["NavPageCount"] > 1)
{
	if ($arResult["NavPageNomer"]+1 <= $arResult["nEndPage"])
	{
		$plus = $arResult["NavPageNomer"]+1;
        $url = $arResult["sUrlPathParams"] .
            (substr($arResult["sUrlPathParams"], -1) == "?" ? "PAGEN_".$arResult['NavNum']."=" :
                substr($arResult["sUrlPathParams"], -1) == "&" ? "PAGEN_".$arResult['NavNum']."=" : "") . $plus .
            (strstr($arResult["sUrlPathParams"], "AJAX=Y") == false ? "&AJAX=Y" : "");
		?>
		<div class="show_more_block fonts__main_comment" data-use="show-more-<?=$arResult['NavNum']?>" data-url="<?=$url?>" data-entity="pager-more">
			<?=\Bitrix\Main\Localization\Loc::getMessage('nav_more')?> <i class="icon-nav_button"></i>
		</div>
		<?
	}
}