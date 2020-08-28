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
$date = new \Sotbit\Origami\Helper\Date();
$hoverClass = implode(" ", Config::getArray("HOVER_EFFECT"));
$lazyLoad = (Config::get('LAZY_LOAD') == "Y");
$sliderButtons = "";
if (\Sotbit\Origami\Helper\Config::get('SLIDER_BUTTONS') == 'square') {
    $sliderButtons = "btn-slider-main--one";
} else if (\Sotbit\Origami\Helper\Config::get('SLIDER_BUTTONS') == 'circle') {
    $sliderButtons = "btn-slider-main--two";
}
?>
<? $idItem = \Bitrix\Main\Security\Random::getString(5);?>


<div class="size puzzle_block main-container">
    <div class="stocks-block">
        <p class="puzzle_block__title fonts__middle_title">
            <?=$arParams["BLOCK_NAME"]?>
            <a href="<?=($arResult["ITEMS"][0]["LIST_PAGE_URL"]) ? $arResult["ITEMS"][0]["LIST_PAGE_URL"] : $arParams["LINK_TO_THE_FULL_LIST"]?>" class="puzzle_block__link fonts__small_text" title="<?=Loc::getMessage("SOTBIT_PROMOTIONS_STOCKS_LINK_TEXT");?>">
                <?=Loc::getMessage("SOTBIT_PROMOTIONS_STOCKS_LINK_TEXT");?>
            </a>
        </p>

        <div id="stocks_slider_<?=$idItem?>" class="stocks-block__slider-wrapper stocks-slider swiper-container">
            <div class="stocks-slider__wrapper swiper-wrapper">
                <?foreach ($arResult["ITEMS"] as $arItem):?><?
                    $blockID = $this->randString();
                    $this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
                    $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));

                    if($lazyLoad)
                    {
                        $strLazyLoad = 'src="'.SITE_TEMPLATE_PATH.'/assets/img/loader_lazy.svg" data-src="'.$arItem["DETAIL_PICTURE"]["SRC"].'" class="lazy"';
                    }else{
                        $strLazyLoad = 'src="'.$arItem["PREVIEW_PICTURE"]["SRC"].'"';
                    }

                    if($arParams["DISPLAY_NAME"]!="N" && $arItem["NAME"]) {
                        if(!$arParams["HIDE_LINK_WHEN_NO_DETAIL"] || ($arItem["DETAIL_TEXT"] && $arResult["USER_HAVE_ACCESS"]))
                            $arHref = 'href="' . $arItem["DETAIL_PAGE_URL"] . '"';
                        else
                            $arHref = '';
                    }
                    ?>
                    <a class="stocks-slider__wrapper--slide stocks-slide main-color_on-hover swiper-slide" <?=$arHref?>  id="<?=$this->GetEditAreaId($arItem['ID']);?>" data-timer="timerID_<?=$blockID?>">
                        <div class="stocks-slide__image-wrapper <?=$hoverClass?>">
                            <?if($arParams["DISPLAY_PICTURE"]!="N"):?>
                                <?if($arItem["DETAIL_PICTURE"]):?>
                                    <img <?=$strLazyLoad?>
                                        width="<?=$arItem["DETAIL_PICTURE"]["WIDTH"]?>"
                                        height="<?=$arItem["DETAIL_PICTURE"]["HEIGHT"]?>"
                                        alt="<?=$arItem["DETAIL_PICTURE"]["ALT"]?>"
                                        title="<?=$arItem["DETAIL_PICTURE"]["TITLE"]?>"
                                    >
                                    <?if($lazyLoad):?>
                                        <span class="loader-lazy"></span>
                                    <?endif;?>
                                <?else:?>
                                    <img src="<?=$templateFolder?>/images/empty_v.jpg"
                                         alt="<?=$arItem["NAME"]?>"
                                         title="<?=$arItem["NAME"]?>"
                                    >
                                <?endif?>
                            <?endif;?>
                        </div>
                        <div class="stocks-slide__description-wrapper">
                            <?if($arParams["DISPLAY_DATE"]!="N"):?>
                                <?if($arItem["DISPLAY_PROPERTIES"]["PERIOD"]["VALUE"]):?>
                                    <div>
                                        <span class="stocks-slide__description-wrapper--date"> <?=$arItem["DISPLAY_PROPERTIES"]["PERIOD"]["VALUE"];?></span>
                                    </div>
                                <?elseif($arItem["DATE_ACTIVE_FROM"]):?>
                                    <div>
                                        <span class="stocks-slide__description-wrapper--date"><?=$date->dateFormatFromTo($arItem["DATE_ACTIVE_FROM"], $arItem["DATE_ACTIVE_TO"]);?></span>
                                    </div>
                                <?endif?>
                            <?endif?>
                            <div class="stocks-slide__description-wrapper--title">
                                <span>
                                    <?=$arItem["NAME"]?>
                                </span>
                            </div>
                            <span class="stocks-slide__description-wrapper--description">
                                <?=$arItem['PREVIEW_TEXT']?>
                        </span>
                            <div class="stocks-slide__description-wrapper--link">
                                <span><?=Loc::getMessage('SOTBIT_PROMOTIONS_STOCKS_MORE_TEXT')?></span>
                            </div>
                        </div>
                        <?if (isset($arItem["DATE_ACTIVE_TO"]) && !empty($arItem["DATE_ACTIVE_TO"])): ?>
                            <?
                            if (Config::get('TIMER_PROMOTIONS') == 'Y') {
                                $APPLICATION->IncludeComponent(
                                    "sotbit:origami.timer",
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
                    </a>
                <?endforeach;?>
            </div>
            <div class="btn-slider-main <?=$sliderButtons?> btn-slider-main--prev btn-slider-main--disabled"></div>
            <div class="btn-slider-main <?=$sliderButtons?> btn-slider-main--next btn-slider-main--disabled"></div>
        </div>
    </div>
</div>


<script>
    const stocks_slider_<?=$idItem?> = new CreateSlider({
        sliderContainer: '#stocks_slider_<?=$idItem?>',
        sizeSliderInit: 'all'
    });
</script>



