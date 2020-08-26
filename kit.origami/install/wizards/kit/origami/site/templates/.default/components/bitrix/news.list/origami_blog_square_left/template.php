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

<div class="blog__wrapper main-container">
<!--    <div class="blog__wrapper">-->
        <p class="puzzle_block__title fonts__middle_title">
            <?=$arParams["BLOCK_NAME"]?>
            <a href="<?=($arResult["ITEMS"][0]["LIST_PAGE_URL"]) ? $arResult["ITEMS"][0]["LIST_PAGE_URL"] : $arParams["LINK_TO_THE_FULL_LIST"]?>" class="puzzle_block__link fonts__small_text" title="<?=Loc::getMessage("KIT_BLOG_SQUARE_LEFT_LINK_TEXT");?>">
                <?=Loc::getMessage("KIT_BLOG_SQUARE_LEFT_LINK_TEXT");?>
                <i class="icon-nav_1"></i>
            </a>
        </p>

        <div class="blog_block-square blog_block-square--left">
            <div class="blog_block-square__wrapper">
                <?
                $i = 0;
                ?>
                <?foreach($arResult["ITEMS"] as $arItem):?>
                    <?
                    $i++;
                    $this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
                    $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));

                    if($lazyLoad)
                    {
                        $strLazyLoad = 'src="'.SITE_TEMPLATE_PATH.'/assets/img/loader_lazy.svg" data-src="'.$arItem["PREVIEW_PICTURE"]["SRC"].'" class="lazy"';
                    }else{
                        $strLazyLoad = 'src="'.$arItem["PREVIEW_PICTURE"]["SRC"].'"';
                    }
                    ?>
                    <?if($i == 1):?>
                        <div class="blog_block-square__block-big" id="<?=$this->GetEditAreaId($arItem['ID']);?>">
                            <div class="blog_block-square__item">
                                <?if($arParams["DISPLAY_PICTURE"]!="N"):?>
                                    <?if(!$arParams["HIDE_LINK_WHEN_NO_DETAIL"] || ($arItem["DETAIL_TEXT"] && $arResult["USER_HAVE_ACCESS"])):?>
                                        <div class="blog_block-square__img-big <?=$hoverClass?>">
                                            <a href="<?=$arItem["DETAIL_PAGE_URL"]?>" class="blog_block-square__img-link"  title="<?if(isset($arItem["IPROPERTY_VALUES"]["ELEMENT_META_TITLE"])) echo $arItem["IPROPERTY_VALUES"]["ELEMENT_META_TITLE"]; else echo $arItem["NAME"]?>">
                                                <?if($arItem["PREVIEW_PICTURE"]):?>
                                                    <img <?=$strLazyLoad?>
                                                        width="<?=$arItem["PREVIEW_PICTURE"]["WIDTH"]?>"
                                                        height="<?=$arItem["PREVIEW_PICTURE"]["HEIGHT"]?>"
                                                        alt="<?=$arItem["PREVIEW_PICTURE"]["ALT"]?>"
                                                        title="<?=$arItem["PREVIEW_PICTURE"]["TITLE"]?>"
                                                    >
                                                    <?if($lazyLoad):?>
                                                        <span class="loader-lazy loader-lazy--big"></span>
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
                                        <div class="blog_block-square__img_big hover-zoom">
                                            <?if($arItem["PREVIEW_PICTURE"]):?>
                                                <img <?=$strLazyLoad?>
                                                    width="<?=$arItem["PREVIEW_PICTURE"]["WIDTH"]?>"
                                                    height="<?=$arItem["PREVIEW_PICTURE"]["HEIGHT"]?>"
                                                    alt="<?=$arItem["PREVIEW_PICTURE"]["ALT"]?>"
                                                    title="<?=$arItem["PREVIEW_PICTURE"]["TITLE"]?>"
                                                >
                                                <?if($lazyLoad):?>
                                                    <span class="loader-lazy loader-lazy--big"></span>
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
                                <div class="blog_block-square__content_dig">
                                    <?if($arParams["DISPLAY_NAME"]!="N" && $arItem["NAME"]):?>
                                        <?if(!$arParams["HIDE_LINK_WHEN_NO_DETAIL"] || ($arItem["DETAIL_TEXT"] && $arResult["USER_HAVE_ACCESS"])):?>
                                            <a href="<?=$arItem["DETAIL_PAGE_URL"]?>" class="blog_block-square__content_dig_title fonts__main_text" title="<?if(isset($arItem["IPROPERTY_VALUES"]["ELEMENT_META_TITLE"])) echo $arItem["IPROPERTY_VALUES"]["ELEMENT_META_TITLE"]; else echo $arItem["NAME"]?>">
                                                <?=$arItem["NAME"]?>
                                            </a>
                                        <?else:?>
                                            <?=$arItem["NAME"]?>
                                        <?endif;?>
                                    <?endif;?>
                                    <?if($arParams["DISPLAY_PREVIEW_TEXT"]!="N" && $arItem["PREVIEW_TEXT"]):?>
                                        <div class="blog_block-square__content_dig_comment fonts__small_text"><?=$arItem["PREVIEW_TEXT"];?></div>
                                    <?endif;?>
                                    <?if($arParams["DISPLAY_DATE"]!="N" && $arItem["DISPLAY_ACTIVE_FROM"]):?>
                                        <div class="blog_block-square__content_dig_data fonts__middle_comment"><?=$arItem["DISPLAY_ACTIVE_FROM"]?></div>
                                    <?endif?>
                                </div>
                            </div>
                        </div>
                    <?else:?>
                        <div class="blog_block-square__block" id="<?=$this->GetEditAreaId($arItem['ID']);?>">
                            <div class="blog_block-square__item">
                                <?if($arParams["DISPLAY_PICTURE"]!="N"):?>
                                    <?if(!$arParams["HIDE_LINK_WHEN_NO_DETAIL"] || ($arItem["DETAIL_TEXT"] && $arResult["USER_HAVE_ACCESS"])):?>
                                        <div class="blog_block-square_img">
                                            <a href="<?=$arItem["DETAIL_PAGE_URL"]?>" class="blog_block-square__item_link <?=$hoverClass?>" title="<?if(isset($arItem["IPROPERTY_VALUES"]["ELEMENT_META_TITLE"])) echo $arItem["IPROPERTY_VALUES"]["ELEMENT_META_TITLE"]; else echo $arItem["NAME"]?>">
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
                                        <div class="blog_block-square__img">
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
                                <div class="blog_block-square__content">
                                    <?if($arParams["DISPLAY_NAME"]!="N" && $arItem["NAME"]):?>
                                        <?if(!$arParams["HIDE_LINK_WHEN_NO_DETAIL"] || ($arItem["DETAIL_TEXT"] && $arResult["USER_HAVE_ACCESS"])):?>
                                            <a href="<?=$arItem["DETAIL_PAGE_URL"]?>" class="blog_block-square__content_title fonts__main_text" title="<?if(isset($arItem["IPROPERTY_VALUES"]["ELEMENT_META_TITLE"])) echo $arItem["IPROPERTY_VALUES"]["ELEMENT_META_TITLE"]; else echo $arItem["NAME"]?>">
                                                <?=$arItem["NAME"]?>
                                            </a>
                                        <?else:?>
                                            <?=$arItem["NAME"]?>
                                        <?endif;?>
                                    <?endif;?>
                                    <?if($arParams["DISPLAY_PREVIEW_TEXT"]!="N" && $arItem["PREVIEW_TEXT"]):?>
                                        <div class="blog_block-square__content_comment fonts__small_text"><?=$arItem["PREVIEW_TEXT"];?></div>
                                    <?endif;?>
                                    <?if($arParams["DISPLAY_DATE"]!="N" && $arItem["DISPLAY_ACTIVE_FROM"]):?>
                                        <div class="blog_block-square__content_data fonts__middle_comment"><?=$arItem["DISPLAY_ACTIVE_FROM"]?></div>
                                    <?endif?>
                                    <a class="main_url main_btn sweep-to-right" href="<?=$arItem["DETAIL_PAGE_URL"]?>">
                                        <?=Loc::getMessage("KIT_BLOG_BUTTON_TEXT");?>
                                        <i class="icon-nav_1"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?endif;?>
                <?endforeach;?>
            </div>
        </div>
<!--    </div>-->

</div>
