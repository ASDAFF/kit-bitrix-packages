<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}
$this->setFrameMode(true);
if ($arResult["ITEMS"]) {
    ?>
    <div class="blog__sidebar-item">
        <div class="blog__sidebar-item-title">
            <?= GetMessage('NEWS_LIST_TITLE') ?>
        </div>
        <div class="blog__sidebar-item-video">
            <? foreach ($arResult["ITEMS"] as $i => $arItem):
                $this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'],
                    CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
                $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'],
                    CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"),
                    ["CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')]);
                ?>
                <a href="<?= $arItem['DETAIL_PAGE_URL'] ?>"
                   class="blog__sidebar-item-video-title"
                   id="<?= $this->GetEditAreaId($arItem['ID']); ?>">
                    <span><?= $arItem['NAME'] ?></span>
                </a>
                <div class="blog_popular_list__item_date"
                     style="display: none"><?= $arItem['DISPLAY_ACTIVE_FROM'] ?></div>
            <? endforeach; ?>
        </div>
    </div>
    <?
}
?>
