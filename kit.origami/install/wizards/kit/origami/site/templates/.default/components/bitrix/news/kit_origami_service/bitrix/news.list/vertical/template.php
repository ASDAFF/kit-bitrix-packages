<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}
$this->setFrameMode(true);

?>
<div class="promotions_list promotions_list_vertical">
	<div class="row">
        <? foreach ($arResult["ITEMS"] as $i => $arItem): ?>
            <?
            $this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'],
                CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
            $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'],
                CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"),
                ["CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')]);
				?>
	            <div class="col-12 promotion-col"
	                 id="<?=
	            $this->GetEditAreaId
	            ($arItem['ID']); ?>">
		            <div class="promotion_list__item">

				            <?
				            if($arItem["PREVIEW_PICTURE"]["SRC"])
				            {
				            	?>
					            <div class="promotion_list__img">
							            <a href="<?= $arItem["DETAIL_PAGE_URL"] ?>" class="promotion_list__img_link hover-zoom hover-highlight">
								            <img src="<?= $arItem["PREVIEW_PICTURE"]["SRC"] ?>" width="<?= $arItem["PREVIEW_PICTURE"]["WIDTH"] ?>" height="<?= $arItem["PREVIEW_PICTURE"]["HEIGHT"] ?>" alt="<?= $arItem['NAME']?>" title="<?= $arItem['NAME']?>">
							            </a>
					            </div>
					            <?
				            }
				            ?>

			            <div class="promotion_list__content">
				            <div class="promotion_list__content_inner">
					            <p class="promotion_list__content_comment
					            promotion_list__content_comment-bold
					            promotion_list__content_comment_name
					            fonts__middle_text">
						            <a href="<?= $arItem["DETAIL_PAGE_URL"] ?>">
		                                <?= $arItem['NAME']?>
						            </a>
					            </p>
		                        <? if ($arParams["DISPLAY_DATE"] != "N" && $arItem["DISPLAY_ACTIVE_FROM"]): ?>
					             <p class="promotion_list__content_date fonts__middle_comment">
                                     <?=\Bitrix\Main\Localization\Loc
                                         ::getMessage('UNTIL')?> <?=$arItem['DISPLAY_ACTIVE_FROM']?>
					             </p>
		                        <?endif;?>
                                <? if ($arParams["DISPLAY_PREVIEW_TEXT"] != "N" && $arItem["PREVIEW_TEXT"]): ?>
						            <p class="promotion_list__content_comment promotion_list__content_comment_text">
                                        <?=$arItem["PREVIEW_TEXT"]?>
						            </p>
                                <?endif;?>
					            <div class="promotion_list__detail">
						            <a class="promotion_list__detail__link"
						               href="<?= $arItem["DETAIL_PAGE_URL"] ?>">
                                        <?=\Bitrix\Main\Localization\Loc::getMessage('NEWS_LIST_READ')?> <i class="icon-nav_1"></i>
						            </a>
					            </div>
				            </div>
			            </div>
		            </div>
	            </div>
	            <?
        endforeach;
        if ($arParams["DISPLAY_BOTTOM_PAGER"]): ?>
            <div class="promotion_list__nav">
				<?= $arResult["NAV_STRING"] ?>
            </div>
        <? endif; ?>
	</div>
</div>
