<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
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

use Bitrix\Main\Localization\Loc;
use Sotbit\Origami\Helper\Config;
Loc::loadMessages(__FILE__);
$hoverClass = implode(" ", Config::getArray("HOVER_EFFECT"));
$lazyLoad = (Config::get('LAZY_LOAD') == "Y");
$sliderButtons = "";
if (\Sotbit\Origami\Helper\Config::get('SLIDER_BUTTONS') == 'square') {
    $sliderButtons = "btn-slider-main--one";
} else if (\Sotbit\Origami\Helper\Config::get('SLIDER_BUTTONS') == 'circle') {
    $sliderButtons = "btn-slider-main--two";
}
?>
<?$idItem = \Bitrix\Main\Security\Random::getString(5);?>

<div class="news-slider-wrapper size puzzle_block main-container">

    <p class="puzzle_block__title fonts__middle_title">
        <?=$arParams["PAGER_TITLE"]?>
        <a href="<?=($arResult["ITEMS"][0]["LIST_PAGE_URL"]) ? $arResult["ITEMS"][0]["LIST_PAGE_URL"] : $arParams["LINK_TO_THE_FULL_LIST"]?>" title="<?=Loc::getMessage("SOTBIT_NEWS_SIMPLE_LINK_TEXT");?>" class="puzzle_block__link fonts__small_text">
            <?=Loc::getMessage("SOTBIT_NEWS_SIMPLE_LINK_TEXT");?>
            <i class="icon-nav_1"></i>
        </a>
    </p>

    <div id="news-slider_<?= $idItem ?>" class="news-slider swiper-container">
        <div class="news-slider__wrapper swiper-wrapper">
            <?foreach($arResult["ITEMS"] as $arItem):?>
                <?
                $this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
                $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
                if($lazyLoad)
                {
                    $strLazyLoad = 'src="'.SITE_TEMPLATE_PATH.'/assets/img/loader_lazy.svg" data-src="'.$arItem["PREVIEW_PICTURE"]["SRC"].'"';
                }else{
                    $strLazyLoad = 'src="'.$arItem["PREVIEW_PICTURE"]["SRC"].'"';
                }

                ?>
                <a <?if(!$arParams["HIDE_LINK_WHEN_NO_DETAIL"] || ($arItem["DETAIL_TEXT"] && $arResult["USER_HAVE_ACCESS"])):?> href="<?=$arItem["DETAIL_PAGE_URL"]?>" <?endif;?>
                    class="news-slider__slide news-slide swiper-slide" id="<?=$this->GetEditAreaId($arItem['ID']);?>">

                    <div class="news-slider__slide--border-wrapper">

                        <div class="news-slider__border-top"></div>
                        <div class="news-slider__border-right"></div>
                        <div class="news-slider__border-bottom"></div>
                        <div class="news-slider__border-left"></div>

                        <div class="news-slide__image-wrapper <?=$hoverClass?>">
                            <?if($arParams["DISPLAY_PICTURE"]!="N"):?>
                                <?if($arItem["PREVIEW_PICTURE"]):?>
                                    <img <?=$strLazyLoad?>
                                        width="<?=$arItem["PREVIEW_PICTURE"]["WIDTH"]?>"
                                        height="<?=$arItem["PREVIEW_PICTURE"]["HEIGHT"]?>"
                                        alt="<?=$arItem["PREVIEW_PICTURE"]["ALT"]?>"
                                        title="<?=$arItem["PREVIEW_PICTURE"]["TITLE"]?>"
                                        class="<?if($lazyLoad):?> lazy <?endif;?> news-slide__image-wrapper--image"
                                    >
                                    <?if($lazyLoad):?>
                                        <span class="loader-lazy"></span>
                                    <?endif;?>
                                <?else:?>
                                    <img src="<?=$templateFolder?>/images/empty_h.jpg"
                                         alt="<?=$arItem["NAME"]?>"
                                         title="<?=$arItem["NAME"]?>"
                                         class="news-slide__image-wrapper--image"
                                    >
                                <?endif?>
                            <?endif;?>

                        </div>
                        <div class="news-slide__description news-description">
                            <div class="news-description__title-wrapper">
                                <?if($arParams["DISPLAY_NAME"]!="N" && $arItem["NAME"]):?>
                                    <span class="news-description__title-wrapper--title">
                                        <?=$arItem["NAME"]?>
                                    </span>
                                <?endif;?>
                            </div>
                            <div class="news-description__text-wrapper">
                                <?if($arParams["DISPLAY_PREVIEW_TEXT"]!="N" && $arItem["PREVIEW_TEXT"]):?>
                                    <span class="news-description__text-wrapper--text">
                                        <?=$arItem["PREVIEW_TEXT"]?>
                                    </span>
                                <?endif;?>
                            </div>
                            <?if(!$arParams["HIDE_LINK_WHEN_NO_DETAIL"] || ($arItem["DETAIL_TEXT"] && $arResult["USER_HAVE_ACCESS"])):?>
                                <div class="news-description__button-wrapper">
                                    <button class="news-description__button-wrapper--button">
                                        <?=Loc::getMessage("SOTBIT_NEWS_SIMPLE_MORE_TEXT");?>
                                    </button>
                                </div>
                            <?endif;?>
                        </div>
                    </div>
                </a>
            <?endforeach;?>
        </div>
        <div class="btn-slider-main <?=$sliderButtons?> btn-slider-main--prev btn-slider-main--disabled"></div>
        <div class="btn-slider-main <?=$sliderButtons?> btn-slider-main--next btn-slider-main--disabled"></div>
    </div>
</div>

<script>
    const newsSlider_<?=$idItem?> = new CreateSlider({
        sliderContainer: '#news-slider_<?=$idItem?>',
        sizeSliderInit: 'all',
        spaceBetween768: 20,
        spaceBetween1024: 32,

    });
</script>