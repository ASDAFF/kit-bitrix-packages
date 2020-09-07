<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}
$this->setFrameMode(true);

$strSectionEdit = CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "SECTION_EDIT");
$strSectionDelete = CIBlock::GetArrayByID($arParams["IBLOCK_ID"],
    "SECTION_DELETE");
$arSectionDeleteParams
    = ["CONFIRM" => GetMessage('CT_BCSL_ELEMENT_DELETE_CONFIRM')];
?>

<div class="popular_category_block">
    <div class="row">
        <?
        foreach ($arResult["SECTIONS"] as $arSection) {
            $this->AddEditAction($arSection['ID'], $arSection['EDIT_LINK'],
                CIBlock::GetArrayByID($arSection["IBLOCK_ID"], "SECTION_EDIT"));
            $this->AddDeleteAction($arSection['ID'], $arSection['DELETE_LINK'],
                CIBlock::GetArrayByID($arSection["IBLOCK_ID"],
                    "SECTION_DELETE"),
                ["CONFIRM" => GetMessage('CT_BCSL_ELEMENT_DELETE_CONFIRM')]);
            ?>

            <div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-12 mb-3 mt-3 popular_category_block_variant__canvas"
                 id="<?= $this->GetEditAreaId($arSection['ID']); ?>">
                <a class="popular_category_block_variant__canvas_link"
                   href="<?= $arSection["SECTION_PAGE_URL"] ?>">
                    <img class="popular_category_block_variant__canvas_img"
                         src="<?= $arSection["PICTURE"]["SRC"] ?>"
                         alt="<?= $arSection["PICTURE"]["ALT"] ?>">
                </a>
                <div class="popular_category_block_variant__content">
                    <div class="popular_category_block_variant__canvas_title fonts__main_text">
                        <?= $arSection["NAME"] ?>
                    </div>


                    <?
                    if ($arSection["CHILD_CATEGORIES"]):?>
                        <div class="popular_category_block_variant__tags">
                            <?
                            foreach (
                                $arSection["CHILD_CATEGORIES"] as $key => $child
                            ) {
                                $this->AddEditAction($key, $child['EDIT_LINK'],
                                    CIBlock::GetArrayByID($child["IBLOCK_ID"],
                                        "SECTION_EDIT"));
                                $this->AddDeleteAction($key,
                                    $child['DELETE_LINK'],
                                    CIBlock::GetArrayByID($child["IBLOCK_ID"],
                                        "SECTION_DELETE"),
                                    ["CONFIRM" => GetMessage('CT_BCSL_ELEMENT_DELETE_CONFIRM')]);
                                ?>
                                <a href="<?= $child["SECTION_PAGE_URL"] ?>"
                                   class="popular_category_block_variant__tags_item fonts__middle_comment"
                                   id="<?= $this->GetEditAreaId($key); ?>">
                                    <?
                                    echo $child["NAME"];
                                    echo $arParams["COUNT_ELEMENTS"]
                                        ? "&nbsp;<span>".$child["ELEMENT_CNT"]
                                        ."</span>" : "";
                                    ?>
                                </a>
                                <?
                            }
                            ?>
                        </div>
                    <?endif ?>

                    <div class="popular_category_block_variant__comment">
                        <?= $arSection["DESCRIPTION"] ?>
                    </div>
                </div>
            </div>

            <?
        }
        ?>
    </div>
</div>