<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}
$this->setFrameMode(true);
use Sotbit\Origami\Helper\Config;
$hoverClass = implode(" ", Config::getArray("HOVER_EFFECT"));
?>
<div class="blog_list">
	<div class="row">
        <? foreach ($arResult["ITEMS"] as $i => $arItem): ?>
            <?
            $this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'],
                CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
            $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'],
                CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"),
                ["CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')]);
            if($i == 0)
            {
            	?>
	            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12 blog-col"
	                 id="<?= $this->GetEditAreaId($arItem['ID']); ?>">
		            <a href="<?= $arItem["DETAIL_PAGE_URL"] ?>"
		               class="blog_list__link <?=$hoverClass?>" title="<?if(isset($arItem["IPROPERTY_VALUES"]["ELEMENT_META_TITLE"])) echo $arItem["IPROPERTY_VALUES"]["ELEMENT_META_TITLE"]; else echo $arItem["NAME"]?>">
			            <div class="blog_list__img_block">
				            <div class="blog_list__img_link_big" title="<?if(isset($arItem["IPROPERTY_VALUES"]["ELEMENT_META_TITLE"])) echo $arItem["IPROPERTY_VALUES"]["ELEMENT_META_TITLE"]; else echo $arItem["NAME"]?>">
				            <?if($arItem["PREVIEW_PICTURE"]["SRC"])
				            	{?>
					             <img class=""
                                      src="<?= $arItem["PREVIEW_PICTURE"]["SRC"] ?>"
                                      width="<?=$arItem["PREVIEW_PICTURE"]["WIDTH"] ?>"
                                      height="<?=$arItem["PREVIEW_PICTURE"]["HEIGHT"] ?>"
                                      alt="<?=$arItem["PREVIEW_PICTURE"]["ALT"] ?>" title="<?=$arItem["PREVIEW_PICTURE"]["NAME"] ?>">
				                <?
				                }
				                else
	                            {
	                            	?>
		                            <img src="<?= SITE_TEMPLATE_PATH.'/assets/img/empty_h.jpg' ?>"
		                                 alt="<?= $arItem['NAME'] ?>"
		                                 title="<?= $arItem['NAME'] ?>">
		                            <?
	                            }?>
				            </div>
			            </div>
			            <div class="blog_list__big_content">
				            <p class="blog_list__content_comment
				            blog_list__content_comment_name
				            blog_list__content_comment-bold fonts__main_text">
					            <?= $arItem['NAME']?>
				            </p>
                            <? if ($arParams["DISPLAY_PREVIEW_TEXT"] != "N" && $arItem["PREVIEW_TEXT"]): ?>
					            <div class="blog_list__content_comment
					            blog_list__content_comment_text
					            fonts__middle_comment">
                                    <?=$arItem["PREVIEW_TEXT"]?>
					            </div>
                            <?endif;?>
                             <? if ($arParams["DISPLAY_DATE"] != "N" && $arItem["DISPLAY_ACTIVE_FROM"]): ?>
				                <p class="blog_list__content_date fonts__middle_comment">
					                <?=$arItem['DISPLAY_ACTIVE_FROM']?>
				                </p>
                            <?endif;?>
			            </div>
		            </a>
	            </div>
	            <?
            }
            else
            {
				?>
	            <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6 col-12 blog-col"
	                 id="<?=
	            $this->GetEditAreaId
	            ($arItem['ID']); ?>">
		            <div class="blog_list__item">
			            <div class="blog_list__img">
				            <a href="<?= $arItem["DETAIL_PAGE_URL"] ?>" class="blog_list__img_link <?=$hoverClass?>" title="<?if(isset($arItem["IPROPERTY_VALUES"]["ELEMENT_META_TITLE"])) echo $arItem["IPROPERTY_VALUES"]["ELEMENT_META_TITLE"]; else echo $arItem["NAME"]?>">
                                <?if($arItem["PREVIEW_PICTURE"]["SRC"])
                                {?>
					            <img src="<?= $arItem["PREVIEW_PICTURE"]["SRC"] ?>" width="<?= $arItem["PREVIEW_PICTURE"]["WIDTH"] ?>" height="<?= $arItem["PREVIEW_PICTURE"]["HEIGHT"] ?>" alt="<?= $arItem["PREVIEW_PICTURE"]["ALT"] ?>" title="<?= $arItem["PREVIEW_PICTURE"]["TITLE"] ?>">
                                    <?
                                }
                                else
                                {
                                    ?>
	                                <img src="<?= SITE_TEMPLATE_PATH.'/assets/img/empty_h.jpg' ?>"
	                                     alt="<?= $arItem['NAME'] ?>"
	                                     title="<?= $arItem['NAME'] ?>">
                                    <?
                                }?>
				            </a>
			            </div>
			            <div class="blog_list__content">
				            <div class="blog_list__content_inner">
					            <p class="blog_list__content_comment
					            blog_list__content_comment-bold
					            blog_list__content_comment_name
					            fonts__middle_text">
						            <a href="<?= $arItem["DETAIL_PAGE_URL"] ?>" title="<?if(isset($arItem["IPROPERTY_VALUES"]["ELEMENT_META_TITLE"])) echo $arItem["IPROPERTY_VALUES"]["ELEMENT_META_TITLE"]; else echo $arItem["NAME"]?>">
		                                <?= $arItem['NAME']?>
						            </a>
					            </p>
		                        <? if ($arParams["DISPLAY_PREVIEW_TEXT"] != "N" && $arItem["PREVIEW_TEXT"]): ?>
						            <div class="blog_list__content_comment blog_list__content_comment_text">
							            <?=$arItem["PREVIEW_TEXT"]?>
						            </div>
		                        <?endif;?>
		                        <? if ($arParams["DISPLAY_DATE"] != "N" && $arItem["DISPLAY_ACTIVE_FROM"]): ?>
					             <p class="blog_list__content_date fonts__middle_comment">
						             <?=$arItem['DISPLAY_ACTIVE_FROM']?>
					             </p>
		                        <?endif;?>
				            </div>
			            </div>
                        <div class="blog_list__detail">
                            <a class="main_url main_btn sweep-to-right
				            blog_list__detail__link"
                               href="<?= $arItem["DETAIL_PAGE_URL"] ?>" title="<?if(isset($arItem["IPROPERTY_VALUES"]["ELEMENT_META_TITLE"])) echo $arItem["IPROPERTY_VALUES"]["ELEMENT_META_TITLE"]; else echo $arItem["NAME"]?>">
                                <?=\Bitrix\Main\Localization\Loc::getMessage('NEWS_LIST_READ')?> <i class="icon-nav_1"></i>
                            </a>
                        </div>
		            </div>
	            </div>
	            <?
            }
        endforeach;
        if ($arParams["DISPLAY_BOTTOM_PAGER"]): ?>
            <div class="blog_list__nav">
				<?= $arResult["NAV_STRING"] ?>
            </div>
        <? endif; ?>
    </div>
    <script>
        let heightImg = new HeightImg('.blog_list__img_block', '.blog_list__img');
    </script>
</div>
