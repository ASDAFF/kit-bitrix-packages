<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$this->setFrameMode(true);

if($arResult["ITEMS"])
{
	foreach($arResult["ITEMS"] as $arItem)
	{
		?>
		<a href="<?=$arItem['DETAIL_PAGE_URL']?>" class="product_detail_brand__link">
			<?if($arItem["PREVIEW_PICTURE"]):?>
				<img src="<?=$arItem["PREVIEW_PICTURE"]["SRC"]?>"
				     width="<?=$arItem["PREVIEW_PICTURE"]["WIDTH"]?>"
				     height="<?=$arItem["PREVIEW_PICTURE"]["HEIGHT"]?>"
				     alt="<?=$arItem["PREVIEW_PICTURE"]["ALT"]?>"
				     title="<?=$arItem["PREVIEW_PICTURE"]["TITLE"]?>">
			<?endif?>
		</a>
		<?
	}
}