<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}
$this->setFrameMode(true);
use Kit\Origami\Helper\Config;
$hoverClass = implode(" ", Config::getArray("HOVER_EFFECT"));
?>
<div class="news_list">
    <div class="row">
        <?php foreach($arResult["ITEMS"] as $i => $arItem):?>
            <?php
            $this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'],
                CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
            $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'],
                CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"),
                ["CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')]);
                ?>
                <div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-12 news-col"
                     id="<?=
                     $this->GetEditAreaId
                     ($arItem['ID']); ?>">
                    <div class="news_list__item">
                        <div class="news_list__img">
                            <a href="<?= $arItem["DETAIL_PAGE_URL"] ?>"
                               class="news_list__img_link <?=$hoverClass?>" title="<?if(isset($arItem["IPROPERTY_VALUES"]["ELEMENT_META_TITLE"])) echo $arItem["IPROPERTY_VALUES"]["ELEMENT_META_TITLE"]; else echo $arItem["NAME"]?>">
	                            <?
	                            if($arItem["PREVIEW_PICTURE"]["SRC"])
	                            {
	                            	?>
		                            <img src="<?= $arItem["PREVIEW_PICTURE"]["SRC"]?>"
		                                 width="<?= $arItem["PREVIEW_PICTURE"]["WIDTH"]?>"
		                                 height="<?= $arItem["PREVIEW_PICTURE"]["HEIGHT"]?>"
		                                 alt="<?= $arItem["PREVIEW_PICTURE"]["ALT"]?><"
		                                 title="<?= $arItem["PREVIEW_PICTURE"]["TITLE"]?>">
		                            <?
	                            }
	                            else
	                            {
	                            	?>
		                            <img src="<?= SITE_TEMPLATE_PATH.'/assets/img/empty_h.jpg' ?>"
		                                 alt="<?= $arItem['NAME'] ?>"
		                                 title="<?= $arItem['NAME'] ?>">
		                            <?
	                            }
	                            ?>

                            </a>
                        </div>
                        <div class="news_list__content">
                            <div class="news_list__content_inner">
                                <p class="news_list__content_comment
                                news_list__content_comment-bold
                                news_list__content_comment_name
                                fonts__middle_text">
                                    <a href="<?= $arItem["DETAIL_PAGE_URL"] ?>"  title="<?if(isset($arItem["IPROPERTY_VALUES"]["ELEMENT_META_TITLE"])) echo $arItem["IPROPERTY_VALUES"]["ELEMENT_META_TITLE"]; else echo $arItem["NAME"]?>">
                                        <?= $arItem['NAME'] ?>
                                    </a>
                                </p>
                                <? if ($arParams["DISPLAY_PREVIEW_TEXT"] != "N"
                                    && $arItem["PREVIEW_TEXT"]
                                ): ?>
                                    <p class="news_list__content_comment news_list__content_comment_text">
                                        <?= $arItem["PREVIEW_TEXT"] ?>
                                    </p>
                                <?endif; ?>
                                <? if ($arParams["DISPLAY_DATE"] != "N"
                                    && $arItem["DISPLAY_ACTIVE_FROM"]
                                ): ?>
                                    <p class="news_list__content_date fonts__middle_comment">
                                        <?= $arItem['DISPLAY_ACTIVE_FROM'] ?>
                                    </p>
                                <?endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
                <?
        endforeach;
        if ($arParams["DISPLAY_BOTTOM_PAGER"]): ?>
            <div class="news_list__nav">
                <?= $arResult["NAV_STRING"] ?>
            </div>
        <? endif; ?>
    </div>
</div>
