<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}
$this->setFrameMode(true);
if ($arResult["ITEMS"]) {
    ?>
    <div class="stocks__sidebar-item">
        <div class="stocks__sidebar-item-title">
            <?= GetMessage('STOCKS_LIST_TITLE') ?>
        </div>

        <div class="stocks__sidebar-item-video">
            <? foreach ($arResult["ITEMS"] as $i => $arItem):
                $this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'],
                    CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
                $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'],
                    CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"),
                    ["CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')]);
                ?>
                <a href="<?= $arItem['DETAIL_PAGE_URL'] ?>" class="stocks__sidebar-item-video-title" id="<?= $this->GetEditAreaId($arItem['ID']); ?>">
                    <span><?= $arItem['NAME'] ?></span>
                </a>
            <?endforeach;?>
        </div>
    </div>
    <?
}
?>