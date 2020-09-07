<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}
$this->createFrame()->begin();

use Sotbit\Origami\Helper\Config;

$hoverClass = implode(" ", Config::getArray("HOVER_EFFECT"));
?>

<?if($arResult["ITEMS"]):?>

<div class="blog">
    <div class="blog__content-wrapper">
        <div class="blog__content">
            <? foreach ($arResult["ITEMS"] as $i => $arItem):
                $this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'],
                    CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
                $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'],
                    CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"),
                    ["CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')]);
                ?>
                    <div class="blog__item-container item-container" id="<?= $this->GetEditAreaId($arItem['ID']); ?>">
                        <a href="<?= $arItem["DETAIL_PAGE_URL"] ?>" class="item-container__image-wrapper <?=$hoverClass?>">
                            <?if($arItem["PREVIEW_PICTURE"]["SRC"])
                            {?>
                                <img class="item-container__image"
                                     src="<?= $arItem["PREVIEW_PICTURE"]["SRC"] ?>"
                                     width="<?=$arItem["PREVIEW_PICTURE"]["WIDTH"] ?>"
                                     height="<?=$arItem["PREVIEW_PICTURE"]["HEIGHT"] ?>"
                                     alt="<?=$arItem["PREVIEW_PICTURE"]["ALT"] ?>" title="<?=$arItem["PREVIEW_PICTURE"]["NAME"] ?>">
                                <?
                            }
                            else
                            {
                                ?>
                                <img class="item-container__image" src="<?= SITE_TEMPLATE_PATH.'/assets/img/empty_h.jpg' ?>"
                                     alt="<?= $arItem['NAME'] ?>"
                                     title="<?= $arItem['NAME'] ?>">
                                <?
                            }?>
                        </a>
                        <div class="item-container__text-wrapper">
                            <a href="<?= $arItem["DETAIL_PAGE_URL"] ?>" class="item-container__title main-color_on-hover">
                                <span><?=$arItem["NAME"]?></span>
                            </a>
                            <? if ($arParams["DISPLAY_PREVIEW_TEXT"] != "N" && $arItem["PREVIEW_TEXT"]): ?>
                                <div class="item-container__description">
                                    <?=$arItem["PREVIEW_TEXT"]?>
                                </div>
                            <?endif;?>
                            <div class="item-container__tags-wrapper blog-tags">
                                <?
                                if($arItem["SHOW_TAGS"]) {
                                    $APPLICATION->IncludeComponent(
                                        "bitrix:search.tags.cloud",
                                        "origami_tags_blog",
                                        Array(
                                            "CHECK_DATES" => "Y",
                                            "arrWHERE" => Array("iblock_" . $arParams["IBLOCK_TYPE"]),
                                            "arrFILTER" => Array("iblock_" . $arParams["IBLOCK_TYPE"]),
                                            "arrFILTER_iblock_" . $arParams["IBLOCK_TYPE"] => Array($arParams["IBLOCK_ID"]),
                                            "CACHE_TYPE" => $arParams["CACHE_TYPE"],
                                            "CACHE_TIME" => $arParams["CACHE_TIME"],
                                            "URL_SEARCH" => $arParams["SEF_URL_TEMPLATES"]["search"],

                                            "PAGE_ELEMENTS" => $arParams["TAGS_CLOUD_ELEMENTS"],
                                            "PERIOD_NEW_TAGS" => $arParams["PERIOD_NEW_TAGS"],
                                            "FONT_MAX" => $arParams["FONT_MAX"],
                                            "FONT_MIN" => $arParams["FONT_MIN"],
                                            "COLOR_NEW" => $arParams["COLOR_NEW"],
                                            "COLOR_OLD" => $arParams["COLOR_OLD"],
                                            "WIDTH" => $arParams["TAGS_CLOUD_WIDTH"],
                                            "ITEM_TAGS" => $arItem["SHOW_TAGS"],
                                        ),
                                        $component
                                    );
                                }
                                ?>
                            </div>
                            <? if ($arParams["DISPLAY_DATE"] != "N" && $arItem["DISPLAY_ACTIVE_FROM"]): ?>
                                <div class="item-container__content-info">
                                    <span class="item-container__content-info-date border-between">
                                         <svg class="item-container__content-info-icon" width="11" height="11">
                                            <use
                                                    xlink:href="/local/templates/sotbit_origami/assets/img/sprite.svg#icon_clock"></use>
                                        </svg>
                                        <span><?=$arItem['DISPLAY_ACTIVE_FROM']?></span>
                                    </span>
                                </div>
                            <?endif;?>
                        </div>
                    </div>
            <?endforeach;?>
        </div>
    </div>
</div>
<?endif;?>