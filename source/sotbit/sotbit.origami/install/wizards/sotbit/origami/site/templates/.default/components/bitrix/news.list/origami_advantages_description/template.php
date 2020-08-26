<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

$this->createFrame()->begin();
?>
<div class="advantage_variant_one">
    <div class="block_main_advantage_variant_one main-container">
        <?php foreach ($arResult["ITEMS"] as $arItem): ?>
            <?php
            $this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'],
                CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
            $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'],
                CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"),
                ["CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')]);
            ?>
            <div class="block_main_advantage_variant"
                 id="<?= $this->GetEditAreaId($arItem['ID']); ?>">
                <?php
                if ($arItem['PROPERTIES']['URL']['VALUE'])
                {
                ?>
                <a class="block_main_advantage_variant__item_link"
                   href="<?= $arItem['PROPERTIES']['URL']['VALUE'] ?>">
                    <? } ?>
                    <div class="block_main_advantage_variant__item">
                        <? if ($arParams["DISPLAY_PICTURE"] != "N"): ?>
                            <? if ($arItem["PREVIEW_PICTURE"]): ?>
                                <div class="block_main_advantage_variant__icons">
                                    <img src="<?= $arItem["PREVIEW_PICTURE"]["SRC"] ?>"
                                         width="<?= $arItem["PREVIEW_PICTURE"]["WIDTH"] ?>"
                                         height="<?= $arItem["PREVIEW_PICTURE"]["HEIGHT"] ?>"
                                         alt="<?= $arItem["PREVIEW_PICTURE"]["ALT"] ?>"
                                         title="<?= $arItem["PREVIEW_PICTURE"]["TITLE"] ?>"
                                    >
                                </div>
                            <? else: ?>
                                <div class="block_main_advantage_variant__icons">
                                    <img src="<?= $templateFolder ?>/images/empty_h.jpg"
                                         alt="<?= $arItem["NAME"] ?>"
                                         title="<?= $arItem["NAME"] ?>"
                                    >
                                </div>
                            <? endif ?>
                        <? endif ?>
                        <div class="block_main_advantage_variant__content">
                            <? if ($arParams["DISPLAY_NAME"] != "N"
                                && $arItem["NAME"]
                            ): ?>
                                <p class="block_main_advantage_variant__title fonts__small_weight_title">
                                    <?= $arItem["NAME"] ?>
                                </p>
                            <? endif; ?>
                            <? if ($arParams["DISPLAY_PREVIEW_TEXT"] != "N"
                                && $arItem["PREVIEW_TEXT"]
                            ): ?>
                                <p class="block_main_advantage_variant__comment fonts__small_text">
                                    <?= $arItem["PREVIEW_TEXT"]; ?>
                                </p>
                            <? endif; ?>
                        </div>
                    </div>
                    <?
                    if ($arItem['PROPERTIES']['URL']['VALUE'])
                    {
                    ?>
                </a>
            <? } ?>
            </div>
        <? endforeach; ?>
    </div>
</div>