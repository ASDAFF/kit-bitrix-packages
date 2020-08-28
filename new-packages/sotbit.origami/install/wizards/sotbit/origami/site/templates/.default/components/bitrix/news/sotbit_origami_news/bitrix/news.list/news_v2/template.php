<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}
$this->createFrame()->begin();

use Sotbit\Origami\Helper\Config;

$hoverClass = implode(" ", Config::getArray("HOVER_EFFECT"));
?>
<? if ($arResult["ITEMS"]): ?>
    <div class="news-list">
        <? foreach ($arResult["ITEMS"] as $i => $arItem):
            $this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'],
                CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
            $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'],
                CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"),
                ["CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')]);
            ?>
            <a href="<?= $arItem['DETAIL_PAGE_URL'] ?>" class="news-list__item" id="<?= $this->GetEditAreaId($arItem['ID']); ?>">
                <div class="news-list__image-wrapper <?=$hoverClass?>">
                    <?
                    if($arItem["PREVIEW_PICTURE"]["SRC"])
                    {
                        ?>
                        <img src="<?= $arItem["PREVIEW_PICTURE"]["SRC"]?>"
                             width="<?= $arItem["PREVIEW_PICTURE"]["WIDTH"]?>"
                             height="<?= $arItem["PREVIEW_PICTURE"]["HEIGHT"]?>"
                             alt="<?= $arItem["PREVIEW_PICTURE"]["ALT"]?><"
                             title="<?= $arItem["PREVIEW_PICTURE"]["TITLE"]?>"
                        >
                        <?
                    }
                    else
                    {
                        ?>
                        <img src="<?= SITE_TEMPLATE_PATH.'/assets/img/empty_h.jpg' ?>"
                             alt="<?= $arItem['NAME'] ?>"
                             title="<?= $arItem['NAME'] ?>"
                        >
                        <?
                    }
                    ?>
                </div>
                <div class="news-list__content-wrapper">
                    <div class="news-list__content-title">
                        <span><?= $arItem['NAME'] ?></span>
                    </div>
                    <div class="news-list__content-text">
                        <? if ($arParams["DISPLAY_PREVIEW_TEXT"] != "N" && $arItem["PREVIEW_TEXT"]): ?>
                            <span><?= $arItem["PREVIEW_TEXT"] ?></span>
                        <?endif; ?>
                    </div>
                    <div class="news-list__content-info">
                            <? if ($arParams["DISPLAY_DATE"] != "N" && $arItem["DISPLAY_ACTIVE_FROM"]): ?>
                                <span class="news-list__content-info-date">
                                     <svg class="news-list__content-info-icon" width="11" height="11">
                                        <use xlink:href="/local/templates/sotbit_origami/assets/img/sprite.svg#icon_clock"></use>
                                    </svg>
                                    <span><?= $arItem['DISPLAY_ACTIVE_FROM'] ?></span>
                                </span>
                            <?endif; ?>

                        <span class="news-list__content-info-comments border-between" style="display: none">
                                 <svg class="news-list__content-info-icon" width="12" height="11">
                                    <use xlink:href="/local/templates/sotbit_origami/assets/img/sprite.svg#icon_comment"></use>
                                </svg>
                                <span>100</span>
                            </span>

                        <span class="news-list__content-info-views" style="display: none">
                                 <svg class="news-list__content-info-icon" width="16" height="9">
                                    <use xlink:href="/local/templates/sotbit_origami/assets/img/sprite.svg#icon_eye"></use>
                                </svg>
                                <span>75</span>
                            </span>
                    </div>
                </div>
            </a>
        <? endforeach;
        if ($arParams["DISPLAY_BOTTOM_PAGER"]): ?>
            <div class="news_list__nav">
                <?= $arResult["NAV_STRING"] ?>
            </div>
        <? endif; ?>
    </div>
<? endif; ?>
