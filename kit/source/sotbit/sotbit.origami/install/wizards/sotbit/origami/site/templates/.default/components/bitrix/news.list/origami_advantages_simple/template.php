<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

$this->createFrame()->begin();
?>

<div class="advantage">
    <div class="block_main_advantage main-container">
        <? foreach ($arResult["ITEMS"] as $arItem): ?>
            <?
            $this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'],
                CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
            $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'],
                CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"),
                ["CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')]);
            ?>
            <div class="block_main_advantage__one"
                 id="<?= $this->GetEditAreaId($arItem['ID']); ?>">
                <?
                if ($arItem['PROPERTIES']['URL']['VALUE'])
                {
                ?>
                <a class="block_main_advantage_item_link"
                   href="<?= $arItem['PROPERTIES']['URL']['VALUE'] ?>">
                    <? } ?>
                    <div class="block_main_advantage_item">
                        <? if ($arParams["DISPLAY_PICTURE"] != "N"): ?>
                            <? if ($arItem["PREVIEW_PICTURE"]): ?>
                                <div class="block_main_advantage_icons">
                                    <img src="<?= $arItem["PREVIEW_PICTURE"]["SRC"] ?>"
                                         width="<?= $arItem["PREVIEW_PICTURE"]["WIDTH"] ?>"
                                         height="<?= $arItem["PREVIEW_PICTURE"]["HEIGHT"] ?>"
                                         alt="<?= $arItem["PREVIEW_PICTURE"]["ALT"] ?>"
                                         title="<?= $arItem["PREVIEW_PICTURE"]["TITLE"] ?>"
                                    >
                                </div>
                            <? else: ?>
                                <div class="block_main_advantage_icons">
                                    <img src="<?= $templateFolder ?>/images/empty_h.jpg"
                                         alt="<?= $arItem["NAME"] ?>"
                                         title="<?= $arItem["NAME"] ?>"
                                    >
                                </div>
                            <? endif ?>
                        <? endif ?>
                        <? if ($arParams["DISPLAY_NAME"] != "N"
                            && $arItem["NAME"]
                        ): ?>
                            <div class="block_main_advantage_title fonts__small_weight_title">
                                <?= $arItem["NAME"] ?>
                            </div>
                        <? endif; ?>
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
