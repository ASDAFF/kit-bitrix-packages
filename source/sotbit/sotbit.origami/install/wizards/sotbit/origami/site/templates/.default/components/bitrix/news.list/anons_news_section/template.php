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

?>

<div class="block_main_left__news_left">
    <a class="block_main_left__news_left__title fonts__main_text" href="/news/"><?=$arResult["NAME"]?></a>
        <?foreach($arResult["ITEMS"] as $arItem):?>
            <?
            $this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
            $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
            ?>
            <div class="block_main_left__news_left__item" id="<?=$this->GetEditAreaId($arItem['ID']);?>">

                <?if($arParams["DISPLAY_DATE"]!="N" && $arItem["DISPLAY_ACTIVE_FROM"]):?>
                    <p class="block_main_left__news_left__date fonts__middle_comment"><?echo $arItem["DISPLAY_ACTIVE_FROM"]?></p>
                <?endif?>

                <?if($arParams["DISPLAY_PREVIEW_TEXT"]!="N" && $arItem["PREVIEW_TEXT"]):?>

                    <?if(!$arParams["HIDE_LINK_WHEN_NO_DETAIL"] || ($arItem["DETAIL_TEXT"] && $arResult["USER_HAVE_ACCESS"])):?>
                        <a class="block_main_left__news_left__link fonts__middle_comment" href="<?echo $arItem["DETAIL_PAGE_URL"]?>">
                            <?echo $arItem["PREVIEW_TEXT"]?></a>
                    <?else:?>
                        <span class="block_main_left__news_left__link fonts__middle_comment"><?echo $arItem["PREVIEW_TEXT"]?></span>
                    <?endif;?>
                <?endif;?>
            </div>
        <?endforeach;?>
    <a href="/news/" class="block_main_left__news_others fonts__middle_comment">
        <?=GetMessage("OTHER_NEWS");?>
        <i class="icon-nav_1"></i>
    </a>
</div>
