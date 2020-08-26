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
$idItem = \Bitrix\Main\Security\Random::getString(5);
?>

<div id="blog__wrapper_<?=$idItem?>" class="blog__wrapper puzzle_block main-container size">
    <p class="puzzle_block__title fonts__middle_title">
        <?=$arParams["BLOCK_NAME"]?>
        <a href="<?=($arResult["ITEMS"][0]["LIST_PAGE_URL"]) ? $arResult["ITEMS"][0]["LIST_PAGE_URL"] : $arParams["LINK_TO_THE_FULL_LIST"]?>" class="puzzle_block__link fonts__small_text" title="<?=Loc::getMessage("SOTBIT_BLOG_SIMPLE_LINK_TEXT");?>">
            <?=Loc::getMessage("SOTBIT_BLOG_SIMPLE_LINK_TEXT");?>
            <i class="icon-nav_1"></i>
        </a>
    </p>

    <div class="blog_block-simple swiper-container">
        <div class="blog_block-simple__wrapper swiper-wrapper">
            <?foreach(array_slice($arResult["ITEMS"], 0, 4) as $arItem):?>
                <?
                $this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
                $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));

                if($lazyLoad)
                {
                    $strLazyLoad = 'src="'.SITE_TEMPLATE_PATH.'/assets/img/loader_lazy.svg" data-src="'.$arItem["PREVIEW_PICTURE"]["SRC"].'" class="lazy"';
                }else{
                    $strLazyLoad = 'src="'.$arItem["PREVIEW_PICTURE"]["SRC"].'"';
                }
                ?>
                <div class="blog_block-simple__item-wrapper swiper-slide" id="<?=$this->GetEditAreaId($arItem['ID']);?>">
                    <div class="blog_block-simple__item">
                        <?if($arParams["DISPLAY_PICTURE"]!="N"):?>
                            <?if(!$arParams["HIDE_LINK_WHEN_NO_DETAIL"] || ($arItem["DETAIL_TEXT"] && $arResult["USER_HAVE_ACCESS"])):?>
                                <div class="blog_block-simple__item-img">
                                    <a href="<?=$arItem["DETAIL_PAGE_URL"]?>" class="blog_block__item_link <?=$hoverClass?>" title="<?if(isset($arItem["IPROPERTY_VALUES"]["ELEMENT_META_TITLE"])) echo $arItem["IPROPERTY_VALUES"]["ELEMENT_META_TITLE"]; else echo $arItem["NAME"]?>">
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
                                            >
                                        <?endif?>
                                    </a>
                                </div>
                            <?else:?>
                                <div class="blog_block-simple__item-img">
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
                                        >
                                    <?endif?>
                                </div>
                            <?endif;?>
                        <?endif?>
                        <div class="blog_block-simple__item-content">
                            <?if($arParams["DISPLAY_NAME"]!="N" && $arItem["NAME"]):?>
                                <?if(!$arParams["HIDE_LINK_WHEN_NO_DETAIL"] || ($arItem["DETAIL_TEXT"] && $arResult["USER_HAVE_ACCESS"])):?>
                                    <a href="<?=$arItem["DETAIL_PAGE_URL"]?>" class="blog_block__content_title fonts__main_text"  title="<?if(isset($arItem["IPROPERTY_VALUES"]["ELEMENT_META_TITLE"])) echo $arItem["IPROPERTY_VALUES"]["ELEMENT_META_TITLE"]; else echo $arItem["NAME"]?>">
                                        <?=$arItem["NAME"]?>
                                    </a>
                                <?else:?>
                                    <?=$arItem["NAME"]?>
                                <?endif;?>
                            <?endif;?>
                            <?if($arParams["DISPLAY_PREVIEW_TEXT"]!="N" && $arItem["PREVIEW_TEXT"]):?>
                                <div class="blog_block__content_comment fonts__small_text"><?=$arItem["PREVIEW_TEXT"];?></div>
                            <?endif;?>
                            <?if($arParams["DISPLAY_DATE"]!="N" && $arItem["DISPLAY_ACTIVE_FROM"]):?>
                                <div class="blog_block-simple__item-data fonts__middle_comment"><?=$arItem["DISPLAY_ACTIVE_FROM"]?></div>
                            <?endif?>
                        </div>
                    </div>
                </div>
            <?endforeach;?>
        </div>
    </div>

</div>
<script>
    const blog__wrapper_<?=$idItem?> = new CreateSlider ({
        sliderContainer: '#blog__wrapper_<?=$idItem?> .blog_block-simple',
        sizeSliderInit: 768
    });
</script>
