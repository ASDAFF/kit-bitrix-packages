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
use Kit\Origami\Helper\Config;
Loc::loadMessages(__FILE__);
$hoverClass = implode(" ", Config::getArray("HOVER_EFFECT"));
$lazyLoad = (Config::get('LAZY_LOAD') == "Y");
?>
<? $idItem = \Bitrix\Main\Security\Random::getString(5); ?>

<div class="puzzle_block news-block__wrapper main-container size">
    <p class="puzzle_block__title fonts__middle_title">
        <?=$arParams["BLOCK_NAME"]?>
        <a href="<?=($arResult["ITEMS"][0]["LIST_PAGE_URL"]) ? $arResult["ITEMS"][0]["LIST_PAGE_URL"] : $arParams["LINK_TO_THE_FULL_LIST"]?>" title="<?=Loc::getMessage("KIT_NEWS_TWO_BIG_LINK_TEXT")?>" class="puzzle_block__link fonts__small_text">
            <?=Loc::getMessage("KIT_NEWS_TWO_BIG_LINK_TEXT");?>
            <i class="icon-nav_1"></i>
        </a>
    </p>

    <div id="news_block_two_<?= $idItem ?>" class="news_block_two swiper-container">
        <div class="news_block_two__wrapper swiper-wrapper">
            <?
            $i = 0;
            ?>
            <?foreach(array_slice($arResult["ITEMS"], 0, 6) as $arItem):?>
                <?
                $i++;
                $this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
                $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
                if($lazyLoad)
                {
                    $strLazyLoad = 'src="'.SITE_TEMPLATE_PATH.'/assets/img/loader_lazy.svg" data-src="'.$arItem["PREVIEW_PICTURE"]["SRC"].'"';
                    $lazyClass = "lazy";
                }else{
                    $strLazyLoad = 'src="'.$arItem["PREVIEW_PICTURE"]["SRC"].'"';
                    $lazyClass = "";
                }

                ?>
                <?if($i <= 2):?>
                    <div class="news_block_two__item swiper-slide" id="<?=$this->GetEditAreaId($arItem['ID']);?>">

                    <?if(!$arParams["HIDE_LINK_WHEN_NO_DETAIL"] || ($arItem["DETAIL_TEXT"] && $arResult["USER_HAVE_ACCESS"])):?>
                        <a href="<?=$arItem["DETAIL_PAGE_URL"]?>" class="news_block_two__news_item <?=$hoverClass?>" title="<?if(isset($arItem["IPROPERTY_VALUES"]["ELEMENT_META_TITLE"])) echo $arItem["IPROPERTY_VALUES"]["ELEMENT_META_TITLE"]; else echo $arItem["NAME"]?>">
                    <?else:?>
                        <div class="news_block_two__news_item">
                    <?endif;?>

                            <?if($arParams["DISPLAY_PICTURE"]!="N"):?>
                                <div class="news_block_two__news_img_block">
                                    <?if($arItem["PREVIEW_PICTURE"]):?>
                                        <div class="news_block_two__img_link_big">
                                            <img class="news_block_two__img <?=$lazyClass?>"
                                                <?=$strLazyLoad?>
                                                width="<?=$arItem["PREVIEW_PICTURE"]["WIDTH"]?>"
                                                height="<?=$arItem["PREVIEW_PICTURE"]["HEIGHT"]?>"
                                                alt="<?=$arItem["PREVIEW_PICTURE"]["ALT"]?>"
                                                title="<?=$arItem["PREVIEW_PICTURE"]["TITLE"]?>"
                                            >
                                            <?if($lazyLoad):?>
                                                <span class="loader-lazy loader-lazy--big"></span>
                                            <?endif;?>
                                        </div>
                                    <?else:?>
                                        <div class="news_block_two__news_img_link hover-zoom hover-highlight">
                                            <img class="news_block_two__news_img"
                                                src="<?=$templateFolder?>/images/empty_h.jpg"
                                                alt="<?=$arItem["NAME"]?>"
                                                title="<?=$arItem["NAME"]?>"
                                            >
                                        </div>
                                    <?endif?>
                                </div>
                            <?endif?>
                            <div class="news_block_two__news_content">
                                <?if($arParams["DISPLAY_NAME"]!="N" && $arItem["NAME"]):?>
                                    <p class="news_block_two__news_content_comment fonts__main_text"><?=$arItem["NAME"]?></p>
                                <?endif;?>
                                <?if($arParams["DISPLAY_DATE"]!="N" && $arItem["DISPLAY_ACTIVE_FROM"]):?>
                                    <p class="news_block_two__news_content_date fonts__middle_comment"><?=$arItem["DISPLAY_ACTIVE_FROM"]?></p>
                                <?endif?>
                            </div>

                    <?if(!$arParams["HIDE_LINK_WHEN_NO_DETAIL"] || ($arItem["DETAIL_TEXT"] && $arResult["USER_HAVE_ACCESS"])):?>
                        </a>
                    <?else:?>
                        </div>
                    <?endif;?>

                    </div>
                <?else:?>
                    <div class="news_block_two__item swiper-slide" id="<?=$this->GetEditAreaId($arItem['ID']);?>">
                        <?if($arParams["DISPLAY_PICTURE"]!="N"):?>
                            <?if(!$arParams["HIDE_LINK_WHEN_NO_DETAIL"] || ($arItem["DETAIL_TEXT"] && $arResult["USER_HAVE_ACCESS"])):?>
                                <div class="news_block_two__img">
                                    <a href="<?=$arItem["DETAIL_PAGE_URL"]?>" class="news_block_two__img_link <?=$hoverClass?>" title="<?if(isset($arItem["IPROPERTY_VALUES"]["ELEMENT_META_TITLE"])) echo $arItem["IPROPERTY_VALUES"]["ELEMENT_META_TITLE"]; else echo $arItem["NAME"]?>">
                                        <?if($arItem["PREVIEW_PICTURE"]):?>
                                            <img <?=$strLazyLoad?>
                                                class="<?=$lazyClass?>"
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
                                <div class="news_block_two__img">
                                    <?if($arItem["PREVIEW_PICTURE"]):?>
                                        <img <?=$strLazyLoad?>
                                            class="<?=$lazyClass?>"
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
                        <div class="news_block_two__content">
                            <?if($arParams["DISPLAY_NAME"]!="N" && $arItem["NAME"]):?>
                                <?if(!$arParams["HIDE_LINK_WHEN_NO_DETAIL"] || ($arItem["DETAIL_TEXT"] && $arResult["USER_HAVE_ACCESS"])):?>
                                    <p class="news_block_two__content_comment fonts__middle_text">
                                        <a href="<?=$arItem["DETAIL_PAGE_URL"]?>" title="<?if(isset($arItem["IPROPERTY_VALUES"]["ELEMENT_META_TITLE"])) echo $arItem["IPROPERTY_VALUES"]["ELEMENT_META_TITLE"]; else echo $arItem["NAME"]?>">
                                            <?=$arItem["NAME"]?>
                                        </a>
                                    </p>
                                <?else:?>
                                    <p class="news_block_two__content_comment fonts__middle_text"><?=$arItem["NAME"]?></p>
                                <?endif;?>
                            <?endif;?>
                            <?if($arParams["DISPLAY_PREVIEW_TEXT"]!="N" && $arItem["PREVIEW_TEXT"]):?>
                                <p class="news_block_two__content_comment fonts__middle_comment"><?=$arItem["PREVIEW_TEXT"];?></p>
                            <?endif;?>
                            <?if($arParams["DISPLAY_DATE"]!="N" && $arItem["DISPLAY_ACTIVE_FROM"]):?>
                                <p class="news_block_two__content_date fonts__middle_comment"><?=$arItem["DISPLAY_ACTIVE_FROM"]?></p>
                            <?endif?>
                        </div>
                    </div>
                <?endif;?>
            <?endforeach;?>
        </div>
    </div>
</div>
<script>
    const news_block_two_<?= $idItem ?> = new CreateSlider({
        sliderContainer: '#news_block_two_<?=$idItem?>',
        sizeSliderInit: 768
    });
</script>
