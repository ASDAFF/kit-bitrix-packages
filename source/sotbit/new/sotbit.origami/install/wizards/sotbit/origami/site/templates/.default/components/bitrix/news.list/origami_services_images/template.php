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
            <?=Loc::getMessage("SOTBIT_SERVICES_IMAGES_LINK_TEXT");?>
            <i class="fas fa-angle-double-right"></i>
        </a>
    </p>

    <div class="service_block">
        <div class="row">
            <?foreach(array_slice($arResult["ITEMS"], 0, 6) as $arItem):?>
                <?
                $this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
                $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
                ?>
                <div class="col-xl-4 col-lg-6 col-md-6 col-sm-6 col-12 mb-3 mt-3" id="<?=$this->GetEditAreaId($arItem['ID']);?>">

                <?if(!$arParams["HIDE_LINK_WHEN_NO_DETAIL"] || ($arItem["DETAIL_TEXT"] && $arResult["USER_HAVE_ACCESS"])):?>
                    <a href="<?=$arItem["DETAIL_PAGE_URL"]?>" class="service_block__variant_two">
                <?else:?>
                    <div class="service_block__variant_two">
                <?endif;?>
                        <div class="service_block__variant_two__img_block">
                            <?if($arParams["DISPLAY_PICTURE"]!="N"):?>
                                <?if($arItem["DETAIL_PICTURE"]):?>
                                    <div class="service_block__variant_two_link">
                                        <img class="service_block__variant_two__img"
                                            src="<?=$arItem["DETAIL_PICTURE"]["SRC"]?>"
                                            width="<?=$arItem["DETAIL_PICTURE"]["WIDTH"]?>"
                                            height="<?=$arItem["DETAIL_PICTURE"]["HEIGHT"]?>"
                                            alt="<?=$arItem["DETAIL_PICTURE"]["ALT"]?>"
                                            title="<?=$arItem["DETAIL_PICTURE"]["TITLE"]?>"
                                        >
                                    </div>
                                <?else:?>
                                    <div class="service_block__variant_two_link">
                                        <img class="service_block__variant_two__img"
                                            src="<?=$templateFolder?>/images/empty_h.jpg"
                                            alt="<?=$arItem["NAME"]?>"
                                            title="<?=$arItem["NAME"]?>"
                                        >
                                    </div>
                                <?endif?>
                            <?endif?>
                        </div>
                        <div class="service_block__variant_two__content">
                            <?if($arParams["DISPLAY_NAME"]!="N" && $arItem["NAME"]):?>
                                <p class="service_block__variant_two_title fonts__main_text"><?=$arItem["NAME"]?></p>
                            <?endif;?>
                            <?if($arParams["DISPLAY_PREVIEW_TEXT"]!="N" && $arItem["PREVIEW_TEXT"]):?>
                                <p class="service_block__variant_two_comment fonts__small_text"><?=$arItem["PREVIEW_TEXT"];?></p>
                            <?endif;?>
                        </div>
                <?if(!$arParams["HIDE_LINK_WHEN_NO_DETAIL"] || ($arItem["DETAIL_TEXT"] && $arResult["USER_HAVE_ACCESS"])):?>
                    </a>
                <?else:?>
                    </div>
                <?endif;?>

                </div>
            <?endforeach;?>
        </div>
    </div>

</div>