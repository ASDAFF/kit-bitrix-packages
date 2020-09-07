<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}
$this->createFrame()->begin();

use Sotbit\Origami\Helper\Config;
use Bitrix\Main\Localization\Loc;

$hoverClass = implode(" ", Config::getArray("HOVER_EFFECT"));
?>

<div class="stocks">
    <div class="stocks__content-wrapper">
        <div class="stocks__content">
            <? foreach ($arResult["ITEMS"] as $i => $arItem):
            $blockID = $this->randString();?>
                <?
                $this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'],
                    CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
                $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'],
                    CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"),
                    ["CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')]);
                ?>
                <div class="stocks__item-container item-container" id="<?= $this->GetEditAreaId($arItem['ID']); ?>">
                    <a href="<?= $arItem["DETAIL_PAGE_URL"] ?>" class="item-container__image-wrapper <?=$hoverClass?>">
                        <?if($arItem["DETAIL_PICTURE"]["SRC"]):?>
                            <img class="item-container__image" src="<?= $arItem["DETAIL_PICTURE"]["SRC"] ?>" width="<?= $arItem["PREVIEW_PICTURE"]["WIDTH"] ?>" height="<?= $arItem["PREVIEW_PICTURE"]["HEIGHT"] ?>" alt="<?= $arItem["PREVIEW_PICTURE"]["ALT"] ?>" title="<?= $arItem["PREVIEW_PICTURE"]["TITLE"] ?>">
                        <?endif;?>
                        <?if ($arParams["DISPLAY_DATE"] != "N" && ($arItem["PRINT_DATE_ACTIVE_FROM"] || $arItem["PRINT_DATE_ACTIVE_TO"])):?>
                            <div class="item-container__time-period">

                               <?if($arItem["PRINT_DATE_ACTIVE_FROM"] && $arItem["PRINT_DATE_ACTIVE_TO"]){
                                    if($arItem["PRINT_DATE_ACTIVE_FROM"]["MM"] == $arItem["PRINT_DATE_ACTIVE_TO"]["MM"] && $arItem["PRINT_DATE_ACTIVE_FROM"]["YYYY"] == $arItem["PRINT_DATE_ACTIVE_TO"]["YYYY"]){
                                        ?>
                                        <span><?=preg_replace("/^0/", "", $arItem["PRINT_DATE_ACTIVE_FROM"]["DD"]).' - '.preg_replace("/^0/", "", $arItem["PRINT_DATE_ACTIVE_TO"]["DD"]).'&nbsp;'.ToLower(FormatDate("F", MakeTimeStamp($arItem["DATE_ACTIVE_TO"]))).'&nbsp;'.$arItem["PRINT_DATE_ACTIVE_TO"]["YYYY"]?></span>
                                        <?
                                    }
                                    elseif ($arItem["PRINT_DATE_ACTIVE_FROM"]["YYYY"] == $arItem["PRINT_DATE_ACTIVE_TO"]["YYYY"]){
                                        ?>
                                        <span><?=preg_replace("/^0/", "", $arItem["PRINT_DATE_ACTIVE_FROM"]["DD"]).'&nbsp;'.ToLower(FormatDate("F", MakeTimeStamp($arItem["DATE_ACTIVE_FROM"]))).' - '.preg_replace("/^0/", "", $arItem["PRINT_DATE_ACTIVE_TO"]["DD"]).'&nbsp;'.ToLower(FormatDate("F", MakeTimeStamp($arItem["DATE_ACTIVE_TO"]))).'&nbsp;'.$arItem["PRINT_DATE_ACTIVE_TO"]["YYYY"]?></span>
                                        <?
                                    }
                                    else {
                                        ?>
                                        <span><?=preg_replace("/^0/", "", $arItem["PRINT_DATE_ACTIVE_FROM"]["DD"]).'&nbsp;'.ToLower(FormatDate("F", MakeTimeStamp($arItem["DATE_ACTIVE_FROM"]))).'&nbsp;'.$arItem["PRINT_DATE_ACTIVE_FROM"]["YYYY"].' - '.preg_replace("/^0/", "", $arItem["PRINT_DATE_ACTIVE_TO"]["DD"]).'&nbsp;'.ToLower(FormatDate("F", MakeTimeStamp($arItem["DATE_ACTIVE_TO"]))).'&nbsp;'.$arItem["PRINT_DATE_ACTIVE_TO"]["YYYY"]?></span>
                                        <?
                                    }
                                }
                                elseif($arItem["PRINT_DATE_ACTIVE_FROM"]){
                                    ?>
                                    <span><?=Loc::GetMessage("ORIGAMI_PROMO_FROM").'&nbsp;'.preg_replace("/^0/", "", $arItem["PRINT_DATE_ACTIVE_FROM"]["DD"]).'&nbsp;'.ToLower(FormatDate("F", MakeTimeStamp($arItem["DATE_ACTIVE_FROM"]))).'&nbsp;'.$arItem["PRINT_DATE_ACTIVE_FROM"]["YYYY"]?></span>
                                    <?
                                }
                                elseif($arItem["PRINT_DATE_ACTIVE_TO"]){
                                    ?>
                                    <span><?=Loc::GetMessage("UNTIL").'&nbsp;'.preg_replace("/^0/", "", $arItem["PRINT_DATE_ACTIVE_TO"]["DD"]).'&nbsp;'.ToLower(FormatDate("F", MakeTimeStamp($arItem["DATE_ACTIVE_TO"]))).'&nbsp;'.$arItem["PRINT_DATE_ACTIVE_TO"]["YYYY"]?></span>
                                    <?
                                } ?>
                            </div>
                        <?endif;?>
                    </a>
                    <div class="item-container__text-wrapper">
                        <div class="item-container__overflow-hider">
                            <a href="<?= $arItem["DETAIL_PAGE_URL"] ?>" class="item-container__title main-color_on-hover">
                                <span><?= $arItem['NAME']?></span>
                            </a>
                            <div class="item-container__description">
                                <? if ($arParams["DISPLAY_PREVIEW_TEXT"] != "N" && $arItem["PREVIEW_TEXT"]): ?>
                                    <span><?=$arItem["PREVIEW_TEXT"]?></span>
                                <?endif;?>
                            </div>
                        </div>
                    </div>
                </div>
            <?endforeach;?>
        </div>
    </div>
</div>
<?if ($arParams["DISPLAY_BOTTOM_PAGER"]): ?>
    <div class="promotion_list__nav">
        <?= $arResult["NAV_STRING"] ?>
    </div>
<? endif; ?>

