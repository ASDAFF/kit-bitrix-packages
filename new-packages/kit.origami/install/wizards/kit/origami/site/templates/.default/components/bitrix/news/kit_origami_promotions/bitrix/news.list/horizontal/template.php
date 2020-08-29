<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}
$this->setFrameMode(true);
use Sotbit\Origami\Helper\Config;
$hoverClass = implode(" ", Config::getArray("HOVER_EFFECT"));

?>
<div class="promotions_list promotions_list_horizontal">
	<div class="row">
        <? foreach ($arResult["ITEMS"] as $i => $arItem):
            $blockID = $this->randString();?>
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
				            if($arItem["DETAIL_PICTURE"]["SRC"])
				            {
				            	?>
					            <div class="promotion_list__img" data-timer="timerID_<?=$blockID?>">
							            <a href="<?= $arItem["DETAIL_PAGE_URL"] ?>" class="promotion_list__img_link <?=$hoverClass?>"  title="<?if(isset($arItem["IPROPERTY_VALUES"]["ELEMENT_META_TITLE"])) echo $arItem["IPROPERTY_VALUES"]["ELEMENT_META_TITLE"]; else echo $arItem["NAME"]?>">
								            <img src="<?= $arItem["DETAIL_PICTURE"]["SRC"] ?>" width="<?= $arItem["PREVIEW_PICTURE"]["WIDTH"] ?>" height="<?= $arItem["PREVIEW_PICTURE"]["HEIGHT"] ?>" alt="<?= $arItem["PREVIEW_PICTURE"]["ALT"] ?>" title="<?= $arItem["PREVIEW_PICTURE"]["TITLE"] ?>">
							            </a>
                                    <?if (isset($arItem["DATE_ACTIVE_TO"]) && !empty($arItem["DATE_ACTIVE_TO"])): ?>
                                        <?
                                        if (Config::get('TIMER_PROMOTIONS') == 'Y') {
                                            $APPLICATION->IncludeComponent(
                                                "kit:origami.timer",
                                                "origami_default",
                                                array(
                                                    "COMPONENT_TEMPLATE" => "origami_default",
                                                    "ID" => $arItem["ID"],
                                                    "BLOCK_ID" => $blockID,
                                                    "ACTIVATE" => "Y",
                                                    "TIMER_SIZE" => "md",
                                                    "TIMER_DATE_END" => $arItem["DATE_ACTIVE_TO"]
                                                ),
                                                $component
                                            );
                                        }
                                        ?>
                                    <?endif;?>
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
						            <a href="<?= $arItem["DETAIL_PAGE_URL"] ?>"  title="<?if(isset($arItem["IPROPERTY_VALUES"]["ELEMENT_META_TITLE"])) echo $arItem["IPROPERTY_VALUES"]["ELEMENT_META_TITLE"]; else echo $arItem["NAME"]?>">
		                                <?= $arItem['NAME']?>
						            </a>
					            </p>
		                        <? if ($arParams["DISPLAY_DATE"] != "N" && $arItem["DISPLAY_ACTIVE_TO"]): ?>
					             <p class="promotion_list__content_date fonts__middle_comment">
						             <?=\Bitrix\Main\Localization\Loc
						             ::getMessage('UNTIL')?>
						             <?=$arItem['DISPLAY_ACTIVE_TO']?>
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
