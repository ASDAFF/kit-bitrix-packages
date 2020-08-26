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

use Bitrix\Main\Localization\Loc;
Loc::loadMessages(__FILE__);
?>

<div class="puzzle_block">
    <p class="puzzle_block__title fonts__middle_title">
        <?=$arParams["BLOCK_NAME"]?>
        <a href="<?=($arResult["ITEMS"][0]["LIST_PAGE_URL"]) ? $arResult["ITEMS"][0]["LIST_PAGE_URL"] : $arParams["LINK_TO_THE_FULL_LIST"]?>" class="puzzle_block__link fonts__small_text">
            <?=Loc::getMessage("SOTBIT_SERVICES_SIMPLE_LINK_TEXT");?>
            <i class="fas fa-angle-double-right"></i>
        </a>
    </p>

    <div class="service_block">
        <div class="row">
            <?foreach(array_slice($arResult["ITEMS"], 0, 4) as $arItem):?>
                <?
                $this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
                $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
                ?>
                <div class="col-xl-3 col-lg-4 col-md-4 col-sm-6 col-12 mb-3 mt-3 service_block__item" id="<?=$this->GetEditAreaId($arItem['ID']);?>">
                    <?if($arParams["DISPLAY_PICTURE"]!="N"):?>
                        <?if(!$arParams["HIDE_LINK_WHEN_NO_DETAIL"] || ($arItem["DETAIL_TEXT"] && $arResult["USER_HAVE_ACCESS"])):?>
                            <a href="<?=$arItem["DETAIL_PAGE_URL"]?>" class="service_block__item_link">
                        <?endif;?>

                            <?if($arItem["PREVIEW_PICTURE"]):?>
                                <img src="<?=$arItem["PREVIEW_PICTURE"]["SRC"]?>"
                                    width="<?=$arItem["PREVIEW_PICTURE"]["WIDTH"]?>"
                                    height="<?=$arItem["PREVIEW_PICTURE"]["HEIGHT"]?>"
                                    alt="<?=$arItem["PREVIEW_PICTURE"]["ALT"]?>"
                                    title="<?=$arItem["PREVIEW_PICTURE"]["TITLE"]?>"
                                >
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
                    <div class="service_block__content">
                        <?if($arParams["DISPLAY_NAME"]!="N" && $arItem["NAME"]):?>
                            <?if(!$arParams["HIDE_LINK_WHEN_NO_DETAIL"] || ($arItem["DETAIL_TEXT"] && $arResult["USER_HAVE_ACCESS"])):?>
                                <a href="<?=$arItem["DETAIL_PAGE_URL"]?>" class="service_block__content_title fonts__main_text">
                                    <?=$arItem["NAME"]?>
                                </a>
                            <?else:?>
                                <p class="service_block__content_title fonts__main_text"><?=$arItem["NAME"]?></p>
                            <?endif;?>
                        <?endif;?>
                        <?if($arParams["DISPLAY_PREVIEW_TEXT"]!="N" && $arItem["PREVIEW_TEXT"]):?>
                            <p class="service_block__content_comment fonts__small_text"><?=$arItem["PREVIEW_TEXT"];?></p>
                        <?endif;?>
                        <a class="main_url main_btn sweep-to-right" href="<?=$arItem["DETAIL_PAGE_URL"]?>"><?=Loc::getMessage("SOTBIT_SERVICES_BUTTON_TEXT");?>
                            <i class="fas fa-angle-double-right"></i>
                        </a>
                    </div>
                </div>
            <?endforeach;?>
        </div>
    </div>

</div>
