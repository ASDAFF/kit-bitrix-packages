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
\Bitrix\Main\Loader::includeModule('sotbit.origami');
$date = new \Sotbit\Origami\Helper\Date();
$hoverClass = implode(" ", Config::getArray("HOVER_EFFECT"));
$lazyLoad = (Config::get('LAZY_LOAD') == "Y");
?>

<div class="promotion-block__wrapper main-container">
    <p class="puzzle_block__title fonts__middle_title">
        <?=$arParams["BLOCK_NAME"]?>
        <a href="<?=($arResult["ITEMS"][0]["LIST_PAGE_URL"]) ? $arResult["ITEMS"][0]["LIST_PAGE_URL"] : $arParams["LINK_TO_THE_FULL_LIST"]?>" title="<?=Loc::getMessage("SOTBIT_PROMOTIONS_VERTICAL_LINK_TEXT");?>" class="puzzle_block__link fonts__small_text">
            <?=Loc::getMessage("SOTBIT_PROMOTIONS_VERTICAL_LINK_TEXT");?>
            <i class="icon-nav_1"></i>
        </a>
    </p>

    <div class="promotion_block">
        <?foreach(array_slice($arResult["ITEMS"], 0, 5) as $arItem):
            $blockID = $this->randString();
        ?>
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
            <div class="promotion_block__content" id="<?=$this->GetEditAreaId($arItem['ID']);?>" data-timer="timerID_<?=$blockID?>">
                <?if($arParams["DISPLAY_PICTURE"]!="N"):?>
                    <?if(!$arParams["HIDE_LINK_WHEN_NO_DETAIL"] || ($arItem["DETAIL_TEXT"] && $arResult["USER_HAVE_ACCESS"])):?>
                        <a href="<?=$arItem["DETAIL_PAGE_URL"]?>" class="promotion_block__content_img-block_link <?=$hoverClass?>" title="<?if(isset($arItem["IPROPERTY_VALUES"]["ELEMENT_META_TITLE"])) echo $arItem["IPROPERTY_VALUES"]["ELEMENT_META_TITLE"]; else echo $arItem["NAME"]?>">
                    <?endif;?>

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
                            <img src="<?=$templateFolder?>/images/empty_v.jpg"
                                alt="<?=$arItem["NAME"]?>"
                                title="<?=$arItem["NAME"]?>"
                            >
                        <?endif?>

                    <?if(!$arParams["HIDE_LINK_WHEN_NO_DETAIL"] || ($arItem["DETAIL_TEXT"] && $arResult["USER_HAVE_ACCESS"])):?>
                        </a>
                    <?endif;?>
                <?endif?>
                <?if($arParams["DISPLAY_DATE"]!="N"):?>
                    <?if($arItem["DISPLAY_PROPERTIES"]["PERIOD"]["VALUE"]):?>
                        <div class="promotion_block__content_date fonts__middle_comment">
                            <?=$arItem["DISPLAY_PROPERTIES"]["PERIOD"]["VALUE"];?>
                        </div>
                    <?elseif($arItem["DATE_ACTIVE_FROM"]):?>
                        <div class="promotion_block__content_date fonts__middle_comment">
                            <?=$date->dateFormatFromTo($arItem["DATE_ACTIVE_FROM"], $arItem["DATE_ACTIVE_TO"]);?>
                        </div>
                    <?endif?>
                <?endif?>
                <?if($arParams["DISPLAY_NAME"]!="N" && $arItem["NAME"]):?>
                    <?if(!$arParams["HIDE_LINK_WHEN_NO_DETAIL"] || ($arItem["DETAIL_TEXT"] && $arResult["USER_HAVE_ACCESS"])):?>
                        <div class="promotion_block__content_name">
                            <a href="<?=$arItem["DETAIL_PAGE_URL"]?>" title="<?if(isset($arItem["IPROPERTY_VALUES"]["ELEMENT_META_TITLE"])) echo $arItem["IPROPERTY_VALUES"]["ELEMENT_META_TITLE"]; else echo $arItem["NAME"]?>" class="promotion_block__content_name_title fonts__main_text">
                                <?=$arItem["NAME"]?>
                            </a>
                        </div>
                    <?else:?>
                        <div class="promotion_block__content_name">
                            <?=$arItem["NAME"]?>
                        </div>
                    <?endif;?>
                <?endif;?>
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
            </div>
        <?endforeach;?>
    </div>

</div>
