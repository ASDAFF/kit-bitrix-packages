<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$this->setFrameMode(true);
?>

<div class="brand_list_inner">
    <?foreach($arResult["ITEMS"] as $arItem):?>
        <?
        $this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
        $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
        ?>
		<div class="brand_list__item" id="<?=$this->GetEditAreaId($arItem['ID']);?>">
            <?if($arParams["DISPLAY_PICTURE"]!="N"):?>
				<a href="<?=$arItem["DETAIL_PAGE_URL"]?>" class="brand_block_variant_two__item_link">
                    <?if($arItem["PREVIEW_PICTURE"]):?>
						<img src="<?=$arItem["PREVIEW_PICTURE"]["SRC"]?>"
						     width="<?=$arItem["PREVIEW_PICTURE"]["WIDTH"]?>"
						     height="<?=$arItem["PREVIEW_PICTURE"]["HEIGHT"]?>"
						     alt="<?=$arItem["PREVIEW_PICTURE"]["ALT"]?>"
						     title="<?=$arItem["PREVIEW_PICTURE"]["TITLE"]?>"
						>
                    <?else:?>
						<img src="<?=$templateFolder?>/images/empty_h.jpg"
						     alt="<?=$arItem["NAME"]?>"
						     title="<?=$arItem["NAME"]?>"
						>
                    <?endif?>
				</a>
            <?endif?>
		</div>
    <?endforeach;?>
</div>
