<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}
$this->setFrameMode(true);
if ($arResult["ITEMS"]) {
    ?>
    <div class="blog_popular_list">
        <p class="blog_popular_list__title">
            <?= GetMessage('NEWS_LIST_TITLE') ?>
        </p>
        <? foreach ($arResult["ITEMS"] as $i => $arItem):
            $this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'],
                CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
            $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'],
                CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"),
                ["CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')]);
            ?>
            <div class="blog_popular_list__item" id="<?= $this->GetEditAreaId($arItem['ID']); ?>">
                <div class="blog_popular_list__item_title">
                    <a href="<?= $arItem['DETAIL_PAGE_URL'] ?>"><?=
                        $arItem['NAME'] ?></a></div>
                <div class="blog_popular_list__item_date"><?= $arItem['DISPLAY_ACTIVE_FROM'] ?></div>
            </div>
        <?endforeach;
        ?>
    </div>
    <?
}
?>
