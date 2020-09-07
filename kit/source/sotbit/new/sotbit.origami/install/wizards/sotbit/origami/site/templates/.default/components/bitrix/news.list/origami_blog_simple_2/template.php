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
$this->createFrame()->begin();

use Bitrix\Main\Localization\Loc;
use Sotbit\Origami\Helper\Config;
Loc::loadMessages(__FILE__);
$hoverClass = implode(" ", Config::getArray("HOVER_EFFECT"));
$lazyLoad = (Config::get('LAZY_LOAD') == "Y");
?>
<div class="blog__wrapper puzzle_block main-container size">
    <p class="puzzle_block__title fonts__middle_title">
        <?=$arParams["BLOCK_NAME"]?>
        <a href="<?=($arResult["ITEMS"][0]["LIST_PAGE_URL"]) ? $arResult["ITEMS"][0]["LIST_PAGE_URL"] : $arParams["LINK_TO_THE_FULL_LIST"]?>" class="puzzle_block__link fonts__small_text" title="<?=Loc::getMessage("SOTBIT_BLOG_SIMPLE_LINK_TEXT");?>">
            <?=Loc::getMessage("SOTBIT_BLOG_SIMPLE_LINK_TEXT");?>
        </a>
    </p>
    <div class="blog-section">
        <?foreach(array_slice($arResult["ITEMS"], 0, 2) as $arItem):?>
            <?
                $this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
                $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));

                if($lazyLoad)
                {
                    $strLazyLoad = 'src="'.SITE_TEMPLATE_PATH.'/assets/img/loader_lazy.svg" data-src="'.$arItem["PREVIEW_PICTURE"]["SRC"].'" class="blog-article__image-wrapper--image lazy"';
                }else{
                    $strLazyLoad = 'src="'.$arItem["PREVIEW_PICTURE"]["SRC"].'" class="blog-article__image-wrapper--image"';
                }
            ?>
            <div class="blog-section__article blog-article" id="<?=$this->GetEditAreaId($arItem['ID']);?>">
                <?if($arParams["DISPLAY_PICTURE"]!="N"):?>
                    <div class="blog-article__image-wrapper <?=$hoverClass?>">
                        <?if($arItem["PREVIEW_PICTURE"]):?>
                            <img <?=$strLazyLoad?>
                                width="<?=$arItem["PREVIEW_PICTURE"]["WIDTH"]?>"
                                height="<?=$arItem["PREVIEW_PICTURE"]["HEIGHT"]?>"
                                alt="<?=$arItem["PREVIEW_PICTURE"]["ALT"]?>"
                                title="<?=$arItem["PREVIEW_PICTURE"]["TITLE"]?>"
                            >
                            <?if($lazyLoad):?>
                                <span class="loader-lazy"></span>
                            <?endif;?>
                        <?else:?>
                            <img src="<?=$templateFolder?>/images/empty_h.jpg"
                                 alt="<?=$arItem["NAME"]?>"
                                 title="<?=$arItem["NAME"]?>"
                                 class="blog-article__image-wrapper--image"
                            >
                        <?endif?>
                    </div>
                <?endif;?>
                <div class="blog-article__text-wrapper">
                    <?if($arParams["DISPLAY_NAME"]!="N" && $arItem["NAME"]):?>
                        <a class="blog-article__text-wrapper--title" <?if(!$arParams["HIDE_LINK_WHEN_NO_DETAIL"] || ($arItem["DETAIL_TEXT"] && $arResult["USER_HAVE_ACCESS"])):?> href="<?=$arItem["DETAIL_PAGE_URL"]?>" <?endif;?> >
                            <div class="blog-article__text-wrapper--title-text">
                                <span>
                                    <?=$arItem["NAME"]?>
                                </span>
                            </div>
                        </a>
                    <?endif;?>
                    <div class="blog-article__text-wrapper--news-text">
                        <?if($arParams["DISPLAY_PREVIEW_TEXT"]!="N" && $arItem["PREVIEW_TEXT"]):?>
                            <span>
                                <?=$arItem["PREVIEW_TEXT"]?>
                            </span>
                        <?endif;?>
                    </div>
                    <?if(!$arParams["HIDE_LINK_WHEN_NO_DETAIL"] || ($arItem["DETAIL_TEXT"] && $arResult["USER_HAVE_ACCESS"])):?>
                        <a class="blog-article__text-wrapper--link" href="<?=$arItem["DETAIL_PAGE_URL"]?>">
                            <span><?=Loc::getMessage("SOTBIT_BLOG_BUTTON_TEXT_MORE");?></span>
                        </a>
                    <?endif;?>
                </div>
            </div>
        <?endforeach;?>

    </div>
</div>
